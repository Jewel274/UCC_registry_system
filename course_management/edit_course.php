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

    // Check if course exists
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get updated values from form submission
        $title = $_POST['title'];
        $credits = $_POST['credits'];
        $degree_level = $_POST['degree_level'];
        $prerequisites = $_POST['prerequisites'];

        // Update course in the database
        $update_sql = "UPDATE Courses SET title = ?, credits = ?, degree_level = ?, prerequisites = ? WHERE course_code = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param('sisss', $title, $credits, $degree_level, $prerequisites, $course_code);

        if ($update_stmt->execute()) {
            // Successfully updated, redirect to view_courses
            header('Location: view_courses.php');
            exit;
        } else {
            // Error while updating
            $message = "Error updating course.";
        }
    }
} else {
    // If no course_code is found
    $message = "Course not found.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Course</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h1 class="my-4">Edit Course Information</h1>

        <?php if (isset($message)) { ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php } ?>

        <?php if (isset($course)) { ?>
            <form method="POST">
                <div class="mb-3">
                    <label for="title" class="form-label">Title:</label>
                    <input type="text" name="title" id="title" class="form-control" value="<?php echo htmlspecialchars($course['title']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="credits" class="form-label">Credits:</label>
                    <input type="number" name="credits" id="credits" class="form-control" value="<?php echo $course['credits']; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="degree_level" class="form-label">Degree Level:</label>
                    <select name="degree_level" id="degree_level" class="form-control" required>
                        <option value="Undergraduate" <?php echo ($course['degree_level'] == 'Undergraduate') ? 'selected' : ''; ?>>Undergraduate</option>
                        <option value="Graduate" <?php echo ($course['degree_level'] == 'Graduate') ? 'selected' : ''; ?>>Graduate</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="prerequisites" class="form-label">Prerequisites:</label>
                    <input type="text" name="prerequisites" id="prerequisites" class="form-control" value="<?php echo htmlspecialchars($course['prerequisites']); ?>">
                </div>

                <button type="submit" class="btn btn-primary">Update Course</button>
            </form>
        <?php } ?>

        <a href="view_courses.php" class="btn btn-secondary mt-3">Back to Courses</a>
    </div>
</body>

</html>