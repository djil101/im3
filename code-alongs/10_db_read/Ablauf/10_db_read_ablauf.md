# Ablauf: Datenbank lesen (PDO)

## Lernziele
- Mit PDO eine Datenbankverbindung herstellen
- SELECT-Statements vorbereiten und ausführen
- Ergebnisse in verschiedenen Formaten ausgeben

## Schritte
1. `config.php` einbinden mit $dsn, $username, $password.
2. PDO-Verbindung herstellen (mit Error-Modus Exception).
3. SELECT-Abfrage vorbereiten und mit `execute()` ausführen.
4. Ergebnisse mit `fetchAll(PDO::FETCH_ASSOC)` abholen.
5. Ausgabe als Debug (`print_r`), HTML-Tabelle oder JSON zeigen.
6. Mini-Aufgabe: Alle User ausgeben, nur id, firstname, lastname.
