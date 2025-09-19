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




// Now fetch student-specific data from the Students table
$student_query = "SELECT * FROM students WHERE student_id = ?";
$stmt = $conn->prepare($student_query);
$stmt->bind_param('i', $student_id);
$stmt->execute();
$student_result = $stmt->get_result();

if ($student_result->num_rows > 0) {
    $student = $student_result->fetch_assoc();
} else {
    die("No student found with the given ID.");
}
// Fetch enrolled courses and grades
$enrollment_query = "
    SELECT 
        Courses.course_code, Courses.title, Course_Enrollment.coursework_grade, Course_Enrollment.final_exam_grade
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
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></h1>

        <h3>Student Information</h3>
        <p>Email: <?php echo htmlspecialchars($student['student_email']); ?></p>
        <p>Contact: <?php echo htmlspecialchars($student['mobile_number']); ?></p>
        <p>Program: <?php echo htmlspecialchars($student['program_of_study']); ?></p>

        <a href="timetable.php" class="btn btn-primary">View Course Timetable</a>

        <h3>Enrolled Courses</h3>
        <?php if ($enrolled_courses->num_rows > 0) { ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Course Code</th>
                        <th>Course Title</th>
                        <th>Coursework Grade</th>
                        <th>Final Exam Grade</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($course = $enrolled_courses->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($course['course_code']); ?></td>
                            <td><?php echo htmlspecialchars($course['title']); ?></td>
                            <td><?php echo htmlspecialchars($course['coursework_grade']); ?></td>
                            <td><?php echo htmlspecialchars($course['final_exam_grade']); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>You are not enrolled in any courses.</p>
        <?php } ?>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</body>

</html>