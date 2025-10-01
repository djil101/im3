# Erklärung: Laden der transformierten Wetterdaten in die Datenbank (Bezug zu `130_extract.php` & `230_transform.php`)

Dieses Dokument erläutert den dritten Schritt der ETL-Kette (**E**xtract → **T**ransform → **L**oad).  
Nach **`130_extract.php`** (Rohdaten holen) und **`230_transform.php`** (Koordinaten-zu-Stadt, °F→°C, `condition` bestimmen) übernimmt dieses Skript die Aufgabe, die **bereits transformierten Daten** in eine **Datenbanktabelle** zu schreiben.

---

## 1) Eingabe: Transformations-Ergebnis beziehen
```php
$jsonData = include('230_transform.php');
$dataArray = json_decode($jsonData, true);
```
- `include('230_transform.php')` führt den Transformationsschritt aus und liefert dessen **JSON-String** zurück.  
- `json_decode(..., true)` wandelt diesen JSON-String in ein **assoziatives PHP-Array** um (d. h. `['location' => ..., 'temperature_celsius' => ...]`).  
- **Bezug:** Die Struktur der Einträge entspricht der in `230_transform.php` erzeugten kompakten Form:  
  ```json
  [
    {
      "location": "Bern",
      "temperature_celsius": 6.11,
      "rain": 0,
      "showers": 0.1,
      "snowfall": 0,
      "cloud_cover": 80,
      "condition": "bewölkt"
    },
    ...
  ]
  ```

---

## 2) DB‑Konfiguration laden
```php
require_once '001_config.php';
```
- Lädt die **PDO‑Konfigurationswerte** (z. B. `$dsn`, `$username`, `$password`, `$options`).  
- Üblicherweise enthält `001_config.php` u. a.:  
  ```php
  $dsn = "mysql:host=localhost;dbname=mein_db;charset=utf8mb4";
  $options = [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES => false,
  ];
  ```

---

## 3) Datenbankverbindung herstellen
```php
$pdo = new PDO($dsn, $username, $password, $options);
```
- Erstellt eine **PDO‑Instanz** mit den geladenen Einstellungen.  
- Dank `ERRMODE_EXCEPTION` werden DB‑Fehler als Exceptions geworfen → vereinfachtes Error‑Handling.

---

## 4) Prepared Statement (INSERT) vorbereiten
```php
$sql = "INSERT INTO weather_data
        (location, temperature_celsius, rain, showers, snowfall, cloud_cover, weather_condition)
        VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);
```
- Nutzt **Platzhalter (`?`)** für sichere Parameterbindung → schützt vor **SQL‑Injection**.  
- Erwartete Tabellenspalten (vereinbart mit Transform):  
  - `location` (z. B. VARCHAR)  
  - `temperature_celsius` (FLOAT/DECIMAL)  
  - `rain`, `showers`, `snowfall`, `cloud_cover` (numerische Typen)  
  - `weather_condition` (VARCHAR; entspricht `condition` aus Transform)

> **Hinweis:** Die Spaltennamen in der DB können von den API‑Feldern abweichen – hier wurde `condition` → `weather_condition` gemappt.

---

## 5) Batch‑Insert aller Einträge
```php
foreach ($dataArray as $item) {
    $stmt->execute([
        $item['location'],
        $item['temperature_celsius'],
        $item['rain'],
        $item['showers'],
        $item['snowfall'],
        $item['cloud_cover'],
        $item['condition']
    ]);
}
```
- Iteriert über jedes Array‑Element und führt das **Prepared Statement** mit den Werten aus.  
- Die Reihenfolge der Werte **muss** der Reihenfolge der `?` im SQL entsprechen.  
- Vorteil der Vorbereitung: Das SQL wird einmal geparst, dann nur noch **Parameter** gebunden.

**Best Practice (Performance):**  
Viele Inserts mit **Transaktion** kapseln:
```php
$pdo->beginTransaction();
foreach ($dataArray as $item) {
    $stmt->execute([ /* Werte wie oben */ ]);
}
$pdo->commit();
```
So werden I/O‑Overheads reduziert und Konsistenz gesichert.

---

## 6) Erfolgsmeldung & Fehlerbehandlung
```php
echo "Daten erfolgreich eingefügt.";
...
} catch (PDOException $e) {
    die("Verbindung zur Datenbank konnte nicht hergestellt werden: " . $e->getMessage());
}
```
- Bei Erfolg: einfache Textausgabe.  
- Bei Fehlern: Abbruch mit Meldung aus der Exception. Für Produktion: lieber **loggen** und eine generische User‑Meldung ausgeben.

---

## Zusammenspiel der ETL‑Schritte (End‑to‑End)

1) **Extract (`130_extract.php`)**  
   - Holt aktuelle Wetterwerte (Open‑Meteo), z. B. `temperature_2m`, `rain`, `cloud_cover`.  
   - Gibt ein **PHP‑Array** mit Locations + `current`‑Block zurück.

2) **Transform (`230_transform.php`)**  
   - Mappt Koordinaten → **Stadt** (`location`).  
   - Rechnet **Fahrenheit → Celsius** (`temperature_celsius`).  
   - Leitet **`condition`** aus Regen/Schauern/Schnee/Bewölkung ab.  
   - Gibt ein **kompaktes JSON** mit den Zielfeldern zurück.

3) **Load (dieses Skript)**  
   - `include` zieht das JSON aus Transform, `json_decode` macht daraus ein **Array**.  
   - **Prepared INSERT** schreibt jedes Element in die Tabelle `weather_data`.

**Visualisierung:** Extract → Transform → Load → **DB**.

---

## Optionale Verbesserungen

- **Idempotenz / Upsert**: doppelte Daten vermeiden (z. B. Unique‑Key auf `location`+Zeitstempel, dann `INSERT ... ON DUPLICATE KEY UPDATE ...`).  
- **Transaktionen**: bei Batch‑Inserts fast immer sinnvoll (Performance & atomare Garantien).  
- **Validierung**: vor dem `execute()` prüfen, ob alle erwarteten Felder vorhanden sind und Typen passen.  
- **Fehlerstrategie**: in `catch` keinen sensiblen DB‑Text an Endnutzer, sondern Logging + generische Meldung.  
- **Schema‑Notiz**: `DECIMAL(5,2)` statt FLOAT für reproduzierbare Rundungen; `cloud_cover` als `TINYINT` (0–100).

---

## Beispieltabellenschema (MySQL/MariaDB)

```sql
CREATE TABLE weather_data (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  location VARCHAR(64) NOT NULL,
  temperature_celsius DECIMAL(5,2) NOT NULL,
  rain DECIMAL(5,2) NOT NULL,
  showers DECIMAL(5,2) NOT NULL,
  snowfall DECIMAL(5,2) NOT NULL,
  cloud_cover TINYINT UNSIGNED NOT NULL,
  weather_condition VARCHAR(32) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```
- Typen an echte Wertebereiche anpassen; wo sinnvoll `UNSIGNED`.  
- Für Upsert eine **Unique‑Constraint** definieren (z. B. auf `location` + `created_at` im Minutenraster).

---

## Kurzfazit
- Dieses Skript implementiert den **Load‑Schritt**: nimmt das **Transform‑JSON**, macht ein Array daraus und speichert **sicher** via **Prepared Statements** in die Datenbank.  
- Zusammen mit `130_extract.php` (Daten holen) und `230_transform.php` (Daten aufbereiten) ergibt sich ein klare, modulare ETL‑Pipeline.
