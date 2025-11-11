<?php
include_once("/opt/fpp/www/common.php");
$pluginName = "fpp-plugin-AdvancedStats";
$pluginConfigFile = $settings['configDirectory'] . "/plugin." . $pluginName;

function getEndpointsfpppluginAdvancedStats() {
    $result = array();

    $ep = array(
        'method' => 'GET',
        'endpoint' => 'version',
        'callback' => 'fppAdvancedStatsVersion');
    array_push($result, $ep);
    
    $ep = array(
        'method' => 'GET',
        'endpoint' => 'status',
        'callback' => 'fppAdvancedStatsStatus');
    array_push($result, $ep);
    
    $ep = array(
        'method' => 'POST',
        'endpoint' => 'start-background',
        'callback' => 'fppAdvancedStatsStartBackground');
    array_push($result, $ep);
    
    $ep = array(
        'method' => 'POST',
        'endpoint' => 'stop-background',
        'callback' => 'fppAdvancedStatsStopBackground');
    array_push($result, $ep);
    
    $ep = array(
        'method' => 'POST',
        'endpoint' => 'pause-background',
        'callback' => 'fppAdvancedStatsPauseBackground');
    array_push($result, $ep);
    
    $ep = array(
        'method' => 'POST',
        'endpoint' => 'resume-background',
        'callback' => 'fppAdvancedStatsResumeBackground');
    array_push($result, $ep);
    
    $ep = array(
        'method' => 'POST',
        'endpoint' => 'next-track',
        'callback' => 'fppAdvancedStatsNextTrack');
    array_push($result, $ep);
    
    $ep = array(
        'method' => 'POST',
        'endpoint' => 'previous-track',
        'callback' => 'fppAdvancedStatsPreviousTrack');
    array_push($result, $ep);
    
    $ep = array(
        'method' => 'POST',
        'endpoint' => 'jump-to-track',
        'callback' => 'fppAdvancedStatsJumpToTrack');
    array_push($result, $ep);
    
    $ep = array(
        'method' => 'POST',
        'endpoint' => 'start-show',
        'callback' => 'fppAdvancedStatsStartShow');
    array_push($result, $ep);
    
    $ep = array(
        'method' => 'POST',
        'endpoint' => 'set-volume',
        'callback' => 'fppAdvancedStatsSetVolume');
    array_push($result, $ep);
    
    $ep = array(
        'method' => 'POST',
        'endpoint' => 'save-settings',
        'callback' => 'fppAdvancedStatsSaveSettings');
    array_push($result, $ep);
    
    $ep = array(
        'method' => 'GET',
        'endpoint' => 'playlist-details',
        'callback' => 'fppAdvancedStatsPlaylistDetails');
    array_push($result, $ep);
    
    $ep = array(
        'method' => 'POST',
        'endpoint' => 'play-announcement',
        'callback' => 'fppAdvancedStatsPlayAnnouncement');
    array_push($result, $ep);
    
    $ep = array(
        'method' => 'POST',
        'endpoint' => 'stop-announcement',
        'callback' => 'fppAdvancedStatsStopAnnouncement');
    array_push($result, $ep);
    
    $ep = array(
        'method' => 'GET',
        'endpoint' => 'psa-status',
        'callback' => 'fppAdvancedStatsPSAStatus');
    array_push($result, $ep);
    
    $ep = array(
        'method' => 'GET',
        'endpoint' => 'check-update',
        'callback' => 'fppAdvancedStatsCheckUpdate');
    array_push($result, $ep);
    
    $ep = array(
        'method' => 'GET',
        'endpoint' => 'get-commit-history',
        'callback' => 'fppAdvancedStatsGetCommitHistory');
    array_push($result, $ep);
    
    $ep = array(
        'method' => 'POST',
        'endpoint' => 'reorder-playlist',
        'callback' => 'fppAdvancedStatsReorderPlaylist');
    array_push($result, $ep);
    
    $ep = array(
        'method' => 'GET',
        'endpoint' => 'tts-status',
        'callback' => 'fppAdvancedStatsTTSStatus');
    array_push($result, $ep);
    
    $ep = array(
        'method' => 'POST',
        'endpoint' => 'install-tts',
        'callback' => 'fppAdvancedStatsInstallTTS');
    array_push($result, $ep);
    
    $ep = array(
        'method' => 'POST',
        'endpoint' => 'generate-tts',
        'callback' => 'fppAdvancedStatsGenerateTTS');
    array_push($result, $ep);
    
    $ep = array(
        'method' => 'POST',
        'endpoint' => 'play-tts',
        'callback' => 'fppAdvancedStatsPlayTTS');
    array_push($result, $ep);
    
    $ep = array(
        'method' => 'GET',
        'endpoint' => 'tts-voices',
        'callback' => 'fppAdvancedStatsTTSVoices');
    array_push($result, $ep);
    
    $ep = array(
        'method' => 'POST',
        'endpoint' => 'install-voice',
        'callback' => 'fppAdvancedStatsInstallVoice');
    array_push($result, $ep);
    
    $ep = array(
        'method' => 'POST',
        'endpoint' => 'set-default-voice',
        'callback' => 'fppAdvancedStatsSetDefaultVoice');
    array_push($result, $ep);
    
    $ep = array(
        'method' => 'POST',
        'endpoint' => 'delete-voice',
        'callback' => 'fppAdvancedStatsDeleteVoice');
    array_push($result, $ep);

    return $result;
}

// GET /api/plugin/fpp-plugin-AdvancedStats/version
function fppAdvancedStatsVersion() {
    $result = array();
    $result['version'] = 'fpp-AdvancedStats v1.0';
    return json($result);
}

