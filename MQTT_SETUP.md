# MQTT Event Tracking Setup Guide

The Advanced Stats plugin uses FPP's built-in MQTT broker to capture events in real-time.

## Why MQTT Instead of Callbacks?

### Callback Limitations:
- ❌ Requires manual registration for each event type
- ❌ Not all FPP events are reliably delivered via callbacks
- ❌ GPIO events require explicit hooks
- ❌ Dependent on FPP calling the script

### MQTT Advantages:
- ✅ **Real-time event capture** - Events published immediately
- ✅ **More reliable** - FPP automatically publishes all events
- ✅ **GPIO monitoring** - Captures ALL GPIO state changes instantly
- ✅ **No registration needed** - Just subscribe to topics
- ✅ **Runs continuously** - Background service monitoring all events

## Setup Instructions

### 1. Enable MQTT in FPP

1. Open FPP web interface
2. Navigate to: **Content Setup** → **System Settings**
3. Scroll down to **Services** section
4. Find **Enable Local MQTT Broker**
5. Check the checkbox to enable it
6. Click **Save** at the bottom of the page
7. FPP will automatically start the MQTT broker on `localhost:1883`

### 2. Verify MQTT is Running

```bash
# Check if MQTT broker is running
systemctl status mosquitto

# Test MQTT connection
mosquitto_sub -h localhost -t '#' -v -C 1
```

### 3. Plugin Automatically Starts

Once MQTT is enabled, the plugin's MQTT listener will automatically:
- Start when FPP starts (via `scripts/postStart.sh`)
- Subscribe to all relevant topics
- Begin logging events to the database
- Stop gracefully when FPP stops (via `scripts/preStop.sh`)

## MQTT Topics Monitored

The plugin subscribes to these FPP MQTT topics:

| Topic Pattern | Description | Example |
|---------------|-------------|---------|
| `falcon/player/+/event/sequence/#` | Sequence start/stop events | `falcon/player/FPP/event/sequence/start` |
| `falcon/player/+/event/playlist/#` | Playlist start/stop events | `falcon/player/FPP/event/playlist/start` |
| `falcon/player/+/gpio/#` | GPIO pin state changes | `falcon/player/FPP/gpio/18` |
| `falcon/player/+/status` | FPP status updates | `falcon/player/FPP/status` |

## Manual Control

### Check if Listener is Running

```bash
# Check for running process
pgrep -f mqtt_listener.py

# Or use ps
ps aux | grep mqtt_listener.py
```

### View Live Logs

```bash
# Tail the log file to see events as they happen
tail -f /home/fpp/media/logs/fpp-plugin-AdvancedStats.log
```

### Start Listener Manually

```bash
cd /home/fpp/media/plugins/fpp-plugin-AdvancedStats
python3 mqtt_listener.py
```

Press `Ctrl+C` to stop.

### Stop Listener

```bash
pkill -f mqtt_listener.py
```

### Restart Listener

```bash
pkill -f mqtt_listener.py && sleep 2 && cd /home/fpp/media/plugins/fpp-plugin-AdvancedStats && nohup python3 mqtt_listener.py >> /home/fpp/media/logs/fpp-plugin-AdvancedStats.log 2>&1 &
```

## Testing Event Capture

### Method 1: Monitor Raw MQTT Messages

```bash
# Subscribe to all FPP topics
mosquitto_sub -h localhost -t 'falcon/player/+/#' -v

# Then play a sequence or trigger a GPIO in FPP
```

### Method 2: Check Database

```bash
# View recent GPIO events
sqlite3 /home/fpp/media/config/plugin.fpp-plugin-AdvancedStats.db "SELECT * FROM gpio_events ORDER BY timestamp DESC LIMIT 10;"

# View recent sequences
sqlite3 /home/fpp/media/config/plugin.fpp-plugin-AdvancedStats.db "SELECT * FROM sequence_history ORDER BY timestamp DESC LIMIT 10;"
```

### Method 3: Use Dashboard

1. Navigate to: **Status/Control** → **Advanced Stats Dashboard**
2. The dashboard will show recent events
3. Play a sequence or trigger GPIO to see live updates

## Troubleshooting

### MQTT Listener Not Starting

**Problem:** `pgrep -f mqtt_listener.py` returns nothing

**Solutions:**
1. Check if MQTT broker is enabled in FPP settings
2. Verify MQTT broker is running: `systemctl status mosquitto`
3. Check logs: `tail -f /home/fpp/media/logs/fpp-plugin-AdvancedStats.log`
4. Start manually to see error messages: `python3 /home/fpp/media/plugins/fpp-plugin-AdvancedStats/mqtt_listener.py`

### Connection Refused Error

**Problem:** `Failed to connect to MQTT broker: [Errno 111] Connection refused`

**Solution:** Enable MQTT in FPP settings (see Step 1 above)

### No Events Captured

**Problem:** Dashboard shows no data

**Checklist:**
1. ✅ MQTT enabled in FPP settings
2. ✅ Listener is running: `pgrep -f mqtt_listener.py`
3. ✅ Actually triggered events (played sequence, changed GPIO)
4. ✅ Database exists: `ls -lh /home/fpp/media/config/plugin.fpp-plugin-AdvancedStats.db`

### Missing Python Library

**Problem:** `ModuleNotFoundError: No module named 'paho'`

**Solution:**
```bash
apt-get update && apt-get install -y python3-paho-mqtt
```

## Event Data Examples

### Sequence Start Event

**Topic:** `falcon/player/FPP/event/sequence/start`

**Payload:**
```json
{
  "sequence": "MySequence",
  "playlist": "MainShow",
  "action": "start"
}
```

### GPIO Event

**Topic:** `falcon/player/FPP/gpio/18`

**Payload:**
```json
{
  "state": 1,
  "value": 1
}
```

## Comparison: Callbacks vs MQTT

| Feature | Callbacks | MQTT |
|---------|-----------|------|
| Setup Complexity | Manual registration | Enable in settings |
| Event Coverage | Limited | Comprehensive |
| GPIO Monitoring | Requires hooks | Automatic |
| Reliability | Depends on FPP calling script | FPP publishes automatically |
| Real-time | Delayed | Instant |
| Background Service | No | Yes |
| **Recommended** | ❌ Legacy | ✅ **Use This!** |

## Additional Resources

- [FPP MQTT Documentation](https://github.com/FalconChristmas/fpp/blob/master/docs/MQTT.md)
- [Mosquitto Documentation](https://mosquitto.org/documentation/)
- [Paho MQTT Python Client](https://www.eclipse.org/paho/index.php?page=clients/python/index.php)

---

**Note:** The plugin maintains both callback (`callbacks.py`) and MQTT (`mqtt_listener.py`) implementations for compatibility, but MQTT is the recommended method for event capture.
