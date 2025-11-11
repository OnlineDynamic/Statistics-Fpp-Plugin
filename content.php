<!DOCTYPE html>
<html>
<head>
    <title>Advanced Stats Settings</title>
    <link rel="stylesheet" href="/css/fpp.css" />
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
                <a href="plugin.php?_menu=status&plugin=fpp-plugin-AdvancedStats&page=advancedstats-about.php">
                    <i class="fas fa-info-circle"></i> About
                </a>
                <a href="plugin.php?_menu=status&plugin=fpp-plugin-AdvancedStats&page=help/advancedstats-help.php">
                    <i class="fas fa-question-circle"></i> Help
                </a>
            </div>
            <h1><i class="fas fa-cog"></i> Advanced Stats Settings</h1>
            <p style="color: #6c757d; font-size: 16px;">Configure plugin options and preferences</p>
        </div>
        
        <div class="placeholder-message">
            <i class="fas fa-tools"></i>
            <h2 style="color: #856404; margin: 10px 0;">Settings Page Template</h2>
            <p style="color: #856404; margin: 0;">
                This is a template settings page. Replace this content with your actual plugin configuration options.
            </p>
        </div>
        
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
                
                <div class="form-group">
                    <label for="dataRetention">Data Retention (days)</label>
                    <input type="number" id="dataRetention" name="dataRetention" value="30" min="1" max="365">
                    <small>How long to keep historical statistics (1-365 days)</small>
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
        
        <div style="margin-top: 30px; padding: 20px; background-color: #d1ecf1; border-left: 4px solid #0c5460; border-radius: 4px;">
            <h3 style="margin-top: 0; color: #0c5460;">
                <i class="fas fa-info-circle"></i> Customization Notes
            </h3>
            <p style="color: #0c5460; margin: 10px 0;">
                This is a template settings page. To customize:
            </p>
            <ul style="color: #0c5460;">
                <li>Replace the example settings with your actual plugin configuration options</li>
                <li>Update the saveSettings() function to handle form submission</li>
                <li>Load existing settings from the plugin configuration file</li>
                <li>Add validation for your specific settings</li>
            </ul>
        </div>
    </div>
    
    <script>
        // Placeholder JavaScript - replace with actual implementation
        
        // Load existing settings when page loads
        document.addEventListener('DOMContentLoaded', function() {
            loadSettings();
        });
        
        function loadSettings() {
            // TODO: Load settings from API
            // Example:
            // fetch('/api/plugin/fpp-plugin-AdvancedStats/get-settings')
            //     .then(response => response.json())
            //     .then(data => {
            //         document.getElementById('enableStats').value = data.enableStats || '1';
            //         document.getElementById('updateInterval').value = data.updateInterval || '60';
            //         // etc...
            //     });
        }
        
        function saveSettings(event) {
            event.preventDefault();
            
            // TODO: Implement actual save functionality
            // Example:
            // const formData = new FormData(event.target);
            // const settings = Object.fromEntries(formData);
            // 
            // fetch('/api/plugin/fpp-plugin-AdvancedStats/save-settings', {
            //     method: 'POST',
            //     headers: { 'Content-Type': 'application/json' },
            //     body: JSON.stringify(settings)
            // })
            // .then(response => response.json())
            // .then(data => {
            //     if (data.status === 'OK') {
            //         alert('Settings saved successfully!');
            //     } else {
            //         alert('Error saving settings: ' + data.message);
            //     }
            // });
            
            alert('Settings page is a template. Implement actual save functionality.');
            return false;
        }
    </script>
</body>
</html>
