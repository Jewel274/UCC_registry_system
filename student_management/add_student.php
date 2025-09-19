<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header('Location: login.php');
    exit;
}

include('../../db.php');

// Check if the form is submitted
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
    $course_code = $_POST['course_code'];  // Course selected from dropdown

    // Insert new student into the database
    $sql = "INSERT INTO students (first_name, middle_name, last_name, personal_email, student_email, mobile_number, home_contact, work_contact, home_address, next_of_kin, next_of_kin_contact, program_of_study, gpa)
            VALUES ('$first_name', '$middle_name', '$last_name', '$personal_email', '$student_email', '$mobile_number', '$home_contact', '$work_contact', '$home_address', '$next_of_kin', '$next_of_kin_contact', '$program_of_study', '$gpa')";
    if ($conn->query($sql) === TRUE) {
        $student_id = $conn->insert_id;  // Get the ID of the newly added student

        // Enroll the student in the selected course if any
        if (!empty($course_code)) {
            $enroll_sql = "INSERT INTO Course_Enrollment (student_id, course_code) VALUES ('$student_id', '$course_code')";
            $conn->query($enroll_sql);
            $message = "New student added and enrolled in the course.";
        } else {
            $message = "New student added without course enrollment.";
        }
    } else {
        $message = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7fc;
            font-family: 'Arial', sans-serif;
        }

        .container {
            margin-top: 30px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .form-label {
            font-weight: bold;
        }

        .form-control {
            border-radius: 8px;
        }

        .alert-info {
            border-radius: 8px;
            font-size: 1rem;
        }

        .btn {
            border-radius: 8px;
        }

        .back-btn {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="text-center mb-4">Add New Student</h1>

        <?php if (isset($message)) {
            echo "<div class='alert alert-info'>$message</div>";
        } ?>

        <form method="POST">
            <div class="mb-3">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name" required>
            </div>

            <div class="mb-3">
                <label for="middle_name" class="form-label">Middle Name</label>
                <input type="text" class="form-control" id="middle_name" name="middle_name">
            </div>

            <div class="mb-3">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name" required>
            </div>

            <div class="mb-3">
                <label for="personal_email" class="form-label">Personal Email</label>
                <input type="email" class="form-control" id="personal_email" name="personal_email" required>
            </div>

            <div class="mb-3">
                <label for="student_email" class="form-label">Student Email</label>
                <input type="email" class="form-control" id="student_email" name="student_email" required>
            </div>

            <div class="mb-3">
                <label for="mobile_number" class="form-label">Mobile Number</label>
                <input type="tel" class="form-control" id="mobile_number" name="mobile_number" required>
            </div>

            <div class="mb-3">
                <label for="home_contact" class="form-label">Home Contact</label>
                <input type="tel" class="form-control" id="home_contact" name="home_contact">
            </div>

            <div class="mb-3">
                <label for="work_contact" class="form-label">Work Contact</label>
                <input type="tel" class="form-control" id="work_contact" name="work_contact">
            </div>

            <div class="mb-3">
                <label for="home_address" class="form-label">Home Address</label>
                <input type="text" class="form-control" id="home_address" name="home_address">
            </div>

            <div class="mb-3">
                <label for="next_of_kin" class="form-label">Next of Kin</label>
                <input type="text" class="form-control" id="next_of_kin" name="next_of_kin" required>
            </div>

            <div class="mb-3">
                <label for="next_of_kin_contact" class="form-label">Next of Kin Contact</label>
                <input type="tel" class="form-control" id="next_of_kin_contact" name="next_of_kin_contact">
            </div>

            <div class="mb-3">
                <label for="program_of_study" class="form-label">Program of Study</label>
                <input type="text" class="form-control" id="program_of_study" name="program_of_study" required>
            </div>

            <div class="mb-3">
                <label for="gpa" class="form-label">GPA (less than 4)</label>
                <input type="text" class="form-control" id="gpa" name="gpa" pattern="^(?!4(\.0+)?$)([0-3](\.\d{1,2})?)$" required title="GPA must be a number less than 4 and up to 2 decimal places.">
                <small class="form-text text-muted">Enter a GPA less than 4, with up to 2 decimal places (e.g., 3.5).</small>
            </div>

            <!-- Dropdown for Course Selection -->
            <div class="mb-3">
                <label for="course_code" class="form-label">Course Enrollment</label>
                <select class="form-control" id="course_code" name="course_code">
                    <option value="">Select a course (Optional)</option>
                    <?php
                    // Fetch the list of available courses
                    $course_sql = "SELECT course_code, title FROM Courses";
                    $course_result = $conn->query($course_sql);

                    // Display courses as options in the dropdown
                    while ($course = $course_result->fetch_assoc()) {
                        echo '<option value="' . $course['course_code'] . '">' . $course['title'] . ' (' . $course['course_code'] . ')</option>';
                    }
                    ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Add Student</button>
        </form>

        <a href="../admin_dashboard.php" class="btn btn-secondary back-btn">Back to Dashboard</a>
    </div>
</body>

</html>