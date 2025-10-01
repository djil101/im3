# 📑 CheatSheet `550_unload.php`

Dieses Skript dient dazu, Daten aus einer Datenbank über einen API-Endpunkt bereitzustellen. Es verarbeitet HTTP-Requests, führt SQL-Abfragen aus und gibt die Ergebnisse als JSON zurück.

---

## 🔎 Typische Struktur

```php
<?php
declare(strict_types=1);

// Content-Type für JSON setzen
header('Content-Type: application/json');

// Datenbankkonfiguration einbinden
require_once '001_config.php';

try {
    // 1. Datenbankverbindung herstellen
    $pdo = new PDO($dsn, $username, $password, $options);

    // 2. Parameter aus der URL verarbeiten
    if (isset($_GET['location'])) {
        $location = $_GET['location'];

        // 3. Prepared Statement vorbereiten
        $sql = "SELECT * FROM weather_data WHERE location = ? ORDER BY created_at DESC";
        $stmt = $pdo->prepare($sql);

        // 4. Statement mit Parameter ausführen
        $stmt->execute([$location]);

        // 5. Ergebnisse abrufen
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 6. Ausgabe als JSON
        echo json_encode($results);
    } else {
        echo json_encode(['error' => 'Parameter "location" wird benötigt.']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
```

---

## 🧩 Erklärungen Schritt für Schritt

### 1. Strict Types
```php
declare(strict_types=1);
```
- Erzwingt strenge Typprüfung (z. B. `int` ≠ `"3"`).  
- Hilft, Fehler frühzeitig zu erkennen.

---

### 2. Header für JSON setzen
```php
header('Content-Type: application/json');
```
- Antwort soll im **JSON-Format** sein.  
- Wichtig, damit Browser oder Frontend-Apps die Daten korrekt interpretieren.

---

### 3. Datenbankverbindung
```php
$pdo = new PDO($dsn, $username, $password, $options);
```
- Erstellt ein PDO-Objekt mit den Verbindungsdaten aus `001_config.php`.  
- `$dsn` enthält Datenbanktyp, Host, Datenbankname.

---

### 4. Eingabeparameter prüfen
```php
if (isset($_GET['location'])) { ... }
```
- Erwartet einen Query-Parameter wie `?location=Bern`.  
- Falls kein Parameter angegeben ist → Fehlermeldung im JSON.

---

### 5. SQL-Abfrage mit Platzhalter
```php
$sql = "SELECT * FROM weather_data WHERE location = ? ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$location]);
```
- **Prepared Statement** schützt vor SQL-Injection.  
- `?` ist ein Platzhalter, der durch den Wert von `$location` ersetzt wird.  
- `ORDER BY created_at DESC` → neueste Einträge zuerst.

---

### 6. Ergebnisse abrufen
```php
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
```
- Holt alle Zeilen als **assoziatives Array**.  
- Schlüssel = Spaltennamen (z. B. `location`, `temperature_celsius`, `created_at`).

---

### 7. Rückgabe als JSON
```php
echo json_encode($results);
```
- Wandelt PHP-Array in JSON um.  
- Gibt es an den Client zurück (Browser, JavaScript-App, etc.).

---

### 8. Fehlerbehandlung
```php
catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
```
- Falls die Datenbankverbindung oder Abfrage fehlschlägt → JSON mit Fehlermeldung.

---

## 📌 Typischer Workflow

1. **Frontend** ruft z. B. `/550_unload.php?location=Bern` auf.  
2. PHP-Skript prüft Parameter.  
3. Datenbankabfrage wird ausgeführt.  
4. Ergebnis wird als JSON zurückgegeben.  
5. Frontend (z. B. mit `fetch()` in JavaScript) verarbeitet die Daten und zeigt sie an.
