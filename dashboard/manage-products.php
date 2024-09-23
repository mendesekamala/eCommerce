<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'store_owner') {
    header("Location: ../login.php");
    exit();
}

// Delete product logic
if (isset($_GET['delete'])) {
    $product_id = $_GET['delete'];
    $sql = "DELETE FROM products WHERE id = $product_id AND owner_id = {$_SESSION['user_id']}";
    $conn->query($sql);
}

// Fetch all products by the store owner
$sql = "SELECT * FROM products WHERE owner_id = {$_SESSION['user_id']}";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <link rel="stylesheet" href="../assets/css/manage-products.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'> <!-- Boxicons -->
</head>
<body>
    <div class="dashboard-container">
        <?php include '../sidebar.php'; ?>

        <div class="dashboard-content">
            <h1>Manage Products</h1>

            <!-- Product List -->
            <table class="products-table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($product = $result->fetch_assoc()): ?>
                    <tr>
                        <td><img src="../assets/images/<?php echo $product['image']; ?>" alt="Product Image" class="product-img"></td>
                        <td><?php echo $product['product_name']; ?></td>
                        <td><?php echo $product['price']; ?></td>
                        <td>
                            <a href="edit-product.php?id=<?php echo $product['id']; ?>" class="edit-btn">
                                <i class='bx bx-edit'></i> Edit
                            </a>
                            <a href="manage-products.php?delete=<?php echo $product['id']; ?>" class="delete-btn">
                                <i class='bx bx-trash'></i> Delete
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
