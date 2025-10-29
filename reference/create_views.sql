-- ==============================================================
-- PLAYLYTICS - FOOTBALL MATCH ANALYTICS SYSTEM
-- SQL Views Definition
-- ==============================================================
-- Purpose: Create reusable database views for common queries
-- Run this after create_table.sql
-- ==============================================================

USE playlytics_db;

-- ==============================================================
-- VIEW 1: MATCH DETAILS WITH TEAM NAMES
-- ==============================================================
-- Purpose: Simplify match queries by pre-joining teams and leagues
-- Usage: SELECT * FROM v_match_details WHERE status = 'live'
-- ==============================================================
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

-- ==============================================================
-- VIEW 2: PLAYER CAREER STATISTICS
-- ==============================================================
-- Purpose: Aggregate player performance across all matches
-- Usage: SELECT * FROM v_player_stats ORDER BY total_goals DESC
-- ==============================================================
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

-- ==============================================================
-- VERIFICATION QUERIES (Test the views)
-- ==============================================================

-- Test view 1: Match details
-- SELECT * FROM v_match_details LIMIT 5;

-- Test view 2: Player stats
-- SELECT * FROM v_player_stats ORDER BY total_goals DESC LIMIT 10;

-- ==============================================================
-- END OF VIEWS
-- ==============================================================
