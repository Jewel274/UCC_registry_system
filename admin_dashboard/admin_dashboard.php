<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }

        .container {
            margin-top: 30px;
        }

        .dashboard-header {
            background-color: #343a40;
            color: white;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 30px;
        }

        .dashboard-header h1 {
            margin: 0;
        }

        .card {
            margin-top: 20px;
        }

        .card h5 {
            background-color: #007bff;
            color: white;
            padding: 10px;
            border-radius: 5px 5px 0 0;
        }

        .card-body {
            padding: 20px;
        }

        .search-form input {
            width: 70%;
        }

        .search-form button {
            width: 25%;
        }

        .table th,
        .table td {
            text-align: center;
        }

        .table th {
            background-color: #007bff;
            color: white;
        }

        .alert {
            margin-top: 20px;
        }

        .btn-danger {
            margin-top: 20px;
        }

        .dashboard-content {
            margin-top: 40px;
        }

        .dashboard-link {
            color: #007bff;
        }

        .dashboard-link:hover {
            text-decoration: underline;
        }

        .row>.col-md-4 {
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <div class="d-flex justify-content-between align-items-center">
                <h1>Welcome, Admin!</h1>
                <!-- Search Form -->
                <form method="POST" action="admin_dashboard.php" class="d-flex search-form">
                    <input type="text" name="search_query" class="form-control" placeholder="Search student or course" required>
                    <button type="submit" class="btn btn-primary ms-2">Search</button>
                </form>
            </div>
        </div>

        <p class="text-center">You have access to manage the UCC Registry System.</p>

        <!-- Dashboard Content -->
        <div class="row dashboard-content">
            <!-- User Management -->
            <div class="col-md-4">
                <div class="card">
                    <h5 class="card-header">User Management</h5>
                    <div class="card-body">
                        <ul>
                            <li><a href="student_management/view_students.php" class="dashboard-link">View Students</a></li>
                            <li><a href="student_management/add_student.php" class="dashboard-link">Add New Student</a></li>
                            <li><a href="student_management/delete_student.php" class="dashboard-link">Remove Student</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Course Management -->
            <div class="col-md-4">
                <div class="card">
                    <h5 class="card-header">Course Management</h5>
                    <div class="card-body">
                        <ul>
                            <li><a href="course_management/view_courses.php" class="dashboard-link">View Courses</a></li>
                            <li><a href="course_management/add_course.php" class="dashboard-link">Add New Course</a></li>
                            <li><a href="course_management/view_courses.php" class="dashboard-link">Remove Course</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Course Scheduling -->
            <div class="col-md-4">
                <div class="card">
                    <h5 class="card-header">Course Scheduling</h5>
                    <div class="card-body">
                        <ul>
                            <li><a href="lectures_management/lecture_schedule.php" class="dashboard-link">View Schedule</a></li>
                            <li><a href="lectures_management/add_schedule.php" class="dashboard-link">Add Schedule</a></li>
                            <li><a href="lectures_management/delete_schedule.php" class="dashboard-link">Remove Schedule</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Results Section -->
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $search_query = $_POST['search_query'];

            // Include database connection
            include('../db.php');

            // SQL query to search for students or courses based on the search query
            $sql = "
            SELECT Students.student_id, Students.first_name, Students.last_name, Students.personal_email, 
            Courses.course_code, Courses.title
            FROM Students
            LEFT JOIN Course_Enrollment ON Students.student_id = Course_Enrollment.student_id
            LEFT JOIN Courses ON Course_Enrollment.course_code = Courses.course_code
            WHERE Students.first_name LIKE ? OR Students.last_name LIKE ? OR Courses.course_code LIKE ?
        ";

            $stmt = $conn->prepare($sql);
            $search_param = "%$search_query%";
            $stmt->bind_param('sss', $search_param, $search_param, $search_param);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Display search results
                echo '<h2 class="text-center">Search Results</h2>';
                echo '<table class="table table-bordered">';
                echo '<thead><tr><th>Student ID</th><th>Full Name</th><th>Email</th><th>Enrolled Course</th><th>Actions</th></tr></thead>';
                echo '<tbody>';

                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . $row['student_id'] . '</td>';
                    echo '<td>' . $row['first_name'] . ' ' . $row['last_name'] . '</td>';
                    echo '<td>' . $row['personal_email'] . '</td>';
                    echo '<td>' . $row['course_code'] . ' - ' . $row['title'] . '</td>';
                    // Add Edit button with student ID as query parameter
                    echo '<td><a href="student_management/edit_student.php?student_id=' . $row['student_id'] . '" class="btn btn-warning">Edit</a></td>';
                    echo '</tr>';
                }

                echo '</tbody>';
                echo '</table>';
            } else {
                echo '<div class="alert alert-warning">No results found for "' . htmlspecialchars($search_query) . '"</div>';
            }
        }
        ?>

        <a href="../logout.php" class="btn btn-danger">Logout</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>