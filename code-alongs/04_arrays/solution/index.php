<?php

/**
 * 04_arrays/index.php
 * Ziel: Indexierte & assoziative Arrays, Array-Funktionen
 * 
 * indexierte Arrays
 * 1. Erstelle ein Array mit den Namen der WG-Mitglieder 
 *    Barbie, Ken, Allan, President Barbie, Anja
 * 2. Füge ein neues Mitglied am Ende des Arrays hinzu .. array_push()
 * 3. Entferne das erste und letzte Mitglied wieder.. array_pop()
 * 4. Mitglied am Anfang hinzufügen .. array_unshift()
 * 5. Entferne ein Mitglied am Anfang .. array_shift()
 * 6. Entferne ein Mitglied an einem bestimmten Index .. array_splice()
 * 7. Füge ein Mitglied an einem bestimmten Index hinzu .. array_splice()
 * 8. Filtere die Namen, so dass nur noch Namen mit mind. 5
 *   Buchstaben übrig bleiben .. array_filter()
 * 9. Hänge an jeden Namen ein Ausrufezeichen an .. array_map()
 * 
 * asoziative Arrays
 * 10. Erstelle ein assoziatives Array mit den Eigenschaften einer Person
 *     Barbie ist 21 Jahre alt, Model, nicht verheiratet, 
 *     Hobbies: Reiten, Autos, Mode
 * 11. Gib den Namen, das zweite Hobby und die Anzahl der Hobbies aus
 * 
 */

// indexierte Arrays
// 1. Erstelle ein Array mit den Namen der WG-Mitglieder 
$wg = ["Barbie", "Ken", "Allan", "President Barbie", "Anja"];
array_ausgeben($wg, "Ursprüngliches Array");

// 2. Füge ein neues Mitglied am Ende des Arrays hinzu .. array_push()
array_push($wg, "Tommy"); // Element hinzufügen
array_ausgeben($wg, "Nach Hinzufügen von Tommy am Ende");

// 3. Entferne das letzte Mitglied wieder .. array_pop()
array_pop($wg); // Letztes Element entfernen
array_ausgeben($wg, "Nach Entfernen des letzten Elements");

// 4. Mitglied am Anfang hinzufügen .. array_unshift()
array_unshift($wg, "Steffi"); // Element am Anfang hinzufügen
array_ausgeben($wg, "Nach Hinzufügen von Steffi am Anfang");

// 5. Entferne ein Mitglied am Anfang .. array_shift()
array_shift($wg); // Erstes Element entfernen
array_ausgeben($wg, "Nach Entfernen des ersten Elements");

// 6. Entferne ein Mitglied an einem bestimmten Index .. array_splice()
array_splice($wg, 2, 1); // Element an Index 2 entfernen
array_ausgeben($wg, "Nach Entfernen des Elements an Index 2");

// 7. Füge ein Mitglied an einem bestimmten Index hinzu .. array_splice()
array_splice($wg, 1, 0, "Urs"); // Element an Index 1 einfügen
array_ausgeben($wg, "Nach Hinzufügen von Ursan Index 1");

// 8. Filtern: mind. 5 Buchstaben .. array_filter()
$long_names = array_filter($wg, fn($name) => strlen($name) >= 5);
array_ausgeben($long_names, "Filter: Namen mit mind. 5 Buchstaben");

// 9. Mappen: Ausrufezeichen anhängen .. array_map()
$shout = array_map(fn($name) => $name . "!", $wg);
array_ausgeben($shout, "Map: Namen der WG mit Ausrufezeichen");
$shout = array_map(fn($name) => $name . "!", $long_names);
array_ausgeben($shout, "Map: lange Namen mit Ausrufezeichen");


// asoziative Arrays
// 10. Erstelle ein assoziatives Array mit den Eigenschaften einer Person
//    Barbie ist 21 Jahre alt, Model, nicht verheiratet,
//    Hobbies: Reiten, Autos, Mode
$person = [
  "name" => "Barbie",
  "alter" => 21,
  "beruf" => "Model",
  "verheiratet" => false,
  "hobbies" => ["Reiten", "Autos", "Mode"]
];
array_ausgeben($person, "Assoziatives Array: Person");

// 11. Gib den Namen, das zweite Hobby und die Anzahl der Hobbies aus
echo "Name: " . $person["name"] . "<br/>";
echo "Hobby 2: " . $person["hobbies"][1] . "<br/>";
echo "Anzahl Hobbies: " . count($person["hobbies"]) . "<br/>";
echo  "---------- ---------- ---------- ----------<br/><br/>";



// Hilfsfunktion zur einfachen Ausgabe von Arrays
function array_ausgeben($arr, $title = "")
{
  if ($title) {
    echo "<h3>$title</h3>";
  }
  echo "<pre>";
  print_r($arr);
  echo "</pre>";
}
