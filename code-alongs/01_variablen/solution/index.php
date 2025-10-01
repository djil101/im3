<?php

/**
 * 01_variablen/index.php 
 * Ziel: Variablen deklarieren, Typen mischen, Werte ausgeben (echo, print_r, var_dump).
 * 
 * Aufgabe:
 * Lege Variablen für einen kleinen Warenkorb an und berechne den Totalbetrag inkl. MWST. Gib am Ende eine formattierte Quittung aus.
 * 
 * HINWEISE:
 * 1. Variablen für zwei Artikel (Name, Preis) anlegen. Beispiel: $item1, $price1, $item2, $price2.
 * 2. Variable für MWST-Satz anlegen (z.B. 0.8 für 8%).
 * 3. Zwischensumme berechnen (Summe der Artikelpreise).
 * 4. MWST-Betrag berechnen (Zwischensumme * MWST-Satz).
 * 5. Total berechnen (Zwischensumme + MWST-Betrag).
 * 6. Ausgabe formatiert: "Brot: 2.90 | Banane: 1.20 | MWST: ... | Total: ..."
 * BONUS (optional):
 * 7. Nutze number_format($zahl, 2) für schöne Ausgabe.
 */

// ------------------------------------------

//1. Variablen für zwei Artikel (Name, Preis) anlegen. Beispiel: $item1, $price1, $item2, $price2.
$item1 = "Brot";
$price1 = 4.90;
$item2 = "Banane";
$price2 = 1.20;

//2. Variable für MWST-Satz anlegen (z.B. 0.8 für 8%).
$mwst = 0.08;

//3. Zwischensumme berechnen (Summe der Artikelpreise).
$subtotal = $price1 + $price2;

//4. MWST-Betrag berechnen (Zwischensumme * MWST-Satz).
$mwst_amount = $subtotal * $mwst;

//5. Total berechnen (Zwischensumme + MWST-Betrag).
$total = $subtotal + $mwst_amount;

//6. Ausgabe formatiert: "Brot: 2.90 | Banane: 1.20 | MWST: ... | Total: ..."
echo "$item1: $price1 | $item2: $price2 | MWST: $mwst_amount | Total: $total";


//7. 
echo "<br>";
echo number_format($price1, 2);
echo "<br>";
echo number_format($price2, 2);
echo "<br>";
echo number_format($mwst_amount, 2);
echo "<br>";
