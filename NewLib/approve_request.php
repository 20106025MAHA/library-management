<?php
include 'connect.php';

$data = json_decode(file_get_contents('php://input'), true);
$request_id = $data['id'];
$status = $data['status'];

// Update the request status in the database
$query = "UPDATE requests SET status = ? WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('si', $status, $request_id);
$result = $stmt->execute();

echo json_encode(['success' => $result]);
?>
