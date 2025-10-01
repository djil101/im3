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


// Todo: Den Inhalt von $users als Text ausgeben
echo '<pre>';
print_r($users);
echo '</pre><br><br>';

// ------------------------------------------
// Todo: Array mit Schleife ausgeben
foreach ($users as $user) {
  echo 'ID: ' . $user['id'] . '<br>';
  echo 'Vorname: ' . $user['firstname'] . '<br>';
  echo 'Nachname: ' . $user['lastname'] . '<br>';
  echo 'Email: ' . $user['email'] . '<br>';
  echo '<hr>';
}

// ------------------------------------------
// Todo: $users als JSON ausgeben
echo json_encode($users);
echo '<hr>';

// ------------------------------------------
// Einzene Daten abfragen
// Beispiel: Daten des Users mit der ID 1 abfragen
$userId = 1;

// SQL-Abfrage mit ?-Platzhalter für die ID
$sql = "SELECT * FROM User WHERE id = ?";

// Prepared Statement vorbereiten
$stmt = $pdo->prepare($sql);

// Prepared Statement ausführen und ID als Parameter übergeben. 
// Parameter MUSS als Array übergeben werden.
$stmt->execute([$userId]);

// Einzelne Zeile als assoziatives Array abrufen
// fetch() statt fetchAll(), da nur eine Zeile erwartet wird
$user = $stmt->fetch();

// Einzelnen User ausgeben
echo '<pre>';
print_r($user);
echo '</pre><br><br>';
