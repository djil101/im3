<?php
// DB-Verbindungsdaten aus externer Datei laden
require_once '../../../config.php';


// ------------------------------------------
// $_POST ist ein vordefiniertes Array in PHP, das die Daten aus einem POST-Request enthält.
// Einfügen eines neuen Datensatzes
if (isset($_POST['insert'])) {

  try {
    // Datenbankverbindung mit PDO herstellen
    $pdo = new PDO($dsn, $username, $password, $options);

    // In diesem Fall wird eine Abfrage für das Einfügen eines neuen Datensatzes in die Tabelle User erstellt.
    // Die Fragezeichen werden später durch die Werte des Arrays in der Methode execute() ersetzt.
    $sql = "INSERT INTO User (firstname, lastname, email) VALUES (?, ?, ?)";

    // Die PDO-Methode prepare() bereitet die SQL-Abfrage für die Ausführung vor.
    $stmt = $pdo->prepare($sql);

    // Die PDO-Methode execute() führt die vorbereitete SQL-Abfrage aus.
    // Die Werte der Parameter werden in einem Array übergeben.
    $result = $stmt->execute([
      $_POST['firstname'],
      $_POST['lastname'],
      $_POST['email']
    ]);

    // Wenn die Abfrage erfolgreich war, wird ein JSON-Objekt mit dem Wert 'true' zurückgegeben.
    if ($result) {
      echo json_encode($result); // Erfolgsmeldung (true)
    } else { // Wenn die Abfrage nicht erfolgreich war, wird ein JSON-Objekt mit einer Fehlermeldung zurückgegeben.
      echo json_encode(['error' => "Daten konnten nicht eingefügt werden."]);
    }
  } catch (PDOException $e) {
    // Wenn ein Fehler auftritt, wird die Fehlermeldung als JSON-Objekt zurückgegeben.
    echo json_encode(['error' => $e->getMessage()]);
  }
}
