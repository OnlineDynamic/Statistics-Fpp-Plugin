<?php include_once(__DIR__ . '/mqtt_warning.inc.php'); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Advanced Stats Settings</title>
    <?php include_once('/opt/fpp/www/common/htmlMeta.inc'); ?>
    <link rel="stylesheet" href="/css/fpp.css" />
    <?php echo getMQTTWarningStyles(); ?>
    <style>
        :root {
            --bg: #ffffff;
            --text: #111111;
            --muted: #6c757d;
            --border: #dee2e6;
            --surface: #f8f9fa;
            --primary: #007bff;
            --primary-dark: #0056b3;
            --secondary: #6c757d;
            --secondary-dark: #5a6268;
            --success: #28a745;
            --warning: #ffc107;
            --danger: #dc3545;
            --info: #17a2b8;
            --panel-shadow: rgba(0,0,0,0.1);
            --input-border: #ced4da;
        }

        [data-bs-theme="dark"] {
            --bg: #0d1117;
            --text: #e6edf3;
            --muted: #9ba0ab;
            --border: #272c34;
            --surface: #161b22;
            --primary: #58a6ff;
            --primary-dark: #1f6feb;
            --secondary: #8b95a2;
            --secondary-dark: #6e7b8b;
            --success: #3fb950;
            --warning: #d29922;
            --danger: #f85149;
            --info: #5bc0de;
            --panel-shadow: rgba(0,0,0,0.4);
            --input-border: #3c434b;
        }

        html, body {
            background: var(--bg);
            color: var(--text);
        }

        .settings-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }

        .settings-header {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
        }

        .settings-header h1 {
            color: var(--primary);
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
            background-color: var(--surface);
            color: var(--text);
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            transition: background-color 0.3s;
            border: 1px solid var(--border);
        }

        .header-buttons a:hover {
            background-color: var(--primary);
            color: #fff;
            border-color: var(--primary);
        }

        .header-buttons a i {
            margin-right: 5px;
        }

        .settings-section {
            background-color: var(--surface);
            border: 2px solid var(--border);
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .settings-section h3 {
            margin-top: 0;
            color: var(--text);
            border-bottom: 2px solid var(--primary);
            padding-bottom: 10px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: var(--text);
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid var(--input-border);
            border-radius: 4px;
            font-size: 14px;
            background-color: var(--bg);
            color: var(--text);
        }

        .form-group small {
            display: block;
            color: var(--muted);
            margin-top: 5px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
        }

        .stat-card {
            background-color: var(--surface);
            border-left: 4px solid var(--primary);
            padding: 15px;
            border-radius: 4px;
        }

        .stat-card-label {
            font-size: 12px;
            color: var(--muted);
            font-weight: bold;
        }

        .stat-card-value {
            font-size: 24px;
            font-weight: bold;
        }

        .placeholder-message {
            background-color: rgba(255, 193, 7, 0.12);
            border: 2px solid var(--warning);
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }

        .placeholder-message i {
            font-size: 48px;
            color: var(--warning);
            margin-bottom: 15px;
        }

        .db-stat-card {
            padding: 15px;
            border-radius: 4px;
            background-color: var(--surface);
            border-left: 4px solid var(--primary);
        }

        .db-stat-card.success { border-left-color: var(--success); }
        .db-stat-card.warning { border-left-color: var(--warning); }
        .db-stat-card.danger { border-left-color: var(--danger); }
        .db-stat-card.info { border-left-color: var(--info); }

        .db-stat-label {
            font-size: 12px;
            color: var(--muted);
            font-weight: bold;
        }

        .db-stat-value {
            font-size: 24px;
            font-weight: bold;
            color: var(--primary);
        }

        .db-stat-card.success .db-stat-value { color: var(--success); }
        .db-stat-card.warning .db-stat-value { color: var(--warning); }
        .db-stat-card.danger .db-stat-value { color: var(--danger); }
        .db-stat-card.info .db-stat-value { color: var(--info); }

        .btn-success { background-color: var(--success); color: white; }
        .btn-success:hover { background-color: #218838; }
        .btn-info { background-color: var(--info); color: white; }
        .btn-info:hover { background-color: #138496; }
        .btn-warning { background-color: var(--warning); color: #212529; }
        .btn-warning:hover { background-color: #e0a800; }
        .btn-danger { background-color: var(--danger); color: white; }
        .btn-danger:hover { background-color: #c82333; }

        .warning-box {
            background-color: rgba(255, 193, 7, 0.12);
            border-left: 4px solid var(--warning);
            padding: 15px;
            border-radius: 4px;
        }

        .muted-text { color: var(--muted); }
    </style>
</head>
<body>
    <div class="settings-container">
        <div class="settings-header">
            <div class="header-buttons">
                <a href="plugin.php?_menu=status&plugin=fpp-plugin-AdvancedStats&page=advancedstats.php">
                    <i class="fas fa-chart-line"></i> Dashboard
                </a>
                <a href="plugin.php?_menu=status&plugin=fpp-plugin-AdvancedStats&page=help/advancedstats-help.php">
                    <i class="fas fa-question-circle"></i> Help
                </a>
            </div>
            <h1><i class="fas fa-cog"></i> Advanced Stats Settings</h1>
            <p class="muted-text" style="font-size: 16px;">Configure plugin options and preferences</p>
        </div>
        
        <?php displayMQTTWarning(); ?>
        
        <!-- Example settings form - replace with actual settings -->
        <form id="settingsForm" onsubmit="return saveSettings(event);">
            <div class="settings-section">
                <h3><i class="fas fa-sliders-h"></i> General Settings</h3>
                
                <div class="form-group">
                    <label for="enableStats">Enable Statistics Collection</label>
                    <select id="enableStats" name="enableStats">
                        <option value="1">Enabled</option>
                        <option value="0">Disabled</option>
                    </select>
                    <small>Enable or disable statistics collection for this plugin</small>
                </div>
                
                <div class="form-group">
                    <label for="updateInterval">Update Interval (seconds)</label>
                    <input type="number" id="updateInterval" name="updateInterval" value="60" min="10" max="3600">
                    <small>How often to collect statistics (10-3600 seconds)</small>
                </div>
            </div>
            
            <div class="settings-section">
                <h3><i class="fas fa-database"></i> Database Information</h3>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 20px;">
                    <div class="db-stat-card">
                        <div class="db-stat-label">DATABASE SIZE</div>
                        <div class="db-stat-value" id="dbSize">-</div>
                    </div>

                    <div class="db-stat-card">
                        <div class="db-stat-label">SEQUENCE RECORDS</div>
                        <div class="db-stat-value" id="sequenceCount">-</div>
                    </div>

                    <div class="db-stat-card success">
                        <div class="db-stat-label">PLAYLIST RECORDS</div>
                        <div class="db-stat-value" id="playlistCount">-</div>
                    </div>

                    <div class="db-stat-card warning">
                        <div class="db-stat-label">GPIO RECORDS</div>
                        <div class="db-stat-value" id="gpioCount">-</div>
                    </div>

                    <div class="db-stat-card danger">
                        <div class="db-stat-label">DAILY STATS</div>
                        <div class="db-stat-value" id="dailyStatsCount">-</div>
                    </div>

                    <div class="db-stat-card info">
                        <div class="db-stat-label">TOTAL RECORDS</div>
                        <div class="db-stat-value" id="totalRecords">-</div>
                    </div>
                </div>

                <div style="display: flex; gap: 10px; justify-content: center; flex-wrap: wrap;">
                    <button type="button" class="btn btn-info" onclick="loadDatabaseInfo()">
                        <i class="fas fa-sync-alt"></i> Refresh Database Info
                    </button>
                    <button type="button" class="btn btn-success" onclick="backupDatabase()">
                        <i class="fas fa-download"></i> Backup Database
                    </button>
                    <button type="button" class="btn btn-warning" onclick="document.getElementById('restoreFileInput').click()">
                        <i class="fas fa-upload"></i> Restore Database
                    </button>
                </div>
                <input type="file" id="restoreFileInput" accept=".db" style="display:none;" onchange="restoreDatabase(this.files[0])" />
            </div>
            
            <div class="settings-section">
                <h3><i class="fas fa-database"></i> Data Retention & Archive</h3>
                
                <div class="form-group">
                    <label for="enableAutoArchive">Enable Automatic Archiving</label>
                    <select id="enableAutoArchive" name="enableAutoArchive">
                        <option value="1">Enabled</option>
                        <option value="0">Disabled</option>
                    </select>
                    <small>Automatically delete old records based on retention policy</small>
                </div>
                
                <div class="form-group">
                    <label for="retentionDays">Data Retention Period</label>
                    <select id="retentionDays" name="retentionDays">
                        <option value="30">30 days</option>
                        <option value="60">60 days</option>
                        <option value="90">90 days (3 months)</option>
                        <option value="180">180 days (6 months)</option>
                        <option value="365" selected>365 days (1 year)</option>
                        <option value="730">730 days (2 years)</option>
                        <option value="1095">1095 days (3 years)</option>
                        <option value="1825">1825 days (5 years)</option>
                        <option value="3650">3650 days (10 years)</option>
                        <option value="-1">Never (keep all data)</option>
                    </select>
                    <small>Records older than this will be automatically deleted (if auto-archive is enabled)</small>
                </div>
                
                <div class="form-group warning-box">
                    <strong>⚠️ Warning:</strong> When auto-archive is enabled, old data will be permanently deleted.
                    Always create a backup before archiving if you might need the historical data later.
                </div>

                <div class="form-group" style="text-align: center; margin-top: 20px;">
                    <button type="button" class="btn btn-danger" onclick="showArchiveDialog()">
                        <i class="fas fa-archive"></i> Archive Old Data Now
                    </button>
                    <br>
                    <small class="muted-text" style="margin-top: 5px; display: block;">Manually delete old records with preview before deletion</small>
                </div>
            </div>
            
            <div class="settings-section">
                <h3><i class="fas fa-chart-bar"></i> Display Options</h3>
                
                <div class="form-group">
                    <label for="showCharts">Show Charts</label>
                    <select id="showCharts" name="showCharts">
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                    <small>Display charts and graphs on the dashboard</small>
                </div>
                
                <div class="form-group">
                    <label for="chartType">Default Chart Type</label>
                    <select id="chartType" name="chartType">
                        <option value="line">Line Chart</option>
                        <option value="bar">Bar Chart</option>
                        <option value="pie">Pie Chart</option>
                    </select>
                    <small>Default chart type for data visualization</small>
                </div>
            </div>
            
            <div style="text-align: center; margin-top: 20px;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Settings
                </button>
            </div>
        </form>
    </div>
    
    <script>
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
                    alert('Database restored successfully! Reloading info...');
                    loadDatabaseInfo();
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
        
        // Placeholder JavaScript - replace with actual implementation
        
        // Load existing settings when page loads
        document.addEventListener('DOMContentLoaded', function() {
            loadSettings();
            loadDatabaseInfo();
        });
        
        function loadDatabaseInfo() {
            // Get database size and record counts
            fetch('/api/plugin/fpp-plugin-AdvancedStats/database-info')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Format file size
                        const size = data.database_size;
                        let sizeStr;
                        if (size < 1024) {
                            sizeStr = size + ' B';
                        } else if (size < 1024 * 1024) {
                            sizeStr = (size / 1024).toFixed(2) + ' KB';
                        } else {
                            sizeStr = (size / (1024 * 1024)).toFixed(2) + ' MB';
                        }
                        
                        document.getElementById('dbSize').textContent = sizeStr;
                        document.getElementById('sequenceCount').textContent = data.counts.sequence_history.toLocaleString();
                        document.getElementById('playlistCount').textContent = data.counts.playlist_history.toLocaleString();
                        document.getElementById('gpioCount').textContent = data.counts.gpio_events.toLocaleString();
                        document.getElementById('dailyStatsCount').textContent = data.counts.daily_stats.toLocaleString();
                        
                        const total = data.counts.sequence_history + 
                                     data.counts.playlist_history + 
                                     data.counts.gpio_events + 
                                     data.counts.daily_stats;
                        document.getElementById('totalRecords').textContent = total.toLocaleString();
                    }
                })
                .catch(error => {
                    console.error('Failed to load database info:', error);
                });
        }
        
        function loadSettings() {
            fetch('/api/plugin/fpp-plugin-AdvancedStats/get-settings')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('enableStats').value = data.settings.enableStats || '1';
                        document.getElementById('updateInterval').value = data.settings.updateInterval || '60';
                        document.getElementById('enableAutoArchive').value = data.settings.enableAutoArchive || '0';
                        document.getElementById('retentionDays').value = data.settings.retentionDays || '365';
                        document.getElementById('showCharts').value = data.settings.showCharts || '1';
                        document.getElementById('chartType').value = data.settings.chartType || 'line';
                    }
                })
                .catch(error => {
                    console.error('Failed to load settings:', error);
                });
        }
        
        function saveSettings(event) {
            event.preventDefault();
            
            const formData = new FormData(event.target);
            const settings = Object.fromEntries(formData);
            
            fetch('/api/plugin/fpp-plugin-AdvancedStats/save-settings', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(settings)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Settings saved successfully!');
                } else {
                    alert('Error saving settings: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                alert('Error saving settings: ' + error.message);
            });
            
            return false;
        }
        
        // Archive old data
        function showArchiveDialog() {
            const retentionDays = prompt('Enter the number of days to keep (data older than this will be deleted):', '90');
            
            if (retentionDays === null) return; // Cancelled
            
            const days = parseInt(retentionDays);
            if (isNaN(days) || days < 1) {
                alert('Please enter a valid number of days (must be 1 or greater).');
                return;
            }
            
            // First, do a dry run to show what will be deleted
            fetch('/api/plugin/fpp-plugin-AdvancedStats/archive-old-data', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    retention_days: days,
                    dry_run: true
                })
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    alert('Error checking data: ' + data.message);
                    return;
                }
                
                const cutoffDate = new Date(data.cutoff_date).toLocaleString();
                
                // Calculate totals from results
                let totalRecords = 0;
                const sequenceRecords = data.results.sequence_history.records_to_delete;
                const playlistRecords = data.results.playlist_history.records_to_delete;
                const gpioRecords = data.results.gpio_events.records_to_delete;
                const statsRecords = data.results.daily_stats.records_to_delete;
                totalRecords = sequenceRecords + playlistRecords + gpioRecords + statsRecords;
                
                let message = `This will delete data older than ${cutoffDate} (${days} days ago):\n\n`;
                message += `• Sequence History: ${sequenceRecords} records\n`;
                message += `• Playlist History: ${playlistRecords} records\n`;
                message += `• GPIO Events: ${gpioRecords} records\n`;
                message += `• Daily Stats: ${statsRecords} records\n`;
                message += `\nTotal: ${totalRecords} records will be permanently deleted.\n\n`;
                message += 'This action cannot be undone! Continue?';
                
                if (totalRecords === 0) {
                    alert('No data found older than ' + days + ' days. Nothing to archive.');
                    return;
                }
                
                if (!confirm(message)) {
                    return;
                }
                
                // User confirmed, now do the actual deletion
                archiveOldData(days);
            })
            .catch(error => {
                alert('Error: ' + error.message);
            });
        }
        
        function archiveOldData(days) {
            fetch('/api/plugin/fpp-plugin-AdvancedStats/archive-old-data', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    retention_days: days,
                    dry_run: false
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const sequenceRecords = data.results.sequence_history.records_to_delete;
                    const playlistRecords = data.results.playlist_history.records_to_delete;
                    const gpioRecords = data.results.gpio_events.records_to_delete;
                    const statsRecords = data.results.daily_stats.records_to_delete;
                    const totalRecords = sequenceRecords + playlistRecords + gpioRecords + statsRecords;
                    
                    alert(`Successfully archived old data!\n\nDeleted:\n• Sequence History: ${sequenceRecords}\n• Playlist History: ${playlistRecords}\n• GPIO Events: ${gpioRecords}\n• Daily Stats: ${statsRecords}\n\nTotal: ${totalRecords} records`);
                } else {
                    alert('Archive failed: ' + data.message);
                }
            })
            .catch(error => {
                alert('Archive error: ' + error.message);
            });
        }
    </script>
</body>
</html>
