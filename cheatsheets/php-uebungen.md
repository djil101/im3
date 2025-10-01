# PHP-Übungen (aufbauend auf den Cheatsheets)

**Zielgruppe:** Studierende mit JS-Vorerfahrung  
**Hinweis:** Jede Aufgabe enthält Lernziele, Aufgabe, Startercode, erwartete Ausgabe sowie Bonus-Ideen. Am Ende findest Du **Lösungsskizzen**.

---

## 1) Variablen

**Lernziele**
- Variablen deklarieren, Werte verarbeiten, Ausgabe (`echo`, `print_r`, `var_dump`).
- String-Interpolation vs. Konkatenation.

**Aufgabe**
Lege Variablen für einen kleinen Warenkorb an und berechne den **Totalbetrag inkl. MWST (7.7 %)**. Gib am Ende eine formatierte Quittung aus.

**Startercode**
```php
<?php
declare(strict_types=1);

$item1 = "Brot";
$price1 = 2.90;      // CHF
$item2 = "Banane";
$price2 = 1.20;

$vat = 0.077;        // 7.7%

// TODO: Zwischensumme berechnen
// TODO: MWST-Betrag berechnen
// TODO: Total berechnen
// TODO: Ausgabe formatiert: "Brot: 2.90 | Banane: 1.20 | MWST: ... | Total: ..."

// Bonus: Nutze number_format($zahl, 2) für schöne Ausgabe.
```
**Erwartete Ausgabe (Beispiel)**  
`Brot: 2.90 | Banane: 1.20 | MWST: 0.31 | Total: 4.41`

**Bonus-Ideen**
- `var_dump($price1)` nutzen, um den Typ zu prüfen.
- `const CURRENCY = "CHF";` definieren und in der Ausgabe verwenden.

---

## 2) Funktionen

**Lernziele**
- Funktionsdefinition, Parameter mit Defaultwert, Rückgabewert.
- Funktionale Zerlegung ähnlich JS.

**Aufgabe**
Schreibe zwei Funktionen:
1) `add_mwst(float $netto, float $satz = 0.077): float` → gibt Bruttobetrag zurück.  
2) `kassenbon(string $name, float $betrag): string` → gibt eine formatierte Zeile zurück, z. B. `"Lea zahlt: 10.75 CHF"`.

**Startercode**
```php
<?php
declare(strict_types=1);

function add_mwst(float $netto, float $satz = 0.077): float {
    // TODO: Brutto berechnen und zurückgeben
}

function kassenbon(string $name, float $betrag): string {
    // TODO: formatierte Zeile zurückgeben (mit number_format)
}

$brutto = add_mwst(9.99); // Default 7.7 %
echo kassenbon("Lea", $brutto) . PHP_EOL;
```
**Erwartete Ausgabe (Beispiel)**  
`Lea zahlt: 10.74 CHF`

**Bonus-Ideen**
- Pfeilfunktion (`fn`) für `add_mwst` schreiben.
- Dritte Funktion `summe(float ...$werte): float` und damit mehrere Netto-Beträge kombinieren.

---

## 3) Bedingungen

**Lernziele**
- `if / elseif / else`, strikter Vergleich `===` vs. `==`, ternary.

**Aufgabe**
Schreibe eine Funktion `bewerteTemperatur(float $c): string`, die folgende Labels zurückgibt:
- `< 0` → `"eiskalt"`  
- `0–10` → `"kalt"`  
- `> 10–20` → `"mild"`  
- `> 20` → `"warm"`  

Teste die Funktion mit mehreren Werten und gib die Labels aus.

**Startercode**
```php
<?php
declare(strict_types=1);

function bewerteTemperatur(float $c): string {
    // TODO: if/elseif/else implementieren
}

$werte = [-5, 3.5, 12, 21.2];
foreach ($werte as $t) {
    echo $t . "°C → " . bewerteTemperatur($t) . PHP_EOL;
}

// Bonus: Nutze ternary, um zusätzlich "Frostwarnung!" auszugeben, wenn $t < 0.
```
**Erwartete Ausgabe (Beispiel)**
```
-5°C → eiskalt
3.5°C → kalt
12°C → mild
21.2°C → warm
```

**Bonus-Ideen**
- Unterschied zwischen `5 == "5"` und `5 === "5"` demonstrieren.

---

## 4) Arrays

**Lernziele**
- Indexierte & assoziative Arrays, `array_map`, `array_filter`, `array_reduce`, `array_merge`.

**Aufgabe**
Gegeben sei ein Array mit Namen. Filtere nur Namen mit **mind. 5 Buchstaben**, ergänze ein `!` an jeden Namen und berechne die **Gesamtanzahl der Buchstaben** der gefilterten Namen.

