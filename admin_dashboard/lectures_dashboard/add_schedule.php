<?php
session_start();

// Ensure the user is logged in and is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header('Location: login.php');
    exit;
}

include('../../db.php');

// Fetch lecturers
$lecturers_sql = "SELECT * FROM lecturers";
$lecturers_result = $conn->query($lecturers_sql);

// Fetch courses
$courses_sql = "SELECT * FROM courses";
$courses_result = $conn->query($courses_sql);

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lecturer_id = $_POST['lecturer_id'];
    $course_code = $_POST['course_code'];
    $semester = $_POST['semester'];
    $year = $_POST['year'];
    $section = $_POST['section'];
    $day_of_week = $_POST['day_of_week'];
    $time = $_POST['time'];
    $location = $_POST['location'];

    // Insert new schedule into the course_schedule table
    $insert_sql = "INSERT INTO course_schedule (lecturer_id, course_code, semester, year, section, day_of_week, time, location) 
                   VALUES ('$lecturer_id', '$course_code', '$semester', '$year', '$section', '$day_of_week', '$time', '$location')";

    if ($conn->query($insert_sql) === TRUE) {
        $message = "Schedule added successfully.";
    } else {
        $message = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Add Schedule to Lecturer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h1>Add Schedule to Lecturer</h1>

        <?php if (isset($message)) {
            echo "<div class='alert alert-info'>$message</div>";
        } ?>

        <form method="POST">
            <div class="mb-3">
                <label for="lecturer_id" class="form-label">Select Lecturer</label>
                <select class="form-control" id="lecturer_id" name="lecturer_id" required>
                    <option value="">Select Lecturer</option>
                    <?php while ($lecturer = $lecturers_result->fetch_assoc()) { ?>
                        <option value="<?php echo $lecturer['lecturer_id']; ?>">
                            <?php echo $lecturer['first_name'] . ' ' . $lecturer['last_name']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="course_code" class="form-label">Select Course</label>
                <select class="form-control" id="course_code" name="course_code" required>
                    <option value="">Select Course</option>
                    <?php while ($course = $courses_result->fetch_assoc()) { ?>
                        <option value="<?php echo $course['course_code']; ?>">
                            <?php echo $course['title']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="semester" class="form-label">Semester</label>
                <input type="text" class="form-control" id="semester" name="semester" required>
            </div>

            <div class="mb-3">
                <label for="year" class="form-label">Year</label>
                <input type="number" class="form-control" id="year" name="year" required>
            </div>

            <div class="mb-3">
                <label for="section" class="form-label">Section</label>
                <input type="text" class="form-control" id="section" name="section" required>
            </div>

            <div class="mb-3">
                <label for="day_of_week" class="form-label">Day of Week</label>
                <input type="text" class="form-control" id="day_of_week" name="day_of_week" required>
            </div>

            <div class="mb-3">
                <label for="time" class="form-label">Time</label>
                <input type="text" class="form-control" id="time" name="time" required>
            </div>

            <div class="mb-3">
                <label for="location" class="form-label">Location</label>
                <input type="text" class="form-control" id="location" name="location" required>
            </div>

            <button type="submit" class="btn btn-primary">Add Schedule</button>
        </form>

        <a href="lecture_schedule.php" class="btn btn-primary mt-3">Back to Schedule</a>
    </div>
</body>

</html>