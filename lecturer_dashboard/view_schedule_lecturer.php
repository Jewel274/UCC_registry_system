<?php
session_start();

// Ensure the user is logged in and is a lecturer
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Lecturer') {
    header('Location: login.php');
    exit;
}

include('../db.php');

// Get the user's ID from the session
$user_id = $_SESSION['user_id']; // assuming 'user_id' is stored in session when logged in

// Fetch the lecturer's ID from the users table based on the logged-in user's ID
$lecturer_sql = "SELECT lecturer_id FROM users WHERE user_id = '$user_id'";
$lecturer_result = $conn->query($lecturer_sql);

if ($lecturer_result->num_rows > 0) {
    $lecturer_row = $lecturer_result->fetch_assoc();
    $lecturer_id = $lecturer_row['lecturer_id'];  // Get the lecturer ID
} else {
    // If no lecturer found for this user
    die("No lecturer found for this user.");
}

// Fetch the lecturer's full name (first and last name) from the lecturers table
$lecturer_name_sql = "SELECT first_name, last_name FROM lecturers WHERE lecturer_id = '$lecturer_id'";
$lecturer_name_result = $conn->query($lecturer_name_sql);
$lecturer_name = '';

if ($lecturer_name_result->num_rows > 0) {
    $lecturer_name_row = $lecturer_name_result->fetch_assoc();
    $lecturer_name = $lecturer_name_row['first_name'] . ' ' . $lecturer_name_row['last_name'];  // Full name
} else {
    // If no lecturer name found
    die("Lecturer name not found.");
}

// Fetch the lecturer's schedule
$schedule_sql = "
    SELECT cs.schedule_id, cs.course_code, cs.semester, cs.year, cs.section, cs.day_of_week, cs.time, cs.location, c.title
    FROM course_schedule cs
    JOIN courses c ON cs.course_code = c.course_code
    WHERE cs.lecturer_id = '$lecturer_id'
";

$schedule_result = $conn->query($schedule_sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>My Schedule</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h1>Schedule for <?php echo htmlspecialchars($lecturer_name); ?></h1> <!-- Display lecturer name -->

        <?php if ($schedule_result->num_rows > 0) { ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Semester</th>
                        <th>Year</th>
                        <th>Section</th>
                        <th>Day</th>
                        <th>Time</th>
                        <th>Location</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($schedule = $schedule_result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $schedule['title']; ?></td>
                            <td><?php echo $schedule['semester']; ?></td>
                            <td><?php echo $schedule['year']; ?></td>
                            <td><?php echo $schedule['section']; ?></td>
                            <td><?php echo $schedule['day_of_week']; ?></td>
                            <td><?php echo $schedule['time']; ?></td>
                            <td><?php echo $schedule['location']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>You don't have any scheduled courses.</p>
        <?php } ?>

        <a href="../logout.php" class="btn btn-primary mt-3">Log Out</a>
    </div>
</body>

</html>