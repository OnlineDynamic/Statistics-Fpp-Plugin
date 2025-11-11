# Enhancement Backlog for Advanced Statistics Plugin

## ✅ Completed Features

* ✅ Tracking of GPIO events (infrastructure in place, needs hardware testing)
* ✅ Dashboards (basic dashboard with real-time stats implemented)
* ✅ Fix playlist and sequence counting (now differentiated via MQTT)
* ✅ Fix sequence duration display - Duration captured from MQTT and properly formatted
* ✅ Add sequence stop event tracking - Both start/stop events logged with duration
* ✅ Implement auto-start for MQTT listener - postStart.sh/preStop.sh implemented
* ✅ Add link buttons to settings/help pages - Navigation in top-right corner
* ✅ Add pagination to history tables - API and UI support for paging through large datasets

## 🎯 Implementation Priority Order

### Phase 1: Core Functionality Fixes (Foundation) ✅ COMPLETE

1. ✅ **Fix sequence duration display (NaNh NaNm)** - Critical for basic functionality
2. ✅ **Add sequence stop event tracking** - Foundation for accurate duration calculation
3. ⏸️ **Test and fix GPIO event capture** - Validate existing infrastructure works (needs hardware)
4. ✅ **Implement auto-start for MQTT listener** - Ensure reliability on boot/restart

### Phase 2: Immediate UX Improvements (Quick Wins) ✅ COMPLETE

5. ✅ **Add link buttons to settings/help pages** - Better navigation
6. ✅ **Add pagination to history tables** - Handle large datasets
7. ✅ **Add date range picker and filters** - Essential for historical analysis

### Phase 3: Data Management (User Control) 🔄 IN PROGRESS

8. ✅ **Add backup and restore functionality** - Data protection
9. ✅ **Implement data export (CSV/JSON)** - User data ownership
10. ⏳ **Auto-archive old data** - Performance and maintenance

### Phase 4: Enhanced Analytics (Value-Add)

11. **Add time-series graphs** - Visualize trends over time
12. **Create top sequences/playlists widget** - Popularity insights
13. **Add heat map visualization** - Pattern discovery
14. **Sequence interruption detection** - Reliability monitoring

### Phase 5: Advanced Features (Power User)

15. **Search/filter functionality** - Better data discovery
16. **Live event stream viewer** - Real-time debugging
17. **Customizable dashboard widgets** - Personalization
18. **Alert thresholds and notifications** - Proactive monitoring

### Phase 6: Integrations & Scaling (Expansion)

19. **Prometheus metrics endpoint** - Professional monitoring
20. **Multi-FPP instance aggregation** - Enterprise features
21. **Webhook notifications** - External integrations

## 📋 High Priority Enhancements

* Add link buttons to settings and help pages on main page - in top right (similar to background music player plugin approach)
* For GPIO triggers capture the human readable function the trigger is doing (held in GPIO input settings as 'Description')
* Add ability to backup and restore DB of event history
* Add graphs to show events over time - with period filters (daily, weekly, monthly, yearly)
* Add ability to drill into a tabular breakdown of numbers - to show event details
* Add pagination to sequence/playlist/GPIO history tables (currently limited to 10 rows)

## 🎯 New Feature Ideas

### Analytics & Visualization

* Heat map showing busiest times/days for sequences/playlists
* Comparison charts (year-over-year, month-over-month)
* Top 10 most played sequences/playlists
* Average show duration statistics
* Sequence/playlist popularity rankings
* Performance metrics (sequence start delays, MQTT latency tracking)

### Event Tracking Enhancements

* Track sequence stop events (currently only tracking starts)
* Calculate actual vs expected sequence duration (detect interruptions)
* Track sequence playback errors/failures
* Monitor FPPD service restarts and uptime
* Track remote trigger events (API calls, scheduler events)
* Log weather conditions at event times (via external API integration)

### GPIO Advanced Features

* GPIO trigger frequency analysis
* GPIO debounce violation tracking
* Track GPIO state change patterns (e.g., multiple rapid presses)
* Alert on unusual GPIO behavior (stuck high/low)
* GPIO utilization heatmap by time of day

### Reporting & Exports

* Email summary reports (daily/weekly digest)
* Export data to CSV/JSON for external analysis
* Generate PDF performance reports
* Integration with external analytics platforms (Google Analytics, etc.)
* Webhook notifications for specific events

### Real-time Monitoring

* Live event stream viewer (real-time MQTT message display)
* Alert thresholds (notify if X events occur in Y time)
* System health dashboard (CPU, memory, disk, network stats)
* MQTT connection status indicator
* Database size monitoring and auto-cleanup

### User Experience

* Date range picker for historical data queries
* Search/filter functionality for event tables
* Dark mode toggle
* Customizable dashboard widgets (drag-and-drop arrangement)
* Event annotations (add notes to specific timestamps)
* Bookmarking/favorites for frequently viewed data

### Playlist & Sequence Intelligence

* Predict next sequence based on schedule/patterns
* Track playlist loop counts
* Identify sequences that frequently error
* Monitor sequence version changes (detect updates)
* Track which sequences run together (correlation analysis)

### Integration Ideas

* IFTTT integration (trigger external actions on events)
* Slack/Discord/Telegram notifications
* Home Assistant integration
* Grafana dashboard export
* Prometheus metrics endpoint

### Advanced Analytics

* Machine learning predictions (estimate power consumption, detect anomalies)
* Seasonal trend analysis
* A/B testing support (compare different show configurations)
* Audience engagement metrics (if integrated with viewer counters)
* Cost analysis (calculate power costs based on runtime)

### Maintenance & Admin

* Auto-archive old data (configurable retention policy)
* Database optimization scheduler
* Import historical data from FPP logs
* Multi-FPP instance aggregation (track multiple controllers)
* Sync data between FPP instances
* UI settings option to clear db data

## 🐛 Known Issues to Fix

* Sequence duration not displaying correctly (NaNh NaNm)
* Need to test GPIO tracking with actual hardware
* MQTT listener service auto-start on boot (add to plugin lifecycle)
* Error handling for database corruption scenarios
* On Main page...  "Recent Sequence History (Last 50)" - change to last 5 and implement functionality - nothing currently displays
* On Main page...  "Recent GPIO Events (Last 50)" - change to last 5 and implement functionality - nothing currently displays

## 🔧 Technical Debt

* Add unit tests for Python modules
* Add API rate limiting
* Implement proper logging levels (debug/info/warn/error)
* Add database migration system for schema changes
* Document MQTT topic structure and expected payloads
* Add configuration file for customizable settings (MQTT host, DB path, etc.)
