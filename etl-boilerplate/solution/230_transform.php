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
function convertToCelsius($fahrenheit)
{
    return ($fahrenheit - 32) * 5 / 9;
}

// Neue Funktion zur Bestimmung der Wetterbedingung
function determineCondition($cloudCover, $rain, $showers, $snowfall)
{
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

// Initialisiert ein Array, um die transformierten Daten zu speichern
$transformedData = [];

// Transformiert und fügt die notwendigen Informationen hinzu
foreach ($data as $location) {
    // Bestimmt den Stadtnamen anhand von Breitengrad und Längengrad
    $cityKey = $location['latitude'] . ',' . $location['longitude'];
    $city = $locationsMap[$cityKey] ?? 'Unbekannt';

    // Wandelt die Temperatur in Celsius um und rundet sie
    $temperatureCelsius = round(convertToCelsius($location['current']['temperature_2m']), 2);

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

// Gibt die JSON-Daten zurück, anstatt sie auszugeben
return $jsonData;
