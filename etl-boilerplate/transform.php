<?php

/**
 * transform.php
 * 
 * Erwartet: extract.php liefert das dekodierte Nextbike-JSON als Array.
 * Gibt ein Array mit folgenden Feldern zurück:
 *   - date                 (Zeitstempel)
 *   - station_name         (Name, ggf. umbenannt)
 *   - bike_racks           (Gesamtstellplätze)
 *   - bike_available_to_rent (verfügbare Mietbikes)
 */

// Rohdaten laden
$data = include('extract.php');

// --- Orte, die wir behalten wollen ---
$whitelist = [
    'Bahnhofplatz',
    'Obere Au',
    'Kantonsspital Graubuenden',
    'Kantonsspital Graubünden',
];

// --- Namens-Mapping ---
$renameMapNorm = [
    'kantonsspital graubuenden' => 'Kantonsspital',
    'kantonsspital graubunden'  => 'Kantonsspital',
];

// --- Helper zum Normalisieren (Umlaute etc.) ---
$normalize = function (string $name): string {
    $n = trim($name);
    $n = str_replace(
        ['ä','ö','ü','Ä','Ö','Ü','ß','é','è','ê','É','È','Ê'],
        ['ae','oe','ue','Ae','Oe','Ue','ss','e','e','e','E','E','E'],
        $n
    );
    $n = str_replace(['graubünden','graubuenden'], 'graubunden', mb_strtolower($n, 'UTF-8'));
    return $n;
};

// Whitelist normalisieren
$whitelistNorm = array_map($normalize, $whitelist);

// --- Transformation starten ---
$transformedData = [];

if (isset($data['countries']) && is_array($data['countries'])) {
    foreach ($data['countries'] as $country) {
        if (empty($country['cities'])) continue;
        foreach ($country['cities'] as $city) {
            if (empty($city['places'])) continue;
            foreach ($city['places'] as $place) {

                if (empty($place['name'])) continue;

                $placeNameNorm = $normalize((string)$place['name']);
                if (!in_array($placeNameNorm, $whitelistNorm, true)) continue;

                // Anzeige-/Speichername ggf. umbenennen
                $displayName = $renameMapNorm[$placeNameNorm] ?? $place['name'];

                // Daten sicher abgreifen
                $bikeRacks = $place['bike_racks']
                    ?? $place['rack_count']
                    ?? $place['total_racks']
                    ?? null;

                $available = $place['bikes_available_to_rent']
                    ?? $place['bikes']
                    ?? $place['available_bikes']
                    ?? null;

                // Zahlen sicher casten
                $bikeRacks = is_numeric($bikeRacks) ? (int)$bikeRacks : null;
                $available = is_numeric($available) ? (int)$available : null;

                // Datenobjekt aufbauen
                $transformedData[] = [
                    'date'                   => date('Y-m-d H:i:s'),
                    'station_name'           => $displayName,
                    'bike_racks'             => $bikeRacks,
                    'bike_available_to_rent' => $available,
                ];
            }
        }
    }
}

// Rückgabe ans nächste ETL-Modul (z. B. load.php)
return $transformedData;
