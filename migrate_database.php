<?php
/**
 * Advanced Stats Plugin - Database Migration Script
 * Run this script to update the database schema for existing installations
 * 
 * Usage: php migrate_database.php
 */

$dbPath = '/home/fpp/media/config/plugin.fpp-plugin-AdvancedStats.db';

if (!file_exists($dbPath)) {
    echo "Error: Database not found at $dbPath\n";
    echo "Please install the plugin first.\n";
    exit(1);
}

try {
    $db = new SQLite3($dbPath);
    
    echo "Advanced Stats Plugin - Database Migration\n";
    echo "==========================================\n\n";
    
    $migrationsApplied = 0;
    
    // Migration 1: Add description column to gpio_events
    echo "Checking Migration 1: gpio_events.description column...\n";
    $result = $db->query("PRAGMA table_info(gpio_events)");
    $hasDescription = false;
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        if ($row['name'] === 'description') {
            $hasDescription = true;
            break;
        }
    }
    
    if (!$hasDescription) {
        echo "  Applying: Adding description column to gpio_events...\n";
        $db->exec('ALTER TABLE gpio_events ADD COLUMN description TEXT');
        echo "  ✓ Migration applied successfully\n";
        $migrationsApplied++;
    } else {
        echo "  ✓ Already applied (column exists)\n";
    }
    
    echo "\n";
    
    // Migration 2: Create command_history table if it doesn't exist
    echo "Checking Migration 2: command_history table...\n";
    $result = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='command_history'");
    $hasCommandHistory = $result->fetchArray() !== false;
    
    if (!$hasCommandHistory) {
        echo "  Applying: Creating command_history table...\n";
        $db->exec('
            CREATE TABLE IF NOT EXISTS command_history (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                timestamp INTEGER NOT NULL,
                command TEXT NOT NULL,
                args TEXT,
                multisyncCommand INTEGER DEFAULT 0,
                multisyncHosts TEXT,
                trigger_source TEXT,
                payload_json TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ');
        $db->exec('CREATE INDEX IF NOT EXISTS idx_cmd_timestamp ON command_history(timestamp)');
        $db->exec('CREATE INDEX IF NOT EXISTS idx_cmd_command ON command_history(command)');
        echo "  ✓ Migration applied successfully\n";
        $migrationsApplied++;
    } else {
        echo "  ✓ Already applied (table exists)\n";
    }
    
    echo "\n";
    
    // Migration 3: Create command_preset_history table if it doesn't exist
    echo "Checking Migration 3: command_preset_history table...\n";
    $result = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='command_preset_history'");
    $hasPresetHistory = $result->fetchArray() !== false;
    
    if (!$hasPresetHistory) {
        echo "  Applying: Creating command_preset_history table...\n";
        $db->exec('
            CREATE TABLE IF NOT EXISTS command_preset_history (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                timestamp INTEGER NOT NULL,
                preset_name TEXT NOT NULL,
                command_count INTEGER DEFAULT 0,
                trigger_source TEXT,
                payload_json TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ');
        $db->exec('CREATE INDEX IF NOT EXISTS idx_preset_timestamp ON command_preset_history(timestamp)');
        $db->exec('CREATE INDEX IF NOT EXISTS idx_preset_name ON command_preset_history(preset_name)');
        echo "  ✓ Migration applied successfully\n";
        $migrationsApplied++;
    } else {
        echo "  ✓ Already applied (table exists)\n";
    }
    
    echo "\n";
    
    // Future migrations can be added here following the same pattern
    // Example:
    // echo "Checking Migration 2: new_feature...\n";
    // ... check and apply migration ...
    
    echo "==========================================\n";
    echo "Migration Summary:\n";
    echo "  - Migrations applied: $migrationsApplied\n";
    echo "  - Database schema is up to date\n";
    
    if ($migrationsApplied > 0) {
        echo "\nDatabase migration completed successfully!\n";
        echo "The plugin should now work correctly with the latest features.\n";
    } else {
        echo "\nNo migrations needed - database is already up to date.\n";
    }
    
    $db->close();
    
} catch (Exception $e) {
    echo "Error during migration: " . $e->getMessage() . "\n";
    exit(1);
}
?>
