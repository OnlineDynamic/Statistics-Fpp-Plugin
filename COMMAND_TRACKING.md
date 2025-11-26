# Command and Command Preset Tracking

The Advanced Stats Plugin now tracks FPP Commands and Command Presets execution via MQTT.

## Overview

When FPP executes a command or command preset, it publishes detailed information to MQTT topics. This plugin captures those events and stores them in the database with full JSON payloads for analysis and pivoting.

## MQTT Topics

The plugin listens to the following MQTT topics for command tracking:

### Individual Commands
**Topic:** `falcon/player/{hostname}/command/{command_name}`

**Expected Payload Structure:**
```json
{
  "command": "Start Playlist",
  "args": {
    "name": "MyPlaylist",
    "repeat": true,
    "position": 0
  },
  "multisyncCommand": false,
  "multisyncHosts": [],
  "trigger": "API",
  "source": "Web Interface"
}
```

### Command Presets
**Topic:** `falcon/player/{hostname}/command_preset/{preset_name}`

**Expected Payload Structure:**
```json
{
  "preset_name": "Show Startup Sequence",
  "name": "Show Startup Sequence",
  "command_count": 5,
  "commands": [
    {"command": "...", "args": {...}},
    {"command": "...", "args": {...}}
  ],
  "trigger": "Schedule",
  "source": "FPP Scheduler"
}
```

## Database Schema

### command_history Table

Stores individual command executions.

| Column | Type | Description |
|--------|------|-------------|
| id | INTEGER PRIMARY KEY | Auto-incrementing ID |
| timestamp | INTEGER | Unix timestamp of execution |
| command | TEXT | Command name/type |
| args | TEXT | Command arguments (JSON string) |
| multisyncCommand | INTEGER | 1 if multisync, 0 otherwise |
| multisyncHosts | TEXT | Comma-separated target hosts |
| trigger_source | TEXT | What triggered the command |
| payload_json | TEXT | Full JSON payload for reference |
| created_at | DATETIME | Record creation timestamp |

**Indexes:**
- `idx_cmd_timestamp` on `timestamp`
- `idx_cmd_command` on `command`

### command_preset_history Table

Stores command preset executions.

| Column | Type | Description |
|--------|------|-------------|
| id | INTEGER PRIMARY KEY | Auto-incrementing ID |
| timestamp | INTEGER | Unix timestamp of execution |
| preset_name | TEXT | Name of the preset |
| command_count | INTEGER | Number of commands in preset |
| trigger_source | TEXT | What triggered the preset |
| payload_json | TEXT | Full JSON payload for reference |
| created_at | DATETIME | Record creation timestamp |

**Indexes:**
- `idx_preset_timestamp` on `timestamp`
- `idx_preset_name` on `preset_name`

## API Endpoints

### Get Command History

**GET** `/api/plugin/fpp-plugin-AdvancedStats/command-history`

Query Parameters:
- `limit` - Number of records (default: 50)
- `offset` - Pagination offset (default: 0)
- `search` - Filter by command, args, or trigger (optional)

Example:
```bash
curl "http://fpp-ip/api/plugin/fpp-plugin-AdvancedStats/command-history?limit=10&search=playlist"
```

Response:
```json
{
  "success": true,
  "commands": [
    {
      "id": 1,
      "timestamp": 1732634400,
      "command": "Start Playlist",
      "args": "{\"name\":\"Christmas\"}",
      "multisyncCommand": 0,
      "multisyncHosts": "",
      "trigger_source": "API",
      "payload_json": "{...}",
      "created_at": "2025-11-26 12:00:00"
    }
  ],
  "total": 1,
  "limit": 10,
  "offset": 0
}
```

### Get Command Preset History

**GET** `/api/plugin/fpp-plugin-AdvancedStats/command-preset-history`

Query Parameters:
- `limit` - Number of records (default: 50)
- `offset` - Pagination offset (default: 0)
- `search` - Filter by preset name or trigger (optional)

