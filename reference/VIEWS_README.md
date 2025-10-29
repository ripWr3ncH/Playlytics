# SQL Views - Playlytics

## Overview
This project includes SQL Views to demonstrate view creation and usage in MySQL databases.

## What are Views?
A VIEW is a virtual table based on the result set of a SQL statement. It contains rows and columns just like a real table, but the data is dynamically generated from the underlying tables.

## Benefits
- **Simplify Complex Queries**: Encapsulate complex JOINs into simple SELECT statements
- **Data Abstraction**: Hide complexity from end users
- **Security**: Restrict access to specific columns/rows
- **Reusability**: Write once, use many times

## Installed Views

### 1. v_match_details
**Purpose**: Simplifies match queries by pre-joining teams and leagues

**Definition**:
```sql
CREATE OR REPLACE VIEW v_match_details AS
SELECT 
    m.id,
    m.match_date,
    m.status,
    m.minute,
    m.home_score,
    m.away_score,
    m.venue,
    m.matchweek,
    l.name AS league_name,
    ht.name AS home_team_name,
    at.name AS away_team_name
FROM football_matches m
INNER JOIN leagues l ON m.league_id = l.id
INNER JOIN teams ht ON m.home_team_id = ht.id
INNER JOIN teams at ON m.away_team_id = at.id;
```

**Usage**:
```sql
-- Get all finished matches
SELECT * FROM v_match_details WHERE status = 'finished' LIMIT 10;

-- Get live matches
SELECT * FROM v_match_details WHERE status = 'live';

-- Get matches by league
SELECT * FROM v_match_details WHERE league_name = 'Premier League';
```

### 2. v_player_stats
**Purpose**: Aggregates player performance across all matches

**Definition**:
```sql
CREATE OR REPLACE VIEW v_player_stats AS
SELECT 
    p.id AS player_id,
    p.name AS player_name,
    t.name AS team_name,
    l.name AS league_name,
    COUNT(DISTINCT ps.match_id) AS matches_played,
    COALESCE(SUM(ps.goals), 0) AS total_goals,
    COALESCE(SUM(ps.assists), 0) AS total_assists,
    COALESCE(SUM(ps.minutes_played), 0) AS total_minutes
FROM players p
INNER JOIN teams t ON p.team_id = t.id
INNER JOIN leagues l ON t.league_id = l.id
LEFT JOIN player_stats ps ON p.id = ps.player_id
GROUP BY p.id, p.name, t.name, l.name;
```

**Usage**:
```sql
-- Top scorers
SELECT * FROM v_player_stats ORDER BY total_goals DESC LIMIT 10;

-- Players with most assists
SELECT * FROM v_player_stats ORDER BY total_assists DESC LIMIT 10;

-- Players from specific team
SELECT * FROM v_player_stats WHERE team_name = 'Arsenal';
```

## Installation

### Option 1: Web Interface
1. Navigate to `http://localhost/Playlytics/setup/install_views.php`
2. Click "Install Views Now"
3. Test in SQL Query Executor

### Option 2: MySQL Command Line
```bash
mysql -u root -p playlytics_db < reference/create_views.sql
```

### Option 3: phpMyAdmin
1. Open phpMyAdmin
2. Select `playlytics_db` database
3. Go to SQL tab
4. Paste contents of `reference/create_views.sql`
5. Click "Go"

## Testing
Visit the SQL Query Executor page to test the views:
- Go to: `http://localhost/Playlytics/pages/query_executor.php`
- Scroll down to "VIEW" examples
- Click "Load Query" buttons to test

## View Management

### Check if views exist:
```sql
SHOW FULL TABLES WHERE Table_type = 'VIEW';
```

### View definition:
```sql
SHOW CREATE VIEW v_match_details;
SHOW CREATE VIEW v_player_stats;
```

### Drop views:
```sql
DROP VIEW IF EXISTS v_match_details;
DROP VIEW IF EXISTS v_player_stats;
```

### Update view:
```sql
CREATE OR REPLACE VIEW v_match_details AS
SELECT ... -- new definition
```

## Notes
- Views are read-only in this project
- Views automatically update when underlying table data changes
- Views do not store data; they generate it dynamically
- Performance: Views with complex aggregations may be slower than direct queries

## Files
- `reference/create_views.sql` - View definitions
- `setup/install_views.php` - Web-based installer
- `pages/query_executor.php` - Test interface with VIEW examples
