<?php
// Database connection configuration
$servername = "localhost:3306";
$username = "root";
$password = "";
$dbname = "food_donate";

// Create connection
$connection = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($connection->connect_error) {
    // Log the error (in a production environment)
    error_log("Database connection failed: " . $connection->connect_error);
    
    // Show a generic error message
    header('HTTP/1.1 500 Internal Server Error');
    exit('Database connection error');
}

// Set the character set (recommended)
$connection->set_charset("utf8mb4");
?>