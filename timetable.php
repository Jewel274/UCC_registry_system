<?php
session_start();

// Ensure the user is logged in and is a student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Student') {
    header('Location: login.php');
    exit;
}

// Include database connection
include('db.php');

// Retrieve student ID from the users table using user_id
$user_id = $_SESSION['user_id'];

// Fetch the student_id from the users table
$query = "SELECT student_id FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $student_id = $row['student_id']; // Store student ID from users table
} else {
    die("No student found with the given user ID.");
}

// Fetch enrolled courses
$enrollment_query = "
    SELECT 
        Course_Enrollment.course_code, 
        Courses.title 
    FROM 
        Course_Enrollment 
    INNER JOIN 
        Courses ON Course_Enrollment.course_code = Courses.course_code 
    WHERE 
        Course_Enrollment.student_id = ?";
$stmt = $conn->prepare($enrollment_query);
$stmt->bind_param('i', $student_id);
$stmt->execute();
$enrolled_courses = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Course Timetable</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h1>Course Timetable</h1>

        <?php if ($enrolled_courses->num_rows > 0) { ?>
            <h3>Your Courses and Timetable</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Course Code</th>
                        <th>Course Title</th>
                        <th>Lecturer</th>
                        <th>Location</th>
                        <th>Day</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($course = $enrolled_courses->fetch_assoc()) {
                        // Fetch course schedule data for each course
                        $course_code = $course['course_code'];
                        $schedule_query = "
                            SELECT 
                                Lecturers.title AS lecturer_title, 
                                Lecturers.first_name AS lecturer_name, 
                                Course_Schedule.location, 
                                Course_Schedule.day_of_week,
                                Course_Schedule.time 
                            FROM 
                                Course_Schedule 
                            INNER JOIN 
                                Lecturers ON Course_Schedule.lecturer_id = Lecturers.lecturer_id 
                            WHERE 
                                Course_Schedule.course_code = ?";
                        $schedule_stmt = $conn->prepare($schedule_query);
                        $schedule_stmt->bind_param('s', $course_code);
                        $schedule_stmt->execute();
                        $schedule_result = $schedule_stmt->get_result();

                        while ($schedule = $schedule_result->fetch_assoc()) {
                    ?>
                            <tr>
                                <td><?php echo htmlspecialchars($course['course_code']); ?></td>
                                <td><?php echo htmlspecialchars($course['title']); ?></td>
                                <td><?php echo htmlspecialchars($schedule['lecturer_title'] . ' ' . $schedule['lecturer_name']); ?></td>
                                <td><?php echo htmlspecialchars($schedule['location']); ?></td>
                                <td><?php echo htmlspecialchars($schedule['day_of_week']); ?></td>
                                <td><?php echo htmlspecialchars($schedule['time']); ?></td>
                            </tr>
                    <?php }
                    } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>You are not enrolled in any courses with a timetable available.</p>
        <?php } ?>
        <a href="student_dashboard.php" class="btn btn-primary">Back to Dashboard</a>
    </div>

</body>

</html>