<?php
// ==============================================================
// PLAYLYTICS - Seed Dummy Data Script
// ==============================================================

require_once '../config/config.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<h2>Playlytics - Seeding Dummy Data</h2>";
echo "<pre>";

// Clear existing data (optional - comment out if you want to keep existing data)
echo "Clearing existing data...\n";
$conn->query("SET FOREIGN_KEY_CHECKS = 0");
$conn->query("DELETE FROM player_stats");
$conn->query("DELETE FROM football_matches");
$conn->query("DELETE FROM players");
$conn->query("DELETE FROM teams");
$conn->query("DELETE FROM leagues");
$conn->query("DELETE FROM users");

// Reset AUTO_INCREMENT counters
$conn->query("ALTER TABLE player_stats AUTO_INCREMENT = 1");
$conn->query("ALTER TABLE football_matches AUTO_INCREMENT = 1");
$conn->query("ALTER TABLE players AUTO_INCREMENT = 1");
$conn->query("ALTER TABLE teams AUTO_INCREMENT = 1");
$conn->query("ALTER TABLE leagues AUTO_INCREMENT = 1");
$conn->query("ALTER TABLE users AUTO_INCREMENT = 1");

$conn->query("SET FOREIGN_KEY_CHECKS = 1");
echo "✓ Existing data cleared and counters reset\n\n";

// ==============================================================
// 1. INSERT LEAGUES
// ==============================================================
echo "Inserting Leagues...\n";

$leagues = [
    ['Premier League', 'premier-league', 'England', '2024-25'],
    ['La Liga', 'la-liga', 'Spain', '2024-25'],
    ['Bundesliga', 'bundesliga', 'Germany', '2024-25'],
    ['Serie A', 'serie-a', 'Italy', '2024-25'],
];

foreach ($leagues as $league) {
    $name = $conn->real_escape_string($league[0]);
    $slug = $conn->real_escape_string($league[1]);
    $country = $conn->real_escape_string($league[2]);
    $season = $conn->real_escape_string($league[3]);
    
    $sql = "INSERT INTO leagues (name, slug, country, season, is_active) 
            VALUES ('$name', '$slug', '$country', '$season', 1)";
    if ($conn->query($sql)) {
        echo "  ✓ {$league[0]} created (ID: {$conn->insert_id})\n";
    } else {
        echo "  ✗ Error creating {$league[0]}: " . $conn->error . "\n";
    }
}

// Verify leagues were inserted
$result = $conn->query("SELECT id, name FROM leagues");
$league_count = $result->num_rows;
echo "\nVerifying leagues in database: Found $league_count leagues\n";
if ($league_count > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "  - League ID {$row['id']}: {$row['name']}\n";
    }
} else {
    echo "  ⚠ WARNING: No leagues found in database! Teams insertion will fail.\n";
    echo "  Stopping execution to prevent errors.\n";
    echo "</pre>";
    $conn->close();
    exit;
}

// ==============================================================
// 2. INSERT TEAMS
// ==============================================================
echo "\nInserting Teams...\n";

$teams = [
    // Premier League
    ['Manchester United', 'manchester-united', 1, 'Manchester', 1878],
    ['Liverpool FC', 'liverpool-fc', 1, 'Liverpool', 1892],
    ['Chelsea FC', 'chelsea-fc', 1, 'London', 1905],
    ['Arsenal FC', 'arsenal-fc', 1, 'London', 1886],
    ['Manchester City', 'manchester-city', 1, 'Manchester', 1880],
    ['Tottenham Hotspur', 'tottenham-hotspur', 1, 'London', 1882],
    
    // La Liga
    ['Real Madrid', 'real-madrid', 2, 'Madrid', 1902],
    ['FC Barcelona', 'fc-barcelona', 2, 'Barcelona', 1899],
    ['Atletico Madrid', 'atletico-madrid', 2, 'Madrid', 1903],
    ['Sevilla FC', 'sevilla-fc', 2, 'Seville', 1890],
    
    // Bundesliga
    ['Bayern Munich', 'bayern-munich', 3, 'Munich', 1900],
    ['Borussia Dortmund', 'borussia-dortmund', 3, 'Dortmund', 1909],
    ['RB Leipzig', 'rb-leipzig', 3, 'Leipzig', 2009],
    
    // Serie A
    ['Juventus', 'juventus', 4, 'Turin', 1897],
    ['AC Milan', 'ac-milan', 4, 'Milan', 1899],
    ['Inter Milan', 'inter-milan', 4, 'Milan', 1908],
];

foreach ($teams as $team) {
    $name = $conn->real_escape_string($team[0]);
    $slug = $conn->real_escape_string($team[1]);
    $city = $conn->real_escape_string($team[3]);
    
    $sql = "INSERT INTO teams (name, slug, league_id, city, founded) 
            VALUES ('$name', '$slug', {$team[2]}, '$city', {$team[4]})";
    if ($conn->query($sql)) {
        echo "  ✓ {$team[0]} created\n";
    } else {
        echo "  ✗ Error creating {$team[0]}: " . $conn->error . "\n";
    }
}

