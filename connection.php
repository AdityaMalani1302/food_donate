<?php
// Load configuration from a separate file or environment variables
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "food_donate";

$connection = mysqli_init();
mysqli_ssl_set($connection, NULL, NULL, NULL, NULL, NULL);
mysqli_real_connect(
    $connection,
    getenv('DB_HOST'),
    getenv('DB_USER'),
    getenv('DB_PASS'),
    getenv('DB_NAME'),
    3306,
    NULL,
    MYSQLI_CLIENT_SSL
);

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set the character set (recommended)
$connection->set_charset("utf8mb4");
?>