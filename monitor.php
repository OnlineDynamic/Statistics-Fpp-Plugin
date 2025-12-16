<?php include_once(__DIR__ . '/mqtt_warning.inc.php'); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Live Event Monitor</title>
    <link rel="stylesheet" href="/css/fpp.css" />
    <?php echo getMQTTWarningStyles(); ?>
    <style>
        .monitor-container {
            max-width: 1600px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .monitor-header {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
        }
        
        .monitor-header h1 {
            color: #4CAF50;
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
        
        .live-monitor {
            background-color: #1a1a1a;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        
        .live-monitor-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .control-group {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        
        .live-monitor-controls button {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            transition: all 0.2s;
            font-weight: 500;
        }
        
        .btn-pause {
            background-color: #FFC107;
            color: #000;
        }
        
        .btn-pause:hover {
            background-color: #FFB300;
        }
        
        .btn-clear {
            background-color: #f44336;
            color: white;
        }
        
        .btn-clear:hover {
            background-color: #d32f2f;
        }
        
        .filter-checkbox {
            display: inline-flex;
            align-items: center;
            color: #fff;
            font-size: 13px;
            margin-right: 15px;
        }
        
        .filter-checkbox input[type="checkbox"] {
            margin-right: 5px;
            margin-left: 0;
        }
        
        .event-stream {
            background-color: #000;
            border: 1px solid #333;
            border-radius: 4px;
            padding: 15px;
            height: calc(100vh - 300px);
            min-height: 400px;
            overflow-y: auto;
            font-family: 'Courier New', monospace;
            font-size: 13px;
        }
        
        .event-item {
            padding: 6px 10px;
            margin-bottom: 6px;
            border-left: 4px solid #666;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .event-item.hidden {
            display: none;
        }
        
        .event-item.sequence {
            border-left-color: #007bff;
            background-color: rgba(0, 123, 255, 0.1);
        }
        
        .event-item.playlist {
            border-left-color: #28a745;
            background-color: rgba(40, 167, 69, 0.1);
        }
        
        .event-item.gpio {
            border-left-color: #ffc107;
            background-color: rgba(255, 193, 7, 0.1);
        }
        
        .event-item.command {
            border-left-color: #9c27b0;
            background-color: rgba(156, 39, 176, 0.1);
        }
        
        .event-item.command_preset {
            border-left-color: #e91e63;
            background-color: rgba(233, 30, 99, 0.1);
        }
        
        .event-time {
            color: #888;
            min-width: 90px;
            font-weight: 500;
        }
        
        .event-type {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 11px;
            min-width: 70px;
            text-align: center;
        }
        
        .event-type.sequence { background-color: #007bff; color: white; }
        .event-type.playlist { background-color: #28a745; color: white; }
        .event-type.gpio { background-color: #ffc107; color: black; }
        .event-type.command { background-color: #9c27b0; color: white; }
        .event-type.command_preset { background-color: #e91e63; color: white; }
        
        .event-details {
            color: #ddd;
            flex: 1;
        }
        
        .event-action {
            color: #aaa;
            font-style: italic;
            margin-right: 8px;
        }
        
        .stats-bar {
            display: flex;
            justify-content: space-around;
            background-color: #2a2a2a;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .stat-item {
            text-align: center;
            color: #fff;
        }
        
        .stat-value {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .stat-value.sequence { color: #007bff; }
        .stat-value.playlist { color: #28a745; }
        .stat-value.gpio { color: #ffc107; }
        .stat-value.command { color: #9c27b0; }
        .stat-value.command_preset { color: #e91e63; }
        
        .stat-label {
            font-size: 12px;
            color: #aaa;
        }
    </style>
</head>
<body>
    <div class="monitor-container">
        <div class="monitor-header">
            <div class="header-buttons">
                <a href="/plugin.php?plugin=fpp-plugin-AdvancedStats&page=advancedstats.php"><i class="fas fa-chart-line"></i> Dashboard</a>
                <a href="/plugin.php?plugin=fpp-plugin-AdvancedStats&page=content.php"><i class="fas fa-cog"></i> Settings</a>
                <a href="/plugin.php?plugin=fpp-plugin-AdvancedStats&page=help/advancedstats-help.php"><i class="fas fa-question-circle"></i> Help</a>
            </div>
            <h1><i class="fas fa-broadcast-tower"></i> Live Event Monitor</h1>
            <p style="color: #666;">Real-time monitoring of FPP sequences, playlists, and GPIO events</p>
        </div>
        
        <?php displayMQTTWarning(); ?>
        
        <div class="stats-bar">
            <div class="stat-item">
                <div class="stat-value sequence" id="monitorSequenceCount">0</div>
                <div class="stat-label">Sequence Events</div>
            </div>
            <div class="stat-item">
                <div class="stat-value playlist" id="monitorPlaylistCount">0</div>
                <div class="stat-label">Playlist Events</div>
            </div>
            <div class="stat-item">
                <div class="stat-value gpio" id="monitorGpioCount">0</div>
                <div class="stat-label">GPIO Events</div>
            </div>
            <div class="stat-item">
                <div class="stat-value command" id="monitorCommandCount">0</div>
                <div class="stat-label">Commands</div>
            </div>
            <div class="stat-item">
                <div class="stat-value command_preset" id="monitorCommandPresetCount">0</div>
                <div class="stat-label">Command Presets</div>
            </div>
            <div class="stat-item">
                <div class="stat-value" style="color: #fff;" id="monitorTotalCount">0</div>
                <div class="stat-label">Total Events</div>
            </div>
        </div>
        
        <div class="live-monitor">
            <div class="live-monitor-controls">
                <div class="control-group">
                    <button class="btn-pause" id="monitorPauseBtn" onclick="toggleMonitorPause()">
                        <i class="fas fa-pause"></i> Pause
                    </button>
                    <button class="btn-clear" onclick="clearEventStream()">
                        <i class="fas fa-trash"></i> Clear
                    </button>
                </div>
                
                <div class="control-group">
                    <label class="filter-checkbox">
                        <input type="checkbox" id="filterSequence" checked onchange="updateFilters()" /> Sequences
                    </label>
                    <label class="filter-checkbox">
                        <input type="checkbox" id="filterPlaylist" checked onchange="updateFilters()" /> Playlists
                    </label>
                    <label class="filter-checkbox">
                        <input type="checkbox" id="filterGpio" checked onchange="updateFilters()" /> GPIO
                    </label>
                    <label class="filter-checkbox">
                        <input type="checkbox" id="filterCommand" checked onchange="updateFilters()" /> Commands
                    </label>
                    <label class="filter-checkbox">
                        <input type="checkbox" id="filterCommandPreset" checked onchange="updateFilters()" /> Presets
                    </label>
                    <label class="filter-checkbox">
                        <input type="checkbox" id="monitorAutoScroll" checked /> Auto-scroll
                    </label>
                </div>
            </div>
            
            <div class="event-stream" id="eventStream">
                <div style="color: #888; text-align: center; padding: 20px;">Monitoring for events...</div>
            </div>
        </div>
    </div>
    
    <script>
        let monitorPaused = false;
        let monitorInterval = null;
        let lastEventTime = Math.floor(Date.now() / 1000) - 60;
        const maxEvents = 100;
        let eventCounts = { sequence: 0, playlist: 0, gpio: 0, command: 0, command_preset: 0 };
        
        function startEventMonitor() {
            if (monitorInterval) return;
            
            // Initial load
            fetchLiveEvents();
            
            // Poll every 2 seconds
            monitorInterval = setInterval(() => {
                if (!monitorPaused) {
                    fetchLiveEvents();
                }
            }, 2000);
        }
        
        function getActiveTypes() {
            const types = [];
            if (document.getElementById('filterSequence').checked) types.push('sequence');
            if (document.getElementById('filterPlaylist').checked) types.push('playlist');
            if (document.getElementById('filterGpio').checked) types.push('gpio');
            if (document.getElementById('filterCommand').checked) types.push('command');
            if (document.getElementById('filterCommandPreset').checked) types.push('command_preset');
            return types.join(',');
        }
        
        function updateFilters() {
            // Apply filters to visible events
            const showSequence = document.getElementById('filterSequence').checked;
            const showPlaylist = document.getElementById('filterPlaylist').checked;
            const showGpio = document.getElementById('filterGpio').checked;
            const showCommand = document.getElementById('filterCommand').checked;
            const showCommandPreset = document.getElementById('filterCommandPreset').checked;
            
            const stream = document.getElementById('eventStream');
            const events = stream.getElementsByClassName('event-item');
            
            for (let event of events) {
                if (event.classList.contains('sequence')) {
                    event.classList.toggle('hidden', !showSequence);
                } else if (event.classList.contains('playlist')) {
                    event.classList.toggle('hidden', !showPlaylist);
                } else if (event.classList.contains('gpio')) {
                    event.classList.toggle('hidden', !showGpio);
                } else if (event.classList.contains('command')) {
                    event.classList.toggle('hidden', !showCommand);
                } else if (event.classList.contains('command_preset')) {
                    event.classList.toggle('hidden', !showCommandPreset);
                }
            }
        }
        
        function fetchLiveEvents() {
            // Fetch all types, filter display client-side
            fetch(`/api/plugin/fpp-plugin-AdvancedStats/events/stream?since=${lastEventTime}&limit=30`)
                .then(response => response.json())
                .then(data => {
                    if (!data.success || data.count === 0) return;
                    
                    const stream = document.getElementById('eventStream');
                    const autoScroll = document.getElementById('monitorAutoScroll').checked;
                    
                    // Update last event time
                    if (data.events.length > 0) {
                        lastEventTime = data.events[0].timestamp;
                    }
                    
                    // Add new events (they come newest first, so reverse)
                    data.events.reverse().forEach(event => {
                        const eventDiv = createEventElement(event);
                        
                        // Apply current filters to new event
                        const showSequence = document.getElementById('filterSequence').checked;
                        const showPlaylist = document.getElementById('filterPlaylist').checked;
                        const showGpio = document.getElementById('filterGpio').checked;
                        const showCommand = document.getElementById('filterCommand').checked;
                        const showCommandPreset = document.getElementById('filterCommandPreset').checked;
                        
                        if (event.source === 'sequence' && !showSequence) {
                            eventDiv.classList.add('hidden');
                        } else if (event.source === 'playlist' && !showPlaylist) {
                            eventDiv.classList.add('hidden');
                        } else if (event.source === 'gpio' && !showGpio) {
                            eventDiv.classList.add('hidden');
                        } else if (event.source === 'command' && !showCommand) {
                            eventDiv.classList.add('hidden');
                        } else if (event.source === 'command_preset' && !showCommandPreset) {
                            eventDiv.classList.add('hidden');
                        }
                        
                        // Update counts
                        eventCounts[event.source]++;
                        
                        // Remove old events if too many
                        while (stream.children.length >= maxEvents) {
                            stream.removeChild(stream.lastChild);
                        }
                        
                        // Add to top
                        stream.insertBefore(eventDiv, stream.firstChild);
                    });
                    
                    // Update stats
                    updateStats();
                    
                    // Auto-scroll to top if enabled
                    if (autoScroll) {
                        stream.scrollTop = 0;
                    }
                })
                .catch(error => {
                    console.error('Failed to fetch live events:', error);
                });
        }
        
        function createEventElement(event) {
            const div = document.createElement('div');
            div.className = `event-item ${event.source}`;
            
            const time = new Date(event.timestamp * 1000);
            const timeStr = time.toLocaleTimeString();
            
            const timeSpan = document.createElement('span');
            timeSpan.className = 'event-time';
            timeSpan.textContent = timeStr;
            
            const typeSpan = document.createElement('span');
            typeSpan.className = `event-type ${event.source}`;
            typeSpan.textContent = event.source.toUpperCase();
            
            const actionSpan = document.createElement('span');
            actionSpan.className = 'event-action';
            actionSpan.textContent = event.event_type;
            
            const detailsSpan = document.createElement('span');
            detailsSpan.className = 'event-details';
            
            if (event.source === 'sequence') {
                detailsSpan.textContent = `${event.name}${event.playlist_name ? ' (playlist: ' + event.playlist_name + ')' : ''}${event.duration > 0 ? ' - ' + event.duration + 's' : ''}`;
            } else if (event.source === 'playlist') {
                detailsSpan.textContent = event.name;
            } else if (event.source === 'gpio') {
                detailsSpan.textContent = `Pin ${event.name} (${event.playlist_name || 'no desc'}) → ${event.duration === 1 ? 'HIGH' : 'LOW'}`;
            } else if (event.source === 'command') {
                const args = event.playlist_name && event.playlist_name !== '[]' ? ` (${event.playlist_name})` : '';
                detailsSpan.textContent = `${event.name}${args}`;
            } else if (event.source === 'command_preset') {
                detailsSpan.textContent = event.name;
            }
            
            div.appendChild(timeSpan);
            div.appendChild(typeSpan);
            div.appendChild(actionSpan);
            div.appendChild(detailsSpan);
            
            return div;
        }
        
        function updateStats() {
            document.getElementById('monitorSequenceCount').textContent = eventCounts.sequence;
            document.getElementById('monitorPlaylistCount').textContent = eventCounts.playlist;
            document.getElementById('monitorGpioCount').textContent = eventCounts.gpio;
            document.getElementById('monitorCommandCount').textContent = eventCounts.command;
            document.getElementById('monitorCommandPresetCount').textContent = eventCounts.command_preset;
            document.getElementById('monitorTotalCount').textContent = 
                eventCounts.sequence + eventCounts.playlist + eventCounts.gpio + eventCounts.command + eventCounts.command_preset;
        }
        
        function toggleMonitorPause() {
            monitorPaused = !monitorPaused;
            const btn = document.getElementById('monitorPauseBtn');
            
            if (monitorPaused) {
                btn.innerHTML = '<i class="fas fa-play"></i> Resume';
                btn.style.backgroundColor = '#4CAF50';
            } else {
                btn.innerHTML = '<i class="fas fa-pause"></i> Pause';
                btn.style.backgroundColor = '#FFC107';
            }
        }
        
        function clearEventStream() {
            const stream = document.getElementById('eventStream');
            stream.innerHTML = '<div style="color: #888; text-align: center; padding: 20px;">Monitoring for events...</div>';
            lastEventTime = Math.floor(Date.now() / 1000);
            eventCounts = { sequence: 0, playlist: 0, gpio: 0, command: 0, command_preset: 0 };
            updateStats();
        }
        
        // Start monitoring on page load
        document.addEventListener('DOMContentLoaded', function() {
            startEventMonitor();
        });
    </script>
</body>
</html>
