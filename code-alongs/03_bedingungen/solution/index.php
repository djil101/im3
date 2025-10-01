<?php

/**
 * 03_bedingungen/index.php
 * Ziel: if / elseif / else
 * 
 * Aufgabe:
 * Schreibe eine Funktion bewerteTemperatur(float $c): string, die folgende Labels zurückgibt:
 * 
 * HINWEISE:
 * 1. Erstelle eine Funktion um eine Temperatur zu bewerten
 * 2. Nutze if / elseif / else um die Temperatur zu bewerten
 *    - "eiskalt" für Temperaturen unter -4 Grad
 *    - "kalt" für Temperaturen von -4 bis 3.5 Grad
 *    - "mild" für Temperaturen von 3.5 bis 12 Grad
 *    - "warm" für Temperaturen über 12 Grad
 * 3. Teste die Funktion mit verschiedenen Temperaturen
 * 4. Nutze den strikten Vergleich (===) um den Unterschied zum 
 * 5. normalen Vergleich (==) zu sehen
 * 6. Nutze den ternären Operator (condition ? if true : if false)
 * 
 */


// 1. Erstelle eine Funktion um eine Temperatur zu bewerten
function bewerteTemperatur($temperatur)
{
  if ($temperatur < -4) {
    return "eiskalt";
  } elseif ($temperatur >= -4 && $temperatur < 3.5) {
    return "kalt";
  } elseif ($temperatur >= 3.5 && $temperatur < 12) {
    return "mild";
  } else {
    return "warm";
  }
}

// 3. Teste die Funktion mit verschiedenen Temperaturen
echo bewerteTemperatur(-10) . "<br/>"; // eiskalt
echo bewerteTemperatur(0) . "<br/>";   // kalt
echo bewerteTemperatur(10) . "<br/>";  // mild
echo bewerteTemperatur(20) . "<br/>";  // warm
echo "<hr/>";


// strikter Vergleich === vs. ==
// === vergleicht auch den Typ, == nur den Wert
// 4. Nutze den strikten Vergleich (===) um den Unterschied zum 

$y = 10;
if ($y === "10") {
  echo "y ist 10 und der Typ ist auch Integer<br/>";
} else {
  echo "y ist 10 aber der Typ ist String<br/>";
}
// 5. normalen Vergleich (==) zu sehen
if ($y == "10") {
  echo "y ist 10 (Typ egal)<br/>";
} else {
  echo "y ist nicht 10<br/>";
}


// Ternärer Operator
// 6. Nutze den ternären Operator
$x = 10;
// condition ? if condition true : if condition false 
echo $x > 10
  ? "mach etwas wenn x grösser als 10 ist"
  : "mach etwas wenn das nicht so ist";
