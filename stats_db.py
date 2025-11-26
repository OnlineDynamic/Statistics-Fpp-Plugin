#!/usr/bin/env python3
"""
Advanced Stats Plugin - Database Helper
Handles all database operations for tracking GPIO events and sequence history
"""

import sqlite3
import time
from datetime import datetime

DB_PATH = '/home/fpp/media/config/plugin.fpp-plugin-AdvancedStats.db'

class StatsDatabase:
    def __init__(self):
        self.db_path = DB_PATH
    
    def get_connection(self):
        """Get a database connection"""
        conn = sqlite3.connect(self.db_path)
        conn.row_factory = sqlite3.Row
        return conn
    
    def log_gpio_event(self, pin_number, pin_state, event_type='', description=''):
        """
        Log a GPIO event to the database
        
        Args:
            pin_number: GPIO pin number
            pin_state: State of the pin (0 or 1)
            event_type: Type of event (e.g., 'trigger', 'input')
            description: Optional description
        """
        try:
            conn = self.get_connection()
            cursor = conn.cursor()
            
            timestamp = int(time.time())
            
            cursor.execute('''
                INSERT INTO gpio_events 
                (timestamp, pin_number, pin_state, event_type, description)
                VALUES (?, ?, ?, ?, ?)
            ''', (timestamp, pin_number, pin_state, event_type, description))
            
            conn.commit()
            conn.close()
            
            # Update daily stats
            self.update_daily_stats('gpio_events_count')
            
            return True
        except Exception as e:
            print(f"Error logging GPIO event: {e}")
            return False
    
    def log_sequence_event(self, sequence_name, event_type, playlist_name='', 
                          duration=0, trigger_source=''):
        """
        Log a sequence start/stop event
        
        Args:
            sequence_name: Name of the sequence
            event_type: 'start' or 'stop'
            playlist_name: Name of the playlist (if part of one)
            duration: Duration in seconds (for stop events)
            trigger_source: What triggered the sequence
        """
        try:
            conn = self.get_connection()
            cursor = conn.cursor()
            
            timestamp = int(time.time())
            
            cursor.execute('''
                INSERT INTO sequence_history 
                (timestamp, sequence_name, playlist_name, event_type, duration, trigger_source)
                VALUES (?, ?, ?, ?, ?, ?)
            ''', (timestamp, sequence_name, playlist_name, event_type, duration, trigger_source))
            
            conn.commit()
            conn.close()
            
            # Update daily stats
            if event_type == 'start':
                self.update_daily_stats('sequences_played')
            if duration > 0:
                self.update_daily_stats('total_sequence_duration', duration)
            
            return True
        except Exception as e:
            print(f"Error logging sequence event: {e}")
            return False
    
    def log_playlist_event(self, playlist_name, event_type, trigger_source=''):
        """
        Log a playlist start/stop event
        
        Args:
            playlist_name: Name of the playlist
            event_type: 'start' or 'stop'
            trigger_source: What triggered the playlist
        """
        try:
            conn = self.get_connection()
            cursor = conn.cursor()
            
            timestamp = int(time.time())
            
            cursor.execute('''
                INSERT INTO playlist_history 
                (timestamp, playlist_name, event_type, trigger_source)
                VALUES (?, ?, ?, ?)
            ''', (timestamp, playlist_name, event_type, trigger_source))
            
            conn.commit()
            conn.close()
            
            # Update daily stats
            if event_type == 'start':
                self.update_daily_stats('playlists_started')
            
            return True
        except Exception as e:
            print(f"Error logging playlist event: {e}")
            return False
    
    def update_daily_stats(self, stat_name, increment=1):
        """Update daily statistics"""
        try:
            conn = self.get_connection()
            cursor = conn.cursor()
            
            today = datetime.now().strftime('%Y-%m-%d')
            
            # Check if record exists for today
            cursor.execute('SELECT id FROM daily_stats WHERE date = ?', (today,))
            row = cursor.fetchone()
            
            if row:
                # Update existing record
                cursor.execute(f'''
                    UPDATE daily_stats 
                    SET {stat_name} = {stat_name} + ?,
                        updated_at = CURRENT_TIMESTAMP
                    WHERE date = ?
                ''', (increment, today))
            else:
                # Insert new record
                cursor.execute(f'''
                    INSERT INTO daily_stats (date, {stat_name})
                    VALUES (?, ?)
                ''', (today, increment))
            
            conn.commit()
            conn.close()
            return True
        except Exception as e:
            print(f"Error updating daily stats: {e}")
            return False
    
    def get_recent_gpio_events(self, limit=100, pin_number=None):
        """Get recent GPIO events"""
        try:
            conn = self.get_connection()
            cursor = conn.cursor()
            
            if pin_number is not None:
                cursor.execute('''
                    SELECT * FROM gpio_events 
                    WHERE pin_number = ?
                    ORDER BY timestamp DESC 
                    LIMIT ?
                ''', (pin_number, limit))
            else:
                cursor.execute('''
                    SELECT * FROM gpio_events 
                    ORDER BY timestamp DESC 
                    LIMIT ?
                ''', (limit,))
            
            events = [dict(row) for row in cursor.fetchall()]
            conn.close()
            return events
        except Exception as e:
            print(f"Error getting GPIO events: {e}")
            return []
    
    def get_recent_sequences(self, limit=100):
        """Get recent sequence history"""
        try:
            conn = self.get_connection()
            cursor = conn.cursor()
            
            cursor.execute('''
                SELECT * FROM sequence_history 
                ORDER BY timestamp DESC 
                LIMIT ?
            ''', (limit,))
            
            sequences = [dict(row) for row in cursor.fetchall()]
            conn.close()
            return sequences
        except Exception as e:
            print(f"Error getting sequence history: {e}")
            return []
    
    def get_daily_stats(self, days=30):
        """Get daily statistics for the last N days"""
        try:
            conn = self.get_connection()
            cursor = conn.cursor()
            
            cursor.execute('''
                SELECT * FROM daily_stats 
                ORDER BY date DESC 
                LIMIT ?
            ''', (days,))
            
            stats = [dict(row) for row in cursor.fetchall()]
            conn.close()
            return stats
        except Exception as e:
            print(f"Error getting daily stats: {e}")
            return []
    
    def log_command_event(self, command, args='', multisync_command=0, multisync_hosts='', 
                         trigger_source='', payload_json=''):
        """
        Log a command execution event
        
        Args:
            command: Command name/type
            args: Command arguments (JSON string or text)
            multisync_command: Whether this is a multisync command (0 or 1)
            multisync_hosts: Comma-separated list of target hosts
            trigger_source: What triggered the command
            payload_json: Full JSON payload for reference
        """
        try:
            conn = self.get_connection()
            cursor = conn.cursor()
            
            timestamp = int(time.time())
            
            cursor.execute('''
                INSERT INTO command_history 
                (timestamp, command, args, multisyncCommand, multisyncHosts, trigger_source, payload_json)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ''', (timestamp, command, args, multisync_command, multisync_hosts, trigger_source, payload_json))
            
            conn.commit()
            conn.close()
            
            return True
        except Exception as e:
            print(f"Error logging command event: {e}")
            return False
    
    def log_command_preset_event(self, preset_name, command_count=0, trigger_source='', payload_json=''):
        """
        Log a command preset execution event
        
        Args:
            preset_name: Name of the command preset
            command_count: Number of commands in the preset
            trigger_source: What triggered the preset
            payload_json: Full JSON payload for reference
        """
        try:
            conn = self.get_connection()
            cursor = conn.cursor()
            
            timestamp = int(time.time())
            
            cursor.execute('''
                INSERT INTO command_preset_history 
                (timestamp, preset_name, command_count, trigger_source, payload_json)
                VALUES (?, ?, ?, ?, ?)
            ''', (timestamp, preset_name, command_count, trigger_source, payload_json))
            
            conn.commit()
            conn.close()
            
            return True
        except Exception as e:
            print(f"Error logging command preset event: {e}")
            return False
