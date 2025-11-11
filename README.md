# Advanced Stats Plugin for FPP

This is the home of the Advanced Stats FPP Plugin Repo

The purpose of this FPP plugin is to allow the end user to ......


## Requirements

- **FPP Version**: 9.0 or higher
-


## Features

- �🔌 **GPIO Integration** -
- ⚙️ **REST API** - Full programmatic control via HTTP endpoints

## Technical Requirements

### 

### Automatic Update Notifications

The plugin checks for updates automatically when internet is available:

- 🔍 Checks GitHub repository hourly for new versions
- 📢 Shows notification banner when updates are available
- 🌿 **Branch-aware**: Automatically checks the correct branch based on your FPP version (defined in pluginInfo.json)
- ⚙️ Uses FPP's native version management (git commits)
- 🌐 Only checks when system has internet connectivity
- ❌ Can be dismissed if you prefer to update later
- 🔄 Update via FPP's Plugin Manager

**How it works:**

- Reads `pluginInfo.json` to determine which branch to check based on FPP version
- Compares local git commit with remote repository
- Shows notification with commit count and latest changes
- Provides direct link to Plugin Manager for one-click updates

## Quick Start

### 1. Installation

Plugin automatically installs via FPP plugin manager.

### 2. Configuration

1. Navigate to **Content Setup**
2.
3. Click **Save Settings**

### 3. Basic Usage


### 4. GPIO Setup (Optional)

The

## API Endpoints

All endpoints available at: `/api/plugin/fpp-plugin-BackgroundMusic/`

### Status

```bash
GET /api/plugin/fpp-plugin-BackgroundMusic/status
```

Returns current plugin state, FPP playlist, brightness, track progress, and configuration.

### Control Background Music

```bash
POST /api/plugin/fpp-plugin-BackgroundMusic/start-background
POST /api/plugin/fpp-plugin-BackgroundMusic/stop-background
```

### Trigger Show

```bash
POST /api/plugin/fpp-plugin-BackgroundMusic/start-show
```

### Set Volume

```bash
POST /api/plugin/fpp-plugin-BackgroundMusic/set-volume
Content-Type: application/json
{"volume": 70}
```

### Playlist Details

```bash
GET /api/plugin/fpp-plugin-BackgroundMusic/playlist-details
```

Returns track list with durations and metadata for the configured background music playlist.

### Save Settings

```bash
POST /api/plugin/fpp-plugin-BackgroundMusic/save-settings
Content-Type: application/json
{
  "BackgroundMusicPlaylist": "Background music only",
  "ShowPlaylist": "Main Show",
  "BackgroundMusicVolume": 70,
  "ShowPlaylistVolume": 100,
  "PostShowBackgroundVolume": 70,
  "FadeTime": 5,
  "BlackoutTime": 2,
  "ShuffleMusic": 1,
  "ReturnToPreShow": 1,
  "PostShowDelay": 5
}
```


## Support & Development

**Plugin Developer:** Stuart Ledingham of Dynamic Pixels

**Resources:**

- [GitHub Repository](https://github.com/OnlineDynamic/BackgroundMusicFPP-Plugin)
- [Bug Reports & Feature Requests](https://github.com/OnlineDynamic/BackgroundMusicFPP-Plugin/issues)

## License

This project is licensed under the terms specified in the LICENSE file.
