# PHP – Variablen

- Variablen beginnen immer mit `$`
- Namen dürfen Buchstaben, Zahlen und `_` enthalten, aber **nicht** mit einer Zahl beginnen
- Case-sensitive: `$name` ≠ `$Name`
- Variablen sind **dynamisch typisiert** (Typ ändert sich nach Wert)
- Zuweisung mit `=`


## Beispiele

```php
<?php
$integer = 42;           // Ganzzahl (int)
$float = 3.14;           // Kommazahl (float)
$string = "Hallo Welt";  // Zeichenkette (string)
$boolean = true;         // Boolescher Wert (bool)
```

## Besondere Typen

```php
$array = ["Apfel", "Birne"];      // Array
$assoc = ["Vorname" => "Lea"];    // Assoziatives Array
$nullVar = null;                  // NULL ≠ 0
```


## Variablen ausgeben

```php
echo $string;     // echo funktioniert nur mit Strings
print_r($array);
var_dump($assoc); // zeigt Typ + Struktur
```

## Konstanten
- Wir werden im Semester keine Konstanten verwenden.
- Die VERSALIEN-Schreibweise ist nicht verpflichtend, aber best Practice. (kleingeschriebene Konstantennamen werden als Fehler bewertet)

```php
define("VERSION", "1.0");  // Legacy
const APP_ENV = "dev";     // moderne Schreibweise
```
