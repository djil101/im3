# Cheatsheet `PDO – Datenbankverbindungen und -operationen` in PHP

PDO (PHP Data Objects) ist die empfohlene Schnittstelle, um mit Datenbanken wie **MySQL/MariaDB, PostgreSQL, SQLite, SQL Server** usw. zu arbeiten.  
Sie bietet **einheitliche Methoden** für verschiedene Datenbanken und unterstützt **Prepared Statements** für mehr Sicherheit.

## Verbindung aufbauen

```php
<?php
declare(strict_types=1);

$host    = "localhost";
$db      = "meine_datenbank";
$user    = "root";
$pass    = "";
$charset = "utf8mb4";

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Fehler als Exceptions
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Assoziative Arrays
    PDO::ATTR_EMULATE_PREPARES   => false,                  // native Prepared Statements
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    // ready to use
} catch (PDOException $e) {
    die("Verbindung fehlgeschlagen: " . $e->getMessage());
}
```

**Erklärung:**  
- **DSN**: beschreibt Treiber + Ziel (hier MySQL/MariaDB). Setze **`charset=utf8mb4`** für volle Unicode-Unterstützung.  
- **`ATTR_ERRMODE=EXCEPTION`**: vereinfacht Fehlerhandling per `try/catch`.  
- **`ATTR_DEFAULT_FETCH_MODE=FETCH_ASSOC`**: gibt Arrays mit Spaltennamen zurück.  
- **`ATTR_EMULATE_PREPARES=false`**: nutzt native Prepared Statements (sicherer, korrektes Typing).  
- `try/catch`: verhindert White-Screen und ermöglicht saubere Fehlermeldungen/Logs.



## Einfache Query (nur für fixe Statements – Anti-Pattern für User-Input)

```php
<?php
$stmt = $pdo->query("SELECT id, name FROM users");
foreach ($stmt as $row) {
    echo $row["id"] . " – " . $row["name"] . PHP_EOL;
}
```

**Erklärung:**  
- **Nur** verwenden, wenn **kein** dynamischer Input in die SQL gelangt.  
- Für alles mit Parametern: **immer Prepared Statements** (siehe unten), sonst SQL-Injection-Risiko.


## Prepared SELECT (mit benannten Platzhaltern)

```php
<?php
$sql  = "SELECT id, name, email FROM users WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([":id" => 5]);

$user = $stmt->fetch();   // erster/ einziger Datensatz oder false
// $users = $stmt->fetchAll(); // alternativ: alle Datensätze
```

**Erklärung:**  
- **Prepared + `execute`**: DB parst das Statement einmal, Parameter werden sicher gebunden.  
- **`:id`** ist ein benannter Platzhalter → lesbar & weniger fehleranfällig.  
- **`fetch()`** vs. **`fetchAll()`**: `fetch()` ist speicherschonend, `fetchAll()` holt alles auf einmal.


## Prepared INSERT

```php
<?php
$sql  = "INSERT INTO users (name, email) VALUES (:name, :email)";
$stmt = $pdo->prepare($sql);

$stmt->execute([
    ":name"  => "Anna",
    ":email" => "anna@example.com",
]);

$newId = $pdo->lastInsertId();
```

**Erklärung:**  
- **Parameterarray** verhindert SQL-Injection.  
- **`lastInsertId()`** liefert die Auto-Increment-ID (MySQL/MariaDB).


## Prepared UPDATE

```php
<?php
$sql  = "UPDATE users SET email = :email WHERE id = :id";
$stmt = $pdo->prepare($sql);

$stmt->execute([
    ":email" => "neu@example.com",
    ":id"    => 5,
]);

$affected = $stmt->rowCount();
```

**Erklärung:**  
- **`rowCount()`**: Anzahl betroffener Zeilen (bei MySQL/MariaDB: 0 kann bedeuten „Wert war identisch“).  
- Updates immer **gezielt** mit WHERE einschränken.


## Prepared DELETE

