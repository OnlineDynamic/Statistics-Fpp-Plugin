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
# FPP publishes status under falcon/player/{hostname}/...
# Subscribe to all subtopics to capture everything
TOPIC_ALL = "falcon/player/+/#"

# Database connection
db = None

# Cache for tracking current sequence state
current_sequence = {
    'name': None,
    'duration': 0,
    'start_time': 0
}

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
        
        # Subscribe to all FPP topics
        client.subscribe(TOPIC_ALL)
        log_message(f"Subscribed to: {TOPIC_ALL}")
        
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
        
        log_message(f"MQTT Message - Topic: {topic}, Payload: {payload[:100]}")
        
        # Try to parse JSON payload
        try:
            data = json.loads(payload)
        except:
            data = {'raw': payload}
        
        # FPP topic structure: falcon/player/{hostname}/{subtopic}/...
        # Example: falcon/player/Dev-Pi4/playlist/name/status
        # Example: falcon/player/Dev-Pi4/current_sequence
        
        parts = topic.split('/')
        if len(parts) < 4:
            return
            
        # parts[0] = falcon
        # parts[1] = player  
        # parts[2] = hostname
        # parts[3+] = subtopic path
        
        subtopic = '/'.join(parts[3:])  # Everything after hostname
        
        # Check most specific patterns first to avoid mis-routing
        # Handle sequence-related topics (check before playlist since sequences are under playlist path)
        if 'playlist/sequence' in subtopic or 'current_sequence' in subtopic:
            handle_sequence_topic(topic, subtopic, payload, data)
        
        # Handle playlist-related topics
        elif 'playlist' in subtopic:
            handle_playlist_topic(topic, subtopic, payload, data)
        
        # Handle GPIO-related topics  
        elif 'gpio' in subtopic:
            handle_gpio_topic(topic, subtopic, payload, data)
            
    except Exception as e:
        log_message(f"Error processing MQTT message: {e}")

def handle_sequence_topic(topic, subtopic, payload, data):
    """Handle sequence-related topics"""
    global db, current_sequence
    
    try:
        # FPP publishes two related topics:
        # falcon/player/{hostname}/playlist/sequence/status - sequence filename or empty
        # falcon/player/{hostname}/playlist/sequence/secondsTotal - duration or empty
        
        if 'playlist/sequence/status' in subtopic:
            sequence_name = payload.strip()
            
            if sequence_name and sequence_name != '':
                # Sequence started - cache the name and time
                current_sequence['name'] = sequence_name
                current_sequence['start_time'] = int(time.time())
                log_message(f"Sequence starting: {sequence_name}")
                
            else:
                # Sequence stopped (empty payload)
                if current_sequence['name']:
                    actual_duration = int(time.time()) - current_sequence['start_time']
                    
                    if db:
                        db.log_sequence_event(
                            sequence_name=current_sequence['name'],
                            event_type='stop',
                            playlist_name='',
                            duration=actual_duration,
                            trigger_source='mqtt'
                        )
                        log_message(f"Logged sequence stop: {current_sequence['name']} (duration: {actual_duration}s)")
                    
                    # Reset cache
                    current_sequence['name'] = None
                    current_sequence['duration'] = 0
                    current_sequence['start_time'] = 0
        
        elif 'playlist/sequence/secondsTotal' in subtopic:
            # Expected duration received
            try:
                duration = int(payload.strip()) if payload.strip() else 0
                current_sequence['duration'] = duration
                
                # If we have both name and duration, log the start event
                if current_sequence['name'] and duration > 0:
                    if db:
                        db.log_sequence_event(
                            sequence_name=current_sequence['name'],
                            event_type='start',
                            playlist_name='',
                            duration=duration,
                            trigger_source='mqtt'
                        )
                        log_message(f"Logged sequence start: {current_sequence['name']} (expected duration: {duration}s)")
                
            except ValueError:
                pass
                
    except Exception as e:
        log_message(f"Error handling sequence topic: {e}")

def handle_playlist_topic(topic, subtopic, payload, data):
    """Handle playlist-related topics"""
    global db
    
    try:
        # FPP publishes: falcon/player/{hostname}/playlist/name/status with playlist/sequence name
        # FPP publishes: falcon/player/{hostname}/status with 'idle' or 'playing'
        
        if 'playlist/name/status' in subtopic:
            playlist_name = payload.strip()
            
            if playlist_name and playlist_name != '':
                # Only log if it's NOT a standalone sequence file
                # Standalone sequences end with .fseq and should only be tracked in sequence_history
                # Real playlists don't have file extensions
                if not playlist_name.endswith('.fseq'):
                    # This is an actual playlist (contains multiple items)
                    if db:
                        db.log_playlist_event(
                            playlist_name=playlist_name,
                            event_type='start',
                            trigger_source='mqtt'
                        )
                        log_message(f"Logged playlist start: {playlist_name}")
                else:
                    # This is a standalone sequence, not a playlist - it will be logged in sequence_history
                    log_message(f"Skipped logging standalone sequence as playlist: {playlist_name}")
        
        # Also track when status changes to playing/idle
        elif subtopic == 'status':
            status = payload.strip()
            log_message(f"FPP Status changed to: {status}")
            # Could track this separately if needed
                
    except Exception as e:
        log_message(f"Error handling playlist topic: {e}")

def handle_gpio_topic(topic, subtopic, payload, data):
    """Handle GPIO-related topics"""
    global db
    
    try:
        # FPP may publish GPIO events - need to determine actual format
        # For now, log what we receive
        log_message(f"GPIO topic received: {subtopic}, payload: {payload}")
                
    except Exception as e:
        log_message(f"Error handling GPIO topic: {e}")

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
    
    # Set username and password (FPP uses 'fpp' user credentials)
    # FPP's local MQTT broker uses the same credentials as the FPP user
    client.username_pw_set("fpp", "falcon")
    
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
