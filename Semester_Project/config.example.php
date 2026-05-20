<?php
// ============================================================
//  UETM Library Portal - Configuration Template
//  Copy this file to config.php and fill in your credentials
// ============================================================

define('DB_HOST', 'localhost');
define('DB_NAME', 'library_portal');
define('DB_USER', 'root');       // Your MySQL username
define('DB_PASS', '');           // Your MySQL password

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function redirect($url) {
    header("Location: $url");
    exit;
}
