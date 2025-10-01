# Cheatsheet `Loops` in PHP
Loops (Schleifen) sind ein zentrales Werkzeug, um in PHP Arrays zu verarbeiten oder repetitive Aufgaben zu automatisieren.


## Foreach-Schleife
Die `foreach`-Schleife ist der gebräuchlichste Weg, um Arrays in PHP zu durchlaufen.

```php
<?php
$wg = ["Barbie", "Ken", "Allan"];
foreach ($wg as $index => $member) {
    echo "$member lebt in der WG mit Index $index\n";
}
// Gibt für jedes Element im Array eine Zeile aus
```


## For-Schleife
Die klassische `for`-Schleife funktioniert wie in vielen Programmiersprachen.

```php
<?php
$wg = ["Barbie", "Ken", "Allan"];
for ($i = 0; $i < count($wg); $i++) {
    echo $wg[$i] . " lebt in der WG mit Index $i\n";
}
```

Auch unabhängig von Arrays nutzbar:
```php
<?php
for ($i = 0; $i < 10; $i++) {
    echo "Mach das zum " . ($i + 1) . " Mal\n";
}
```

**Parameter der for-Schleife:**
1. Startwert (meist `0`)  
2. Bedingung, wann abgebrochen wird (meist mit `count($array)`)  
3. Schrittweite (meist `i++`)  



## While-Schleife
Die `while`-Schleife führt Code so lange aus, bis die Bedingung **falsch** wird.

```php
<?php
$i = 0;
while ($i < 10) {
    echo "Mach das zum " . ($i + 1) . " Mal\n";
    $i++;
}
```

⚠️ **Achtung:** Wenn die Bedingung nie `false` wird, entsteht eine Endlosschleife.  


## Do-While-Schleife
Der Codeblock wird **mindestens einmal** ausgeführt, auch wenn die Bedingung zu Beginn falsch ist.

```php
<?php
$i = 0;
do {
    echo "Mach das zum " . ($i + 1) . " Mal\n";
    $i++;
} while ($i < 10);
```



## break und continue
Mit `break` und `continue` kannst du die Schleifensteuerung beeinflussen.

```php
<?php
$numbers = [10, -5, 20, 0, 30];

foreach ($numbers as $n) {
    if ($n < 0) continue; // überspringt negative Zahlen
    if ($n === 0) break;  // beendet die Schleife bei 0
    echo $n . "\n";       // gibt nur positive Zahlen aus bis 0 erreicht ist
}
```


## Quellen
- [PHP foreach](https://www.php.net/manual/en/control-structures.foreach.php)  
- [PHP for](https://www.php.net/manual/en/control-structures.for.php)  
- [PHP while](https://www.php.net/manual/en/control-structures.while.php)  
- [PHP do...while](https://www.php.net/manual/en/control-structures.do.while.php)  
- [PHP break / continue](https://www.php.net/manual/en/control-structures.break.php)  
