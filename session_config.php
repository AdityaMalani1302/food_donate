<?php
// Configure error logging
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/food_donate.log');
error_log("Session configuration initialized");

// Set session cookie parameters
session_set_cookie_params([
    'lifetime' => 0,                    // Until browser closes
    'path' => '/',                      // Available across entire domain
    'domain' => '',                     // Current domain only
    'secure' => false,                  // Changed to false since we might not be using HTTPS locally
    'httponly' => true                  // Prevent JavaScript access
]);

// Set session garbage collection
ini_set('session.gc_maxlifetime', 3600);    // 1 hour
ini_set('session.gc_probability', 1);
ini_set('session.gc_divisor', 100);         // 1% chance of GC running

// Set session name
session_name('FOODDONATESESSID');

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Regenerate session ID periodically (every 30 minutes)
if (!isset($_SESSION['last_regeneration']) || (time() - $_SESSION['last_regeneration']) > 1800) {
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
}

// Log current session state
error_log("Current session state: " . print_r($_SESSION, true));
?> 