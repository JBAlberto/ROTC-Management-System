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

// Connect to database using PDO
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roster of Cadets</title>
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
                Roster of Cadets
            </div>
            <div class="user-info">
                <?php
                    echo "Welcome Back, " . htmlspecialchars($_SESSION['user']);
                ?>
            </div>
        </div>

    <div class="container">
        <h1>MMSU-ROTCU ROSTER</h1>

        <div class="top-bar">
            <input type="text" id="searchInput" placeholder="Search students..." onkeyup="filterTable()">
            <div class="action-buttons">
                <a href="add.php" class="btn-link">+ Add Student</a>
                <div class="dropdown">
                    <button class="btn-link btn-export">Export ▼</button>
                    <div class="dropdown-content">
                        <a href="backend/export.php?sort=name">By Name (A-Z)</a>
                        <a href="backend/export.php?sort=college">By College (A-Z)</a>
                        <a href="backend/export.php?sort=gender">By Gender</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-container">
            <table id="studentsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Student No.</th>
                        <th>Name</th>
                        <th>Gender</th>
                        <th>Course</th>
                        <th>College</th>
                        <th>Contact</th>
                        <th>Address</th>
                        <th>Additional Info</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $pdo->query("SELECT * FROM students");
                    while ($row = $stmt->fetch()) {
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['student_number']}</td>
                                <td>{$row['name']}</td>
                                <td>{$row['gender']}</td>
                                <td>{$row['course']}</td>
                                <td>{$row['college']}</td>
                                <td>{$row['contact_number']}</td>
                                <td>{$row['address']}</td>
                                <td>{$row['additional_info']}</td>
                                <td>
                                    <a class='btn-link' href='edit.php?id={$row['id']}'>Edit</a>
                                    <button class='btn-danger' onclick='confirmDelete(\"backend/delete.php?id={$row['id']}\")'>Delete</button>
                                </td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="js/script.js"></script>
</body>
</html>
