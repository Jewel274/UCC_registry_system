<?php
session_start();

// Ensure the user is logged in and is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header('Location: login.php');
    exit;
}

include('../../db.php');

// Fetch lecture schedules with associated lecturer and course information
$sql = "SELECT cs.schedule_id, cs.course_code, cs.semester, cs.year, cs.section, cs.day_of_week, cs.time, cs.location,
               c.title, l.first_name AS lecturer_first_name, l.last_name AS lecturer_last_name, 
               l.department, l.position
        FROM course_schedule cs
        JOIN courses c ON cs.course_code = c.course_code
        JOIN lecturers l ON cs.lecturer_id = l.lecturer_id";
$course_schedule = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Lectures and Course Schedules</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h1>Lectures and Course Schedules</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Lecturer</th>
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
                <?php if ($course_schedule->num_rows > 0) {
                    while ($schedule = $course_schedule->fetch_assoc()) {
                        $course_name = $schedule['title'];
                        $semester = $schedule['semester'];
                        $year = $schedule['year'];
                        $section = $schedule['section'];
                        $day_of_week = $schedule['day_of_week'];
                        $time = $schedule['time'];
                        $location = $schedule['location'];
                        $lecturer_name = $schedule['lecturer_first_name'] . " " . $schedule['lecturer_last_name'];
                        $lecturer_department = $schedule['department'];
                        $lecturer_position = $schedule['position'];

                        // Display lecture schedule details
                        echo "<tr>";
                        echo "<td>{$schedule['schedule_id']}</td>";
                        echo "<td>{$lecturer_name}<br>Department: $lecturer_department<br>Position: $lecturer_position</td>";
                        echo "<td>{$course_name}</td>";
                        echo "<td>{$semester}</td>";
                        echo "<td>{$year}</td>";
                        echo "<td>{$section}</td>";
                        echo "<td>{$day_of_week}</td>";
                        echo "<td>{$time}</td>";
                        echo "<td>{$location}</td>";
                        echo "<td>
                                <a href='edit_schedule.php?schedule_id={$schedule['schedule_id']}' class='btn btn-warning btn-sm'>Edit</a>
                                <a href='delete_schedule.php?schedule_id={$schedule['schedule_id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this schedule?\")'>Delete</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='9'>No course schedules found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>