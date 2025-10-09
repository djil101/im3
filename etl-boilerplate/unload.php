<?php
declare(strict_types=1);

require_once 'config.php';
header('Content-Type: application/json; charset=utf-8');

try {
    $pdo = new PDO($dsn, $username, $password, $options);
    $sql = "SELECT * FROM `velometer`";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $results = $stmt->fetchAll();

    echo json_encode($results, JSON_PRETTY_PRINT);}
catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);}

// -------- Input & Defaults --------
$action     = $_GET['action'] ?? 'current'; // 'current' | 'stats'
$stationRaw = $_GET['station_name'] ?? '';  // NUR station_name erlaubt
$weekday    = $_GET['weekday'] ?? '';       // Mo,Di,... oder 1–7
$hours      = (isset($_GET['hours']) && is_numeric($_GET['hours'])) ? max(1, (int)$_GET['hours']) : 2;
$startHr    = isset($_GET['start_hour']) ? max(0, min(23, (int)$_GET['start_hour'])) : 0;
$endHr      = isset($_GET['end_hour'])   ? max(0, min(23, (int)$_GET['end_hour']))   : 23;

// -------- Station validieren & normalisieren --------
$allowed = ['Bahnhofplatz','Obere Au','Kantonsspital'];

$normalize = function(string $s): string {
    $s = trim($s);
    $s = str_replace(
        ['ä','ö','ü','Ä','Ö','Ü','ß','é','è','ê','É','È','Ê'],
        ['ae','oe','ue','Ae','Oe','Ue','ss','e','e','e','E','E','E'],
        $s
    );
    return mb_strtolower($s, 'UTF-8');
};

$stationMap = [
    'bahnhofplatz'                 => 'Bahnhofplatz',
    'obere au'                     => 'Obere Au',
    'kantonsspital'                => 'Kantonsspital',
    'kantonsspital graubuenden'    => 'Kantonsspital',
    'kantonsspital graubunden'     => 'Kantonsspital',
    'kantonsspital graubünden'     => 'Kantonsspital',
];

$stationNorm = $normalize((string)$stationRaw);
$stationDb   = $stationMap[$stationNorm] ?? '';

if (!in_array($stationDb, $allowed, true)) {
    http_response_code(400);
    echo json_encode([
        'error' => 'Parameter "station_name" fehlt oder ist ungueltig. Erlaubt: Bahnhofplatz, Obere Au, Kantonsspital.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// -------- Wochentag mappen (So=1 … Sa=7) --------
$wMap = [
    'so'=>1,'sonntag'=>1,
    'mo'=>2,'montag'=>2,
    'di'=>3,'dienstag'=>3,
    'mi'=>4,'mittwoch'=>4,
    'do'=>5,'donnerstag'=>5,
    'fr'=>6,'freitag'=>6,
    'sa'=>7,'samstag'=>7
];

$weekdayNum = null;
if ($weekday !== '') {
    $w = mb_strtolower(trim((string)$weekday), 'UTF-8');
    if (isset($wMap[$w])) {
        $weekdayNum = $wMap[$w];
    } elseif (ctype_digit($w) && (int)$w >= 1 && (int)$w <= 7) {
        $weekdayNum = (int)$w;
    }
}

try {
    $pdo = new PDO($dsn, $username, $password, $options ?? []);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // -------- ACTION: current --------
    if ($action === 'current') {
        $stmt = $pdo->prepare("
            SELECT `date`, station_name, bike_racks, bike_available_to_rent
            FROM velometer
            WHERE station_name = :s
            ORDER BY `date` DESC
            LIMIT 1
        ");
        $stmt->execute([':s' => $stationDb]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode($row ?: new stdClass(), JSON_UNESCAPED_UNICODE);
        exit;
    }

    // -------- ACTION: stats --------
    if ($action === 'stats') {
        if ($weekdayNum === null) {
            http_response_code(400);
            echo json_encode([
                'error' => 'Parameter "weekday" fehlt/ungueltig. Erlaubt: Mo…So oder 1–7 (So=1).'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        $stmt = $pdo->prepare("
            SELECT HOUR(`date`) AS hour,
                   AVG(bike_available_to_rent) AS avg_bikes,
                   COUNT(*) AS samples
            FROM velometer
            WHERE station_name = :s
              AND DAYOFWEEK(`date`) = :w
              AND HOUR(`date`) BETWEEN :sh AND :eh
            GROUP BY HOUR(`date`)
            ORDER BY hour ASC
        ");
        $stmt->bindValue(':s',  $stationDb, PDO::PARAM_STR);
        $stmt->bindValue(':w',  $weekdayNum, PDO::PARAM_INT);
        $stmt->bindValue(':sh', $startHr, PDO::PARAM_INT);
        $stmt->bindValue(':eh', $endHr,   PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Stunden in vollständigen Bereich 0–23 (oder eingeschränkt) bringen
        $hourly = [];
        for ($h = $startHr; $h <= $endHr; $h++) {
            $match = array_filter($rows, fn($r) => (int)$r['hour'] === $h);
            $row = $match ? array_values($match)[0] : null;
            $hourly[] = [
                'hour'      => $h,
                'avg_bikes' => $row ? (float)$row['avg_bikes'] : null,
                'samples'   => $row ? (int)$row['samples'] : 0,
            ];
        }

        // Bestes Zeitfenster finden (zusammenhängend)
        $best = ['start_hour'=>null,'end_hour'=>null,'avg_bikes'=>null,'hours'=>$hours];
        $count = count($hourly);
        for ($i = 0; $i < $count; $i++) {
            $start = $hourly[$i]['hour'];
            $end   = $start + $hours - 1;
            if ($end > $endHr) break;

            $sum = 0.0; $cnt = 0; $valid = true;
            for ($h = $start; $h <= $end; $h++) {
                $found = array_filter($hourly, fn($r) => $r['hour'] === $h);
                $entry = $found ? array_values($found)[0] : null;
                if (!$entry || $entry['avg_bikes'] === null) { $valid = false; break; }
                $sum += $entry['avg_bikes']; $cnt++;
            }
            if (!$valid || $cnt === 0) continue;
            $avg = $sum / $cnt;
            if ($best['avg_bikes'] === null || $avg > $best['avg_bikes']) {
                $best = ['start_hour'=>$start,'end_hour'=>$end,'avg_bikes'=>$avg,'hours'=>$hours];
            }
        }

        echo json_encode([
            'station_name' => $stationDb,
            'weekday'      => $weekdayNum,
            'range'        => ['start_hour'=>$startHr,'end_hour'=>$endHr],
            'hours'        => $hourly,
            'best_window'  => $best
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // -------- Fallback bei unbekannter Action --------
    http_response_code(400);
    echo json_encode(['error' => 'Unbekannte action. Erlaubt: current oder stats'], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
