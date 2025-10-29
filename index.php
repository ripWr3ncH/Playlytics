<?php
require_once 'config/config.php';
require_once 'config/db_connect.php';

$page_title = 'Home - Football Match Analytics';
$conn = getConnection();

// Clear query log for new page
clearQueryLog();

// Get live matches
$live_query = "SELECT m.*, 
                ht.name as home_team, 
                at.name as away_team, 
                l.name as league_name 
                FROM football_matches m 
                INNER JOIN teams ht ON m.home_team_id = ht.id 
                INNER JOIN teams at ON m.away_team_id = at.id 
                INNER JOIN leagues l ON m.league_id = l.id 
                WHERE m.status = 'live' 
                ORDER BY m.match_date DESC";
$live_matches = executeQuery($conn, $live_query, "INNER JOIN: Fetch all live matches with team names and league information");

// Get recent finished matches
$recent_query = "SELECT m.*, 
                  ht.name as home_team, 
                  at.name as away_team, 
                  l.name as league_name 
                  FROM football_matches m 
                  INNER JOIN teams ht ON m.home_team_id = ht.id 
                  INNER JOIN teams at ON m.away_team_id = at.id 
                  INNER JOIN leagues l ON m.league_id = l.id 
                  WHERE m.status = 'finished' 
                  ORDER BY m.match_date DESC 
                  LIMIT 6";
$recent_matches = executeQuery($conn, $recent_query, "SELECT with LIMIT: Get 6 most recent finished matches");

// Get upcoming scheduled matches
$upcoming_query = "SELECT m.*, 
                    ht.name as home_team, 
                    at.name as away_team, 
                    l.name as league_name 
                    FROM football_matches m 
                    INNER JOIN teams ht ON m.home_team_id = ht.id 
                    INNER JOIN teams at ON m.away_team_id = at.id 
                    INNER JOIN leagues l ON m.league_id = l.id 
                    WHERE m.status = 'scheduled' 
                    AND m.match_date >= NOW() 
                    ORDER BY m.match_date ASC 
                    LIMIT 6";
$upcoming_matches = executeQuery($conn, $upcoming_query, "WHERE with date comparison: Get upcoming scheduled matches");

// Get statistics
$stats_query = "SELECT 
                (SELECT COUNT(*) FROM leagues WHERE is_active = 1) as active_leagues,
                (SELECT COUNT(*) FROM teams) as total_teams,
                (SELECT COUNT(*) FROM players) as total_players,
                (SELECT COUNT(*) FROM football_matches) as total_matches";
$stats = executeQuery($conn, $stats_query, "Subqueries in SELECT: Get aggregated statistics from multiple tables");
$stats_data = $stats->fetch_assoc();

include 'includes/header.php';
?>