// ==============================================================
// 3. INSERT PLAYERS
// ==============================================================
echo "\nInserting Players...\n";

$players = [
    // Manchester United
    ['Marcus Rashford', 'marcus-rashford', 1, 'Forward', 26, 'England'],
    ['Bruno Fernandes', 'bruno-fernandes', 1, 'Midfielder', 29, 'Portugal'],
    ['David De Gea', 'david-de-gea', 1, 'Goalkeeper', 33, 'Spain'],
    ['Harry Maguire', 'harry-maguire', 1, 'Defender', 31, 'England'],
    
    // Liverpool
    ['Mohamed Salah', 'mohamed-salah', 2, 'Forward', 31, 'Egypt'],
    ['Virgil van Dijk', 'virgil-van-dijk', 2, 'Defender', 32, 'Netherlands'],
    ['Trent Alexander-Arnold', 'trent-alexander-arnold', 2, 'Defender', 25, 'England'],
    ['Alisson Becker', 'alisson-becker', 2, 'Goalkeeper', 31, 'Brazil'],
    
    // Chelsea
    ['Raheem Sterling', 'raheem-sterling', 3, 'Forward', 29, 'England'],
    ['Enzo Fernandez', 'enzo-fernandez', 3, 'Midfielder', 23, 'Argentina'],
    ['Thiago Silva', 'thiago-silva', 3, 'Defender', 39, 'Brazil'],
    
    // Arsenal
    ['Bukayo Saka', 'bukayo-saka', 4, 'Forward', 22, 'England'],
    ['Martin Odegaard', 'martin-odegaard', 4, 'Midfielder', 25, 'Norway'],
    ['William Saliba', 'william-saliba', 4, 'Defender', 23, 'France'],
    
    // Manchester City
    ['Erling Haaland', 'erling-haaland', 5, 'Forward', 24, 'Norway'],
    ['Kevin De Bruyne', 'kevin-de-bruyne', 5, 'Midfielder', 32, 'Belgium'],
    ['Ederson', 'ederson', 5, 'Goalkeeper', 30, 'Brazil'],
    
    // Real Madrid
    ['Vinicius Junior', 'vinicius-junior', 7, 'Forward', 23, 'Brazil'],
    ['Luka Modric', 'luka-modric', 7, 'Midfielder', 38, 'Croatia'],
    ['Thibaut Courtois', 'thibaut-courtois', 7, 'Goalkeeper', 31, 'Belgium'],
    
    // Barcelona
    ['Robert Lewandowski', 'robert-lewandowski', 8, 'Forward', 35, 'Poland'],
    ['Pedri', 'pedri', 8, 'Midfielder', 21, 'Spain'],
    ['Gavi', 'gavi', 8, 'Midfielder', 19, 'Spain'],
    
    // Bayern Munich
    ['Harry Kane', 'harry-kane', 11, 'Forward', 30, 'England'],
    ['Joshua Kimmich', 'joshua-kimmich', 11, 'Midfielder', 28, 'Germany'],
    ['Manuel Neuer', 'manuel-neuer', 11, 'Goalkeeper', 37, 'Germany'],
    
    // Juventus
    ['Dusan Vlahovic', 'dusan-vlahovic', 14, 'Forward', 24, 'Serbia'],
    ['Federico Chiesa', 'federico-chiesa', 14, 'Forward', 26, 'Italy'],
    
    // AC Milan
    ['Rafael Leao', 'rafael-leao', 15, 'Forward', 24, 'Portugal'],
    ['Theo Hernandez', 'theo-hernandez', 15, 'Defender', 26, 'France'],
];

foreach ($players as $player) {
    $name = $conn->real_escape_string($player[0]);
    $slug = $conn->real_escape_string($player[1]);
    $position = $conn->real_escape_string($player[3]);
    $nationality = $conn->real_escape_string($player[5]);
    
    $sql = "INSERT INTO players (name, slug, team_id, position, age, nationality) 
            VALUES ('$name', '$slug', {$player[2]}, '$position', {$player[4]}, '$nationality')";
    if ($conn->query($sql)) {
        echo "  ✓ {$player[0]} created\n";
    } else {
        echo "  ✗ Error creating {$player[0]}: " . $conn->error . "\n";
    }
}

// ==============================================================
// 4. INSERT MATCHES
// ==============================================================
echo "\nInserting Matches...\n";

