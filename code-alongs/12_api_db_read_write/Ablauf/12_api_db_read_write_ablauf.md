# Ablauf: API – DB Read & Write

## Lernziele
- DB-Daten per API abrufen (Read)
- Neue Daten per POST einfügen (Write)
- Fehler- und Erfolgs-Feedback als JSON zurückgeben

## Schritte
1. `config.php` einbinden, PDO-Verbindung herstellen.
2. Header setzen: `Content-Type: application/json`.
3. Für GET: SELECT-Abfrage vorbereiten, Ergebnisse als JSON ausgeben.
4. Für POST: Daten aus `$_POST` validieren und per Prepared Statement einfügen.
5. Erfolg → `{success: true}`, Fehler → `{error: "..."} `.
6. Sicherheit: Prepared Statements, Validierung, kein SQL Injection.
7. Mini-Aufgabe: Einen neuen User einfügen und danach alle User zurückgeben.
