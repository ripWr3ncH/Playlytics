<?php
// Check authentication first
require_once 'auth_check.php';

require_once '../config/config.php';
require_once '../config/db_connect.php';

$page_title = 'Admin Dashboard';
$conn = getConnection();
clearQueryLog();

// Get statistics
$stats_query = "SELECT 
                (SELECT COUNT(*) FROM leagues) as total_leagues,
                (SELECT COUNT(*) FROM teams) as total_teams,
                (SELECT COUNT(*) FROM players) as total_players,
                (SELECT COUNT(*) FROM football_matches) as total_matches,
                (SELECT COUNT(*) FROM football_matches WHERE status = 'live') as live_matches,
                (SELECT COUNT(*) FROM users) as total_users";
$stats = executeQuery($conn, $stats_query, "Multiple subqueries: Get comprehensive dashboard statistics");
$stats_data = $stats->fetch_assoc();

// Recent matches
$recent_query = "SELECT m.*, 
                 ht.name as home_team, 
                 at.name as away_team 
                 FROM football_matches m 
                 JOIN teams ht ON m.home_team_id = ht.id 
                 JOIN teams at ON m.away_team_id = at.id 
                 ORDER BY m.created_at DESC 
                 LIMIT 5";
$recent_matches = executeQuery($conn, $recent_query, "Get recently added matches");

include '../includes/header.php';
?>

<div class="container">
    <div class="page-header">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <div>
                <h1><i class="fas fa-tachometer-alt"></i> Admin Dashboard</h1>
                <p>Welcome back, <?php echo htmlspecialchars($_SESSION['admin_name']); ?>! Manage your football analytics system</p>
            </div>
            <a href="logout.php" class="btn btn-danger">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>

    <!-- Statistics Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label"><i class="fas fa-trophy"></i> Total Leagues</div>
            <div class="stat-value"><?php echo $stats_data['total_leagues']; ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-label"><i class="fas fa-shield-alt"></i> Total Teams</div>
            <div class="stat-value"><?php echo $stats_data['total_teams']; ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-label"><i class="fas fa-users"></i> Total Players</div>
            <div class="stat-value"><?php echo $stats_data['total_players']; ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-label"><i class="fas fa-futbol"></i> Total Matches</div>
            <div class="stat-value"><?php echo $stats_data['total_matches']; ?></div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
            <div class="stat-value"><?php echo $stats_data['live_matches']; ?></div>
            <div class="stat-label">Live Matches</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #06b6d4, #0891b2);">
            <div class="stat-value"><?php echo $stats_data['total_users']; ?></div>
            <div class="stat-label">Total Users</div>
        </div>
    </div>

    <!-- Quick Actions -->
    <section class="section">
        <h2 class="section-title"><i class="fas fa-bolt"></i> Quick Actions</h2>
        <div class="grid grid-2">
            <a href="manage_leagues.php" class="btn btn-primary"><i class="fas fa-trophy"></i> Manage Leagues</a>
            <a href="manage_teams.php" class="btn btn-primary"><i class="fas fa-shield-alt"></i> Manage Teams</a>
            <a href="manage_players.php" class="btn btn-primary"><i class="fas fa-users"></i> Manage Players</a>
            <a href="manage_matches.php" class="btn btn-primary"><i class="fas fa-futbol"></i> Manage Matches</a>
        </div>
    </section>

    <!-- Recent Matches -->
    <section class="section">
        <h2 class="section-title"><i class="fas fa-history"></i> Recent Matches</h2>
        <?php if ($recent_matches && $recent_matches->num_rows > 0): ?>
        <div class="table-container">
            <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Home Team</th>
                    <th>Away Team</th>
                    <th>Score</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($match = $recent_matches->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $match['id']; ?></td>
                    <td><?php echo htmlspecialchars($match['home_team']); ?></td>
                    <td><?php echo htmlspecialchars($match['away_team']); ?></td>
                    <td><?php echo $match['home_score']; ?> - <?php echo $match['away_score']; ?></td>
                    <td>
                        <span class="match-status status-<?php echo $match['status']; ?>">
                            <?php echo strtoupper($match['status']); ?>
                        </span>
                    </td>
                    <td><?php echo date('M d, Y', strtotime($match['match_date'])); ?></td>
                    <td>
                        <div class="btn-group">
                            <a href="manage_matches.php?edit=<?php echo $match['id']; ?>" class="btn btn-primary"><i class="fas fa-edit"></i> Edit</a>
                            <a href="manage_matches.php" class="btn btn-secondary"><i class="fas fa-eye"></i> View All</a>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        </div>
        <?php else: ?>
        <p style="text-align: center; color: var(--text-secondary); padding: 2rem;">No matches found.</p>
        <?php endif; ?>
    </section>
</div>

<?php
$conn->close();
include '../includes/footer.php';
?>
