# Erklärung: PHP-Skript zum Abruf von Wetterdaten (Open-Meteo API)

Dieses PHP-Skript ruft Wetterdaten von der **Open-Meteo API** ab, verarbeitet die JSON-Antwort und gibt die Daten als **PHP-Array** zurück.

---

## 1. Funktionsdefinition
```php
function fetchWeatherData() {
    ...
}
```
- Verpackt den API-Abruf in eine Funktion.
- Keine Parameter → URL ist fest eingebaut.
- Rückgabewert: Wetterdaten als Array.

---

## 2. URL mit Parametern
```php
$url = "https://api.open-meteo.com/v1/forecast?latitude=46.9481,46.8499,47.3667&longitude=7.4474,9.5329,8.55&current=temperature_2m,relative_humidity_2m,apparent_temperature,is_day,rain,showers,snowfall,cloud_cover&temperature_unit=fahrenheit&timezone=auto&forecast_days=1";
```
- Abgefragte Orte: Bern, Chur, Zürich (Koordinaten).
- Parameter:
  - **latitude/longitude**: Koordinaten.
  - **current=...**: Temperatur, Luftfeuchtigkeit, Regen, etc.
  - **temperature_unit=fahrenheit**: Temperatur in Fahrenheit.
  - **timezone=auto**: automatische Zeitzone.
  - **forecast_days=1**: nur ein Tag.

---

## 3. cURL-Sitzung starten
```php
$ch = curl_init($url);
```
- Startet eine cURL-Sitzung für die angegebene URL.

---

## 4. Optionen setzen
```php
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
```
- Antwort als String zurückgeben (statt sofort auszugeben).

---

## 5. Anfrage ausführen
```php
$response = curl_exec($ch);
```
- Führt die HTTP-Anfrage aus.
- `$response` enthält die API-Antwort als JSON-String.

---

## 6. Sitzung schließen
```php
curl_close($ch);
```
- Gibt die cURL-Ressourcen wieder frei.

---

## 7. JSON dekodieren
```php
return json_decode($response, true);
```
- Wandelt JSON in ein PHP-Array um.
- `true` → Ergebnis als assoziatives Array.

Beispielausgabe:
```php
[
  "latitude" => 46.9481,
  "longitude" => 7.4474,
  "current" => [
    "temperature_2m" => 42.8,
    "rain" => 0
  ]
]
```

---

## 8. Rückgabe im Hauptskript
```php
return fetchWeatherData();
```
- Wenn das Skript eingebunden wird (`include` / `require`), liefert es direkt das Array zurück.

---

## Verbesserungen (Best Practices)
- Fehlerprüfung für cURL:
  ```php
  if ($response === false) {
      throw new RuntimeException(curl_error($ch));
  }
  ```
- JSON-Fehler abfangen:
  ```php
  return json_decode($response, true, 512, JSON_THROW_ON_ERROR);
  ```
- Timeout setzen:
  ```php
  curl_setopt($ch, CURLOPT_TIMEOUT, 10);
  ```

---

## Zusammenfassung
Der Code:
1. Baut eine API-URL für mehrere Städte.  
2. Ruft per cURL die Daten ab.  
3. Dekodiert die JSON-Antwort.  
4. Gibt das Ergebnis als Array zurück.  

Damit können die Wetterdaten in PHP weiterverarbeitet oder angezeigt werden.
