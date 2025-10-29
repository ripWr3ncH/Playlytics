<?php
// Check authentication first
require_once 'auth_check.php';

require_once '../config/config.php';
require_once '../config/db_connect.php';

$page_title = 'Manage Matches';
$conn = getConnection();
clearQueryLog();

$success = '';
$error = '';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $delete_query = "DELETE FROM football_matches WHERE id = $id";
    if (executeQuery($conn, $delete_query, "DELETE statement: Remove match record")) {
        $success = "Match deleted successfully!";
    } else {
        $error = "Error deleting match.";
    }
}

// Handle Create/Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $league_id = intval($_POST['league_id']);
    $home_team_id = intval($_POST['home_team_id']);
    $away_team_id = intval($_POST['away_team_id']);
    $match_date = $_POST['match_date'];
    $home_score = intval($_POST['home_score']);
    $away_score = intval($_POST['away_score']);
    $status = $_POST['status'];
    $minute = !empty($_POST['minute']) ? intval($_POST['minute']) : 'NULL';
    $venue = $_POST['venue'];
    $referee = $_POST['referee'];
    $matchweek = !empty($_POST['matchweek']) ? intval($_POST['matchweek']) : 'NULL';
    
    if ($home_team_id === $away_team_id) {
        $error = "Home and away teams must be different!";
    } else {
        if ($id > 0) {
            // Update
            $query = "UPDATE football_matches SET 
                      league_id = $league_id,
                      home_team_id = $home_team_id,
                      away_team_id = $away_team_id,
                      match_date = '$match_date',
                      home_score = $home_score,
                      away_score = $away_score,
                      status = '$status',
                      minute = $minute,
                      venue = '$venue',
                      referee = '$referee',
                      matchweek = $matchweek
                      WHERE id = $id";
            if (executeQuery($conn, $query, "UPDATE statement: Modify match details")) {
                $success = "Match updated successfully!";
            }
        } else {
            // Insert
            $query = "INSERT INTO football_matches 
                      (league_id, home_team_id, away_team_id, match_date, home_score, away_score, status, minute, venue, referee, matchweek) 
                      VALUES ($league_id, $home_team_id, $away_team_id, '$match_date', $home_score, $away_score, '$status', $minute, '$venue', '$referee', $matchweek)";
            if (executeQuery($conn, $query, "INSERT statement: Create new match record")) {
                $success = "Match created successfully!";
            }
        }
    }
}

// Get edit data
$edit_match = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $edit_query = "SELECT * FROM football_matches WHERE id = $id";
    $result = executeQuery($conn, $edit_query, "SELECT with WHERE: Fetch match for editing");
    $edit_match = $result->fetch_assoc();
}

// Get all matches
$matches_query = "SELECT m.*, 
                  ht.name as home_team, 
                  at.name as away_team,
                  l.name as league_name
                  FROM football_matches m
                  JOIN teams ht ON m.home_team_id = ht.id
                  JOIN teams at ON m.away_team_id = at.id
                  JOIN leagues l ON m.league_id = l.id
                  ORDER BY m.match_date DESC";
$matches = executeQuery($conn, $matches_query, "Multiple JOINs: Get all matches with related data");

// Get leagues and teams for dropdowns
$leagues = executeQuery($conn, "SELECT * FROM leagues ORDER BY name", "Get leagues for dropdown");
$teams = executeQuery($conn, "SELECT * FROM teams ORDER BY name", "Get teams for dropdown");

include '../includes/header.php';
?>

