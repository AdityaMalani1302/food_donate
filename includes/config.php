<?php
// Configuration settings for the Food Donation System

// Email Configuration
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', getenv('SMTP_USERNAME')); // Will be set in hosting environment
define('SMTP_PASSWORD', getenv('SMTP_PASSWORD')); // Will be set in hosting environment
define('SYSTEM_EMAIL', getenv('SYSTEM_EMAIL')); // Will be set in hosting environment
define('ADMIN_EMAIL', getenv('ADMIN_EMAIL')); // Will be set in hosting environment

// Time Zone
define('DEFAULT_TIMEZONE', 'Asia/Kolkata');

// Site Configuration
define('SITE_NAME', 'Food Donation System');
define('SITE_URL', getenv('SITE_URL')); // Will be set based on hosting URL

// Database Configuration
define('DB_HOST', getenv('DB_HOST')); // Will be set by hosting provider
define('DB_USER', getenv('DB_USER')); // Will be set by hosting provider
define('DB_PASS', getenv('DB_PASS')); // Will be set by hosting provider
define('DB_NAME', getenv('DB_NAME')); // Will be set by hosting provider 