<?php

$data = [
    'firstname' => 'Jessica',
    'lastname' => 'Fisher',
    'email' => 'jessica.fisher@gmail.com'
];

require_once 'config.php'; // Bindet die Datenbankkonfiguration ein

try {
    // Erstellt eine neue PDO-Instanz mit der Konfiguration aus config.php
    $pdo = new PDO($dsn, $username, $password, $options);

    // SQL-Query mit Platzhaltern für das Einfügen von Daten
    $sql = "INSERT INTO User (firstname, lastname, email) VALUES (?, ?, ?)";

    // Bereitet die SQL-Anweisung vor
    $stmt = $pdo->prepare($sql);

    // Fügt jedes Element im Array in die Datenbank ein (Reihenfolge wichtig)
     
        $stmt->execute([
            $data['firstname'],
            $data['lastname'],
            $data['email'],
        ]);
    

    echo "Daten erfolgreich eingefügt.";
} catch (PDOException $e) {
    die("Verbindung zur Datenbank konnte nicht hergestellt werden: " . $e->getMessage());
}