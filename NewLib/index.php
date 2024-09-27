<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management Dashboard</title>
    <link rel="stylesheet" href="script.css">
</head>
<body>
    <div class="sidebar">
        <h2>Library Management</h2>
        <a href="#searchBooks" onclick="showSection('searchBooks')">Search Books</a>
        <a href="#approvalRequests" onclick="showSection('approvalRequests')">Approval Requests</a>
        <a href="#pendingRequests" onclick="showSection('pendingRequests')">Pending Requests</a>
        <button class="logout-btn" onclick="logout()">Logout</button>
    </div>

    <div class="main-content">
        <div id="searchBooks" class="content-section">
            <h1>Search Books</h1>
            <div class="search-container">
                <input type="text" id="searchInput" placeholder="Enter book title or author">
                <button onclick="searchBooks()">Search</button>
            </div>
            <table id="resultTable">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Quantity</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Results will be displayed here -->
                </tbody>
            </table>
            <div class="back-btn" onclick="backToMain()">Back to Main</div>
        </div>

        <div id="approvalRequests" class="content-section hidden">
            <h1>Approval Requests</h1>
            <table>
                <thead>
                    <tr>
                        <th>Request ID</th>
                        <th>Book Title</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="approvalRequestsTable">
                    <!-- Approval requests will be displayed here -->
                </tbody>
            </table>
            <div class="back-btn" onclick="backToMain()">Back to Main</div>
        </div>

        <div id="pendingRequests" class="content-section hidden">
            <h1>Pending Requests</h1>
            <table>
                <thead>
                    <tr>
                        <th>Request ID</th>
                        <th>Book Title</th>
                        <th>Author</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="pendingRequestsTable">
                    <!-- Pending requests will be displayed here -->
                </tbody>
            </table>
            <div class="back-btn" onclick="backToMain()">Back to Main</div>
        </div>
    </div>

    <script>
        function showSection(sectionId) {
            const sections = document.querySelectorAll('.content-section');
            sections.forEach(section => {
                section.classList.toggle('hidden', section.id !== sectionId);
            });
        }

        function backToMain() {
            showSection('searchBooks');
        }

        function logout() {
            window.location.href = 'index.html';
        }

        async function searchBooks() {
            const searchInput = document.getElementById('searchInput').value.trim();
            if (!searchInput) {
                alert("Please enter a search query.");
                return;
            }

            try {
                const response = await fetch(`search_books.php?query=${encodeURIComponent(searchInput)}`);
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                const books = await response.json();

                const resultTable = document.getElementById('resultTable').querySelector('tbody');
                resultTable.innerHTML = '';
                if (Array.isArray(books) && books.length > 0) {
                    books.forEach(book => {
                        resultTable.innerHTML += `
                            <tr>
                                <td>${book.title}</td>
                                <td>${book.author}</td>
                                <td>${book.quantity}</td>
                                <td><button onclick="requestBook(${book.id})">Request</button></td>
                            </tr>
                        `;
                    });
                } else {
                    resultTable.innerHTML = '<tr><td colspan="4">No results found.</td></tr>';
                }
            } catch (error) {
                console.error('Error fetching books:', error);
                alert('Failed to fetch books.');
            }
        }

        async function requestBook(bookId) {
            try {
                const response = await fetch('request_book.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ bookId })
                });

                if (response.ok) {
                    alert('Book request has been submitted.');
                } else {
                    alert('Failed to submit book request.');
                }
            } catch (error) {
                console.error('Error requesting book:', error);
                alert('Failed to submit book request.');
            }
        }

        async function fetchApprovalRequests() {
            try {
                const response = await fetch('fetch_approval_requests.php');
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                const requests = await response.json();

                const approvalTable = document.getElementById('approvalRequestsTable');
                approvalTable.innerHTML = '';
                if (Array.isArray(requests)) {
                    requests.forEach(request => {
                        approvalTable.innerHTML += `
                            <tr>
                                <td>${request.id}</td>
                                <td>${request.bookTitle}</td>
                                <td>${request.status}</td>
                                <td>
                                    ${request.status === 'Pending' ? `
                                        <button onclick="approveRequest(${request.id})">Approve</button>
                                        <button onclick="rejectRequest(${request.id})">Reject</button>
                                    ` : ''}
                                </td>
                            </tr>
                        `;
                    });
                }
            } catch (error) {
                console.error('Error fetching approval requests:', error);
                alert('Failed to fetch approval requests.');
            }
        }

        async function fetchPendingRequests() {
            try {
                const response = await fetch('fetch_pending_requests.php');
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                const requests = await response.json();

                const pendingTable = document.getElementById('pendingRequestsTable');
                pendingTable.innerHTML = '';
                if (Array.isArray(requests)) {
                    requests.forEach(request => {
                        pendingTable.innerHTML += `
                            <tr>
                                <td>${request.id}</td>
                                <td>${request.bookTitle}</td>
                                <td>${request.author}</td>
                                <td>${request.status}</td>
                                <td>
                                    ${request.status === 'Pending' ? `
                                       
                                        <button onclick="rejectRequest(${request.id})">Reject</button>
                                    ` : ''}
                                </td>
                            </tr>
                        `;
                    });
                }
            } catch (error) {
                console.error('Error fetching pending requests:', error);
                alert('Failed to fetch pending requests.');
            }
        }

        async function approveRequest(requestId) {
            try {
                const response = await fetch('approve_request.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ requestId, status: 'Approved' })
                });

                if (response.ok) {
                    alert('Request has been approved.');
                    fetchApprovalRequests();
                    fetchPendingRequests();
                } else {
                    alert('Failed to approve request.');
                }
            } catch (error) {
                console.error('Error approving request:', error);
                alert('Failed to approve request.');
            }
        }

        async function rejectRequest(requestId) {
            try {
                const response = await fetch('approve_request.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ requestId, status: 'Rejected' })
                });

                if (response.ok) {
                    alert('Request has been rejected.');
                    fetchApprovalRequests();
                    fetchPendingRequests();
                } else {
                    alert('Failed to reject request.');
                }
            } catch (error) {
                console.error('Error rejecting request:', error);
                alert('Failed to reject request.');
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            fetchApprovalRequests();
            fetchPendingRequests();
        });
    </script>
</body>
</html>
