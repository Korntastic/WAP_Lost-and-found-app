<?php
session_start(); // Start the session

// Check if the user is an admin
require_once __DIR__ . '/includes/admin_check.php';

// Include common header and database connection
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/db_connect.php';

// Get all users from the database, ordered by ID
$result = $conn->query("SELECT id, name, email, role FROM users ORDER BY id ASC");
if (!$result) {
    die("❌ Failed to fetch users: " . $conn->error);
}

// Build HTML table rows for each user
$userRows = '';
while ($user = $result->fetch_assoc()) {
    $id = $user['id'];
    $name = htmlspecialchars($user['name']);
    $email = htmlspecialchars($user['email']);
    $role = htmlspecialchars($user['role']);

    // If not admin, allow deletion; otherwise lock the action
    if ($role !== 'admin') {
        $action = "<a href=\"delete_user.php?id=$id\" onclick=\"return confirm('Are you sure to delete this user?')\">Delete</a>";
    } else {
        $action = "🔒";
    }

    // Add row to the user table
    $userRows .= "<tr>
        <td>$id</td>
        <td>$name</td>
        <td>$email</td>
        <td>$role</td>
        <td>$action</td>
    </tr>\n";
}

// Load HTML template and insert the user table rows
$template = file_get_contents(__DIR__ . '/views/admin_users.html');

$replacements = [
    '{{user_table_rows}}' => $userRows
];

echo strtr($template, $replacements);

// Close the database connection and show the footer
$conn->close();
require_once __DIR__ . '/includes/footer.php'; // Include footer
