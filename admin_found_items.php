<?php
session_start(); // Start the session

// Check if current user is an admin
require_once __DIR__ . '/includes/admin_check.php';

// Include common header
require_once __DIR__ . '/includes/header.php';

// Connect to the database
require_once __DIR__ . '/includes/db_connect.php';

// Get all found items and the names of users who reported them, newest first
$sql = "
    SELECT fi.*, u.name AS user_name 
    FROM found_items fi 
    JOIN users u ON fi.user_id = u.id 
    ORDER BY fi.found_created_at DESC
";
$result = $conn->query($sql);

$listHTML = ''; // Will hold the HTML for the item list

// If there are any found items, generate list HTML
if ($result && $result->num_rows > 0) {
    while ($item = $result->fetch_assoc()) {
        $id = htmlspecialchars($item['id']);
        $name = htmlspecialchars($item['found_item_name']);
        $user = htmlspecialchars($item['user_name']);
        $created = htmlspecialchars($item['found_created_at']);

        // Add one list item
        $listHTML .= <<<HTML
<li>
  <strong>$name</strong> (by $user at $created)<br>
  <a href="found_detail.php?id=$id">📝 View</a> |
  <a href="admin_delete_found.php?id=$id" onclick="return confirm('Delete this found item?')">❌ Delete</a>
</li>
<hr>
HTML;
    }

    // Wrap the list items in a <ul>
    $listHTML = "<ul>$listHTML</ul>";
} else {
    // If no found items, show message
    $listHTML = "<p>😌 No found items yet.</p>";
}

// Load HTML template and insert the item list into it
$template = file_get_contents(__DIR__ . '/views/admin_found_items.html');
echo str_replace('{{found_item_list}}', $listHTML, $template);

// Close database connection and show footer
$conn->close();
require_once __DIR__ . '/includes/footer.php';