// GET /api/plugin/fpp-plugin-AdvancedStats/status
function fppAdvancedStatsStatus() {
    global $settings;
    $pluginConfigFile = $settings['configDirectory'] . "/plugin.fpp-plugin-AdvancedStats";
    
    // Load plugin config with error checking
    $pluginSettings = array();
    if (file_exists($pluginConfigFile)){
        // Check if file is readable
        if (!is_readable($pluginConfigFile)) {
            error_log("AdvancedStats Plugin: Config file exists but is not readable: $pluginConfigFile");
            // Try to fix permissions
            @chmod($pluginConfigFile, 0644);
        }
        
        // Try to parse the file
        $pluginSettings = @parse_ini_file($pluginConfigFile);
        if ($pluginSettings === false) {
            error_log("AdvancedStats Plugin: Failed to parse config file: $pluginConfigFile");
            $pluginSettings = array();
        }
    } else {
        error_log("AdvancedStats Plugin: Config file does not exist: $pluginConfigFile");
    }
    
    // Get current brightness
    $brightness = getSetting('brightness');
    if ($brightness === false || $brightness === '') {
        $brightness = 100;
    }
    
    // Check running playlists
    //$status = GetCurrentStatus();
    $currentPlaylist = isset($status['current_playlist']['playlist']) ? $status['current_playlist']['playlist'] : '';
    $fppStatus = isset($status['status_name']) ? $status['status_name'] : 'unknown';
    $currentSequence = isset($status['current_sequence']) ? $status['current_sequence'] : '';
    
    $AdvancedStatsPlaylist = isset($pluginSettings['AdvancedStatsPlaylist']) ? $pluginSettings['AdvancedStatsPlaylist'] : '';
    $showPlaylist = isset($pluginSettings['ShowPlaylist']) ? $pluginSettings['ShowPlaylist'] : '';
    $returnToPreShow = isset($pluginSettings['ReturnToPreShow']) ? $pluginSettings['ReturnToPreShow'] : '1';
    $shuffleMusic = isset($pluginSettings['ShuffleMusic']) ? $pluginSettings['ShuffleMusic'] : '0';
    $volumeLevel = isset($pluginSettings['VolumeLevel']) ? intval($pluginSettings['VolumeLevel']) : 70;
    $AdvancedStatsVolume = isset($pluginSettings['AdvancedStatsVolume']) ? intval($pluginSettings['AdvancedStatsVolume']) : $volumeLevel;
    $showPlaylistVolume = isset($pluginSettings['ShowPlaylistVolume']) ? intval($pluginSettings['ShowPlaylistVolume']) : 100;
    $postShowBackgroundVolume = isset($pluginSettings['PostShowBackgroundVolume']) ? intval($pluginSettings['PostShowBackgroundVolume']) : $AdvancedStatsVolume;
    
    // Check if background music player is running (independent of FPP playlists)
    $pidFile = '/tmp/background_music_player.pid';
    $AdvancedStatsRunning = false;
    if (file_exists($pidFile)) {
        $pid = trim(file_get_contents($pidFile));
        // Check if process is actually running
        exec("ps -p $pid > /dev/null 2>&1", $output, $returnCode);
        $AdvancedStatsRunning = ($returnCode === 0);
    }
    
    // Get current track information if player is running
    $currentTrack = '';
    $trackDuration = 0;
    $trackElapsed = 0;
    $trackProgress = 0;
    $playbackState = 'stopped';
    $currentTrackNumber = 0;
    $totalTracks = 0;
    $streamSource = false;
    $streamTitle = '';
    $streamArtist = '';
    
    if ($AdvancedStatsRunning) {
        $statusFile = '/tmp/bg_music_status.txt';
        if (file_exists($statusFile)) {
            // Read status file line by line to handle special characters properly
            $statusLines = file($statusFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $statusData = array();
            foreach ($statusLines as $line) {
                // Split on first = only
                $pos = strpos($line, '=');
                if ($pos !== false) {
                    $key = substr($line, 0, $pos);
                    $value = substr($line, $pos + 1);
                    $statusData[$key] = $value;
                }
            }
            
            if ($statusData) {
                // Check if this is a stream source
                $streamSource = isset($statusData['source']) && $statusData['source'] === 'stream';
                
                if ($streamSource) {
                    // Get stream metadata (ICY info)
                    $streamTitle = isset($statusData['stream_title']) ? $statusData['stream_title'] : '';
                    $streamArtist = isset($statusData['stream_artist']) ? $statusData['stream_artist'] : '';
                } else {
                    // Only populate track info for playlist mode, not for streams
                    $currentTrack = isset($statusData['filename']) ? $statusData['filename'] : '';
                    $trackDuration = isset($statusData['duration']) ? intval($statusData['duration']) : 0;
                    $trackElapsed = isset($statusData['elapsed']) ? intval($statusData['elapsed']) : 0;
                    $trackProgress = isset($statusData['progress']) ? intval($statusData['progress']) : 0;
                    $currentTrackNumber = isset($statusData['track_number']) ? intval($statusData['track_number']) : 0;
                    $totalTracks = isset($statusData['total_tracks']) ? intval($statusData['total_tracks']) : 0;
                }
                $playbackState = isset($statusData['state']) ? $statusData['state'] : 'playing';
            }
        }
    }
    
    $showRunning = ($currentPlaylist === $showPlaylist && $currentPlaylist !== '');
    
    // Check if fpp-brightness plugin is installed (required for transitions)
    $brightnessPluginInstalled = file_exists('/home/fpp/media/plugins/fpp-brightness/libfpp-brightness.so');
    
    $result = array(
        'AdvancedStatsRunning' => $AdvancedStatsRunning,
        'showRunning' => $showRunning,
        'streamSource' => $streamSource,
        'brightness' => intval($brightness),
        'brightnessPluginInstalled' => $brightnessPluginInstalled,
        'currentPlaylist' => $currentPlaylist,
        'fppStatus' => $fppStatus,
        'currentSequence' => $currentSequence,
        'currentTrack' => $currentTrack,
        'trackDuration' => $trackDuration,
        'trackElapsed' => $trackElapsed,
        'trackProgress' => $trackProgress,
        'playbackState' => $playbackState,
        'currentTrackNumber' => $currentTrackNumber,
        'totalTracks' => $totalTracks,
        'streamTitle' => $streamTitle,
        'streamArtist' => $streamArtist,
        'config' => array(
            'AdvancedStatsSource' => isset($pluginSettings['AdvancedStatsSource']) ? $pluginSettings['AdvancedStatsSource'] : 'playlist',
            'AdvancedStatsPlaylist' => $AdvancedStatsPlaylist,
            'AdvancedStatsStreamURL' => isset($pluginSettings['AdvancedStatsStreamURL']) ? $pluginSettings['AdvancedStatsStreamURL'] : '',
            'showPlaylist' => $showPlaylist,
            'fadeTime' => isset($pluginSettings['FadeTime']) ? $pluginSettings['FadeTime'] : 5,
            'blackoutTime' => isset($pluginSettings['BlackoutTime']) ? $pluginSettings['BlackoutTime'] : 2,
            'returnToPreShow' => $returnToPreShow,
            'postShowDelay' => isset($pluginSettings['PostShowDelay']) ? $pluginSettings['PostShowDelay'] : 0,
            'shuffleMusic' => $shuffleMusic,
            'enableCrossfade' => isset($pluginSettings['EnableCrossfade']) && $pluginSettings['EnableCrossfade'] == '1' ? true : false,
            'crossfadeDuration' => isset($pluginSettings['CrossfadeDuration']) ? floatval($pluginSettings['CrossfadeDuration']) : 3,
            'volumeLevel' => $volumeLevel,
            'AdvancedStatsVolume' => $AdvancedStatsVolume,
            'showPlaylistVolume' => $showPlaylistVolume,
            'postShowBackgroundVolume' => $postShowBackgroundVolume,
            'PSAAnnouncementVolume' => isset($pluginSettings['PSAAnnouncementVolume']) ? $pluginSettings['PSAAnnouncementVolume'] : '90',
            'PSADuckVolume' => isset($pluginSettings['PSADuckVolume']) ? $pluginSettings['PSADuckVolume'] : '30'
        )
    );
    
    // Dynamically add all PSA button configurations (1-20)
    for ($i = 1; $i <= 20; $i++) {
        $result['config']['PSAButton' . $i . 'Label'] = isset($pluginSettings['PSAButton' . $i . 'Label']) ? $pluginSettings['PSAButton' . $i . 'Label'] : '';
        $result['config']['PSAButton' . $i . 'File'] = isset($pluginSettings['PSAButton' . $i . 'File']) ? $pluginSettings['PSAButton' . $i . 'File'] : '';
    }
    
    return json($result);
}

// POST /api/plugin/fpp-plugin-AdvancedStats/start-background
function fppAdvancedStatsStartBackground() {
    global $settings;
    $pluginConfigFile = $settings['configDirectory'] . "/plugin.fpp-plugin-AdvancedStats";
    
    if (file_exists($pluginConfigFile)){
        $pluginSettings = parse_ini_file($pluginConfigFile);
    } else {
        return json(array('status' => 'ERROR', 'message' => 'Plugin not configured'));
    }
    
    // Check source type
    $source = isset($pluginSettings['AdvancedStatsSource']) ? $pluginSettings['AdvancedStatsSource'] : 'playlist';
    
    if ($source === 'stream') {
        $streamURL = isset($pluginSettings['AdvancedStatsStreamURL']) ? $pluginSettings['AdvancedStatsStreamURL'] : '';
        if (empty($streamURL)) {
            return json(array('status' => 'ERROR', 'message' => 'Stream URL not configured'));
        }
    } else {
        $AdvancedStatsPlaylist = isset($pluginSettings['AdvancedStatsPlaylist']) ? $pluginSettings['AdvancedStatsPlaylist'] : '';
        if (empty($AdvancedStatsPlaylist)) {
            return json(array('status' => 'ERROR', 'message' => 'Background music playlist not configured'));
        }
    }
    
    // Start background music using independent player (not FPP playlist system)
    // This allows music to play while FPP scheduler controls the sequence playlist
    $scriptPath = dirname(__FILE__) . '/scripts/background_music_player.sh';
    $output = array();
    $returnCode = 0;
    exec("/bin/bash " . escapeshellarg($scriptPath) . " start 2>&1", $output, $returnCode);
    
    if ($returnCode === 0) {
        return json(array('status' => 'OK', 'message' => 'Background music started'));
    } else {
        return json(array('status' => 'ERROR', 'message' => 'Failed to start background music', 'details' => implode("\n", $output)));
    }
}

// POST /api/plugin/fpp-plugin-AdvancedStats/stop-background
function fppAdvancedStatsStopBackground() {
    // Stop the independent background music player
    $scriptPath = dirname(__FILE__) . '/scripts/background_music_player.sh';
    $output = array();
    $returnCode = 0;
    exec("sudo /bin/bash " . escapeshellarg($scriptPath) . " stop 2>&1", $output, $returnCode);
    
    // Also stop any FPP playlist that might be playing background music
    // Check if a background music playlist is currently playing
    global $settings;
    $pluginConfigFile = $settings['configDirectory'] . "/plugin.fpp-plugin-AdvancedStats";
    
    if (file_exists($pluginConfigFile)) {
        $pluginSettings = parse_ini_file($pluginConfigFile);
        $bgMusicPlaylist = isset($pluginSettings['AdvancedStatsPlaylist']) ? $pluginSettings['AdvancedStatsPlaylist'] : '';
        
        // Get current FPP status
        $fppStatus = @file_get_contents('http://localhost/api/fppd/status');
        if ($fppStatus) {
            $statusData = json_decode($fppStatus, true);
            $currentPlaylist = isset($statusData['current_playlist']['playlist']) ? $statusData['current_playlist']['playlist'] : '';
            
            // If the current FPP playlist matches our background music playlist, stop it
            if (!empty($bgMusicPlaylist) && $currentPlaylist === $bgMusicPlaylist) {
                // Stop the FPP playlist
                @file_get_contents('http://localhost/api/playlists/stop');
            }
        }
    }
    
    return json(array('status' => 'OK', 'message' => 'Background music stopped'));
}

// POST /api/plugin/fpp-plugin-AdvancedStats/pause-background
function fppAdvancedStatsPauseBackground() {
    $scriptPath = dirname(__FILE__) . '/scripts/background_music_player.sh';
    $output = array();
    $returnCode = 0;
    exec("sudo /bin/bash " . escapeshellarg($scriptPath) . " pause 2>&1", $output, $returnCode);
    
    if ($returnCode === 0) {
        return json(array('status' => 'OK', 'message' => 'Background music paused'));
    } else {
        return json(array('status' => 'ERROR', 'message' => 'Failed to pause background music', 'details' => implode("\n", $output)));
    }
}

// POST /api/plugin/fpp-plugin-AdvancedStats/resume-background
function fppAdvancedStatsResumeBackground() {
    $scriptPath = dirname(__FILE__) . '/scripts/background_music_player.sh';
    $output = array();
    $returnCode = 0;
    exec("sudo /bin/bash " . escapeshellarg($scriptPath) . " resume 2>&1", $output, $returnCode);
    
    if ($returnCode === 0) {
        return json(array('status' => 'OK', 'message' => 'Background music resumed'));
    } else {
        return json(array('status' => 'ERROR', 'message' => 'Failed to resume background music', 'details' => implode("\n", $output)));
    }
}

// POST /api/plugin/fpp-plugin-AdvancedStats/next-track
function fppAdvancedStatsNextTrack() {
    $scriptPath = dirname(__FILE__) . '/scripts/background_music_player.sh';
    $output = array();
    $returnCode = 0;
    exec("sudo /bin/bash " . escapeshellarg($scriptPath) . " next 2>&1", $output, $returnCode);
    
    if ($returnCode === 0) {
        return json(array('status' => 'OK', 'message' => 'Skipped to next track'));
    } else {
        return json(array('status' => 'ERROR', 'message' => 'Failed to skip track', 'details' => implode("\n", $output)));
    }
}

// POST /api/plugin/fpp-plugin-AdvancedStats/previous-track
function fppAdvancedStatsPreviousTrack() {
    $scriptPath = dirname(__FILE__) . '/scripts/background_music_player.sh';
    $output = array();
    $returnCode = 0;
    exec("sudo /bin/bash " . escapeshellarg($scriptPath) . " previous 2>&1", $output, $returnCode);
    
    if ($returnCode === 0) {
        return json(array('status' => 'OK', 'message' => 'Went to previous track'));
    } else {
        return json(array('status' => 'ERROR', 'message' => 'Failed to go to previous track', 'details' => implode("\n", $output)));
    }
}

// POST /api/plugin/fpp-plugin-AdvancedStats/jump-to-track
function fppAdvancedStatsJumpToTrack() {
    $input = json_decode(file_get_contents('php://input'), true);
    $trackNumber = isset($input['trackNumber']) ? intval($input['trackNumber']) : 0;
    
    if ($trackNumber < 1) {
        return json(array('status' => 'ERROR', 'message' => 'Invalid track number'));
    }
    
    $scriptPath = dirname(__FILE__) . '/scripts/background_music_player.sh';
    $output = array();
    $returnCode = 0;
    exec("sudo /bin/bash " . escapeshellarg($scriptPath) . " jump " . escapeshellarg($trackNumber) . " 2>&1", $output, $returnCode);
    
    if ($returnCode === 0) {
        return json(array('status' => 'OK', 'message' => 'Jumped to track ' . $trackNumber));
    } else {
        return json(array('status' => 'ERROR', 'message' => 'Failed to jump to track', 'details' => implode("\n", $output)));
    }
}

// POST /api/plugin/fpp-plugin-AdvancedStats/start-show
function fppAdvancedStatsStartShow() {
    global $settings;
    $pluginConfigFile = $settings['configDirectory'] . "/plugin.fpp-plugin-AdvancedStats";
    
    if (file_exists($pluginConfigFile)){
        $pluginSettings = parse_ini_file($pluginConfigFile);
    } else {
        return json(array('status' => 'ERROR', 'message' => 'Plugin not configured'));
    }
    
    $showPlaylist = isset($pluginSettings['ShowPlaylist']) ? $pluginSettings['ShowPlaylist'] : '';
    $fadeTime = isset($pluginSettings['FadeTime']) ? intval($pluginSettings['FadeTime']) : 5;
    $blackoutTime = isset($pluginSettings['BlackoutTime']) ? intval($pluginSettings['BlackoutTime']) : 2;
    
    if (empty($showPlaylist)) {
        return json(array('status' => 'ERROR', 'message' => 'Show playlist not configured'));
    }
    
    // Execute the fade and show transition script in background
    $scriptPath = dirname(__FILE__) . '/scripts/start_show_transition.sh';
    $logFile = '/home/fpp/media/logs/fpp-plugin-AdvancedStats-api.log';
    $cmd = sprintf('/bin/bash %s %d %d %s >> %s 2>&1 &', 
        escapeshellarg($scriptPath), 
        $fadeTime, 
        $blackoutTime, 
        escapeshellarg($showPlaylist),
        escapeshellarg($logFile));
    
    exec($cmd);
    
    return json(array('status' => 'OK', 'message' => 'Show transition started'));
}

// POST /api/plugin/fpp-plugin-AdvancedStats/set-volume
function fppAdvancedStatsSetVolume() {
    global $settings;
    $pluginConfigFile = $settings['configDirectory'] . "/plugin.fpp-plugin-AdvancedStats";
    
    // Get POST data
    $input = json_decode(file_get_contents('php://input'), true);
    $volume = isset($input['volume']) ? intval($input['volume']) : null;
    
    if ($volume === null || $volume < 0 || $volume > 100) {
        return json(array('status' => 'ERROR', 'message' => 'Invalid volume level. Must be between 0 and 100.'));
    }
    
    // Update the config file
    if (file_exists($pluginConfigFile)){
        $pluginSettings = parse_ini_file($pluginConfigFile);
    } else {
        $pluginSettings = array();
    }
    
    $pluginSettings['VolumeLevel'] = $volume;
    $pluginSettings['AdvancedStatsVolume'] = $volume;  // Update both for consistency
    
    // Write back to config file
    $configContent = "";
    foreach ($pluginSettings as $key => $value) {
        $configContent .= "$key=$value\n";
    }
    file_put_contents($pluginConfigFile, $configContent);
    
    // Apply volume change immediately using system audio controls
    $scriptPath = dirname(__FILE__) . '/scripts/restore_audio_volume.sh';
    exec("/bin/bash " . escapeshellarg($scriptPath) . " " . escapeshellarg($volume) . " 2>&1", $output, $returnCode);
    
    if ($returnCode === 0) {
        return json(array('status' => 'OK', 'message' => 'Volume updated immediately', 'volume' => $volume));
    } else {
        return json(array('status' => 'WARNING', 'message' => 'Volume saved but failed to apply immediately', 'volume' => $volume));
    }
}

/* function GetCurrentStatus() {
    $ch = curl_init('http://localhost/api/fppd/status');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $data = curl_exec($ch);
    curl_close($ch);
    return json_decode($data, true);
} */

// POST /api/plugin/fpp-plugin-AdvancedStats/save-settings
function fppAdvancedStatsSaveSettings() {
    global $settings;
    $pluginConfigFile = $settings['configDirectory'] . "/plugin.fpp-plugin-AdvancedStats";
    
    // Get POST data
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        return json(array('status' => 'ERROR', 'message' => 'Invalid input data'));
    }
    
    // Write settings in INI format
    $configContent = "";
    foreach ($input as $key => $value) {
        // Quote values that contain spaces or special characters
        if (strpos($value, ' ') !== false || strpos($value, '"') !== false || strpos($value, '=') !== false) {
            // Escape any existing quotes and wrap in quotes
            $value = '"' . str_replace('"', '\\"', $value) . '"';
        }
        $configContent .= "$key=$value\n";
    }
    
    if (file_put_contents($pluginConfigFile, $configContent) !== false) {
        return json(array('status' => 'OK', 'message' => 'Settings saved successfully'));
    } else {
        return json(array('status' => 'ERROR', 'message' => 'Failed to write settings file'));
    }
}

