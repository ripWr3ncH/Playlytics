# 🔄 Real-Time Football Data Setup Guide

## Overview
KickOff Stats supports multiple methods to get real-time football data for Premier League, La Liga, and Serie A.

## 🛠️ Setup Options

### Option 1: Football-Data.org (Recommended for Beginners)

**Free Tier**: 10 requests/minute, perfect for testing

1. **Get API Key**:
   - Visit [football-data.org](https://www.football-data.org)
   - Register for free account
   - Get your API token

2. **Configure Laravel**:
   ```bash
   # Add to .env file
   FOOTBALL_DATA_API_KEY=your_api_key_here
   ```

3. **Sync Data**:
   ```bash
   # Sync today's matches
   php artisan football:sync --today
   
   # Sync only live matches
   php artisan football:sync --live
   
   # Full data sync (teams, standings, matches)
   php artisan football:sync
   ```

### Option 2: API-Football (RapidAPI) - More Features

**Features**: Live commentary, player stats, detailed events

1. **Get API Key**:
   - Visit [RapidAPI API-Football](https://rapidapi.com/api-sports/api/api-football)
   - Subscribe to free or paid plan
   - Get your API key

2. **Configure**:
   ```bash
   # Add to .env
   API_FOOTBALL_KEY=your_rapidapi_key
   API_FOOTBALL_HOST=api-football-v1.p.rapidapi.com
   ```

### Option 3: SportMonks (Premium)

**Features**: Most comprehensive data, real-time WebSockets

## 🚀 Automated Updates

### Schedule Background Updates

1. **Laravel Scheduler** (Windows/Local Development):
   ```bash
   # Run this command to start the scheduler
   php artisan schedule:work
   ```

2. **Cron Job** (Production Linux Server):
   ```bash
   # Add to your server's crontab
   * * * * * cd /path/to/your/project && php artisan schedule:run >> /dev/null 2>&1
   ```

### Manual Updates
```bash
# Test the API connection
php artisan football:sync --today

# Update live scores only
php artisan football:sync --live
```

## 🔴 Live Score Features

### Automatic Frontend Updates
- **Auto-refresh**: Every 30 seconds during live matches
- **Visual indicators**: Pulsing animations for live matches
- **Notifications**: Updates shown to users
- **Manual refresh**: Floating refresh button

### Real-Time WebSocket (Advanced)
```javascript
// WebSocket connection for instant updates
const ws = new WebSocket('ws://your-domain.com/ws/live-scores');
ws.onmessage = (event) => {
    const data = JSON.parse(event.data);
    // Update UI instantly
};
```

## 📊 Data Coverage

### Leagues Supported
- ✅ **Premier League** (England)
- ✅ **La Liga** (Spain) 
- ✅ **Serie A** (Italy)

### Data Types
- ✅ Live scores and match status
- ✅ League standings/tables
- ✅ Team information
- ✅ Match fixtures and results
- ✅ Player statistics (with premium APIs)
- ✅ Match events (goals, cards, subs)

## 🔧 Troubleshooting

### Common Issues

1. **API Rate Limits**:
   ```bash
   # Check current usage
   curl -H "X-Auth-Token: YOUR_KEY" https://api.football-data.org/v4/matches
   ```

2. **Database Connection**:
   ```bash
   # Test database
   php artisan migrate:status
   ```

3. **Sync Errors**:
   ```bash
   # Check logs
   tail -f storage/logs/laravel.log
   ```

## 💡 Performance Tips

### Optimize API Calls
- Cache responses for 30 seconds
- Only sync live matches during match time
- Use webhooks when available
- Batch multiple requests

### Database Optimization
```sql
-- Add indexes for faster queries
ALTER TABLE football_matches ADD INDEX idx_status_date (status, match_date);
ALTER TABLE football_matches ADD INDEX idx_live (status, minute);
```

## 🚀 Production Deployment

### Server Requirements
- PHP 8.2+
- MySQL 8.0+
- Redis (for caching)
- Supervisor (for queue workers)

### Environment Setup
```bash
# Production .env
APP_ENV=production
QUEUE_CONNECTION=redis
CACHE_DRIVER=redis

# API keys
FOOTBALL_DATA_API_KEY=your_production_key
```

### Supervisor Configuration
```ini
[program:kickoff-stats-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/artisan queue:work redis --sleep=3 --tries=3
directory=/path/to/your/project
user=www-data
numprocs=2
autostart=true
autorestart=true
```

## 📈 Monitoring

### Health Checks
```bash
# Monitor API status
php artisan football:sync --live --dry-run

# Check queue status
php artisan queue:monitor
```

### Analytics
- Track API usage
- Monitor response times
- Alert on failed syncs
- User engagement metrics

## 🔐 Security

### API Key Protection
- Store in environment variables
- Use different keys for dev/staging/production
- Monitor usage for anomalies
- Rotate keys regularly

### Rate Limiting
```php
// Add to routes/web.php
Route::middleware('throttle:60,1')->group(function () {
    Route::prefix('api')->group(function () {
        // API routes
    });
});
```

---

## 🎯 Quick Start

1. **Get Football-Data.org API key**
2. **Add to .env**: `FOOTBALL_DATA_API_KEY=your_key`
3. **Run**: `php artisan football:sync --today`
4. **Start scheduler**: `php artisan schedule:work`
5. **Visit**: `http://localhost:8000`

Your football website will now have real-time live scores! ⚽
