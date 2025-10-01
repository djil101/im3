<?php

/**
 * read_id.php 
 * Ziel: EINEN Datensatz als JSON ausgeben.
 * 
 * HINWEISE:
 * 1. Nutze require_once 'config.php' für $dsn, $username, $password, $options.
 * 2. Prüfe, ob der 'string'-Parameter in der URL vorhanden ist.
 * 3. Speicher den 'string'-Parameter in einer Variablen.
 * 4. Verwende einen try-catch-Block, um Fehler abzufangen.
 * 5. Stelle eine PDO-Verbindung her.
 * 6. Baue ein SELECT, alle Zeilen mit einem bestimmten Suchstring 
 *    aus der Tabelle User ausgibt. Der Suchstring soll in den Spalten 
 *    'firstname', 'lastname' oder 'email' gesucht werden.
 *      SELECT * FROM User WHERE 
 *        firstname LIKE <suchstring> OR 
 *        lastname LIKE <suchstring> OR 
 *        email LIKE <suchstring>
 * 7. Verwende prepared statements.
 * 8. Führe das Statement aus.
 *    In diesem Fall wird der Wert des 'string'-Parameters dreimal in das Array eingefügt.
 * 9. Speichere Das Ergebnis mit fetchAll() in einer Variablen.
 * 10. Setze den Header 'Content-Type: application/json' und gib ein JSON-Array zurück.
 * 11. Wenn ein Fehler auftritt, gib die Fehlermeldung als JSON-Objekt zurück.
 */

// ------------------------------------------

// 1. 

// 2.

  // 3.

  // 4.

    // 5.
    
    // 6.

    // 7.

    // 8.

    // 9.

    // 10.

    // 11.