// GET /api/plugin/fpp-plugin-AdvancedStats/playlist-details
function fppAdvancedStatsPlaylistDetails() {
    global $settings;
    $pluginConfigFile = $settings['configDirectory'] . "/plugin.fpp-plugin-AdvancedStats";
    
    $result = array();
    $result['status'] = 'OK';
    $result['tracks'] = array();
    $result['playlistName'] = '';
    $result['totalDuration'] = 0;
    
    // Read plugin configuration
    if (file_exists($pluginConfigFile)) {
        $pluginSettings = parse_ini_file($pluginConfigFile);
        $playlistName = isset($pluginSettings['AdvancedStatsPlaylist']) ? $pluginSettings['AdvancedStatsPlaylist'] : '';
        
        if (!empty($playlistName)) {
            $result['playlistName'] = $playlistName;
            
            // Get playlist file path - FPP playlists are JSON files
            $playlistFile = $settings['playlistDirectory'] . '/' . $playlistName . '.json';
            
            if (file_exists($playlistFile)) {
                $playlistContent = file_get_contents($playlistFile);
                $playlistData = json_decode($playlistContent, true);
                
                if ($playlistData && isset($playlistData['mainPlaylist'])) {
                    $trackNum = 1;
                    foreach ($playlistData['mainPlaylist'] as $item) {
                        // Only include media items that are enabled
                        if ($item['type'] === 'media' && isset($item['enabled']) && $item['enabled'] == 1) {
                            $trackInfo = array();
                            $trackInfo['number'] = $trackNum;
                            $trackInfo['name'] = isset($item['mediaName']) ? $item['mediaName'] : 'Unknown';
                            
                            // Duration is already in the playlist JSON
                            $duration = isset($item['duration']) ? (int)$item['duration'] : 0;
                            $trackInfo['duration'] = $duration;
                            $trackInfo['durationFormatted'] = formatDuration($duration);
                            
                            $result['tracks'][] = $trackInfo;
                            $result['totalDuration'] += $duration;
                            $trackNum++;
                        }
                    }
                    
                    $result['totalDurationFormatted'] = formatDuration($result['totalDuration']);
                    $result['totalTracks'] = count($result['tracks']);
                } else {
                    $result['status'] = 'WARNING';
                    $result['message'] = 'Invalid playlist format';
                }
            } else {
                $result['status'] = 'WARNING';
                $result['message'] = 'Playlist file not found: ' . $playlistFile;
            }
        } else {
            $result['status'] = 'WARNING';
            $result['message'] = 'No background music playlist configured';
        }
    } else {
        $result['status'] = 'ERROR';
        $result['message'] = 'Plugin not configured';
    }
    
    return json($result);
}

