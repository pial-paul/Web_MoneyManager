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

// Enable error reporting for debugging
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Handle form submission and insert transaction
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $type = $_POST['type'];
    $date = $_POST['date'];
    $amount = $_POST['amount'];
    $category = $_POST['category'];
    $note = $_POST['note'];

    // Prepare the SQL statement with placeholders
    $sql = "INSERT INTO transactions (type, date, amount, category, note) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind the parameters to the prepared statement
        $stmt->bind_param("ssdss", $type, $date, $amount, $category, $note);

        // Execute the statement
        if ($stmt->execute()) {
            // Redirect to the same page after successful insertion
            header("Location: transaction.php");
            exit();
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }

        // Close the prepared statement
        $stmt->close();
    } else {
        echo "<script>alert('Error preparing statement: " . $conn->error . "');</script>";
    }
}

// Fetch transactions from the database
$sql = "SELECT * FROM transactions ORDER BY date DESC";
$result = $conn->query($sql);

// Initialize transactions array
$transactions = [];

// Check if there are any results
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $transactions[] = $row;
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Transactions</title>
    <link rel="stylesheet" href="income-expense.css" />
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
            <a href="signin.php">Logout</a>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="container">
        <h2>Transaction Records</h2>

        <!-- Transaction Form -->
        <!-- <form class="transaction-form" method="POST" action="transaction.php">
            <div class="form-group">
                <label for="type">Type</label>
                <select id="type" name="type" required>
                    <option value="income">Income</option>
                    <option value="expense">Expense</option>
                </select>
            </div>

            <div class="form-group">
                <label for="date">Date</label>
                <input type="date" id="date" name="date" required />
            </div>

            <div class="form-group">
                <label for="amount">Amount</label>
                <input type="number" step="0.01" id="amount" name="amount" required />
            </div>

            <div class="form-group">
                <label for="category">Category</label>
                <input type="text" id="category" name="category" required />
            </div>

            <div class="form-group">
                <label for="note">Note</label>
                <textarea id="note" name="note"></textarea>
            </div>

            <button type="submit" class="submit-button">Save Record</button>
        </form> -->

        <!-- Controls for search and sort -->
        <div class="controls">
            <input
                type="text"
                id="search-transaction"
                placeholder="Search Transactions"
                onkeyup="filterTable('transaction-table', 'search-transaction')"
            />
            <button onclick="sortTable('transaction-table', 0)">Sort by Date</button>
        </div>

        <!-- Transaction Table -->
        <table id="transaction-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Category</th>
                    <th>Note</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Display transaction rows dynamically
                foreach ($transactions as $transaction) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($transaction['date']) . "</td>";
                    echo "<td>" . htmlspecialchars($transaction['type']) . "</td>";
                    echo "<td>" . htmlspecialchars($transaction['amount']) . "</td>";
                    echo "<td>" . htmlspecialchars($transaction['category']) . "</td>";
                    echo "<td>" . htmlspecialchars($transaction['note']) . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="income-expense.js"></script>
    <script>
        // Sorting and Filtering Functions
        function sortTable(tableId, colIndex) {
            const table = document.getElementById(tableId);
            const rows = Array.from(table.rows).slice(1); // Skip the header row
            const sortedRows = rows.sort((rowA, rowB) => {
                const cellA = rowA.cells[colIndex].innerText;
                const cellB = rowB.cells[colIndex].innerText;
                return new Date(cellB) - new Date(cellA); // Sorting by date
            });
            sortedRows.forEach(row => table.appendChild(row));
        }

        function filterTable(tableId, searchId) {
            const input = document.getElementById(searchId);
            const filter = input.value.toLowerCase();
            const table = document.getElementById(tableId);
            const rows = table.getElementsByTagName("tr");

            for (let i = 1; i < rows.length; i++) {
                const cells = rows[i].getElementsByTagName("td");
                let match = false;
                for (let j = 0; j < cells.length; j++) {
                    if (cells[j].innerText.toLowerCase().includes(filter)) {
                        match = true;
                        break;
                    }
                }
                rows[i].style.display = match ? "" : "none";
            }
        }
    </script>
</body>
</html>
