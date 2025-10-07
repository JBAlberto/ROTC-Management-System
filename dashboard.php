<?php
session_start();

$host = 'localhost';
$db = 'ro_db';
$user = 'root';
$pass = '';

// Connect to database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

// Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Cadet stats
$totalCadets = $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
$maleCadets = $pdo->query("SELECT COUNT(*) FROM students WHERE gender='Male'")->fetchColumn();
$femaleCadets = $pdo->query("SELECT COUNT(*) FROM students WHERE gender='Female'")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard</title>
<link rel="stylesheet" href="css/dashboard.css">
<link rel="icon" href="css/images/logo.jpg">
</head>
<body>

<div class="sidebar">
    <div class="logo">MMSU-ROTCU</div>
    <ul class="menu">
        <li class="active"><a href="#">Dashboard</a></li>
        <li><a href="roster.php">Roster of Cadets</a></li>
        <li><a href="rs.php">Reports and Schedule</a></li>
        <li><a href="att_check.php">Attendance</a></li>
        <li><a href="backend/logout.php">Logout</a></li>
    </ul>
</div>

<div class="main-content">
    <div class="topbar">
        <div class="toggle-btn" onclick="toggleSidebar()">
            <img src="css/images/logo.jpg" alt="logo" height="70">
            Dashboard
        </div>
        <div class="user-info">
            <?php echo "Welcome Back, " . htmlspecialchars($_SESSION['user']); ?>
        </div>
    </div>

    <div class="dashboard-cards">
        <div class="card">
            <a href="roster.php" class="buttns">
                <h3>Total Cadets</h3>
                <p><?php echo $totalCadets; ?></p>
            </a>
        </div>
        <div class="card">
            <h3>Male Cadets</h3>
            <p><?php echo $maleCadets; ?></p>
        </div>
        <div class="card">
            <h3>Female Cadets</h3>
            <p><?php echo $femaleCadets; ?></p>
        </div>
        <div class="card">
            <h3>Tasking</h3>
            <p>0</p>
        </div>
    </div>

    <div class="content">
        <h2>Upcoming Activities</h2>
        <div id="upcoming-events">
            <p>Loading upcoming activities...</p>
        </div>
    </div>
</div>

<script src="js/dashboard.js"></script>
</body>
</html>

