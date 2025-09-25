<?php
session_start();

$host = 'localhost';
$db   = 'ro_db';
$user = 'root';
$pass = '';

if (!isset($_SESSION['user'])) {
    die("Unauthorized");
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("DB error: " . $e->getMessage());
}

// get all events
$events = $pdo->query("SELECT id, title FROM events ORDER BY event_date ASC")->fetchAll(PDO::FETCH_ASSOC);

// prepare header
$headers = ["Student No.", "Name", "Course", "College"];
foreach ($events as $event) {
    $headers[] = $event['title'];
}

// fetch all students
$students = $pdo->query("SELECT id, student_number, name, course, college FROM students ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);

// open output
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename="attendance_report.csv"');
$output = fopen("php://output", "w");

// write header
fputcsv($output, $headers);

// write rows
foreach ($students as $stu) {
    $row = [
        $stu['student_number'],
        $stu['name'],
        $stu['course'],
        $stu['college']
    ];

    foreach ($events as $event) {
        $stmt = $pdo->prepare("SELECT time_in FROM attendance WHERE student_id = ? AND event_id = ?");
        $stmt->execute([$stu['id'], $event['id']]);
        $att = $stmt->fetch();
        $row[] = $att ? "Present" : "Absent";
    }

    fputcsv($output, $row);
}

fclose($output);
exit;
