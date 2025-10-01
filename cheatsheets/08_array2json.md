# 📑 CheatSheet `Array ↔ JSON` in PHP

---

## 🔎 Grundlagen
- In PHP werden Daten oft in **Arrays** oder **assoziativen Arrays** gespeichert.  
- Für den Austausch mit Frontend oder APIs werden sie in **JSON** umgewandelt.  
- PHP bietet dafür die Funktionen:
  - `json_encode()` → Array → JSON  
  - `json_decode()` → JSON → Array oder Objekt  

---

## 1. Array → JSON
```php
<?php
$person = [
    "name" => "Lea",
    "age" => 23,
    "skills" => ["PHP", "JavaScript", "CSS"]
];

// Array in JSON konvertieren
$json = json_encode($person);

echo $json;
```

**Ausgabe:**
```json
{"name":"Lea","age":23,"skills":["PHP","JavaScript","CSS"]}
```

👉 Mit `JSON_PRETTY_PRINT` lässt sich das JSON lesbarer formatieren:
```php
echo json_encode($person, JSON_PRETTY_PRINT);
```

---

## 2. JSON → Array
```php
<?php
$json = '{"name":"Lea","age":23,"skills":["PHP","JavaScript","CSS"]}';

// JSON in Array konvertieren
$array = json_decode($json, true);

print_r($array);
```

**Ausgabe (PHP-Array):**
```
Array
(
    [name] => Lea
    [age] => 23
    [skills] => Array
        (
            [0] => PHP
            [1] => JavaScript
            [2] => CSS
        )
)
```

👉 Der zweite Parameter `true` sorgt dafür, dass **assoziative Arrays** statt Objekte erzeugt werden.  

---

## 3. JSON → Objekt
```php
<?php
$json = '{"name":"Lea","age":23}';

// Ohne zweiten Parameter → Objekt
$obj = json_decode($json);

echo $obj->name; // Lea
echo $obj->age;  // 23
```

---

## 4. Typische Anwendungsfälle
- API-Daten empfangen (`fetch()` im Frontend → `json_decode()` in PHP)  
- Datenbankabfragen als JSON bereitstellen (`json_encode()` in PHP → `fetch()` im Frontend)  
- Konfigurationen in JSON speichern und in PHP einlesen  

---

## 5. Fehlerbehandlung
```php
$json = '{"name":"Lea", "age":}'; // fehlerhaftes JSON

$data = json_decode($json, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo "JSON-Fehler: " . json_last_error_msg();
}
```

---

👉 Damit hast du den kompletten Überblick, wie man Arrays und JSON in PHP hin und her konvertiert.
