<?php

// Bindet das Skript 130_extract.php für Rohdaten ein
$data = include('130_extract.php');

// Definiert eine Zuordnung von Koordinaten zu Stadtnamen
$locationsMap = [
    '46.94,7.44' => 'Bern',
    '46.84,9.52' => 'Chur',
    '47.36,8.559999' => 'Zürich',
];

// Transformation der Standorte
foreach ($data as $location) {
    
    // Ursprüngliche Breite und Länge
    echo "Ursprüngliche Breite: " . $location['latitude'] . ", Länge: " . $location['longitude'] . "<br>";

    // Umgeformter Standortname
    if (isset($locationsMap[$location['latitude'] . ',' . $location['longitude']])) {

        $city = $locationsMap[$location['latitude'] . ',' . $location['longitude']];

    } else {

        $city = 'Unbekannt';

    }

    echo "Transformierter Standort: " . $city . "<br><br>";
}

// Funktion, um Fahrenheit in Celsius umzurechnen
function convertToCelsius($fahrenheit) {
    return round(($fahrenheit - 32) * 5/9, 1);
}

// Transformation der Temperatur
foreach ($data as $location) {
    // Zeigt ursprüngliche und transformierte Temperatur
    $originalTemp = $location['current']['temperature_2m'];
    $transformedTemp = convertToCelsius($originalTemp);
    echo "Temperatur Unverwandelt: " . $originalTemp . "°F<br>";
    echo "Temperatur Umgeformt: " . $transformedTemp . "°C<br><br>";
}

// Funktion zur Bestimmung der Wetterbedingung
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

// Transformation der Wetterbedingung
foreach ($data as $location) {
    // Berechnet und zeigt Wetterbedingung
    $condition = determineCondition(
        $location['current']['cloud_cover'],
        $location['current']['rain'],
        $location['current']['showers'],
        $location['current']['snowfall']
    );
    echo "Basierend auf Bewölkung (" . $location['current']['cloud_cover'] . "%) und Niederschlag (Regen: " . $location['current']['rain'] . "mm, Schauer: " . $location['current']['showers'] . "mm, Schneefall: " . $location['current']['snowfall'] . "cm),<br>";
    echo "Wetterbedingung Umgeformt: " . $condition . "<br><br>";
}

?>