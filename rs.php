<?php
session_start();

$host = 'localhost';
$db = 'ro_db';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event Scheduler</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="icon" href="css/images/logo.jpg">
</head>
<body>
    <div class="sidebar">
        <div class="logo">MMSU-ROTCU</div>
        <ul class="menu">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="roster.php">Roster of Cadets</a></li>
            <li class="active"><a href="rs.php">Reports and Schedule</a></li>
            <li><a href="att_check.php">Attendance</a></li>
            <li><a href="backend/logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="topbar">
            <div class="toggle-btn">
                <img src="css/images/logo.jpg" alt="logo" height="70">
                Reports and Schedule
            </div>
            <div class="user-info">
                <?php echo "Welcome Back, " . htmlspecialchars($_SESSION['user']); ?>
            </div>
        </div>

        <h2>Add an Event</h2>
        <form id="event-form">
            <input type="hidden" id="event-id">
            <input type="text" id="title" placeholder="Event Title" required>
            <input type="date" id="event_date" required>
            <textarea id="description" placeholder="Description"></textarea>
            <button type="submit">Save Event</button>
            <button type="button" onclick="clearForm()">Cancel</button>
            <button type="button" id="delete-btn" style="background:red;color:white;display:none;">Delete Event</button>
        </form>

        <div id="calendar-controls">
            <button onclick="changeMonth(-1)">◀ Prev</button>
            <span id="current-month"></span>
            <button onclick="changeMonth(1)">Next ▶</button>
        </div>

        <div id="calendar-header"></div>
        <div id="calendar"></div>
    </div>

    <script src="js/rs.js"></script>
</body>
</html>
