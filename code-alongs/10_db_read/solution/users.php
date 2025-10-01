<?php
// DB-Verbindungsdaten aus externer Datei laden
require_once '../../../config.php';

// Alle Datensätze aus der Tabelle User abfragen
$pdo = new PDO($dsn, $username, $password, $options);
$sql = "SELECT * FROM User";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Todo: HTML-Seite, die das $users als Tabelle anzeigt.
?>
<!DOCTYPE html>
<html lang="de">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Benutzerliste</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <h1>Benutzerliste</h1>
  <table>
    <thead>
      <tr>
        <th>Vorname</th>
        <th>Nachname</th>
        <th>Email</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($users as $user): ?>
        <tr>
          <td><?php echo $user['firstname']; ?></td>
          <td><?php echo $user['lastname']; ?></td>
          <td><?php echo $user['email']; ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body>

</html>