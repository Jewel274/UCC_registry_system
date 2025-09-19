<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header('Location: login.php');
    exit;
}

include('../../db.php');

// Check if 'student_id' exists in the URL
if (!isset($_GET['student_id'])) {
    die("Student ID is missing.");
}

$student_id = $_GET['student_id'];

// Query to get the student details
$sql = "SELECT * FROM students WHERE student_id = '$student_id'";
$result = $conn->query($sql);

// If no student is found with that ID, show an error
if ($result->num_rows === 0) {
    die("No student found with that ID.");
}

$student = $result->fetch_assoc();

// Fetch available courses
$courses = $conn->query("SELECT * FROM courses")->fetch_all(MYSQLI_ASSOC);

// Fetch already enrolled courses
$enrolled_courses = $conn->query("SELECT course_code FROM course_enrollment WHERE student_id = '$student_id'")
    ->fetch_all(MYSQLI_ASSOC);
$enrolled_course_codes = array_column($enrolled_courses, 'course_code');

// Update student details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $personal_email = $_POST['personal_email'];
    $student_email = $_POST['student_email'];
    $mobile_number = $_POST['mobile_number'];
    $home_contact = $_POST['home_contact'];
    $work_contact = $_POST['work_contact'];
    $home_address = $_POST['home_address'];
    $next_of_kin = $_POST['next_of_kin'];
    $next_of_kin_contact = $_POST['next_of_kin_contact'];
    $program_of_study = $_POST['program_of_study'];
    $gpa = $_POST['gpa'];
    $selected_courses = $_POST['courses'] ?? [];

    // Update student details
    $sql = "UPDATE students SET 
            first_name = '$first_name', middle_name = '$middle_name', last_name = '$last_name', personal_email = '$personal_email',
            student_email = '$student_email', mobile_number = '$mobile_number', home_contact = '$home_contact', 
            work_contact = '$work_contact', home_address = '$home_address', next_of_kin = '$next_of_kin',
            next_of_kin_contact = '$next_of_kin_contact', program_of_study = '$program_of_study', gpa = '$gpa'
            WHERE student_id = '$student_id'";

    if ($conn->query($sql) === TRUE) {
        // Update course enrollments
        $conn->query("DELETE FROM course_enrollment WHERE student_id = '$student_id'");
        foreach ($selected_courses as $course_code) {
            $stmt = $conn->prepare("INSERT INTO course_enrollment (student_id, course_code) VALUES (?, ?)");
            $stmt->bind_param("is", $student_id, $course_code);
            $stmt->execute();
            $stmt->close();
        }
        $message = "Student details and course enrollments updated successfully.";
    } else {
        $message = "Error: " . $conn->error;
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>Edit Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h1>Edit Student Information</h1>

        <?php if (isset($message)) {
            echo "<div class='alert alert-info'>$message</div>";
        } ?>

        <form method="POST">
            <div class="mb-3">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo $student['first_name']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="middle_name" class="form-label">Middle Name</label>
                <input type="text" class="form-control" id="middle_name" name="middle_name" value="<?php echo $student['middle_name']; ?>">
            </div>

            <div class="mb-3">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo $student['last_name']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="personal_email" class="form-label">Personal Email</label>
                <input type="email" class="form-control" id="personal_email" name="personal_email" value="<?php echo $student['personal_email']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="student_email" class="form-label">Student Email</label>
                <input type="email" class="form-control" id="student_email" name="student_email" value="<?php echo $student['student_email']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="mobile_number" class="form-label">Mobile Number</label>
                <input type="text" class="form-control" id="mobile_number" name="mobile_number" value="<?php echo $student['mobile_number']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="home_contact" class="form-label">Home Contact</label>
                <input type="text" class="form-control" id="home_contact" name="home_contact" value="<?php echo $student['home_contact']; ?>">
            </div>

            <div class="mb-3">
                <label for="work_contact" class="form-label">Work Contact</label>
                <input type="text" class="form-control" id="work_contact" name="work_contact" value="<?php echo $student['work_contact']; ?>">
            </div>

            <div class="mb-3">
                <label for="home_address" class="form-label">Home Address</label>
                <input type="text" class="form-control" id="home_address" name="home_address" value="<?php echo $student['home_address']; ?>">
            </div>

            <div class="mb-3">
                <label for="next_of_kin" class="form-label">Next of Kin</label>
                <input type="text" class="form-control" id="next_of_kin" name="next_of_kin" value="<?php echo $student['next_of_kin']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="next_of_kin_contact" class="form-label">Next of Kin Contact</label>
                <input type="text" class="form-control" id="next_of_kin_contact" name="next_of_kin_contact" value="<?php echo $student['next_of_kin_contact']; ?>">
            </div>

            <div class="mb-3">
                <label for="program_of_study" class="form-label">Program of Study</label>
                <input type="text" class="form-control" id="program_of_study" name="program_of_study" value="<?php echo $student['program_of_study']; ?>" required>
            </div>


            <div class="mb-3">
                <label for="courses" class="form-label">Select Courses (Max: 6)</label>
                <?php foreach ($courses as $course): ?>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="courses[]" value="<?php echo $course['course_code']; ?>"
                            <?php echo in_array($course['course_code'], $enrolled_course_codes) ? 'checked' : ''; ?>>
                        <label class="form-check-label">
                            <?php echo $course['title']; ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="mb-3">
                <label for="gpa" class="form-label">GPA</label>
                <input type="text" class="form-control" id="gpa" name="gpa" value="<?php echo $student['gpa']; ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Update Student</button>
        </form>

        <a href="../admin_dashboard.php" class="btn btn-primary">Back to Dashboard</a>
    </div>
</body>

</html>