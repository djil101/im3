<?php
// Titel der Seite setzen
$title = "Übungsdateien für das ETL-Projekt";

// Pfad zum Verzeichnis, in dem sich die Dateien befinden
$dir = ".";

// Header für die HTML-Seite
echo "<!DOCTYPE html>\n";
echo "<html lang='de'>\n";
echo "<head>\n";
echo "  <meta charset='UTF-8'>\n";
echo "  <meta name='viewport' content='width=device-width, initial-scale=1.0'>\n";
echo "  <title>$title</title>\n";
echo "</head>\n";
echo "<body>\n";
echo "<h1>$title</h1>\n";
echo" Diese Datei zeigt dir alle Dateien an, die auf deinem Webserver liegen: <br>";
echo" Falls du hier keine, zu wenige oder zu viele Dateien siehst, musst du deinen Webserver aufräumen ⚠️ <br>";

echo "<ul>\n";


// Dateien und Verzeichnisse im aktuellen Verzeichnis lesen und sortieren
$files = scandir($dir);
sort($files); // Sortiert die Dateien alphabetisch

foreach ($files as $entry) {
    if ($entry != "." && $entry != ".." && $entry != "index.php" && !is_dir($dir."/".$entry)) {
        echo "  <li><a href='$entry'>$entry</a></li>\n";
    }
}

echo "</ul>\n";
echo "</body>\n";
echo "</html>\n";
?>