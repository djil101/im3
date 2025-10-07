<?php
declare(strict_types=1);

// Transformations-Skript einbinden (liefert bereits ein PHP-Array)
$dataArray = include __DIR__ . '/transform.php';

if (!is_array($dataArray)) {
    die("Fehler: transform.php hat kein Array zurueckgegeben.");
}

require_once __DIR__ . '/config.php'; // $dsn, $username, $password, $options

try {
    $pdo = new PDO($dsn, $username, $password, $options ?? []);
    // Falls in config.php nicht gesetzt:
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Optional: Abbrechen, wenn nichts da ist
    if (empty($dataArray)) {
        echo "Hinweis: Keine Datensaetze zum Einfuegen gefunden.";
        exit;
    }

    // Transaktion fuer Performance & Konsistenz
    $pdo->beginTransaction();

    // Achtung: `date` zur Sicherheit escapen
    $sql = "INSERT INTO velometer (`date`, station_name, bike_racks, bike_available_to_rent)
            VALUES (:date, :station_name, :bike_racks, :bike_available_to_rent)";
    $stmt = $pdo->prepare($sql);

    $inserted = 0;
    foreach ($dataArray as $item) {
        // Minimal-Validierung
        if (!isset($item['date'], $item['station_name'])) {
            continue;
        }
        $stmt->execute([
            ':date'                   => $item['date'],
            ':station_name'           => $item['station_name'],
            ':bike_racks'             => $item['bike_racks'] ?? null,
            ':bike_available_to_rent' => $item['bike_available_to_rent'] ?? null,
        ]);
        $inserted++;
    }

    $pdo->commit();

    echo "✅ $inserted Datensaetze erfolgreich eingefuegt.";
} catch (PDOException $e) {
    if ($pdo && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    die("DB-Fehler: " . $e->getMessage());
}
