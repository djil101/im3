<?php

require_once '../../config.php';

header('Content-Type: application/json');

$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10; // Standardmäßig die letzten 10 Datensätze, falls kein Limit angegeben ist

try {
    $pdo = new PDO($dsn, $username, $password, $options);
    $stmt = $pdo->prepare("SELECT temperature_celsius, created_at FROM weather_data WHERE location = 'Bern' ORDER BY created_at DESC LIMIT :limit");
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    $results = $stmt->fetchAll();

    echo json_encode($results);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
