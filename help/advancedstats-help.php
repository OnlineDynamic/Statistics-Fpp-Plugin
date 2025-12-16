<?php include_once(__DIR__ . '/../mqtt_warning.inc.php'); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Advanced Stats Plugin - Help & About</title>
    <link rel="stylesheet" href="/css/fpp.css" />
    <link rel="stylesheet" href="/css/fontawesome.all.min.css" />
    <?php include_once(__DIR__ . '/../logo_base64.php'); ?>
    <?php echo getMQTTWarningStyles(); ?>
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
        
        <?php displayMQTTWarning(); ?>
        
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
            <h2>Welcome to Advanced Stats Plugin</h2>
            <p>
                The Advanced Stats Plugin enhances Falcon Player (FPP) by providing comprehensive analytics, 
                real-time monitoring, and detailed insights about your light show operations. Track sequences, 
                playlists, GPIO events, and system performance all in one place.
            </p>
            
            <h2>Key Features</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin: 20px 0;">
                <div style="background-color: #e7f3ff; border-left: 4px solid #007bff; padding: 15px; border-radius: 4px;">
                    <h3 style="margin-top: 0; color: #007bff;"><i class="fas fa-chart-line"></i> Real-Time Analytics</h3>
                    <ul style="margin: 0;">
                        <li>Live event monitoring</li>
                        <li>Time-series graphs with multiple periods</li>
                        <li>Heat map visualizations (day/hour activity)</li>
                        <li>Instant statistics updates</li>
                    </ul>
                </div>
                
                <div style="background-color: #d4edda; border-left: 4px solid #28a745; padding: 15px; border-radius: 4px;">
                    <h3 style="margin-top: 0; color: #28a745;"><i class="fas fa-database"></i> Data Tracking</h3>
                    <ul style="margin: 0;">
                        <li>Sequence playback history</li>
                        <li>Playlist execution logs</li>
                        <li>GPIO button event capture</li>
                        <li>Daily aggregated statistics</li>
                    </ul>
                </div>
                
                <div style="background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; border-radius: 4px;">
                    <h3 style="margin-top: 0; color: #856404;"><i class="fas fa-search"></i> Search & Filter</h3>
                    <ul style="margin: 0;">
                        <li>Search sequences by name</li>
                        <li>Filter GPIO events by pin</li>
                        <li>Paginated results (15 per page)</li>
                        <li>Real-time filtering</li>
                    </ul>
                </div>
                
                <div style="background-color: #f8d7da; border-left: 4px solid #dc3545; padding: 15px; border-radius: 4px;">
                    <h3 style="margin-top: 0; color: #dc3545;"><i class="fas fa-microchip"></i> GPIO Integration</h3>
                    <ul style="margin: 0;">
                        <li>Button press tracking</li>
                        <li>Rising/falling edge detection</li>
                        <li>Custom pin descriptions</li>
                        <li>Event state monitoring</li>
                    </ul>
                </div>
                
                <div style="background-color: #d1ecf1; border-left: 4px solid #17a2b8; padding: 15px; border-radius: 4px;">
                    <h3 style="margin-top: 0; color: #17a2b8;"><i class="fas fa-code"></i> REST API</h3>
                    <ul style="margin: 0;">
                        <li>Full programmatic access</li>
                        <li>JSON response format</li>
                        <li>15+ endpoints available</li>
                        <li>Easy integration</li>
                    </ul>
                </div>
                
                <div style="background-color: #e2e3e5; border-left: 4px solid #6c757d; padding: 15px; border-radius: 4px;">
                    <h3 style="margin-top: 0; color: #6c757d;"><i class="fas fa-shield-alt"></i> Data Management</h3>
                    <ul style="margin: 0;">
                        <li>Database backup & restore</li>
                        <li>Automatic data archiving</li>
                        <li>Retention policies (up to 10 years)</li>
                        <li>Manual data cleanup</li>
                    </ul>
                </div>
                
                <div style="background-color: #e8d5f2; border-left: 4px solid #9c27b0; padding: 15px; border-radius: 4px;">
                    <h3 style="margin-top: 0; color: #9c27b0;"><i class="fas fa-terminal"></i> Command Tracking</h3>
                    <ul style="margin: 0;">
                        <li>Individual command execution logs</li>
                        <li>Command preset triggering</li>
                        <li>Trigger source identification (UI/API/Internal)</li>
                        <li>Arguments and parameters captured</li>
                    </ul>
                </div>
            </div>
            
            <h2>What You Can Track</h2>
            <ul>
                <li><strong>Sequences:</strong> Every sequence played, duration, part of playlist or standalone, interruption detection</li>
                <li><strong>Playlists:</strong> Playlist starts, stops, repeats, and total play counts</li>
                <li><strong>GPIO Events:</strong> Button presses, state changes, with customizable descriptions</li>
                <li><strong>Commands:</strong> FPP command executions with arguments, trigger source (UI, API, Internal), and timestamps</li>
                <li><strong>Command Presets:</strong> Command preset triggering with command count and execution details</li>
                <li><strong>System Activity:</strong> Peak usage times, most popular shows, daily/weekly/monthly trends</li>
            </ul>
            
            <h2>Dashboard Features</h2>
            <ul>
                <li><strong>Quick Stats Cards:</strong> Total sequences, playlists, GPIO events, commands, and command presets at a glance</li>
                <li><strong>Interactive Charts:</strong> Zoom through hourly, daily, weekly, and monthly views</li>
                <li><strong>Heat Maps:</strong> Visualize activity patterns by day of week and hour of day</li>
                <li><strong>Top 10 Lists:</strong> Most played sequences, popular playlists, active GPIO pins, frequently used commands and presets</li>
                <li><strong>Live Monitor:</strong> Dedicated page for real-time event streaming with filters (sequences, playlists, GPIO, commands, presets)</li>
                <li><strong>History Tables:</strong> Detailed logs with search, pagination, and timestamps for all event types</li>
                <li><strong>Command Analytics:</strong> Track which commands are being used, how they're triggered, and execution frequency</li>
            </ul>
            
            <h2>Command Tracking Features</h2>
            <div style="background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0; border: 2px solid #dee2e6;">
                <h3 style="margin-top: 0;"><i class="fas fa-terminal"></i> Command Execution Tracking</h3>
                <p>The plugin automatically tracks all FPP command executions and identifies how they were triggered:</p>
                <ul>
                    <li><strong>UI Triggers:</strong> Commands executed through FPP's "Run FPP Command" dialog</li>
                    <li><strong>API Triggers:</strong> Commands called via REST API (GET or POST requests)</li>
                    <li><strong>Internal Triggers:</strong> Commands executed by schedules, playlists, MQTT events, GPIO triggers, or other automated sources</li>
                </ul>
                
                <h4>Captured Information:</h4>
                <ul>
                    <li><strong>Command Name:</strong> The exact command that was executed (e.g., "Brightness", "Volume Set")</li>
                    <li><strong>Arguments:</strong> Any parameters passed to the command (captured as JSON array)</li>
                    <li><strong>Trigger Source:</strong> How the command was initiated (ui/api-get/api-post/internal)</li>
                    <li><strong>MultiSync:</strong> Whether the command was sent to multiple FPP instances</li>
                    <li><strong>Timestamp:</strong> Exact date and time of execution</li>
                </ul>
                
                <h4>Command Presets:</h4>
                <p>When command presets are executed, the plugin tracks:</p>
                <ul>
                    <li>Preset name or slot number</li>
                    <li>Number of commands in the preset</li>
                    <li>Trigger source</li>
                    <li>Full execution timestamp</li>
                </ul>
                
                <h4>Dashboard Views:</h4>
                <ul>
                    <li><strong>Top Commands:</strong> See which commands are used most frequently</li>
                    <li><strong>Top Presets:</strong> Track the most commonly triggered command presets</li>
                    <li><strong>Recent Executions:</strong> Browse command history with search and pagination</li>
                    <li><strong>Live Monitor:</strong> Watch commands execute in real-time with color-coded event types</li>
                </ul>
            </div>
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
            <p>The plugin automatically listens to the following MQTT topics to collect statistics:</p>
            <div style="background-color: #f8f9fa; padding: 15px; border-radius: 4px; border: 1px solid #dee2e6;">
                <ul style="font-family: monospace; font-size: 14px;">
                    <li><code>falcon/player/#/playlist/name/status</code> - Playlist start/stop events</li>
                    <li><code>falcon/player/#/playlist/sequence/status</code> - Sequence within playlist events</li>
                    <li><code>falcon/player/#/gpio/+</code> - GPIO pin state changes and button events</li>
                </ul>
            </div>
            <p style="margin-top: 10px;"><em>Note: The '#' and '+' are MQTT wildcards that match all FPP instances and GPIO pins.</em></p>
            
            <h2>Tips for Best Results</h2>
            <ul>
                <li><strong>Regular Monitoring:</strong> Check the dashboard weekly to identify patterns and optimize your shows</li>
                <li><strong>Use the Heat Map:</strong> Identify peak viewing times to schedule your best content</li>
                <li><strong>Monitor GPIO Events:</strong> Track button usage to understand which manual controls are used most</li>
                <li><strong>Archive Old Data:</strong> Keep your database lean by archiving old statistics (Settings page)</li>
                <li><strong>Backup Regularly:</strong> Download database backups before major changes or at regular intervals</li>
                <li><strong>API Integration:</strong> Use the REST API to build custom dashboards or integrate with home automation</li>
                <li><strong>Keep MQTT Running:</strong> Statistics are only collected when MQTT broker is active and configured</li>
            </ul>
            
            <h2>Understanding Your Dashboard</h2>
            <div style="background-color: #e7f3ff; padding: 15px; margin: 15px 0; border-radius: 4px; border-left: 4px solid #007bff;">
                <h3 style="margin-top: 0;">Quick Stats Cards (Top of Page)</h3>
                <ul>
                    <li><strong>Total Sequences:</strong> Number of unique sequences played</li>
                    <li><strong>Total Playlists:</strong> Number of playlist start events</li>
                    <li><strong>GPIO Events:</strong> Total button presses and state changes</li>
                    <li><strong>Interruptions:</strong> Sequences that were stopped before completing</li>
                </ul>
            </div>
            
            <div style="background-color: #d4edda; padding: 15px; margin: 15px 0; border-radius: 4px; border-left: 4px solid #28a745;">
                <h3 style="margin-top: 0;">Time-Series Graphs</h3>
                <p>Three interactive charts showing activity over time:</p>
                <ul>
                    <li><strong>Sequence Activity:</strong> Track how many sequences played per period</li>
                    <li><strong>Playlist Activity:</strong> Monitor playlist usage trends</li>
                    <li><strong>GPIO Activity:</strong> See button press patterns</li>
                </ul>
                <p>Use the period buttons (Hour/Day/Week/Month) to zoom in or out.</p>
            </div>
            
            <div style="background-color: #fff3cd; padding: 15px; margin: 15px 0; border-radius: 4px; border-left: 4px solid #ffc107;">
                <h3 style="margin-top: 0;">Heat Map Visualization</h3>
                <p>Shows a 7×24 grid (days of week vs. hours of day) with color intensity representing activity level:</p>
                <ul>
                    <li><strong>Dark blue:</strong> High activity</li>
                    <li><strong>Light blue:</strong> Moderate activity</li>
                    <li><strong>White:</strong> No activity</li>
                </ul>
                <p>Switch between Sequence, Playlist, and GPIO views using the buttons above the heat map.</p>
            </div>
            
            <h2>Data Management</h2>
            <p>The plugin includes several tools to help you manage your statistics database:</p>
            
            <h3>Backup & Restore</h3>
            <div style="background-color: #d1ecf1; padding: 15px; margin: 15px 0; border-radius: 4px; border-left: 4px solid #17a2b8;">
                <p><strong>Location:</strong> Backup and restore functions are located in the <strong>Settings</strong> page under the <strong>Database Information</strong> section.</p>
            </div>
            <ul>
                <li><strong>Backup Database:</strong> Download a complete copy of your statistics database
                    <ul>
                        <li>Navigate to the <strong>Settings</strong> page (gear icon in header)</li>
                        <li>Find the <strong>Database Information</strong> section</li>
                        <li>Click the "Backup Database" button</li>
                        <li>The database file will be downloaded to your computer</li>
                        <li>Store backups in a safe location</li>
                    </ul>
                </li>
                <li><strong>Restore Database:</strong> Replace your current database with a backup
                    <ul>
                        <li>Navigate to the <strong>Settings</strong> page</li>
                        <li>Find the <strong>Database Information</strong> section</li>
                        <li>Click the "Restore Database" button</li>
                        <li>Select a backup file from your computer</li>
                        <li>A safety backup is automatically created before restoring</li>
                        <li>The database info will refresh with data from the restored backup</li>
                    </ul>
                </li>
            </ul>
            
            <h3>Archive Old Data</h3>
            <p>Over time, your database can grow large. The archive feature helps keep it manageable:</p>
            <ul>
                <li><strong>Manual Archive (Dashboard):</strong>
                    <ul>
                        <li>Click the "Archive Old Data" button on the dashboard</li>
                        <li>Enter how many days of data to keep (e.g., 365 for one year)</li>
                        <li>Preview shows exactly what will be deleted</li>
                        <li>Confirm to permanently remove old records</li>
                    </ul>
                </li>
                <li><strong>Automatic Archive (Settings Page):</strong>
                    <ul>
                        <li>Go to Content Setup → Advanced Stats → Settings</li>
                        <li>Enable "Enable Automatic Archiving"</li>
                        <li>Select retention period: 30 days to 10 years, or "Never" to keep all data</li>
                        <li>Save settings</li>
                        <li>Old data will be automatically cleaned up on schedule</li>
                    </ul>
                </li>
            </ul>
            
            <h3>Database Information</h3>
            <p>The Settings page displays:</p>
            <ul>
                <li><strong>Database Size:</strong> Total size of the SQLite database file</li>
                <li><strong>Record Counts:</strong> Number of records in each table (sequences, playlists, GPIO, daily stats)</li>
                <li><strong>Total Records:</strong> Combined count across all tables</li>
            </ul>
            <p>Click "Refresh Database Info" to update the statistics.</p>
            
            <div style="background-color: #f8d7da; padding: 15px; margin: 10px 0; border-left: 4px solid #dc3545; border-radius: 4px;">
                <strong>⚠️ Warning:</strong> Archived data is permanently deleted and cannot be recovered. Always create a backup before archiving if you might need the historical data later.
            </div>
        </div>
        
        <!-- API Tab -->
        <div id="api" class="tab-content">
            <h2>API Endpoints</h2>
            <p>All endpoints are available at: <code>/api/plugin/fpp-plugin-AdvancedStats/{endpoint}</code></p>
            <p>Base URL: <code>http://your-fpp-ip/api/plugin/fpp-plugin-AdvancedStats/</code></p>
            
            <h3>Statistics & Data Retrieval</h3>
            
            <div class="api-endpoint">
                <div>
                    <span class="api-method get">GET</span>
                    <span class="api-path">/dashboard-data</span>
                </div>
                <p style="margin-top: 10px;">Returns complete dashboard statistics including totals, top 10 lists, and recent activity.</p>
                <div class="code-block">
