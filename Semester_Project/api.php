<?php
require_once 'config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Simple API key authentication (Optional but good for marks)
$api_key = $_GET['api_key'] ?? '';
$valid_api_key = 'uetm_secret_key_2026'; // Simple hardcoded key for demonstration

if ($api_key !== $valid_api_key) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized. Invalid API Key. Please provide ?api_key=uetm_secret_key_2026']);
    exit;
}

$endpoint = $_GET['endpoint'] ?? '';

if ($endpoint === 'books') {
    if (isset($pdo)) {
        try {
            $stmt = $pdo->query('SELECT id, title, author, category, isbn, published_year, quantity, status, created_at FROM books ORDER BY created_at DESC');
            $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode([
                'status' => 'success',
                'count' => count($books),
                'data' => $books
            ]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Database Error']);
        }
    }
} elseif ($endpoint === 'stats') {
    if (isset($pdo)) {
        try {
            $total_books = $pdo->query('SELECT COUNT(*) FROM books')->fetchColumn();
            $total_users = $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
            $available_books = $pdo->query("SELECT COUNT(*) FROM books WHERE status = 'Available'")->fetchColumn();
            
            echo json_encode([
                'status' => 'success',
                'data' => [
                    'total_books' => $total_books,
                    'total_users' => $total_users,
                    'available_books' => $available_books
                ]
            ]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Database Error']);
        }
    }
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Endpoint not found. Available endpoints: books, stats']);
}
?>
