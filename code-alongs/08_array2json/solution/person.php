<?php

/**
 * 08_array2json/solution/person.php
 * Ziel: Ausgabe eines Arrays als JSON
 * 
 * Bonus: Ausgabe mit pretty print
 * 
 * 
 */


$person = [
  "name" => "Barbie",
  "alter" => 21,
  "beruf" => "Model",
  "verheiratet" => false,
  "hobbies" => ["Reiten", "Autos", "Mode"]
];

// Header für JSON-Ausgabe
header('Content-Type: application/json');
// einfache Ausgabe
// echo json_encode($person);
// Ausgabe mit pretty print
echo json_encode($person, JSON_PRETTY_PRINT);
