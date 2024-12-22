<?php
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
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $userName = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hashing the password

    // Check if the username or email already exists
    $checkSql = "SELECT id FROM users WHERE username = ? OR email = ?";
    $checkStmt = $conn->prepare($checkSql);

    if ($checkStmt) {
        $checkStmt->bind_param("ss", $userName, $email);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            echo "<script>alert('Username or Email already exists. Please try a different one.');</script>";
        } else {
            // If no duplicate, proceed with the insertion
            $sql = "INSERT INTO users (first_name, last_name, username, email, password) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("sssss", $firstName, $lastName, $userName, $email, $password);

                if ($stmt->execute()) {
                    echo "<script>alert('Sign-up successful!'); window.location.href='signin.php';</script>";
                } else {
                    echo "<script>alert('Error: " . $stmt->error . "');</script>";
                }

                $stmt->close();
            } else {
                echo "<script>alert('Error preparing statement: " . $conn->error . "');</script>";
            }
        }

        $checkStmt->close();
    } else {
        echo "<script>alert('Error preparing duplicate check: " . $conn->error . "');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="signup-container">
    <form class="signup-form" method="POST" action="signup.php">
        <!-- Centered Sign Up Heading -->
        <h2 class="signup-heading">Sign Up</h2>

        <div class="form-row">
            <div class="form-group">
                <label for="first-name">First Name</label>
                <div class="input-icon">
                    <input
                        type="text"
                        id="first-name"
                        name="first_name"
                        placeholder="First Name"
                        required
                    />
                    <span class="icon">👤</span>
                </div>
            </div>
            <div class="form-group">
                <label for="last-name">Last Name</label>
                <div class="input-icon">
                    <input
                        type="text"
                        id="last-name"
                        name="last_name"
                        placeholder="Last Name"
                        required
                    />
                    <span class="icon">👤</span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="username">Username</label>
            <div class="input-icon">
                <input type="text" id="username" name="username" placeholder="Username" required />
                <span class="icon">👤</span>
            </div>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <div class="input-icon">
                <input type="email" id="email" name="email" placeholder="Email" required />
                <span class="icon">✉️</span>
            </div>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <div class="input-icon">
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Password"
                    minlength="8"
                    required
                />
                <span class="icon">🔒</span>
            </div>
            <small>Minimum length is 8 characters.</small>
        </div>
        <button type="submit" class="signup-button">Sign Up</button>
        <p class="terms-text">
            By creating an account, you agree to the
            <a href="#">Terms of Service</a>.
        </p>
        <p class="login-link">
            Already have an account? <a href="signin.php">Login</a>
        </p>
    </form>
</div>
</body>
</html>
