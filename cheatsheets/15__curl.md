# 📑 CheatSheet `cURL` in PHP

---

## 🔎 Was ist cURL?  
- **cURL** steht für **Client URL** und ist ein Open-Source-Tool, das Daten über URLs übertragen kann.  
- Es unterstützt viele Protokolle (z. B. HTTP, HTTPS, FTP, SMTP, …).  
- In PHP gibt es eine eingebaute cURL-Erweiterung, mit der HTTP-Anfragen gesendet und Antworten verarbeitet werden können.  
- Typische Einsatzgebiete sind:  
  - Kommunikation mit **APIs** (z. B. Wetterdaten, Zahlungsanbieter, Social Media)  
  - **Automatisierte Datenabfragen** von externen Diensten  
  - **Formularübermittlungen** oder **Datei-Uploads**  
- Vorteil: cURL ist flexibel, robust und funktioniert unabhängig von `file_get_contents()` auch bei komplexen Anfragen (z. B. mit Headern, Sessions, Authentifizierung).

---

## 1. Grundaufbau
```php
<?php
$ch = curl_init("https://api.example.com/data");   // Session starten
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);   // Antwort als String zurückgeben
$response = curl_exec($ch);                       // Anfrage ausführen
curl_close($ch);                                  // Session schließen
echo $response;                                   // Antwort verarbeiten
```

---

## 2. GET-Request mit Parametern
```php
$url = "https://api.example.com/data?query=test";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);
```

---

## 3. POST-Request mit Daten
```php
$data = [
    'username' => 'wolfgang',
    'password' => 'geheim'
];

$ch = curl_init("https://api.example.com/login");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

echo $response;
```

---

## 4. JSON-POST mit Headern
```php
$data = json_encode(['city' => 'Chur', 'country' => 'CH']);

$ch = curl_init("https://api.example.com/weather");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Content-Length: ' . strlen($data)
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

echo $response;
```

---

## 5. Wichtige Optionen
- `CURLOPT_URL` → Ziel-URL setzen  
- `CURLOPT_RETURNTRANSFER` → Antwort als String zurückgeben statt direkt ausgeben  
- `CURLOPT_POST` → POST-Methode aktivieren  
- `CURLOPT_POSTFIELDS` → Daten im Request senden  
- `CURLOPT_HTTPHEADER` → Header setzen (z. B. Content-Type, Authorization)  
- `CURLOPT_TIMEOUT` → Timeout für die Anfrage  
- `CURLOPT_FOLLOWLOCATION` → Weiterleitungen automatisch folgen  

---

## 6. Fehlerbehandlung
```php
$ch = curl_init("https://api.example.com/data");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);

if ($response === false) {
    echo "cURL-Fehler: " . curl_error($ch);
}

curl_close($ch);
```

---

👉 Damit hast du eine solide Grundlage, um mit cURL in PHP APIs und externe Dienste anzusprechen.
