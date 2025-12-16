<?php include_once(__DIR__ . '/mqtt_warning.inc.php'); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Advanced Stats Dashboard</title>
    <link rel="stylesheet" href="/css/fpp.css" />
    <?php echo getMQTTWarningStyles(); ?>
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
        

        
        .rank-badge {
            display: inline-block;
            width: 30px;
            height: 30px;
            line-height: 30px;
            text-align: center;
            border-radius: 50%;
            font-weight: bold;
            color: white;
        }
        
        .rank-1 { background: linear-gradient(135deg, #FFD700, #FFA500); }
        .rank-2 { background: linear-gradient(135deg, #C0C0C0, #808080); }
        .rank-3 { background: linear-gradient(135deg, #CD7F32, #8B4513); }
        .rank-other { background-color: #6c757d; }
        

        
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
        .filter-section select,
        .filter-section input[type="text"] {
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
        
        /* Time-series graph styles */
        .graph-container {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .graph-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .graph-header h3 {
            margin: 0;
            color: #333;
        }
        
        .period-selector {
            display: flex;
            gap: 5px;
        }
        
        .period-btn {
            padding: 5px 12px;
            border: 1px solid #ddd;
            background: white;
            cursor: pointer;
            border-radius: 4px;
            font-size: 12px;
            transition: all 0.2s;
        }
        
        .period-btn:hover {
            background-color: #f0f0f0;
        }
        
        .period-btn.active {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }
        
        .chart-canvas {
            max-height: 300px;
        }
        
        /* Heat map styles */
        .heatmap-container {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .heatmap-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .heatmap-header h3 {
            margin: 0;
            color: #333;
        }
        
        .type-selector {
            display: flex;
            gap: 5px;
        }
        
        .type-btn {
            padding: 5px 12px;
            border: 1px solid #ddd;
            background: white;
            cursor: pointer;
            border-radius: 4px;
            font-size: 12px;
            transition: all 0.2s;
        }
        
        .type-btn:hover {
            background-color: #f0f0f0;
        }
        
        .type-btn.active {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }
        
        .heatmap-grid {
            display: grid;
            grid-template-columns: 60px repeat(24, 1fr);
            gap: 2px;
            font-size: 11px;
            overflow-x: auto;
        }
        
        .heatmap-label {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 4px;
            font-weight: 500;
            color: #666;
        }
        
        .heatmap-cell {
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 2px;
            cursor: pointer;
            transition: transform 0.1s;
            position: relative;
        }
        
        .heatmap-cell:hover {
            transform: scale(1.1);
            z-index: 10;
            box-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        
        .heatmap-cell.empty {
            background-color: #f5f5f5;
        }
        
        .heatmap-tooltip {
            position: absolute;
            background-color: rgba(0,0,0,0.8);
            color: white;
            padding: 8px 12px;
            border-radius: 4px;
            font-size: 12px;
            pointer-events: none;
            z-index: 1000;
            white-space: nowrap;
            display: none;
        }
    </style>
    <script src="/plugin.php?plugin=fpp-plugin-AdvancedStats&file=js/chart.min.js&nopage=1"></script>
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
                <button class="refresh-btn" style="background-color: #4CAF50;" onclick="window.location.href='/plugin.php?plugin=fpp-plugin-AdvancedStats&page=monitor.php'">
                    <i class="fas fa-broadcast-tower"></i> Live Monitor
                </button>
            </div>
            <div id="lastUpdate" style="color: #6c757d; font-size: 12px; margin-top: 10px;"></div>
        </div>
        
        <?php displayMQTTWarning(); ?>
        
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
            
            <div class="stat-card">
                <h3><i class="fas fa-terminal"></i> Total Commands</h3>
                <div class="stat-value" id="totalCommandCount">--</div>
                <div class="stat-label">Commands executed</div>
            </div>
            
            <div class="stat-card">
                <h3><i class="fas fa-layer-group"></i> Command Presets</h3>
                <div class="stat-value" id="totalPresetCount">--</div>
                <div class="stat-label">Presets executed</div>
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
        
        <!-- Top Playlists Table -->
        <div class="table-section">
            <h2><i class="fas fa-list-alt"></i> Top 10 Most Played Playlists</h2>
            <div id="topPlaylistsLoading" class="loading">
                <i class="fas fa-spinner fa-spin"></i> Loading...
            </div>
            <table id="topPlaylistsTable" style="display:none;">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Playlist Name</th>
                        <th>Play Count</th>
                    </tr>
                </thead>
                <tbody id="topPlaylistsBody"></tbody>
            </table>
            <div id="topPlaylistsEmpty" class="no-data" style="display:none;">
                No playlist data available yet. Playlists will appear here once they start running.
            </div>
        </div>
        
        <!-- Top GPIO Pins Table -->
        <div class="table-section">
            <h2><i class="fas fa-bolt"></i> Top 10 Most Active GPIO Events</h2>
            <div id="topGpioLoading" class="loading">
                <i class="fas fa-spinner fa-spin"></i> Loading...
            </div>
            <table id="topGpioTable" style="display:none;">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Pin Number</th>
                        <th>Description</th>
                        <th>Event Count</th>
                    </tr>
                </thead>
                <tbody id="topGpioBody"></tbody>
            </table>
            <div id="topGpioEmpty" class="no-data" style="display:none;">
                No GPIO data available yet. GPIO events will appear here once detected.
            </div>
        </div>
        
        <!-- Sequence Interruptions Alert -->
        <div id="interruptionsAlert" class="table-section" style="display:none; background-color: #fff3cd; border-color: #ffc107;">
            <h2 style="color: #856404;"><i class="fas fa-exclamation-triangle"></i> Possible Sequence Interruptions Detected</h2>
            <p style="color: #856404; margin-bottom: 15px;">
                The following sequences may have been interrupted or failed to complete properly:
            </p>
            <table id="interruptionsTable">
                <thead>
                    <tr>
                        <th>Start Time</th>
                        <th>Sequence Name</th>
                        <th>Playlist</th>
                        <th>Issue</th>
                    </tr>
                </thead>
                <tbody id="interruptionsBody"></tbody>
            </table>
        </div>
        
        <!-- Top Commands Table -->
        <div class="table-section">
            <h2><i class="fas fa-terminal"></i> Top 10 Most Used Commands</h2>
            <div id="topCommandsLoading" class="loading">
                <i class="fas fa-spinner fa-spin"></i> Loading...
            </div>
            <table id="topCommandsTable" style="display:none;">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Command</th>
                        <th>Execution Count</th>
                    </tr>
                </thead>
                <tbody id="topCommandsBody"></tbody>
            </table>
            <div id="topCommandsEmpty" class="no-data" style="display:none;">
                No command data available yet. Commands will appear here once they are executed.
            </div>
        </div>
        
        <!-- Top Command Presets Table -->
        <div class="table-section">
            <h2><i class="fas fa-layer-group"></i> Top 10 Most Used Command Presets</h2>
            <div id="topPresetsLoading" class="loading">
                <i class="fas fa-spinner fa-spin"></i> Loading...
            </div>
            <table id="topPresetsTable" style="display:none;">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Preset Name</th>
                        <th>Execution Count</th>
                        <th>Avg Commands/Exec</th>
                    </tr>
                </thead>
                <tbody id="topPresetsBody"></tbody>
            </table>
            <div id="topPresetsEmpty" class="no-data" style="display:none;">
                No preset data available yet. Presets will appear here once they are executed.
            </div>
        </div>
        
        <!-- Time-Series Graphs -->
        <div class="graph-container">
            <div class="graph-header">
                <h3><i class="fas fa-chart-line"></i> Sequence Activity Over Time</h3>
                <div class="period-selector">
                    <button class="period-btn active" onclick="changeSequencePeriod(1)">24h</button>
                    <button class="period-btn" onclick="changeSequencePeriod(7)">7d</button>
                    <button class="period-btn" onclick="changeSequencePeriod(30)">30d</button>
                    <button class="period-btn" onclick="changeSequencePeriod(90)">90d</button>
                    <button class="period-btn" onclick="changeSequencePeriod(365)">1y</button>
                </div>
            </div>
            <canvas id="sequenceChart" class="chart-canvas"></canvas>
        </div>
        
        <div class="graph-container">
            <div class="graph-header">
                <h3><i class="fas fa-chart-line"></i> Playlist Activity Over Time</h3>
                <div class="period-selector">
                    <button class="period-btn active" onclick="changePlaylistPeriod(1)">24h</button>
                    <button class="period-btn" onclick="changePlaylistPeriod(7)">7d</button>
                    <button class="period-btn" onclick="changePlaylistPeriod(30)">30d</button>
                    <button class="period-btn" onclick="changePlaylistPeriod(90)">90d</button>
                    <button class="period-btn" onclick="changePlaylistPeriod(365)">1y</button>
                </div>
            </div>
            <canvas id="playlistChart" class="chart-canvas"></canvas>
        </div>
        
        <div class="graph-container">
            <div class="graph-header">
                <h3><i class="fas fa-chart-line"></i> GPIO Activity Over Time</h3>
                <div class="period-selector">
                    <button class="period-btn active" onclick="changeGpioPeriod(1)">24h</button>
                    <button class="period-btn" onclick="changeGpioPeriod(7)">7d</button>
                    <button class="period-btn" onclick="changeGpioPeriod(30)">30d</button>
                    <button class="period-btn" onclick="changeGpioPeriod(90)">90d</button>
                    <button class="period-btn" onclick="changeGpioPeriod(365)">1y</button>
                </div>
            </div>
            <canvas id="gpioChart" class="chart-canvas"></canvas>
        </div>
        
        <!-- Activity Heat Map -->
        <div class="heatmap-container">
            <div class="heatmap-header">
                <h3><i class="fas fa-th"></i> Activity Heat Map - Peak Usage Times</h3>
                <div class="type-selector">
                    <button class="type-btn active" onclick="changeHeatMapType('sequence')">Sequences</button>
                    <button class="type-btn" onclick="changeHeatMapType('playlist')">Playlists</button>
                    <button class="type-btn" onclick="changeHeatMapType('gpio')">GPIO</button>
                </div>
            </div>
            <div id="heatmapGrid" class="heatmap-grid"></div>
            <div class="heatmap-tooltip" id="heatmapTooltip"></div>
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
                <label for="seqSearch">Search:</label>
                <input type="text" id="seqSearch" placeholder="Search sequence or playlist..." style="min-width: 200px;" />
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
                <label for="gpioSearch">Search:</label>
                <input type="text" id="gpioSearch" placeholder="Search pin or description..." style="min-width: 200px;" />
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
                        <th>Description</th>
                        <th>Event Type</th>
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
        
        <!-- Command History Section -->
        <div class="table-section">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h2><i class="fas fa-terminal"></i> Recent Command Executions</h2>
                <div>
                    <button class="refresh-btn" style="background-color: #28a745; padding: 6px 12px; font-size: 13px;" onclick="exportData('command_history', 'csv')">
                        <i class="fas fa-file-csv"></i> CSV
                    </button>
                    <button class="refresh-btn" style="background-color: #17a2b8; padding: 6px 12px; font-size: 13px; margin-left: 5px;" onclick="exportData('command_history', 'json')">
                        <i class="fas fa-file-code"></i> JSON
                    </button>
                </div>
            </div>
            <div class="filter-section">
                <label for="cmdSearch">Search:</label>
                <input type="text" id="cmdSearch" placeholder="Command name, args, or trigger..." onkeypress="if(event.key==='Enter') loadCommandHistory('search')" />
                <button class="refresh-btn" onclick="loadCommandHistory('search')">
                    <i class="fas fa-search"></i> Search
                </button>
                <button class="refresh-btn" onclick="document.getElementById('cmdSearch').value=''; loadCommandHistory('search');">
                    <i class="fas fa-times"></i> Clear
                </button>
            </div>
            <div id="commandHistoryLoading" class="loading">
                <i class="fas fa-spinner fa-spin"></i> Loading...
            </div>
            <table id="commandHistoryTable" style="display:none;">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Command</th>
                        <th>Arguments</th>
                        <th>MultiSync</th>
                        <th>Trigger</th>
                    </tr>
                </thead>
                <tbody id="commandHistoryBody"></tbody>
            </table>
            <div id="commandHistoryEmpty" class="no-data" style="display:none;">
                No commands recorded yet.
            </div>
            <div id="commandHistoryPagination" class="pagination" style="display:none;">
                <button id="cmdHistPrev" onclick="loadCommandHistory('prev')">
                    <i class="fas fa-chevron-left"></i> Previous
                </button>
                <span class="page-info" id="cmdHistPageInfo">Page 1</span>
                <button id="cmdHistNext" onclick="loadCommandHistory('next')">
                    Next <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
        
        <!-- Command Preset History Section -->
        <div class="table-section">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h2><i class="fas fa-layer-group"></i> Recent Command Preset Executions</h2>
                <div>
                    <button class="refresh-btn" style="background-color: #28a745; padding: 6px 12px; font-size: 13px;" onclick="exportData('command_preset_history', 'csv')">
                        <i class="fas fa-file-csv"></i> CSV
                    </button>
                    <button class="refresh-btn" style="background-color: #17a2b8; padding: 6px 12px; font-size: 13px; margin-left: 5px;" onclick="exportData('command_preset_history', 'json')">
                        <i class="fas fa-file-code"></i> JSON
                    </button>
                </div>
            </div>
            <div class="filter-section">
                <label for="presetSearch">Search:</label>
                <input type="text" id="presetSearch" placeholder="Preset name or trigger..." onkeypress="if(event.key==='Enter') loadPresetHistory('search')" />
                <button class="refresh-btn" onclick="loadPresetHistory('search')">
                    <i class="fas fa-search"></i> Search
                </button>
                <button class="refresh-btn" onclick="document.getElementById('presetSearch').value=''; loadPresetHistory('search');">
                    <i class="fas fa-times"></i> Clear
                </button>
            </div>
            <div id="presetHistoryLoading" class="loading">
                <i class="fas fa-spinner fa-spin"></i> Loading...
            </div>
            <table id="presetHistoryTable" style="display:none;">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Preset Name</th>
                        <th>Command Count</th>
                        <th>Trigger</th>
                    </tr>
                </thead>
                <tbody id="presetHistoryBody"></tbody>
            </table>
            <div id="presetHistoryEmpty" class="no-data" style="display:none;">
                No presets recorded yet.
            </div>
            <div id="presetHistoryPagination" class="pagination" style="display:none;">
                <button id="presetHistPrev" onclick="loadPresetHistory('prev')">
                    <i class="fas fa-chevron-left"></i> Previous
                </button>
                <span class="page-info" id="presetHistPageInfo">Page 1</span>
                <button id="presetHistNext" onclick="loadPresetHistory('next')">
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
                    document.getElementById('totalCommandCount').textContent = data.totals.commands || 0;
                    document.getElementById('totalPresetCount').textContent = data.totals.presets || 0;
                    
                    // Update top sequences table
                    const topSeqBody = document.getElementById('topSequencesBody');
                    const topSeqTable = document.getElementById('topSequencesTable');
                    const topSeqLoading = document.getElementById('topSequencesLoading');
                    const topSeqEmpty = document.getElementById('topSequencesEmpty');
                    
                    topSeqLoading.style.display = 'none';
                    if (data.top_sequences.length > 0) {
                        topSeqTable.style.display = 'table';
                        topSeqEmpty.style.display = 'none';
                        topSeqBody.innerHTML = data.top_sequences.map((seq, idx) => {
                            const rank = idx + 1;
                            const rankClass = rank === 1 ? 'rank-1' : rank === 2 ? 'rank-2' : rank === 3 ? 'rank-3' : 'rank-other';
                            const trophy = rank <= 3 ? ['🥇', '🥈', '🥉'][rank - 1] : '';
                            return `
                            <tr>
                                <td><span class="rank-badge ${rankClass}">${rank}</span> ${trophy}</td>
                                <td><strong>${seq.sequence_name}</strong></td>
                                <td><strong>${seq.play_count}</strong> plays</td>
                                <td>${formatDuration(seq.total_duration)}</td>
                            </tr>
                        `}).join('');
                    } else {
                        topSeqTable.style.display = 'none';
                        topSeqEmpty.style.display = 'block';
                    }
                    
                    // Update top playlists table
                    const topPlayBody = document.getElementById('topPlaylistsBody');
                    const topPlayTable = document.getElementById('topPlaylistsTable');
                    const topPlayLoading = document.getElementById('topPlaylistsLoading');
                    const topPlayEmpty = document.getElementById('topPlaylistsEmpty');
                    
                    topPlayLoading.style.display = 'none';
                    if (data.top_playlists && data.top_playlists.length > 0) {
                        topPlayTable.style.display = 'table';
                        topPlayEmpty.style.display = 'none';
                        topPlayBody.innerHTML = data.top_playlists.map((playlist, idx) => {
                            const rank = idx + 1;
                            const rankClass = rank === 1 ? 'rank-1' : rank === 2 ? 'rank-2' : rank === 3 ? 'rank-3' : 'rank-other';
                            const trophy = rank <= 3 ? ['🥇', '🥈', '🥉'][rank - 1] : '';
                            return `
                            <tr>
                                <td><span class="rank-badge ${rankClass}">${rank}</span> ${trophy}</td>
                                <td><strong>${playlist.playlist_name}</strong></td>
                                <td><strong>${playlist.play_count}</strong> plays</td>
                            </tr>
                        `}).join('');
                    } else {
                        topPlayTable.style.display = 'none';
                        topPlayEmpty.style.display = 'block';
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
                        topGpioBody.innerHTML = data.top_gpio_pins.map((pin, idx) => {
                            const description = pin.description || '<span style="color: #6c757d;">N/A</span>';
                            return `
                            <tr>
                                <td>${idx + 1}</td>
                                <td><code>${pin.pin_number}</code></td>
                                <td><strong>${description}</strong></td>
                                <td><strong>${pin.event_count}</strong> events</td>
                            </tr>
                        `}).join('');
                    } else {
                        topGpioTable.style.display = 'none';
                        topGpioEmpty.style.display = 'block';
                    }
                    
                    // Update top commands table
                    const topCmdBody = document.getElementById('topCommandsBody');
                    const topCmdTable = document.getElementById('topCommandsTable');
                    const topCmdLoading = document.getElementById('topCommandsLoading');
                    const topCmdEmpty = document.getElementById('topCommandsEmpty');
                    
                    topCmdLoading.style.display = 'none';
                    if (data.top_commands && data.top_commands.length > 0) {
                        topCmdTable.style.display = 'table';
                        topCmdEmpty.style.display = 'none';
                        topCmdBody.innerHTML = data.top_commands.map((cmd, idx) => {
                            const rank = idx + 1;
                            const rankClass = rank === 1 ? 'rank-1' : rank === 2 ? 'rank-2' : rank === 3 ? 'rank-3' : 'rank-other';
                            const trophy = rank <= 3 ? ['🥇', '🥈', '🥉'][rank - 1] : '';
                            return `
                            <tr>
                                <td><span class="rank-badge ${rankClass}">${rank}</span> ${trophy}</td>
                                <td><code>${cmd.command}</code></td>
                                <td><strong>${cmd.use_count}</strong> times</td>
                            </tr>
                        `}).join('');
                    } else {
                        topCmdTable.style.display = 'none';
                        topCmdEmpty.style.display = 'block';
                    }
                    
                    // Update top presets table
                    const topPresetBody = document.getElementById('topPresetsBody');
                    const topPresetTable = document.getElementById('topPresetsTable');
                    const topPresetLoading = document.getElementById('topPresetsLoading');
                    const topPresetEmpty = document.getElementById('topPresetsEmpty');
                    
                    topPresetLoading.style.display = 'none';
                    if (data.top_presets && data.top_presets.length > 0) {
                        topPresetTable.style.display = 'table';
                        topPresetEmpty.style.display = 'none';
                        topPresetBody.innerHTML = data.top_presets.map((preset, idx) => {
                            const rank = idx + 1;
                            const rankClass = rank === 1 ? 'rank-1' : rank === 2 ? 'rank-2' : rank === 3 ? 'rank-3' : 'rank-other';
                            const trophy = rank <= 3 ? ['🥇', '🥈', '🥉'][rank - 1] : '';
                            return `
                            <tr>
                                <td><span class="rank-badge ${rankClass}">${rank}</span> ${trophy}</td>
                                <td><strong>${preset.preset_name}</strong></td>
                                <td><strong>${preset.use_count}</strong> times</td>
                            </tr>
                        `}).join('');
                    } else {
                        topPresetTable.style.display = 'none';
                        topPresetEmpty.style.display = 'block';
                    }
                })
                .catch(error => {
                    showError('Failed to load dashboard data: ' + error.message);
                });
        }
        
        // Pagination state
        let sequencePage = { offset: 0, limit: 15, total: 0 };
        let gpioPage = { offset: 0, limit: 15, total: 0 };
        
        // Filter state
        let sequenceFilters = {};
        let gpioFilters = {};
        
        // Apply sequence filters
        function applySequenceFilters() {
            sequenceFilters = {};
            const startDate = document.getElementById('seqStartDate').value;
            const endDate = document.getElementById('seqEndDate').value;
            const eventType = document.getElementById('seqEventType').value;
            const searchText = document.getElementById('seqSearch').value;
            
            if (startDate) sequenceFilters.start_date = startDate;
            if (endDate) sequenceFilters.end_date = endDate;
            if (eventType) sequenceFilters.event_type = eventType;
            if (searchText) sequenceFilters.search = searchText;
            
            sequencePage.offset = 0; // Reset to first page
            loadSequenceHistory();
        }
        
        // Clear sequence filters
        function clearSequenceFilters() {
            document.getElementById('seqStartDate').value = '';
            document.getElementById('seqEndDate').value = '';
            document.getElementById('seqEventType').value = '';
            document.getElementById('seqSearch').value = '';
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
            const searchText = document.getElementById('gpioSearch').value;
            
            if (startDate) gpioFilters.start_date = startDate;
            if (endDate) gpioFilters.end_date = endDate;
            if (pin) gpioFilters.pin = pin;
            if (searchText) gpioFilters.search = searchText;
            
            gpioPage.offset = 0; // Reset to first page
            loadGPIOEvents();
        }
        
        // Clear GPIO filters
        function clearGPIOFilters() {
            document.getElementById('gpioStartDate').value = '';
            document.getElementById('gpioEndDate').value = '';
            document.getElementById('gpioPin').value = '';
            document.getElementById('gpioSearch').value = '';
            gpioFilters = {};
            gpioPage.offset = 0;
            loadGPIOEvents();
        }
        
        // Pagination and filter state for commands and presets
        let commandPage = { offset: 0, limit: 15, total: 0 };
        let presetPage = { offset: 0, limit: 15, total: 0 };
        let commandFilters = {};
        let presetFilters = {};
        
        // Apply command filters
        function applyCommandFilters() {
            commandFilters = {};
            const searchText = document.getElementById('cmdSearch').value;
            if (searchText) commandFilters.search = searchText;
            commandPage.offset = 0;
            loadCommandHistory();
        }
        
        // Clear command filters
        function clearCommandFilters() {
            document.getElementById('cmdSearch').value = '';
            commandFilters = {};
            commandPage.offset = 0;
            loadCommandHistory();
        }
        
        // Apply preset filters
        function applyPresetFilters() {
            presetFilters = {};
            const searchText = document.getElementById('presetSearch').value;
            if (searchText) presetFilters.search = searchText;
            presetPage.offset = 0;
            loadPresetHistory();
        }
        
        // Clear preset filters
        function clearPresetFilters() {
            document.getElementById('presetSearch').value = '';
            presetFilters = {};
            presetPage.offset = 0;
            loadPresetHistory();
        }
        
        // Load command history
        function loadCommandHistory(action) {
            if (action === 'prev' && commandPage.offset > 0) {
                commandPage.offset -= commandPage.limit;
            } else if (action === 'next' && commandPage.offset + commandPage.limit < commandPage.total) {
                commandPage.offset += commandPage.limit;
            } else if (action === 'prev' || action === 'next') {
                return;
            }
            
            let queryParams = `limit=${commandPage.limit}&offset=${commandPage.offset}`;
            for (let key in commandFilters) {
                queryParams += `&${key}=${encodeURIComponent(commandFilters[key])}`;
            }
            
            fetch(`/api/plugin/fpp-plugin-AdvancedStats/command-history?${queryParams}`)
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        showError('Failed to load command history');
                        return;
                    }
                    
                    const cmdHistBody = document.getElementById('commandHistoryBody');
                    const cmdHistTable = document.getElementById('commandHistoryTable');
                    const cmdHistLoading = document.getElementById('commandHistoryLoading');
                    const cmdHistEmpty = document.getElementById('commandHistoryEmpty');
                    const cmdHistPagination = document.getElementById('commandHistoryPagination');
                    
                    cmdHistLoading.style.display = 'none';
                    commandPage.total = data.total || 0;
                    
                    if (data.data && data.data.length > 0) {
                        cmdHistTable.style.display = 'table';
                        cmdHistEmpty.style.display = 'none';
                        cmdHistBody.innerHTML = data.data.map(cmd => {
                            // Parse args if it's a JSON string, otherwise use as-is
                            let argsDisplay = '--';
                            try {
                                const parsedArgs = typeof cmd.args === 'string' ? JSON.parse(cmd.args) : cmd.args;
                                if (Array.isArray(parsedArgs) && parsedArgs.length > 0) {
                                    argsDisplay = parsedArgs.join(', ');
                                } else if (parsedArgs && typeof parsedArgs === 'object' && Object.keys(parsedArgs).length > 0) {
                                    argsDisplay = JSON.stringify(parsedArgs);
                                }
                            } catch (e) {
                                argsDisplay = cmd.args || '--';
                            }
                            const multisync = cmd.multisyncCommand ? 'Yes' : 'No';
                            const source = cmd.trigger_source || 'Unknown';
                            return `
                            <tr>
                                <td>${formatTimestamp(cmd.timestamp * 1000)}</td>
                                <td><code>${cmd.command}</code></td>
                                <td>${argsDisplay}</td>
                                <td>${multisync}</td>
                                <td>${source}</td>
                            </tr>
                        `}).join('');
                        
                        // Update pagination controls
                        cmdHistPagination.style.display = 'flex';
                        const startNum = commandPage.offset + 1;
                        const endNum = Math.min(commandPage.offset + commandPage.limit, commandPage.total);
                        document.getElementById('cmdHistPageInfo').textContent = 
                            `${startNum}-${endNum} of ${commandPage.total}`;
                        document.getElementById('cmdHistPrev').disabled = commandPage.offset === 0;
                        document.getElementById('cmdHistNext').disabled = 
                            commandPage.offset + commandPage.limit >= commandPage.total;
                    } else {
                        cmdHistTable.style.display = 'none';
                        cmdHistEmpty.style.display = 'block';
                        cmdHistPagination.style.display = 'none';
                    }
                })
                .catch(error => {
                    showError('Failed to load command history: ' + error.message);
                });
        }
        
        // Load preset history
        function loadPresetHistory(action) {
            if (action === 'prev' && presetPage.offset > 0) {
                presetPage.offset -= presetPage.limit;
            } else if (action === 'next' && presetPage.offset + presetPage.limit < presetPage.total) {
                presetPage.offset += presetPage.limit;
            } else if (action === 'prev' || action === 'next') {
                return;
            }
            
            let queryParams = `limit=${presetPage.limit}&offset=${presetPage.offset}`;
            for (let key in presetFilters) {
                queryParams += `&${key}=${encodeURIComponent(presetFilters[key])}`;
            }
            
            fetch(`/api/plugin/fpp-plugin-AdvancedStats/command-preset-history?${queryParams}`)
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        showError('Failed to load preset history');
                        return;
                    }
                    
                    const presetHistBody = document.getElementById('presetHistoryBody');
                    const presetHistTable = document.getElementById('presetHistoryTable');
                    const presetHistLoading = document.getElementById('presetHistoryLoading');
                    const presetHistEmpty = document.getElementById('presetHistoryEmpty');
                    const presetHistPagination = document.getElementById('presetHistoryPagination');
                    
                    presetHistLoading.style.display = 'none';
                    presetPage.total = data.total || 0;
                    
                    if (data.data && data.data.length > 0) {
                        presetHistTable.style.display = 'table';
                        presetHistEmpty.style.display = 'none';
                        presetHistBody.innerHTML = data.data.map(preset => {
                            const source = preset.trigger_source || 'Unknown';
                            return `
                            <tr>
                                <td>${formatTimestamp(preset.timestamp * 1000)}</td>
                                <td><strong>${preset.preset_name}</strong></td>
                                <td>${preset.command_count} commands</td>
                                <td>${source}</td>
                            </tr>
                        `}).join('');
                        
                        // Update pagination controls
                        presetHistPagination.style.display = 'flex';
                        const startNum = presetPage.offset + 1;
                        const endNum = Math.min(presetPage.offset + presetPage.limit, presetPage.total);
                        document.getElementById('presetHistPageInfo').textContent = 
                            `${startNum}-${endNum} of ${presetPage.total}`;
                        document.getElementById('presetHistPrev').disabled = presetPage.offset === 0;
                        document.getElementById('presetHistNext').disabled = 
                            presetPage.offset + presetPage.limit >= presetPage.total;
                    } else {
                        presetHistTable.style.display = 'none';
                        presetHistEmpty.style.display = 'block';
                        presetHistPagination.style.display = 'none';
                    }
                })
                .catch(error => {
                    showError('Failed to load preset history: ' + error.message);
                });
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
                        seqHistBody.innerHTML = data.sequences.map(seq => {
                            // For start events, duration is sequence metadata (not playback time)
                            // For stop events, duration is actual playback time
                            const displayDuration = seq.event_type === 'start' ? 
                                '<span style="color: #6c757d;">--</span>' : 
                                formatDuration(seq.duration);
                            
                            return `
                            <tr>
                                <td>${formatTimestamp(seq.timestamp * 1000)}</td>
                                <td>${seq.sequence_name}</td>
                                <td><span class="badge ${seq.event_type === 'start' ? 'badge-success' : 'badge-warning'}">${seq.event_type}</span></td>
                                <td>${seq.playlist_name || '<span style="color: #6c757d;">N/A</span>'}</td>
                                <td>${displayDuration}</td>
                            </tr>
                        `}).join('');
                        
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
                        gpioBody.innerHTML = data.events.map(event => {
                            const description = event.description || '<span style="color: #6c757d;">N/A</span>';
                            const eventType = event.event_type || 'event';
                            const stateColor = event.pin_state == 1 ? '#28a745' : '#dc3545';
                            return `
                            <tr>
                                <td>${formatTimestamp(event.timestamp * 1000)}</td>
                                <td><code>${event.pin_number}</code></td>
                                <td><strong>${description}</strong></td>
                                <td><span style="color: #007bff;">${eventType}</span></td>
                                <td><strong style="color: ${stateColor};">${event.pin_state == 1 ? 'HIGH' : 'LOW'}</strong></td>
                            </tr>
                        `}).join('');
                        
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
            loadCommandHistory();
            loadPresetHistory();
            loadInterruptions();
            loadTimeSeriesCharts();
            document.getElementById('lastUpdate').textContent = 'Last updated: ' + new Date().toLocaleString();
        }
        
        // Load sequence interruptions
        function loadInterruptions() {
            fetch('/api/plugin/fpp-plugin-AdvancedStats/sequence-interruptions?limit=15')
                .then(response => response.json())
                .then(data => {
                    if (!data.success || data.count === 0) {
                        document.getElementById('interruptionsAlert').style.display = 'none';
                        return;
                    }
                    
                    const alertBox = document.getElementById('interruptionsAlert');
                    const tbody = document.getElementById('interruptionsBody');
                    
                    tbody.innerHTML = data.interruptions.map(item => {
                        const issue = item.duration === 0 || item.duration === null ? 
                            'No duration recorded' : 
                            'No stop event detected';
                        const playlistName = item.playlist_name || 'N/A';
                        
                        return `
                            <tr>
                                <td>${item.start_time}</td>
                                <td><strong>${item.sequence_name}</strong></td>
                                <td>${playlistName}</td>
                                <td><span style="color: #dc3545;">${issue}</span></td>
                            </tr>
                        `;
                    }).join('');
                    
                    alertBox.style.display = 'block';
                })
                .catch(error => {
                    console.error('Failed to load interruptions:', error);
                });
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
        
        // Time-series chart instances
        let sequenceChart = null;
        let playlistChart = null;
        let gpioChart = null;
        let currentSequenceDays = 1;
        let currentPlaylistDays = 1;
        let currentGpioDays = 1;
        
        // Load time-series data and render chart
        function loadTimeSeries(type, days, chartId) {
            const period = days === 1 ? 'hourly' : (days <= 7 ? 'daily' : (days <= 90 ? 'daily' : 'weekly'));
            
            fetch(`/api/plugin/fpp-plugin-AdvancedStats/stats/timeseries?type=${type}&period=${period}&days=${days}`)
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        console.error('Failed to load time-series data:', data.message);
                        return;
                    }
                    
                    // Prepare chart data
                    const labels = data.data.map(item => item.label);
                    const values = data.data.map(item => item.count);
                    
                    // Get existing chart instance and destroy if it exists
                    let chartInstance = null;
                    if (type === 'sequence' && sequenceChart) {
                        sequenceChart.destroy();
                    } else if (type === 'playlist' && playlistChart) {
                        playlistChart.destroy();
                    } else if (type === 'gpio' && gpioChart) {
                        gpioChart.destroy();
                    }
                    
                    // Create new chart
                    const ctx = document.getElementById(chartId).getContext('2d');
                    const chartColor = type === 'sequence' ? '#007bff' : (type === 'playlist' ? '#28a745' : '#ffc107');
                    
                    chartInstance = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: `${type.charAt(0).toUpperCase() + type.slice(1)} Events`,
                                data: values,
                                borderColor: chartColor,
                                backgroundColor: chartColor + '20',
                                borderWidth: 2,
                                fill: true,
                                tension: 0.4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    mode: 'index',
                                    intersect: false,
                                    callbacks: {
                                        label: function(context) {
                                            return context.dataset.label + ': ' + context.parsed.y + ' events';
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        precision: 0
                                    }
                                }
                            },
                            interaction: {
                                mode: 'nearest',
                                axis: 'x',
                                intersect: false
                            }
                        }
                    });
                    
                    // Store chart instance
                    if (type === 'sequence') sequenceChart = chartInstance;
                    else if (type === 'playlist') playlistChart = chartInstance;
                    else if (type === 'gpio') gpioChart = chartInstance;
                })
                .catch(error => {
                    console.error('Failed to load time-series data:', error);
                });
        }
        
        // Period change handlers
        function changeSequencePeriod(days) {
            currentSequenceDays = days;
            updatePeriodButtons('sequence', days);
            loadTimeSeries('sequence', days, 'sequenceChart');
        }
        
        function changePlaylistPeriod(days) {
            currentPlaylistDays = days;
            updatePeriodButtons('playlist', days);
            loadTimeSeries('playlist', days, 'playlistChart');
        }
        
        function changeGpioPeriod(days) {
            currentGpioDays = days;
            updatePeriodButtons('gpio', days);
            loadTimeSeries('gpio', days, 'gpioChart');
        }
        
        // Update active button styling
        function updatePeriodButtons(type, days) {
            const container = document.querySelector(`#${type}Chart`).closest('.graph-container');
            const buttons = container.querySelectorAll('.period-btn');
            
            buttons.forEach((btn, index) => {
                const btnDays = [1, 7, 30, 90, 365][index];
                if (btnDays === days) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });
        }
        
        // Load all time-series charts
        function loadTimeSeriesCharts() {
            loadTimeSeries('sequence', currentSequenceDays, 'sequenceChart');
            loadTimeSeries('playlist', currentPlaylistDays, 'playlistChart');
            loadTimeSeries('gpio', currentGpioDays, 'gpioChart');
        }
        
        // Heat map functions
        let currentHeatMapType = 'sequence';
        
        function loadHeatMap(type, days = 30) {
            fetch(`/api/plugin/fpp-plugin-AdvancedStats/stats/heatmap?type=${type}&days=${days}`)
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        console.error('Failed to load heat map data:', data.message);
                        return;
                    }
                    
                    renderHeatMap(data.matrix, data.max_count);
                })
                .catch(error => {
                    console.error('Failed to load heat map data:', error);
                });
        }
        
        function renderHeatMap(matrix, maxCount) {
            const grid = document.getElementById('heatmapGrid');
            grid.innerHTML = '';
            
            const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
            
            // Add top row with hour labels
            grid.appendChild(createLabel(''));
            for (let hour = 0; hour < 24; hour++) {
                grid.appendChild(createLabel(hour.toString()));
            }
            
            // Add rows for each day
            for (let day = 0; day < 7; day++) {
                grid.appendChild(createLabel(days[day]));
                
                for (let hour = 0; hour < 24; hour++) {
                    const count = matrix[day][hour];
                    const cell = document.createElement('div');
                    cell.className = 'heatmap-cell';
                    
                    if (count === 0) {
                        cell.classList.add('empty');
                    } else {
                        const intensity = maxCount > 0 ? count / maxCount : 0;
                        const color = getHeatColor(intensity);
                        cell.style.backgroundColor = color;
                    }
                    
                    // Add hover tooltip
                    cell.addEventListener('mouseenter', function(e) {
                        showTooltip(e, days[day], hour, count);
                    });
                    
                    cell.addEventListener('mouseleave', function() {
                        hideTooltip();
                    });
                    
                    grid.appendChild(cell);
                }
            }
        }
        
        function createLabel(text) {
            const label = document.createElement('div');
            label.className = 'heatmap-label';
            label.textContent = text;
            return label;
        }
        
        function getHeatColor(intensity) {
            // Color gradient from light blue to dark red
            if (intensity < 0.2) return `rgba(33, 150, 243, ${0.2 + intensity})`;
            if (intensity < 0.4) return `rgba(76, 175, 80, ${0.4 + intensity})`;
            if (intensity < 0.6) return `rgba(255, 193, 7, ${0.5 + intensity})`;
            if (intensity < 0.8) return `rgba(255, 152, 0, ${0.6 + intensity})`;
            return `rgba(244, 67, 54, ${0.7 + intensity})`;
        }
        
        function showTooltip(event, day, hour, count) {
            const tooltip = document.getElementById('heatmapTooltip');
            const hourStr = hour === 0 ? '12am' : (hour < 12 ? `${hour}am` : (hour === 12 ? '12pm' : `${hour-12}pm`));
            tooltip.textContent = `${day} ${hourStr}: ${count} events`;
            tooltip.style.display = 'block';
            tooltip.style.left = event.pageX + 10 + 'px';
            tooltip.style.top = event.pageY + 10 + 'px';
        }
        
        function hideTooltip() {
            const tooltip = document.getElementById('heatmapTooltip');
            tooltip.style.display = 'none';
        }
        
        function changeHeatMapType(type) {
            currentHeatMapType = type;
            
            // Update button states
            const buttons = document.querySelectorAll('.type-btn');
            buttons.forEach(btn => {
                const btnText = btn.textContent.toLowerCase();
                if ((type === 'sequence' && btnText === 'sequences') ||
                    (type === 'playlist' && btnText === 'playlists') ||
                    (type === 'gpio' && btnText === 'gpio')) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });
            
            loadHeatMap(type);
        }
        
        // Auto-load on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadAllData();
            loadTimeSeriesCharts();
            loadHeatMap(currentHeatMapType);
            
            // Add Enter key support for search boxes
            document.getElementById('seqSearch').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') applySequenceFilters();
            });
            
            document.getElementById('gpioSearch').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') applyGPIOFilters();
            });
        });
    </script>
</body>
</html>
