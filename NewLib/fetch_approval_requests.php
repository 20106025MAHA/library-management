<?php
include 'connect.php';

// Fetch approval requests
$query = "SELECT r.id, b.title AS bookTitle, r.status 
          FROM requests r 
          JOIN books b ON r.book_id = b.id 
          WHERE r.status IN ('Approved', 'Rejected')";
$result = $conn->query($query);

$requests = [];
while ($row = $result->fetch_assoc()) {
    $requests[] = $row;
}

echo json_encode($requests);
?>
