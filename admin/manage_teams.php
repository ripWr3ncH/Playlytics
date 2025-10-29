<?php
// Check authentication first
require_once 'auth_check.php';

require_once '../config/config.php';
require_once '../config/db_connect.php';

$page_title = 'Manage Teams';
$conn = getConnection();
clearQueryLog();

$success = $error = '';

if (isset($_GET['delete'])) {
    if (executeQuery($conn, "DELETE FROM teams WHERE id = " . intval($_GET['delete']), "DELETE: Remove team")) {
        $success = "Team deleted!";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $name = $conn->real_escape_string($_POST['name']);
    $slug = !empty($_POST['slug']) ? $conn->real_escape_string($_POST['slug']) : strtolower(str_replace(' ', '-', $name));
    $league_id = intval($_POST['league_id']);
    $city = $conn->real_escape_string($_POST['city']);
    $founded = !empty($_POST['founded']) ? intval($_POST['founded']) : 'NULL';
    
    if ($id > 0) {
        $query = "UPDATE teams SET name='$name', slug='$slug', league_id=$league_id, city='$city', founded=$founded WHERE id=$id";
        if (executeQuery($conn, $query, "UPDATE: Modify team")) $success = "Team updated!";
    } else {
        $query = "INSERT INTO teams (name, slug, league_id, city, founded) VALUES ('$name', '$slug', $league_id, '$city', $founded)";
        if (executeQuery($conn, $query, "INSERT: Create team")) $success = "Team created!";
    }
}

$edit_team = null;
if (isset($_GET['edit'])) {
    $result = executeQuery($conn, "SELECT * FROM teams WHERE id = " . intval($_GET['edit']), "SELECT: Fetch team");
    $edit_team = $result->fetch_assoc();
}

$teams = executeQuery($conn, "SELECT t.*, l.name as league_name FROM teams t JOIN leagues l ON t.league_id = l.id ORDER BY t.name", "JOIN: Get teams with leagues");
$leagues = executeQuery($conn, "SELECT * FROM leagues ORDER BY name", "SELECT: Get leagues");

include '../includes/header.php';
?>

<div class="container">
    <div class="page-header"><h1>⚽ Manage Teams</h1></div>

    <?php if ($success): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>

    <div class="card">
        <div class="card-header"><h2 class="card-title"><?php echo $edit_team ? 'Edit Team' : 'Add New Team'; ?></h2></div>
        <form method="POST">
            <?php if ($edit_team): ?><input type="hidden" name="id" value="<?php echo $edit_team['id']; ?>"><?php endif; ?>
            <div class="grid-2">
                <div class="form-group">
                    <label>Team Name *</label>
                    <input type="text" name="name" class="form-control" value="<?php echo $edit_team ? htmlspecialchars($edit_team['name']) : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label>Slug</label>
                    <input type="text" name="slug" class="form-control" value="<?php echo $edit_team ? htmlspecialchars($edit_team['slug']) : ''; ?>">
                </div>
            </div>
            <div class="grid-3">
                <div class="form-group">
                    <label>League *</label>
                    <select name="league_id" class="form-control" required>
                        <option value="">Select League</option>
                        <?php while ($league = $leagues->fetch_assoc()): ?>
                            <option value="<?php echo $league['id']; ?>" <?php echo ($edit_team && $edit_team['league_id'] == $league['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($league['name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>City</label>
                    <input type="text" name="city" class="form-control" value="<?php echo $edit_team ? htmlspecialchars($edit_team['city']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label>Founded Year</label>
                    <input type="number" name="founded" class="form-control" min="1800" max="<?php echo date('Y'); ?>" value="<?php echo $edit_team ? $edit_team['founded'] : ''; ?>">
                </div>
            </div>
            <button type="submit" class="btn btn-success">💾 <?php echo $edit_team ? 'Update' : 'Create'; ?></button>
            <?php if ($edit_team): ?><a href="manage_teams.php" class="btn btn-primary">Cancel</a><?php endif; ?>
        </form>
    </div>

    <div class="card">
        <div class="card-header"><h2 class="card-title">All Teams</h2></div>
        <?php if ($teams && $teams->num_rows > 0): ?>
        <table class="data-table">
            <thead><tr><th>ID</th><th>Name</th><th>League</th><th>City</th><th>Founded</th><th>Actions</th></tr></thead>
            <tbody>
                <?php while ($team = $teams->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $team['id']; ?></td>
                    <td><?php echo htmlspecialchars($team['name']); ?></td>
                    <td><?php echo htmlspecialchars($team['league_name']); ?></td>
                    <td><?php echo htmlspecialchars($team['city']); ?></td>
                    <td><?php echo $team['founded']; ?></td>
                    <td>
                        <div class="action-buttons">
                            <a href="?edit=<?php echo $team['id']; ?>" class="btn btn-small btn-primary">Edit</a>
                            <a href="?delete=<?php echo $team['id']; ?>" class="btn btn-small btn-danger" onclick="return confirm('Delete?')">Delete</a>
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
