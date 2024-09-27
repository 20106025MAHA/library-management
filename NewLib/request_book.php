<?php
include 'connect.php';

$data = json_decode(file_get_contents("php://input"), true);
$book_id = $data['bookId'];
$user_name = 'John Doe'; // Replace this with actual user data

$stmt = $conn->prepare("INSERT INTO requests (book_id, user_name) VALUES (?, ?)");
$stmt->bind_param("is", $book_id, $user_name);

if ($stmt->execute()) {
    echo json_encode(["message" => "Request submitted successfully."]);
} else {
    echo json_encode(["message" => "Failed to submit request."]);
}

$stmt->close();
$conn->close();
?>