```php
<?php
$sql  = "DELETE FROM users WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([":id" => 5]);

$deleted = $stmt->rowCount();
```

**Erklärung:**  
- Immer **WHERE** setzen, sonst löschst du alles.  
- `rowCount()` liefert die Anzahl gelöschter Zeilen.


## Fetch-Varianten (Cursor-Iteration)

```php
<?php
$stmt = $pdo->query("SELECT id, name FROM users");

// Variante A: explizites fetch()
while ($row = $stmt->fetch()) {
    echo $row["id"] . ": " . $row["name"] . PHP_EOL;
}

// Variante B: foreach über Statement (internes Iterieren)
foreach ($pdo->query("SELECT id, name FROM users") as $row) {
    echo $row["id"] . ": " . $row["name"] . PHP_EOL;
}
```

**Erklärung:**  
- **Cursor-basiert** iterieren spart RAM bei grossen Resultsets.  
- `FETCH_ASSOC` (Default via Option) → `$row['spalte']`.


## Fragezeichen-Platzhalter (Alternative)

```php
<?php
$sql  = "SELECT * FROM users WHERE email = ? AND status = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute(["anna@example.com", "active"]);
$result = $stmt->fetchAll();
```

**Erklärung:**  
- **Positionsbasiert** (Reihenfolge der Werte zählt).  
- Funktional gleich sicher, aber weniger selbstdokumentierend als benannte Platzhalter.


## Transaktionen (atomare Mehrschritt-Operationen)

```php
<?php
try {
    $pdo->beginTransaction();

    $stmt1 = $pdo->prepare(
        "UPDATE accounts SET balance = balance - :amount WHERE id = :id"
    );
    $stmt2 = $pdo->prepare(
        "UPDATE accounts SET balance = balance + :amount WHERE id = :id"
    );

    $stmt1->execute([":amount" => 100, ":id" => 1]);
    $stmt2->execute([":amount" => 100, ":id" => 2]);

    $pdo->commit(); // alles erfolgreich
} catch (Throwable $e) {
    $pdo->rollBack(); // alles rückgängig machen
    // logging / Fehlerbehandlung
    error_log($e->getMessage());
}
```

**Erklärung:**  
- **`beginTransaction` → `commit`**: garantiert „alles oder nichts“.  
- Bei Exception wird in `catch` **`rollBack()`** ausgeführt.  
- Ideal für Geld-Transfers, Mehrzeilen-Upserts, Migrations etc.


## Nützliche Methoden & Konstanten (Kurzreferenz)

```php
<?php
$pdo->lastInsertId();        // letzte Auto-Increment-ID
$stmt->rowCount();           // betroffene Zeilen (INSERT/UPDATE/DELETE)
$stmt->fetch();              // nächster Datensatz (oder false)
$stmt->fetchAll();           // alle Datensätze (vorsichtig bei grossen Tabellen)
$stmt->fetchColumn(0);       // einzelne Spalte der nächsten Zeile

// Häufige Optionen:
PDO::ATTR_ERRMODE;           // z. B. PDO::ERRMODE_EXCEPTION
PDO::ATTR_DEFAULT_FETCH_MODE;// z. B. PDO::FETCH_ASSOC
PDO::ATTR_EMULATE_PREPARES;  // false = native prepares (empfohlen)

// Häufige Fetch-Modi:
PDO::FETCH_ASSOC;            // assoziatives Array
PDO::FETCH_NUM;              // numerische Indizes
PDO::FETCH_BOTH;             // beide
PDO::FETCH_OBJ;              // als Objekt
```

**Erklärung:**  
- Diese Helfer brauchst du ständig beim Lesen/Schreiben.  
- Wähle **FETCH-Modus** bewusst (Performance/Bequemlichkeit).

---

## Fehlerbehandlung

```php
<?php
try {
    $pdo->query("SELECT * FROM table_does_not_exist");
} catch (PDOException $e) {
    // Nutzerfreundliche Reaktion + Logging
    http_response_code(500);
    error_log("[DB] " . $e->getMessage());
    echo "Interner Fehler. Bitte später erneut versuchen.";
}
```

