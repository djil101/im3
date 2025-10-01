# Erklärung: Transformation der Wetterdaten (Bezug zu `130_extract.php`)

Dieses Dokument erklärt den zweiten Skriptabschnitt (Transformation), der **auf den Rohdaten** aus `130_extract.php` aufbaut.  
Im ersten Schritt (Extract) werden mit cURL Wetterdaten der **Open-Meteo API** als JSON geholt und als **PHP-Array** zurückgegeben.  
Der hier gezeigte Code übernimmt diese Rohdaten, **normalisiert** und **anreichert** sie und gibt am Ende **JSON** zurück.

---

## 1) Rohdaten einbinden (Bezug: Extract)
```php
$data = include('130_extract.php');
```
- Führt `130_extract.php` aus und **erhält dessen Rückgabewert** (das Array mit den Wetterdaten).  
- Vorteil: lose Kopplung – das Transformationsskript kennt nur das **Interface** (Rohdaten-Array), nicht die Fetch-Details.

---

## 2) Koordinaten → Stadtnamen (Mapping)
```php
$locationsMap = [
    '46.94,7.44' => 'Bern',
    '46.84,9.52' => 'Chur',
    '47.36,8.559999' => 'Zürich',
];
```
- Ordnet **Latitude,Longitude**-Strings einem Anzeigenamen zu.  
- **Wichtig:** Hier wird **String-Gleichheit** erwartet. Das funktioniert nur, wenn `latitude`/`longitude` **genau** in derselben Schreibweise im Rohdatensatz stehen.

**Praxis-Tipp:**  
Nutze eine **Rundung/Formatierung** vor dem Key-Bau, z. B. `number_format($lat, 2) . "," . number_format($lon, 2)`, damit kleine Abweichungen nicht zum *„Unbekannt“*-Fallback führen.

---

## 3) Hilfsfunktion: Fahrenheit → Celsius
```php
function convertToCelsius($fahrenheit) {
    return ($fahrenheit - 32) * 5/9;
}
```
- Die Rohdaten werden in **Fahrenheit** abgefragt (`temperature_unit=fahrenheit`).  
- Diese Funktion rechnet zuverlässig in **Celsius** um.

**Hinweis:** Wenn du künftig in `130_extract.php` auf `temperature_unit=celsius` wechselst, **entfällt** diese Umrechnung.

---

## 4) Hilfsfunktion: Wetterbedingung bestimmen
```php
function determineCondition($cloudCover, $rain, $showers, $snowfall) {
    if ($rain > 0 || $showers > 0.2 || $snowfall > 0.2) {
        return 'regnerisch';
    } elseif ($cloudCover < 20) {
        return 'sonnig';
    } elseif ($cloudCover < 70) {
        return 'teilweise bewölkt';
    } else {
        return 'bewölkt';
    }
}
```
- **Heuristik** für eine **menschlich lesbare** Bedingung aus numerischen Werten:  
  - Regen/Schauer/Schnee → **„regnerisch“**  
  - Sehr wenig Bewölkung → **„sonnig“**  
  - Mittlere Bewölkung → **„teilweise bewölkt“**  
  - Sonst → **„bewölkt“**

**Anpassbar:** Schwellwerte (`0.2` etc.) und Reihenfolge der Checks können je nach Datenlage feiner kalibriert werden.

---

## 5) Transformations-Loop
```php
$transformedData = [];

foreach ($data as $location) {
    $cityKey = $location['latitude'] . ',' . $location['longitude'];
    $city = $locationsMap[$cityKey] ?? 'Unbekannt';

    $temperatureCelsius = round(convertToCelsius($location['current']['temperature_2m']), 2);

    $condition = determineCondition(
        $location['current']['cloud_cover'],
        $location['current']['rain'],
        $location['current']['showers'],
        $location['current']['snowfall']
    );

    $transformedData[] = [
        'location'            => $city,
        'temperature_celsius' => $temperatureCelsius,
        'rain'                => $location['current']['rain'],
        'showers'             => $location['current']['showers'],
        'snowfall'            => $location['current']['snowfall'],
        'cloud_cover'         => $location['current']['cloud_cover'],
        'condition'           => $condition
    ];
}
```
- **Eingabe:** jedes Element in `$data` repräsentiert eine **Location** mit `latitude`, `longitude` und einem `current`-Block (aktuelle Messwerte).  
- **City-Auflösung:** baut `"$lat,$lon"` als Key und schlägt ihn in `$locationsMap` nach.  
  - Fallback: **„Unbekannt“**, falls kein exakter Treffer.  
- **Celsius-Temperatur:** konvertiert via `convertToCelsius(...)` und rundet auf **2 Dezimalstellen**.  
- **`condition`**: menschliche Zusammenfassung der Wetterlage.  
- **Ausgabeformat:** ein **flaches Array** je Stadt mit Feldern `location, temperature_celsius, rain, showers, snowfall, cloud_cover, condition`.

**Bezug zu `130_extract.php`:**  
- Der `current`-Block stammt **direkt** aus dem JSON der Open-Meteo-API (im Extract festgelegt via `current=...`).  
- Die Transformationsschicht **reduziert** und **vereinheitlicht** die Struktur – ideal für Weitergabe an Frontend/DB/CSV.

---

## 6) JSON-Ausgabe (statt Echo)
```php
$jsonData = json_encode($transformedData, JSON_PRETTY_PRINT);
return $jsonData;
```
- **Serialisiert** das Ergebnis-Array als **hübsches JSON** (`JSON_PRETTY_PRINT`).  
- `return` (statt `echo`) macht das Skript **einbettbar** – wer dieses Skript `include`/`require`t, bekommt direkt die **JSON-String**-Repräsentation zurück.

**Best Practice:**  
- Für robustes Fehler-Handling: `json_encode(..., JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE)` nutzen und Exceptions oben `try/catch` behandeln.

---

## Zusammenspiel Extract ↔ Transform (End-to-End)
1. **Extract (`130_extract.php`)**: Holt per cURL JSON, prüft Status, dekodiert und gibt **PHP-Array** zurück.  
2. **Transform (dieser Code)**: Mapped Koordinaten → Stadt, rechnet Temperatur um, bestimmt `condition` und gibt **JSON** zurück.  
3. **Load/Anzeige**: Weitere Schritte (Persistenz, Rendering) können mit dem kompakten JSON direkt arbeiten.

---

## Mögliche Verbesserungen
- **Koordinaten normalisieren** (z. B. auf 2–3 Nachkommastellen runden), damit das Mapping stabil ist.  
- **Fehlerbehandlung**: prüfen, ob Felder in `$location['current']` wirklich existieren (defensive Defaults).  
- **Einheitlichkeit**: Einheit (°C vs. °F) zentral konfigurieren (ENV/Config).  
- **Lokalisierung**: `condition` sprachabhängig (de/en/…); ggf. Mapping-Tabelle.  
- **Erweiterungen**: Wind, Feuchte, gefühlte Temperatur (`apparent_temperature`) ergänzen.

---

## Kurzbeispiel: robustes Koordinaten-Mapping
```php
$lat = number_format((float)$location['latitude'], 2, '.', '');
$lon = number_format((float)$location['longitude'], 2, '.', '');
$cityKey = "{$lat},{$lon}";
$city = $locationsMap[$cityKey] ?? 'Unbekannt';
```
- Vermeidet **Floating-Point-Drift** und erhöht die Trefferquote im Mapping.
