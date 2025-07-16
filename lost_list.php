<?php
session_start(); // Start session (for potential future use like login checks)

// Include database connection and header template
require_once __DIR__ . '/includes/db_connect.php';
require_once __DIR__ . '/includes/header.php';

// Fetch all lost items from the database, ordered by creation time (latest first)
$lost_sql = "SELECT id, lost_item_name, lost_image_path, lost_created_at, lost_location FROM lost_items ORDER BY lost_created_at DESC";
$lost_result = $conn->query($lost_sql);

// Load the HTML template to display the lost items list
include __DIR__ . '/views/browse-items_lost.php';

// Include footer
include __DIR__ . '/includes/footer.php';
