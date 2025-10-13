<?php
declare(strict_types=1);

require_once 'config.php';
header('Content-Type: application/json; charset=utf-8');

try {
    $pdo = new PDO($dsn, $username, $password, $options);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $action = $_GET['action'] ?? 'raw';

    if ($action === 'avg_by_weekday') {
        // Erwartet: ?action=avg_by_weekday&weekday=0..6 (0=Mo, 6=So)
        $weekday = isset($_GET['weekday']) ? (int)$_GET['weekday'] : 0;
        if ($weekday < 0 || $weekday > 6) {
            http_response_code(400);
            echo json_encode(['error' => 'Parameter weekday muss 0..6 sein (0=Mo, 6=So).']);
            exit;
        }

        // date ist in Lokalzeit gespeichert -> keine CONVERT_TZ-Nutzung
        $sql = "
            SELECT
                station_name,
                HOUR(`date`) AS hour_local,
                AVG(bike_available_to_rent) AS avg_available
            FROM `velometerChart`
            WHERE WEEKDAY(`date`) = :weekday
            GROUP BY station_name, hour_local
            ORDER BY station_name, hour_local
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':weekday' => $weekday]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Ergebnis in kompaktes JSON transformieren:
        // {
        //   "weekday": 0,
        //   "data": {
        //     "Bahnhofplatz": { "0": null, "1": null, ..., "23": 3.5 },
        //     "Obere Au": { ... },
        //     "Kantonsspital": { ... }
        //   }
        // }
        $result = [];
        foreach ($rows as $r) {
            $st = (string)$r['station_name'];
            $h  = (string)intval($r['hour_local']);
            $avg = $r['avg_available'] !== null ? (float)$r['avg_available'] : null;
            if (!isset($result[$st])) {
                // vorinitialisieren mit 0..23 -> null
                $result[$st] = array_fill_keys(array_map('strval', range(0, 23)), null);
            }
            $result[$st][$h] = $avg;
        }

        echo json_encode(['weekday' => $weekday, 'data' => $result], JSON_UNESCAPED_UNICODE);
        exit;
    }

    if ($action === 'current') {
    // Hole die neuesten Daten für alle Stationen
    $sql = "
        SELECT 
            station_name,
            bike_available_to_rent,
            date
        FROM `velometerChart`
        WHERE (station_name, date) IN (
            SELECT station_name, MAX(date) 
            FROM `velometerChart` 
            GROUP BY station_name
        )
        ORDER BY station_name
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($rows, JSON_UNESCAPED_UNICODE);
    exit;
}

    // Default: Rohdaten (wie bisher)
    $sql = "SELECT * FROM `velometerChart`";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($results, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'DB-Fehler', 'details' => $e->getMessage()]);
} catch (Throwable $t) {
    http_response_code(500);
    echo json_encode(['error' => 'Server-Fehler', 'details' => $t->getMessage()]);
}