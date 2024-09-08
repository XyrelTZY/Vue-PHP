<?php
// Enable CORS for local development
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Get the JSON data from the request
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['fullName']) || !isset($data['track']) || !isset($data['section'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
    exit();
}

$fullName = $data['fullName'];
$track = $data['track'];
$section = $data['section'];

// Database connection parameters
$host = 'localhost';
$db = 'school';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare and execute the SQL statement
    $stmt = $pdo->prepare('INSERT INTO students (full_name, track, section) VALUES (?, ?, ?)');
    $stmt->execute([$fullName, $track, $section]);

    echo json_encode(['status' => 'success']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
