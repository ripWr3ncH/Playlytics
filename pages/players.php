<?php
require_once '../config/config.php';
require_once '../config/db_connect.php';

$page_title = 'Players';
$conn = getConnection();
clearQueryLog();

// Get all players with team and league information
$players_query = "SELECT p.*, 
                  t.name as team_name,
                  l.name as league_name,
                  COALESCE(SUM(ps.goals), 0) as total_goals,
                  COALESCE(SUM(ps.assists), 0) as total_assists,
                  COALESCE(SUM(ps.yellow_cards), 0) as total_yellow_cards,
                  COALESCE(SUM(ps.red_cards), 0) as total_red_cards,
                  COUNT(DISTINCT ps.match_id) as matches_played
                  FROM players p
                  INNER JOIN teams t ON p.team_id = t.id
                  INNER JOIN leagues l ON t.league_id = l.id
                  LEFT JOIN player_stats ps ON p.id = ps.player_id
                  GROUP BY p.id
                  ORDER BY total_goals DESC, total_assists DESC";
$players_result = executeQuery($conn, $players_query, 
    "Multiple JOINs with aggregate functions: Get players with their statistics, team, and league info");

include '../includes/header.php';
?>

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-users"></i> Players</h1>
        <p>View all players and their statistics across all competitions</p>
    </div>

    <!-- Players Table -->
    <section class="section">
        <h2 class="section-title"><i class="fas fa-table"></i> All Players</h2>
        
        <?php if ($players_result && $players_result->num_rows > 0): ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Team</th>
                        <th>League</th>
                        <th>Position</th>
                        <th>Age</th>
                        <th>Nationality</th>
                        <th>Matches</th>
                        <th>Goals</th>
                        <th>Assists</th>
                        <th>🟨</th>
                        <th>🟥</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($player = $players_result->fetch_assoc()): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($player['name']); ?></strong></td>
                        <td><?php echo htmlspecialchars($player['team_name']); ?></td>
                        <td><?php echo htmlspecialchars($player['league_name']); ?></td>
                        <td><?php echo $player['position'] ? htmlspecialchars($player['position']) : '-'; ?></td>
                        <td><?php echo $player['age'] ?? '-'; ?></td>
                        <td><?php echo $player['nationality'] ? htmlspecialchars($player['nationality']) : '-'; ?></td>
                        <td><?php echo $player['matches_played']; ?></td>
                        <td><strong style="color: var(--success-color);"><?php echo $player['total_goals']; ?></strong></td>
                        <td><?php echo $player['total_assists']; ?></td>
                        <td><span class="badge badge-warning"><?php echo $player['total_yellow_cards']; ?></span></td>
                        <td><span class="badge badge-danger"><?php echo $player['total_red_cards']; ?></span></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <p style="text-align: center; color: var(--text-secondary); padding: 2rem;">
            No players found. Please add players in the admin panel.
        </p>
        <?php endif; ?>
    </section>

    <!-- Top Scorers -->
    <?php
    $top_scorers_query = "SELECT p.name, 
                          t.name as team_name,
                          SUM(ps.goals) as goals,
                          SUM(ps.assists) as assists
                          FROM players p
                          INNER JOIN teams t ON p.team_id = t.id
                          INNER JOIN player_stats ps ON p.id = ps.player_id
                          GROUP BY p.id
                          HAVING goals > 0
                          ORDER BY goals DESC, assists DESC
                          LIMIT 10";
    $top_scorers = executeQuery($conn, $top_scorers_query, 
        "GROUP BY with HAVING clause: Get top 10 goal scorers");
    ?>

    <?php if ($top_scorers && $top_scorers->num_rows > 0): ?>
    <section class="section">
        <h2 class="section-title"><i class="fas fa-medal"></i> Top Scorers</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Player</th>
                        <th>Team</th>
                        <th>Goals</th>
                        <th>Assists</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $rank = 1;
                    while ($scorer = $top_scorers->fetch_assoc()): 
                    ?>
                    <tr>
                        <td><strong><?php echo $rank++; ?></strong></td>
                        <td><?php echo htmlspecialchars($scorer['name']); ?></td>
                        <td><?php echo htmlspecialchars($scorer['team_name']); ?></td>
                        <td><strong><?php echo $scorer['goals']; ?></strong></td>
                        <td><?php echo $scorer['assists']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

    <!-- Players by Team -->
    <?php
    $team_players_query = "SELECT t.name as team_name,
                           COUNT(p.id) as player_count,
                           AVG(p.age) as avg_age
                           FROM teams t
                           LEFT JOIN players p ON t.id = p.team_id
                           GROUP BY t.id
                           HAVING player_count > 0
                           ORDER BY player_count DESC, team_name ASC";
    $team_players = executeQuery($conn, $team_players_query, 
        "AVG aggregate with GROUP BY: Count players per team and calculate average age");
    ?>

    <?php if ($team_players && $team_players->num_rows > 0): ?>
    <div class="card" style="margin-top: 2rem;">
        <div class="card-header">
            <h2 class="card-title">📊 Players by Team</h2>
        </div>
        <div class="grid-3">
            <?php while ($team = $team_players->fetch_assoc()): ?>
            <div style="background: var(--light-bg); padding: 1rem; border-radius: 8px;">
                <h4><?php echo htmlspecialchars($team['team_name']); ?></h4>
                <p><strong>Players:</strong> <?php echo $team['player_count']; ?></p>
                <p><strong>Avg Age:</strong> <?php echo number_format($team['avg_age'], 1); ?> years</p>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php
$conn->close();
include '../includes/footer.php';
?>
