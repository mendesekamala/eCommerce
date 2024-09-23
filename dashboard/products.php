<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'store_owner') {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = $_POST['product_name'];

    // Remove commas from the price input before storing it
    $price = str_replace(',', '', $_POST['price']);
    
    $description = $_POST['description'];
    $image = $_FILES['image']['name'];
    $target = "../assets/images/" . basename($image);

    // Insert product into the database
    $sql = "INSERT INTO products (owner_id, product_name, price, description, image) 
            VALUES ('{$_SESSION['user_id']}', '$product_name', '$price', '$description', '$image')";

    if ($conn->query($sql) === TRUE) {
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
        $success = "Product added successfully!";
    } else {
        $error = "Error adding product: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>create Products</title>
    <link rel="stylesheet" href="../assets/css/products.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="dashboard-container">
        <?php include '../sidebar.php'; ?>

        <div class="dashboard-content">
            <h1>Add New Product</h1>

            <?php if (isset($success)): ?>
                <p class="message success" id="success-msg">
                    <i class='bx bx-check-circle'></i> <?php echo $success; ?>
                </p>
            <?php elseif (isset($error)): ?>
                <p class="message error">
                    <i class='bx bx-error-circle'></i> <?php echo $error; ?>
                </p>
            <?php endif; ?>

            <form action="products.php" method="POST" enctype="multipart/form-data">
                <div class="input-group">
                    <label for="product_name">Product Name</label>
                    <input type="text" id="product_name" name="product_name" required>
                </div>

                <div class="input-group">
                    <label for="price">Price</label>
                    <input type="text" id="price" name="price" required oninput="formatPrice(this)">
                </div>

                <div class="input-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" required></textarea>
                </div>

                <div class="input-group">
                    <label for="image">Product Image</label>
                    <input type="file" id="image" name="image" required>
                </div>

                <button type="submit" class="btn-submit">Add Product</button>
            </form>
        </div>
    </div>

    <script>
        // Fade out success message after 5 seconds
        setTimeout(function() {
            let successMsg = document.getElementById('success-msg');
            if (successMsg) {
                successMsg.style.transition = "opacity 1s ease";
                successMsg.style.opacity = 0;
                setTimeout(function() {
                    successMsg.style.display = 'none';
                }, 1000);  // Add a bit more time for the fading effect
            }
        }, 5000);  // 5-second timer

        function formatPrice(input) {
            // Remove any non-digit characters (like commas) before formatting
            let value = input.value.replace(/,/g, '');

            // Format the number with commas
            input.value = new Intl.NumberFormat().format(value);
        }
    </script>
</body>
</html>
