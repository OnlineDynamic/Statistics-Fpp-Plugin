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
    
    $ep = array(
        'method' => 'GET',
        'endpoint' => 'gpio-events',
        'callback' => 'advancedStatsGetGPIOEvents');
    array_push($result, $ep);
    
    $ep = array(
        'method' => 'GET',
        'endpoint' => 'sequence-history',
        'callback' => 'advancedStatsGetSequenceHistory');
    array_push($result, $ep);
    
    $ep = array(
        'method' => 'GET',
        'endpoint' => 'playlist-history',
        'callback' => 'advancedStatsGetPlaylistHistory');
    array_push($result, $ep);
    
    $ep = array(
        'method' => 'GET',
        'endpoint' => 'daily-stats',
        'callback' => 'advancedStatsGetDailyStats');
    array_push($result, $ep);
    
    $ep = array(
        'method' => 'GET',
        'endpoint' => 'dashboard-data',
        'callback' => 'advancedStatsGetDashboardData');
    array_push($result, $ep);
    
    $ep = array(
        'method' => 'GET',
        'endpoint' => 'backup-database',
        'callback' => 'advancedStatsBackupDatabase');
    array_push($result, $ep);
    
    $ep = array(
        'method' => 'POST',
        'endpoint' => 'restore-database',
        'callback' => 'advancedStatsRestoreDatabase');
    array_push($result, $ep);
    
    $ep = array(
        'method' => 'GET',
        'endpoint' => 'export-data',
        'callback' => 'advancedStatsExportData');
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

/**
 * Get GPIO events history
 */
function advancedStatsGetGPIOEvents() {
    $dbPath = '/home/fpp/media/config/plugin.fpp-plugin-AdvancedStats.db';
    
    if (!file_exists($dbPath)) {
        return json(array(
            'success' => false,
            'message' => 'Database not initialized'
        ));
    }
    
    try {
        $db = new SQLite3($dbPath);
        $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 100;
        $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
        $pin = isset($_GET['pin']) ? intval($_GET['pin']) : null;
        $startDate = isset($_GET['start_date']) ? $_GET['start_date'] : null;
        $endDate = isset($_GET['end_date']) ? $_GET['end_date'] : null;
        
        // Build WHERE clause for filters
        $where = array();
        $params = array();
        
        if ($pin !== null) {
            $where[] = 'pin_number = :pin';
            $params[':pin'] = $pin;
        }
        
        if ($startDate) {
            $startTimestamp = strtotime($startDate . ' 00:00:00');
            $where[] = 'timestamp >= :start_timestamp';
            $params[':start_timestamp'] = $startTimestamp;
        }
        
        if ($endDate) {
            $endTimestamp = strtotime($endDate . ' 23:59:59');
            $where[] = 'timestamp <= :end_timestamp';
            $params[':end_timestamp'] = $endTimestamp;
        }
        
        $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';
        
        // Get total count with filters
        $countQuery = "SELECT COUNT(*) FROM gpio_events $whereClause";
        $countStmt = $db->prepare($countQuery);
        foreach ($params as $key => $value) {
            $countStmt->bindValue($key, $value);
        }
        $totalCount = $countStmt->execute()->fetchArray(SQLITE3_NUM)[0];
        
        $query = "SELECT * FROM gpio_events $whereClause ORDER BY timestamp DESC LIMIT :limit OFFSET :offset";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':limit', $limit, SQLITE3_INTEGER);
        $stmt->bindValue(':offset', $offset, SQLITE3_INTEGER);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $result = $stmt->execute();
        $events = array();
        
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $events[] = $row;
        }
        
        $db->close();
        
        return json(array(
            'success' => true,
            'events' => $events,
            'count' => count($events),
            'total' => $totalCount,
            'offset' => $offset,
            'limit' => $limit
        ));
    } catch (Exception $e) {
        return json(array(
            'success' => false,
            'message' => 'Error retrieving GPIO events: ' . $e->getMessage()
        ));
    }
}

/**
 * Get sequence history
 */
