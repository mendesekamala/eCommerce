<!-- sidebar.php -->
<div class="menu-btn" onclick="toggleSidebar()">
    <i class='bx bx-menu'></i> 
</div>

<nav class="dashboard-nav" id="sidebar">
    <div class="header">
        <h2>Welcome, <?php echo $_SESSION['user_name']; ?>!</h2>
        <i class='bx bx-x close-btn' id="close-btn" onclick="toggleSidebar()"></i>  <!-- Close icon -->
    </div>
    <ul>
        <li><a href="index.php"><i class='bx bx-grid-alt'></i> Dashboard</a></li>
        <li><a href="products.php"><i class='bx bx-box'></i> Manage Products</a></li>
        <li><a href="orders.php"><i class='bx bx-receipt'></i> View Orders</a></li>
        <li><a href="../logout.php"><i class='bx bx-user-circle'></i> Logout</a></li>
    </ul>
</nav>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('active');
    }
</script>
