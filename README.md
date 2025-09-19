# registry_system
# UCC Registry Management System

This is a web-based Registry Management System built using PHP and MySQL, developed as a major project for the University of the Commonwealth Caribbean (UCC). The system is designed to support administrative operations such as course scheduling, student enrollment, and lecture timetable management.

##  Project Features

###  Student Module
- Secure login/logout system
- Dashboard with class timetable
- View personal academic schedule

###  Admin Module
- Admin dashboard with full access
- Add, edit, delete and view courses
- Manage student accounts (add, delete, edit)
- Schedule and manage lectures
- View enrolled students per course

##  Technologies Used
- PHP (Core Backend)
- MySQL (Database)
- HTML/CSS (Frontend)
- Wamp Server

## Database Setup
1. Locate the file: `ucc_registry_system.sql` inside the `Source Code and Database/` folder.
2. Import it using PHPMyAdmin or the MySQL CLI.
3. Update your database credentials in `db.php`:


$servername = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "ucc_registry_system";
$conn = new mysqli($servername, $username, $password, $database);
?>