function formatDuration($seconds) {
    if ($seconds < 60) {
        return $seconds . 's';
    } else if ($seconds < 3600) {
        $minutes = floor($seconds / 60);
        $secs = $seconds % 60;
        return sprintf('%d:%02d', $minutes, $secs);
    } else {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $secs = $seconds % 60;
        return sprintf('%d:%02d:%02d', $hours, $minutes, $secs);
    }
}

// POST /api/plugin/fpp-plugin-AdvancedStats/play-announcement
function fppAdvancedStatsPlayAnnouncement() {
    global $settings;
    $pluginConfigFile = $settings['configDirectory'] . "/plugin.fpp-plugin-AdvancedStats";
    
    // Load plugin config
    if (!file_exists($pluginConfigFile)) {
        return json(array('status' => 'ERROR', 'message' => 'Plugin not configured'));
    }
    
    $pluginSettings = parse_ini_file($pluginConfigFile);
    
    // Get input data
    $input = json_decode(file_get_contents('php://input'), true);
    $buttonNumber = isset($input['buttonNumber']) ? intval($input['buttonNumber']) : 0;
    
    if ($buttonNumber < 1 || $buttonNumber > 5) {
        return json(array('status' => 'ERROR', 'message' => 'Invalid button number'));
    }
    
    // Get announcement configuration
    $announcementFile = isset($pluginSettings['PSAButton' . $buttonNumber . 'File']) ? $pluginSettings['PSAButton' . $buttonNumber . 'File'] : '';
    $announcementLabel = isset($pluginSettings['PSAButton' . $buttonNumber . 'Label']) ? $pluginSettings['PSAButton' . $buttonNumber . 'Label'] : 'PSA #' . $buttonNumber;
    $announcementVolume = isset($pluginSettings['PSAAnnouncementVolume']) ? intval($pluginSettings['PSAAnnouncementVolume']) : 90;
    $duckVolume = isset($pluginSettings['PSADuckVolume']) ? intval($pluginSettings['PSADuckVolume']) : 30;
    
    if (empty($announcementFile)) {
        return json(array('status' => 'ERROR', 'message' => 'Announcement button not configured'));
    }
    
    if (!file_exists($announcementFile)) {
        return json(array('status' => 'ERROR', 'message' => 'Announcement file not found: ' . $announcementFile));
    }
    
    // Call the play_announcement script
    $scriptPath = dirname(__FILE__) . '/scripts/play_announcement.sh';
    $output = array();
    $returnCode = 0;
    
    $cmd = "/bin/bash " . escapeshellarg($scriptPath) . " " . 
           escapeshellarg($announcementFile) . " " . 
           escapeshellarg($duckVolume) . " " . 
           escapeshellarg($announcementVolume) . " " . 
           escapeshellarg($buttonNumber) . " " . 
           escapeshellarg($announcementLabel) . " 2>&1";
    
    exec($cmd, $output, $returnCode);
    
    if ($returnCode === 0) {
        return json(array('status' => 'OK', 'message' => 'Announcement started'));
    } else {
        return json(array('status' => 'ERROR', 'message' => 'Failed to play announcement', 'details' => implode("\n", $output)));
    }
}

