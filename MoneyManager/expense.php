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

// Fetch expense records from the database
$sql = "SELECT date, amount, note FROM transactions WHERE type = 'expense' ORDER BY date DESC";
$result = $conn->query($sql);

// Initialize expense records array
$expenseRecords = [];

// Check if there are any results
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $expenseRecords[] = $row;
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
    <title>Expense</title>
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
        <h2>Expense Records</h2>
        <div class="controls">
            <input
                type="text"
                id="search-expense"
                placeholder="Search Expense"
                onkeyup="filterTable('expense-table', 'search-expense')"
            />
            <button onclick="sortTable('expense-table', 0)">Sort by Date</button>
        </div>
        <table id="expense-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Display expense rows dynamically
                foreach ($expenseRecords as $record) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($record['date']) . "</td>";
                    echo "<td>" . htmlspecialchars($record['amount']) . "</td>";
                    echo "<td>" . htmlspecialchars($record['note']) . "</td>";
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
