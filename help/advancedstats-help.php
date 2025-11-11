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
    </style>
</head>
<body>
    <div id="global" class="settings">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h1 style="margin: 0;">Advanced Stats Plugin - Help & About</h1>
            <div>
                <a href="/plugin.php?_menu=status&plugin=fpp-plugin-AdvancedStats&page=advancedstats.php" class="btn btn-outline-secondary" style="margin-right: 5px;">
                    <i class="fas fa-chart-line"></i> Dashboard
                </a>
                <a href="/plugin.php?_menu=content&plugin=fpp-plugin-AdvancedStats&page=content.php" class="btn btn-outline-secondary">
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
            <button class="tab-button" onclick="switchTab('changelog', this)">
                <i class="fas fa-history"></i> Changelog
            </button>
            <button class="tab-button" onclick="switchTab('about', this)">
                <i class="fas fa-info-circle"></i> About
            </button>
        </div>
        
        <!-- Overview Tab -->
        <div id="overview" class="tab-content active">
            <h2>Welcome to Advanced Stats Plugin</h2>
            <p>
                The Advanced Stats Plugin provides comprehensive analytics and monitoring for your 
                Falcon Player (FPP) system. Track performance, analyze usage patterns, and gain 
                insights into your light show operations.
            </p>
            
            <h3>Key Features</h3>
            <ul>
                <li><strong>Real-time Monitoring:</strong> Track system metrics as they happen</li>
                <li><strong>Historical Data:</strong> View trends and patterns over time</li>
                <li><strong>Detailed Analytics:</strong> Comprehensive statistics about your shows</li>
                <li><strong>REST API:</strong> Full API access for automation and integration</li>
                <li><strong>Custom Reports:</strong> Generate reports tailored to your needs</li>
            </ul>
            
            <h3>What You Can Track</h3>
            <ul>
                <li>Playlist performance and usage statistics</li>
                <li>System resource utilization (CPU, memory, network)</li>
                <li>Sequence playback metrics</li>
                <li>Show runtime and scheduling data</li>
                <li>Custom events and milestones</li>
            </ul>
        </div>
        
        <!-- Setup Guide Tab -->
        <div id="setup" class="tab-content">
            <h2>Getting Started</h2>
            
            <h3>Installation</h3>
            <ol>
                <li>The plugin should already be installed via FPP's Plugin Manager</li>
                <li>If not, navigate to Content Setup → Plugin Manager</li>
                <li>Search for "Advanced Stats" and click Install</li>
                <li>Restart FPPd when prompted</li>
            </ol>
            
            <h3>Configuration</h3>
            <ol>
                <li>Go to Content Setup → Advanced Stats Settings</li>
                <li>Configure your tracking preferences:
                    <ul>
                        <li>Enable/disable specific metrics</li>
                        <li>Set data retention periods</li>
                        <li>Configure API access</li>
                        <li>Set up custom alerts (if applicable)</li>
                    </ul>
                </li>
                <li>Click "Save Settings" to apply your configuration</li>
            </ol>
            
            <h3>Using the Dashboard</h3>
            <ol>
                <li>Navigate to Status/Control → Advanced Stats Dashboard</li>
                <li>View real-time statistics and metrics</li>
                <li>Use filters to focus on specific data ranges</li>
                <li>Export data for external analysis if needed</li>
            </ol>
            
            <h3>Best Practices</h3>
            <ul>
                <li><strong>Regular Monitoring:</strong> Check your stats regularly to identify trends early</li>
                <li><strong>Data Retention:</strong> Balance detail with storage space - don't keep everything forever</li>
                <li><strong>Performance Impact:</strong> Be mindful of logging overhead on lower-powered devices</li>
                <li><strong>Security:</strong> If exposing API endpoints, ensure proper authentication is in place</li>
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
                // Helper function to escape HTML
                function escapeHtml(text) {
                    const div = document.createElement('div');
                    div.textContent = text;
                    return div.innerHTML;
                }
                
                // Load Git commit history when changelog tab is opened
                function loadChangelog() {
                    fetch('/api/plugin/fpp-plugin-AdvancedStats/get-commit-history')
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            const container = document.getElementById('changelogContent');
                            
                            if (data.status === 'OK' && data.commits && data.commits.length > 0) {
                                let html = '';
                                data.commits.forEach((commit, index) => {
                                    const date = new Date(commit.date);
                                    const formattedDate = date.toLocaleDateString('en-US', {
                                        year: 'numeric',
                                        month: 'long',
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
            <h2>About Advanced Stats Plugin</h2>
            <p>
                The Advanced Stats Plugin enhances Falcon Player (FPP) by providing comprehensive 
                analytics and monitoring capabilities to help you understand and optimize your light 
                show operations.
            </p>
            
            <h3>Features</h3>
            <ul>
                <li><strong>System Monitoring:</strong> Track FPP system metrics and performance</li>
                <li><strong>Playlist Analytics:</strong> Detailed statistics on playlist usage</li>
                <li><strong>Historical Tracking:</strong> Monitor trends over time</li>
                <li><strong>REST API:</strong> Full API for automation and integration</li>
                <li><strong>Custom Dashboards:</strong> Visualize data your way</li>
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
                        This plugin enhances Falcon Player (FPP) by adding comprehensive statistics 
                        and analytics capabilities to help you monitor and optimize your light shows.
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