// POST /api/plugin/fpp-plugin-AdvancedStats/stop-announcement
function fppAdvancedStatsStopAnnouncement() {
    $pidFile = '/tmp/announcement_player.pid';
    
    if (!file_exists($pidFile)) {
        return json(array('status' => 'OK', 'message' => 'No announcement playing'));
    }
    
    $pid = trim(file_get_contents($pidFile));
    
    // Kill the announcement process
    exec("kill $pid 2>&1", $output, $returnCode);
    
    // Clean up PID file
    @unlink($pidFile);
    
    return json(array('status' => 'OK', 'message' => 'Announcement stopped'));
}

// GET /api/plugin/fpp-plugin-AdvancedStats/psa-status
function fppAdvancedStatsPSAStatus() {
    $pidFile = '/tmp/announcement_player.pid';
    $statusFile = '/tmp/announcement_status.txt';
    $playing = false;
    $buttonNumber = 0;
    $buttonLabel = '';
    $announcementFile = '';
    $duration = 0;
    $elapsed = 0;
    $progress = 0;
    $maxDuration = 300; // 5 minutes max for any announcement
    
    if (file_exists($pidFile)) {
        $pid = trim(file_get_contents($pidFile));
        // Check if process is actually running
        exec("ps -p $pid > /dev/null 2>&1", $output, $returnCode);
        $playing = ($returnCode === 0);
        
        // If playing, read status information
        if ($playing && file_exists($statusFile)) {
            $statusData = parse_ini_file($statusFile);
            if ($statusData) {
                $buttonNumber = isset($statusData['buttonNumber']) ? intval($statusData['buttonNumber']) : 0;
                $buttonLabel = isset($statusData['buttonLabel']) ? $statusData['buttonLabel'] : '';
                $announcementFile = isset($statusData['announcementFile']) ? $statusData['announcementFile'] : '';
                $duration = isset($statusData['duration']) ? intval($statusData['duration']) : 0;
                
                // Calculate elapsed time and progress
                if (isset($statusData['startTime'])) {
                    $startTime = intval($statusData['startTime']);
                    $elapsed = time() - $startTime;
                    
                    // Calculate progress percentage
                    if ($duration > 0) {
                        $progress = min(100, round(($elapsed / $duration) * 100));
                    }
                    
                    // Check if announcement has been running too long (stuck)
                    if ($elapsed > $maxDuration) {
                        error_log("AdvancedStats: PSA stuck for $elapsed seconds, killing process $pid");
                        // Kill stuck process
                        exec("kill $pid 2>&1");
                        $playing = false;
                    }
                }
            }
        }
        
        // If not playing but PID file exists, clean it up
        if (!$playing) {
            @unlink($pidFile);
            @unlink($statusFile);
        }
    } else {
        // No PID file - ensure status file is also cleaned up
        if (file_exists($statusFile)) {
            @unlink($statusFile);
        }
    }
    
    $result = array(
        'status' => 'OK',
        'playing' => $playing,
        'buttonNumber' => $buttonNumber,
        'buttonLabel' => $buttonLabel,
        'announcementFile' => $announcementFile,
        'duration' => $duration,
        'elapsed' => $elapsed,
        'progress' => $progress
    );
    
    return json($result);
}

