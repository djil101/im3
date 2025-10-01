<?php
// DB-Verbindungsdaten aus externer Datei laden
require_once '../../../config.php';


// ------------------------------------------
// Abfrage aller Datensätze, die den String $string in firstname, lastname oder email enthalten
// Wenn der Parameter 'search' in den Daten vorhanden ist, dann ...
if (isset($_GET['string'])) {
  try {
    $pdo = new PDO($dsn, $username, $password, $options);

    $string = $_GET['string'];

    // Die SQL-Abfrage wird in der Variablen $sql gespeichert.
    // In diesem Fall wird eine Abfrage für alle Spalten und Zeilen der Tabelle User erstellt,
    //  bei denen der Wert aus der DB-Tabellenspalte 'firstname', 'lastname' oder 'email' mit dem Wert des übertragennen 'string'-Parameters übereinstimmt.
    //  Der Wert des 'string'-Parameters wird in der Abfrage durch ein Fragezeichen ersetzt.
    //  Die Fragezeichen werden später durch die Werte des Arrays in der Methode execute() ersetzt.
    $sql = "SELECT * FROM User WHERE firstname LIKE ? OR lastname LIKE ? OR email LIKE ?";

    $stmt = $pdo->prepare($sql);

    // Die PDO-Methode execute() führt die vorbereitete SQL-Abfrage aus.
    // Das Array in der Methode execute() enthält die Werte, die die Fragezeichen in der SQL-Abfrage ersetzen.
    // In diesem Fall wird der Wert des 'string'-Parameters dreimal in das Array eingefügt.
    $stmt->execute(["%$string%", "%$string%", "%$string%"]);
    $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($searchResults);
  } catch (PDOException $e) {
    echo json_encode($e->getMessage());
  }
}
