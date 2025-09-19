<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header('Location: login.php');
    exit;
}

include('../../db.php');

// Query to get all students
$sql = "SELECT * FROM students";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>View Students</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h1>View Students</h1>
        <p>Here are all the students in the system.</p>

        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Program of Study</th>
                    <th>GPA</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($student = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . $student['student_id'] . "</td>
                                <td>" . $student['first_name'] . "</td>
                                <td>" . $student['last_name'] . "</td>
                                <td>" . $student['student_email'] . "</td>
                                <td>" . $student['program_of_study'] . "</td>
                                <td>" . $student['gpa'] . "</td>
                                <td>
                                    <a href='edit_student.php?student_id=" . $student['student_id'] . "' class='btn btn-warning'>Edit</a>
                                    <a href='delete_student.php?id=" . $student['student_id'] . "' class='btn btn-danger'>Delete</a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No students found.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <a href="../admin_dashboard.php" class="btn btn-primary">Back to Dashboard</a>
    </div>
</body>

</html>