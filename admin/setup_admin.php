<?php
include('../connection.php');

// Check for existing admin
$result = mysqli_query($connection, "SELECT COUNT(*) as count FROM admin");
if (mysqli_fetch_assoc($result)['count'] > 0) {
    die("Admin account already exists!");
}

// Admin credentials
$admin = [
    'name' => 'admin',
    'email' => 'admin@gmail.com',
    'password' => password_hash('admin123', PASSWORD_DEFAULT),
    'location' => 'Belgaum',
    'address' => 'Main Office'
];

// Insert admin using prepared statement
$stmt = mysqli_prepare($connection, 
    "INSERT INTO admin (name, email, password, location, address) 
     VALUES (?, ?, ?, ?, ?)"
);

if ($stmt && mysqli_stmt_bind_param($stmt, "sssss", 
    $admin['name'], 
    $admin['email'], 
    $admin['password'], 
    $admin['location'], 
    $admin['address']) && 
    mysqli_stmt_execute($stmt)
) {
    echo "Admin account created successfully!<br>";
    echo "Email: " . htmlspecialchars($admin['email']) . "<br>";
    echo "Password: admin123<br>";
    echo "<br>Please save these credentials and delete this file.";
} else {
    echo "Error: " . mysqli_error($connection);
}

mysqli_stmt_close($stmt);
mysqli_close($connection);
?>