function advancedStatsGetSequenceHistory() {
    $dbPath = '/home/fpp/media/config/plugin.fpp-plugin-AdvancedStats.db';
    
    if (!file_exists($dbPath)) {
        return json(array(
            'success' => false,
            'message' => 'Database not initialized'
        ));
    }
    
    try {
        $db = new SQLite3($dbPath);
        $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 100;
        $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
        $startDate = isset($_GET['start_date']) ? $_GET['start_date'] : null;
        $endDate = isset($_GET['end_date']) ? $_GET['end_date'] : null;
        $eventType = isset($_GET['event_type']) ? $_GET['event_type'] : null;
        
        // Build WHERE clause for filters
        $where = array();
        $params = array();
        
        if ($startDate) {
            $startTimestamp = strtotime($startDate . ' 00:00:00');
            $where[] = 'timestamp >= :start_timestamp';
            $params[':start_timestamp'] = $startTimestamp;
        }
        
        if ($endDate) {
            $endTimestamp = strtotime($endDate . ' 23:59:59');
            $where[] = 'timestamp <= :end_timestamp';
            $params[':end_timestamp'] = $endTimestamp;
        }
        
        if ($eventType && ($eventType === 'start' || $eventType === 'stop')) {
            $where[] = 'event_type = :event_type';
            $params[':event_type'] = $eventType;
        }
        
        $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';
        
        // Get total count with filters
        $countQuery = "SELECT COUNT(*) FROM sequence_history $whereClause";
        $countStmt = $db->prepare($countQuery);
        foreach ($params as $key => $value) {
            $countStmt->bindValue($key, $value);
        }
        $totalCount = $countStmt->execute()->fetchArray(SQLITE3_NUM)[0];
        
        $query = "SELECT * FROM sequence_history $whereClause ORDER BY timestamp DESC LIMIT :limit OFFSET :offset";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':limit', $limit, SQLITE3_INTEGER);
        $stmt->bindValue(':offset', $offset, SQLITE3_INTEGER);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $result = $stmt->execute();
        $sequences = array();
        
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $sequences[] = $row;
        }
        
        $db->close();
        
        return json(array(
            'success' => true,
            'sequences' => $sequences,
            'count' => count($sequences),
            'total' => $totalCount,
            'offset' => $offset,
            'limit' => $limit
        ));
    } catch (Exception $e) {
        return json(array(
            'success' => false,
            'message' => 'Error retrieving sequence history: ' . $e->getMessage()
        ));
    }
}

/**
 * Get playlist history
 */
function advancedStatsGetPlaylistHistory() {
    $dbPath = '/home/fpp/media/config/plugin.fpp-plugin-AdvancedStats.db';
    
    if (!file_exists($dbPath)) {
        return json(array(
            'success' => false,
            'message' => 'Database not initialized'
        ));
    }
    
    try {
        $db = new SQLite3($dbPath);
        $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 100;
        $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
        
        // Get total count
        $totalCount = $db->querySingle("SELECT COUNT(*) FROM playlist_history");
        
        $query = "SELECT * FROM playlist_history ORDER BY timestamp DESC LIMIT :limit OFFSET :offset";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':limit', $limit, SQLITE3_INTEGER);
        $stmt->bindValue(':offset', $offset, SQLITE3_INTEGER);
        
        $result = $stmt->execute();
        $playlists = array();
        
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $playlists[] = $row;
        }
        
        $db->close();
        
        return json(array(
            'success' => true,
            'playlists' => $playlists,
            'count' => count($playlists),
            'total' => $totalCount,
            'offset' => $offset,
            'limit' => $limit
        ));
    } catch (Exception $e) {
        return json(array(
            'success' => false,
            'message' => 'Error retrieving playlist history: ' . $e->getMessage()
        ));
    }
}

/**
 * Get daily statistics
 */
