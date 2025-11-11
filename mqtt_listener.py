#!/usr/bin/env python3
"""
Advanced Stats Plugin - MQTT Event Listener
Subscribes to FPP's local MQTT broker to capture events in real-time
"""

import paho.mqtt.client as mqtt
import json
import time
import sys
import os
from stats_db import StatsDatabase

# MQTT Configuration
MQTT_HOST = "localhost"
MQTT_PORT = 1883
MQTT_CLIENT_ID = "fpp-plugin-advancedstats"

# FPP MQTT Topics
TOPIC_SEQUENCE = "falcon/player/+/event/sequence/#"
TOPIC_PLAYLIST = "falcon/player/+/event/playlist/#"
TOPIC_GPIO = "falcon/player/+/gpio/#"
TOPIC_STATUS = "falcon/player/+/status"

# Database connection
db = None

# Log file
LOG_FILE = "/home/fpp/media/logs/fpp-plugin-AdvancedStats.log"

def log_message(message):
    """Write message to log file and console"""
    timestamp = time.strftime("%Y-%m-%d %H:%M:%S")
    log_line = f"[{timestamp}] {message}\n"
    print(log_line, end='')
    try:
        with open(LOG_FILE, 'a') as f:
            f.write(log_line)
    except:
        pass

def on_connect(client, userdata, flags, rc):
    """Callback when connected to MQTT broker"""
    if rc == 0:
        log_message("Connected to MQTT broker successfully")
        
        # Subscribe to all FPP event topics
        client.subscribe(TOPIC_SEQUENCE)
        log_message(f"Subscribed to: {TOPIC_SEQUENCE}")
        
        client.subscribe(TOPIC_PLAYLIST)
        log_message(f"Subscribed to: {TOPIC_PLAYLIST}")
        
        client.subscribe(TOPIC_GPIO)
        log_message(f"Subscribed to: {TOPIC_GPIO}")
        
        client.subscribe(TOPIC_STATUS)
        log_message(f"Subscribed to: {TOPIC_STATUS}")
        
    else:
        log_message(f"Failed to connect to MQTT broker. Return code: {rc}")

def on_disconnect(client, userdata, rc):
    """Callback when disconnected from MQTT broker"""
    if rc != 0:
        log_message(f"Unexpected MQTT disconnection. Will auto-reconnect. RC: {rc}")

def on_message(client, userdata, msg):
    """Callback when a message is received"""
    global db
    
    try:
        topic = msg.topic
        payload = msg.payload.decode('utf-8')
        
        log_message(f"MQTT Message - Topic: {topic}")
        
        # Try to parse JSON payload
        try:
            data = json.loads(payload)
        except:
            data = {'raw': payload}
        
        # Handle sequence events
        if '/event/sequence/' in topic:
            handle_sequence_event(topic, data)
        
        # Handle playlist events
        elif '/event/playlist/' in topic:
            handle_playlist_event(topic, data)
        
        # Handle GPIO events
        elif '/gpio/' in topic:
            handle_gpio_event(topic, data)
        
        # Handle status updates
        elif '/status' in topic:
            handle_status_event(topic, data)
            
    except Exception as e:
        log_message(f"Error processing MQTT message: {e}")

def handle_sequence_event(topic, data):
    """Handle sequence start/stop events"""
    global db
    
    try:
        # Topic format: falcon/player/FPP/event/sequence/start or /stop
        action = 'start' if '/start' in topic else 'stop'
        
        sequence_name = data.get('sequence', data.get('name', 'Unknown'))
        playlist_name = data.get('playlist', '')
        duration = data.get('duration', 0)
        
        if db and sequence_name != 'Unknown':
            db.log_sequence_event(
                sequence_name=sequence_name,
                event_type=action,
                playlist_name=playlist_name,
                duration=duration,
                trigger_source='mqtt'
            )
            log_message(f"Logged sequence {action}: {sequence_name}")
            
    except Exception as e:
        log_message(f"Error handling sequence event: {e}")

def handle_playlist_event(topic, data):
    """Handle playlist start/stop events"""
    global db
    
    try:
        # Topic format: falcon/player/FPP/event/playlist/start or /stop
        action = 'start' if '/start' in topic else 'stop'
        
        playlist_name = data.get('playlist', data.get('name', 'Unknown'))
        
        if db and playlist_name != 'Unknown':
            db.log_playlist_event(
                playlist_name=playlist_name,
                event_type=action,
                trigger_source='mqtt'
            )
            log_message(f"Logged playlist {action}: {playlist_name}")
            
    except Exception as e:
        log_message(f"Error handling playlist event: {e}")

def handle_gpio_event(topic, data):
    """Handle GPIO state change events"""
    global db
    
    try:
        # Topic format: falcon/player/FPP/gpio/<pin_number>
        # Extract pin number from topic
        parts = topic.split('/')
        if len(parts) >= 5:
            pin_number = int(parts[-1])
            
            # Get state from payload
            if isinstance(data, dict):
                pin_state = data.get('state', data.get('value', 0))
            else:
                pin_state = int(data.get('raw', 0))
            
            if db:
                db.log_gpio_event(
                    pin_number=pin_number,
                    pin_state=pin_state,
                    event_type='mqtt',
                    description=f'MQTT GPIO event on pin {pin_number}'
                )
                log_message(f"Logged GPIO event: Pin {pin_number} = {pin_state}")
                
    except Exception as e:
        log_message(f"Error handling GPIO event: {e}")

def handle_status_event(topic, data):
    """Handle FPP status updates (optional - for future use)"""
    # Could track system status, mode changes, etc.
    pass

def main():
    """Main entry point"""
    global db
    
    log_message("=" * 60)
    log_message("Advanced Stats Plugin - MQTT Listener Starting")
    log_message("=" * 60)
    
    # Initialize database connection
    try:
        db = StatsDatabase()
        log_message("Database connection initialized")
    except Exception as e:
        log_message(f"ERROR: Failed to initialize database: {e}")
        sys.exit(1)
    
    # Create MQTT client
    client = mqtt.Client(client_id=MQTT_CLIENT_ID, clean_session=True)
    client.on_connect = on_connect
    client.on_disconnect = on_disconnect
    client.on_message = on_message
    
    # Connect to MQTT broker
    try:
        log_message(f"Connecting to MQTT broker at {MQTT_HOST}:{MQTT_PORT}...")
        client.connect(MQTT_HOST, MQTT_PORT, 60)
        
        # Start the MQTT loop (blocking - runs forever)
        log_message("MQTT listener is running. Press Ctrl+C to stop.")
        client.loop_forever()
        
    except KeyboardInterrupt:
        log_message("MQTT listener stopped by user (Ctrl+C)")
        client.disconnect()
        sys.exit(0)
        
    except Exception as e:
        log_message(f"ERROR: Failed to connect to MQTT broker: {e}")
        log_message("Make sure MQTT is enabled in FPP settings")
        sys.exit(1)

if __name__ == "__main__":
    main()
