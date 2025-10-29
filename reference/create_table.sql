-- ==============================================================
-- PLAYLYTICS - FOOTBALL MATCH ANALYTICS SYSTEM
-- Database Schema (MySQL)
-- ==============================================================

CREATE DATABASE IF NOT EXISTS playlytics_db;
USE playlytics_db;

-- ==============================================================
-- 1. USERS TABLE
-- ==============================================================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    is_admin BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ==============================================================
-- 2. LEAGUES TABLE
-- ==============================================================
CREATE TABLE leagues (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE,
    country VARCHAR(255),
    logo VARCHAR(255),
    season VARCHAR(50) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ==============================================================
-- 3. TEAMS TABLE
-- ==============================================================
CREATE TABLE teams (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE,
    league_id INT NOT NULL,
    city VARCHAR(255),
    founded YEAR,
    logo VARCHAR(255),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (league_id) REFERENCES leagues(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- ==============================================================
-- 4. PLAYERS TABLE
-- ==============================================================
CREATE TABLE players (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE,
    team_id INT NOT NULL,
    position VARCHAR(100),
    age INT,
    nationality VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (team_id) REFERENCES teams(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- ==============================================================
-- 5. FOOTBALL MATCHES TABLE
-- ==============================================================
CREATE TABLE football_matches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    league_id INT NOT NULL,
    home_team_id INT NOT NULL,
    away_team_id INT NOT NULL,
    match_date DATETIME,
    home_score INT DEFAULT 0,
    away_score INT DEFAULT 0,
    status ENUM('scheduled', 'live', 'finished', 'postponed', 'cancelled') NOT NULL DEFAULT 'scheduled',
    minute INT,
    venue VARCHAR(255),
    attendance INT,
    referee VARCHAR(255),
    matchweek INT,
    events JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (league_id) REFERENCES leagues(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (home_team_id) REFERENCES teams(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (away_team_id) REFERENCES teams(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- ==============================================================
-- 6. PLAYER STATS TABLE
-- ==============================================================
CREATE TABLE player_stats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    match_id INT NOT NULL,
    player_id INT NOT NULL,
    goals INT DEFAULT 0,
    assists INT DEFAULT 0,
    minutes_played INT DEFAULT 0,
    yellow_cards INT DEFAULT 0,
    red_cards INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (match_id) REFERENCES football_matches(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (player_id) REFERENCES players(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- ==============================================================
-- INDEXES FOR PERFORMANCE
-- ==============================================================
CREATE INDEX idx_leagues_slug ON leagues(slug);
CREATE INDEX idx_teams_slug ON teams(slug);
CREATE INDEX idx_players_slug ON players(slug);
CREATE INDEX idx_matches_status ON football_matches(status);
CREATE INDEX idx_matches_date ON football_matches(match_date);

-- ==============================================================
-- END OF FILE
-- ==============================================================

