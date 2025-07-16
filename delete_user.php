<?php
// Check if current user is admin and connect to the database
require_once __DIR__ . '/includes/admin_check.php';
require_once __DIR__ . '/includes/db_connect.php';

// Get user ID from the URL and validate it
$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    die("❌ Invalid user ID.");
}

// Check if the user exists and is not an admin
$stmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("❌ User not found.");
}

$user = $result->fetch_assoc();
if ($user['role'] === 'admin') {
    die("❌ Cannot delete another admin.");
}

// Delete the user from the database
$delete = $conn->prepare("DELETE FROM users WHERE id = ?");
$delete->bind_param("i", $id);

if ($delete->execute()) {
    echo "✅ User deleted successfully.";
    header("refresh:2;url=admin_users.php"); // Redirect after 2 seconds
    exit();
} else {
    echo "❌ Failed to delete user: " . $conn->error;
}

// Close statements and database connection
$stmt->close();
$delete->close();
$conn->close();
?>
