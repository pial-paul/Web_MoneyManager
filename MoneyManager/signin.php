<?php
// Start the session
session_start();

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$database = "money_management";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usernameOrEmail = $_POST['username_or_email'];
    $password = $_POST['password'];

    // SQL query to fetch user by username or email
    $sql = "SELECT id, password FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind the input parameters
        $stmt->bind_param("ss", $usernameOrEmail, $usernameOrEmail);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if a user exists with the given username or email
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Verify the entered password with the stored hashed password
            if (password_verify($password, $row['password'])) {
                // Set session variable on successful login
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['logged_in'] = true;

                // Redirect to the homepage
                echo "<script>alert('Login successful!'); window.location.href='index.php';</script>";
            } else {
                echo "<script>alert('Invalid password. Please try again.');</script>";
            }
        } else {
            echo "<script>alert('No user found with that username or email.');</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Error preparing statement: " . $conn->error . "');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <form class="sign-in-form" method="POST" action="signin.php">
        <h2>Sign In</h2>
        <div class="input-group">
            <label for="username">Username or Email</label>
            <input
                type="text"
                id="username"
                name="username_or_email"
                placeholder="Enter your username or email"
                required
            />
        </div>
        <div class="input-group">
            <label for="password">Password</label>
            <input
                type="password"
                id="password"
                name="password"
                placeholder="Enter your password"
                required
            />
            <a href="forgot-password.html" class="forgot-password">Forgot Password?</a>
        </div>
        <div class="options">
            <label><input type="checkbox" /> Remember Me</label>
        </div>
        <button type="submit" class="btn">Login</button>
        <p class="sign-up-link">
            Don't have an account? <a href="signup.php">Sign Up</a> <br />
            Go to <a href="index.php">Home</a>
        </p>
    </form>
</div>
</body>
</html>
