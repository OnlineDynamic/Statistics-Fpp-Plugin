#!/bin/bash

# fpp-plugin-advancedstats install script

BASEDIR=$(dirname "$0")
cd "$BASEDIR"
cd ..
PLUGIN_DIR=$(pwd)

# Source FPP common functions if available
if [ -f "${FPPDIR}/scripts/common" ]; then
    . ${FPPDIR}/scripts/common
elif [ -f "/opt/fpp/scripts/common" ]; then
    . /opt/fpp/scripts/common
fi


# Create log files with proper permissions
LOG_FILE="/home/fpp/media/logs/fpp-plugin-advancedstats.log"


if [ ! -f "$LOG_FILE" ]; then
    touch "$LOG_FILE"
    chown fpp:fpp "$LOG_FILE"
    chmod 664 "$LOG_FILE"
    echo "Created log file: $LOG_FILE"
fi

# Install Python MQTT client library
echo "Installing Python MQTT library..."
apt-get install -y python3-paho-mqtt > /dev/null 2>&1 || echo "Warning: Could not install python3-paho-mqtt"

# Check if database already exists (upgrade scenario)
DB_PATH="/home/fpp/media/config/plugin.fpp-plugin-AdvancedStats.db"
if [ -f "$DB_PATH" ]; then
    echo "Existing database found - running migrations..."
    php "${PLUGIN_DIR}/migrate_database.php"
else
    echo "No existing database - performing fresh installation..."
fi

# Initialize database (creates tables if they don't exist)
echo "Initializing Advanced Stats database..."
php "${PLUGIN_DIR}/init_database.php"
if [ $? -eq 0 ]; then
    echo "Database initialized successfully"
else
    echo "Warning: Database initialization failed"
fi

# Set restart flag if setSetting function is available
if command -v setSetting &> /dev/null; then
    setSetting restartFlag 1
fi