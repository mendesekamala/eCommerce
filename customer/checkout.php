<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$customer_id = $_SESSION['user_id'];

// Fetch cart data from session or database
$cart_items = $_SESSION['cart'];  // Assuming cart data is stored in session

// Calculate total amount from the cart
$total_amount = 0;
foreach ($cart_items as $item) {
    $total_amount += $item['price'] * $item['quantity'];
}

// When user submits the form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Insert new order
    $sql_order = "INSERT INTO orders (customer_id, total_amount, status) VALUES ('$customer_id', '$total_amount', 'pending')";

    if ($conn->query($sql_order) === TRUE) {
        $order_id = $conn->insert_id;  // Get the last inserted order ID

        // Insert each item from the cart into order_items table
        foreach ($cart_items as $item) {
            $product_name = $item['product_name'];
            $quantity = $item['quantity'];
            $owner_id = $item['owner_id'];

            $sql_order_items = "INSERT INTO order_items (order_id, product, quantity, owner_id) 
                                VALUES ('$order_id', '$product_name', '$quantity', '$owner_id')";

            $conn->query($sql_order_items);
        }

        // Clear cart after successful order
        unset($_SESSION['cart']);

        $success = "Order placed successfully!";
    } else {
        $error = "Error placing order: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/checkout.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="dashboard-container">
        <?php include '../customer-sidebar.php'; ?>  <!-- Include the sidebar -->
        
        <div class="dashboard-content">
            <div class="checkout-container">
                <h1>Checkout</h1>

                <?php if (isset($success)): ?>
                    <p class="message success" id="success-msg">
                        <i class='bx bx-check-circle'></i> <?php echo $success; ?>
                    </p>
                <?php elseif (isset($error)): ?>
                    <p class="message error">
                        <i class='bx bx-error-circle'></i> <?php echo $error; ?>
                    </p>
                <?php endif; ?>

                <div class="payment-methods">
                    <button id="credit-card-btn" class="payment-btn active"><i class='bx bx-credit-card'></i> Credit Card</button>
                    <button id="mobile-payment-btn" class="payment-btn"><i class='bx bx-mobile-alt'></i> Mobile</button>
                </div>

                <!-- Credit Card Payment Form -->
                <form id="credit-card-form" action="checkout.php" method="POST" onsubmit="return validateCreditCard()">
                    <div class="input-group">
                        <label for="amount">Amount</label>
                        <input type="text" id="amount" name="amount" value="<?php echo number_format($total_amount); ?>" readonly>
                    </div>
                    <div class="input-group">
                        <label for="card-number">Card Number</label>
                        <input type="text" id="card-number" name="card_number" placeholder="XXXX XXXX XXXX XXXX" maxlength="19" required>
                    </div>
                    <div class="input-group">
                        <label for="card-name">Card Holder's Name</label>
                        <input type="text" id="card-name" name="card_name" required>
                    </div>
                    <div class="input-group">
                        <label for="expiration-date">Expiration Date</label>
                        <input type="text" id="expiration-date" name="expiration_date" placeholder="MM/YY" maxlength="5" required>
                    </div>
                    <button type="submit" class="btn-submit">Submit Payment</button>
                </form>

                <!-- Mobile Payment Form -->
                <form id="mobile-payment-form" style="display: none;" action="checkout.php" method="POST" onsubmit="return validateMobilePayment()">
                    <div class="input-group">
                        <label for="amount">Amount</label>
                        <input type="text" id="amount" name="amount" value="<?php echo number_format($total_amount); ?>" readonly>
                    </div>
                    <div class="input-group">
                        <label for="network">Network Line</label>
                        <select id="network" name="network">
                            <option value="mpesa">M-Pesa</option>
                            <option value="tigopesa">TigoPesa</option>
                            <option value="airtel">Airtel Money</option>
                            <option value="halopesa">HaloPesa</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <label for="paying-number">Paying Mobile Number</label>
                        <input type="text" id="paying-number" name="paying_number" maxlength="10" required>
                    </div>
                    <div class="input-group">
                        <label for="active-number">Active Mobile Number</label>
                        <input type="text" id="active-number" name="active_number" maxlength="10" required>
                    </div>
                    <button type="submit" class="btn-submit">Submit Payment</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        // Toggle between payment methods
        const creditCardBtn = document.getElementById('credit-card-btn');
        const mobilePaymentBtn = document.getElementById('mobile-payment-btn');
        const creditCardForm = document.getElementById('credit-card-form');
        const mobilePaymentForm = document.getElementById('mobile-payment-form');

        creditCardBtn.addEventListener('click', function() {
            creditCardForm.style.display = 'block';
            mobilePaymentForm.style.display = 'none';
            creditCardBtn.classList.add('active');
            mobilePaymentBtn.classList.remove('active');
        });

        mobilePaymentBtn.addEventListener('click', function() {
            creditCardForm.style.display = 'none';
            mobilePaymentForm.style.display = 'block';
            creditCardBtn.classList.remove('active');
            mobilePaymentBtn.classList.add('active');
        });

        // Validation for credit card inputs
        function validateCreditCard() {
            const cardNumber = document.getElementById('card-number').value;
            const expirationDate = document.getElementById('expiration-date').value;

            const cardNumberPattern = /^\d{4}\s\d{4}\s\d{4}\s\d{4}$/;
            const expirationDatePattern = /^(0[1-9]|1[0-2])\/\d{2}$/;

            if (!cardNumberPattern.test(cardNumber)) {
                alert("Please enter a valid card number in the format XXXX XXXX XXXX XXXX");
                return false;
            }
            if (!expirationDatePattern.test(expirationDate)) {
                alert("Please enter a valid expiration date in the format MM/YY");
                return false;
            }
            return true;
        }

        // Validation for mobile payment inputs
        function validateMobilePayment() {
            const payingNumber = document.getElementById('paying-number').value;
            const activeNumber = document.getElementById('active-number').value;
            const mobilePattern = /^0\d{9}$/;

            if (!mobilePattern.test(payingNumber)) {
                alert("Enter paying mobile number starting with 0 and no spaces, e.g., 0712345678");
                return false;
            }
            if (!mobilePattern.test(activeNumber)) {
                alert("Enter active mobile number starting with 0 and no spaces, e.g., 0712345678");
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
