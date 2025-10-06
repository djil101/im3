<?php

$data = [
    'date' => '2025-10-06',
    'station_name' => 'Bahnhofplatz',
    'bike_racks' => 16,
    'bike_available_to_rent' => 5
];

require_once 'config.php'; // Bindet die Datenbankkonfiguration ein

try {
    // Erstellt eine neue PDO-Instanz mit der Konfiguration aus config.php
    $pdo = new PDO($dsn, $username, $password, $options);

    // SQL-Query mit Platzhaltern für das Einfügen von Daten
    $sql = "INSERT INTO velometer (date, station_name, bike_racks, bike_available_to_rent) VALUES (?, ?, ?, ?)";

    // Bereitet die SQL-Anweisung vor
    $stmt = $pdo->prepare($sql);

    // Fügt jedes Element im Array in die Datenbank ein (Reihenfolge wichtig)
     
        $stmt->execute([
            $data['firstname'],
            $data['lastname'],
            $data['email'],
        ]);
    

    echo "Daten erfolgreich eingefügt.";
} catch (PDOException $e) {
    die("Verbindung zur Datenbank konnte nicht hergestellt werden: " . $e->getMessage());
}