<?php

/**
 * 03_arrays/index.php
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
// 1. 
$wg = [];
array_ausgeben($wg, "Ursprüngliches Array");

// 2. 

array_ausgeben($wg, "Nach Hinzufügen von Tommy am Ende");


// 3. 

array_ausgeben($wg, "Nach Entfernen des letzten Elements");


// 4. 

array_ausgeben($wg, "Nach Hinzufügen von Steffi am Anfang");


// 5. 

array_ausgeben($wg, "Nach Entfernen des ersten Elements");


// 6. 

array_ausgeben($wg, "Nach Entfernen des Elements an Index 2");


// 7. 

array_ausgeben($wg, "Nach Hinzufügen von Ursan Index 1");


// 8. 

array_ausgeben($long_names, "Filter: Namen mit mind. 5 Buchstaben");


// 9. 

array_ausgeben($shout, "Map: Namen der WG mit Ausrufezeichen");

array_ausgeben($shout, "Map: lange Namen mit Ausrufezeichen");


// asoziative Arrays
// 10. Barbie ist 21 Jahre alt, Model, nicht verheiratet,
//     Hobbies: Reiten, Autos, Mode
$person = [];
array_ausgeben($person, "Assoziatives Array: Person");

// 11. 





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
