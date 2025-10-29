<?php
require_once '../config/config.php';
require_once '../config/db_connect.php';

$conn = getConnection();
clearQueryLog();

$league_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Get league details
$league_query = "SELECT * FROM leagues WHERE id = $league_id";
$league_result = executeQuery($conn, $league_query, "SELECT: Fetch league details by ID");
$league = $league_result->fetch_assoc();

if (!$league) {
    header('Location: leagues.php');
    exit;
}

$page_title = $league['name'];

// Get teams in this league
$teams_query = "SELECT t.*, 
                COUNT(DISTINCT p.id) as player_count
                FROM teams t
                LEFT JOIN players p ON t.id = p.team_id
                WHERE t.league_id = $league_id
                GROUP BY t.id
                ORDER BY t.name";
$teams_result = executeQuery($conn, $teams_query, "LEFT JOIN with COUNT: Get teams and player count for this league");

// Get league matches
$matches_query = "SELECT m.*, 
                  ht.name as home_team, 
                  at.name as away_team
                  FROM football_matches m
                  JOIN teams ht ON m.home_team_id = ht.id
                  JOIN teams at ON m.away_team_id = at.id
                  WHERE m.league_id = $league_id
                  ORDER BY m.match_date DESC
                  LIMIT 10";
$matches_result = executeQuery($conn, $matches_query, "JOIN: Get recent matches for this league");

// Get all team activities using UNION (combine home and away match statistics)
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
$team_activities_result = executeQuery($conn, $team_activities_query, 
    "UNION ALL: Combine home and away match results for all teams in league");

include '../includes/header.php';
?>

<div class="container">
    <div class="page-header">
        <h1>🏆 <?php echo htmlspecialchars($league['name']); ?></h1>
        <p><?php echo htmlspecialchars($league['country']); ?> • Season <?php echo htmlspecialchars($league['season']); ?></p>
    </div>

    <!-- Teams in League -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">⚽ Teams</h2>
        </div>
        <?php if ($teams_result && $teams_result->num_rows > 0): ?>
        <div class="grid-3">
            <?php while ($team = $teams_result->fetch_assoc()): ?>
            <div style="background: var(--light-bg); padding: 1rem; border-radius: 8px; text-align: center;">
                <h3><?php echo htmlspecialchars($team['name']); ?></h3>
                <p><?php echo htmlspecialchars($team['city']); ?></p>
                <p><strong>Players:</strong> <?php echo $team['player_count']; ?></p>
                <?php if ($team['founded']): ?>
                <p><small>Founded: <?php echo $team['founded']; ?></small></p>
                <?php endif; ?>
            </div>
            <?php endwhile; ?>
        </div>
        <?php else: ?>
        <p style="text-align: center; padding: 2rem; color: var(--text-light);">No teams in this league yet.</p>
        <?php endif; ?>
    </div>

    <!-- Recent Matches -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">📊 Recent Matches</h2>
        </div>
        <?php if ($matches_result && $matches_result->num_rows > 0): ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Home Team</th>
                    <th>Score</th>
                    <th>Away Team</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($match = $matches_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo date('M d, Y', strtotime($match['match_date'])); ?></td>
                    <td><?php echo htmlspecialchars($match['home_team']); ?></td>
                    <td><strong><?php echo $match['home_score']; ?> - <?php echo $match['away_score']; ?></strong></td>
                    <td><?php echo htmlspecialchars($match['away_team']); ?></td>
                    <td><span class="match-status status-<?php echo $match['status']; ?>"><?php echo strtoupper($match['status']); ?></span></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p style="text-align: center; padding: 2rem; color: var(--text-light);">No matches in this league yet.</p>
        <?php endif; ?>
    </div>

    <!-- Team Match Activities (Using UNION) -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title"><i class="fas fa-exchange-alt"></i> Team Activities (Home + Away Combined)</h2>
            <p style="margin: 0.5rem 0 0 0; font-size: 0.9rem; color: var(--text-secondary);">
                <i class="fas fa-info-circle"></i> Using UNION ALL to combine home and away match results
            </p>
        </div>
        <?php if ($team_activities_result && $team_activities_result->num_rows > 0): ?>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Team</th>
                        <th>Type</th>
                        <th>Goals Scored</th>
                        <th>Goals Conceded</th>
                        <th>Result</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($activity = $team_activities_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo date('M d, Y', strtotime($activity['match_date'])); ?></td>
                        <td><strong><?php echo htmlspecialchars($activity['team_name']); ?></strong></td>
                        <td>
                            <span class="badge" style="background: <?php echo $activity['match_type'] === 'Home Match' ? '#2196F3' : '#FF9800'; ?>;">
                                <?php echo $activity['match_type']; ?>
                            </span>
                        </td>
                        <td><strong style="color: var(--success-color);"><?php echo $activity['goals_scored']; ?></strong></td>
                        <td><strong style="color: var(--danger-color);"><?php echo $activity['goals_conceded']; ?></strong></td>
                        <td>
                            <span class="badge badge-<?php 
                                echo $activity['result'] === 'Won' ? 'success' : 
                                    ($activity['result'] === 'Lost' ? 'danger' : 'warning'); 
                            ?>">
                                <?php echo $activity['result']; ?>
                            </span>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <p style="text-align: center; padding: 2rem; color: var(--text-light);">No finished matches yet.</p>
        <?php endif; ?>
    </div>

    <div style="margin-top: 2rem;">
        <a href="leagues.php" class="btn btn-primary">← Back to All Leagues</a>
    </div>
</div>

<?php
$conn->close();
include '../includes/footer.php';
?>
