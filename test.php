<?php

// Variable definieren
// vgl. JavaScript: let name = 'testilein';

$name = 'testilein'; 
echo "Hallo $name!";

$a = 292;
$b = 22;
echo $a + $b;

// Funktionen

function multiply($a, $b) {
    return $a * $b;
}
echo multiply(3, 4);

// Bedingungen
//Note muss 4 oder grösser sein, um zu bestehen
$note = 3.75;
if ($note >= 4) {
    echo "Du hesch bestange :)";
} elseif ($note < 4 && $note >= 3.5) {
    echo "Du dörfsch no einisch probiere";
} else {
    echo "Du hesch nöd bestange :(";
}

// Arrays
$banane = ["mama banane", "papa banane", "kind banane"];
echo "<pre>";
print_r($banane[2]);
echo "</pre>";

foreach ($banane as $item) {
    echo $item . "<br>";
}

// Assoziative Arrays
$standorte = [
    "chur" => 15.4,
    "zürich" => 20.1,
    "bern" => 18.3
];



?>

<h1>hallo <?php echo $name; ?></h1>