# Was ist PHP?

- PHP ist eine serverseitige Skriptsprache, die hauptsächlich für die Webentwicklung genutzt wird.  
- Mit PHP können dynamische Webseiten erzeugt werden, indem Inhalte direkt auf dem Server generiert werden.  
- PHP-Dateien enden mit `.php`.  
- PHP-Code wird auf dem Server ausgeführt, der Browser erhält nur das generierte Inhalte.
- Typische Anwendungsfälle: Formulare verarbeiten, Datenbanken ansprechen, Sessions verwalten.  
- PHP wird häufig dazu benutzt, **Datenbankinhalte abzurufen und anzuzeigen** (z. B. mit MySQL/MariaDB).
- Um PHP auszuführen, braucht man einen Webserver mit PHP-Interpreter (Standard bei fast allen Servern). 

Hier ein Beispiel für eine minimale PHP-Datei
```php
<?php
// Minimaler PHP-Code   <- Kommentar
$message = "Hallo Welt!";  // speichert "Hallo Welt in der Variablen $message
echo $message;             // Gibt den Inhalt der Variablen aus.
?>
```

PHP und HTML können auch kombiniert werden. Eine PHP-Datei kann auch zu grossen Teilen aus HTML bestehen. 

```php
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>PHP Minimalbeispiel</title>
</head>
<body>
    <h1>Willkommen zu meiner ersten PHP-Seite</h1>

    <?php
    // Hier beginnt der PHP-Teil
    $name = "Wolfgang";
    $datum = date("d.m.Y");
    echo "<p>Hallo, $name! Heute ist der $datum.</p>";
    ?>
</body>
</html>
```
- Der HTML-Teil sorgt für Struktur und Layout.
- Der PHP-Teil (`<?php ... ?>`) wird auf dem Server ausgeführt.
- Mit `date("d.m.Y")` wird das aktuelle Datum eingefügt.
- `echo` schreibt den Text in die HTML-Ausgabe.

## Weiterführende Quellen
[PHP Introduction](https://www.geeksforgeeks.org/php/php-introduction/)