<?php
// Advanced Stats Plugin - API Endpoints
// This file handles API requests for the plugin using FPP's plugin API system

include_once("/opt/fpp/www/common.php");
$pluginName = "fpp-plugin-AdvancedStats";
$pluginConfigFile = $settings['configDirectory'] . "/plugin." . $pluginName;

/**
 * Register API endpoints for the plugin
 * FPP calls this function to discover available endpoints
 */
function getEndpointsfpppluginAdvancedStats() {
    $result = array();

    $ep = array(
        'method' => 'GET',
        'endpoint' => 'git-commits',
        'callback' => 'advancedStatsGetGitCommits');
    array_push($result, $ep);
    
    $ep = array(
        'method' => 'GET',
        'endpoint' => 'status',
        'callback' => 'advancedStatsGetStatus');
    array_push($result, $ep);
    
    return $result;
}


/**
 * Get git commit history for the plugin
 */
function advancedStatsGetGitCommits() {
    global $pluginName;
    $pluginDir = dirname(__FILE__);
    
    // Check if this is a git repository
    if (!is_dir($pluginDir . '/.git')) {
    return json(array(
        'success' => false,
        'message' => 'This plugin is not installed via git. Manual installation or version tracking not available.'
    ));
}    // Get last 20 commits
    $command = "cd " . escapeshellarg($pluginDir) . " && git log -20 --pretty=format:'%H|%an|%at|%s' 2>&1";
    $output = shell_exec($command);
    
    if (empty($output)) {
        return json(array(
            'success' => false,
            'message' => 'Unable to retrieve git history. Git may not be installed or accessible.'
        ));
    }
    
    // Check for git errors
    if (strpos($output, 'fatal:') !== false || strpos($output, 'not a git repository') !== false) {
        return json(array(
            'success' => false,
            'message' => 'Git repository error. This may be a manual installation.'
        ));
    }
    
    $commits = array();
    $lines = explode("\n", trim($output));
    
    foreach ($lines as $line) {
        if (empty($line)) continue;
        
        $parts = explode('|', $line, 4);
        if (count($parts) === 4) {
            $commits[] = array(
                'hash' => $parts[0],
                'author' => $parts[1],
                'date' => (int)$parts[2],
                'message' => $parts[3]
            );
        }
    }
    
    return json(array(
        'success' => true,
        'commits' => $commits,
        'count' => count($commits)
    ));
}

/**
 * Get plugin status
 */
function advancedStatsGetStatus() {
    global $pluginName;
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
    
    return json(array(
        'success' => true,
        'status' => 'active',
        'version' => $version,
        'isGitRepo' => $isGitRepo,
        'pluginDir' => basename($pluginDir)
    ));
}
?>
