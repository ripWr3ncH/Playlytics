<?php
// Check authentication first
require_once 'auth_check.php';

require_once '../config/config.php';
require_once '../config/db_connect.php';

$page_title = 'Manage Leagues';
$conn = getConnection();
clearQueryLog();

$success = $error = '';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    if (executeQuery($conn, "DELETE FROM leagues WHERE id = $id", "DELETE: Remove league")) {
        $success = "League deleted successfully!";
    } else {
        $error = "Error deleting league.";
    }
}

// Handle Create/Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $name = $conn->real_escape_string($_POST['name']);
    $slug = !empty($_POST['slug']) ? $conn->real_escape_string($_POST['slug']) : strtolower(str_replace(' ', '-', $name));
    $country = $conn->real_escape_string($_POST['country']);
    $season = $conn->real_escape_string($_POST['season']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    if ($id > 0) {
        $query = "UPDATE leagues SET name='$name', slug='$slug', country='$country', season='$season', is_active=$is_active WHERE id=$id";
        if (executeQuery($conn, $query, "UPDATE: Modify league")) $success = "League updated!";
    } else {
        $query = "INSERT INTO leagues (name, slug, country, season, is_active) VALUES ('$name', '$slug', '$country', '$season', $is_active)";
        if (executeQuery($conn, $query, "INSERT: Create league")) $success = "League created!";
    }
}

$edit_league = null;
if (isset($_GET['edit'])) {
    $result = executeQuery($conn, "SELECT * FROM leagues WHERE id = " . intval($_GET['edit']), "SELECT: Fetch league");
    $edit_league = $result->fetch_assoc();
}

$leagues = executeQuery($conn, "SELECT * FROM leagues ORDER BY name", "SELECT: Get all leagues");

include '../includes/header.php';
?>

<div class="container">
    <div class="page-header">
        <h1>🏆 Manage Leagues</h1>
        <p>Create, update, and delete leagues</p>
    </div>

    <?php if ($success): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>

    <div class="card">
        <div class="card-header"><h2 class="card-title"><?php echo $edit_league ? 'Edit League' : 'Add New League'; ?></h2></div>
        <form method="POST">
            <?php if ($edit_league): ?><input type="hidden" name="id" value="<?php echo $edit_league['id']; ?>"><?php endif; ?>
            <div class="grid-2">
                <div class="form-group">
                    <label>League Name *</label>
                    <input type="text" name="name" class="form-control" value="<?php echo $edit_league ? htmlspecialchars($edit_league['name']) : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label>Slug</label>
                    <input type="text" name="slug" class="form-control" value="<?php echo $edit_league ? htmlspecialchars($edit_league['slug']) : ''; ?>">
                </div>
            </div>
            <div class="grid-2">
                <div class="form-group">
                    <label>Country</label>
                    <input type="text" name="country" class="form-control" value="<?php echo $edit_league ? htmlspecialchars($edit_league['country']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label>Season *</label>
                    <input type="text" name="season" class="form-control" value="<?php echo $edit_league ? htmlspecialchars($edit_league['season']) : '2024-25'; ?>" required>
                </div>
            </div>
            <div class="form-group">
                <label><input type="checkbox" name="is_active" <?php echo (!$edit_league || $edit_league['is_active']) ? 'checked' : ''; ?>> Active</label>
            </div>
            <button type="submit" class="btn btn-success">💾 <?php echo $edit_league ? 'Update' : 'Create'; ?></button>
            <?php if ($edit_league): ?><a href="manage_leagues.php" class="btn btn-primary">Cancel</a><?php endif; ?>
        </form>
    </div>

    <div class="card">
        <div class="card-header"><h2 class="card-title">All Leagues</h2></div>
        <?php if ($leagues && $leagues->num_rows > 0): ?>
        <table class="data-table">
            <thead><tr><th>ID</th><th>Name</th><th>Slug</th><th>Country</th><th>Season</th><th>Active</th><th>Actions</th></tr></thead>
            <tbody>
                <?php while ($league = $leagues->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $league['id']; ?></td>
                    <td><?php echo htmlspecialchars($league['name']); ?></td>
                    <td><?php echo htmlspecialchars($league['slug']); ?></td>
                    <td><?php echo htmlspecialchars($league['country']); ?></td>
                    <td><?php echo htmlspecialchars($league['season']); ?></td>
                    <td><?php echo $league['is_active'] ? '✓' : '✗'; ?></td>
                    <td>
                        <div class="action-buttons">
                            <a href="?edit=<?php echo $league['id']; ?>" class="btn btn-small btn-primary">Edit</a>
                            <a href="?delete=<?php echo $league['id']; ?>" class="btn btn-small btn-danger" onclick="return confirm('Delete?')">Delete</a>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p style="text-align: center; padding: 2rem;">No leagues found.</p>
        <?php endif; ?>
    </div>
</div>

<?php $conn->close(); include '../includes/footer.php'; ?>