// GET /api/plugin/fpp-plugin-AdvancedStats/check-update
function fppAdvancedStatsCheckUpdate() {
    $pluginDir = dirname(__FILE__);
    $result = array(
        'status' => 'OK',
        'hasUpdate' => false,
        'currentCommit' => '',
        'latestCommit' => '',
        'currentCommitShort' => '',
        'latestCommitShort' => '',
        'behindBy' => 0,
        'lastChecked' => time(),
        'canConnect' => false,
        'branch' => 'master',
        'repoURL' => 'https://github.com/OnlineDynamic/Statistics-Fpp-Plugin'
    );
    
    // Determine which branch to check based on FPP version
    $pluginInfoFile = $pluginDir . '/pluginInfo.json';
    $branch = 'master'; // Default fallback
    
    if (file_exists($pluginInfoFile)) {
        $pluginInfo = json_decode(file_get_contents($pluginInfoFile), true);
        if ($pluginInfo && isset($pluginInfo['versions']) && is_array($pluginInfo['versions'])) {
            // Get FPP version
            $fppVersion = getFPPVersion();
            $fppVersionParts = explode('.', $fppVersion);
            $fppMajor = isset($fppVersionParts[0]) ? intval($fppVersionParts[0]) : 0;
            $fppMinor = isset($fppVersionParts[1]) ? intval($fppVersionParts[1]) : 0;
            
            // Find matching version config
            foreach ($pluginInfo['versions'] as $versionConfig) {
                $minFPP = isset($versionConfig['minFPPVersion']) ? $versionConfig['minFPPVersion'] : '0.0';
                $maxFPP = isset($versionConfig['maxFPPVersion']) ? $versionConfig['maxFPPVersion'] : '0';
                $configBranch = isset($versionConfig['branch']) ? $versionConfig['branch'] : 'master';
                
                $minParts = explode('.', $minFPP);
                $minMajor = isset($minParts[0]) ? intval($minParts[0]) : 0;
                $minMinor = isset($minParts[1]) ? intval($minParts[1]) : 0;
                
                // Check if FPP version >= minFPPVersion
                $meetsMin = ($fppMajor > $minMajor) || ($fppMajor == $minMajor && $fppMinor >= $minMinor);
                
                // Check maxFPPVersion (0 means no maximum)
                $meetsMax = false;
                if ($maxFPP === '0' || $maxFPP === 0) {
                    $meetsMax = true;
                } else {
                    $maxParts = explode('.', $maxFPP);
                    $maxMajor = isset($maxParts[0]) ? intval($maxParts[0]) : 0;
                    $maxMinor = isset($maxParts[1]) ? intval($maxParts[1]) : 0;
                    $meetsMax = ($fppMajor < $maxMajor) || ($fppMajor == $maxMajor && $fppMinor <= $maxMinor);
                }
                
                if ($meetsMin && $meetsMax) {
                    $branch = $configBranch;
                    break;
                }
            }
        }
    }
    
    $result['branch'] = $branch;
    
    // Check if we can connect to the internet (check GitHub)
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://github.com");
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 3);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    $result['canConnect'] = ($httpCode >= 200 && $httpCode < 400);
    
    if (!$result['canConnect']) {
        $result['message'] = 'Cannot connect to GitHub - check internet connection';
        return json($result);
    }
    
    // Get current commit hash
    $currentCommit = trim(shell_exec("cd " . escapeshellarg($pluginDir) . " && git rev-parse HEAD 2>/dev/null"));
    if (empty($currentCommit)) {
        $result['status'] = 'ERROR';
        $result['message'] = 'Failed to get current git commit';
        return json($result);
    }
    
    $result['currentCommit'] = $currentCommit;
    $result['currentCommitShort'] = substr($currentCommit, 0, 7);
    
    // Fetch latest from remote for the appropriate branch
    exec("cd " . escapeshellarg($pluginDir) . " && git fetch origin " . escapeshellarg($branch) . " 2>&1", $fetchOutput, $fetchReturn);
    
    if ($fetchReturn !== 0) {
        $result['status'] = 'ERROR';
        $result['message'] = 'Failed to fetch updates from remote repository';
        return json($result);
    }
    
    // Get latest commit hash from remote branch
    $latestCommit = trim(shell_exec("cd " . escapeshellarg($pluginDir) . " && git rev-parse origin/" . escapeshellarg($branch) . " 2>/dev/null"));
    if (empty($latestCommit)) {
        $result['status'] = 'ERROR';
        $result['message'] = 'Failed to get latest commit from remote';
        return json($result);
    }
    
    $result['latestCommit'] = $latestCommit;
    $result['latestCommitShort'] = substr($latestCommit, 0, 7);
    
    // Check if we're behind
    if ($currentCommit !== $latestCommit) {
        $result['hasUpdate'] = true;
        
        // Get number of commits behind
        $behindCount = trim(shell_exec("cd " . escapeshellarg($pluginDir) . " && git rev-list --count HEAD..origin/" . escapeshellarg($branch) . " 2>/dev/null"));
        $result['behindBy'] = intval($behindCount);
        
        // Get latest commit details
        $commitInfo = shell_exec("cd " . escapeshellarg($pluginDir) . " && git log origin/" . escapeshellarg($branch) . " -1 --pretty=format:'%s|%an|%ar' 2>/dev/null");
        if (!empty($commitInfo)) {
            $parts = explode('|', $commitInfo);
            $result['latestCommitMessage'] = isset($parts[0]) ? $parts[0] : '';
            $result['latestCommitAuthor'] = isset($parts[1]) ? $parts[1] : '';
            $result['latestCommitDate'] = isset($parts[2]) ? $parts[2] : '';
        }
    }
    
    return json($result);
}

