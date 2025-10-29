<?php
require_once '../config/config.php';
require_once '../config/db_connect.php';

$page_title = 'Leagues';
$conn = getConnection();
clearQueryLog();

// Get all active leagues with team and match counts
$leagues_query = "SELECT l.*,
                  COUNT(DISTINCT t.id) as team_count,
                  COUNT(DISTINCT m.id) as match_count
                  FROM leagues l
                  LEFT JOIN teams t ON l.id = t.league_id
                  LEFT JOIN football_matches m ON l.id = m.league_id
                  WHERE l.is_active = 1
                  GROUP BY l.id
                  ORDER BY l.name ASC";
$leagues_result = executeQuery($conn, $leagues_query, "LEFT JOIN with GROUP BY: Get leagues with team and match counts");

include '../includes/header.php';
?>

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-trophy"></i> Football Leagues</h1>
        <p>Explore leagues and their standings across major competitions</p>
    </div>

    <!-- Leagues Grid -->
    <div class="grid grid-3">
        <?php if ($leagues_result && $leagues_result->num_rows > 0): ?>
            <?php while ($league = $leagues_result->fetch_assoc()): ?>
            <div class="card">
                <div class="card-header">
                    <h3><?php echo htmlspecialchars($league['name']); ?></h3>
                </div>
                <div class="card-body">
                    <?php if ($league['country']): ?>
                    <p><i class="fas fa-globe"></i> <strong>Country:</strong> <?php echo htmlspecialchars($league['country']); ?></p>
                    <?php endif; ?>
                    <p><i class="far fa-calendar"></i> <strong>Season:</strong> <?php echo htmlspecialchars($league['season']); ?></p>
                    <p><i class="fas fa-shield-alt"></i> <strong>Teams:</strong> <?php echo $league['team_count']; ?></p>
                    <p><i class="fas fa-futbol"></i> <strong>Matches:</strong> <?php echo $league['match_count']; ?></p>
                </div>
                <a href="league_detail.php?id=<?php echo $league['id']; ?>" class="btn btn-primary" style="margin-top: 1rem;"><i class="fas fa-info-circle"></i> View Details</a>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="card" style="grid-column: 1/-1;">
                <p style="text-align: center; color: var(--text-secondary); padding: 2rem;">
                    No active leagues found. Please add leagues in the admin panel.
                </p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Sample League Standings (if leagues exist) -->
    <?php
    $leagues_result->data_seek(0);
    if ($leagues_result->num_rows > 0):
        $first_league = $leagues_result->fetch_assoc();
        
        // Get standings for first league using complex query
        $standings_query = "SELECT 
                            t.name as team_name,
                            COUNT(DISTINCT m.id) as played,
                            SUM(CASE 
                                WHEN (m.home_team_id = t.id AND m.home_score > m.away_score) OR 
                                     (m.away_team_id = t.id AND m.away_score > m.home_score) 
                                THEN 1 ELSE 0 END) as won,
                            SUM(CASE 
                                WHEN m.home_score = m.away_score 
                                THEN 1 ELSE 0 END) as drawn,
                            SUM(CASE 
                                WHEN (m.home_team_id = t.id AND m.home_score < m.away_score) OR 
                                     (m.away_team_id = t.id AND m.away_score < m.home_score) 
                                THEN 1 ELSE 0 END) as lost,
                            SUM(CASE 
                                WHEN m.home_team_id = t.id THEN m.home_score 
                                ELSE m.away_score END) as goals_for,
                            SUM(CASE 
                                WHEN m.home_team_id = t.id THEN m.away_score 
                                ELSE m.home_score END) as goals_against,
                            (SUM(CASE 
                                WHEN m.home_team_id = t.id THEN m.home_score 
                                ELSE m.away_score END) - 
                             SUM(CASE 
                                WHEN m.home_team_id = t.id THEN m.away_score 
                                ELSE m.home_score END)) as goal_difference,
                            (SUM(CASE 
                                WHEN (m.home_team_id = t.id AND m.home_score > m.away_score) OR 
                                     (m.away_team_id = t.id AND m.away_score > m.home_score) 
                                THEN 3 ELSE 0 END) +
                             SUM(CASE 
                                WHEN m.home_score = m.away_score 
                                THEN 1 ELSE 0 END)) as points
                            FROM teams t
                            LEFT JOIN football_matches m ON 
                                (t.id = m.home_team_id OR t.id = m.away_team_id) 
                                AND m.status = 'finished' 
                                AND m.league_id = {$first_league['id']}
                            WHERE t.league_id = {$first_league['id']}
                            GROUP BY t.id, t.name
                            ORDER BY points DESC, goal_difference DESC, goals_for DESC";
        
        $standings_result = executeQuery($conn, $standings_query, 
            "Complex query with CASE statements and aggregations: Calculate league standings with points, goals, and goal difference");
    ?>
    
    <section class="section">
        <h2 class="section-title"><i class="fas fa-list-ol"></i> <?php echo htmlspecialchars($first_league['name']); ?> - Standings</h2>
        
        <?php if ($standings_result && $standings_result->num_rows > 0): ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Pos</th>
                        <th>Team</th>
                        <th>P</th>
                        <th>W</th>
                        <th>D</th>
                        <th>L</th>
                        <th>GF</th>
                        <th>GA</th>
                        <th>GD</th>
                        <th>Pts</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $position = 1;
                    while ($row = $standings_result->fetch_assoc()): 
                    ?>
                    <tr>
                        <td><strong><?php echo $position++; ?></strong></td>
                        <td><?php echo htmlspecialchars($row['team_name']); ?></td>
                        <td><?php echo $row['played']; ?></td>
                        <td><?php echo $row['won']; ?></td>
                        <td><?php echo $row['drawn']; ?></td>
                        <td><?php echo $row['lost']; ?></td>
                        <td><?php echo $row['goals_for']; ?></td>
                        <td><?php echo $row['goals_against']; ?></td>
                        <td><?php echo $row['goal_difference'] > 0 ? '+' : ''; ?><?php echo $row['goal_difference']; ?></td>
                        <td><strong><?php echo $row['points']; ?></strong></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <p style="text-align: center; color: var(--text-light); padding: 2rem;">
            No standings data available yet. Add some finished matches!
        </p>
        <?php endif; ?>
    </section>
    <?php endif; ?>
</div>

<?php
$conn->close();
include '../includes/footer.php';
?>
