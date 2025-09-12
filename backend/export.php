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
// Determine sort order
$sort = $_GET['sort'] ?? 'name';
$orderBy = "name ASC";

if ($sort === 'college') {
    $orderBy = "college ASC, name ASC";
} elseif ($sort === 'gender') {
    $orderBy = "gender ASC, name ASC";
}

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=students_export.csv');

$output = fopen('php://output', 'w');
fputcsv($output, ['ID', 'Student Number', 'Name', 'Gender', 'Course', 'College', 'Contact', 'Address', 'Additional Info']);

$stmt = $pdo->query("SELECT * FROM students ORDER BY $orderBy");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, $row);
}
fclose($output);
exit;
