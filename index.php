<?php
session_start();

if (isset($_SESSION['user_id'])) {
    // User is logged in, redirect based on role
    switch ($_SESSION['role']) {
        case 'Admin':
            header('Location: admin/admin_dashboard.php');
            break;
        case 'Student':
            header('Location: student_dashboard.php');
            break;
        case 'Lecturer':
            header('Location: lecturer_dashboard/view_schedule_lecturer.php');
            break;
        default:
            echo "Invalid role!";
            session_destroy();
            header('Location: login.php');
    }
} else {
    // User is not logged in, redirect to login page
    header('Location: login.php');
}
exit;
