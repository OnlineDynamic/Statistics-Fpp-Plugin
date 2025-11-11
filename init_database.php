<?php
// Advanced Stats Plugin - Database Initialization
// Creates SQLite database for tracking GPIO events and sequence history

$dbPath = '/home/fpp/media/config/plugin.fpp-plugin-AdvancedStats.db';

try {
    // Create or open the database
    $db = new SQLite3($dbPath);
    
    // Set database to be writable by FPP user
    chmod($dbPath, 0666);
    
    // Create GPIO events table
    $db->exec('
        CREATE TABLE IF NOT EXISTS gpio_events (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            timestamp INTEGER NOT NULL,
            pin_number INTEGER NOT NULL,
            pin_state INTEGER NOT NULL,
            event_type TEXT,
            description TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ');
    
    // Create index on timestamp for faster queries
    $db->exec('CREATE INDEX IF NOT EXISTS idx_gpio_timestamp ON gpio_events(timestamp)');
    $db->exec('CREATE INDEX IF NOT EXISTS idx_gpio_pin ON gpio_events(pin_number)');
    
    // Create sequence history table
    $db->exec('
        CREATE TABLE IF NOT EXISTS sequence_history (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            timestamp INTEGER NOT NULL,
            sequence_name TEXT NOT NULL,
            playlist_name TEXT,
            event_type TEXT NOT NULL,
            duration INTEGER,
            trigger_source TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ');
    
    // Create index on timestamp and sequence name
    $db->exec('CREATE INDEX IF NOT EXISTS idx_seq_timestamp ON sequence_history(timestamp)');
    $db->exec('CREATE INDEX IF NOT EXISTS idx_seq_name ON sequence_history(sequence_name)');
    
    // Create playlist history table
    $db->exec('
        CREATE TABLE IF NOT EXISTS playlist_history (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            timestamp INTEGER NOT NULL,
            playlist_name TEXT NOT NULL,
            event_type TEXT NOT NULL,
            trigger_source TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ');
    
    // Create index on timestamp and playlist name
    $db->exec('CREATE INDEX IF NOT EXISTS idx_playlist_timestamp ON playlist_history(timestamp)');
    $db->exec('CREATE INDEX IF NOT EXISTS idx_playlist_name ON playlist_history(playlist_name)');
    
    // Create statistics summary table (for quick aggregations)
    $db->exec('
        CREATE TABLE IF NOT EXISTS daily_stats (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            date TEXT NOT NULL UNIQUE,
            gpio_events_count INTEGER DEFAULT 0,
            sequences_played INTEGER DEFAULT 0,
            playlists_started INTEGER DEFAULT 0,
            total_sequence_duration INTEGER DEFAULT 0,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ');
    
    echo "Database initialized successfully at: $dbPath\n";
    echo "Tables created: gpio_events, sequence_history, playlist_history, daily_stats\n";
    
    $db->close();
    
} catch (Exception $e) {
    echo "Error initializing database: " . $e->getMessage() . "\n";
    exit(1);
}
?>
