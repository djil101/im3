# Cheatsheet `Funktionen`
Funktionen in PHP sind dazu da, vordefinierten Code mehrfach auszuführen.


## Aufbau einer Funktion
Um eine Funktion in PHP zu schreiben, muss diese zuerst **deklariert** werden.  
Das passiert in einer vorgegebenen Syntax:

```php
<?php
function name_der_funktion($parameter) {
    echo "Hoi $parameter\n";
}
// function: Schlüsselwort, das PHP sagt, dass es sich um eine Funktion handelt
// name_der_funktion: Titel unter welchem die Funktion später aufgerufen werden kann
// $parameter: Parameter sind Werte, die der Funktion mitgegeben werden können
// `\n` ist der Code für eine neue Zeile
// echo: gibt Text oder Werte aus
```

Sobald eine Funktion einmal **deklariert** wurde, kann man sie **aufrufen** – so oft man will:

```php
name_der_funktion("Lea");       // gibt "Hoi Lea" aus
name_der_funktion("Anja");      // gibt "Hoi Anja" aus
```


## Arten von Funktionen

### Einfache Funktion ohne Parameter
```php
<?php
function check() {
    echo "I am working!\n";
}

check(); // gibt "I am working!" aus
```


### Einfache Funktion mit Parameter
```php
<?php
function verdoppeln($x) {
    echo $x * 2 . "\n";
}

verdoppeln(332); // gibt 664 aus
```


### Funktion mit Parameter und Default-Wert (Fallback)
```php
<?php
function multiplizieren($x, $y = 2) {
    echo $x * $y . "\n";
}

multiplizieren(10);    // gibt 20 aus
multiplizieren(2, 5);  // gibt 10 aus
```


### Funktion mit Parameter und Rückgabewert (Return)
```php
<?php
function p_entfernen($string) {
    return str_replace("p", "", $string);
}

$message = "propositionen und prädikate";
$message_manipulated = p_entfernen($message);

echo $message_manipulated; 
// gibt "roositionen und rädikate" aus
```


## Zusätzliche Hinweise
- Funktionen müssen **vor ihrem ersten Aufruf definiert** sein (Ausnahme: wenn alle im selben Skript stehen, werden sie beim Parsen registriert).
- Parameter können **optional** sein, wenn ein Default-Wert angegeben wird.  
- Der `return`-Befehl gibt einen Wert zurück und beendet die Funktion.  
