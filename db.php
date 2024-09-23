<?php
// Database connection settings
$host = 'localhost';
$user = 'root';  // Use your MySQL username
$pass = '';      // Use your MySQL password
$dbname = 'ecommerce_db';

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
