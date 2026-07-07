<?php
// includes/auth.php
if (!function_exists('isLoggedIn')) {

    session_start();
    require 'db.php';

    function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    function isAdmin() {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }

    // Protect pages
    $current_page = basename($_SERVER['PHP_SELF']);
    $allowed_pages = ['login.php', 'register.php', 'index.php'];

    if (!isLoggedIn() && !in_array($current_page, $allowed_pages)) {
        header("Location: ../login.php");   // Adjusted for admin folder
        exit();
    }
}
?>