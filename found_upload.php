<?php
// Check if user is logged in and connect to the database
require_once __DIR__ . '/includes/auth_check.php';
require_once __DIR__ . '/includes/db_connect.php';

// Get user info from session
$user_id = $_SESSION['user_id'];
$email = $_SESSION['user_email'] ?? '';

// Get and sanitize POST form data
$found_item_name    = trim($_POST['found_item_name'] ?? '');
$category           = trim($_POST['category'] ?? '');
$found_location     = trim($_POST['found_location'] ?? '');
$found_date         = trim($_POST['found_date'] ?? '');
$dropoff_location   = trim($_POST['dropoff_location'] ?? '');
$found_contact_phone = trim($_POST['phone'] ?? '');
$found_description  = trim($_POST['description'] ?? '');
$reporter_name      = trim($_POST['reporter_name'] ?? '');
$reporter_email     = trim($_POST['reporter_email'] ?? '');

// Basic validation for required fields
if (empty($found_item_name) || empty($found_location)) {
    die("❌ Item name and found location are required.");
}

// Handle image upload
$image = $_FILES['image'] ?? null;
if (!$image || $image['error'] !== UPLOAD_ERR_OK) {
    die("❌ Image upload failed.");
}

// Validate image type
$allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
$image_ext = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
if (!in_array($image_ext, $allowed_types)) {
    die("❌ Invalid image format.");
}

// Prepare upload directory
$upload_dir = 'uploads/found/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Save uploaded image with unique name
$image_name = uniqid() . "_" . basename($image['name']);
$found_image_path = $upload_dir . $image_name;

if (!move_uploaded_file($image['tmp_name'], $found_image_path)) {
    die("❌ Failed to save uploaded image.");
}

// Insert item data into the database
$stmt = $conn->prepare("
    INSERT INTO found_items (
        user_id,
        found_item_name,
        category,
        found_image_path,
        found_location,
        found_date,
        dropoff_location,
        found_contact_phone,
        found_description,
        reporter_name,
        reporter_email
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

if (!$stmt) {
    die("❌ SQL Error: " . $conn->error);
}

$stmt->bind_param(
    "issssssssss",
    $user_id,
    $found_item_name,
    $category,
    $found_image_path,
    $found_location,
    $found_date,
    $dropoff_location,
    $found_contact_phone,
    $found_description,
    $reporter_name,
    $reporter_email
);

// Execute and redirect on success
if ($stmt->execute()) {
    echo "✅ Found item uploaded successfully!";
    header("refresh:2;url=found_list.php");
    exit();
} else {
    echo "❌ Upload failed: " . $stmt->error;
}

// Close statement and connection
$stmt->close();
$conn->close();
?>
