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

// Fetch the current schedule details
$sql = "SELECT * FROM course_schedule WHERE schedule_id = '$schedule_id'";
$result = $conn->query($sql);

// If no schedule found, show an error
if ($result->num_rows === 0) {
    die("No schedule found with that ID.");
}

$schedule = $result->fetch_assoc();

// Update the schedule details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $day_of_week = $_POST['day_of_week'];
    $time = $_POST['time'];
    $location = $_POST['location'];

    // Update query
    $update_sql = "UPDATE course_schedule SET 
                    day_of_week = '$day_of_week', time = '$time', location = '$location'
                    WHERE schedule_id = '$schedule_id'";

    if ($conn->query($update_sql) === TRUE) {
        $message = "Schedule updated successfully.";
    } else {
        $message = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Edit Schedule</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h1>Edit Schedule Information</h1>

        <?php if (isset($message)) {
            echo "<div class='alert alert-info'>$message</div>";
        } ?>

        <form method="POST">
            <div class="mb-3">
                <label for="day_of_week" class="form-label">Day of Week</label>
                <input type="text" class="form-control" id="day_of_week" name="day_of_week" value="<?php echo $schedule['day_of_week']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="time" class="form-label">Time</label>
                <input type="text" class="form-control" id="time" name="time" value="<?php echo $schedule['time']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="location" class="form-label">Location</label>
                <input type="text" class="form-control" id="location" name="location" value="<?php echo $schedule['location']; ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Update Schedule</button>
        </form>

        <a href="lecture_schedule.php" class="btn btn-primary">Back to Schedule</a>
    </div>
</body>

</html>