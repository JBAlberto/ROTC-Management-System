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

// Get ID from URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int) $_GET['id'];

    $stmt = $pdo->prepare("DELETE FROM students WHERE id = ?");
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        // Deletion successful
        header("Location: ../roster.php?msg=deleted");
        exit;
    } else {
        // No row deleted
        echo "No record found with ID $id.";
        exit;
    }
} else {
    echo "Invalid ID.";
    exit;
}
?>
