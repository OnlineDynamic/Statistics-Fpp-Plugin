#!/bin/sh

# Advanced Stats Plugin - Pre-Stop Script
# This script runs before FPP stops

LOG_FILE="/home/fpp/media/logs/fpp-plugin-AdvancedStats.log"

# Stop MQTT listener
echo "Stopping Advanced Stats MQTT listener..." >> "$LOG_FILE"

# Find and kill the MQTT listener process
pkill -f "mqtt_listener.py"

if [ $? -eq 0 ]; then
    echo "MQTT listener stopped successfully" >> "$LOG_FILE"
else
    echo "MQTT listener was not running" >> "$LOG_FILE"
fi


