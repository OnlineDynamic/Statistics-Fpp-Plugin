<!DOCTYPE html>
<html>
<head>
    <title>Advanced Stats Plugin - Help & About</title>
    <link rel="stylesheet" href="/css/fpp.css" />
    <?php include_once(__DIR__ . '/../logo_base64.php'); ?>
    <style>
        /* Tab Navigation */
        .tab-navigation {
            display: flex;
            border-bottom: 2px solid #dee2e6;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .tab-button {
            padding: 12px 24px;
            background-color: #f8f9fa;
            border: none;
            border-bottom: 3px solid transparent;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            transition: all 0.3s;
            color: #495057;
        }
        
        .tab-button:hover {
            background-color: #e9ecef;
        }
        
        .tab-button.active {
            background-color: #fff;
            border-bottom-color: #007bff;
            color: #007bff;
        }
        
        .tab-content {
            display: none;
            animation: fadeIn 0.3s;
        }
        
        .tab-content.active {
            display: block;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .section-divider {
            margin: 30px 0;
            border-top: 2px solid #e0e0e0;
        }
        
        .about-section {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border: 2px solid #dee2e6;
            margin: 20px 0;
        }
        
        .about-section h2 {
            margin-top: 0;
            color: #007bff;
        }
        
        .credits {
            font-size: 16px;
            line-height: 1.8;
        }
        
        .credits a {
            color: #007bff;
            text-decoration: none;
        }
        
        .credits a:hover {
            text-decoration: underline;
        }
        
        .api-endpoint {
            background-color: #f8f9fa;
            border-left: 4px solid #007bff;
            padding: 15px;
            margin: 15px 0;
            border-radius: 4px;
        }
        
        .api-method {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 12px;
            margin-right: 10px;
        }
        
        .api-method.get {
            background-color: #28a745;
            color: white;
        }
        
        .api-method.post {
            background-color: #007bff;
            color: white;
        }
        
        .api-path {
            font-family: monospace;
            background-color: #e9ecef;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .code-block {
            background-color: #2d2d2d;
            color: #f8f8f2;
            padding: 15px;
            border-radius: 4px;
            overflow-x: auto;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            margin: 10px 0;
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
    </style>
</head>
<body>
    <div id="global" class="settings">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h1 style="margin: 0;">Advanced Stats Plugin - Help & About</h1>
            <div class="header-buttons">
                <a href="plugin.php?_menu=status&plugin=fpp-plugin-AdvancedStats&page=advancedstats.php">
                    <i class="fas fa-chart-line"></i> Dashboard
                </a>
                <a href="plugin.php?_menu=status&plugin=fpp-plugin-AdvancedStats&page=content.php">
                    <i class="fas fa-cog"></i> Settings
                </a>
            </div>
        </div>
        
        <!-- Tab Navigation -->
        <div class="tab-navigation">
            <button class="tab-button active" onclick="switchTab('overview', this)">
                <i class="fas fa-home"></i> Overview
            </button>
            <button class="tab-button" onclick="switchTab('setup', this)">
                <i class="fas fa-cog"></i> Setup Guide
            </button>
            <button class="tab-button" onclick="switchTab('api', this)">
                <i class="fas fa-code"></i> API Endpoints
            </button>
            <button class="tab-button" onclick="switchTab('troubleshooting', this)">
                <i class="fas fa-wrench"></i> Troubleshooting
            </button>
            <button class="tab-button" onclick="switchTab('changelog', this)">
                <i class="fas fa-history"></i> Changelog
            </button>
            <button class="tab-button" onclick="switchTab('about', this)">
                <i class="fas fa-info-circle"></i> Plugin Info
            </button>
        </div>
        
        <!-- Overview Tab -->
        <div id="overview" class="tab-content active">
            <h2>Overview</h2>
            <p>
                The Advanced Stats Plugin enhances Falcon Player (FPP) by providing detailed analytics 
                and statistics about your light show operations.
            </p>
            
            <h2>Purpose</h2>
            <p>
                This plugin tracks and displays comprehensive statistics about your FPP system, including 
                playlist performance, system metrics, and operational insights.
            </p>
            
            <h2>Features</h2>
            <ul>
                <li><strong>Real-time Statistics:</strong> Monitor your system's performance in real-time</li>
                <li><strong>Historical Data:</strong> Track trends and patterns over time</li>
                <li><strong>Detailed Analytics:</strong> Gain insights into playlist usage and system health</li>
                <li><strong>REST API:</strong> Full API access for integration with other systems</li>
                <li><strong>GPIO Integration:</strong> Hardware button support for physical control</li>
            </ul>
        </div>
        
        <!-- Setup Guide Tab -->
        <div id="setup" class="tab-content">
            <h2>Setup Steps</h2>
            <ol>
                <li><strong>Install the Plugin:</strong>
                    <ul>
                        <li>The plugin should already be installed via FPP's Plugin Manager</li>
                        <li>If not, go to Content Setup → Plugin Manager → Install Plugins</li>
                    </ul>
                </li>
                <li><strong>Enable MQTT Broker (REQUIRED):</strong>
                    <div style="background-color: #fff3cd; padding: 15px; margin: 10px 0; border-left: 4px solid #ffc107; border-radius: 4px;">
                        <strong>⚠️ Important:</strong> This plugin requires MQTT to be enabled to collect statistics.
                    </div>
                    <ul>
                        <li><strong>Step 1: Enable the MQTT Broker</strong>
                            <ul>
                                <li>Go to <strong>Status/Control → FPP Settings</strong></li>
                                <li>Click on the <strong>Services</strong> tab</li>
                                <li>Find <strong>"Enable Local MQTT Broker"</strong></li>
                                <li>Check the box to enable it</li>
                                <li>Click <strong>Save</strong></li>
                            </ul>
                        </li>
                        <li><strong>Step 2: Configure MQTT Settings</strong>
                            <ul>
                                <li>Go to <strong>Status/Control → FPP Settings</strong></li>
                                <li>Click on the <strong>MQTT</strong> tab</li>
                                <li>Configure the following settings:</li>
                            </ul>
                        </li>
                    </ul>
                    <div style="background-color: #f8f9fa; padding: 15px; margin: 10px 0; border-radius: 4px; border: 1px solid #dee2e6;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 8px; font-weight: bold; width: 200px;">Setting</td>
                                <td style="padding: 8px; font-weight: bold;">Value</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px;"><strong>MQTT Host:</strong></td>
                                <td style="padding: 8px;"><code>localhost</code></td>
                            </tr>
                            <tr>
                                <td style="padding: 8px;"><strong>MQTT Port:</strong></td>
                                <td style="padding: 8px;"><code>1883</code></td>
                            </tr>
                            <tr>
                                <td style="padding: 8px;"><strong>MQTT Client ID:</strong></td>
                                <td style="padding: 8px;"><em>(Leave default or customize)</em></td>
                            </tr>
                            <tr>
                                <td style="padding: 8px;"><strong>MQTT Prefix:</strong></td>
                                <td style="padding: 8px;"><code>/</code> <em>(default)</em></td>
                            </tr>
                            <tr>
                                <td style="padding: 8px;"><strong>MQTT Username:</strong></td>
                                <td style="padding: 8px;"><code>fpp</code></td>
                            </tr>
                            <tr>
                                <td style="padding: 8px;"><strong>MQTT Password:</strong></td>
                                <td style="padding: 8px;"><code>falcon</code></td>
                            </tr>
                            <tr>
                                <td style="padding: 8px;"><strong>MQTT Frequency:</strong></td>
                                <td style="padding: 8px;"><code>0</code> <em>(disabled - not needed for this plugin)</em></td>
                            </tr>
                        </table>
                    </div>
                    <ul>
                        <li><strong>Step 3: Save and Restart</strong>
                            <ul>
                                <li>Click <strong>Save</strong> at the bottom of the MQTT settings page</li>
                                <li>Restart FPPD or reboot your system for changes to take effect</li>
                                <li>Verify the MQTT broker is running: Go to Status/Control and ensure no MQTT warnings appear on the plugin dashboard</li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li><strong>Configure Plugin Settings:</strong>
                    <ul>
                        <li>Go to Content Setup → Advanced Stats Settings</li>
                        <li>Configure your preferences and options</li>
                        <li>Save your settings</li>
                    </ul>
                </li>
                <li><strong>Access the Dashboard:</strong>
                    <ul>
                        <li>Go to Status/Control → Advanced Stats Dashboard</li>
                        <li>View your statistics and analytics</li>
                    </ul>
                </li>
            </ol>
            
            <h2>MQTT Topic Structure</h2>
            <p>The plugin listens to the following MQTT topics to collect statistics:</p>
            <ul>
                <li><code>falcon/player/+/playlist/name/status</code> - Playlist start/stop events</li>
                <li><code>falcon/player/+/playlist/sequence/status</code> - Sequence within playlist</li>
                <li><code>falcon/player/+/playlist/media/status</code> - Media file playback</li>
                <li><code>falcon/player/+/status</code> - Overall FPP status</li>
            </ul>
            
            <h2>Tips for Best Results</h2>
            <ul>
                <li><strong>Regular Monitoring:</strong> Check your stats regularly to identify trends</li>
                <li><strong>Data Analysis:</strong> Use the historical data to optimize your shows</li>
                <li><strong>API Integration:</strong> Leverage the REST API for custom reporting</li>
                <li><strong>Keep MQTT Running:</strong> Statistics are only collected when MQTT is active</li>
            </ul>
            
            <h2>Data Management</h2>
            <p>The plugin includes several tools to help you manage your statistics database:</p>
            
            <h3>Backup & Restore</h3>
            <ul>
                <li><strong>Backup Database:</strong> Download a complete copy of your statistics database
                    <ul>
                        <li>Click the "Backup Database" button on the main dashboard</li>
                        <li>The database file will be downloaded to your computer</li>
                        <li>Store backups in a safe location</li>
                    </ul>
                </li>
                <li><strong>Restore Database:</strong> Replace your current database with a backup
                    <ul>
                        <li>Click the "Restore Database" button on the main dashboard</li>
                        <li>Select a backup file from your computer</li>
                        <li>A safety backup is automatically created before restoring</li>
                        <li>The page will reload with data from the restored backup</li>
                    </ul>
                </li>
            </ul>
            
            <h3>Archive Old Data</h3>
            <p>Over time, your database can grow large. The archive feature helps keep it manageable:</p>
            <ul>
                <li><strong>Manual Archive:</strong> Click the "Archive Old Data" button on the dashboard
                    <ul>
                        <li>You'll be prompted to enter how many days of data to keep</li>
                        <li>The system will show you what will be deleted before proceeding</li>
                        <li>A preview allows you to confirm before any data is removed</li>
                        <li>Data older than the specified period will be permanently deleted</li>
                    </ul>
                </li>
                <li><strong>Automatic Archive:</strong> Configure in Settings (Content Setup → Advanced Stats Settings)
                    <ul>
                        <li>Enable "Auto-Archive Old Data"</li>
                        <li>Set your retention period (30, 60, 90, 180, or 365 days)</li>
                        <li>Old data will be automatically cleaned up on a regular schedule</li>
                    </ul>
                </li>
            </ul>
            
            <div style="background-color: #f8d7da; padding: 15px; margin: 10px 0; border-left: 4px solid #dc3545; border-radius: 4px;">
                <strong>⚠️ Warning:</strong> Archived data is permanently deleted and cannot be recovered. Always create a backup before archiving if you might need the historical data later.
            </div>
        </div>
        
        <!-- API Tab -->
        <div id="api" class="tab-content">
            <h2>API Endpoints</h2>
            <p>All endpoints are available at: <code>/api/plugin/fpp-plugin-AdvancedStats/</code></p>
            
            <div class="api-endpoint">
                <div>
                    <span class="api-method get">GET</span>
                    <span class="api-path">/api/plugin/fpp-plugin-AdvancedStats/status</span>
                </div>
                <p style="margin-top: 10px;">Returns current plugin status and statistics.</p>
                <div class="code-block">
{
    "status": "active",
    "version": "1.0",
    "uptime": 3600,
    "stats": { ... }
}
                </div>
            </div>
            
            <div class="api-endpoint">
                <div>
                    <span class="api-method get">GET</span>
                    <span class="api-path">/api/plugin/fpp-plugin-AdvancedStats/stats</span>
                </div>
                <p style="margin-top: 10px;">Returns detailed statistics data.</p>
            </div>
            
            <div class="api-endpoint">
                <div>
                    <span class="api-method post">POST</span>
                    <span class="api-path">/api/plugin/fpp-plugin-AdvancedStats/reset</span>
                </div>
                <p style="margin-top: 10px;">Resets statistics counters (requires confirmation).</p>
            </div>
            
            <h3>Example Usage</h3>
            <div class="code-block">
# Get current status
curl http://your-fpp-ip/api/plugin/fpp-plugin-AdvancedStats/status

# Get statistics
curl http://your-fpp-ip/api/plugin/fpp-plugin-AdvancedStats/stats
            </div>
        </div>
        
        <!-- Troubleshooting Tab -->
        <div id="troubleshooting" class="tab-content">
            <h2>Common Issues</h2>
            
            <h3>Stats Not Updating</h3>
            <ul>
                <li>Check that FPPd is running</li>
                <li>Verify plugin is enabled in Plugin Manager</li>
                <li>Check system logs for errors</li>
            </ul>
            
            <h3>Dashboard Not Loading</h3>
            <ul>
                <li>Clear your browser cache</li>
                <li>Check browser console for JavaScript errors</li>
                <li>Verify FPP version compatibility (requires FPP 9.0+)</li>
            </ul>
            
            <h3>API Not Responding</h3>
            <ul>
                <li>Ensure FPP web interface is accessible</li>
                <li>Check firewall settings</li>
                <li>Verify correct API endpoint URL</li>
            </ul>
            
            <h3>Getting Help</h3>
            <p>If you continue to experience issues:</p>
            <ul>
                <li>Check the <a href="https://github.com/OnlineDynamic/Statistics-Fpp-Plugin/issues" target="_blank">GitHub Issues</a> page</li>
                <li>Review FPP system logs in Status/Control → Status Page</li>
                <li>Post detailed information in the FPP Discord or Forums</li>
            </ul>
        </div>
        
        <!-- Changelog Tab -->
        <div id="changelog" class="tab-content">
            <h2>Plugin Version History</h2>
            <p>Recent commits and changes to the Advanced Stats Plugin:</p>
            
            <div id="changelogContent" style="margin-top: 20px;">
                <div style="text-align: center; padding: 40px;">
                    <i class="fas fa-spinner fa-spin" style="font-size: 32px; color: #007bff;"></i>
                    <p style="margin-top: 15px; color: #6c757d;">Loading commit history...</p>
                </div>
            </div>
            
            <script>
                function escapeHtml(text) {
                    const div = document.createElement('div');
                    div.textContent = text;
                    return div.innerHTML;
                }
                
                function loadChangelog() {
                    fetch('/api/plugin/fpp-plugin-AdvancedStats/git-commits')
                        .then(response => response.json())
                        .then(data => {
                            const container = document.getElementById('changelogContent');
                            if (data.success && data.commits && data.commits.length > 0) {
                                let html = '';
                                data.commits.forEach((commit, index) => {
                                    const date = new Date(commit.date * 1000);
                                    const formattedDate = date.toLocaleDateString('en-US', {
                                        year: 'numeric',
                                        month: 'short',
                                        day: 'numeric',
                                        hour: '2-digit',
                                        minute: '2-digit'
                                    });
                                    
                                    const escapedMessage = escapeHtml(commit.message);
                                    const escapedAuthor = escapeHtml(commit.author);
                                    const shortHash = commit.hash.substring(0, 7);
                                    const bgColor = index % 2 === 0 ? '#f8f9fa' : '#ffffff';
                                    
                                    html += `
                                        <div style="background-color: ${bgColor}; 
                                                    border-left: 4px solid #007bff; 
                                                    padding: 15px 20px; 
                                                    margin-bottom: 10px; 
                                                    border-radius: 4px;
                                                    box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                                            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 8px;">
                                                <div style="flex: 1;">
                                                    <strong style="color: #212529; font-size: 15px;">${escapedMessage}</strong>
                                                </div>
                                                <div style="text-align: right; margin-left: 15px;">
                                                    <code style="background-color: #e9ecef; padding: 2px 6px; border-radius: 3px; font-size: 11px; color: #495057;">${shortHash}</code>
                                                </div>
                                            </div>
                                            <div style="font-size: 13px; color: #6c757d;">
                                                <i class="fas fa-user" style="margin-right: 5px;"></i>${escapedAuthor}
                                                <span style="margin: 0 10px;">•</span>
                                                <i class="fas fa-calendar" style="margin-right: 5px;"></i>${formattedDate}
                                            </div>
                                        </div>
                                    `;
                                });
                                container.innerHTML = html;
                            } else {
                                const errorMsg = data.message || 'Unable to load commit history. This may be a manual installation or Git is not available.';
                                container.innerHTML = `
                                    <div style="background-color: #fff3cd; border: 2px solid #ffc107; border-radius: 5px; padding: 20px; text-align: center;">
                                        <i class="fas fa-exclamation-triangle" style="font-size: 32px; color: #856404; margin-bottom: 10px;"></i>
                                        <p style="margin: 0; color: #856404;">${escapeHtml(errorMsg)}</p>
                                    </div>
                                `;
                            }
                        })
                        .catch(error => {
                            document.getElementById('changelogContent').innerHTML = `
                                <div style="background-color: #f8d7da; border: 2px solid #f5c6cb; border-radius: 5px; padding: 20px; text-align: center;">
                                    <i class="fas fa-exclamation-circle" style="font-size: 32px; color: #721c24; margin-bottom: 10px;"></i>
                                    <p style="margin: 0; color: #721c24;">Error loading commit history: ${escapeHtml(error.message)}</p>
                                </div>
                            `;
                        });
                }
                
                // Load changelog when tab is first opened
                document.addEventListener('DOMContentLoaded', function() {
                    let changelogLoaded = false;
                    const originalSwitchTab = window.switchTab;
                    
                    if (typeof originalSwitchTab === 'function') {
                        window.switchTab = function(tabName, button) {
                            originalSwitchTab(tabName, button);
                            if (tabName === 'changelog' && !changelogLoaded) {
                                loadChangelog();
                                changelogLoaded = true;
                            }
                        };
                    }
                });
            </script>
        </div>
        
        <!-- About Tab -->
        <div id="about" class="tab-content">
            <h2>Purpose</h2>
            <p>
                The Advanced Stats Plugin provides comprehensive analytics and monitoring capabilities for 
                Falcon Player (FPP), helping you understand and optimize your light show operations.
            </p>
            
            <h2>Features</h2>
            <ul>
                <li><strong>Real-time Monitoring:</strong> Track system performance as it happens</li>
                <li><strong>Historical Analytics:</strong> Review past performance and identify trends</li>
                <li><strong>Playlist Statistics:</strong> Understand which playlists and sequences are most popular</li>
                <li><strong>System Metrics:</strong> Monitor CPU, memory, and disk usage</li>
                <li><strong>REST API:</strong> Full programmatic access for automation and integration</li>
                <li><strong>GPIO Support:</strong> Physical button controls for common operations</li>
            </ul>
            
            <!-- About Section -->
            <div class="about-section">
                <h2><i class="fas fa-info-circle"></i> Plugin Information</h2>
                
                <!-- Dynamic Pixels Logo -->
                <div style="text-align: center; margin: 20px 0;">
                    <img src="<?php echo $logoBase64; ?>" 
                         alt="Dynamic Pixels Logo" 
                         style="max-width: 100%; height: auto; max-height: 120px;">
                </div>
                
                <div class="credits">
                    <p><strong>Plugin Developed By:</strong></p>
                    <p style="margin-left: 20px;">
                        Stuart Ledingham of <strong>Dynamic Pixels</strong>
                    </p>
                    
                    <p style="margin-top: 20px;"><strong>Resources:</strong></p>
                    <ul style="margin-left: 20px;">
                        <li><a href='https://github.com/OnlineDynamic/Statistics-Fpp-Plugin' target='_blank'>
                            <i class="fab fa-github"></i> Git Repository
                        </a></li>
                        <li><a href='https://github.com/OnlineDynamic/Statistics-Fpp-Plugin/issues' target='_blank'>
                            <i class="fas fa-bug"></i> Bug Reporter / Feature Requests
                        </a></li>
                    </ul>
                    
                    <p style="margin-top: 20px; font-size: 14px; color: #6c757d;">
                        This plugin enhances Falcon Player (FPP) by adding comprehensive analytics and 
                        monitoring capabilities to help you understand and optimize your light show operations.
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function switchTab(tabName, element) {
            // Hide all tab contents
            var tabContents = document.getElementsByClassName('tab-content');
            for (var i = 0; i < tabContents.length; i++) {
                tabContents[i].classList.remove('active');
            }
            
            // Remove active class from all buttons
            var tabButtons = document.getElementsByClassName('tab-button');
            for (var i = 0; i < tabButtons.length; i++) {
                tabButtons[i].classList.remove('active');
            }
            
            // Show the selected tab
            document.getElementById(tabName).classList.add('active');
            
            // Mark the button as active
            if (element) {
                element.classList.add('active');
            }
        }
    </script>
</body>
</html>