// POST /api/plugin/fpp-plugin-AdvancedStats/reorder-playlist
function fppAdvancedStatsReorderPlaylist() {
    global $settings;
    $pluginConfigFile = $settings['configDirectory'] . "/plugin.fpp-plugin-AdvancedStats";
    
    // Get POST data
    $input = json_decode(file_get_contents('php://input'), true);
    $trackOrder = isset($input['trackOrder']) ? $input['trackOrder'] : null;
    
    if (!is_array($trackOrder) || count($trackOrder) === 0) {
        return json(array('status' => 'ERROR', 'message' => 'Invalid track order data'));
    }
    
    // Read plugin configuration to get playlist name
    if (file_exists($pluginConfigFile)) {
        $pluginSettings = parse_ini_file($pluginConfigFile);
        $playlistName = isset($pluginSettings['AdvancedStatsPlaylist']) ? $pluginSettings['AdvancedStatsPlaylist'] : '';
        
        if (empty($playlistName)) {
            return json(array('status' => 'ERROR', 'message' => 'No background music playlist configured'));
        }
        
        // Get playlist file path
        $playlistFile = $settings['playlistDirectory'] . '/' . $playlistName . '.json';
        
        if (!file_exists($playlistFile)) {
            return json(array('status' => 'ERROR', 'message' => 'Playlist file not found'));
        }
        
        // Read and parse playlist
        $playlistContent = file_get_contents($playlistFile);
        $playlistData = json_decode($playlistContent, true);
        
        if (!$playlistData || !isset($playlistData['mainPlaylist'])) {
            return json(array('status' => 'ERROR', 'message' => 'Invalid playlist format'));
        }
        
        // Extract only the media items that are enabled
        $mediaItems = array();
        $otherItems = array();
        foreach ($playlistData['mainPlaylist'] as $item) {
            if ($item['type'] === 'media' && isset($item['enabled']) && $item['enabled'] == 1) {
                $mediaItems[] = $item;
            } else {
                $otherItems[] = $item;
            }
        }
        
        // Verify track order matches the number of media items
        if (count($trackOrder) !== count($mediaItems)) {
            return json(array('status' => 'ERROR', 'message' => 'Track order count does not match playlist'));
        }
        
        // Reorder the media items based on trackOrder array (0-indexed)
        $reorderedMedia = array();
        foreach ($trackOrder as $oldIndex) {
            if (isset($mediaItems[$oldIndex])) {
                $reorderedMedia[] = $mediaItems[$oldIndex];
            }
        }
        
        // Merge back with other items (keep non-media items in their original positions)
        // For simplicity, we'll put all media items first, then other items
        $playlistData['mainPlaylist'] = array_merge($reorderedMedia, $otherItems);
        
        // Write back to file with pretty printing
        $jsonOutput = json_encode($playlistData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        if (file_put_contents($playlistFile, $jsonOutput) !== false) {
            // Check if background music is running
            $scriptPath = dirname(__FILE__) . '/scripts/background_music_player.sh';
            exec("/bin/bash " . escapeshellarg($scriptPath) . " status 2>&1", $statusOutput, $statusReturn);
            
            if ($statusReturn === 0) {
                // It's running - regenerate the m3u playlist file so it picks up the new order
                // The player will continue the current track and use the new order afterwards
                $m3uFile = '/tmp/background_music_playlist.m3u';
                $m3uContent = "#EXTM3U\n";
                foreach ($reorderedMedia as $item) {
                    $mediaName = isset($item['mediaName']) ? $item['mediaName'] : '';
                    if (!empty($mediaName)) {
                        $m3uContent .= "/home/fpp/media/music/" . $mediaName . "\n";
                    }
                }
                file_put_contents($m3uFile, $m3uContent);
                
                // Create signal file to tell player that playlist was reordered
                file_put_contents('/tmp/bg_music_reorder.txt', '1');
                
                return json(array('status' => 'OK', 'message' => 'Playlist reordered - will apply after current track'));
            } else {
                return json(array('status' => 'OK', 'message' => 'Playlist reordered successfully'));
            }
        } else {
            return json(array('status' => 'ERROR', 'message' => 'Failed to save playlist file'));
        }
    } else {
        return json(array('status' => 'ERROR', 'message' => 'Plugin not configured'));
    }
}

// GET /api/plugin/fpp-plugin-AdvancedStats/get-commit-history
function fppAdvancedStatsGetCommitHistory() {
    $pluginDir = dirname(__FILE__);
    
    // Check if this is a Git repository
    if (!is_dir($pluginDir . '/.git')) {
        return json(array(
            'status' => 'ERROR',
            'message' => 'Not a Git repository. This may be a manual installation.',
            'commits' => array()
        ));
    }
    
    // Get the last 50 commits
    $gitCommand = "cd " . escapeshellarg($pluginDir) . " && git log -50 --pretty=format:'%H|%an|%ae|%ad|%s' --date=iso 2>&1";
    $output = array();
    $returnVar = 0;
    
    exec($gitCommand, $output, $returnVar);
    
    if ($returnVar !== 0) {
        return json(array(
            'status' => 'ERROR',
            'message' => 'Failed to execute git log command',
            'commits' => array()
        ));
    }
    
    $commits = array();
    foreach ($output as $line) {
        $parts = explode('|', $line, 5);
        if (count($parts) === 5) {
            $commits[] = array(
                'hash' => $parts[0],
                'author' => $parts[1],
                'email' => $parts[2],
                'date' => $parts[3],
                'message' => $parts[4]
            );
        }
    }
    
    return json(array(
        'status' => 'OK',
        'commits' => $commits,
        'count' => count($commits)
    ));
}

// GET /api/plugin/fpp-plugin-AdvancedStats/tts-status
function fppAdvancedStatsTTSStatus() {
    $pluginDir = dirname(__FILE__);
    $piperDir = $pluginDir . '/piper';
    $piperBin = $piperDir . '/piper';
    $statusFile = $piperDir . '/status.txt';
    
    $status = array(
        'installed' => false,
        'version' => '',
        'architecture' => '',
        'installDate' => '',
        'voices' => array(),
        'defaultVoice' => ''
    );
    
    if (file_exists($piperBin)) {
        $status['installed'] = true;
        
        // Read status file if it exists
        if (file_exists($statusFile)) {
            $statusLines = file($statusFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($statusLines as $line) {
                if (strpos($line, 'version:') === 0) {
                    $status['version'] = trim(substr($line, 8));
                } elseif (strpos($line, 'architecture:') === 0) {
                    $status['architecture'] = trim(substr($line, 13));
                } elseif (preg_match('/^\d{4}-\d{2}-\d{2}/', $line)) {
                    $status['installDate'] = trim($line);
                }
            }
        }
        
        // List available voices
        $voicesDir = $piperDir . '/voices';
        if (is_dir($voicesDir)) {
            $voices = glob($voicesDir . '/*.onnx');
            foreach ($voices as $voice) {
                $voiceName = basename($voice, '.onnx');
                $status['voices'][] = $voiceName;
            }
        }
        
        // Check default voice
        if (file_exists($piperDir . '/default_voice.onnx')) {
            $defaultLink = readlink($piperDir . '/default_voice.onnx');
            if ($defaultLink) {
                $status['defaultVoice'] = basename($defaultLink, '.onnx');
            }
        }
    }
    
    return json($status);
}

// POST /api/plugin/fpp-plugin-AdvancedStats/install-tts
function fppAdvancedStatsInstallTTS() {
    $pluginDir = dirname(__FILE__);
    $installScript = $pluginDir . '/scripts/install_piper.sh';
    
    if (!file_exists($installScript)) {
        return json(array('status' => 'ERROR', 'message' => 'Installation script not found'));
    }
    
    // Run installation in background
    $logFile = '/tmp/piper_install.log';
    $cmd = "bash " . escapeshellarg($installScript) . " > " . escapeshellarg($logFile) . " 2>&1 &";
    exec($cmd);
    
    return json(array(
        'status' => 'OK', 
        'message' => 'Piper TTS installation started. This may take several minutes.',
        'logFile' => $logFile
    ));
}

// POST /api/plugin/fpp-plugin-AdvancedStats/generate-tts
function fppAdvancedStatsGenerateTTS() {
    $pluginDir = dirname(__FILE__);
    $piperBin = $pluginDir . '/piper/piper';
    
    // Check if Piper is installed
    if (!file_exists($piperBin)) {
        return json(array(
            'status' => 'ERROR', 
            'message' => 'Piper TTS not installed. Please install it first.'
        ));
    }
    
    // Get POST data
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['text']) || empty($data['text'])) {
        return json(array('status' => 'ERROR', 'message' => 'No text provided'));
    }
    
    if (!isset($data['filename']) || empty($data['filename'])) {
        return json(array('status' => 'ERROR', 'message' => 'No filename provided'));
    }
    
    $text = $data['text'];
    $filename = $data['filename'];
    $voice = isset($data['voice']) ? $data['voice'] : '';
    
    // Sanitize filename
    $filename = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $filename);
    if (!preg_match('/\.mp3$/', $filename)) {
        $filename .= '.mp3';
    }
    
    // Build command
    $generateScript = $pluginDir . '/scripts/generate_tts.sh';
    $cmd = "bash " . escapeshellarg($generateScript) . " " . 
           escapeshellarg($text) . " " . 
           escapeshellarg($filename);
    
    if (!empty($voice)) {
        $cmd .= " " . escapeshellarg($voice);
    }
    
    $cmd .= " 2>&1";
    
    $output = array();
    $returnVar = 0;
    exec($cmd, $output, $returnVar);
    
    if ($returnVar === 0) {
        return json(array(
            'status' => 'OK',
            'message' => 'TTS audio generated successfully',
            'filename' => $filename,
            'path' => '/home/fpp/media/music/' . $filename
        ));
    } else {
        return json(array(
            'status' => 'ERROR',
            'message' => 'Failed to generate TTS audio',
            'details' => implode("\n", $output)
        ));
    }
}

