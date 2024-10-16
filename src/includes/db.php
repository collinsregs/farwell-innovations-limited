<?php
// includes/db.php
require_once 'config.php';

try {
    // Set up the DSN for SQLite
    $dsn = "sqlite:" . DB_PATH;


    // Create a new PDO instance
    $pdo = new PDO($dsn);// Use native prepared statements]);


    // Enable foreign key constraints in SQLite
    $pdo->exec("PRAGMA foreign_keys = ON;");

    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>