**Erklärung:**  
- **Nie** rohe Fehlermeldungen an Endnutzer ausgeben (Informationsabfluss).  
- Mit **HTTP-Status** + **Log** sauber reagieren.



## Performance-Tipps

```php
<?php
// 1) Stückweise verarbeiten statt alles in den RAM laden
$stmt = $pdo->query("SELECT * FROM big_table");
while ($row = $stmt->fetch()) { /* verarbeiten */ }

// 2) Mehrere Inserts in einer Transaktion bündeln
$pdo->beginTransaction();
$stmt = $pdo->prepare("INSERT INTO logs (msg) VALUES (:msg)");
foreach ($messages as $m) {
    $stmt->execute([":msg" => $m]);
}
$pdo->commit();
```

**Erklärung:**  
- **Streaming-Fetch** reduziert Speicherbedarf.  
- **Transaktionen** beschleunigen Massen-Writes merklich (weniger fsync/Lock-Overhead).


## Sicherheit & Best Practices

```php
<?php
// Niemals User-Input direkt in SQL konkatenieren!
$unsicher = "SELECT * FROM users WHERE email = '" . $_GET["email"] . "'";

// Sicher mit Prepared Statements:
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
$stmt->execute([":email" => $_GET["email"] ?? ""]);
```

**Erklärung:**  
- **SQL-Injection** vermeidest du ausschließlich mit Prepared Statements.  
- Validiere Eingaben (Typen, Längen, Whitelists), besonders bei LIMIT/OFFSET/ORDER BY.  
- Trenne **Konfiguration** (DSN/User/Pass) von Code, z. B. per `.env`.  
- Nutze **least privilege** (DB-User nur mit benötigten Rechten).  
- Protokolliere Fehler **serverseitig** (keine sensiblen Infos im Frontend).



## Upsert (MySQL/MariaDB-spezifisch)
Upsert ist ein zusammengesetztes Wort aus Update + Insert.

👉 Es bedeutet:

- Wenn ein Datensatz noch nicht existiert, wird er eingefügt (Insert).
- Wenn der Datensatz schon existiert (z. B. anhand eines Primärschlüssels oder Unique Keys), wird er aktualisiert (Update).

```php
<?php
$sql = "
  INSERT INTO users (id, name, email)
  VALUES (:id, :name, :email)
  ON DUPLICATE KEY UPDATE
    name = VALUES(name),
    email = VALUES(email)
";
$stmt = $pdo->prepare($sql);
$stmt->execute([
  ":id"    => 42,
  ":name"  => "Lea",
  ":email" => "lea@example.com",
]);
```

**Erklärung:**  
- Wenn es noch keinen id=42 gibt → neuer Datensatz wird eingefügt.
- Wenn id=42 schon existiert → die Werte name und email werden aktualisiert.
- Praktisch für **Synchronisation/ETL**: falls `id` existiert → Update, sonst Insert.  
- Voraussetzung: eindeutiger Index/Primary Key.

### Warum Upsert?
- Spart Code, da man nicht vorher prüfen muss, ob ein Datensatz existiert.
- Nützlich bei Synchronisationen, ETL-Prozessen, Importen.
- Verhindert doppelte Einträge bei gleichzeitiger Möglichkeit zur Aktualisierung.


## Platzhalter korrekt binden (Typing-Hinweis)

```php
<?php
$stmt = $pdo->prepare("SELECT * FROM products WHERE price >= :min");
$stmt->bindValue(":min", 19.90, PDO::PARAM_STR); // oder PARAM_INT bei ganzen Zahlen
$stmt->execute();
```

**Erklärung:**  
- MySQL führt intern oft String-Vergleiche tolerant aus; sauberes Typing (INT/STR/BOOL) verhindert schleichende Bugs und sorgt für verständliche Query-Pläne.