function advancedStatsGetDailyStats() {
    $dbPath = '/home/fpp/media/config/plugin.fpp-plugin-AdvancedStats.db';
    
    if (!file_exists($dbPath)) {
        return json(array(
            'success' => false,
            'message' => 'Database not initialized'
        ));
    }
    
    try {
        $db = new SQLite3($dbPath);
        $days = isset($_GET['days']) ? intval($_GET['days']) : 30;
        
        $query = "SELECT * FROM daily_stats ORDER BY date DESC LIMIT :days";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':days', $days, SQLITE3_INTEGER);
        
        $result = $stmt->execute();
        $stats = array();
        
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $stats[] = $row;
        }
        
        $db->close();
        
        return json(array(
            'success' => true,
            'stats' => $stats,
            'count' => count($stats)
        ));
    } catch (Exception $e) {
        return json(array(
            'success' => false,
            'message' => 'Error retrieving daily stats: ' . $e->getMessage()
        ));
    }
}

/**
 * Get dashboard data (combined summary)
 */
function advancedStatsGetDashboardData() {
    $dbPath = '/home/fpp/media/config/plugin.fpp-plugin-AdvancedStats.db';
    
    if (!file_exists($dbPath)) {
        return json(array(
            'success' => false,
            'message' => 'Database not initialized'
        ));
    }
    
    try {
        $db = new SQLite3($dbPath);
        
        // Get today's stats
        $today = date('Y-m-d');
        $stmt = $db->prepare("SELECT * FROM daily_stats WHERE date = :date");
        $stmt->bindValue(':date', $today, SQLITE3_TEXT);
        $result = $stmt->execute();
        $todayStats = $result->fetchArray(SQLITE3_ASSOC);
        
        if (!$todayStats) {
            $todayStats = array(
                'gpio_events_count' => 0,
                'sequences_played' => 0,
                'playlists_started' => 0,
                'total_sequence_duration' => 0
            );
        }
        
        // Get total counts
        $totalGPIO = $db->querySingle("SELECT COUNT(*) FROM gpio_events");
        $totalSequences = $db->querySingle("SELECT COUNT(*) FROM sequence_history WHERE event_type = 'start'");
        $totalPlaylists = $db->querySingle("SELECT COUNT(*) FROM playlist_history WHERE event_type = 'start'");
        
        // Get most played sequences (top 10)
        $topSequences = array();
        $query = "SELECT sequence_name, 
                         COUNT(*) as play_count,
                         SUM(CASE WHEN duration > 0 THEN duration ELSE 0 END) as total_duration
                  FROM sequence_history 
                  WHERE event_type = 'start' 
                  GROUP BY sequence_name 
                  ORDER BY play_count DESC 
                  LIMIT 10";
        $result = $db->query($query);
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $topSequences[] = $row;
        }
        
        // Get most active GPIO pins
        $topGPIO = array();
        $query = "SELECT pin_number, COUNT(*) as event_count FROM gpio_events 
                  GROUP BY pin_number 
                  ORDER BY event_count DESC 
                  LIMIT 10";
        $result = $db->query($query);
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $topGPIO[] = $row;
        }
        
        $db->close();
        
        return json(array(
            'success' => true,
            'today' => $todayStats,
            'totals' => array(
                'gpio_events' => $totalGPIO,
                'sequences' => $totalSequences,
                'playlists' => $totalPlaylists
            ),
            'top_sequences' => $topSequences,
            'top_gpio_pins' => $topGPIO
        ));
    } catch (Exception $e) {
        return json(array(
            'success' => false,
            'message' => 'Error retrieving dashboard data: ' . $e->getMessage()
        ));
    }
}

/**
 * Backup database - download DB file
 */
function advancedStatsBackupDatabase() {
    $dbPath = '/home/fpp/media/config/plugin.fpp-plugin-AdvancedStats.db';
    
    if (!file_exists($dbPath)) {
        header('Content-Type: application/json');
        echo json_encode(array(
            'success' => false,
            'message' => 'Database not found'
        ));
        return;
    }
    
    $filename = 'advancedstats-backup-' . date('Y-m-d-His') . '.db';
    
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Content-Length: ' . filesize($dbPath));
    header('Cache-Control: no-cache, must-revalidate');
    header('Pragma: public');
    
    readfile($dbPath);
    exit;
}

