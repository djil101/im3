# Ablauf: API – DB Read

## Lernziele
- Daten aus DB per API als JSON bereitstellen
- GET-Parameter für Filter nutzen (z. B. id oder name)
- Fehlerfälle behandeln

## Schritte
1. `config.php` einbinden, PDO-Verbindung herstellen.
2. Header setzen: `Content-Type: application/json`.
3. Optional: Parameter `id` prüfen (über `$_GET`).
4. SELECT mit prepared statement und Parameter ausführen.
5. Ergebnisse als JSON (`json_encode`) ausgeben.
6. Fehler mit `{error: "..."} ` behandeln.
7. Mini-Aufgabe: API bauen, die nach `id` filtert.
