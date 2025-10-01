<?php

require_once '../../config.php'; // Stellen Sie sicher, dass dies auf Ihre tatsächliche Konfigurationsdatei verweist

header('Content-Type: application/json');

try {
    $pdo = new PDO($dsn, $username, $password, $options);
    $cities = ['Bern', 'Chur', 'Zürich'];
    $results = [];

    foreach ($cities as $city) {
        // Bereitet eine SQL-Anfrage vor, um Wetterdaten für eine bestimmte Stadt zu holen, sortiert nach dem neuesten Datum
        $stmt = $pdo->prepare("SELECT location, temperature_celsius, created_at FROM weather_data WHERE location = :city ORDER BY created_at DESC");
        $stmt->execute([':city' => $city]); // Führt die vorbereitete Anfrage mit der Stadt als Parameter aus
        $results[$city] = $stmt->fetchAll(); // Speichert die Ergebnisse im Array $results
    }

    echo json_encode($results); // Gibt die Wetterdaten im JSON-Format aus
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]); // Gibt einen Fehler im JSON-Format aus, falls eine Ausnahme auftritt
}
