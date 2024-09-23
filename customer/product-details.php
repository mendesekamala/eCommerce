<?php
session_start();
require '../db.php';  // Database connection

// Get product details from database
$product_id = $_GET['id'];
$sql = "SELECT * FROM products WHERE id = '$product_id'";
$result = $conn->query($sql);
$product = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $owner_id = $_POST['owner_id'];

    // Add to cart logic
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;  // Update quantity if already in cart
    } else {
        $_SESSION['cart'][$product_id] = [
            'id' => $product['id'],
            'product_name' => $product['product_name'],
            'price' => $product['price'],
            'quantity' => $quantity,
            'owner_id' => $product['owner_id'],
            'image' => $product['image'],
        ];
    }

    $success = "Product added to cart!";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $product['product_name']; ?></title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/product-details.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="dashboard-container">
        <?php include '../customer-sidebar.php'; ?>  <!-- Include the sidebar -->

        <div class="dashboard-content">
            <div class="container">
                <div class="product-details">
                    <img src="../assets/images/<?php echo $product['image']; ?>" alt="<?php echo $product['product_name']; ?>">
                    <div class="details">
                        <h1><?php echo $product['product_name']; ?></h1>
                        <p><?php echo $product['description']; ?></p>
                        <p class="price">$<?php echo number_format($product['price']); ?></p>

                        <form action="product-details.php?id=<?php echo $product['id']; ?>" method="POST">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <input type="hidden" name="owner_id" value="<?php echo $product['owner_id']; ?>">
                            <label for="quantity">Quantity:</label>
                            <input type="number" name="quantity" value="1" min="1" required>
                            <button type="submit" class="btn-add-to-cart">
                                <i class='bx bx-cart'></i> Add to Cart
                            </button>
                        </form>

                        <?php if (isset($success)): ?>
                            <p class="message success"><?php echo $success; ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
