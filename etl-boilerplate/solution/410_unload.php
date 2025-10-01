<?php

// Datenbankkonfiguration einbinden
require_once '../../config.php';

// Header setzen, um JSON-Inhaltstyp zurückzugeben
header('Content-Type: application/json');

// Den Standortparameter aus der URL holen
if (isset($_GET['location'])) {
    $location = $_GET['location'];
} else {
    $location = '';
}

// Überprüfen, ob der Standortparameter angegeben wurde
if (empty($location)) {
    echo json_encode(['error' => 'Standortparameter wird benötigt.']);
    exit;
}

try {
    // Erstellt eine neue PDO-Instanz mit der Konfiguration aus config.php
    $pdo = new PDO($dsn, $username, $password, $options);

    // SQL-Query, um Daten basierend auf dem Standort auszuwählen, sortiert nach Zeitstempel
    // Verwende ein Fragezeichen (?) anstelle eines benannten Parameters
    $sql = "SELECT * FROM weather_data WHERE location = ? ORDER BY created_at DESC";

    // Bereitet die SQL-Anweisung vor
    $stmt = $pdo->prepare($sql);

    // Führt die Abfrage mit der Standortvariablen aus, die in einem Array übergeben wird
    // Die Standortvariable ersetzt das erste Fragezeichen in der SQL-Anweisung
    $stmt->execute([$location]);

    // Holt alle passenden Einträge
    $results = $stmt->fetchAll();

    // Gibt die Ergebnisse im JSON-Format zurück
    echo json_encode($results);
} catch (PDOException $e) {
    // Gibt eine Fehlermeldung zurück, wenn etwas schiefgeht
    echo json_encode(['error' => $e->getMessage()]);
}
