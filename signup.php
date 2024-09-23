<?php
require 'db.php';  // Include database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="assets/css/signup.css">
</head>
<body>

    <div class="signup-container">
        <div class="signup-box">
            <h2>Create an Account</h2>
            <form action="signup.php" method="POST" class="signup-form">
                <div class="input-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" required>
                </div>

                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="input-group">
                    <label for="role">Sign up as</label>
                    <select id="role" name="role" required>
                        <option value="customer">Customer</option>
                        <option value="store_owner">Store Owner</option>
                    </select>
                </div>

                <button type="submit" class="btn-signup">Sign Up</button>
                <p class="login-link">Already have an account? <a href="login.php">Login here</a></p>
            </form>
        </div>
    </div>

</body>
</html>

<?php
// PHP code to handle signup
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);  // Hashing password
    $role = $_POST['role'];

    // Check if the email is already registered
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<p>Email is already registered. Please use another email.</p>";
    } else {
        // Insert new user into the database
        $sql = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$password', '$role')";

        if ($conn->query($sql) === TRUE) {
            echo "<p>Registration successful! <a href='login.php'>Login here</a></p>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>