# Example Response
{
  "success": true,
  "totals": {
    "sequences": 156,
    "playlists": 42,
    "gpio_events": 89,
    "interruptions": 3
  },
  "top_sequences": [...],
  "top_playlists": [...],
  "top_gpio_pins": [...]
}
                </div>
            </div>
            
            <div class="api-endpoint">
                <div>
                    <span class="api-method get">GET</span>
                    <span class="api-path">/sequence-history?limit=15&offset=0&search=</span>
                </div>
                <p style="margin-top: 10px;">Returns paginated sequence playback history. Supports search parameter for filtering.</p>
                <strong>Query Parameters:</strong>
                <ul>
                    <li><code>limit</code> - Number of records to return (default: 50)</li>
                    <li><code>offset</code> - Starting position for pagination (default: 0)</li>
                    <li><code>search</code> - Filter by sequence name (optional)</li>
                </ul>
            </div>
            
            <div class="api-endpoint">
                <div>
                    <span class="api-method get">GET</span>
                    <span class="api-path">/gpio-events?limit=15&offset=0&search=</span>
                </div>
                <p style="margin-top: 10px;">Returns GPIO button press history with filtering support.</p>
                <strong>Query Parameters:</strong>
                <ul>
                    <li><code>limit</code> - Number of records to return</li>
                    <li><code>offset</code> - Starting position for pagination</li>
                    <li><code>search</code> - Filter by pin number or description</li>
                </ul>
            </div>
            
            <div class="api-endpoint">
                <div>
                    <span class="api-method get">GET</span>
                    <span class="api-path">/stats/timeseries?type=sequence&period=day</span>
                </div>
                <p style="margin-top: 10px;">Returns time-series data for graphing activity over time.</p>
                <strong>Query Parameters:</strong>
                <ul>
                    <li><code>type</code> - Data type: <code>sequence</code>, <code>playlist</code>, or <code>gpio</code></li>
                    <li><code>period</code> - Aggregation period: <code>hour</code>, <code>day</code>, <code>week</code>, <code>month</code></li>
                </ul>
            </div>
            
            <div class="api-endpoint">
                <div>
                    <span class="api-method get">GET</span>
                    <span class="api-path">/stats/heatmap?type=sequence</span>
                </div>
                <p style="margin-top: 10px;">Returns heat map data (7×24 grid) showing activity by day of week and hour of day.</p>
                <strong>Query Parameters:</strong>
                <ul>
                    <li><code>type</code> - Data type: <code>sequence</code>, <code>playlist</code>, or <code>gpio</code></li>
                </ul>
            </div>
            
            <div class="api-endpoint">
                <div>
                    <span class="api-method get">GET</span>
                    <span class="api-path">/events/stream?since=1234567890&limit=30</span>
                </div>
                <p style="margin-top: 10px;">Returns live event stream for real-time monitoring. Used by the Live Monitor page.</p>
                <strong>Query Parameters:</strong>
                <ul>
                    <li><code>since</code> - Unix timestamp; only return events after this time</li>
                    <li><code>limit</code> - Maximum number of events to return (default: 50)</li>
                    <li><code>types</code> - Comma-separated list: <code>sequence,playlist,gpio,command,command_preset</code> (optional)</li>
                </ul>
            </div>
            
            <div class="api-endpoint">
                <div>
                    <span class="api-method get">GET</span>
                    <span class="api-path">/sequence-interruptions?limit=15</span>
                </div>
                <p style="margin-top: 10px;">Returns sequences that were interrupted (stopped before completion).</p>
            </div>
            
            <h3>Command Tracking</h3>
            
            <div class="api-endpoint">
                <div>
                    <span class="api-method get">GET</span>
                    <span class="api-path">/command-history?limit=15&offset=0&search=</span>
                </div>
                <p style="margin-top: 10px;">Returns paginated command execution history with trigger source and arguments.</p>
                <strong>Query Parameters:</strong>
                <ul>
                    <li><code>limit</code> - Number of records to return (default: 50)</li>
                    <li><code>offset</code> - Starting position for pagination (default: 0)</li>
                    <li><code>search</code> - Filter by command name, args, or trigger source (optional)</li>
                </ul>
                <div class="code-block">
