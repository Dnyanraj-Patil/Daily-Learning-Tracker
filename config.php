<?php
// Database configuration
$servername = "localhost";  // Change if necessary
$username = "root";         // Your database username
$password = "";             // Your database password
$dbname = "PHP"; // The name of your database

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
