<?php

/**
 * users.php 
 * Ziel: ALLE Datensätze in einer HTML-Struktur als Tabelle ausgeben.
 * 
 * HINWEISE:
 * 1. Nutze require_once 'config.php' für $dsn, $username, $password, $options.
 * 2. Stelle eine PDO-Verbindung her.
 * 3. Baue ein SELECT, das ALLE Zeilen aus der Tabelle Userausgibt.
 * 4. Verwende prepared statements, auch wenn keine Parameter vorhanden sind (einheitlich).
 * 5. Führe das Statement aus.
 * 6. Hole alle Zeilen mit fetchAll().
 * 
 * 7. Beende den PHP-Block und baue eine HTML-Seite, die das $users als Tabelle anzeigt.
 * 8. Im HTML-Teil: Nutze eine foreach-Schleife, um die Zeilen in der Tabelle auszugeben.
 * 9. Beende die foreach-Schleife korrekt.
 
 */

// 1.

// 2.

// 3.

// 4.

// 5.

// 6.

// 7. Beende den PHP-Block und baue eine HTML-Seite, die das $users als Tabelle anzeigt.
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
      <!-- // 8. -->
      <tr>
        <td><!-- PHP-Ausgabe für 'firstname' --></td>
        <td><!-- PHP-Ausgabe für 'lastname' --></td>
        <td><!-- PHP-Ausgabe für 'email' --></td>
      </tr>
      <!-- // 9. -->
    </tbody>
  </table>
</body>

</html>