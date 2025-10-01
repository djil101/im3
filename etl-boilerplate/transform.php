<?php

/* ============================================================================
   HANDLUNGSANWEISUNG (transform.php)
   0) Schau dir die Rohdaten genau an und plane exakt, wie du die Daten umwandeln möchtest (auf Papier)
   1) Binde extract.php ein und erhalte das Rohdaten-Array.
   2) Definiere Mapping Koordinaten → Anzeigename (z. B. Bern/Chur/Zürich).
   3) Konvertiere Einheiten (z. B. °F → °C) und runde sinnvoll (Celsius = (Fahrenheit - 32) * 5 / 9).
   4) Leite eine einfache "condition" ab (z. B. sonnig/teilweise bewölkt/bewölkt/regnerisch).
   5) Baue ein kompaktes, flaches Array je Standort mit den Ziel-Feldern.
   6) Optional: Sortiere die Werte (z. B. nach Zeit), entferne irrelevante Felder.
   7) Validiere Pflichtfelder (location, temperature_celsius, …).
   8) Kodieren: json_encode(..., JSON_PRETTY_PRINT) → JSON-String.
   9) GIB den JSON-String ZURÜCK (return), nicht ausgeben – für den Load-Schritt.
  10) Fehlerfälle als Exception nach oben weiterreichen (kein HTML/echo).
   ============================================================================ */

// Bindet das Skript extract.php für Rohdaten ein und speichere es in $data
$data = include('extract.php');

// Definiert eine Zuordnung von Koordinaten zu Stadtnamen
$locationsMap = [
    '46.94,7.44' => 'Bern',
    '46.84,9.52' => 'Chur',
    '47.36,8.559999' => 'Zürich',
];

// Funktion, um Fahrenheit in Celsius umzurechnen

// Neue Funktion zur Bestimmung der Wetterbedingung



// Initialisiert ein Array, um die transformierten Daten zu speichern
$transformedData = [];

// Transformiert und fügt die notwendigen Informationen hinzu
foreach ($data as $location) {
    // Bestimmt den Stadtnamen anhand von Breitengrad und Längengrad

    // Wandelt die Temperatur in Celsius um und rundet sie

    // Bestimmt die Wetterbedingung

    // Konstruiert die neue Struktur mit allen angegebenen Feldern, einschließlich des neuen 'condition'-Feldes
}

// Kodiert die transformierten Daten in JSON

// Gibt die JSON-Daten zurück, anstatt sie auszugeben
