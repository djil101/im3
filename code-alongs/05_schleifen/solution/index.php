<?php

/**
 * 05_schleifen/index.php
 * Ziel: foreach, for, while, do-while
 * 
 * 
 */

// Arrays für Schleifen
$wg = ["Barbie", "Ken", "Allan", "President Barbie", "Anja"];

$person = [
  "name" => "Barbie",
  "alter" => 21,
  "beruf" => "Model",
  "verheiratet" => false,
  "hobbies" => ["Reiten", "Autos", "Mode"]
];


// -- FOREACH ($wg) -------------------------------------------------------------
foreach ($wg as $mitglied) {
  echo "- " . $mitglied . "<br/>";
}
echo  "<hr><br/><br/>";

// mit Index ($wg) -----------------------------------------------------
foreach ($wg as $index => $mitglied) {
  echo ($index + 1) . ". " . $mitglied . "<br/>";
}
echo  "<hr><br/><br/>";

// assoziatives Array ($person) -----------------------------------------------------

foreach ($person as $key => $value) {
  if (is_array($value)) {
    $value = implode(", ", $value);
  } elseif (is_bool($value)) {
    $value = $value ? "ja" : "nein";
  }
  echo ucfirst($key) . ": " . $value . "<br/>";
}
echo  "<hr><br/><br/>";


// -- FOR ($wg) -------------------------------------------------------------
echo "WG-Mitglieder (for-Schleife):<br/>";
for ($i = 0; $i < count($wg); $i++) {
  echo ($i + 1) . ". " . $wg[$i] . "<br/>";
}
echo  "<hr><br/><br/>";







// -- WHILE ($wg) (sehr selten verwendet, nicht in diesem Semester) -------------------------------------------------------------
echo "WG-Mitglieder (while-Schleife):<br/>";
$i = 0;
while ($i < count($wg)) {
  echo ($i + 1) . ". " . $wg[$i] . "<br/>";
  $i++;
}
echo  "<hr><br/><br/>";


// -- DO-WHILE ($wg) (sehr selten verwendet, nicht in diesem Semester) -------------------------------------------------------------
echo "WG-Mitglieder (do-while-Schleife):<br/>";
$i = 0;
do {
  echo ($i + 1) . ". " . $wg[$i] . "<br/>";
  $i++;
} while ($i < count($wg));
echo  "<hr><br/><br/>";
