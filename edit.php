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

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM students WHERE id=?");
$stmt->execute([$id]);
$student = $stmt->fetch();

if (!$student) {
    die("Student not found!");
}

if (isset($_POST['update'])) {
    $student_number = $_POST['student_number'];
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $course = $_POST['course'];
    $college = $_POST['college'];
    $contact = $_POST['contact_number'];
    $address = $_POST['address'];
    $additional = $_POST['additional_info'];

    $sql = "UPDATE students SET student_number=?, name=?, gender=?, course=?, college=?, contact_number=?, address=?, additional_info=? WHERE id=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$student_number, $name, $gender, $course, $college, $contact, $address, $additional, $id]);

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
            <li><a href="att.php">Attendance</a></li>
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
        <h2>Edit Student</h2>
        <form method="post">
            <input type="text" name="student_number" value="<?= htmlspecialchars($student['student_number']) ?>" required>
            <input type="text" name="name" value="<?= htmlspecialchars($student['name']) ?>" required>
            <select name="gender" required>
                <option value="Male" <?= $student['gender'] === 'Male' ? 'selected' : '' ?>>Male</option>
                <option value="Female" <?= $student['gender'] === 'Female' ? 'selected' : '' ?>>Female</option>
            </select>
            <input type="text" name="course" value="<?= htmlspecialchars($student['course']) ?>" required>
            <input type="text" name="college" value="<?= htmlspecialchars($student['college']) ?>" required>
            <input type="text" name="contact_number" value="<?= htmlspecialchars($student['contact_number']) ?>">
            <textarea name="address"><?= htmlspecialchars($student['address']) ?></textarea>
            <textarea name="additional_info"><?= htmlspecialchars($student['additional_info']) ?></textarea>
            <input type="submit" name="update" value="Update Student">
            <a href="roster.php" class="btn-link">Cancel</a>
        </form>
    </div>
    <script src="js/script.js"></script>
</body>
</html>
