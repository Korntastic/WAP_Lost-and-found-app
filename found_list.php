<?php
session_start(); // Start the session

// Include database connection and page header
require_once __DIR__ . '/includes/db_connect.php';
require_once __DIR__ . '/includes/header.php';

// Fetch all found items from the database, ordered by latest first
$sql = "SELECT id, found_item_name, found_image_path, found_created_at, found_location FROM found_items ORDER BY found_created_at DESC";
$result = $conn->query($sql);

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['user_id']);

// Load the view to display found items
include __DIR__ . '/views/browse-items_found.php';

// Include page footer
include __DIR__ . '/includes/footer.php';
?>
