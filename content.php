<?php include_once(__DIR__ . '/mqtt_warning.inc.php'); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Advanced Stats Settings</title>
    <link rel="stylesheet" href="/css/fpp.css" />
    <?php echo getMQTTWarningStyles(); ?>
    <style>
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
        
        .settings-section {
            background-color: #f8f9fa;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .settings-section h3 {
            margin-top: 0;
            color: #495057;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #495057;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .form-group small {
            display: block;
            color: #6c757d;
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
            background-color: #007bff;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #0056b3;
        }
        
        .placeholder-message {
            background-color: #fff3cd;
            border: 2px solid #ffc107;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }
        
        .placeholder-message i {
            font-size: 48px;
            color: #856404;
            margin-bottom: 15px;
        }
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
            <p style="color: #6c757d; font-size: 16px;">Configure plugin options and preferences</p>
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
                    <div style="background-color: #e7f3ff; border-left: 4px solid #007bff; padding: 15px; border-radius: 4px;">
                        <div style="font-size: 12px; color: #495057; font-weight: bold;">DATABASE SIZE</div>
                        <div style="font-size: 24px; color: #007bff; font-weight: bold;" id="dbSize">-</div>
                    </div>
                    
                    <div style="background-color: #e7f3ff; border-left: 4px solid #007bff; padding: 15px; border-radius: 4px;">
                        <div style="font-size: 12px; color: #495057; font-weight: bold;">SEQUENCE RECORDS</div>
                        <div style="font-size: 24px; color: #007bff; font-weight: bold;" id="sequenceCount">-</div>
                    </div>
                    
                    <div style="background-color: #e7f3ff; border-left: 4px solid #28a745; padding: 15px; border-radius: 4px;">
                        <div style="font-size: 12px; color: #495057; font-weight: bold;">PLAYLIST RECORDS</div>
                        <div style="font-size: 24px; color: #28a745; font-weight: bold;" id="playlistCount">-</div>
                    </div>
                    
                    <div style="background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; border-radius: 4px;">
                        <div style="font-size: 12px; color: #495057; font-weight: bold;">GPIO RECORDS</div>
                        <div style="font-size: 24px; color: #856404; font-weight: bold;" id="gpioCount">-</div>
                    </div>
                    
                    <div style="background-color: #f8d7da; border-left: 4px solid #dc3545; padding: 15px; border-radius: 4px;">
                        <div style="font-size: 12px; color: #495057; font-weight: bold;">DAILY STATS</div>
                        <div style="font-size: 24px; color: #dc3545; font-weight: bold;" id="dailyStatsCount">-</div>
                    </div>
                    
                    <div style="background-color: #d1ecf1; border-left: 4px solid #17a2b8; padding: 15px; border-radius: 4px;">
                        <div style="font-size: 12px; color: #495057; font-weight: bold;">TOTAL RECORDS</div>
                        <div style="font-size: 24px; color: #17a2b8; font-weight: bold;" id="totalRecords">-</div>
                    </div>
                </div>
                
                <div style="display: flex; gap: 10px; justify-content: center; flex-wrap: wrap;">
                    <button type="button" class="btn" style="background-color: #17a2b8; color: white;" onclick="loadDatabaseInfo()">
                        <i class="fas fa-sync-alt"></i> Refresh Database Info
                    </button>
                    <button type="button" class="btn" style="background-color: #28a745; color: white;" onclick="backupDatabase()">
                        <i class="fas fa-download"></i> Backup Database
                    </button>
                    <button type="button" class="btn" style="background-color: #ffc107; color: #212529;" onclick="document.getElementById('restoreFileInput').click()">
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
                
                <div class="form-group" style="background-color: #fff3cd; padding: 15px; border-radius: 4px; border-left: 4px solid #ffc107;">
                    <strong>⚠️ Warning:</strong> When auto-archive is enabled, old data will be permanently deleted.
                    Always create a backup before archiving if you might need the historical data later.
                </div>
                
                <div class="form-group" style="text-align: center; margin-top: 20px;">
                    <button type="button" class="btn" style="background-color: #dc3545; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;" onclick="showArchiveDialog()">
                        <i class="fas fa-archive"></i> Archive Old Data Now
                    </button>
                    <br>
                    <small style="color: #6c757d; margin-top: 5px; display: block;">Manually delete old records with preview before deletion</small>
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
