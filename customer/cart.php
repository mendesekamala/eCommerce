<?php
session_start();

// Remove item from cart
if (isset($_GET['remove'])) {
    $product_id = $_GET['remove'];
    unset($_SESSION['cart'][$product_id]);
}

// Update item quantities
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($_POST['quantities'] as $product_id => $quantity) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

// Calculate total price
$total = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
}

$grandTotal = $total
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>  <!-- Added Boxicons -->
    <style>
        /* Cart Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .cart-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .cart-item img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
        }

        .cart-item-details {
            flex: 1;
            margin-left: 15px;
        }

        .cart-item-details h4 {
            font-size: 16px;
            margin: 0;
        }

        .cart-item-details p {
            margin: 5px 0;
            color: #888;
            font-size: 14px;
        }

        .cart-item-quantity {
            display: flex;
            align-items: center;
        }

        .cart-item-quantity input[type="number"] {
            width: 50px;
            padding: 5px;
            margin-right: 10px;
            font-size: 16px;
        }

        .cart-item-remove {
            color: red;
            cursor: pointer;
        }

        .cart-summary {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .cart-summary h3 {
            margin-bottom: 10px;
        }

        .cart-summary p {
            margin-bottom: 5px;
            color: #888;
        }

        .cart-summary .total-price {
            font-size: 20px;
            font-weight: bold;
            margin-top: 10px;
        }

        .btn-update {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s ease;
            cursor: pointer;
        }

        .btn-checkout {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s ease;
            display: block;
            text-align: center;
            margin-top: 20px;
        }

        .btn-checkout:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <div class="dashboard-container">
        <?php include '../customer-sidebar.php'; ?>  <!-- Include the sidebar -->
        
        <div class="dashboard-content">
            <div class="cart-container">
                <h2>My Cart</h2>

                <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                    <form action="cart.php" method="POST">
                        <?php foreach ($_SESSION['cart'] as $product_id => $item): ?>
                            <div class="cart-item">
                                <img src="../assets/images/<?php echo $item['image']; ?>" alt="Product Image">
                                <div class="cart-item-details">
                                    <h4><?php echo $item['product_name']; ?></h4>
                                    <p>Tsh <?php echo number_format($item['price']); ?></p>
                                </div>
                                <div class="cart-item-quantity">
                                    <input type="number" name="quantities[<?php echo $product_id; ?>]" value="<?php echo $item['quantity']; ?>" min="1">
                                    <a href="cart.php?remove=<?php echo $product_id; ?>" class="cart-item-remove"><i class='bx bx-trash'></i></a>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <div class="cart-summary">
                            <h3 class="total-price">Total: Tsh <?php echo number_format($grandTotal); ?></h3>
                            <button type="submit" class="btn-update">Update Cart</button>
                            <a href="checkout.php" class="btn-checkout">Proceed to Checkout</a>
                        </div>
                    </form>
                <?php else: ?>
                    <p>Your cart is empty.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

</body>
</html>
