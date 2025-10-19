<?php
session_start();

// If the user is not logged in, redirect to the login page
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php"); // index.php = login page
    exit();
}
?>
