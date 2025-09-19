<?php
session_start();

// Ensure the user is logged in and is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header('Location: login.php');
    exit;
}

include('../../db.php');

// Check if 'schedule_id' exists in the URL
if (!isset($_GET['schedule_id'])) {
    die("Schedule ID is missing.");
}

$schedule_id = $_GET['schedule_id'];

// Delete query
$sql = "DELETE FROM course_schedule WHERE schedule_id = '$schedule_id'";

if ($conn->query($sql) === TRUE) {
    $message = "Schedule deleted successfully.";
    header('Location: lecture_schedule.php'); // Redirect back to the schedule list
    exit;
} else {
    die("Error: " . $conn->error);
}
