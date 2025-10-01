# Cheatsheet `Arrays` in PHP
Arrays sind ein Datentyp in PHP. Sie können viele Werte in einer Variablen speichern.  
Ein Array kann numerisch (indexiert), assoziativ (mit Schlüssel/Wert) oder verschachtelt sein.  


## Erstellen eines Arrays
```php
<?php
$wg = ["Barbie", "Ken", "Allan"];  // Ein Array mit drei Elementen 
$haus = [["Barbie", "Ken", "Allan"], ["Superman", "Spiderman"]];   // Ein mehrdimensionales Array mit zwei Elementen, die wiederum Array sind
$zahlen = [221, 4, 11889, -3, 0.5];

// Assoziatives Array
$user = ["name" => "Lea", "age" => 23];  // Ein Array mit den zwei benannten Elementen $user["name"] und $user["age"]
```


## Auslesen aus einem Array
Die Indexierung in einem numerischen Array beginnt bei `0`.

```php
<?php
echo $wg[0];       // gibt 'Barbie' zurück
print_r($haus[1]); // gibt ['Superman', 'Spiderman'] zurück
echo $user["name"]; // 'Lea'
```

## Anzahl Einträge in einem Array
```php
<?php
echo count($wg); // gibt 3 zurück
```


## Array manipulieren

### Inhalte hinzufügen
```php
<?php
$wg[] = "President Barbie";            // ans Ende hinzufügen
array_unshift($wg, "President Barbie"); // an den Anfang hinzufügen
$wg[3] = "President Barbie";            // direkter Index-Zugriff (nicht empfohlen)
```

### Inhalte entfernen
```php
<?php
array_pop($wg);    // entfernt letztes Element
array_shift($wg);  // entfernt erstes Element
```

### Inhalte ersetzen
```php
<?php
$wg[0] = "President Barbie"; // ersetzt 'Barbie' mit 'President Barbie'
```

### Slicing/Splicing
```php
<?php
$wg = ["Barbie", "Ken", "Allan"];
array_splice($wg, 0, 2); // entfernt 'Barbie' und 'Ken'

$wg = ["Barbie", "Ken", "Allan"];
array_splice($wg, 1, 1, ["President Barbie"]); 
// entfernt 'Ken' und fügt 'President Barbie' ein

array_splice($wg, 2, 0, ["Spiderman", "Superman"]); 
// fügt 'Spiderman' und 'Superman' ein
```


## Arrays zusammenfügen
```php
<?php
$wg_bern = ["Barbie", "Ken", "Allan"];
$wg_chur = ["Superman", "Spiderman"];

$wgs = array_merge($wg_bern, $wg_chur); // klassisch
$wgs = [...$wg_bern, ...$wg_chur];      // Spread (PHP 8.1+)
```

## Weitere Array-Funktionen

### sort
```php
<?php
$numbers = [4, 5, -10, 100, 31];
sort($numbers);  // aufsteigend
rsort($numbers); // absteigend

$food = ["Toast","Bread","Banana","Ananas"];
sort($food); // alphabetisch
```

### map
```php
<?php
$numbers = [14, 33, 107];
$numbers_plus_ten = array_map(fn($n) => $n + 10, $numbers);
// [24, 43, 117]
```

### find (erste passende Suche via filter)
```php
<?php
$wg_bern = ["Barbie", "Ken", "Allan"];
$five_letters = array_values(
    array_filter($wg_bern, fn($m) => strlen($m) === 5)
)[0] ?? null; 
// gibt 'Allan' zurück oder null, wenn nicht gefunden
```

### filter
```php
<?php
$numbers = [10, 31, -2, 11, -109];
$positive_numbers = array_filter($numbers, fn($n) => $n >= 0);
// [10, 31, 11]
```

### reduce
```php
<?php
$sum = array_reduce([1,2,3], fn($carry, $n) => $carry + $n, 0);
// 6
```
---

## Quellen
- [PHP Arrays](https://www.php.net/manual/en/language.types.array.php)  
- [array_splice](https://www.php.net/manual/en/function.array-splice.php)  
- [sort](https://www.php.net/manual/en/function.sort.php)  
- [array_map](https://www.php.net/manual/en/function.array-map.php)  
- [array_filter](https://www.php.net/manual/en/function.array-filter.php)  
- [array_reduce](https://www.php.net/manual/en/function.array-reduce.php)  
