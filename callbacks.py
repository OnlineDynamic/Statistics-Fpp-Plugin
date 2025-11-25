#!/usr/bin/env python3

import time
import os
import argparse   
import sys
import json
import subprocess

# Import our database helper
try:
    from stats_db import StatsDatabase
    db = StatsDatabase()
except Exception as e:
    print(f"Error importing stats_db: {e}")
    db = None

class Logger(object):
    def __init__(self, filename="/home/fpp/media/logs/fpp-plugin-AdvancedStats.log"):
        self.terminal = sys.stdout
        self.log = open(filename, "a")

    def write(self, message):
        self.terminal.write(message)
        self.log.write(message)
    
    def flush(self):
        self.terminal.flush()
        self.log.flush()

sys.stdout = Logger("/home/fpp/media/logs/fpp-plugin-AdvancedStats.log")

parser = argparse.ArgumentParser(description='Advanced Stats Plugin')
parser.add_argument('-l','--list', help='Plugin Actions',action='store_true')
parser.add_argument('--type', help='Callback type')
parser.add_argument('--data', help='JSON data for callback')
parser.add_argument('--gpio', help='GPIO pin number')
parser.add_argument('--state', help='GPIO pin state')
args = parser.parse_args()

if args.list:
    # List available callbacks
    print("media")
    print("playlist")
    print("sequence")
    sys.exit(0)

# Handle callbacks
if args.type:
    timestamp = int(time.time())
    
    try:
        # Parse JSON data if provided
        data = {}
        if args.data:
            data = json.loads(args.data)
        
        # Media callback (sequence start/stop)
        if args.type == 'media':
            media_type = data.get('type', '')
            sequence_name = data.get('Sequence', data.get('sequence', ''))
            action = data.get('Action', data.get('action', ''))
            playlist_name = data.get('playlist', data.get('Playlist', ''))
            
            if sequence_name and action and db:
                if action == 'start':
                    db.log_sequence_event(sequence_name, 'start', playlist_name=playlist_name, trigger_source='fpp')
                    print(f"Logged sequence start: {sequence_name}" + (f" (playlist: {playlist_name})" if playlist_name else ""))
                elif action == 'stop':
                    # Try to get duration if available
                    duration = data.get('duration', 0)
                    db.log_sequence_event(sequence_name, 'stop', playlist_name=playlist_name, duration=duration, trigger_source='fpp')
                    print(f"Logged sequence stop: {sequence_name}" + (f" (playlist: {playlist_name})" if playlist_name else ""))
        
        # Playlist callback
        elif args.type == 'playlist':
            playlist_name = data.get('playlist', '')
            action = data.get('action', '')
            
            if playlist_name and action and db:
                if action in ['start', 'stop']:
                    # Only log if it's NOT a standalone sequence file
                    # Standalone sequences end with .fseq and should only be tracked in sequence_history
                    # Real playlists don't have file extensions
                    if not playlist_name.endswith('.fseq'):
                        db.log_playlist_event(playlist_name, action, trigger_source='fpp')
                        print(f"Logged playlist {action}: {playlist_name}")
                    else:
                        print(f"Skipped logging standalone sequence as playlist: {playlist_name}")
        
        # GPIO callback (if we add GPIO support)
        elif args.type == 'gpio' or args.gpio:
            pin_number = int(args.gpio) if args.gpio else data.get('pin', 0)
            pin_state = int(args.state) if args.state else data.get('state', 0)
            
            if pin_number > 0 and db:
                db.log_gpio_event(pin_number, pin_state, event_type='trigger')
                print(f"Logged GPIO event: Pin {pin_number} = {pin_state}")
        
    except Exception as e:
        print(f"Error processing callback: {e}")
        sys.exit(1)