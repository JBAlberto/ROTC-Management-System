<?php
$host = 'localhost';
$db   = 'ro_db';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    http_response_code(500);
    echo json_encode(['success'=>false,'error'=>$e->getMessage()]);
    exit;
}

$action = $_GET['action'] ?? '';

switch($action) {
    case 'list':
        $stmt = $pdo->query("SELECT * FROM events ORDER BY event_date ASC");
        echo json_encode($stmt->fetchAll());
        break;

    case 'add':
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $pdo->prepare("INSERT INTO events (title, event_date, description) VALUES (?,?,?)");
        $stmt->execute([$data['title'], $data['event_date'], $data['description']]);
        echo json_encode(['success'=>true,'id'=>$pdo->lastInsertId()]);
        break;

    case 'edit':
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $pdo->prepare("UPDATE events SET title=?, event_date=?, description=? WHERE id=?");
        $stmt->execute([$data['title'], $data['event_date'], $data['description'], $data['id']]);
        echo json_encode(['success'=>true]);
        break;

    case 'delete':
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $pdo->prepare("DELETE FROM events WHERE id=?");
        $stmt->execute([$data['id']]);
        echo json_encode(['success'=>true]);
        break;

    case 'upcoming':
        // ✅ Use DATE() to work with DATETIME or DATE columns
        $stmt = $pdo->query("SELECT * FROM events WHERE DATE(event_date) >= CURDATE() ORDER BY event_date ASC LIMIT 5");
        $events = $stmt->fetchAll();
        echo json_encode($events);
        break;

    default:
        echo json_encode(['success'=>false,'error'=>'Invalid action']);
        break;
}
