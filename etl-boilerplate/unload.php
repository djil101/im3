<?php
declare(strict_types=1);
require_once 'config.php'; // Stellen Sie sicher, dass dies auf Ihre tatsächliche Konfigurationsdatei verweist
header('Content-Type: application/json; charset=utf-8');

// --- Inputs ---
$action  = $_GET['action']  ?? 'current'; // current | stats
$station = $_GET['station'] ?? '';
$weekday = $_GET['weekday'] ?? '';        // Mo,Di,Mi,Do,Fr,Sa,So oder 1-7 (So=1)
$hours   = isset($_GET['hours']) && is_numeric($_GET['hours']) ? max(1,(int)$_GET['hours']) : 2; // Länge bestes Fenster
$startHr = isset($_GET['start_hour']) ? max(0, min(23, (int)$_GET['start_hour'])) : 0;
$endHr   = isset($_GET['end_hour'])   ? max(0, min(23, (int)$_GET['end_hour']))   : 23;

// Station normalisieren (nur 3 erlaubt)
$allowed = ['Bahnhofplatz','Obere Au','Kantonsspital'];
$norm = function(string $s){ return mb_strtolower(str_replace(['ä','ö','ü','Ä','Ö','Ü','ß'],['ae','oe','ue','Ae','Oe','Ue','ss'], trim($s)), 'UTF-8'); };
$toDb = function(string $s) use($norm){
  $n=$norm($s);
  if (in_array($n,['kantonsspital graubuenden','kantonsspital graubunden','kantonsspital graubünden'],true)) return 'Kantonsspital';
  if ($n===$norm('Bahnhofplatz')) return 'Bahnhofplatz';
  if ($n===$norm('Obere Au'))     return 'Obere Au';
  if (in_array($s,['Bahnhofplatz','Obere Au','Kantonsspital'],true)) return $s;
  return '';
};
$stationDb = $toDb($station);
if (!in_array($stationDb, $allowed, true)) {
  http_response_code(400);
  echo json_encode(['error'=>'Parameter "station" fehlt oder ist ungueltig. Erlaubt: Bahnhofplatz, Obere Au, Kantonsspital.']);
  exit;
}

// Wochentag mappen (So=1 … Sa=7, wie MySQL DAYOFWEEK)
$wMap = ['so'=>1,'sonntag'=>1,'mo'=>2,'montag'=>2,'di'=>3,'dienstag'=>3,'mi'=>4,'mittwoch'=>4,'do'=>5,'donnerstag'=>5,'fr'=>6,'freitag'=>6,'sa'=>7,'samstag'=>7];
$weekdayNum = null;
if ($weekday!=='') {
  $w = mb_strtolower(trim((string)$weekday),'UTF-8');
  if (isset($wMap[$w])) $weekdayNum = $wMap[$w];
  elseif (ctype_digit($w) && (int)$w>=1 && (int)$w<=7) $weekdayNum = (int)$w;
}

try {
  $pdo = new PDO($dsn, $username, $password, $options ?? []);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  if ($action === 'current') {
    $stmt = $pdo->prepare("
      SELECT `date`, station_name, bike_racks, bike_available_to_rent
      FROM velometer
      WHERE station_name = :s
      ORDER BY `date` DESC
      LIMIT 1
    ");
    $stmt->execute([':s'=>$stationDb]);
    echo json_encode($stmt->fetch(PDO::FETCH_ASSOC) ?: new stdClass(), JSON_UNESCAPED_UNICODE);
    exit;
  }

  if ($action === 'stats') {
    if ($weekdayNum===null) {
      http_response_code(400);
      echo json_encode(['error'=>'Parameter "weekday" fehlt/ungueltig. Erlaubt: Mo…So oder 1-7 (So=1).']);
      exit;
    }

    // Stundenmittel holen
    $stmt = $pdo->prepare("
      SELECT HOUR(`date`) AS hour, AVG(bike_available_to_rent) AS avg_bikes, COUNT(*) AS samples
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

    // Array 0–23 (oder eingeschränkt) füllen + bestes Fenster finden
    $hourly = [];
    for ($h=$startHr; $h<=$endHr; $h++) $hourly[$h] = null;
    foreach ($rows as $r) $hourly[(int)$r['hour']] = $r['avg_bikes']!==null ? (float)$r['avg_bikes'] : null;

    // Bestes zusammenhängendes Fenster (nur Stunden mit Daten zählen)
    $best = ['start_hour'=>null,'end_hour'=>null,'avg_bikes'=>null,'hours'=>$hours];
    $keys = array_keys($hourly);
    for ($i=0; $i<count($keys); $i++) {
      $start = $keys[$i]; $end = $start + $hours - 1; if ($end>$endHr) break;
      $sum=0; $cnt=0; $hasNull=false;
      for ($h=$start; $h<=$end; $h++) {
        if ($hourly[$h]===null) { $hasNull=true; break; }
        $sum += $hourly[$h]; $cnt++;
      }
      if ($hasNull || $cnt===0) continue;
      $avg = $sum / $cnt;
      if ($best['avg_bikes']===null || $avg>$best['avg_bikes']) $best = ['start_hour'=>$start,'end_hour'=>$end,'avg_bikes'=>$avg,'hours'=>$hours];
    }

    echo json_encode([
      'station'    => $stationDb,
      'weekday'    => $weekdayNum, // So=1 … Sa=7
      'range'      => ['start_hour'=>$startHr,'end_hour'=>$endHr],
      'hours'      => array_map(fn($h,$v)=>['hour'=>$h,'avg_bikes'=>$v,'samples'=> (int)($rows[array_search($h, array_column($rows,'hour'))]['samples'] ?? 0)], array_keys($hourly), array_values($hourly)),
      'best_window'=> $best
    ], JSON_UNESCAPED_UNICODE);
    exit;
  }

  http_response_code(400);
  echo json_encode(['error'=>'Unbekannte action. Erlaubt: current oder stats']);
} catch (PDOException $e) {
  http_response_code(500);
  echo json_encode(['error'=>$e->getMessage()]);
}


header('Content-Type: application/json');
