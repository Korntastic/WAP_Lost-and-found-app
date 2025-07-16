<?php
session_start(); // Start the session

// Include DB connection and permission checker
require_once __DIR__ . '/includes/db_connect.php';
require_once __DIR__ . '/includes/owner_or_admin_check.php';

// Ensure the request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("❌ Invalid request method.");
}

// Get and validate item ID
$id = $_POST['id'] ?? '';
if (!$id || !is_numeric($id)) {
    die("❌ Invalid item ID.");
}

// Fetch the item's current data (user ID and image path)
$stmt = $conn->prepare("SELECT user_id, found_image_path FROM found_items WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows !== 1) {
    die("❌ Item not found.");
}
$item = $result->fetch_assoc();
$stmt->close();

// Check if current user is owner or admin
checkOwnerOrAdmin($item['user_id']);
$old_image_path = $item['found_image_path'];

// Get and sanitize form input
$found_item_name     = trim($_POST['found_item_name']);
$category            = trim($_POST['category']);
$found_location      = trim($_POST['found_location']);
$found_date          = trim($_POST['found_date']);
$dropoff_location    = trim($_POST['dropoff_location']);
$found_contact_phone = trim($_POST['found_contact_phone']);
$found_description   = trim($_POST['found_description']);
$reporter_name       = trim($_POST['reporter_name']);
$reporter_email      = trim($_POST['reporter_email']);

// Basic validation
if (empty($found_item_name) || empty($found_location)) {
    die("❌ Item name and location are required.");
}

// Handle image upload (if a new one is provided)
if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($ext, $allowed)) {
        die("❌ Invalid image type.");
    }

    $new_name = uniqid() . "_" . basename($_FILES['image']['name']);
    $target_path = "uploads/found/" . $new_name;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
        if (file_exists($old_image_path)) unlink($old_image_path); // Remove old image
        $old_image_path = $target_path; // Set new image path
    } else {
        die("❌ Failed to upload image.");
    }
}

// Prepare and execute update query
$update = $conn->prepare("
    UPDATE found_items
    SET 
        found_item_name = ?, 
        category = ?, 
        found_image_path = ?, 
        found_location = ?, 
        found_date = ?, 
        dropoff_location = ?, 
        found_contact_phone = ?, 
        found_description = ?, 
        reporter_name = ?, 
        reporter_email = ?
    WHERE id = ?
");
if (!$update) {
    die("❌ SQL prepare failed: " . $conn->error);
}

$update->bind_param(
    "ssssssssssi",
    $found_item_name,
    $category,
    $old_image_path,
    $found_location,
    $found_date,
    $dropoff_location,
    $found_contact_phone,
    $found_description,
    $reporter_name,
    $reporter_email,
    $id
);

// Execute and handle result
if ($update->execute()) {
    echo "✅ Update successful. Redirecting...";
    header("refresh:2;url=found_detail.php?id=$id");
    exit();
} else {
    echo "❌ Update failed: " . $update->error;
}

// Clean up
$update->close();
$conn->close();
