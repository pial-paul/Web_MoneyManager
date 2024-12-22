<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$database = "money_management";

// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in to continue.'); window.location.href='signin.php';</script>";
    exit;
}

// Get the logged-in user's ID
$user_id = $_SESSION['user_id'];

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data and validate
    $type = isset($_POST['type']) ? $_POST['type'] : '';
    $date = isset($_POST['date']) ? $_POST['date'] : '';
    $amount = isset($_POST['amount']) ? (float)$_POST['amount'] : 0;
    $category = isset($_POST['category']) ? $_POST['category'] : '';
    $note = isset($_POST['note']) ? $_POST['note'] : '';

    // Validate required fields
    if (empty($type) || empty($date) || $amount <= 0 || empty($category)) {
        echo "<script>alert('Please fill in all required fields correctly.');</script>";
    } else {
        // Prepare the SQL statement with placeholders
        $sql = "INSERT INTO transactions (user_id, type, date, amount, category, note) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            // Bind the parameters to the prepared statement
            $stmt->bind_param("issdss", $user_id, $type, $date, $amount, $category, $note);

            // Execute the statement
            if ($stmt->execute()) {
                echo "<script>alert('Transaction saved successfully!'); window.location.href='transaction.php';</script>";
            } else {
                echo "<script>alert('Error saving transaction: " . htmlspecialchars($stmt->error) . "');</script>";
            }

            // Close the prepared statement
            $stmt->close();
        } else {
            echo "<script>alert('Error preparing statement: " . htmlspecialchars($conn->error) . "');</script>";
        }
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Money Manager Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Top Navigation Bar -->
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

    <!-- Main Container -->
    <div class="dashboard-container">
        <div class="main-header">
            <h1>Add Income / Expense</h1>
        </div>

        <!-- Transaction Form -->
        <form class="transaction-form" method="POST" action="transaction.php">
            <div class="form-group">
                <label for="type">Type</label>
                <select id="type" name="type" required>
                    <option value="income">Income</option>
                    <option value="expense">Expense</option>
                </select>
            </div>

            <div class="form-group">
                <label for="date">Date</label>
                <input type="date" id="date" name="date" required>
            </div>

            <div class="form-group">
                <label for="amount">Amount</label>
                <input type="number" id="amount" name="amount" step="0.01" required>
            </div>

            <div class="form-group">
                <label for="category">Category</label>
                <input type="text" id="category" name="category" required>
            </div>

            <div class="form-group">
                <label for="note">Note</label>
                <textarea id="note" name="note"></textarea>
            </div>

            <button type="submit" class="submit-button">Save Record</button>
        </form>
    </div>
    <script src="script.js"></script>
</body>
</html>