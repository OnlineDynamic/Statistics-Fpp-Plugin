#!/bin/sh

# Advanced Stats Plugin - Post-Start Script
# This script runs after FPP starts

# TEMPORARILY DISABLED - MQTT listener causing performance issues with fppd
# Will investigate and fix the issue before re-enabling
exit 0

BASEDIR=$(dirname "$0")
cd "$BASEDIR"
cd ..
PLUGIN_DIR=$(pwd)

# Wait a moment for FPP to fully initialize
sleep 3

# Start MQTT listener in background
MQTT_LISTENER="${PLUGIN_DIR}/mqtt_listener.py"
LOG_FILE="/home/fpp/media/logs/fpp-plugin-AdvancedStats.log"

# Check if MQTT listener is already running
if pgrep -f "mqtt_listener.py" > /dev/null; then
    echo "MQTT listener already running" >> "$LOG_FILE"
else
    # Make sure the script is executable
    chmod +x "$MQTT_LISTENER"
    
    # Start the MQTT listener
    echo "Starting Advanced Stats MQTT listener..." >> "$LOG_FILE"
    nohup python3 "$MQTT_LISTENER" >> "$LOG_FILE" 2>&1 &
    
    echo "MQTT listener started with PID: $!" >> "$LOG_FILE"
fi


