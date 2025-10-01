<?php

/**
 * 02_funktionen/index.php 
 * Ziel: Funktionsdefinition, Parameter mit Defaultwert, Rückgabewert.
 * 
 * Aufgabe:
 * Lege Variablen für einen kleinen Warenkorb an und berechne den Totalbetrag inkl. MWST. Gib am Ende eine formattierte Quittung aus.
 */

// ------------------------------------------

// 1. Funktion add_mwst($netto, $satz) anlegen.
function add_mwst($netto, $satz = 0.081)
{
  // 2. Den MwSt-Betrag berechnen (Netto + MWST). Defaultwert für $satz = 0.081 (8.1%).
  $mwst_betrag = $netto * $satz;
  // 3. Gib den Totalbetrag zurück (return).
  return $netto + $mwst_betrag;
}

// 4. Funktion kassenbon($name, $betrag) anlegen, die eine formatierte Zeile zurückgibt (Kunde: Name | Total: Betrag).
function kassenbon($name, $betrag)
{
  // 5. Nutze number_format($zahl, 2) für schöne Ausgabe.
  return "Kunde: $name | Total: " . number_format($betrag, 2);
}

// 6. Rufe die Funktionen mit Beispielwerten auf und gib das Resultat aus
$brutto = add_mwst(9.99);
echo kassenbon("Lea", $brutto) . "<br>";
