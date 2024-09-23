<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'store_owner') {
    header("Location: ../login.php");  // Redirect to login if not store owner
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store Owner Dashboard</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>  <!-- Added Boxicons -->
</head>
<body>
    <div class="dashboard-container">
        <?php include '../sidebar.php'; ?>  <!-- Include the sidebar -->

        <div class="dashboard-content">
            <h1>Store Owner Dashboard</h1>
            <p>Use the links on the left to manage your products or view orders.</p>
        </div>
    </div>
</body>
</html>
