<?php
// Strict error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Constants for admin credentials
define('ADMIN_EMAIL', 'fooddonate12@gmail.com');
define('ADMIN_PASSWORD', 'fooddonate@12');
define('ADMIN_LOCATION', 'Belgaum');

try {
    // Include database connection
    require_once('../connection.php');

    // Verify database connection
    if (!$connection instanceof mysqli) {
        throw new Exception("Database connection failed");
    }

    // Check for existing admin using prepared statement
    $checkStmt = mysqli_prepare($connection, "SELECT COUNT(*) as count FROM admin");
    if (!$checkStmt) {
        throw new Exception("Failed to prepare check statement: " . mysqli_error($connection));
    }

    mysqli_stmt_execute($checkStmt);
    $result = mysqli_stmt_get_result($checkStmt);
    $count = mysqli_fetch_assoc($result)['count'];
    mysqli_stmt_close($checkStmt);

    if ($count > 0) {
        throw new Exception("Admin account already exists!");
    }

    // Admin data array
    $admin = [
        'name' => 'admin',
        'email' => ADMIN_EMAIL,
        'password' => password_hash(ADMIN_PASSWORD, PASSWORD_DEFAULT, ['cost' => 12]),
        'location' => ADMIN_LOCATION,
        'address' => 'Main Office'
    ];

    // Prepare insert statement
    $insertStmt = mysqli_prepare($connection, 
        "INSERT INTO admin (name, email, password, location, address) 
         VALUES (?, ?, ?, ?, ?)"
    );

    if (!$insertStmt) {
        throw new Exception("Failed to prepare insert statement: " . mysqli_error($connection));
    }

    // Bind parameters and execute
    if (!mysqli_stmt_bind_param($insertStmt, "sssss", 
        $admin['name'], 
        $admin['email'], 
        $admin['password'], 
        $admin['location'], 
        $admin['address'])) {
        throw new Exception("Failed to bind parameters: " . mysqli_stmt_error($insertStmt));
    }

    if (!mysqli_stmt_execute($insertStmt)) {
        throw new Exception("Failed to create admin account: " . mysqli_stmt_error($insertStmt));
    }

    // Success message with security headers
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: DENY');
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Admin Setup Success</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; }
            .success { color: green; }
            .warning { color: red; font-weight: bold; }
        </style>
    </head>
    <body>
        <h2 class="success">Admin account created successfully!</h2>
        <p>Email: <?= htmlspecialchars($admin['email']) ?></p>
        <p>Password: <?= htmlspecialchars(ADMIN_PASSWORD) ?></p>
        <p class="warning">Please save these credentials and delete this file immediately!</p>
    </body>
    </html>
    <?php

} catch (Exception $e) {
    header('HTTP/1.1 500 Internal Server Error');
    echo "Error: " . htmlspecialchars($e->getMessage());
} finally {
    // Clean up
    if (isset($insertStmt)) {
        mysqli_stmt_close($insertStmt);
    }
    if (isset($connection)) {
        mysqli_close($connection);
    }
}
?>
