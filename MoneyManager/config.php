<?php
$servername = "localhost";
$username = "root";  // XAMPP default
$password = "";      // XAMPP default (empty password)
$dbname = "money_management";  // Correct database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
