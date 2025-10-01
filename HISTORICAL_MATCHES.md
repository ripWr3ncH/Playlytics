# Historical Matches - Data Availability

## Current Data Range
Your KickOff Stats database currently contains matches from **August 15, 2025** onwards.

## Why No Older Matches?
- The Football Data API provides matches based on when they were first synced
- Historical matches before August 15, 2025 were not captured in the initial setup
- The API has rate limits for historical data requests

## How to Get More Historical Data

### Method 1: Sync Recent Historical Matches
```bash
# Sync matches from the last 30 days (default)
php artisan matches:sync-historical

# Sync matches from last 60 days
php artisan matches:sync-historical --days=60

# Sync matches from a specific date range
php artisan matches:sync-historical --from=2025-07-01 --to=2025-08-14
```

### Method 2: Regular Sync Schedule
The auto-updater already handles:
- Current day matches (every 2 minutes for live updates)
- Today's matches (every 30 minutes)
- Finishing live matches (every 10 minutes)

## Navigation Features
The matches page now includes:
- **Smart Date Picker**: Limited to available date range
- **Disabled Navigation**: Arrow buttons are disabled when no data is available
- **Quick Access**: "First Available" button to jump to earliest match
- **Clear Messaging**: Shows available date range when no matches are found

## API Limitations
- **Free Tier**: 10 requests per minute
- **Historical Data**: Limited availability for older matches
- **Rate Limiting**: Automatic delays to prevent API limits

## Tips for Better Coverage
1. Keep the auto-updater running to capture ongoing matches
2. Run historical sync commands during off-peak hours
3. Be patient with API rate limits for large date ranges
4. Focus on specific leagues to reduce API usage

## Current Status
✅ **Live Updates**: Working (every 2-30 minutes)  
✅ **Navigation**: Smart date boundaries implemented  
✅ **Historical Sync**: Available via command line  
✅ **Data Range**: August 15, 2025 to present  