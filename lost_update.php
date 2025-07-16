<?php
session_start(); // Start session to access login info

// Include DB connection and permission checker
require_once __DIR__ . '/includes/db_connect.php';
require_once __DIR__ . '/includes/owner_or_admin_check.php';

// Ensure request is POST method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("❌ Invalid request method.");
}

// Get item ID and validate it
$id = $_POST['id'] ?? '';
if (!$id || !is_numeric($id)) {
    die("❌ Invalid item ID.");
}

// Fetch the item from DB to verify existence and get image path
$stmt = $conn->prepare("SELECT user_id, lost_image_path FROM lost_items WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows !== 1) {
    die("❌ Item not found.");
}
$item = $result->fetch_assoc();
$stmt->close();

// Check if current user is the owner or admin
checkOwnerOrAdmin($item['user_id']);
$old_image_path = $item['lost_image_path'];

// Get form input values and sanitize
$lost_item_name   = trim($_POST['lost_item_name'] ?? '');
$category         = trim($_POST['category'] ?? '');
$lost_location    = trim($_POST['lost_location'] ?? '');
$lost_date        = trim($_POST['lost_date'] ?? '');
$dropoff_location = trim($_POST['dropoff_location'] ?? '');
$phone            = trim($_POST['lost_contact_phone'] ?? '');
$lost_description = trim($_POST['lost_description'] ?? '');
$reporter_name    = trim($_POST['reporter_name'] ?? '');
$reporter_email   = trim($_POST['reporter_email'] ?? '');

// Check required fields
if (empty($lost_item_name) || empty($lost_location)) {
    die("❌ Item name and location are required.");
}

// Handle image upload if a new one is provided
if (!empty($_FILES['lost_image']['name']) && $_FILES['lost_image']['error'] === UPLOAD_ERR_OK) {
    $ext = strtolower(pathinfo($_FILES['lost_image']['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];

    if (!in_array($ext, $allowed)) {
        die("❌ Invalid image type.");
    }

    $new_name = uniqid() . "_" . basename($_FILES['lost_image']['name']);
    $target_path = "uploads/lost/" . $new_name;

    // Move uploaded file and remove old image
    if (move_uploaded_file($_FILES['lost_image']['tmp_name'], $target_path)) {
        if (file_exists($old_image_path)) unlink($old_image_path);
        $old_image_path = $target_path;
    } else {
        die("❌ Failed to upload image.");
    }
}

// Prepare and execute update statement
$update = $conn->prepare("
    UPDATE lost_items
    SET lost_item_name = ?, 
        category = ?, 
        lost_image_path = ?, 
        lost_location = ?, 
        lost_date = ?, 
        dropoff_location = ?, 
        lost_contact_phone = ?, 
        lost_description = ?, 
        reporter_name = ?, 
        reporter_email = ?
    WHERE id = ?
");
if (!$update) {
    die("❌ SQL prepare failed: " . $conn->error);
}

$update->bind_param(
    "ssssssssssi",
    $lost_item_name,
    $category,
    $old_image_path,
    $lost_location,
    $lost_date,
    $dropoff_location,
    $phone,
    $lost_description,
    $reporter_name,
    $reporter_email,
    $id
);

// Execute update and redirect or show error
if ($update->execute()) {
    echo "✅ Update successful. Redirecting...";
    header("refresh:2;url=lost_detail.php?id=$id");
    exit();
} else {
    echo "❌ Update failed: " . $update->error;
}

$update->close();
$conn->close();
