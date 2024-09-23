<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
    header("Location: login.php");  // Redirect to login if not logged in or not customer
    exit();
}

echo "<h1>Welcome, " . $_SESSION['user_name'] . "</h1>";
?>
