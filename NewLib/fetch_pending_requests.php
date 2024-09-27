<?php
include 'connect.php';

// Fetch pending requests
$query = "SELECT r.id, b.title AS bookTitle, b.author, r.status 
          FROM requests r 
          JOIN books b ON r.book_id = b.id 
          WHERE r.status = 'Pending'";
$result = $conn->query($query);

$requests = [];
while ($row = $result->fetch_assoc()) {
    $requests[] = $row;
}

echo json_encode($requests);
?>
