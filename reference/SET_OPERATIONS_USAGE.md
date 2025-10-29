# SET Operations Usage in Playlytics

## Overview
SET operations (UNION, INTERSECT) have been implemented in **2 practical locations** within the application, not just as examples in the SQL executor.

---

## 1️⃣ UNION Implementation

### **Location:** `pages/league_detail.php`

### **Purpose:** 
Combine home and away match statistics for teams in a league

### **Query Used:**
```php
$team_activities_query = "
    SELECT 
        t.id,
        t.name AS team_name,
        'Home Match' AS match_type,
        m.match_date,
        m.home_score AS goals_scored,
        m.away_score AS goals_conceded,
        CASE 
            WHEN m.home_score > m.away_score THEN 'Won'
            WHEN m.home_score < m.away_score THEN 'Lost'
            ELSE 'Draw'
        END AS result
    FROM teams t
    JOIN football_matches m ON t.id = m.home_team_id
    WHERE t.league_id = $league_id AND m.status = 'finished'
    
    UNION ALL
    
    SELECT 
        t.id,
        t.name AS team_name,
        'Away Match' AS match_type,
        m.match_date,
        m.away_score AS goals_scored,
        m.home_score AS goals_conceded,
        CASE 
            WHEN m.away_score > m.home_score THEN 'Won'
            WHEN m.away_score < m.home_score THEN 'Lost'
            ELSE 'Draw'
        END AS result
    FROM teams t
    JOIN football_matches m ON t.id = m.away_team_id
    WHERE t.league_id = $league_id AND m.status = 'finished'
    
    ORDER BY match_date DESC
    LIMIT 15";
```

### **What it Does:**
- **First SELECT:** Gets all home matches for teams
- **UNION ALL:** Combines with away matches
- **Result:** Complete match history showing both home and away performances

### **Why UNION ALL instead of UNION:**
- No duplicates possible (a match can't be both home and away for the same team)
- UNION ALL is faster (no duplicate checking needed)

### **Display:**
Shows a table with columns:
- Date
- Team Name
- Match Type (Home/Away with color badges)
- Goals Scored
- Goals Conceded
- Result (Won/Lost/Draw)

### **How to Test:**
1. Go to `http://localhost/Playlytics/pages/leagues.php`
2. Click "View Details" on any league
3. Scroll down to see "Team Activities (Home + Away Combined)" section
4. The table shows unified home and away match results

---

## 2️⃣ INTERSECT Implementation (Simulated)

### **Location:** `pages/players.php`

### **Purpose:** 
Find players who have BOTH scored goals AND provided assists (intersection of two sets)

### **Query Used:**
```php
$versatile_players_query = "SELECT 
                            p.id,
                            p.name,
                            t.name as team_name,
                            SUM(ps.goals) as total_goals,
                            SUM(ps.assists) as total_assists
                            FROM players p
                            INNER JOIN teams t ON p.team_id = t.id
                            INNER JOIN player_stats ps ON p.id = ps.player_id
                            WHERE p.id IN (
                                SELECT player_id FROM player_stats WHERE goals > 0
                            ) 
                            AND p.id IN (
                                SELECT player_id FROM player_stats WHERE assists > 0
                            )
                            GROUP BY p.id, p.name, t.name
                            HAVING total_goals > 0 AND total_assists > 0
                            ORDER BY (total_goals + total_assists) DESC
                            LIMIT 10";
```

### **What it Does:**
- **First subquery:** Finds all players who scored (Set A)
- **Second subquery:** Finds all players who assisted (Set B)
- **WHERE ... AND ...:** Gets intersection (players in both sets)
- **Result:** Players who are "versatile" (both scorers and playmakers)

### **Why INTERSECT Simulation:**
MySQL doesn't support native INTERSECT, so we use:
- Two `IN` subqueries with `AND` logic
- This achieves the same result as `SELECT ... INTERSECT SELECT ...`

### **Display:**
Shows a table titled "Versatile Players (Goals + Assists)" with:
- Rank
- Player Name
- Team
- Goals (green)
- Assists (blue)
- Total Contributions

### **How to Test:**
1. Go to `http://localhost/Playlytics/pages/players.php`
2. Scroll down past "Top Scorers" section
3. See "Versatile Players (Goals + Assists)" section
4. Table shows only players who contributed both goals AND assists

---

## Comparison: UNION vs INTERSECT

| Feature | UNION (league_detail.php) | INTERSECT (players.php) |
|---------|---------------------------|-------------------------|
| **Operation** | Combines rows from two queries | Finds common rows between two sets |
| **SQL Syntax** | `SELECT ... UNION SELECT ...` | `WHERE id IN (...) AND id IN (...)` |
| **Result Size** | Sum of both queries (minus duplicates) | Only rows present in BOTH queries |
| **Use Case** | Merge home + away matches | Find players in both scorer AND assister sets |
| **Performance** | UNION ALL is faster | Subqueries may be slower on large data |

---

## Educational Value

### **For UNION:**
✅ Demonstrates combining related data from different perspectives
✅ Shows UNION ALL optimization (when duplicates impossible)
✅ Practical use case: unified match history view

### **For INTERSECT:**
✅ Shows how to simulate INTERSECT in MySQL
✅ Demonstrates set theory concepts (intersection)
✅ Practical use case: finding versatile/multi-skilled players

---

## SQL Logged in Query Tracker

Both queries appear in the floating "View SQL Queries" button with descriptions:

1. **UNION Query:**
   ```
   "UNION ALL: Combine home and away match results for all teams in league"
   File: pages/league_detail.php
   ```

2. **INTERSECT Query:**
   ```
   "INTERSECT simulation using IN subqueries: Find players who BOTH scored AND assisted"
   File: pages/players.php
   ```

---

## Alternative Implementations

### **If you wanted to use UNION in more places:**

**Example: Combine Live + Scheduled Matches**
```sql
SELECT 'LIVE' AS status, * FROM football_matches WHERE status = 'live'
UNION ALL
SELECT 'SCHEDULED' AS status, * FROM football_matches WHERE status = 'scheduled'
ORDER BY match_date;
```

**Example: All Team Names from Multiple Leagues**
```sql
SELECT name, 'Premier League' AS league FROM teams WHERE league_id = 1
UNION
SELECT name, 'La Liga' AS league FROM teams WHERE league_id = 2;
```

### **If you wanted more INTERSECT simulations:**

**Example: Find Common Opponents**
```sql
-- Teams that played against BOTH Team A and Team B
SELECT DISTINCT t.id, t.name
FROM teams t
WHERE t.id IN (
    SELECT home_team_id FROM football_matches WHERE away_team_id = 1
    UNION
    SELECT away_team_id FROM football_matches WHERE home_team_id = 1
)
AND t.id IN (
    SELECT home_team_id FROM football_matches WHERE away_team_id = 2
    UNION
    SELECT away_team_id FROM football_matches WHERE home_team_id = 2
);
```

---

## Summary

✅ **UNION implemented:** `pages/league_detail.php` - Team Activities section
✅ **INTERSECT implemented:** `pages/players.php` - Versatile Players section
✅ **Both are functional** and visible in the live application
✅ **Educational annotations** included in UI (info messages)
✅ **Queries logged** for tracking and learning purposes

Both implementations serve real business logic, not just examples! 🎯