<div class="container">
    <!-- Page Header -->
    <div class="page-header">
        <h1><i class="fas fa-chart-line"></i> Welcome to Playlytics</h1>
        <p>Professional Football Match Analytics & SQL Query Showcase Platform</p>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label"><i class="fas fa-trophy"></i> Active Leagues</div>
            <div class="stat-value"><?php echo $stats_data['active_leagues']; ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-label"><i class="fas fa-shield-alt"></i> Teams</div>
            <div class="stat-value"><?php echo $stats_data['total_teams']; ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-label"><i class="fas fa-users"></i> Players</div>
            <div class="stat-value"><?php echo $stats_data['total_players']; ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-label"><i class="fas fa-futbol"></i> Total Matches</div>
            <div class="stat-value"><?php echo $stats_data['total_matches']; ?></div>
        </div>
    </div>

    <!-- Live Matches Section -->
    <?php if ($live_matches && $live_matches->num_rows > 0): ?>
    <section class="section">
        <h2 class="section-title"><i class="fas fa-circle" style="color: var(--danger-color); font-size: 0.8rem;"></i> Live Matches</h2>
        <div class="matches-grid">
            <?php while ($match = $live_matches->fetch_assoc()): ?>
            <div class="match-card" data-match-id="<?php echo $match['id']; ?>">
                <div class="match-header">
                    <span class="match-status status-live">LIVE <?php echo $match['minute'] ? $match['minute'] . "'" : ''; ?></span>
                    <span><?php echo $match['league_name']; ?></span>
                </div>
                <div class="match-teams">
                    <div class="team">
                        <div class="team-name"><?php echo htmlspecialchars($match['home_team']); ?></div>
                        <div class="score home-score"><?php echo $match['home_score']; ?></div>
                    </div>
                    <div class="vs">-</div>
                    <div class="team">
                        <div class="team-name"><?php echo htmlspecialchars($match['away_team']); ?></div>
                        <div class="score away-score"><?php echo $match['away_score']; ?></div>
                    </div>
                </div>
                <div class="match-info">
                <div class="match-footer">
                    <span><i class="far fa-calendar-alt"></i> <?php echo date('M d, Y', strtotime($match['match_date'])); ?></span>
                    <?php if ($match['venue']): ?>
                    <span><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($match['venue']); ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- Recent Matches Section -->
    <section class="section">
        <h2 class="section-title"><i class="fas fa-check-circle"></i> Recent Results</h2>
        <?php if ($recent_matches && $recent_matches->num_rows > 0): ?>
        <div class="matches-grid">
            <?php while ($match = $recent_matches->fetch_assoc()): ?>
            <div class="match-card">
                <div class="match-header">
                    <span class="match-status status-finished">FINISHED</span>
                    <span><?php echo $match['league_name']; ?></span>
                </div>
                <div class="match-teams">
                    <div class="team">
                        <div class="team-name"><?php echo htmlspecialchars($match['home_team']); ?></div>
                        <div class="score"><?php echo $match['home_score']; ?></div>
                    </div>
                    <div class="vs">-</div>
                    <div class="team">
                        <div class="team-name"><?php echo htmlspecialchars($match['away_team']); ?></div>
                        <div class="score"><?php echo $match['away_score']; ?></div>
                    </div>
                </div>
                <div class="match-footer">
                    <span><i class="far fa-calendar-alt"></i> <?php echo date('M d, Y', strtotime($match['match_date'])); ?></span>
                    <?php if ($match['venue']): ?>
                    <span><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($match['venue']); ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        <?php else: ?>
        <p style="text-align: center; color: var(--text-secondary); padding: 2rem;">No recent matches found. Add some matches in the admin panel!</p>
        <?php endif; ?>
    </section>

    <!-- Upcoming Matches Section -->
    <section class="section">
        <h2 class="section-title"><i class="far fa-clock"></i> Upcoming Matches</h2>
        <?php if ($upcoming_matches && $upcoming_matches->num_rows > 0): ?>
        <div class="matches-grid">
            <?php while ($match = $upcoming_matches->fetch_assoc()): ?>
            <div class="match-card">
                <div class="match-header">
                    <span class="match-status status-scheduled">SCHEDULED</span>
                    <span><?php echo $match['league_name']; ?></span>
                </div>
                <div class="match-teams">
                    <div class="team">
                        <div class="team-name"><?php echo htmlspecialchars($match['home_team']); ?></div>
                    </div>
                    <div class="vs">VS</div>
                    <div class="team">
                        <div class="team-name"><?php echo htmlspecialchars($match['away_team']); ?></div>
                    </div>
                </div>
                <div class="match-footer">
                    <span><i class="far fa-calendar-alt"></i> <?php echo date('M d, Y - H:i', strtotime($match['match_date'])); ?></span>
                    <?php if ($match['venue']): ?>
                    <span><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($match['venue']); ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        <?php else: ?>
        <p style="text-align: center; color: var(--text-secondary); padding: 2rem;">No upcoming matches scheduled.</p>
        <?php endif; ?>
    </section>

    <!-- Quick Actions -->
    <section class="card" style="margin-top: 2rem;">
        <h2 class="card-title">🚀 Quick Actions</h2>
        <div class="grid-3" style="margin-top: 1rem;">
            <a href="pages/leagues.php" class="btn btn-primary">View Leagues</a>
            <a href="pages/players.php" class="btn btn-primary">View Players</a>
            <a href="pages/query_executor.php" class="btn btn-success">SQL Executor</a>
            <a href="admin/index.php" class="btn btn-primary">Admin Panel</a>
        </div>
    </section>
</div>

<?php
$conn->close();
include 'includes/footer.php';
?>
