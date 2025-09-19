<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header('Location: login.php');
    exit;
}

include('../../db.php');

// Get student ID from URL
$student_id = $_GET['id'];

// Delete student from the database
$sql = "DELETE FROM students WHERE student_id = '$student_id'";
if ($conn->query($sql) === TRUE) {
    header('Location: view_students.php');
} else {
    echo "Error deleting student: " . $conn->error;
}
