<?php
// ==============================================================
// PLAYLYTICS - Create Default Admin User
// ==============================================================

require_once '../config/config.php';
require_once '../config/db_connect.php';

echo "<h2>Playlytics - Create Default Admin User</h2>";
echo "<pre>";

$conn = getConnection();

// Check if admin user already exists
$check_query = "SELECT id FROM users WHERE email = 'admin@playlytics.com'";
$result = $conn->query($check_query);

if ($result->num_rows > 0) {
    echo "⚠️  Admin user already exists!\n\n";
    echo "If you want to reset the password, please delete the existing admin user first.\n";
} else {
    // Create default admin user
    $name = "Admin";
    $email = "admin@playlytics.com";
    $password = "admin123";
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $is_admin = 1;
    
    $insert_query = "INSERT INTO users (name, email, password, is_admin) 
                     VALUES (?, ?, ?, ?)";
    
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("sssi", $name, $email, $hashed_password, $is_admin);
    
    if ($stmt->execute()) {
        echo "✓ Default admin user created successfully!\n\n";
        echo "==============================================\n";
        echo "Admin Login Credentials:\n";
        echo "==============================================\n";
        echo "Email:    admin@playlytics.com\n";
        echo "Password: admin123\n";
        echo "==============================================\n\n";
        echo "⚠️  IMPORTANT: Please change the password after first login!\n\n";
        echo "You can now login at: " . BASE_URL . "admin/login.php\n";
    } else {
        echo "✗ Error creating admin user: " . $conn->error . "\n";
    }
    
    $stmt->close();
}

$conn->close();

echo "\n<a href='../admin/login.php' style='display: inline-block; margin-top: 20px; padding: 10px 20px; background: #10b981; color: white; text-decoration: none; border-radius: 5px;'>Go to Admin Login</a>";
echo "</pre>";
?>
