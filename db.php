<?php
$servername = "localhost"; // Usually localhost
$username = "root"; // Default MySQL username in XAMPP
$password = ""; // Default password for XAMPP
$dbname = "ucc_registry_system"; // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
