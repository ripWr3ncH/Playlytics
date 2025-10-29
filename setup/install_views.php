<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Install Views - Playlytics</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container" style="max-width: 800px; margin: 3rem auto;">
        <div class="card">
            <h1 style="color: var(--primary-color); margin-bottom: 1rem;">
                <i class="fas fa-eye"></i> Install Database Views
            </h1>

            <?php
            require_once '../config/config.php';
            require_once '../config/db_connect.php';

            $conn = getConnection();

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $sql1 = "CREATE OR REPLACE VIEW v_match_details AS
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
                INNER JOIN teams at ON m.away_team_id = at.id";

                $sql2 = "CREATE OR REPLACE VIEW v_player_stats AS
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
                GROUP BY p.id, p.name, t.name, l.name";

                $success = true;
                $messages = [];

                if ($conn->query($sql1)) {
                    $messages[] = "✓ Created view: v_match_details";
                } else {
                    $success = false;
                    $messages[] = "✗ Error creating v_match_details: " . $conn->error;
                }

                if ($conn->query($sql2)) {
                    $messages[] = "✓ Created view: v_player_stats";
                } else {
                    $success = false;
                    $messages[] = "✗ Error creating v_player_stats: " . $conn->error;
                }

                if ($success) {
                    echo '<div class="alert alert-success">';
                    echo '<h3><i class="fas fa-check-circle"></i> Success!</h3>';
                    echo '<p>Database views installed successfully:</p>';
                    echo '<ul>';
                    foreach ($messages as $msg) {
                        echo '<li>' . $msg . '</li>';
                    }
                    echo '</ul>';
                    echo '</div>';
                    echo '<div style="margin-top: 1.5rem;">';
                    echo '<a href="../pages/query_executor.php" class="btn btn-success"><i class="fas fa-play"></i> Test Views in SQL Executor</a> ';
                    echo '<a href="../index.php" class="btn btn-primary"><i class="fas fa-home"></i> Go to Home</a>';
                    echo '</div>';
                } else {
                    echo '<div class="alert alert-error">';
                    echo '<h3><i class="fas fa-exclamation-circle"></i> Error</h3>';
                    echo '<ul>';
                    foreach ($messages as $msg) {
                        echo '<li>' . $msg . '</li>';
                    }
                    echo '</ul>';
                    echo '</div>';
                }

            } else {
                ?>
                <div class="alert alert-info">
                    <h3><i class="fas fa-info-circle"></i> About Database Views</h3>
                    <p>This will create 2 SQL views for educational purposes:</p>
                    <ul>
                        <li><strong>v_match_details</strong> - Simplifies match queries with team names</li>
                        <li><strong>v_player_stats</strong> - Aggregates player statistics</li>
                    </ul>
                    <p style="margin-top: 1rem;"><strong>Benefits of Views:</strong></p>
                    <ul>
                        <li>Encapsulate complex JOINs</li>
                        <li>Simplify recurring queries</li>
                        <li>Provide data abstraction</li>
                        <li>Improve code maintainability</li>
                    </ul>
                </div>

                <form method="POST" style="margin-top: 2rem;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-download"></i> Install Views Now
                    </button>
                </form>
                <?php
            }

            $conn->close();
            ?>
        </div>
    </div>
</body>
</html>