// POST /api/plugin/fpp-plugin-AdvancedStats/play-tts
function fppAdvancedStatsPlayTTS() {
    $pluginDir = dirname(__FILE__);
    $piperBin = $pluginDir . '/piper/piper';
    
    // Check if Piper is installed
    if (!file_exists($piperBin)) {
        return json(array(
            'status' => 'ERROR',
            'message' => 'Piper TTS not installed. Please install it first.'
        ));
    }
    
    // Get POST data
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['text']) || empty($data['text'])) {
        return json(array('status' => 'ERROR', 'message' => 'No text provided'));
    }
    
    $text = $data['text'];
    $voice = isset($data['voice']) ? $data['voice'] : '';
    
    // Build command
    $playScript = $pluginDir . '/scripts/play_tts_announcement.sh';
    $cmd = "bash " . escapeshellarg($playScript) . " " . escapeshellarg($text);
    
    if (!empty($voice)) {
        $cmd .= " " . escapeshellarg($voice);
    }
    
    $cmd .= " > /dev/null 2>&1 &";
    
    exec($cmd);
    
    return json(array(
        'status' => 'OK',
        'message' => 'TTS announcement started'
    ));
}

// GET /api/plugin/fpp-plugin-AdvancedStats/tts-voices
function fppAdvancedStatsTTSVoices() {
    $pluginDir = dirname(__FILE__);
    $piperDir = $pluginDir . '/piper';
    $voicesDir = $piperDir . '/voices';
    $availableVoicesFile = $piperDir . '/available_voices.json';
    
    // Load available voices catalog
    $availableVoices = array();
    if (file_exists($availableVoicesFile)) {
        $jsonContent = file_get_contents($availableVoicesFile);
        $voicesData = json_decode($jsonContent, true);
        if ($voicesData && isset($voicesData['voices'])) {
            $availableVoices = $voicesData['voices'];
        }
    }
    
    // Get installed voices
    $installedVoices = array();
    if (is_dir($voicesDir)) {
        $voiceFiles = glob($voicesDir . '/*.onnx');
        foreach ($voiceFiles as $voiceFile) {
            $voiceId = basename($voiceFile, '.onnx');
            $installedVoices[] = $voiceId;
        }
    }
    
    // Get default voice
    $defaultVoice = '';
    if (file_exists($piperDir . '/default_voice.onnx')) {
        $defaultLink = readlink($piperDir . '/default_voice.onnx');
        if ($defaultLink) {
            $defaultVoice = basename($defaultLink, '.onnx');
        }
    }
    
    // Merge installed status with available voices
    foreach ($availableVoices as &$voice) {
        $voice['installed'] = in_array($voice['id'], $installedVoices);
        $voice['is_default'] = ($voice['id'] === $defaultVoice);
    }
    
    return json(array(
        'status' => 'OK',
        'voices' => $availableVoices,
        'installed_count' => count($installedVoices),
        'default_voice' => $defaultVoice
    ));
}

// POST /api/plugin/fpp-plugin-AdvancedStats/install-voice
function fppAdvancedStatsInstallVoice() {
    $pluginDir = dirname(__FILE__);
    $installScript = $pluginDir . '/scripts/install_voice.sh';
    
    if (!file_exists($installScript)) {
        return json(array('status' => 'ERROR', 'message' => 'Voice installation script not found'));
    }
    
    // Get POST data
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['voice_id']) || empty($data['voice_id'])) {
        return json(array('status' => 'ERROR', 'message' => 'No voice ID provided'));
    }
    
    $voiceId = $data['voice_id'];
    
    // Run installation
    $cmd = "bash " . escapeshellarg($installScript) . " " . escapeshellarg($voiceId) . " 2>&1";
    $output = array();
    $returnVar = 0;
    exec($cmd, $output, $returnVar);
    
    if ($returnVar === 0) {
        return json(array(
            'status' => 'OK',
            'message' => 'Voice installed successfully',
            'voice_id' => $voiceId
        ));
    } else {
        return json(array(
            'status' => 'ERROR',
            'message' => 'Failed to install voice',
            'details' => implode("\n", $output)
        ));
    }
}

// POST /api/plugin/fpp-plugin-AdvancedStats/set-default-voice
function fppAdvancedStatsSetDefaultVoice() {
    $pluginDir = dirname(__FILE__);
    $piperDir = $pluginDir . '/piper';
    $voicesDir = $piperDir . '/voices';
    
    // Get POST data
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['voice_id']) || empty($data['voice_id'])) {
        return json(array('status' => 'ERROR', 'message' => 'No voice ID provided'));
    }
    
    $voiceId = $data['voice_id'];
    $voiceFile = $voicesDir . '/' . $voiceId . '.onnx';
    $voiceConfigFile = $voicesDir . '/' . $voiceId . '.onnx.json';
    
    // Check if voice exists
    if (!file_exists($voiceFile)) {
        return json(array('status' => 'ERROR', 'message' => 'Voice not installed'));
    }
    
    $defaultVoiceLink = $piperDir . '/default_voice.onnx';
    $defaultVoiceConfigLink = $piperDir . '/default_voice.onnx.json';
    
    // Remove old symlinks (check is_link first, not file_exists)
    if (is_link($defaultVoiceLink)) {
        unlink($defaultVoiceLink);
    }
    if (is_link($defaultVoiceConfigLink)) {
        unlink($defaultVoiceConfigLink);
    }
    
    // Create new symlinks
    $symlinkResult1 = symlink($voiceFile, $defaultVoiceLink);
    $symlinkResult2 = symlink($voiceConfigFile, $defaultVoiceConfigLink);
    
    if ($symlinkResult1 && $symlinkResult2) {
        return json(array(
            'status' => 'OK',
            'message' => 'Default voice set successfully',
            'voice_id' => $voiceId
        ));
    } else {
        return json(array(
            'status' => 'ERROR',
            'message' => 'Failed to create symlinks. Check permissions.'
        ));
    }
}

// POST /api/plugin/fpp-plugin-AdvancedStats/delete-voice
function fppAdvancedStatsDeleteVoice() {
    $pluginDir = dirname(__FILE__);
    $piperDir = $pluginDir . '/piper';
    $voicesDir = $piperDir . '/voices';
    
    // Get POST data
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['voice_id']) || empty($data['voice_id'])) {
        return json(array('status' => 'ERROR', 'message' => 'No voice ID provided'));
    }
    
    $voiceId = $data['voice_id'];
    $voiceFile = $voicesDir . '/' . $voiceId . '.onnx';
    $voiceConfigFile = $voicesDir . '/' . $voiceId . '.onnx.json';
    
    // Check if this is the default voice
    $defaultVoice = '';
    if (file_exists($piperDir . '/default_voice.onnx')) {
        $defaultLink = readlink($piperDir . '/default_voice.onnx');
        if ($defaultLink) {
            $defaultVoice = basename($defaultLink, '.onnx');
        }
    }
    
    if ($defaultVoice === $voiceId) {
        return json(array(
            'status' => 'ERROR',
            'message' => 'Cannot delete default voice. Set a different default voice first.'
        ));
    }
    
    // Delete voice files
    $deleted1 = @unlink($voiceFile);
    $deleted2 = @unlink($voiceConfigFile);
    
    if ($deleted1 || $deleted2) {
        return json(array(
            'status' => 'OK',
            'message' => 'Voice deleted successfully',
            'voice_id' => $voiceId
        ));
    } else {
        return json(array(
            'status' => 'ERROR',
            'message' => 'Failed to delete voice files'
        ));
    }
}

?>

