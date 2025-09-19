<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header('Location: login.php');
    exit;
}

include('../../db.php');

// Fetch courses along with the number of enrolled students
$sql = "SELECT Courses.course_code, Courses.title, COUNT(Course_Enrollment.student_id) AS student_count
        FROM Courses
        LEFT JOIN Course_Enrollment ON Courses.course_code = Course_Enrollment.course_code
        GROUP BY Courses.course_code";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h1 class="my-4">Courses and Enrollment</h1>

        <?php if ($result->num_rows > 0) { ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Course Code</th>
                        <th>Course Title</th>
                        <th>Enrolled Students</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($course = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($course['course_code']); ?></td>
                            <td><?php echo htmlspecialchars($course['title']); ?></td>
                            <td><?php echo $course['student_count']; ?></td>
                            <td>
                                <!-- Edit Button -->
                                <a href="edit_course.php?course_code=<?php echo htmlspecialchars($course['course_code']); ?>" class="btn btn-warning">Edit</a>

                                <!-- Delete Button -->
                                <a href="delete_course.php?course_code=<?php echo htmlspecialchars($course['course_code']); ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this course?');">Delete</a>

                                <!-- View Enrolled Students Button -->
                                <a href="view_enrolled_students.php?course_code=<?php echo htmlspecialchars($course['course_code']); ?>" class="btn btn-primary">View Enrolled Students</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>No courses found or no students enrolled.</p>
        <?php } ?>
        <a href="../admin_dashboard.php" class="btn btn-primary">Back to Dashboard</a>
    </div>

</body>

</html>