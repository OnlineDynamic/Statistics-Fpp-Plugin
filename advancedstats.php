<?php
// Check if MQTT broker is enabled
function isMQTTEnabled() {
    $settings_file = '/home/fpp/media/settings';
    if (file_exists($settings_file)) {
        $settings = file_get_contents($settings_file);
        // Check if MQTTBroker is set to 1 (enabled)
        if (preg_match('/MQTTBroker\s*=\s*"?1"?/i', $settings)) {
            return true;
        }
    }
    return false;
}

// Check if mosquitto service is running
function isMQTTRunning() {
    $output = shell_exec('systemctl is-active mosquitto 2>&1');
    return trim($output) === 'active';
}

$mqttEnabled = isMQTTEnabled();
$mqttRunning = isMQTTRunning();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Advanced Stats Dashboard</title>
    <link rel="stylesheet" href="/css/fpp.css" />
    <style>
        .stats-container {
            max-width: 1600px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .stats-header {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
        }
        
        .stats-header h1 {
            color: #007bff;
            margin-bottom: 10px;
        }
        
        .header-buttons {
            position: absolute;
            top: 0;
            right: 0;
        }
        
        .header-buttons a {
            display: inline-block;
            margin-left: 10px;
            padding: 8px 15px;
            background-color: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            transition: background-color 0.3s;
        }
        
        .header-buttons a:hover {
            background-color: #5a6268;
        }
        
        .header-buttons a i {
            margin-right: 5px;
        }
        
        .refresh-btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin-top: 10px;
        }
        
        .refresh-btn:hover {
            background-color: #0056b3;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background-color: #f8f9fa;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .stat-card h3 {
            margin-top: 0;
            color: #495057;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
            font-size: 16px;
        }
        
        .stat-value {
            font-size: 32px;
            font-weight: bold;
            color: #007bff;
            margin: 15px 0;
        }
        
        .stat-label {
            color: #6c757d;
            font-size: 14px;
        }
        
        .table-section {
            background-color: #ffffff;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .table-section h2 {
            margin-top: 0;
            color: #495057;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        
        th {
            background-color: #007bff;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: bold;
        }
        
        td {
            padding: 10px;
            border-bottom: 1px solid #dee2e6;
        }
        
        tr:hover {
            background-color: #f8f9fa;
        }
        
        .no-data {
            text-align: center;
            color: #6c757d;
            padding: 30px;
            font-style: italic;
        }
        
        .loading {
            text-align: center;
            color: #007bff;
            padding: 20px;
        }
        
        .error {
            background-color: #f8d7da;
            border: 2px solid #f5c6cb;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        
        .warning-banner {
            background-color: #fff3cd;
            border: 2px solid #ffc107;
            color: #856404;
            padding: 15px 20px;
            border-radius: 5px;
            margin: 20px 0;
            display: flex;
            align-items: center;
            font-size: 14px;
        }
        
        .warning-banner i {
            font-size: 24px;
            margin-right: 15px;
            color: #ffc107;
        }
        
        .warning-banner strong {
            display: block;
            font-size: 16px;
            margin-bottom: 5px;
        }
        
        .warning-banner a {
            color: #004085;
            text-decoration: underline;
            font-weight: bold;
        }
        
        .warning-banner a:hover {
            color: #002752;
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
            gap: 10px;
        }
        
        .pagination button {
            padding: 8px 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }
        
        .pagination button:hover:not(:disabled) {
            background-color: #0056b3;
        }
        
        .pagination button:disabled {
            background-color: #6c757d;
            cursor: not-allowed;
            opacity: 0.5;
        }
        
        .pagination .page-info {
            color: #495057;
            font-size: 14px;
            margin: 0 10px;
        }
        
        .filter-section {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        .filter-section label {
            font-weight: bold;
            color: #495057;
        }
        
        .filter-section input[type="date"],
        .filter-section select {
            padding: 8px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .filter-section button {
            padding: 8px 16px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }
        
        .filter-section button:hover {
            background-color: #218838;
        }
        
        .filter-section .clear-btn {
            background-color: #6c757d;
        }
        
        .filter-section .clear-btn:hover {
            background-color: #5a6268;
        }
        
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .badge-success {
            background-color: #28a745;
            color: white;
        }
        
        .badge-warning {
            background-color: #ffc107;
            color: #212529;
        }
    </style>
</head>
<body>
    <div class="stats-container">
        <div class="stats-header">
            <div class="header-buttons">
                <a href="plugin.php?_menu=status&plugin=fpp-plugin-AdvancedStats&page=content.php">
                    <i class="fas fa-cog"></i> Settings
                </a>
                <a href="plugin.php?_menu=status&plugin=fpp-plugin-AdvancedStats&page=help/advancedstats-help.php">
                    <i class="fas fa-question-circle"></i> Help
                </a>
            </div>
            <h1><i class="fas fa-chart-line"></i> Advanced Stats Dashboard</h1>
            <p style="color: #6c757d; font-size: 16px;">GPIO Input & Sequence Play History</p>
            <div style="display: flex; gap: 10px; justify-content: center; flex-wrap: wrap; margin-top: 15px;">
                <button class="refresh-btn" onclick="loadAllData()">
                    <i class="fas fa-sync"></i> Refresh Data
                </button>
                <button class="refresh-btn" style="background-color: #17a2b8;" onclick="backupDatabase()">
                    <i class="fas fa-download"></i> Backup Database
                </button>
                <button class="refresh-btn" style="background-color: #ffc107; color: #212529;" onclick="document.getElementById('restoreFileInput').click()">
                    <i class="fas fa-upload"></i> Restore Database
                </button>
            </div>
            <input type="file" id="restoreFileInput" accept=".db" style="display:none;" onchange="restoreDatabase(this.files[0])" />
            <div id="lastUpdate" style="color: #6c757d; font-size: 12px; margin-top: 10px;"></div>
        </div>
        
        <?php if (!$mqttEnabled || !$mqttRunning): ?>
        <!-- MQTT Warning Banner -->
        <div class="warning-banner">
            <i class="fas fa-exclamation-triangle"></i>
            <div>
                <strong>⚠️ MQTT Broker Not Enabled</strong>
                <?php if (!$mqttEnabled): ?>
                <p style="margin: 5px 0 0 0;">
                    The plugin requires the MQTT broker to be enabled to capture real-time events. 
                    Please enable it in <a href="/settings.php">FPP Settings → MQTT</a> and restart FPPD.
                </p>
                <?php elseif (!$mqttRunning): ?>
                <p style="margin: 5px 0 0 0;">
                    The MQTT broker is enabled but not running. Please restart FPPD or run: 
                    <code style="background-color: #fff; padding: 2px 6px; border-radius: 3px;">sudo systemctl start mosquitto</code>
                </p>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <div id="errorContainer"></div>
        
        <!-- Today's Summary Cards -->
        <h2 style="color: #495057; margin-top: 0;">Today's Activity</h2>
        <div class="stats-grid">
            <div class="stat-card">
                <h3><i class="fas fa-microchip"></i> GPIO Events</h3>
                <div class="stat-value" id="todayGpioCount">--</div>
                <div class="stat-label">GPIO triggers today</div>
            </div>
            
            <div class="stat-card">
                <h3><i class="fas fa-film"></i> Sequences Played</h3>
                <div class="stat-value" id="todaySequenceCount">--</div>
                <div class="stat-label">Sequences played today</div>
            </div>
            
            <div class="stat-card">
                <h3><i class="fas fa-list"></i> Playlists Started</h3>
                <div class="stat-value" id="todayPlaylistCount">--</div>
                <div class="stat-label">Playlists started today</div>
            </div>
            
            <div class="stat-card">
                <h3><i class="fas fa-clock"></i> Total Duration</h3>
                <div class="stat-value" id="todayDuration">--</div>
                <div class="stat-label">Sequence runtime today</div>
            </div>
        </div>
        
        <!-- All Time Totals -->
        <h2 style="color: #495057;">All-Time Totals</h2>
        <div class="stats-grid">
            <div class="stat-card">
                <h3><i class="fas fa-microchip"></i> Total GPIO Events</h3>
                <div class="stat-value" id="totalGpioCount">--</div>
                <div class="stat-label">All GPIO triggers recorded</div>
            </div>
            
            <div class="stat-card">
                <h3><i class="fas fa-film"></i> Total Sequences</h3>
                <div class="stat-value" id="totalSequenceCount">--</div>
                <div class="stat-label">All sequences played</div>
            </div>
            
            <div class="stat-card">
                <h3><i class="fas fa-list"></i> Total Playlists</h3>
                <div class="stat-value" id="totalPlaylistCount">--</div>
                <div class="stat-label">All playlists started</div>
            </div>
        </div>
        
        <!-- Top Sequences Table -->
        <div class="table-section">
            <h2><i class="fas fa-trophy"></i> Top 10 Most Played Sequences</h2>
            <div id="topSequencesLoading" class="loading">
                <i class="fas fa-spinner fa-spin"></i> Loading...
            </div>
            <table id="topSequencesTable" style="display:none;">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Sequence Name</th>
                        <th>Play Count</th>
                        <th>Total Duration</th>
                    </tr>
                </thead>
                <tbody id="topSequencesBody"></tbody>
            </table>
            <div id="topSequencesEmpty" class="no-data" style="display:none;">
                No sequence data available yet. Sequences will appear here once they start playing.
            </div>
        </div>
        
        <!-- Top GPIO Pins Table -->
        <div class="table-section">
            <h2><i class="fas fa-bolt"></i> Top 10 Most Active GPIO Pins</h2>
            <div id="topGpioLoading" class="loading">
                <i class="fas fa-spinner fa-spin"></i> Loading...
            </div>
            <table id="topGpioTable" style="display:none;">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Pin Number</th>
                        <th>Event Count</th>
                    </tr>
                </thead>
                <tbody id="topGpioBody"></tbody>
            </table>
            <div id="topGpioEmpty" class="no-data" style="display:none;">
                No GPIO data available yet. GPIO events will appear here once detected.
            </div>
        </div>
        
        <!-- Recent Sequence History -->
        <div class="table-section">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h2><i class="fas fa-history"></i> Recent Sequence History</h2>
                <div>
                    <button class="refresh-btn" style="background-color: #28a745; padding: 6px 12px; font-size: 13px;" onclick="exportData('sequence_history', 'csv')">
                        <i class="fas fa-file-csv"></i> CSV
                    </button>
                    <button class="refresh-btn" style="background-color: #17a2b8; padding: 6px 12px; font-size: 13px; margin-left: 5px;" onclick="exportData('sequence_history', 'json')">
                        <i class="fas fa-file-code"></i> JSON
                    </button>
                </div>
            </div>
            <div class="filter-section">
                <label for="seqStartDate">From:</label>
                <input type="date" id="seqStartDate" />
                <label for="seqEndDate">To:</label>
                <input type="date" id="seqEndDate" />
                <label for="seqEventType">Event Type:</label>
                <select id="seqEventType">
                    <option value="">All</option>
                    <option value="start">Start Only</option>
                    <option value="stop">Stop Only</option>
                </select>
                <button onclick="applySequenceFilters()">
                    <i class="fas fa-filter"></i> Apply Filters
                </button>
                <button class="clear-btn" onclick="clearSequenceFilters()">
                    <i class="fas fa-times"></i> Clear
                </button>
            </div>
            <div id="sequenceHistoryLoading" class="loading">
                <i class="fas fa-spinner fa-spin"></i> Loading...
            </div>
            <table id="sequenceHistoryTable" style="display:none;">
                <thead>
                    <tr>
                        <th>Timestamp</th>
                        <th>Sequence Name</th>
                        <th>Event Type</th>
                        <th>Playlist</th>
                        <th>Duration (sec)</th>
                    </tr>
                </thead>
                <tbody id="sequenceHistoryBody"></tbody>
            </table>
            <div id="sequenceHistoryEmpty" class="no-data" style="display:none;">
                No sequence history available yet.
            </div>
            <div id="sequenceHistoryPagination" class="pagination" style="display:none;">
                <button id="seqPrevBtn" onclick="loadSequenceHistory('prev')">
                    <i class="fas fa-chevron-left"></i> Previous
                </button>
                <span class="page-info" id="seqPageInfo">Page 1</span>
                <button id="seqNextBtn" onclick="loadSequenceHistory('next')">
                    Next <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
        
        <!-- Recent GPIO Events -->
        <div class="table-section">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h2><i class="fas fa-microchip"></i> Recent GPIO Events</h2>
                <div>
                    <button class="refresh-btn" style="background-color: #28a745; padding: 6px 12px; font-size: 13px;" onclick="exportData('gpio_events', 'csv')">
                        <i class="fas fa-file-csv"></i> CSV
                    </button>
                    <button class="refresh-btn" style="background-color: #17a2b8; padding: 6px 12px; font-size: 13px; margin-left: 5px;" onclick="exportData('gpio_events', 'json')">
                        <i class="fas fa-file-code"></i> JSON
                    </button>
                </div>
            </div>
            <div class="filter-section">
                <label for="gpioStartDate">From:</label>
                <input type="date" id="gpioStartDate" />
                <label for="gpioEndDate">To:</label>
                <input type="date" id="gpioEndDate" />
                <label for="gpioPin">Pin:</label>
                <select id="gpioPin">
                    <option value="">All Pins</option>
                </select>
                <button onclick="applyGPIOFilters()">
                    <i class="fas fa-filter"></i> Apply Filters
                </button>
                <button class="clear-btn" onclick="clearGPIOFilters()">
                    <i class="fas fa-times"></i> Clear
                </button>
            </div>
            <div id="gpioEventsLoading" class="loading">
                <i class="fas fa-spinner fa-spin"></i> Loading...
            </div>
            <table id="gpioEventsTable" style="display:none;">
                <thead>
                    <tr>
                        <th>Timestamp</th>
                        <th>Pin Number</th>
                        <th>State</th>
                    </tr>
                </thead>
                <tbody id="gpioEventsBody"></tbody>
            </table>
            <div id="gpioEventsEmpty" class="no-data" style="display:none;">
                No GPIO events recorded yet.
            </div>
            <div id="gpioEventsPagination" class="pagination" style="display:none;">
                <button id="gpioPrevBtn" onclick="loadGPIOEvents('prev')">
                    <i class="fas fa-chevron-left"></i> Previous
                </button>
                <span class="page-info" id="gpioPageInfo">Page 1</span>
                <button id="gpioNextBtn" onclick="loadGPIOEvents('next')">
                    Next <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
    
    <script>
        // Format seconds to readable time
        function formatDuration(seconds) {
            if (!seconds || isNaN(seconds) || seconds === null) return '0s';
            seconds = parseInt(seconds);
            if (seconds < 60) return seconds + 's';
            if (seconds < 3600) return Math.floor(seconds / 60) + 'm ' + (seconds % 60) + 's';
            return Math.floor(seconds / 3600) + 'h ' + Math.floor((seconds % 3600) / 60) + 'm';
        }
        
        // Format timestamp
        function formatTimestamp(timestamp) {
            const date = new Date(timestamp);
            return date.toLocaleString();
        }
        
        // Show error message
        function showError(message) {
            const errorContainer = document.getElementById('errorContainer');
            errorContainer.innerHTML = '<div class="error"><strong>Error:</strong> ' + message + '</div>';
        }
        
        // Load dashboard summary data
        function loadDashboardData() {
            fetch('/api/plugin/fpp-plugin-AdvancedStats/dashboard-data')
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        showError('Failed to load dashboard data');
                        return;
                    }
                    
                    // Update today's stats
                    document.getElementById('todayGpioCount').textContent = data.today.gpio_events_count;
                    document.getElementById('todaySequenceCount').textContent = data.today.sequences_played;
                    document.getElementById('todayPlaylistCount').textContent = data.today.playlists_started;
                    document.getElementById('todayDuration').textContent = formatDuration(data.today.total_sequence_duration);
                    
                    // Update all-time totals
                    document.getElementById('totalGpioCount').textContent = data.totals.gpio_events;
                    document.getElementById('totalSequenceCount').textContent = data.totals.sequences;
                    document.getElementById('totalPlaylistCount').textContent = data.totals.playlists;
                    
                    // Update top sequences table
                    const topSeqBody = document.getElementById('topSequencesBody');
                    const topSeqTable = document.getElementById('topSequencesTable');
                    const topSeqLoading = document.getElementById('topSequencesLoading');
                    const topSeqEmpty = document.getElementById('topSequencesEmpty');
                    
                    topSeqLoading.style.display = 'none';
                    if (data.top_sequences.length > 0) {
                        topSeqTable.style.display = 'table';
                        topSeqEmpty.style.display = 'none';
                        topSeqBody.innerHTML = data.top_sequences.map((seq, idx) => `
                            <tr>
                                <td>${idx + 1}</td>
                                <td>${seq.sequence_name}</td>
                                <td>${seq.play_count}</td>
                                <td>${formatDuration(seq.total_duration)}</td>
                            </tr>
                        `).join('');
                    } else {
                        topSeqTable.style.display = 'none';
                        topSeqEmpty.style.display = 'block';
                    }
                    
                    // Update top GPIO pins table
                    const topGpioBody = document.getElementById('topGpioBody');
                    const topGpioTable = document.getElementById('topGpioTable');
                    const topGpioLoading = document.getElementById('topGpioLoading');
                    const topGpioEmpty = document.getElementById('topGpioEmpty');
                    
                    topGpioLoading.style.display = 'none';
                    if (data.top_gpio_pins.length > 0) {
                        topGpioTable.style.display = 'table';
                        topGpioEmpty.style.display = 'none';
                        topGpioBody.innerHTML = data.top_gpio_pins.map((pin, idx) => `
                            <tr>
                                <td>${idx + 1}</td>
                                <td>${pin.pin_number}</td>
                                <td>${pin.event_count}</td>
                            </tr>
                        `).join('');
                    } else {
                        topGpioTable.style.display = 'none';
                        topGpioEmpty.style.display = 'block';
                    }
                })
                .catch(error => {
                    showError('Failed to load dashboard data: ' + error.message);
                });
        }
        
        // Pagination state
        let sequencePage = { offset: 0, limit: 50, total: 0 };
        let gpioPage = { offset: 0, limit: 50, total: 0 };
        
        // Filter state
        let sequenceFilters = {};
        let gpioFilters = {};
        
        // Apply sequence filters
        function applySequenceFilters() {
            sequenceFilters = {};
            const startDate = document.getElementById('seqStartDate').value;
            const endDate = document.getElementById('seqEndDate').value;
            const eventType = document.getElementById('seqEventType').value;
            
            if (startDate) sequenceFilters.start_date = startDate;
            if (endDate) sequenceFilters.end_date = endDate;
            if (eventType) sequenceFilters.event_type = eventType;
            
            sequencePage.offset = 0; // Reset to first page
            loadSequenceHistory();
        }
        
        // Clear sequence filters
        function clearSequenceFilters() {
            document.getElementById('seqStartDate').value = '';
            document.getElementById('seqEndDate').value = '';
            document.getElementById('seqEventType').value = '';
            sequenceFilters = {};
            sequencePage.offset = 0;
            loadSequenceHistory();
        }
        
        // Apply GPIO filters
        function applyGPIOFilters() {
            gpioFilters = {};
            const startDate = document.getElementById('gpioStartDate').value;
            const endDate = document.getElementById('gpioEndDate').value;
            const pin = document.getElementById('gpioPin').value;
            
            if (startDate) gpioFilters.start_date = startDate;
            if (endDate) gpioFilters.end_date = endDate;
            if (pin) gpioFilters.pin = pin;
            
            gpioPage.offset = 0; // Reset to first page
            loadGPIOEvents();
        }
        
        // Clear GPIO filters
        function clearGPIOFilters() {
            document.getElementById('gpioStartDate').value = '';
            document.getElementById('gpioEndDate').value = '';
            document.getElementById('gpioPin').value = '';
            gpioFilters = {};
            gpioPage.offset = 0;
            loadGPIOEvents();
        }
        
        // Load sequence history
        function loadSequenceHistory(action) {
            if (action === 'prev' && sequencePage.offset > 0) {
                sequencePage.offset -= sequencePage.limit;
            } else if (action === 'next' && sequencePage.offset + sequencePage.limit < sequencePage.total) {
                sequencePage.offset += sequencePage.limit;
            } else if (action === 'prev' || action === 'next') {
                return; // Don't reload if can't move
            }
            
            // Build query string with filters
            let queryParams = `limit=${sequencePage.limit}&offset=${sequencePage.offset}`;
            for (let key in sequenceFilters) {
                queryParams += `&${key}=${encodeURIComponent(sequenceFilters[key])}`;
            }
            
            fetch(`/api/plugin/fpp-plugin-AdvancedStats/sequence-history?${queryParams}`)
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        showError('Failed to load sequence history');
                        return;
                    }
                    
                    const seqHistBody = document.getElementById('sequenceHistoryBody');
                    const seqHistTable = document.getElementById('sequenceHistoryTable');
                    const seqHistLoading = document.getElementById('sequenceHistoryLoading');
                    const seqHistEmpty = document.getElementById('sequenceHistoryEmpty');
                    const seqHistPagination = document.getElementById('sequenceHistoryPagination');
                    
                    seqHistLoading.style.display = 'none';
                    
                    // Update pagination state
                    sequencePage.total = data.total || 0;
                    
                    if (data.sequences && data.sequences.length > 0) {
                        seqHistTable.style.display = 'table';
                        seqHistEmpty.style.display = 'none';
                        seqHistBody.innerHTML = data.sequences.map(seq => `
                            <tr>
                                <td>${formatTimestamp(seq.timestamp * 1000)}</td>
                                <td>${seq.sequence_name}</td>
                                <td><span class="badge ${seq.event_type === 'start' ? 'badge-success' : 'badge-warning'}">${seq.event_type}</span></td>
                                <td>${seq.playlist_name || 'N/A'}</td>
                                <td>${seq.duration}</td>
                            </tr>
                        `).join('');
                        
                        // Show pagination if more than one page
                        if (sequencePage.total > sequencePage.limit) {
                            seqHistPagination.style.display = 'flex';
                            const currentPage = Math.floor(sequencePage.offset / sequencePage.limit) + 1;
                            const totalPages = Math.ceil(sequencePage.total / sequencePage.limit);
                            document.getElementById('seqPageInfo').textContent = `Page ${currentPage} of ${totalPages} (${sequencePage.total} total)`;
                            document.getElementById('seqPrevBtn').disabled = sequencePage.offset === 0;
                            document.getElementById('seqNextBtn').disabled = sequencePage.offset + sequencePage.limit >= sequencePage.total;
                        } else {
                            seqHistPagination.style.display = 'none';
                        }
                    } else {
                        seqHistTable.style.display = 'none';
                        seqHistEmpty.style.display = 'block';
                        seqHistPagination.style.display = 'none';
                    }
                })
                .catch(error => {
                    showError('Failed to load sequence history: ' + error.message);
                });
        }
        
        // Load GPIO events
        function loadGPIOEvents(action) {
            if (action === 'prev' && gpioPage.offset > 0) {
                gpioPage.offset -= gpioPage.limit;
            } else if (action === 'next' && gpioPage.offset + gpioPage.limit < gpioPage.total) {
                gpioPage.offset += gpioPage.limit;
            } else if (action === 'prev' || action === 'next') {
                return; // Don't reload if can't move
            }
            
            // Build query string with filters
            let queryParams = `limit=${gpioPage.limit}&offset=${gpioPage.offset}`;
            for (let key in gpioFilters) {
                queryParams += `&${key}=${encodeURIComponent(gpioFilters[key])}`;
            }
            
            fetch(`/api/plugin/fpp-plugin-AdvancedStats/gpio-events?${queryParams}`)
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        showError('Failed to load GPIO events');
                        return;
                    }
                    
                    const gpioBody = document.getElementById('gpioEventsBody');
                    const gpioTable = document.getElementById('gpioEventsTable');
                    const gpioLoading = document.getElementById('gpioEventsLoading');
                    const gpioEmpty = document.getElementById('gpioEventsEmpty');
                    const gpioPagination = document.getElementById('gpioEventsPagination');
                    
                    gpioLoading.style.display = 'none';
                    
                    // Update pagination state
                    gpioPage.total = data.total || 0;
                    
                    if (data.events && data.events.length > 0) {
                        gpioTable.style.display = 'table';
                        gpioEmpty.style.display = 'none';
                        gpioBody.innerHTML = data.events.map(event => `
                            <tr>
                                <td>${formatTimestamp(event.timestamp * 1000)}</td>
                                <td>GPIO ${event.pin_number}</td>
                                <td>${event.pin_state == 1 ? 'HIGH' : 'LOW'}</td>
                            </tr>
                        `).join('');
                        
                        // Show pagination if more than one page
                        if (gpioPage.total > gpioPage.limit) {
                            gpioPagination.style.display = 'flex';
                            const currentPage = Math.floor(gpioPage.offset / gpioPage.limit) + 1;
                            const totalPages = Math.ceil(gpioPage.total / gpioPage.limit);
                            document.getElementById('gpioPageInfo').textContent = `Page ${currentPage} of ${totalPages} (${gpioPage.total} total)`;
                            document.getElementById('gpioPrevBtn').disabled = gpioPage.offset === 0;
                            document.getElementById('gpioNextBtn').disabled = gpioPage.offset + gpioPage.limit >= gpioPage.total;
                        } else {
                            gpioPagination.style.display = 'none';
                        }
                    } else {
                        gpioTable.style.display = 'none';
                        gpioEmpty.style.display = 'block';
                        gpioPagination.style.display = 'none';
                    }
                })
                .catch(error => {
                    showError('Failed to load GPIO events: ' + error.message);
                });
        }
        
        // Load all data
        function loadAllData() {
            document.getElementById('errorContainer').innerHTML = '';
            loadDashboardData();
            loadSequenceHistory();
            loadGPIOEvents();
            document.getElementById('lastUpdate').textContent = 'Last updated: ' + new Date().toLocaleString();
        }
        
        // Backup database
        function backupDatabase() {
            window.location.href = '/api/plugin/fpp-plugin-AdvancedStats/backup-database';
        }
        
        // Restore database
        function restoreDatabase(file) {
            if (!file) return;
            
            if (!confirm('Are you sure you want to restore from this backup? This will replace your current database. A safety backup will be created automatically.')) {
                document.getElementById('restoreFileInput').value = '';
                return;
            }
            
            const formData = new FormData();
            formData.append('database', file);
            
            fetch('/api/plugin/fpp-plugin-AdvancedStats/restore-database', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Database restored successfully! Reloading data...');
                    loadAllData();
                } else {
                    alert('Restore failed: ' + data.message);
                }
                document.getElementById('restoreFileInput').value = '';
            })
            .catch(error => {
                alert('Restore error: ' + error.message);
                document.getElementById('restoreFileInput').value = '';
            });
        }
        
        // Export data
        function exportData(table, format) {
            window.location.href = `/api/plugin/fpp-plugin-AdvancedStats/export-data?table=${table}&format=${format}`;
        }
        
        // Auto-load on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadAllData();
        });
    </script>
</body>
</html>
