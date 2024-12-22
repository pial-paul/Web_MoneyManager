<?php
// Start session
session_start();

// Include database connection
require_once 'config.php'; // Assuming your DB connection is in 'config.php'

// Check if the user is logged in (using 'user_id')
if (!isset($_SESSION['user_id'])) {
    echo "<p>Session ID not found. Are you logged in? Debugging session variables:</p>";
    var_dump($_SESSION); // Debugging purpose, remove in production
    exit();
}

// Fetch user data from the database
$user_id = $_SESSION['user_id']; // Use the correct session variable
$sql = "SELECT first_name, last_name, username, email FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    die("User not found. Please ensure the user ID exists in the database.");
}

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Fetch new values from the form
    $new_first_name = trim($_POST['first_name']);
    $new_last_name = trim($_POST['last_name']);
    $new_username = trim($_POST['username']);
    $new_password = trim($_POST['password']);

    // Validate input fields
    if (empty($new_first_name) || empty($new_last_name) || empty($new_username)) {
        echo "All fields except password are required.";
        exit();
    }

    // Hash the new password if provided
    $hashed_password = null;
    if (!empty($new_password)) {
        if (strlen($new_password) < 8) {
            echo "Password must be at least 8 characters long.";
            exit();
        }
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    }

    // Update user data
    if ($hashed_password) {
        $update_sql = "UPDATE users SET first_name = ?, last_name = ?, username = ?, password = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ssssi", $new_first_name, $new_last_name, $new_username, $hashed_password, $user_id);
    } else {
        $update_sql = "UPDATE users SET first_name = ?, last_name = ?, username = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("sssi", $new_first_name, $new_last_name, $new_username, $user_id);
    }

    if ($update_stmt->execute()) {
        echo "Profile updated successfully.";

        // Refresh user data for session or immediate display
        $user['first_name'] = $new_first_name;
        $user['last_name'] = $new_last_name;
        $user['username'] = $new_username;
    } else {
        echo "Error updating profile: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Settings</title>
    <link rel="stylesheet" href="style.css">
</head>
<style>
    /* style.css */

    /* General Body Styling */
    body {
      font-family: "Arial", sans-serif;
      margin: 0;
      padding: 0;
      background-color: #0f172a; /* Dark Blue Background */
      color: #ffffff; /* White Text Color */
    }

    /* Top Navigation Bar */
    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background-color: #1e293b; /* Slightly Lighter Dark Blue */
      padding: 10px 20px;
    }

    .navbar .logo {
      font-size: 1.5em;
      font-weight: bold;
      color: #38bdf8; /* Light Blue */
    }

    .navbar .nav-links a {
      color: #94a3b8; /* Grayish Blue */
      text-decoration: none;
      margin-left: 15px;
      transition: color 0.3s ease;
    }

    .navbar .nav-links a:hover {
      color: #38bdf8;
    }

    /* Container Styling */
    .container {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 85vh;
    }

    /* Form Styling */
    .settings-form {
      background-color: #1e293b; /* Form Background */
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.5);
      width: 350px;
      text-align: center;
    }

    .settings-form h2 {
      color: #38bdf8; /* Light Blue Heading */
      margin-bottom: 20px;
    }

    .form-group {
      margin-bottom: 20px;
      text-align: left;
    }

    .form-group label {
      display: block;
      font-weight: bold;
      color: #94a3b8; /* Label Color */
      margin-bottom: 5px;
    }

    .form-group input {
      width: 100%;
      padding: 10px;
      border: none;
      border-radius: 5px;
      background-color: #334155; /* Input Field Background */
      color: #ffffff;
    }

    .form-group input:focus {
      outline: none;
      border: 2px solid #38bdf8; /* Highlight on Focus */
    }

    small {
      color: #94a3b8;
      display: block;
      margin-top: 5px;
    }

    /* Buttons Styling */
    .btn {
      background-color: #38bdf8; /* Light Blue Button */
      color: #ffffff;
      border: none;
      padding: 10px 20px;
      border-radius: 5px;
      font-size: 1em;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .btn:hover {
      background-color: #0ea5e9; /* Darker Blue */
    }

    /* Back Link */
    .back-link {
      margin-top: 20px;
    }

    .back-link a {
      color: #38bdf8;
      text-decoration: none;
    }

    .back-link a:hover {
      text-decoration: underline;
    }
  </style>
<body>
<nav class="navbar">
    <div class="logo">Money Manager</div>
    <div class="nav-links">
    <a href="index.php">Home</a>
            <a href="income.php">Income</a>
            <a href="expense.php">Expense</a>
            <a href="transaction.php">Transaction</a>
            <a href="profile.php">Settings</a>
            <a href="logout.php">Logout</a>
    </div>
</nav>

<div class="_container">
    <form class="settings-form" method="POST">
        <h2>User Settings</h2>

        <div class="form-group">
            <label for="first-name">First Name</label>
            <input type="text" id="first-name" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
        </div>

        <div class="form-group">
            <label for="last-name">Last Name</label>
            <input type="text" id="last-name" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
        </div>

        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter new password (optional)">
            <small>Leave blank to keep current password. Minimum length is 8 characters if changed.</small>
        </div>

        <button type="submit" class="btn">Save Changes</button>
        <p class="back-link"><a href="index.php">Back to Dashboard</a></p>
    </form>
</div>

</body>
</html>