$matches = [
    // Live matches
    [1, 1, 2, '2024-10-27 15:30:00', 2, 1, 'live', 67, 'Old Trafford', 'Michael Oliver', 10],
    [1, 5, 4, '2024-10-27 15:30:00', 3, 2, 'live', 72, 'Etihad Stadium', 'Anthony Taylor', 10],
    
    // Recent finished matches
    [1, 3, 6, '2024-10-25 20:00:00', 2, 2, 'finished', null, 'Stamford Bridge', 'Martin Atkinson', 9],
    [2, 7, 8, '2024-10-24 21:00:00', 3, 1, 'finished', null, 'Santiago Bernabeu', 'Carlos Del Cerro', 9],
    [3, 11, 12, '2024-10-24 18:30:00', 4, 1, 'finished', null, 'Allianz Arena', 'Felix Brych', 9],
    [4, 14, 15, '2024-10-23 19:45:00', 1, 1, 'finished', null, 'Allianz Stadium', 'Daniele Orsato', 9],
    [1, 2, 5, '2024-10-22 17:30:00', 2, 3, 'finished', null, 'Anfield', 'Michael Oliver', 9],
    [2, 8, 9, '2024-10-21 20:00:00', 2, 0, 'finished', null, 'Camp Nou', 'Antonio Mateu', 8],
    
    // Upcoming scheduled matches
    [1, 4, 1, '2024-10-29 20:00:00', 0, 0, 'scheduled', null, 'Emirates Stadium', null, 11],
    [1, 6, 3, '2024-10-30 19:45:00', 0, 0, 'scheduled', null, 'Tottenham Hotspur Stadium', null, 11],
    [2, 9, 7, '2024-10-31 21:00:00', 0, 0, 'scheduled', null, 'Wanda Metropolitano', null, 10],
    [3, 12, 11, '2024-11-01 18:30:00', 0, 0, 'scheduled', null, 'Signal Iduna Park', null, 10],
    [4, 15, 16, '2024-11-02 19:45:00', 0, 0, 'scheduled', null, 'San Siro', null, 10],
    [1, 1, 5, '2024-11-03 16:00:00', 0, 0, 'scheduled', null, 'Old Trafford', null, 11],
];

foreach ($matches as $match) {
    $minute = $match[7] !== null ? $match[7] : 'NULL';
    $venue = $conn->real_escape_string($match[8]);
    $referee = $match[9] !== null ? "'" . $conn->real_escape_string($match[9]) . "'" : 'NULL';
    $matchweek = $match[10] !== null ? $match[10] : 'NULL';
    
    $sql = "INSERT INTO football_matches 
            (league_id, home_team_id, away_team_id, match_date, home_score, away_score, status, minute, venue, referee, matchweek) 
            VALUES ({$match[0]}, {$match[1]}, {$match[2]}, '{$match[3]}', {$match[4]}, {$match[5]}, '{$match[6]}', $minute, '$venue', $referee, $matchweek)";
    if ($conn->query($sql)) {
        echo "  ✓ Match #{$conn->insert_id} created\n";
    } else {
        echo "  ✗ Error: " . $conn->error . "\n";
    }
}

// ==============================================================
// 5. INSERT PLAYER STATS
// ==============================================================
echo "\nInserting Player Stats...\n";

$player_stats = [
    // Match 1: Man United 2-1 Liverpool (live)
    [1, 1, 2, 1, 90, 0, 0],  // Rashford - 2 goals
    [1, 2, 0, 1, 90, 1, 0],  // Fernandes - 1 assist, yellow
    [1, 5, 1, 0, 90, 0, 0],  // Salah - 1 goal
    
    // Match 2: Man City 3-2 Arsenal (live)
    [2, 15, 2, 0, 72, 0, 0], // Haaland - 2 goals
    [2, 16, 0, 2, 72, 0, 0], // De Bruyne - 2 assists
    [2, 12, 2, 0, 72, 0, 0], // Saka - 2 goals
    
    // Match 3: Chelsea 2-2 Tottenham (finished)
    [3, 9, 1, 1, 90, 0, 0],  // Sterling - 1 goal, 1 assist
    
    // Match 4: Real Madrid 3-1 Barcelona (finished)
    [4, 18, 2, 1, 90, 0, 0], // Vinicius - 2 goals, 1 assist
    [4, 21, 1, 0, 90, 0, 0], // Lewandowski - 1 goal
    
    // Match 5: Bayern 4-1 Dortmund (finished)
    [5, 24, 3, 1, 90, 0, 0], // Kane - 3 goals (hat-trick), 1 assist
    [5, 25, 0, 2, 90, 1, 0], // Kimmich - 2 assists, yellow
];

foreach ($player_stats as $stat) {
    $sql = "INSERT INTO player_stats 
            (match_id, player_id, goals, assists, minutes_played, yellow_cards, red_cards) 
            VALUES ({$stat[0]}, {$stat[1]}, {$stat[2]}, {$stat[3]}, {$stat[4]}, {$stat[5]}, {$stat[6]})";
    if ($conn->query($sql)) {
        echo "  ✓ Player stat added\n";
    }
}

echo "\n";
echo "===========================================\n";
echo "Data Seeding Complete!\n";
echo "===========================================\n";
echo "\n";
echo "Summary:\n";
echo "- " . count($leagues) . " leagues added\n";
echo "- " . count($teams) . " teams added\n";
echo "- " . count($players) . " players added\n";
echo "- " . count($matches) . " matches added\n";
echo "- " . count($player_stats) . " player stats added\n";
echo "\n";
echo "You can now explore the application!\n";
echo "Visit: " . BASE_URL . "\n";
echo "</pre>";

$conn->close();
?>
