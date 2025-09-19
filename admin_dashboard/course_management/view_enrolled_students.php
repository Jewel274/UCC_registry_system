<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header('Location: login.php');
    exit;
}

include('../../db.php');

// Get course code from URL
if (isset($_GET['course_code'])) {
    $course_code = $_GET['course_code'];

    // Fetch enrolled students for this course
    $sql = "SELECT Students.student_id, Students.first_name, Students.last_name, Students.student_email
            FROM Students
            JOIN Course_Enrollment ON Students.student_id = Course_Enrollment.student_id
            WHERE Course_Enrollment.course_code = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $course_code);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    echo "Course not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrolled Students</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h1 class="my-4">Enrolled Students in Course: <?php echo htmlspecialchars($course_code); ?></h1>

        <?php if ($result->num_rows > 0) { ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($student = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($student['student_id']); ?></td>
                            <td><?php echo htmlspecialchars($student['first_name']); ?></td>
                            <td><?php echo htmlspecialchars($student['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($student['student_email']); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>No students are enrolled in this course.</p>
        <?php } ?>

        <a href="../admin_dashboard.php" class="btn btn-primary">Back to Dashboard</a>
    </div>
</body>

</html>