/**
 * Restore database - upload and replace DB file
 */
function advancedStatsRestoreDatabase() {
    $dbPath = '/home/fpp/media/config/plugin.fpp-plugin-AdvancedStats.db';
    
    if (!isset($_FILES['database']) || $_FILES['database']['error'] !== UPLOAD_ERR_OK) {
        return json(array(
            'success' => false,
            'message' => 'No file uploaded or upload error'
        ));
    }
    
    $uploadedFile = $_FILES['database']['tmp_name'];
    
    // Verify it's a valid SQLite database
    try {
        $db = new SQLite3($uploadedFile);
        // Check if required tables exist
        $tables = array('gpio_events', 'sequence_history', 'playlist_history', 'daily_stats');
        foreach ($tables as $table) {
            $result = $db->querySingle("SELECT name FROM sqlite_master WHERE type='table' AND name='$table'");
            if (!$result) {
                $db->close();
                return json(array(
                    'success' => false,
                    'message' => "Invalid database: missing table '$table'"
                ));
            }
        }
        $db->close();
    } catch (Exception $e) {
        return json(array(
            'success' => false,
            'message' => 'Invalid SQLite database file'
        ));
    }
    
    // Backup current database before replacing
    if (file_exists($dbPath)) {
        $backupPath = $dbPath . '.backup-' . date('YmdHis');
        if (!copy($dbPath, $backupPath)) {
            return json(array(
                'success' => false,
                'message' => 'Failed to create safety backup'
            ));
        }
    }
    
    // Replace database
    if (move_uploaded_file($uploadedFile, $dbPath)) {
        chmod($dbPath, 0664);
        return json(array(
            'success' => true,
            'message' => 'Database restored successfully'
        ));
    } else {
        return json(array(
            'success' => false,
            'message' => 'Failed to restore database'
        ));
    }
}

/**
 * Export data in CSV or JSON format
 */
function advancedStatsExportData() {
    $dbPath = '/home/fpp/media/config/plugin.fpp-plugin-AdvancedStats.db';
    
    if (!file_exists($dbPath)) {
        header('Content-Type: application/json');
        echo json_encode(array(
            'success' => false,
            'message' => 'Database not initialized'
        ));
        return;
    }
    
    $table = isset($_GET['table']) ? $_GET['table'] : 'sequence_history';
    $format = isset($_GET['format']) ? $_GET['format'] : 'csv';
    
    $validTables = array('gpio_events', 'sequence_history', 'playlist_history', 'daily_stats');
    if (!in_array($table, $validTables)) {
        header('Content-Type: application/json');
        echo json_encode(array(
            'success' => false,
            'message' => 'Invalid table name'
        ));
        return;
    }
    
    try {
        $db = new SQLite3($dbPath);
        $result = $db->query("SELECT * FROM $table ORDER BY timestamp DESC");
        
        $data = array();
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $data[] = $row;
        }
        $db->close();
        
        if (empty($data)) {
            header('Content-Type: application/json');
            echo json_encode(array(
                'success' => false,
                'message' => 'No data to export'
            ));
            return;
        }
        
        $filename = "advancedstats-$table-" . date('Y-m-d-His');
        
        if ($format === 'json') {
            header('Content-Type: application/json');
            header('Content-Disposition: attachment; filename="' . $filename . '.json"');
            echo json_encode($data, JSON_PRETTY_PRINT);
        } else {
            // CSV export
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '.csv"');
            
            $output = fopen('php://output', 'w');
            
            // Write header row
            fputcsv($output, array_keys($data[0]));
            
            // Write data rows
            foreach ($data as $row) {
                fputcsv($output, $row);
            }
            
            fclose($output);
        }
        exit;
    } catch (Exception $e) {
        header('Content-Type: application/json');
        echo json_encode(array(
            'success' => false,
            'message' => 'Export error: ' . $e->getMessage()
        ));
        return;
    }
}
?>
