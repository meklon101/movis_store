<?php
// Database connection configuration for the online cinema app.
// Uses PDO with safe settings and connects to the local MySQL database.
$host = '127.0.0.1';
$dbName = 'online_cinema';
$dbUser = 'root';
$dbPass = '';

// PDO options ensure exceptions, associative arrays, and real prepared statements.
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPass, $options);
} catch (PDOException $e) {
    // On connection failure, stop execution and show a clear error.
    die('Database connection failed: ' . $e->getMessage());
}
?>
