<?php
// DB-Verbindungsdaten aus externer Datei laden
require_once '../../../config.php';

// ------------------------------------------
// Abfrage aller Daten aus der Tabelle User
$pdo = new PDO($dsn, $username, $password, $options);
$sql = "SELECT * FROM User";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ausgabe als JSON
// Header setzen, damit der Browser weiß, dass JSON-Daten gesendet werden.
header('Content-Type: application/json');
// Die Funktion json_encode() konvertiert das assoziative Array in ein JSON-Objekt.
echo json_encode($users, JSON_PRETTY_PRINT);