**Startercode**
```php
<?php
declare(strict_types=1);

$wg = ["Barbie","Ken","Allan","President Barbie","Anja"];

// 1) Filtern: mind. 5 Buchstaben
$long = array_filter($wg, fn($name) => strlen($name) >= 5);

// 2) Mappen: Ausrufezeichen anhängen
$shout = array_map(fn($name) => $name . "!", $long);

// 3) Reduzieren: Gesamtanzahl der Buchstaben (ohne "!") ermitteln
$totalLetters = array_reduce($long, fn($c,$n) => $c + strlen($n), 0);

// TODO: Ausgabe
print_r($shout);
echo "Total Buchstaben: " . $totalLetters . PHP_EOL;

// Bonus: Füge ein zweites Array hinzu und merge es (array_merge oder Spread).
```
**Erwartete Ausgabe (Beispiel)**
```
Array
(
    [0] => Barbie!
    [2] => Allan!
    [3] => President Barbie!
    [4] => Anja!
)
Total Buchstaben: 29
```

**Bonus-Ideen**
- `array_splice` nutzen, um an Position 1 zwei neue Namen einzufügen.
- Alphabetisch sortieren und nur die ersten drei ausgeben.

---

## 5) Loops

**Lernziele**
- `foreach`, `for`, `while`/`do…while`, `break`/`continue`.

**Aufgabe**
Iteriere über ein Zahlen-Array und gib **nur positive Zahlen** aus, **bis** eine `0` erreicht wird (dann `break`). Überspringe **negative** mit `continue`. Anschliessend: Zähle mit `while` von `1` bis `10`.

**Startercode**
```php
<?php
declare(strict_types=1);

$numbers = [10, -5, 20, -1, 3, 0, 30];

foreach ($numbers as $n) {
    // TODO: negative überspringen
    // TODO: bei 0 abbrechen
    // TODO: positive ausgeben
}

$i = 1;
while ($i <= 10) {
    echo $i . " ";
    $i++;
}

// Bonus: Schreibe eine for-Schleife, die jeden 3. Wert (3, 6, 9, ...) ausgibt.
```
**Erwartete Ausgabe (Beispiel)**  
`10 20 3`  
`1 2 3 4 5 6 7 8 9 10`

**Bonus-Ideen**
- Eine `do…while` schreiben, die mindestens einmal ausgibt, selbst wenn die Bedingung falsch ist.

---

# Lösungsskizzen (Kurz)

> Hinweis: Es gibt mehrere korrekte Lösungen. Die Skizzen zeigen eine mögliche Umsetzung.

### 1) Variablen
```php
$subtotal = $price1 + $price2;
$tax = $subtotal * $vat;
$total = $subtotal + $tax;

echo "Brot: " . number_format($price1, 2) 
   . " | Banane: " . number_format($price2, 2)
   . " | MWST: " . number_format($tax, 2) 
   . " | Total: " . number_format($total, 2) . PHP_EOL;
```

### 2) Funktionen
```php
function add_mwst(float $netto, float $satz = 0.077): float {
    return $netto * (1 + $satz);
}
function kassenbon(string $name, float $betrag): string {
    return $name . " zahlt: " . number_format($betrag, 2) . " CHF";
}
$brutto = add_mwst(9.99);
echo kassenbon("Lea", $brutto) . PHP_EOL;
```

### 3) Bedingungen
```php
function bewerteTemperatur(float $c): string {
    if ($c < 0) return "eiskalt";
    elseif ($c <= 10) return "kalt";
    elseif ($c <= 20) return "mild";
    else return "warm";
}
$werte = [-5, 3.5, 12, 21.2];
foreach ($werte as $t) {
    echo $t . "°C → " . bewerteTemperatur($t) . PHP_EOL;
}
```

### 4) Arrays
```php
$wg = ["Barbie","Ken","Allan","President Barbie","Anja"];
$long = array_filter($wg, fn($name) => strlen($name) >= 5);
$shout = array_map(fn($name) => $name . "!", $long);
$totalLetters = array_reduce($long, fn($c,$n) => $c + strlen($n), 0);
print_r($shout);
echo "Total Buchstaben: " . $totalLetters . PHP_EOL;
```

### 5) Loops
```php
$numbers = [10, -5, 20, -1, 3, 0, 30];
foreach ($numbers as $n) {
    if ($n < 0) continue;
    if ($n === 0) break;
    echo $n . " ";
}
echo PHP_EOL;

$i = 1;
while ($i <= 10) {
    echo $i . " ";
    $i++;
}
echo PHP_EOL;
```


---

# 📊 Beurteilungsraster PHP-Grundlagenübungen

| Kriterium                   | Punkte (max) | Beschreibung                                                                 |
|-----------------------------|--------------|-------------------------------------------------------------------------------|
| **Variablen**               | 2            | Richtige Deklaration, Typkonsistenz, Nutzung von `echo` / String-Interpolation |
| **Funktionen**              | 3            | Saubere Definition, Default-Parameter, Rückgabewert, Wiederverwendbarkeit     |
| **Bedingungen**             | 3            | Korrekte Verwendung von `if / elseif / else`, sinnvolle Vergleiche (`===`)    |
| **Arrays**                  | 4            | Anwendung von `array_map`, `array_filter`, `array_reduce`, sinnvolle Ausgabe  |
| **Loops**                   | 4            | Einsatz von `foreach`, `for`, `while`, `break` / `continue`                  |
| **Code-Qualität**           | 2            | Lesbarkeit, Einrückung, Benennung, Kommentare                                |
| **Bonus (optional)**        | +2           | Zusätzliche Features (Ternary, Upsert, Extra-Funktionen)                     |

