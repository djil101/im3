# 📑 Cheatsheet `Daten aus DB abrufen (PHP & PDO)`

> Voraussetzungen: PHP ≥ 8.1, `declare(strict_types=1);`, aktiviertes PDO-Driver (z. B. `pdo_mysql`).  
> Grundidee: **Verbinde → Bereite Statement vor → Führe aus → Hole Daten → Gib aus.**

---

## 0) Boilerplate: Verbindung & Basis-Optionen

```php
<?php
declare(strict_types=1);

require_once '001_config.php'; // enthält $dsn, $username, $password, $options

try {
    $pdo = new PDO($dsn, $username, $password, $options);
    // Empfohlen in 001_config.php:
    // PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    // PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    // PDO::ATTR_EMULATE_PREPARES => false
} catch (PDOException $e) {
    http_response_code(500);
    exit("DB-Verbindung fehlgeschlagen.");
}
```

---

## 1) Einfacher SELECT (alle Zeilen)

```php
$sql  = "SELECT id, firstname, lastname, email, created_at FROM User ORDER BY id ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll();   // Alle Zeilen
```

---

## 2) SELECT by ID (Prepared Statement)

```php
$id = $_GET['id'] ?? null;
if ($id === null || !ctype_digit((string)$id)) {
    http_response_code(400);
    exit("Gültige id erwartet.");
}

$sql  = "SELECT id, firstname, lastname, email, created_at FROM User WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$user = $stmt->fetch(); // genau eine Zeile oder false
```

---

## 3) SELECT mit Suche (LIKE)

```php
$q = trim((string)($_GET['q'] ?? ''));
if ($q === '') { $q = ''; }

$sql  = "SELECT id, firstname, lastname, email FROM User
         WHERE firstname LIKE ? OR lastname LIKE ? OR email LIKE ?
         ORDER BY lastname ASC, firstname ASC
         LIMIT 50";

$like = "%{$q}%";
$stmt = $pdo->prepare($sql);
$stmt->execute([$like, $like, $like]);

$matches = $stmt->fetchAll();
```

---

## 4) Verschiedene Ausgabemöglichkeiten

### 4.1 HTML

```php
header('Content-Type: text/html; charset=utf-8');
?>
<table>
  <thead><tr><th>ID</th><th>Name</th><th>Email</th></tr></thead>
  <tbody>
  <?php foreach ($users as $u): ?>
    <tr>
      <td><?= htmlspecialchars((string)$u['id']) ?></td>
      <td><?= htmlspecialchars($u['firstname'] . ' ' . $u['lastname']) ?></td>
      <td><?= htmlspecialchars($u['email']) ?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<?php
```

---

### 4.2 JSON (API)

```php
header('Content-Type: application/json; charset=utf-8');
echo json_encode($users, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
```

---

### 4.3 CSV

```php
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="users.csv"');
$out = fopen('php://output', 'w');
fputcsv($out, ['id','firstname','lastname','email','created_at'], ';');
foreach ($users as $u) {
    fputcsv($out, [$u['id'], $u['firstname'], $u['lastname'], $u['email'], $u['created_at']], ';');
}
fclose($out);
```

---

### 4.4 Plain-Text

```php
header('Content-Type: text/plain; charset=utf-8');
foreach ($users as $u) {
    echo $u['id'] . " | " . $u['firstname'] . " " . $u['lastname'] . " | " . $u['email'] . PHP_EOL;
}
```

---

### 4.5 Debug

```php
echo "<pre>";
print_r($users);
echo "</pre>";
```

---

## 5) Pagination

```php
$page  = max(1, (int)($_GET['page'] ?? 1));
$limit = 20;
$offset = ($page - 1) * $limit;

$sql  = "SELECT id, firstname, lastname, email FROM User
         ORDER BY id ASC
         LIMIT :limit OFFSET :offset";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':limit',  $limit,  PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

$users = $stmt->fetchAll();
```

---

## 6) Fetch-Strategien

```php
// a) Alle auf einmal
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// b) Zeile für Zeile
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { /* ... */ }

// c) Nur eine Spalte
$emails = [];
while ($email = $stmt->fetchColumn(0)) { $emails[] = $email; }
```

---

## 7) Fehlerbehandlung

```php
try {
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Interner DB-Fehler.']);
}
```

---

## 8) Sicherheit & Qualität

- Immer **Prepared Statements** verwenden.  
- Eingaben validieren.  
- HTML-Ausgaben escapen.  
- Sortierung & LIMIT für Performance.  
- UTF-8 überall.  

---

## 9) Switchable Output

```php
$format = $_GET['format'] ?? 'html';

switch ($format) {
    case 'json':
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($users);
        break;
    case 'csv':
        // CSV-Ausgabe (s. 4.3)
        break;
    default:
        // HTML-Ausgabe (s. 4.1)
}
```

---

## 10) Häufige Stolperfallen

- Direkte Übergabe von `$_GET` an SQL → Injection.  
- Fehlender `Content-Type`.  
- Unsortierte SELECTs.  
- Keine Pagination → riesige Responses.  
- Debug-Ausgaben in Produktion.
