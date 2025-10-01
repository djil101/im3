<?php

// Transformations-Skript  als '230_transform.php' einbinden
$jsonData = include('230_transform.php');

// Dekodiert die JSON-Daten zu einem Array
$dataArray = json_decode($jsonData, true);

require_once '../../config.php'; // Bindet die Datenbankkonfiguration ein

try {
    // Erstellt eine neue PDO-Instanz mit der Konfiguration aus config.php
    $pdo = new PDO($dsn, $username, $password, $options);

    // SQL-Query mit Platzhaltern für das Einfügen von Daten
    $sql = "INSERT INTO weather_data (location, temperature_celsius, rain, showers, snowfall, cloud_cover, weather_condition) VALUES (?, ?, ?, ?, ?, ?, ?)";

    // Bereitet die SQL-Anweisung vor
    $stmt = $pdo->prepare($sql);

    // Fügt jedes Element im Array in die Datenbank ein
    foreach ($dataArray as $item) {
        $stmt->execute([
            $item['location'],
            $item['temperature_celsius'],
            $item['rain'],
            $item['showers'],
            $item['snowfall'],
            $item['cloud_cover'],
            $item['condition']
        ]);
    }

    echo "Daten erfolgreich eingefügt.";
} catch (PDOException $e) {
    die("Verbindung zur Datenbank konnte nicht hergestellt werden: " . $e->getMessage());
}
