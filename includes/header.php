<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>Playlytics</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container">
            <div class="nav-brand">
                <h1><i class="fas fa-futbol"></i> Playlytics</h1>
                <p class="tagline">Professional Football Analytics Platform</p>
            </div>
            <ul class="nav-menu">
                <li><a href="<?php echo BASE_URL; ?>index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="<?php echo BASE_URL; ?>pages/leagues.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'leagues.php' ? 'active' : ''; ?>"><i class="fas fa-trophy"></i> Leagues</a></li>
                <li><a href="<?php echo BASE_URL; ?>pages/players.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'players.php' ? 'active' : ''; ?>"><i class="fas fa-users"></i> Players</a></li>
                <li><a href="<?php echo BASE_URL; ?>pages/query_executor.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'query_executor.php' ? 'active' : ''; ?>"><i class="fas fa-terminal"></i> SQL Executor</a></li>
                <?php 
                // Check if user is logged in as admin
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): 
                ?>
                    <li><a href="<?php echo BASE_URL; ?>admin/index.php" class="admin-link"><i class="fas fa-shield-alt"></i> Admin Panel</a></li>
                    <li><a href="<?php echo BASE_URL; ?>admin/logout.php" class="btn-danger" style="padding: 0.6rem 1.2rem;"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                <?php else: ?>
                    <li><a href="<?php echo BASE_URL; ?>admin/login.php" class="admin-link"><i class="fas fa-sign-in-alt"></i> Admin Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <!-- Floating SQL Viewer Button -->
    <button class="floating-sql-btn" onclick="openSQLViewer()" title="View SQL Queries">
        <i class="fas fa-database"></i>
        <span class="query-count" id="queryCount">0</span>
    </button>

    <main class="main-content">
