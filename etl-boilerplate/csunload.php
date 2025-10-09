<?php
declare(strict_types=1);

require_once 'config.php';
header('Content-Type: application/json; charset=utf-8');

try {
    $pdo = new PDO($dsn, $username, $password, $options);
    $sql = "SELECT * FROM `velometer`";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $results = $stmt->fetchAll();

    echo json_encode($results, JSON_PRETTY_PRINT);}
catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