# Example Response
{
  "success": true,
  "data": [
    {
      "timestamp": 1732622854,
      "command": "Brightness",
      "args": "[\"60\"]",
      "multisyncCommand": 0,
      "trigger_source": "ui"
    }
  ],
  "total": 156
}
                </div>
            </div>
            
            <div class="api-endpoint">
                <div>
                    <span class="api-method get">GET</span>
                    <span class="api-path">/command-preset-history?limit=15&offset=0&search=</span>
                </div>
                <p style="margin-top: 10px;">Returns command preset execution history.</p>
                <strong>Query Parameters:</strong>
                <ul>
                    <li><code>limit</code> - Number of records to return (default: 50)</li>
                    <li><code>offset</code> - Starting position for pagination (default: 0)</li>
                    <li><code>search</code> - Filter by preset name or trigger (optional)</li>
                </ul>
            </div>
            
            <h3>Settings & Configuration</h3>
            
            <div class="api-endpoint">
                <div>
                    <span class="api-method get">GET</span>
                    <span class="api-path">/get-settings</span>
                </div>
                <p style="margin-top: 10px;">Returns current plugin settings and preferences.</p>
            </div>
            
            <div class="api-endpoint">
                <div>
                    <span class="api-method post">POST</span>
                    <span class="api-path">/save-settings</span>
                </div>
                <p style="margin-top: 10px;">Saves plugin settings. Send JSON body with settings object.</p>
                <div class="code-block">
