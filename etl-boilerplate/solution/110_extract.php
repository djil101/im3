<?php

$url = "https://api.open-meteo.com/v1/forecast?latitude=46.9481,46.8499,47.3667&longitude=7.4474,9.5329,8.55&current=temperature_2m,relative_humidity_2m,apparent_temperature,is_day,rain,showers,snowfall,cloud_cover&temperature_unit=fahrenheit&timezone=auto&forecast_days=1";

// Initialisiert eine cURL-Sitzung
$ch = curl_init($url);

// Setzt Optionen
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Führt die cURL-Sitzung aus und erhält den Inhalt
$response = curl_exec($ch);

// Schließt die cURL-Sitzung
curl_close($ch);

// Zeigt die JSON-Antwort an
echo $response;

?>
