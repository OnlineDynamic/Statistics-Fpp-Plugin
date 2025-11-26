# Database Schema Changes

This document tracks schema changes made to the Advanced Stats Plugin database.

## Current Schema Version: 1.1

### Database Location
`/home/fpp/media/config/plugin.fpp-plugin-AdvancedStats.db`

## Tables

### gpio_events
Stores GPIO button press and state change events.

| Column | Type | Description |
|--------|------|-------------|
| id | INTEGER PRIMARY KEY | Auto-incrementing ID |
| timestamp | INTEGER | Unix timestamp of event |
| pin_number | INTEGER | GPIO pin number (e.g., "1-0038-0") |
| pin_state | INTEGER | Pin state (0=LOW, 1=HIGH) |
| event_type | TEXT | Event type (rising, falling, event) |
| description | TEXT | GPIO pin description from FPP config |
| created_at | DATETIME | Record creation timestamp |

**Indexes:**
- `idx_gpio_timestamp` on `timestamp`
- `idx_gpio_pin` on `pin_number`

### sequence_history
Tracks sequence playback events.

| Column | Type | Description |
|--------|------|-------------|
| id | INTEGER PRIMARY KEY | Auto-incrementing ID |
| timestamp | INTEGER | Unix timestamp of event |
| sequence_name | TEXT | Name of the sequence |
| playlist_name | TEXT | Parent playlist (if any) |
| event_type | TEXT | Event type (start, stop) |
| duration | INTEGER | Duration in seconds (for stop events) |
| trigger_source | TEXT | What triggered the sequence |
| created_at | DATETIME | Record creation timestamp |

**Indexes:**
- `idx_seq_timestamp` on `timestamp`
- `idx_seq_name` on `sequence_name`

### playlist_history
Tracks playlist start/stop events.

| Column | Type | Description |
|--------|------|-------------|
| id | INTEGER PRIMARY KEY | Auto-incrementing ID |
| timestamp | INTEGER | Unix timestamp of event |
| playlist_name | TEXT | Name of the playlist |
| event_type | TEXT | Event type (start, stop) |
| trigger_source | TEXT | What triggered the playlist |
| created_at | DATETIME | Record creation timestamp |

**Indexes:**
- `idx_playlist_timestamp` on `timestamp`
- `idx_playlist_name` on `playlist_name`

### daily_stats
Aggregated daily statistics for quick access.

| Column | Type | Description |
|--------|------|-------------|
| id | INTEGER PRIMARY KEY | Auto-incrementing ID |
| date | TEXT UNIQUE | Date in YYYY-MM-DD format |
| gpio_events_count | INTEGER | Number of GPIO events |
| sequences_played | INTEGER | Number of sequences played |
| playlists_started | INTEGER | Number of playlists started |
| total_sequence_duration | INTEGER | Total sequence runtime in seconds |
| updated_at | DATETIME | Last update timestamp |

## Schema Change History

### Version 1.1 (November 2025)
**Changes:**
- Added `description` column to `gpio_events` table
  - Stores custom GPIO pin descriptions from FPP configuration
  - Allows for human-readable pin identification in the UI
  - Nullable field (TEXT type)

**Migration:**
- Automatic migration added to install script
- Standalone migration script: `migrate_database.php`
- Existing installations automatically upgraded on plugin update

### Version 1.0 (Initial Release)
**Initial Schema:**
- Created `gpio_events` table with basic fields
- Created `sequence_history` table
- Created `playlist_history` table
- Created `daily_stats` table
- Added all indexes

## Running Migrations

### Automatic Migration (Recommended)
Migrations run automatically when you:
1. Install the plugin for the first time
2. Update the plugin via FPP Plugin Manager
3. Run the install script: `/home/fpp/media/plugins/fpp-plugin-AdvancedStats/scripts/fpp_install.sh`

### Manual Migration
If you need to run migrations manually:

```bash
cd /home/fpp/media/plugins/fpp-plugin-AdvancedStats
php migrate_database.php
```

The migration script will:
- Check which migrations have been applied
- Apply any missing migrations
- Report the results
- Skip migrations that are already applied (safe to run multiple times)

## Checking Your Schema Version

To verify your database has all the latest columns:

```bash
sqlite3 /home/fpp/media/config/plugin.fpp-plugin-AdvancedStats.db "PRAGMA table_info(gpio_events);"
```

You should see the `description` column (column index 5) in the output.

## Backward Compatibility

All schema changes are designed to be backward compatible:
- New columns are nullable or have default values
- Existing queries continue to work
- Old data is preserved during migrations
- No data loss during upgrades

## Future Schema Changes

When adding new schema changes:
1. Update `init_database.php` with the new schema
2. Add migration check to `migrate_database.php`
3. Test migration on a database without the changes
4. Update this document with the new version
5. Document the changes in CHANGELOG.md
