<?php

/* ============================================================================
   HANDLUNGSANWEISUNG (extract.php)
   1) Lade Konfiguration/Constants (API-URL, Parameter, ggf. Zeitzone).
   2) Baue die Request-URL (Query-Params sauber via http_build_query).
   3) Initialisiere cURL (curl_init) mit der Ziel-URL.
   4) Setze cURL-Optionen (RETURNTRANSFER, TIMEOUT, HTTP-Header, FOLLOWLOCATION).
   5) Führe Request aus (curl_exec) und prüfe Transportfehler (curl_error).
   6) Prüfe HTTP-Status & Content-Type (JSON erwartet), sonst früh abbrechen.
   7) Dekodiere JSON robust (json_decode(..., true)).
   8) Normalisiere/prüfe Felder (defensive Defaults, Typen casten).
   9) Gib die Rohdaten als PHP-Array ZURÜCK (kein echo) für den Transform-Schritt.
  10) Fehlerfälle: Exception/Fehlerobjekt nach oben reichen (kein HTML ausgeben).
   ============================================================================ */

function fetchWeatherData()
{
    $url = "https://api.open-meteo.com/v1/forecast?latitude=46.9481,46.8499,47.3667&longitude=7.4474,9.5329,8.55&current=temperature_2m,relative_humidity_2m,apparent_temperature,is_day,rain,showers,snowfall,cloud_cover&temperature_unit=fahrenheit&timezone=auto&forecast_days=1";

    // Initialisiert eine cURL-Sitzung


    // Setzt Optionen


    // Führt die cURL-Sitzung aus und erhält den Inhalt


    // Schließt die cURL-Sitzung


    // Dekodiert die JSON-Antwort und gibt Daten zurück

}

// Gibt die Daten zurück, wenn dieses Skript eingebunden ist
return fetchWeatherData();
