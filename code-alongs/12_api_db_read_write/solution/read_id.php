<?php
// DB-Verbindungsdaten aus externer Datei laden
require_once '../../../config.php';



// Abfrage eines Datensatzes mit id
// Wenn der Parameter 'read' (hier: name-Parameter des Formular-Buttons) in den Daten vorhanden ist, dann ...
if (isset($_GET['id'])) {
  // try-catch-Block wird ausgeführt, um Fehler abzufangen.
  try {
    // Datenbankverbindung mit PDO herstellen
    $pdo = new PDO($dsn, $username, $password, $options);

    // Der Wert des 'id'-Parameters wird in die Variable $id gespeichert.
    $id = $_GET['id'];

    // Die SQL-Abfrage wird in der Variablen $sql gespeichert.
    // In diesem Fall wird eine Abfrage für alle Spalten und Zeilen der Tabelle User erstellt, 
    //  bei denen der Wert aus der DB-Tabellenspalte 'id' mit dem Wert des übertragennen 'id'-Parameters übereinstimmt.
    //  Der Wert des 'id'-Parameters wird in der Abfrage durch ein Fragezeichen ersetzt.
    //  Die Fragezeichen werden später durch die Werte des Arrays in der Methode execute() ersetzt.
    $sql = "SELECT * FROM User WHERE id = ?";

    // Die PDO-Methode prepare() bereitet die SQL-Abfrage für die Ausführung vor.
    $stmt = $pdo->prepare($sql);

    // Die PDO-Methode execute() führt die vorbereitete SQL-Abfrage aus.
    // Das Array in der Methode execute() enthält die Werte, die die Fragezeichen in der SQL-Abfrage ersetzen.
    // In diesem Fall wird nur ein Wert, der des 'id'-Parameters in das Array eingefügt.
    $stmt->execute([$id]);

    // Die PDO-Methode fetch() gibt die erste Zeile der Abfrage zurück.
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Die Funktion json_encode() konvertiert das assoziative Array in ein JSON-Objekt.
    echo json_encode($result);
  } catch (PDOException $e) {
    // Wenn ein Fehler auftritt, wird die Fehlermeldung als JSON-Objekt zurückgegeben.
    echo json_encode($e->getMessage());
  }
}
