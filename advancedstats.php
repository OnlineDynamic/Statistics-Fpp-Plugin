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
    </style>
</head>
<body>
    <div class="stats-container">
        <div class="stats-header">
            <div class="header-buttons">
                <a href="help/advancedstats-help.php" target="_blank">
                    <i class="fas fa-question-circle"></i> Help
                </a>
                <a href="advancedstats-about.php">
                    <i class="fas fa-info-circle"></i> About
                </a>
            </div>
            <h1><i class="fas fa-chart-line"></i> Advanced Stats Dashboard</h1>
            <p style="color: #6c757d; font-size: 16px;">GPIO Input & Sequence Play History</p>
            <button class="refresh-btn" onclick="loadAllData()">
                <i class="fas fa-sync"></i> Refresh Data
            </button>
            <div id="lastUpdate" style="color: #6c757d; font-size: 12px; margin-top: 10px;"></div>
        </div>
        
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
            <h2><i class="fas fa-history"></i> Recent Sequence History (Last 50)</h2>
            <div id="sequenceHistoryLoading" class="loading">
                <i class="fas fa-spinner fa-spin"></i> Loading...
            </div>
            <table id="sequenceHistoryTable" style="display:none;">
                <thead>
                    <tr>
                        <th>Timestamp</th>
                        <th>Sequence Name</th>
                        <th>Playlist</th>
                        <th>Duration (sec)</th>
                    </tr>
                </thead>
                <tbody id="sequenceHistoryBody"></tbody>
            </table>
            <div id="sequenceHistoryEmpty" class="no-data" style="display:none;">
                No sequence history available yet.
            </div>
        </div>
        
        <!-- Recent GPIO Events -->
        <div class="table-section">
            <h2><i class="fas fa-microchip"></i> Recent GPIO Events (Last 50)</h2>
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
        
        // Load sequence history
        function loadSequenceHistory() {
            fetch('/api/plugin/fpp-plugin-AdvancedStats/sequence-history?limit=50')
                .then(response => response.json())
                .then(data => {
                    const seqHistBody = document.getElementById('sequenceHistoryBody');
                    const seqHistTable = document.getElementById('sequenceHistoryTable');
                    const seqHistLoading = document.getElementById('sequenceHistoryLoading');
                    const seqHistEmpty = document.getElementById('sequenceHistoryEmpty');
                    
                    seqHistLoading.style.display = 'none';
                    if (data.length > 0) {
                        seqHistTable.style.display = 'table';
                        seqHistEmpty.style.display = 'none';
                        seqHistBody.innerHTML = data.map(seq => `
                            <tr>
                                <td>${formatTimestamp(seq.timestamp)}</td>
                                <td>${seq.sequence_name}</td>
                                <td>${seq.playlist || 'N/A'}</td>
                                <td>${seq.duration}</td>
                            </tr>
                        `).join('');
                    } else {
                        seqHistTable.style.display = 'none';
                        seqHistEmpty.style.display = 'block';
                    }
                })
                .catch(error => {
                    showError('Failed to load sequence history: ' + error.message);
                });
        }
        
        // Load GPIO events
        function loadGpioEvents() {
            fetch('/api/plugin/fpp-plugin-AdvancedStats/gpio-events?limit=50')
                .then(response => response.json())
                .then(data => {
                    const gpioBody = document.getElementById('gpioEventsBody');
                    const gpioTable = document.getElementById('gpioEventsTable');
                    const gpioLoading = document.getElementById('gpioEventsLoading');
                    const gpioEmpty = document.getElementById('gpioEventsEmpty');
                    
                    gpioLoading.style.display = 'none';
                    if (data.length > 0) {
                        gpioTable.style.display = 'table';
                        gpioEmpty.style.display = 'none';
                        gpioBody.innerHTML = data.map(event => `
                            <tr>
                                <td>${formatTimestamp(event.timestamp)}</td>
                                <td>GPIO ${event.pin_number}</td>
                                <td>${event.state == 1 ? 'HIGH' : 'LOW'}</td>
                            </tr>
                        `).join('');
                    } else {
                        gpioTable.style.display = 'none';
                        gpioEmpty.style.display = 'block';
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
            loadGpioEvents();
            document.getElementById('lastUpdate').textContent = 'Last updated: ' + new Date().toLocaleString();
        }
        
        // Auto-load on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadAllData();
        });
    </script>
</body>
</html>
