<?php
include 'connect.php';
$books = $conn->query("SELECT * FROM books");
$pending_requests = $conn->query("SELECT r.id, b.title, r.user_name, r.status 
                                  FROM requests r 
                                  JOIN books b ON r.book_id = b.id 
                                  WHERE r.status = 'Pending'");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request_id = $_POST['request_id'];
    $action = $_POST['action'];

    if ($action === 'approve') {
        $conn->query("UPDATE requests SET status = 'Approved' WHERE id = $request_id");
    } else if ($action === 'reject') {
        $conn->query("UPDATE requests SET status = 'Rejected' WHERE id = $request_id");
    }

    header("Location: admin_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Admin Dashboard</h1>
        <a href="add_book.php" class="button">Add Book</a>
        <h2>Pending Book Requests</h2>
        <table>
            <thead>
                <tr>
                    <th>Request ID</th>
                    <th>Book Title</th>
                    <th>User Name</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($request = $pending_requests->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $request['id']; ?></td>
                        <td><?php echo $request['title']; ?></td>
                        <td><?php echo $request['user_name']; ?></td>
                        <td><?php echo $request['status']; ?></td>
                        <td>
                            
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                                <button type="submit" name="action" value="approve" class="approve-btn">Approve</button>
                                <button type="submit" name="action" value="reject" class="reject-btn">Reject</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <div>
    <button class="logout-btn" onclick="logout()">Logout</button>
    </div>
    <script>
          function logout() {
            window.location.href = 'index.html';
        }
    </script>
</body>
</html>

