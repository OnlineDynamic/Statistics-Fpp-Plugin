# Header Indicator

## Overview
The header indicator displays a small icon in the top-right corner of all FPP pages when the plugin is active. It shows:
- A visual indicator when stats are being tracked
- Real-time status information
- Clicking the indicator navigates to the Advanced Stats Dashboard page

## Implementation

The header indicator is implemented in `header-indicator.js` which is automatically injected into all FPP pages via the `plugin_setup.php` hook.

### Files Involved:
- `header-indicator.js` - The indicator JavaScript code
- `plugin_setup.php` - Registers the header hook with FPP

### How It Works:

1. **Automatic Injection**: The `plugin_setup.php` file registers a header hook with FPP that includes `header-indicator.js` on every page
2. **Status Polling**: The script periodically polls the plugin's API endpoint to check status
3. **Visual Feedback**: Updates the indicator icon based on current plugin state
4. **Navigation**: Clicking the indicator takes you to the Advanced Stats Dashboard

## Technical Details

The script is automatically included on all FPP pages and will show the indicator when appropriate.

### API Endpoint Used:
```
GET /api/plugin/fpp-plugin-AdvancedStats/status
```

The indicator checks this endpoint to determine the current state of the plugin.
