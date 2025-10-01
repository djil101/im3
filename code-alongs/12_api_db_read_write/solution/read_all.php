<?php
// DB-Verbindungsdaten aus externer Datei laden
require_once '../../../config.php';

// ------------------------------------------
// Abfrage aller Daten aus der Tabelle User
// Datenbankverbindung mit PDO herstellen
// Mit den Variablen aus der Datei config.php wird eine Datenbankverbindung.
// Dazu wird ein PDO-Objekt erstellt und in der Variablen $pdo gespeichert.
$pdo = new PDO($dsn, $username, $password, $options);

// Die SQL-Abfrage wird in der Variablen $sql gespeichert.
// In diesem Fall wird eine Abfrage für alle Spalten und Zeilen der Tabelle User erstellt.
$sql = "SELECT * FROM User";

// Die Methode prepare() bereitet die SQL-Abfrage für die Ausführung vor.
// Das Ergebnis wird in der Variablen $stmt gespeichert.
$stmt = $pdo->prepare($sql);

// Die PDO-Methode execute() führt die vorbereitete SQL-Abfrage aus.
$stmt->execute();

// Die PDO-Methode fetchAll() gibt alle Zeilen der Abfrage zurück.
// Die Konstante PDO::FETCH_ASSOC gibt an, dass die Zeilen als assoziatives Array zurückgegeben werden sollen.
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Die Funktion json_encode() konvertiert das assoziative Array in ein JSON-Objekt.
echo json_encode($users);
