<!DOCTYPE html>
<html>
<head>
    <title>Advanced Stats Dashboard</title>
    <link rel="stylesheet" href="/css/fpp.css" />
    <style>
        .stats-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .stats-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .stats-header h1 {
            color: #007bff;
            margin-bottom: 10px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
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
    <div class="stats-container">
        <div class="stats-header">
            <h1><i class="fas fa-chart-line"></i> Advanced Stats Dashboard</h1>
            <p style="color: #6c757d; font-size: 16px;">Real-time analytics and system monitoring</p>
        </div>
        
        <div class="placeholder-message">
            <i class="fas fa-tools"></i>
            <h2 style="color: #856404; margin: 10px 0;">Dashboard Under Development</h2>
            <p style="color: #856404; margin: 0;">
                This is a template page for the Advanced Stats plugin. 
                Replace this content with your actual statistics dashboard implementation.
            </p>
        </div>
        
        <!-- Example stat cards - replace with actual data -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3><i class="fas fa-play-circle"></i> Total Playlists</h3>
                <div class="stat-value" id="totalPlaylists">--</div>
                <div class="stat-label">Configured playlists in system</div>
            </div>
            
            <div class="stat-card">
                <h3><i class="fas fa-clock"></i> Runtime Today</h3>
                <div class="stat-value" id="runtimeToday">--</div>
                <div class="stat-label">Hours of operation</div>
            </div>
            
            <div class="stat-card">
                <h3><i class="fas fa-server"></i> System Health</h3>
                <div class="stat-value" id="systemHealth">OK</div>
                <div class="stat-label">Current system status</div>
            </div>
            
            <div class="stat-card">
                <h3><i class="fas fa-list"></i> Active Sequences</h3>
                <div class="stat-value" id="activeSequences">--</div>
                <div class="stat-label">Currently running sequences</div>
            </div>
        </div>
        
        <div style="margin-top: 30px; padding: 20px; background-color: #d1ecf1; border-left: 4px solid #0c5460; border-radius: 4px;">
            <h3 style="margin-top: 0; color: #0c5460;">
                <i class="fas fa-info-circle"></i> Getting Started
            </h3>
            <p style="color: #0c5460; margin: 10px 0;">
                This is a template dashboard. To customize:
            </p>
            <ul style="color: #0c5460;">
                <li>Add your statistics gathering logic in the API endpoints</li>
                <li>Update the dashboard to fetch and display real data</li>
                <li>Create visualizations using charts and graphs</li>
                <li>Configure settings in Content Setup → Advanced Stats Settings</li>
            </ul>
        </div>
    </div>
    
    <script>
        // Placeholder JavaScript - replace with actual data fetching
        document.addEventListener('DOMContentLoaded', function() {
            // Example: Load stats from API
            loadStats();
        });
        
        function loadStats() {
            // TODO: Implement actual API calls to fetch statistics
            // For now, showing placeholder values
            
            // Example API call structure:
            // fetch('/api/plugin/fpp-plugin-AdvancedStats/stats')
            //     .then(response => response.json())
            //     .then(data => {
            //         document.getElementById('totalPlaylists').textContent = data.totalPlaylists;
            //         document.getElementById('runtimeToday').textContent = data.runtimeToday;
            //         // etc...
            //     });
        }
    </script>
</body>
</html>
