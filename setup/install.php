<?php
// ==============================================================
// PLAYLYTICS - Database Installation Script
// ==============================================================

require_once '../config/config.php';

// Create database if not exists
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<h2>Playlytics Database Installation</h2>";
echo "<pre>";

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;
if ($conn->query($sql) === TRUE) {
    echo "✓ Database created successfully\n";
} else {
    echo "✗ Error creating database: " . $conn->error . "\n";
}

$conn->select_db(DB_NAME);

// Read and execute create_table.sql
$sql_file = file_get_contents('../reference/create_table.sql');

// Split by semicolon and execute each statement
$statements = array_filter(array_map('trim', explode(';', $sql_file)));

foreach ($statements as $statement) {
    if (empty($statement) || strpos($statement, '--') === 0) {
        continue;
    }
    
    // Skip CREATE DATABASE and USE statements
    if (stripos($statement, 'CREATE DATABASE') !== false || stripos($statement, 'USE ') !== false) {
        continue;
    }
    
    if ($conn->query($statement) === TRUE) {
        // Extract table name from CREATE TABLE statement
        if (preg_match('/CREATE TABLE\s+(?:IF NOT EXISTS\s+)?(\w+)/i', $statement, $matches)) {
            echo "✓ Table '{$matches[1]}' created successfully\n";
        } else if (stripos($statement, 'CREATE INDEX') !== false) {
            echo "✓ Index created successfully\n";
        }
    } else {
        echo "✗ Error: " . $conn->error . "\n";
        echo "Statement: " . substr($statement, 0, 100) . "...\n";
    }
}

// Create admin user
$admin_password = password_hash(ADMIN_PASSWORD, PASSWORD_DEFAULT);
$check_admin = $conn->query("SELECT id FROM users WHERE email = '" . ADMIN_EMAIL . "'");

if ($check_admin->num_rows == 0) {
    $sql = "INSERT INTO users (name, email, password, is_admin) VALUES 
            ('Admin User', '" . ADMIN_EMAIL . "', '$admin_password', 1)";
    
    if ($conn->query($sql) === TRUE) {
        echo "✓ Admin user created successfully\n";
        echo "  Email: " . ADMIN_EMAIL . "\n";
        echo "  Password: " . ADMIN_PASSWORD . "\n";
    } else {
        echo "✗ Error creating admin user: " . $conn->error . "\n";
    }
} else {
    echo "✓ Admin user already exists\n";
}

echo "\n";
echo "===========================================\n";
echo "Installation Complete!\n";
echo "===========================================\n";
echo "Next step: Run seed_data.php to populate with dummy data\n";
echo "</pre>";

$conn->close();
?>