# Example Request
{
  "enableStats": "1",
  "updateInterval": "60",
  "enableAutoArchive": "0",
  "retentionDays": "365",
  "showCharts": "1",
  "chartType": "line"
}
                </div>
            </div>
            
            <h3>Data Management</h3>
            
            <div class="api-endpoint">
                <div>
                    <span class="api-method get">GET</span>
                    <span class="api-path">/database-info</span>
                </div>
                <p style="margin-top: 10px;">Returns database size and record counts for all tables.</p>
                <div class="code-block">
# Example Response
{
  "success": true,
  "database_size": 1048576,
  "database_path": "/home/fpp/media/config/plugin.fpp-plugin-AdvancedStats.db",
  "counts": {
    "sequence_history": 1250,
    "playlist_history": 340,
    "gpio_events": 567,
    "daily_stats": 90
  }
}
                </div>
            </div>
            
            <div class="api-endpoint">
                <div>
                    <span class="api-method get">GET</span>
                    <span class="api-path">/backup-database</span>
                </div>
                <p style="margin-top: 10px;">Downloads the SQLite database file as a backup.</p>
            </div>
            
            <div class="api-endpoint">
                <div>
                    <span class="api-method post">POST</span>
                    <span class="api-path">/restore-database</span>
                </div>
                <p style="margin-top: 10px;">Restores database from uploaded backup file. Creates automatic backup before restore.</p>
            </div>
            
            <div class="api-endpoint">
                <div>
                    <span class="api-method post">POST</span>
                    <span class="api-path">/archive-old-data</span>
                </div>
                <p style="margin-top: 10px;">Archives (deletes) old records based on retention period.</p>
                <strong>POST Body:</strong>
                <div class="code-block">
{
  "retention_days": 365,
  "dry_run": true  // Set false to actually delete
}
                </div>
            </div>
            
            <h3>System Information</h3>
            
            <div class="api-endpoint">
                <div>
                    <span class="api-method get">GET</span>
                    <span class="api-path">/status</span>
                </div>
                <p style="margin-top: 10px;">Returns plugin status and version information.</p>
            </div>
            
            <div class="api-endpoint">
                <div>
                    <span class="api-method get">GET</span>
                    <span class="api-path">/git-commits</span>
                </div>
                <p style="margin-top: 10px;">Returns recent git commit history (if installed via git).</p>
            </div>
            
            <h3>Example Usage</h3>
            <div class="code-block">
