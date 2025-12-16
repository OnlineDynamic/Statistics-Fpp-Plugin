<?php
// Check if MQTT broker is enabled
function isMQTTEnabled() {
    $settings_file = '/home/fpp/media/settings';
    if (file_exists($settings_file)) {
        $settings = file_get_contents($settings_file);
        // Check if Service_MQTT_localbroker is set to 1 (enabled)
        if (preg_match('/Service_MQTT_localbroker\s*=\s*"?1"?/i', $settings)) {
            return true;
        }
    }
    return false;
}

// Check if mosquitto service is running
function isMQTTRunning() {
    $output = shell_exec('systemctl is-active mosquitto 2>&1');
    return trim($output) === 'active';
}

// Display MQTT warning banner if not enabled or not running
function displayMQTTWarning() {
    $mqttEnabled = isMQTTEnabled();
    $mqttRunning = isMQTTRunning();
    
    if (!$mqttEnabled || !$mqttRunning): ?>
    <!-- MQTT Warning Banner -->
    <div class="mqtt-warning-banner">
        <i class="fas fa-exclamation-triangle"></i>
        <div>
            <strong>⚠️ MQTT Broker Not Enabled</strong>
            <?php if (!$mqttEnabled): ?>
            <p style="margin: 5px 0 0 0;">
                The plugin requires the MQTT broker to be enabled to capture real-time events. 
                Please enable <strong>"Enable Local MQTT Broker"</strong> in 
                <a href="/settings.php#settings-services" target="_blank">FPP Settings → Services</a> and restart FPPD.
            </p>
            <?php elseif (!$mqttRunning): ?>
            <p style="margin: 5px 0 0 0;">
                The MQTT broker is enabled but not running. Please restart FPPD or run: 
                <code style="background-color: #fff; padding: 2px 6px; border-radius: 3px;">sudo systemctl start mosquitto</code>
            </p>
            <?php endif; ?>
        </div>
    </div>
    <?php endif;
}

// CSS styles for MQTT warning banner
function getMQTTWarningStyles() {
    return <<<CSS
    <style>
        .mqtt-warning-banner {
            background-color: #fff3cd;
            border: 2px solid #ffc107;
            color: #856404;
            padding: 15px 20px;
            border-radius: 5px;
            margin: 20px 0;
            display: flex;
            align-items: center;
            font-size: 14px;
        }
        
        .mqtt-warning-banner i {
            font-size: 24px;
            margin-right: 15px;
            color: #ffc107;
        }
        
        .mqtt-warning-banner strong {
            display: block;
            font-size: 16px;
            margin-bottom: 5px;
        }
        
        .mqtt-warning-banner a {
            color: #004085;
            text-decoration: underline;
            font-weight: bold;
        }
        
        .mqtt-warning-banner a:hover {
            color: #002752;
        }
    </style>
CSS;
}
?>
