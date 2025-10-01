<?php

// Bindet das Skript 130_extract.php für Rohdaten ein
$data = include('130_extract.php');

// Definiert eine Zuordnung von Koordinaten zu Stadtnamen
$locationsMap = [
    '46.94,7.44' => 'Bern',
    '46.84,9.52' => 'Chur',
    '47.36,8.559999' => 'Zürich',
];

// Funktion, um Fahrenheit in Celsius umzurechnen
function convertToCelsius($fahrenheit) {
    return round(($fahrenheit - 32) * 5/9, 1);
}

// Neue Funktion zur Bestimmung der Wetterbedingung
function determineCondition($cloudCover, $rain, $showers, $snowfall) {
    if ($rain > 0 || $showers > 0) {
        return 'Regnerisch';
    } elseif ($cloudCover < 20) {
        return 'Sonnig';
    } elseif ($cloudCover < 70) {
        return 'Teilweise Bewölkt';
    } else {
        return 'Bewölkt';
    }
}

// Initialisiert ein Array, um die transformierten Daten zu speichern
$transformedData = [];

// Transformiert und fügt die notwendigen Informationen hinzu
foreach ($data as $location) {
    // Bestimmt den Stadtnamen anhand von Breitengrad und Längengrad
    $cityKey = $location['latitude'] . ',' . $location['longitude'];
    $city = $locationsMap[$cityKey] ?? 'Unbekannt';

    // Wandelt die Temperatur in Celsius um und rundet sie
    $temperatureCelsius = convertToCelsius($location['current']['temperature_2m']);

    // Bestimmt die Wetterbedingung
    $condition = determineCondition(
        $location['current']['cloud_cover'],
        $location['current']['rain'],
        $location['current']['showers'],
        $location['current']['snowfall']
    );

    // Konstruiert die neue Struktur mit allen angegebenen Feldern, einschließlich des neuen 'condition'-Feldes
    $transformedData[] = [
        'location' => $city,
        'temperature_celsius' => $temperatureCelsius,
        'rain' => $location['current']['rain'],
        'showers' => $location['current']['showers'],
        'snowfall' => $location['current']['snowfall'],
        'cloud_cover' => $location['current']['cloud_cover'],
        'condition' => $condition // Fügt das Feld 'condition' hinzu
    ];
}

// Kodiert die transformierten Daten in JSON
$jsonData = json_encode($transformedData, JSON_PRETTY_PRINT);

// Optional kann das JSON ausgegeben werden, um die Ausgabe zu sehen
echo $jsonData;

// Wenn dies in eine Datei gespeichert werden soll, kommentieren Sie die folgende Zeile aus
// file_put_contents('transformed_weather_data.json', $jsonData);

?>