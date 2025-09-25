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
$type       = $_POST['type'] ?? null;

if (!$student_id || !$event_id || !$type) {
    echo json_encode(["success" => false, "message" => "Missing data"]);
    exit;
}

$current_time = date("H:i:s");

$stmt = $pdo->prepare("SELECT id FROM attendance WHERE student_id = ? AND event_id = ?");
$stmt->execute([$student_id, $event_id]);
$existing = $stmt->fetch();

if ($existing) {
    if ($type === "in") {
        $pdo->prepare("UPDATE attendance SET time_in = ? WHERE student_id = ? AND event_id = ?")
            ->execute([$current_time, $student_id, $event_id]);
    } else {
        $pdo->prepare("UPDATE attendance SET time_out = ? WHERE student_id = ? AND event_id = ?")
            ->execute([$current_time, $student_id, $event_id]);
    }
} else {
    if ($type === "in") {
        $pdo->prepare("INSERT INTO attendance (student_id, event_id, time_in) VALUES (?, ?, ?)")
            ->execute([$student_id, $event_id, $current_time]);
    } else {
        $pdo->prepare("INSERT INTO attendance (student_id, event_id, time_out) VALUES (?, ?, ?)")
            ->execute([$student_id, $event_id, $current_time]);
    }
}

echo json_encode(["success" => true, "time" => $current_time]);
