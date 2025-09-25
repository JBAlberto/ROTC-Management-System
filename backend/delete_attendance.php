<?php
session_start();
header("Content-Type: application/json");

$host = 'localhost';
$db   = 'ro_db';
$user = 'root';
$pass = '';

if (!isset($_SESSION['user'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "DB error"]);
    exit;
}

$student_id = $_POST['student_id'] ?? null;
$event_id   = $_POST['event_id'] ?? null;

if (!$student_id || !$event_id) {
    echo json_encode(["success" => false, "message" => "Missing data"]);
    exit;
}

$stmt = $pdo->prepare("DELETE FROM attendance WHERE student_id = ? AND event_id = ?");
$stmt->execute([$student_id, $event_id]);

echo json_encode(["success" => true]);
