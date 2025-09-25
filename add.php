<?php
session_start();

$host = 'localhost';
$db = 'ro_db';
$user = 'root';
$pass = '';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
// Prevent browser from caching the page
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

if (isset($_POST['submit'])) {
    $student_number = $_POST['student_number'];
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $course = $_POST['course'];
    $college = $_POST['college'];
    $contact = $_POST['contact_number'];
    $address = $_POST['address'];
    $additional = $_POST['additional_info'];

    $sql = "INSERT INTO students (student_number, name, gender, course, college, contact_number, address, additional_info)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$student_number, $name, $gender, $course, $college, $contact, $address, $additional]);

    header("Location: roster.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/roster.css">
    <link rel="icon" href="css/images/logo.jpg">
</head>
<body>
    <header>

    </header>
    <div class="sidebar">
        <div class="logo">MMSU-ROTCU</div>
        <ul class="menu">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li class="active"><a href="#">Roster of Cadets</a></li>
            <li><a href="rs.php">Reports and Schedule</a></li>
            <li><a href="att_check.php">Attendance</a></li>
            <li><a href="backend/logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="topbar">
            <div class="toggle-btn" onclick="toggleSidebar()">
                <img src="css/images/logo.jpg" alt="logo" height="70">
            </div>
            <div class="user-info">
                <?php
                    echo "Welcome Back, " . htmlspecialchars($_SESSION['user']);
                ?>
            </div>
        </div>
            <div class="container">
        <h2>Add Student</h2>
        <form method="post">
            <input type="text" name="student_number" placeholder="Student Number" required>
            <input type="text" name="name" placeholder="Name" required>
            <select name="gender" required>
                <option value="" disabled selected>Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
            <input type="text" name="course" placeholder="Course" required>
            <input type="text" name="college" placeholder="College" required>
            <input type="text" name="contact_number" placeholder="Contact Number">
            <textarea name="address" placeholder="Address"></textarea>
            <textarea name="additional_info" placeholder="Additional Info"></textarea>
            <input type="submit" name="submit" value="Add Student">
            <a href="roster.php" class="btn-link">Cancel</a>
        </form>
    </div>

    <script src="js/script.js"></script>
</body>
</html>
