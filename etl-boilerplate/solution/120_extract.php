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

// Dekodiert die JSON-Antwort
$data = json_decode($response, true);

// Informationen anzeigen
foreach ($data as $location) {
    echo "Standort: Breitengrad " . $location['latitude'] . ", Längengrad " . $location['longitude'] . "<br>";
    echo "Zeitzone: " . $location['timezone'] . "<br>";
    echo "Aktuelles Wetter:<br>";
    echo "- Temperatur: " . $location['current']['temperature_2m'] . "°F<br>";
    echo "- Relative Feuchtigkeit: " . $location['current']['relative_humidity_2m'] . "%<br>";
    echo "- Gefühlte Temperatur: " . $location['current']['apparent_temperature'] . "°C<br>";
    echo "- Ist es Tag: " . ($location['current']['is_day'] ? "Ja" : "Nein") . "<br>";
    echo "- Regen: " . $location['current']['rain'] . "mm<br>";
    echo "- Schauer: " . $location['current']['showers'] . "mm<br>";
    echo "- Schneefall: " . $location['current']['snowfall'] . "cm<br>";
    echo "- Bewölkung: " . $location['current']['cloud_cover'] . "%<br><br>";
}

?>
