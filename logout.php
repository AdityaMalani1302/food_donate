<?php

session_start();

// Clear all session data
$_SESSION = array();

// Delete the session cookie if it exists
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-3600, '/');
}

// Destroy the session
session_destroy();

// Redirect with absolute path for reliability
header('Location: index.php');
exit();
?>