# Cheatsheet `Bedingungen`
Bedingungen (auf Englisch *Conditions*) sind Ausdrücke, die entweder **wahr** (`true`) oder **falsch** (`false`) sind.  
Oft werden sie genutzt, um Code abhängig vom Ergebnis auszuführen.


## Bedingungen
Bedingungen können wahr (`true`) oder falsch (`false`) sein.

```php
<?php
$x = 1;
$y = 2;

var_dump($x === $y); // is equal to | false
var_dump($x !== $y); // is NOT equal to | true
var_dump($x < $y);   // is smaller than | true
var_dump($x <= $y);  // is smaller than or same as | true
var_dump($x > $y);   // is bigger than | false
var_dump($x >= $y);  // is bigger than or same as | false
```

### Vergleichsarten
In PHP gibt es zwei Arten von Gleichheitsvergleichen: **lose** (`==`) und **strikt** (`===`).


#### Lose Gleichheit: `==`
- Vergleicht nur den **Wert**.
- PHP wandelt Typen automatisch um (*Type Juggling*).

```php
<?php
var_dump(5 == "5");     // true (String wird zu Zahl konvertiert)
var_dump(0 == false);   // true
var_dump("0" == false); // true
var_dump(null == false); // true
```

#### Strikte Gleichheit: `===`
- Vergleicht **Wert UND Typ**.
- Keine automatische Typumwandlung.

```php
<?php
var_dump(5 === "5");     // false (int ≠ string)
var_dump(0 === false);   // false (int ≠ bool)
var_dump("0" === false); // false (string ≠ bool)
var_dump(null === false); // false (null ≠ bool)
var_dump(5 === 5);       // true (gleicher Wert und Typ)
```


#### Merksatz
- `==` → prüft nur **Werte nach Typkonvertierung**
- `===` → prüft **Wert und Typ**

👉 **Best Practice:** In moderner PHP-Entwicklung fast immer `===` und `!==` verwenden, um unerwartete Typkonvertierungen zu vermeiden.

## Bedingungen verknüpfen
```php
<?php
$x = 1;
$y = 2;

var_dump($x >= 1 && $y <= 2); // both must be true | true
var_dump($x === 1 || $y === 1); // one must be true | true
```


## Conditionals / Conditional Statements

### if
```php
<?php
$x = 10;
if ($x > 10) {
    echo "mach etwas wenn x grösser als 10 ist";
}
```

### if / else
```php
<?php
$x = 10;
if ($x > 10) {
    echo "mach etwas wenn x grösser als 10 ist";
} else {
    echo "mach etwas, wenn die Bedingung nicht eintritt";
}
```

### if / elseif / else
```php
<?php
$x = 10;
if ($x > 10) {
    echo "mach etwas wenn x grösser als 10 ist";
} elseif ($x === 10) {
    echo "mach etwas wenn x gleich 10 ist";
} else {
    echo "mach etwas wenn keine Bedingung eintritt";
}
```


### switch | case
```php
<?php
$x = 10;
switch ($x) {
    case 20:
        echo "mach etwas wenn x 20 ist";
        break;
    case 10:
        echo "mach etwas wenn x 10 ist";
        break;
    default:
        echo "mach etwas wenn kein case eintritt";
        break;
}
```


### ternary
```php
<?php
$x = 10;
// condition ? if condition true : if condition false 
echo $x > 10 
    ? "mach etwas wenn x grösser als 10 ist" 
    : "mach etwas wenn das nicht so ist";
```
---

## Quellen
- [if-Anweisungen](https://www.php-einfach.de/php-tutorial/if-anweisungen/)
- [Vergleichsoperatoren in PHP](https://www.php-einfach.de/php-tutorial/php-vergleichsoperatoren/)
- [Logische Operatoren](https://www.php-einfach.de/php-tutorial/logische-operatoren/)
- [PHP Comparison Operators](https://www.php.net/manual/en/language.operators.comparison.php)  
- [PHP if...else](https://www.php.net/manual/en/control-structures.if.php)  
- [PHP switch](https://www.php.net/manual/en/control-structures.switch.php)  
- [PHP ternary operator](https://www.php.net/manual/en/language.operators.comparison.php#language.operators.comparison.ternary)  
