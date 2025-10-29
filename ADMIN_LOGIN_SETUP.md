# Admin Login System Setup

## Overview
A secure admin login verification system has been implemented for the Playlytics admin panel.

## Features
✅ **Secure Authentication**: Password hashing using PHP's password_hash()
✅ **Session Management**: Secure session handling with periodic regeneration
✅ **SQL Injection Protection**: Prepared statements for database queries
✅ **Auto-redirect**: Automatically redirects logged-in users from login page
✅ **Logout Functionality**: Complete session destruction on logout
✅ **Professional UI**: Beautiful dark-themed login page with icons

## Setup Instructions

### Step 1: Create the Default Admin User
Visit: `http://localhost/Playlytics/setup/create_admin.php`

This will create a default admin account with:
- **Email**: admin@playlytics.com
- **Password**: admin123

### Step 2: Access Admin Login
Visit: `http://localhost/Playlytics/admin/login.php`

Enter the default credentials to access the admin panel.

### Step 3: Change Default Password (Recommended)
For security, you should change the default password after first login.

## File Structure

```
admin/
├── login.php          # Login page with authentication
├── logout.php         # Logout handler (destroys session)
├── auth_check.php     # Authentication verification (included in all admin pages)
├── index.php          # Admin dashboard (protected)
├── manage_leagues.php # Manage leagues (protected)
├── manage_teams.php   # Manage teams (protected)
├── manage_players.php # Manage players (protected)
└── manage_matches.php # Manage matches (protected)

setup/
└── create_admin.php   # Script to create default admin user
```

## Security Features

### 1. Password Hashing
Passwords are hashed using `password_hash()` with bcrypt algorithm:
```php
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
```

### 2. Prepared Statements
SQL injection protection using prepared statements:
```php
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
```

### 3. Session Security
- Session ID regeneration every 30 minutes
- Secure session variables
- Proper session destruction on logout

### 4. Access Control
All admin pages include `auth_check.php` at the top:
```php
require_once 'auth_check.php';
```

This ensures only logged-in admins can access protected pages.

## Navigation Changes

The header now dynamically shows:
- **When logged out**: "Admin Login" link
- **When logged in**: "Admin Panel" + "Logout" button

## Admin User Management

### Creating Additional Admin Users
You can create additional admin users by:
1. Inserting into the `users` table
2. Setting `is_admin = 1`
3. Using `password_hash()` for the password

Example SQL:
```sql
INSERT INTO users (name, email, password, is_admin) 
VALUES ('John Doe', 'john@example.com', '$2y$10$...', 1);
```

### Password Reset
To reset an admin password:
1. Delete the existing user from database
2. Run `create_admin.php` again, or
3. Update the password directly in database using password_hash()

## Testing the Login System

1. **Test Logout**: Click logout button - should redirect to login page
2. **Test Redirect**: Try accessing `/admin/index.php` without login - should redirect to login
3. **Test Wrong Credentials**: Enter wrong password - should show error message
4. **Test Session**: Login and navigate between admin pages - should stay logged in
5. **Test Auto-redirect**: Visit login page while logged in - should redirect to dashboard

## Default Credentials
```
Email:    admin@playlytics.com
Password: admin123
```

⚠️ **IMPORTANT**: Change the default password after first login for security!

## Troubleshooting

### Can't Login?
- Ensure you've run `create_admin.php` first
- Check that the `users` table exists
- Verify the email and password are correct
- Check PHP error logs for issues

### Session Not Persisting?
- Ensure `session_start()` is called
- Check PHP session configuration
- Verify cookies are enabled in browser

### Redirecting to Login After Logging In?
- Check that `auth_check.php` is properly included
- Verify session variables are being set
- Check for session timeout issues

## Support
For issues or questions, check the project documentation or review the code comments.
