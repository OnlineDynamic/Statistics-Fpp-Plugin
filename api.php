<?php
// Advanced Stats Plugin - API Endpoints
// This file handles API requests for the plugin

header('Content-Type: application/json');

// Get the requested action from the URL
// FPP passes the path segments via $_GET parameters
$action = '';

// Check for action in query parameters
if (isset($_GET['action'])) {
    $action = $_GET['action'];
}
// Check for command parameter (some FPP versions use this)
elseif (isset($_GET['command'])) {
    $action = $_GET['command'];
}
// Parse REQUEST_URI to extract the action after the plugin name
elseif (isset($_SERVER['REQUEST_URI'])) {
    // Extract action from URL like: /api/plugin/fpp-plugin-AdvancedStats/git-commits
    if (preg_match('#/api/plugin/[^/]+/(.+?)(?:\?|$)#', $_SERVER['REQUEST_URI'], $matches)) {
        $action = $matches[1];
    }
}
// Parse PATH_INFO for RESTful endpoints
elseif (isset($_SERVER['PATH_INFO'])) {
    $path = trim($_SERVER['PATH_INFO'], '/');
    $action = $path;
}

// Handle different API actions
switch ($action) {
    case 'git-commits':
        getGitCommits();
        break;
    
    case 'status':
        getPluginStatus();
        break;
    
    default:
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Unknown API endpoint: ' . $action
        ]);
        break;
}

/**
 * Get git commit history for the plugin
 */
function getGitCommits() {
    $pluginDir = dirname(__FILE__);
    
    // Check if this is a git repository
    if (!is_dir($pluginDir . '/.git')) {
        echo json_encode([
            'success' => false,
            'message' => 'This plugin is not installed via git. Manual installation or version tracking not available.'
        ]);
        return;
    }
    
    // Get last 20 commits
    $command = "cd " . escapeshellarg($pluginDir) . " && git log -20 --pretty=format:'%H|%an|%at|%s' 2>&1";
    $output = shell_exec($command);
    
    if (empty($output)) {
        echo json_encode([
            'success' => false,
            'message' => 'Unable to retrieve git history. Git may not be installed or accessible.'
        ]);
        return;
    }
    
    // Check for git errors
    if (strpos($output, 'fatal:') !== false || strpos($output, 'not a git repository') !== false) {
        echo json_encode([
            'success' => false,
            'message' => 'Git repository error. This may be a manual installation.'
        ]);
        return;
    }
    
    $commits = [];
    $lines = explode("\n", trim($output));
    
    foreach ($lines as $line) {
        if (empty($line)) continue;
        
        $parts = explode('|', $line, 4);
        if (count($parts) === 4) {
            $commits[] = [
                'hash' => $parts[0],
                'author' => $parts[1],
                'date' => (int)$parts[2],
                'message' => $parts[3]
            ];
        }
    }
    
    echo json_encode([
        'success' => true,
        'commits' => $commits,
        'count' => count($commits)
    ]);
}

/**
 * Get plugin status
 */
function getPluginStatus() {
    $pluginDir = dirname(__FILE__);
    $isGitRepo = is_dir($pluginDir . '/.git');
    
    // Get current version from pluginInfo.json
    $version = 'unknown';
    $pluginInfoFile = $pluginDir . '/pluginInfo.json';
    if (file_exists($pluginInfoFile)) {
        $pluginInfo = json_decode(file_get_contents($pluginInfoFile), true);
        if (isset($pluginInfo['name'])) {
            $version = $pluginInfo['name'];
        }
    }
    
    echo json_encode([
        'success' => true,
        'status' => 'active',
        'version' => $version,
        'isGitRepo' => $isGitRepo,
        'pluginDir' => basename($pluginDir)
    ]);
}
?>
