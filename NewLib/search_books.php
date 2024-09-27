<?php
include 'connect.php';

if (isset($_GET['query'])) {
    $query = $conn->real_escape_string($_GET['query']);
    $result = $conn->query("SELECT id, title, author, quantity FROM books WHERE title LIKE '%$query%' OR author LIKE '%$query%'");

    $books = [];
    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($books);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid query']);
}
?>
