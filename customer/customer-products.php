<?php
session_start();
require '../db.php';  // Include database connection

// Fetch products from the database
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Products</title>
    <link rel="stylesheet" href="../assets/css/customer-products.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="dashboard-container">
        <?php include '../customer-sidebar.php'; ?>  <!-- Include the sidebar -->

        <div class="dashboard-content">
            <div class="container">
                <h1>Our Products</h1>
                <div class="product-grid">
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <div class="product-card">
                                <img src="../assets/images/<?php echo $row['image']; ?>" alt="<?php echo $row['product_name']; ?>">
                                <h3><?php echo $row['product_name']; ?></h3>
                                <p><?php echo $row['description']; ?></p>
                                <p class="price">Tsh <?php echo number_format($row['price']); ?></p>
                                <a href="product-details.php?id=<?php echo $row['id']; ?>" class="btn-details">
                                    <i class='bx bx-cart'></i> add to cart
                                </a>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>No products available at the moment.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
