<?php
include('db.php');

// Start the session
session_start();

// Check User Login
if (!isset($_SESSION['userId'])) {
    header('location:' . $admin_url . '/login/');
    exit();
}

// Set session timeout
$session_timeout = 600; // 10 minutes in seconds

// Check if session variables are set
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $session_timeout)) {
    // Session has expired, destroy session and start a new one
    session_unset();     // Unset all session variables
    session_destroy();   // Destroy the session
    header('location:' . $admin_url . '/login/');
    exit();
}

// Update last activity time
$_SESSION['LAST_ACTIVITY'] = time();

// Regenerate session ID periodically (e.g., every 5 minutes)
if (!isset($_SESSION['CREATED']) || (time() - $_SESSION['CREATED'] > 300)) {
    // Regenerate session ID
    session_regenerate_id(true);
    // Update creation time
    $_SESSION['CREATED'] = time();
}