Example:
```bash
curl "http://fpp-ip/api/plugin/fpp-plugin-AdvancedStats/command-preset-history?limit=10"
```

Response:
```json
{
  "success": true,
  "presets": [
    {
      "id": 1,
      "timestamp": 1732634400,
      "preset_name": "Show Startup",
      "command_count": 5,
      "trigger_source": "Schedule",
      "payload_json": "{...}",
      "created_at": "2025-11-26 12:00:00"
    }
  ],
  "total": 1,
  "limit": 10,
  "offset": 0
}
```

## Data Analysis Capabilities

With full JSON payloads stored, you can pivot and analyze:

### Commands
- Most frequently executed commands
- Commands by trigger source (API, Web, GPIO, Schedule, etc.)
- MultiSync vs local commands
- Command execution patterns over time
- Arguments used with specific commands
- Failed vs successful command executions

### Command Presets
- Most used presets
- Preset complexity (command count)
- Preset trigger sources
- Execution frequency and timing
- Individual commands within presets (from payload_json)

## Testing Command Tracking

### Monitor MQTT Topics

```bash
# Subscribe to all command-related topics
mosquitto_sub -h localhost -u fpp -P falcon -t "falcon/player/+/command/#" -v

# Subscribe to command presets only
mosquitto_sub -h localhost -u fpp -P falcon -t "falcon/player/+/command_preset/#" -v
```

### Check Database

```bash
# View command history
sqlite3 /home/fpp/media/config/plugin.fpp-plugin-AdvancedStats.db \
  "SELECT * FROM command_history ORDER BY timestamp DESC LIMIT 5;"

# View command preset history
sqlite3 /home/fpp/media/config/plugin.fpp-plugin-AdvancedStats.db \
  "SELECT * FROM command_preset_history ORDER BY timestamp DESC LIMIT 5;"

# Get command statistics
sqlite3 /home/fpp/media/config/plugin.fpp-plugin-AdvancedStats.db \
  "SELECT command, COUNT(*) as count FROM command_history GROUP BY command ORDER BY count DESC;"
```

### Trigger Test Commands

From FPP Web Interface:
1. Go to Content Setup → Commands
2. Create and trigger a test command
3. Check the database or API to verify it was logged

From API:
```bash
# Trigger a command via FPP API
curl -X POST "http://fpp-ip/api/command" \
  -H "Content-Type: application/json" \
  -d '{"command":"Test Command","args":{}}'

# Check if it was logged
curl "http://fpp-ip/api/plugin/fpp-plugin-AdvancedStats/command-history?limit=1"
```

## Migration Notes

The command tracking tables are automatically created during:
1. **Fresh installation** - Tables created by `init_database.php`
2. **Plugin upgrade** - Tables added by `migrate_database.php` (Migrations 2 & 3)
3. **Manual migration** - Run `php migrate_database.php` in plugin directory

Check migration status:
```bash
cd /home/fpp/media/plugins/fpp-plugin-AdvancedStats
php migrate_database.php
```

## Future Dashboard Integration

Planned features for the dashboard UI:
- Command history table with search/filter
- Command preset history table
- Top 10 most executed commands
- Command execution time-series graphs
- Command trigger source breakdown
- MultiSync command visualization
- Preset complexity analysis

## Payload JSON Structure

The `payload_json` field stores the complete MQTT message, allowing for:
- Future schema extraction without data loss
- Custom queries against nested JSON data
- Flexible reporting without schema changes
- Debugging and troubleshooting
- Historical analysis of command parameters

Example query using JSON extraction (SQLite 3.38+):
```sql
SELECT 
  command,
  json_extract(payload_json, '$.trigger') as trigger,
  COUNT(*) as count
FROM command_history
GROUP BY command, trigger
ORDER BY count DESC;
```

## Schema Version

Current schema version: **1.2**

Includes:
- command_history table (Migration 2)
- command_preset_history table (Migration 3)
- Full JSON payload storage
- Indexed timestamps and names for fast queries
