<?php
session_start();
include 'includes/config.php';

// Redirect if already logged in
if (isset($_SESSION['admin_id']) || isset($_SESSION['admin_logged_in'])) {
    header("Location: dashboard.php");
    exit();
}

// Redirect to login page
header("Location: login.php");
exit();
?>