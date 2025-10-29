<?php
// Check authentication first
require_once 'auth_check.php';

require_once '../config/config.php';
require_once '../config/db_connect.php';

$page_title = 'Manage Players';
$conn = getConnection();
clearQueryLog();

$success = $error = '';

if (isset($_GET['delete'])) {
    if (executeQuery($conn, "DELETE FROM players WHERE id = " . intval($_GET['delete']), "DELETE: Remove player")) {
        $success = "Player deleted!";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $name = $conn->real_escape_string($_POST['name']);
    $slug = !empty($_POST['slug']) ? $conn->real_escape_string($_POST['slug']) : strtolower(str_replace(' ', '-', $name));
    $team_id = intval($_POST['team_id']);
    $position = $conn->real_escape_string($_POST['position']);
    $age = !empty($_POST['age']) ? intval($_POST['age']) : 'NULL';
    $nationality = $conn->real_escape_string($_POST['nationality']);
    
    if ($id > 0) {
        $query = "UPDATE players SET name='$name', slug='$slug', team_id=$team_id, position='$position', age=$age, nationality='$nationality' WHERE id=$id";
        if (executeQuery($conn, $query, "UPDATE: Modify player")) $success = "Player updated!";
    } else {
        $query = "INSERT INTO players (name, slug, team_id, position, age, nationality) VALUES ('$name', '$slug', $team_id, '$position', $age, '$nationality')";
        if (executeQuery($conn, $query, "INSERT: Create player")) $success = "Player created!";
    }
}

$edit_player = null;
if (isset($_GET['edit'])) {
    $result = executeQuery($conn, "SELECT * FROM players WHERE id = " . intval($_GET['edit']), "SELECT: Fetch player");
    $edit_player = $result->fetch_assoc();
}

$players = executeQuery($conn, "SELECT p.*, t.name as team_name, l.name as league_name FROM players p JOIN teams t ON p.team_id = t.id JOIN leagues l ON t.league_id = l.id ORDER BY p.name", "Multiple JOINs: Get players with team and league");
$teams = executeQuery($conn, "SELECT * FROM teams ORDER BY name", "SELECT: Get teams");

include '../includes/header.php';
?>

<div class="container">
    <div class="page-header"><h1>👤 Manage Players</h1></div>

    <?php if ($success): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>

    <div class="card">
        <div class="card-header"><h2 class="card-title"><?php echo $edit_player ? 'Edit Player' : 'Add New Player'; ?></h2></div>
        <form method="POST">
            <?php if ($edit_player): ?><input type="hidden" name="id" value="<?php echo $edit_player['id']; ?>"><?php endif; ?>
            <div class="grid-2">
                <div class="form-group">
                    <label>Player Name *</label>
                    <input type="text" name="name" class="form-control" value="<?php echo $edit_player ? htmlspecialchars($edit_player['name']) : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label>Slug</label>
                    <input type="text" name="slug" class="form-control" value="<?php echo $edit_player ? htmlspecialchars($edit_player['slug']) : ''; ?>">
                </div>
            </div>
            <div class="grid-2">
                <div class="form-group">
                    <label>Team *</label>
                    <select name="team_id" class="form-control" required>
                        <option value="">Select Team</option>
                        <?php while ($team = $teams->fetch_assoc()): ?>
                            <option value="<?php echo $team['id']; ?>" <?php echo ($edit_player && $edit_player['team_id'] == $team['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($team['name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Position</label>
                    <select name="position" class="form-control">
                        <option value="">Select Position</option>
                        <?php 
                        $positions = ['Goalkeeper', 'Defender', 'Midfielder', 'Forward'];
                        foreach ($positions as $pos): 
                        ?>
                            <option value="<?php echo $pos; ?>" <?php echo ($edit_player && $edit_player['position'] == $pos) ? 'selected' : ''; ?>><?php echo $pos; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="grid-2">
                <div class="form-group">
                    <label>Age</label>
                    <input type="number" name="age" class="form-control" min="16" max="50" value="<?php echo $edit_player ? $edit_player['age'] : ''; ?>">
                </div>
                <div class="form-group">
                    <label>Nationality</label>
                    <input type="text" name="nationality" class="form-control" value="<?php echo $edit_player ? htmlspecialchars($edit_player['nationality']) : ''; ?>">
                </div>
            </div>
            <button type="submit" class="btn btn-success">💾 <?php echo $edit_player ? 'Update' : 'Create'; ?></button>
            <?php if ($edit_player): ?><a href="manage_players.php" class="btn btn-primary">Cancel</a><?php endif; ?>
        </form>
    </div>

    <div class="card">
        <div class="card-header"><h2 class="card-title">All Players</h2></div>
        <?php if ($players && $players->num_rows > 0): ?>
        <table class="data-table">
            <thead><tr><th>ID</th><th>Name</th><th>Team</th><th>League</th><th>Position</th><th>Age</th><th>Nationality</th><th>Actions</th></tr></thead>
            <tbody>
                <?php while ($player = $players->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $player['id']; ?></td>
                    <td><?php echo htmlspecialchars($player['name']); ?></td>
                    <td><?php echo htmlspecialchars($player['team_name']); ?></td>
                    <td><?php echo htmlspecialchars($player['league_name']); ?></td>
                    <td><?php echo htmlspecialchars($player['position']); ?></td>
                    <td><?php echo $player['age']; ?></td>
                    <td><?php echo htmlspecialchars($player['nationality']); ?></td>
                    <td>
                        <div class="action-buttons">
                            <a href="?edit=<?php echo $player['id']; ?>" class="btn btn-small btn-primary">Edit</a>
                            <a href="?delete=<?php echo $player['id']; ?>" class="btn btn-small btn-danger" onclick="return confirm('Delete?')">Delete</a>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>

<?php $conn->close(); include '../includes/footer.php'; ?>
