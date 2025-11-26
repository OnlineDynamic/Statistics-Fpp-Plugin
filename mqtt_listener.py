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

# FPP Configuration Paths
GPIO_CONFIG_FILE = "/home/fpp/media/config/gpio.json"

# FPP MQTT Topics
# FPP publishes status under falcon/player/{hostname}/...
# Subscribe to all subtopics to capture everything
TOPIC_ALL = "falcon/player/+/#"

# Database connection
db = None

# GPIO pin descriptions cache
gpio_descriptions = {}

# Cache for tracking current sequence state
current_sequence = {
    'name': None,
    'duration': 0,
    'start_time': 0
}

# Log file
LOG_FILE = "/home/fpp/media/logs/fpp-plugin-AdvancedStats.log"

def load_gpio_descriptions():
    """Load GPIO pin descriptions from FPP config"""
    global gpio_descriptions
    
    try:
        if os.path.exists(GPIO_CONFIG_FILE):
            with open(GPIO_CONFIG_FILE, 'r') as f:
                gpio_config = json.load(f)
                
            for gpio_pin in gpio_config:
                if 'pin' in gpio_pin and 'desc' in gpio_pin:
                    pin_name = gpio_pin['pin']
                    description = gpio_pin['desc']
                    gpio_descriptions[pin_name] = description
                    
            log_message(f"Loaded descriptions for {len(gpio_descriptions)} GPIO pins")
        else:
            log_message(f"GPIO config file not found: {GPIO_CONFIG_FILE}")
            
    except Exception as e:
        log_message(f"Error loading GPIO descriptions: {e}")

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
        
        # Handle command-related topics
        elif 'command' in subtopic:
            handle_command_topic(topic, subtopic, payload, data)
            
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
    """Handle GPIO-related topics
    
    Topic format: falcon/player/{hostname}/gpio/{pin_name}/{event_type}
    Where event_type is: event, rising, or falling
    Payload: "1" for high/rising, "0" for low/falling
    
    Note: FPP publishes 3 messages per GPIO change:
    - 'event' (generic, with current state)
    - 'rising' or 'falling' (specific edge)
    We only capture rising/falling to avoid duplicates.
    """
    global db, gpio_descriptions
    
    try:
        # Parse topic: falcon/player/{hostname}/gpio/{pin_name}/{event_type}
        # Example: falcon/player/FPP/gpio/GPIO17/rising
        parts = topic.split('/')
        
        if len(parts) < 6:
            log_message(f"GPIO topic format unexpected: {topic}")
            return
        
        pin_name = parts[4]  # e.g., "GPIO17", "P8-07", "1-0038-0"
        event_type = parts[5]  # "event", "rising", or "falling"
        
        # Skip generic 'event' messages to avoid duplicates
        # Only capture rising/falling which are the actual edge triggers
        if event_type == 'event':
            return
        
        # Payload is "1" or "0"
        pin_state = 1 if payload.strip() == "1" else 0
        
        # Get description from cache
        description = gpio_descriptions.get(pin_name, '')
        
        # Log the event to database
        if db:
            db.log_gpio_event(
                pin_number=pin_name,  # Store pin name as-is (GPIO17, P8-07, etc.)
                pin_state=pin_state,
                event_type=event_type,
                description=description
            )
            desc_display = f" ({description})" if description else ""
            log_message(f"GPIO Event: {pin_name}{desc_display} {event_type} (state={pin_state})")
        else:
            log_message(f"GPIO event received but database not available: {pin_name} {event_type}")
                
    except Exception as e:
        log_message(f"Error handling GPIO topic: {e}")

def handle_command_topic(topic, subtopic, payload, data):
    """Handle command-related MQTT topics"""
    global db
    
    try:
        # Command topics structure:
        # falcon/player/{hostname}/command/run - individual command execution
        # falcon/player/{hostname}/command/preset/triggered - preset execution
        
        if 'command/preset/triggered' in subtopic:
            # Command preset execution
            # Expected payload: JSON with preset details
            preset_name = data.get('preset', data.get('name', str(data.get('slot', 'Unknown'))))
            # Count commands if provided, otherwise estimate or use 0
            command_count = data.get('command_count', len(data.get('commands', []))) if 'commands' in data else 1
            trigger_source = data.get('trigger', data.get('source', ''))
            payload_json = json.dumps(data) if isinstance(data, dict) else payload
            
            if db:
                db.log_command_preset_event(
                    preset_name=preset_name,
                    command_count=command_count,
                    trigger_source=trigger_source,
                    payload_json=payload_json
                )
                log_message(f"Command Preset Executed: {preset_name} ({command_count} commands) - Trigger: {trigger_source}")
            else:
                log_message(f"Command preset event received but database not available: {preset_name}")
                
        elif 'command/run' in subtopic:
            # Individual command execution
            # Expected payload: JSON with command details
            command = data.get('command', data.get('type', 'Unknown'))
            args = json.dumps(data.get('args', [])) if 'args' in data else ''
            multisync_command = 1 if data.get('multisyncCommand', False) else 0
            multisync_hosts = ','.join(data.get('multisyncHosts', [])) if 'multisyncHosts' in data else ''
            trigger_source = data.get('trigger', data.get('source', ''))
            payload_json = json.dumps(data) if isinstance(data, dict) else payload
            
            if db:
                db.log_command_event(
                    command=command,
                    args=args,
                    multisync_command=multisync_command,
                    multisync_hosts=multisync_hosts,
                    trigger_source=trigger_source,
                    payload_json=payload_json
                )
                multisync_str = f" [MultiSync: {multisync_hosts}]" if multisync_command else ""
                log_message(f"Command Executed: {command} (args: {args[:50]}){multisync_str}")
            else:
                log_message(f"Command event received but database not available: {command}")
                
    except Exception as e:
        log_message(f"Error handling command topic: {e}")

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
    
    # Load GPIO descriptions from config
    load_gpio_descriptions()
    
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
