<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header('Location: ../login.php');  // Redirect to login if not admin
    exit;
}

include('../../db.php');

// Check if course_code is provided via GET
if (isset($_GET['course_code'])) {
    $course_code = $_GET['course_code'];

    // Fetch course details using course_code
    $sql = "SELECT * FROM Courses WHERE course_code = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $course_code);  // 's' for string type
    $stmt->execute();
    $result = $stmt->get_result();
    $course = $result->fetch_assoc();

    // If the course exists
    if ($course) {
        // Handle the deletion on POST request
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Delete the course
            $delete_sql = "DELETE FROM Courses WHERE course_code = ?";
            $delete_stmt = $conn->prepare($delete_sql);
            $delete_stmt->bind_param('s', $course_code);

            if ($delete_stmt->execute()) {
                // Success message and redirect
                echo "Course deleted successfully!";
                header('Location: view_courses.php');  // Redirect to view_courses after deletion
                exit;
            } else {
                // Error message
                $message = "Error deleting course.";
            }
        }
    } else {
        $message = "Course not found.";
    }
} else {
    $message = "No course code provided.";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Course</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h1 class="my-4">Delete Course</h1>

        <?php if (isset($message)) { ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php } ?>

        <?php if (isset($course)) { ?>
            <p>Are you sure you want to delete the following course?</p>

            <div class="mb-3">
                <strong>Course Code:</strong> <?php echo htmlspecialchars($course['course_code']); ?>
            </div>
            <div class="mb-3">
                <strong>Title:</strong> <?php echo htmlspecialchars($course['title']); ?>
            </div>
            <div class="mb-3">
                <strong>Credits:</strong> <?php echo $course['credits']; ?>
            </div>
            <div class="mb-3">
                <strong>Degree Level:</strong> <?php echo $course['degree_level']; ?>
            </div>
            <div class="mb-3">
                <strong>Prerequisites:</strong> <?php echo htmlspecialchars($course['prerequisites']); ?>
            </div>

            <!-- Confirmation form -->
            <form method="POST">
                <button type="submit" class="btn btn-danger">Delete Course</button>
            </form>
        <?php } ?>

        <a href="view_courses.php" class="btn btn-secondary mt-3">Back to Courses</a>
    </div>
</body>

</html>