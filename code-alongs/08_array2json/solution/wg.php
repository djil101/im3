<?php

/**
 * 08_array2json/solution/wg.php
 * Ziel: Ausgabe eines Arrays als JSON
 * 
 * Bonus: Ausgabe mit pretty print
 * 
 * 
 */

$wg = ["Barbie", "Ken", "Allan", "President Barbie", "Anja"];

// Header für JSON-Ausgabe
header('Content-Type: application/json');
// einfache Ausgabe
// echo json_encode($wg);
// Ausgabe mit pretty print
echo json_encode($wg, JSON_PRETTY_PRINT);
