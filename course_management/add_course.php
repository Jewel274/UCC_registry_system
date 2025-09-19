<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header('Location: login.php');
    exit;
}

include('../../db.php');

// Add new course to the database
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_code = $_POST['course_code'];
    $title = $_POST['title'];
    $credits = $_POST['credits'];
    $degree_level = $_POST['degree_level'];
    $prerequisites = $_POST['prerequisites'];

    $sql = "INSERT INTO courses (course_code, title, credits, degree_level, prerequisites) 
            VALUES ('$course_code', '$title', '$credits', '$degree_level', '$prerequisites')";

    if ($conn->query($sql) === TRUE) {
        $message = "Course added successfully.";
    } else {
        $message = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Add New Course</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h1>Add New Course</h1>

        <?php if (isset($message)) {
            echo "<div class='alert alert-info'>$message</div>";
        } ?>

        <form method="POST">
            <div class="mb-3">
                <label for="course_code" class="form-label">Course Code</label>
                <input type="text" class="form-control" id="course_code" name="course_code" required>
            </div>

            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>

            <div class="mb-3">
                <label for="credits" class="form-label">Credits</label>
                <input type="number" class="form-control" id="credits" name="credits" required>
            </div>

            <div class="mb-3">
                <label for="degree_level" class="form-label">Degree Level</label>
                <input type="text" class="form-control" id="degree_level" name="degree_level" required>
            </div>

            <div class="mb-3">
                <label for="prerequisites" class="form-label">Prerequisites</label>
                <input type="text" class="form-control" id="prerequisites" name="prerequisites">
            </div>

            <button type="submit" class="btn btn-primary">Add Course</button>
        </form>

        <a href="view_courses.php" class="btn btn-secondary mt-3">Back to Courses</a>
    </div>
</body>

</html>