<div class="container">
    <div class="page-header">
        <h1>🎮 Manage Matches</h1>
        <p>Create, update, and delete match records</p>
    </div>

    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <!-- Form -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title"><?php echo $edit_match ? 'Edit Match' : 'Add New Match'; ?></h2>
        </div>
        <form method="POST" action="">
            <?php if ($edit_match): ?>
                <input type="hidden" name="id" value="<?php echo $edit_match['id']; ?>">
            <?php endif; ?>
            
            <div class="grid-2">
                <div class="form-group">
                    <label>League *</label>
                    <select name="league_id" class="form-control" required>
                        <option value="">Select League</option>
                        <?php 
                        $leagues->data_seek(0);
                        while ($league = $leagues->fetch_assoc()): 
                        ?>
                            <option value="<?php echo $league['id']; ?>" 
                                <?php echo ($edit_match && $edit_match['league_id'] == $league['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($league['name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Match Date *</label>
                    <input type="datetime-local" name="match_date" class="form-control" 
                           value="<?php echo $edit_match ? date('Y-m-d\TH:i', strtotime($edit_match['match_date'])) : ''; ?>" required>
                </div>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label>Home Team *</label>
                    <select name="home_team_id" class="form-control" required>
                        <option value="">Select Home Team</option>
                        <?php 
                        $teams->data_seek(0);
                        while ($team = $teams->fetch_assoc()): 
                        ?>
                            <option value="<?php echo $team['id']; ?>"
                                <?php echo ($edit_match && $edit_match['home_team_id'] == $team['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($team['name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Away Team *</label>
                    <select name="away_team_id" class="form-control" required>
                        <option value="">Select Away Team</option>
                        <?php 
                        $teams->data_seek(0);
                        while ($team = $teams->fetch_assoc()): 
                        ?>
                            <option value="<?php echo $team['id']; ?>"
                                <?php echo ($edit_match && $edit_match['away_team_id'] == $team['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($team['name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>

            <div class="grid-3">
                <div class="form-group">
                    <label>Home Score</label>
                    <input type="number" name="home_score" class="form-control" min="0" 
                           value="<?php echo $edit_match ? $edit_match['home_score'] : '0'; ?>">
                </div>

                <div class="form-group">
                    <label>Away Score</label>
                    <input type="number" name="away_score" class="form-control" min="0" 
                           value="<?php echo $edit_match ? $edit_match['away_score'] : '0'; ?>">
                </div>

                <div class="form-group">
                    <label>Minute</label>
                    <input type="number" name="minute" class="form-control" min="0" max="120" 
                           value="<?php echo $edit_match ? $edit_match['minute'] : ''; ?>" placeholder="For live matches">
                </div>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label>Status *</label>
                    <select name="status" class="form-control" required>
                        <option value="scheduled" <?php echo ($edit_match && $edit_match['status'] == 'scheduled') ? 'selected' : ''; ?>>Scheduled</option>
                        <option value="live" <?php echo ($edit_match && $edit_match['status'] == 'live') ? 'selected' : ''; ?>>Live</option>
                        <option value="finished" <?php echo ($edit_match && $edit_match['status'] == 'finished') ? 'selected' : ''; ?>>Finished</option>
                        <option value="postponed" <?php echo ($edit_match && $edit_match['status'] == 'postponed') ? 'selected' : ''; ?>>Postponed</option>
                        <option value="cancelled" <?php echo ($edit_match && $edit_match['status'] == 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Matchweek</label>
                    <input type="number" name="matchweek" class="form-control" min="1" 
                           value="<?php echo $edit_match ? $edit_match['matchweek'] : ''; ?>">
                </div>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label>Venue</label>
                    <input type="text" name="venue" class="form-control" 
                           value="<?php echo $edit_match ? htmlspecialchars($edit_match['venue']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label>Referee</label>
                    <input type="text" name="referee" class="form-control" 
                           value="<?php echo $edit_match ? htmlspecialchars($edit_match['referee']) : ''; ?>">
                </div>
            </div>

            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-success">💾 <?php echo $edit_match ? 'Update' : 'Create'; ?> Match</button>
                <?php if ($edit_match): ?>
                    <a href="manage_matches.php" class="btn btn-primary">Cancel</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Matches List -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">All Matches</h2>
        </div>
        <?php if ($matches && $matches->num_rows > 0): ?>
        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>League</th>
                        <th>Home Team</th>
                        <th>Score</th>
                        <th>Away Team</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($match = $matches->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $match['id']; ?></td>
                        <td><?php echo htmlspecialchars($match['league_name']); ?></td>
                        <td><?php echo htmlspecialchars($match['home_team']); ?></td>
                        <td><strong><?php echo $match['home_score']; ?> - <?php echo $match['away_score']; ?></strong></td>
                        <td><?php echo htmlspecialchars($match['away_team']); ?></td>
                        <td><span class="match-status status-<?php echo $match['status']; ?>"><?php echo strtoupper($match['status']); ?></span></td>
                        <td><?php echo date('M d, Y H:i', strtotime($match['match_date'])); ?></td>
                        <td>
                            <div class="action-buttons">
                                <a href="?edit=<?php echo $match['id']; ?>" class="btn btn-small btn-primary">Edit</a>
                                <a href="?delete=<?php echo $match['id']; ?>" class="btn btn-small btn-danger" 
                                   onclick="return confirm('Delete this match?')">Delete</a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <p style="text-align: center; color: var(--text-light); padding: 2rem;">No matches found.</p>
        <?php endif; ?>
    </div>
</div>

<?php
$conn->close();
include '../includes/footer.php';
?>
