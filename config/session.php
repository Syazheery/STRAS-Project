<?php
// Set session configuration
ini_set('session.cookie_lifetime', 86400); // 1 day lifetime
ini_set('session.gc_maxlifetime', 86400);

// Create a dedicated sessions directory if it doesn't exist
$sessionPath = __DIR__ . '/../sessions';
if (!file_exists($sessionPath)) {
    mkdir($sessionPath, 0700, true);
}

// Set custom session save path
session_save_path($sessionPath);

// Start the session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
?>