# Get dashboard data (now includes command statistics)
curl http://192.168.1.200/api/plugin/fpp-plugin-AdvancedStats/dashboard-data

# Get sequence history with search
curl "http://192.168.1.200/api/plugin/fpp-plugin-AdvancedStats/sequence-history?search=christmas&limit=10"

# Get command execution history
curl "http://192.168.1.200/api/plugin/fpp-plugin-AdvancedStats/command-history?limit=20"

# Get command preset history
curl http://192.168.1.200/api/plugin/fpp-plugin-AdvancedStats/command-preset-history

# Get time-series data for daily sequence activity
curl "http://192.168.1.200/api/plugin/fpp-plugin-AdvancedStats/stats/timeseries?type=sequence&period=day"

# Get heat map data for GPIO events
curl "http://192.168.1.200/api/plugin/fpp-plugin-AdvancedStats/stats/heatmap?type=gpio"

# Get live event stream including commands (events in last 60 seconds)
curl "http://192.168.1.200/api/plugin/fpp-plugin-AdvancedStats/events/stream?since=$(($(date +%s) - 60))&types=sequence,playlist,gpio,command,command_preset"

# Get database information
curl http://192.168.1.200/api/plugin/fpp-plugin-AdvancedStats/database-info

# Archive old data (dry run preview)
curl -X POST http://192.168.1.200/api/plugin/fpp-plugin-AdvancedStats/archive-old-data \
  -H "Content-Type: application/json" \
  -d '{"retention_days": 365, "dry_run": true}'
            </div>
            
            <h3>Response Format</h3>
            <p>All API endpoints return JSON responses with a standard structure:</p>
            <div class="code-block">
{
  "success": true,    // or false if error
  "message": "...",   // error message if success=false
  "data": { ... }     // endpoint-specific data
}
            </div>
        </div>
        
        <!-- Troubleshooting Tab -->
        <div id="troubleshooting" class="tab-content">
            <h2>Common Issues & Solutions</h2>
            
            <div style="background-color: #f8f9fa; border-left: 4px solid #007bff; padding: 15px; margin: 15px 0; border-radius: 4px;">
                <h3 style="margin-top: 0;"><i class="fas fa-exclamation-triangle"></i> No Statistics Being Collected</h3>
                <p><strong>Symptoms:</strong> Dashboard shows zero events, no data appearing</p>
                <p><strong>Causes & Solutions:</strong></p>
                <ol>
                    <li><strong>MQTT Broker Not Enabled</strong>
                        <ul>
                            <li>Go to Status/Control → FPP Settings → Services</li>
                            <li>Enable "Enable Local MQTT Broker"</li>
                            <li>Save and restart FPPD</li>
                        </ul>
                    </li>
                    <li><strong>MQTT Not Configured</strong>
                        <ul>
                            <li>Go to Status/Control → FPP Settings → MQTT tab</li>
                            <li>Set Host: <code>localhost</code>, Port: <code>1883</code></li>
                            <li>Set Username: <code>fpp</code>, Password: <code>falcon</code></li>
                            <li>Save settings</li>
                        </ul>
                    </li>
                    <li><strong>MQTT Listener Not Running</strong>
                        <ul>
                            <li>Check if plugin is enabled in Plugin Manager</li>
                            <li>Restart FPPD: <code>sudo systemctl restart fppd</code></li>
                            <li>Check logs: <code>/var/log/fpp/fpp.log</code></li>
                        </ul>
                    </li>
                </ol>
            </div>
            
            <div style="background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 15px 0; border-radius: 4px;">
                <h3 style="margin-top: 0;"><i class="fas fa-chart-line"></i> Charts Not Loading / Displaying Errors</h3>
                <p><strong>Symptoms:</strong> Blank chart areas, JavaScript errors in console</p>
                <p><strong>Solutions:</strong></p>
                <ul>
                    <li><strong>Clear Browser Cache:</strong> Press Ctrl+Shift+R (or Cmd+Shift+R on Mac)</li>
                    <li><strong>Check Chart.js:</strong> Ensure <code>js/chart.min.js</code> exists in plugin directory</li>
                    <li><strong>Browser Console:</strong> Open Developer Tools (F12) and check for JavaScript errors</li>
                    <li><strong>Verify Data:</strong> Test API endpoint: <code>/api/plugin/fpp-plugin-AdvancedStats/stats/timeseries?type=sequence&period=day</code></li>
                </ul>
            </div>
            
            <div style="background-color: #d4edda; border-left: 4px solid #28a745; padding: 15px; margin: 15px 0; border-radius: 4px;">
                <h3 style="margin-top: 0;"><i class="fas fa-database"></i> Database Errors</h3>
                <p><strong>Symptoms:</strong> "Database locked" errors, data not saving</p>
                <p><strong>Solutions:</strong></p>
                <ul>
                    <li><strong>Check Permissions:</strong>
                        <ul>
                            <li>Database location: <code>/home/fpp/media/config/plugin.fpp-plugin-AdvancedStats.db</code></li>
                            <li>Ensure www-data user has write access</li>
                            <li>Run: <code>sudo chown www-data:www-data /home/fpp/media/config/plugin.fpp-plugin-AdvancedStats.db</code></li>
                        </ul>
                    </li>
                    <li><strong>Database Corruption:</strong>
                        <ul>
                            <li>Backup existing database</li>
                            <li>Try restoring from a backup</li>
                            <li>If needed, delete and recreate: plugin will rebuild schema</li>
                        </ul>
                    </li>
                </ul>
            </div>
            
            <div style="background-color: #f8d7da; border-left: 4px solid #dc3545; padding: 15px; margin: 15px 0; border-radius: 4px;">
                <h3 style="margin-top: 0;"><i class="fas fa-microchip"></i> GPIO Events Not Being Captured</h3>
                <p><strong>Symptoms:</strong> Button presses work but don't appear in stats</p>
                <p><strong>Solutions:</strong></p>
                <ul>
                    <li><strong>Check GPIO Configuration:</strong>
                        <li>Go to Input/Output Setup → GPIO Inputs</li>
                        <li>Ensure GPIO pins are properly configured</li>
                        <li>Verify "Mode" is set correctly (e.g., GPIO Input)</li>
                    </li>
                    <li><strong>MQTT GPIO Events:</strong>
                        <ul>
                            <li>Plugin requires MQTT broker to capture GPIO events</li>
                            <li>FPP publishes GPIO state changes to MQTT topic <code>falcon/player/#/gpio/+</code></li>
                            <li>Verify MQTT is working with: <code>mosquitto_sub -h localhost -t "falcon/player/#" -v</code></li>
                        </ul>
                    </li>
                    <li><strong>Add GPIO Descriptions:</strong>
                        <ul>
                            <li>Use Channel Inputs page to add descriptions to GPIO pins</li>
                            <li>Descriptions make events easier to identify in the stats</li>
                        </ul>
                    </li>
                </ul>
            </div>
            
            <div style="background-color: #d1ecf1; border-left: 4px solid #17a2b8; padding: 15px; margin: 15px 0; border-radius: 4px;">
                <h3 style="margin-top: 0;"><i class="fas fa-sync-alt"></i> Dashboard Not Updating / Stale Data</h3>
                <p><strong>Solutions:</strong></p>
                <ul>
                    <li><strong>Hard Refresh:</strong> Press Ctrl+Shift+R (Cmd+Shift+R on Mac)</li>
                    <li><strong>Check Browser Console:</strong> Look for API call errors (F12 → Console tab)</li>
                    <li><strong>Test API Manually:</strong> Visit <code>http://your-fpp-ip/api/plugin/fpp-plugin-AdvancedStats/dashboard-data</code></li>
                    <li><strong>Verify Plugin Active:</strong> Check Content Setup → Plugin Manager</li>
                </ul>
            </div>
            
            <h3>Performance Optimization</h3>
            <div style="background-color: #e2e3e5; padding: 15px; margin: 15px 0; border-radius: 4px; border: 1px solid #6c757d;">
                <p><strong>If dashboard is loading slowly:</strong></p>
                <ul>
                    <li><strong>Archive Old Data:</strong> Large databases slow down queries
                        <ul>
                            <li>Go to Settings → Archive Old Data</li>
                            <li>Keep only necessary history (e.g., 365 days)</li>
                        </ul>
                    </li>
                    <li><strong>Reduce Page Size:</strong> Tables now show 15 entries per page (configurable)</li>
                    <li><strong>Use Live Monitor Sparingly:</strong> Real-time polling adds overhead</li>
                </ul>
            </div>
            
            <h3>Getting Additional Help</h3>
            <p>If you continue to experience issues:</p>
            <ul>
                <li><strong>GitHub Issues:</strong> <a href="https://github.com/OnlineDynamic/Statistics-Fpp-Plugin/issues" target="_blank">Report bugs or request features</a></li>
                <li><strong>FPP Forums:</strong> Post in the FPP community forums with detailed information</li>
                <li><strong>FPP Discord:</strong> Join the FPP Discord server for real-time help</li>
                <li><strong>System Logs:</strong> Check <code>/var/log/fpp/fpp.log</code> for error messages</li>
                <li><strong>Plugin Logs:</strong> Look in <code>/home/fpp/media/logs/</code> for plugin-specific logs</li>
            </ul>
            
            <h3>Debug Checklist</h3>
            <div class="code-block">
# 1. Check if MQTT broker is running
sudo systemctl status mosquitto

# 2. Test MQTT subscription to FPP events
mosquitto_sub -h localhost -u fpp -P falcon -t "falcon/player/#" -v

# 3. Check plugin files exist
ls -la /home/fpp/media/plugins/fpp-plugin-AdvancedStats/

# 4. Verify database exists and has data
sqlite3 /home/fpp/media/config/plugin.fpp-plugin-AdvancedStats.db "SELECT COUNT(*) FROM sequence_history;"

# 5. Check Python listener is running
ps aux | grep mqtt_listener

# 6. View recent log entries
tail -50 /var/log/fpp/fpp.log | grep -i "advancedstats\|mqtt"

# 7. Test API endpoint
curl http://localhost/api/plugin/fpp-plugin-AdvancedStats/status
            </div>
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
                            <i class="fas fa-code-branch"></i> Git Repository
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