**Maximalpunkte:** 18 (+2 Bonus)  

---

# 🛒 Projektbriefing „Mini-Kassensystem“

**Ziel:**  
Baue ein kleines Kassensystem in PHP, das Produkte verwaltet, den Warenkorb berechnet und eine Quittung ausgibt.  

**Aufgabenstellung:**  
1. **Variablen:** Lege Produkte mit Preis als Variablen/Arrays an.  
2. **Arrays:** Erstelle einen Warenkorb (`$cart`) als Array von Produkten.  
3. **Funktionen:**  
   - `addMwst(float $netto, float $satz = 0.077): float`  
   - `printReceipt(array $cart): void` – iteriert über den Warenkorb und ruft `addMwst` auf.  
4. **Bedingungen:** Baue eine Funktion `rabatt(float $total): float`, die abhängig vom Betrag Rabatt gewährt:  
   - `< 20 CHF` → kein Rabatt  
   - `20–50 CHF` → 5 % Rabatt  
   - `> 50 CHF` → 10 % Rabatt  
5. **Loops:** Durchlaufe den Warenkorb und gib alle Produkte mit Einzelpreis aus. Berechne Zwischensumme, MWST, Rabatt und Endtotal.  

**Erwartete Ausgabe (Beispiel):**
```
Quittung
---------
Brot: 2.90 CHF
Banane: 1.20 CHF
Milch: 1.50 CHF
---------
Subtotal: 5.60 CHF
MWST: 0.43 CHF
Rabatt: 0.00 CHF
Total: 6.03 CHF
```

**Bonus-Ideen:**  
- Nutze ein assoziatives Array für den Warenkorb: `['name' => ..., 'price' => ...]`.  
- Füge eine While-Schleife hinzu, die wiederholt Produkte einliest (z. B. per CLI).  
- Schreibe eine Export-Funktion, die die Quittung in eine Textdatei speichert.  


---

# 📊 Beurteilungsraster PHP-Grundlagenübungen

| Kriterium                   | Punkte (max) | Beschreibung                                                                 |
|-----------------------------|--------------|-------------------------------------------------------------------------------|
| **Variablen**               | 2            | Richtige Deklaration, Typkonsistenz, Nutzung von `echo` / String-Interpolation |
| **Funktionen**              | 3            | Saubere Definition, Default-Parameter, Rückgabewert, Wiederverwendbarkeit     |
| **Bedingungen**             | 3            | Korrekte Verwendung von `if / elseif / else`, sinnvolle Vergleiche (`===`)    |
| **Arrays**                  | 4            | Anwendung von `array_map`, `array_filter`, `array_reduce`, sinnvolle Ausgabe  |
| **Loops**                   | 4            | Einsatz von `foreach`, `for`, `while`, `break` / `continue`                   |
| **Code-Qualität**           | 2            | Lesbarkeit, Einrückung, Benennung, Kommentare                                 |
| **Bonus (optional)**        | +2           | Zusätzliche Features (Ternary, Zusatzfunktionen, Export etc.)                  |

**Maximalpunkte:** 18 (+2 Bonus)

---

# 🛒 Projektbriefing „Mini-Kassensystem“

**Ziel**  
Ein kleines Kassensystem in PHP, das Produkte verwaltet, den Warenkorb berechnet und eine Quittung ausgibt.

## Anforderungen
1. **Variablen**: Lege Produkte mit Preis als Variablen/Arrays an.  
2. **Arrays**: Erstelle einen Warenkorb (`$cart`) als Array von Produkten.  
3. **Funktionen**:
   - `addMwst(float $netto, float $satz = 0.077): float`
   - `printReceipt(array $cart): void` – iteriert über den Warenkorb, summiert Netto, berechnet MWST und druckt die Quittung.
4. **Bedingungen**: Funktion `rabatt(float $total): float`  
   - `< 20 CHF` → 0 %  
   - `20–50 CHF` → 5 %  
   - `> 50 CHF` → 10 %  
5. **Loops**: Durchlaufe den Warenkorb, gib alle Produkte mit Einzelpreis aus. Berechne Subtotal, MWST, Rabatt und Total.

## Beispielausgabe
```
Quittung
---------
Brot: 2.90 CHF
Banane: 1.20 CHF
Milch: 1.50 CHF
---------
Subtotal: 5.60 CHF
MWST: 0.43 CHF
Rabatt: 0.00 CHF
Total: 6.03 CHF
```

## Bonus
- Warenkorb als assoziatives Array `['name' => ..., 'price' => ...]` führen.  
- CLI-Input: Produkte per `readline()` einlesen (While-Schleife).  
- Export: Quittung in eine Datei schreiben (`file_put_contents`).  
- Tests: Einfache Assertions (manuell) für `addMwst` und `rabatt`.

