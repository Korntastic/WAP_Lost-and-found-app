<?php
// Require login check and DB connection
require_once __DIR__ . '/includes/auth_check.php';
require_once __DIR__ . '/includes/db_connect.php';

// Get current logged-in user info
$user_id = $_SESSION['user_id'];
$email = $_SESSION['user_email'] ?? '';

// Get form data and sanitize
$lost_item_name      = trim($_POST['lost_item_name'] ?? '');
$category            = trim($_POST['lost_category'] ?? '');
$lost_location       = trim($_POST['lost_location'] ?? '');
$lost_date           = trim($_POST['date_item_lost'] ?? '');
$dropoff_location    = trim($_POST['dropoff_location'] ?? '');
$lost_contact_phone  = trim($_POST['phone'] ?? '');
$lost_description    = trim($_POST['lost_description'] ?? '');
$reporter_name       = trim($_POST['reporter_name'] ?? '');
$reporter_email      = trim($_POST['reporter_email'] ?? '');
$lost_proof          = trim($_POST['lost_proof'] ?? ''); 

// Basic required field check
if (empty($lost_item_name) || empty($lost_location)) {
    die("❌ Item name and location are required.");
}

// Handle image upload
$lost_image = $_FILES['lost_image'] ?? null;
if (!$lost_image || $lost_image['error'] !== UPLOAD_ERR_OK) {
    die("❌ Image upload failed.");
}

// Validate image type
$allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
$ext = strtolower(pathinfo($lost_image['name'], PATHINFO_EXTENSION));
if (!in_array($ext, $allowed_types)) {
    die("❌ Invalid image format.");
}

// Create upload directory if it doesn't exist
$upload_dir = 'uploads/lost/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Set unique filename and full path
$lost_image_name = uniqid() . "_" . basename($lost_image['name']);
$lost_image_path = $upload_dir . $lost_image_name;

// Move uploaded image to target path
if (!move_uploaded_file($lost_image['tmp_name'], $lost_image_path)) {
    die("❌ Failed to save uploaded image.");
}

// Insert item data into database
$stmt = $conn->prepare("
    INSERT INTO lost_items (
        user_id,
        lost_item_name,
        category,
        lost_image_path,
        lost_location,
        lost_date,
        dropoff_location,
        lost_contact_phone,
        lost_description,
        reporter_name,
        reporter_email
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

if (!$stmt) {
    die("❌ SQL Error: " . $conn->error);
}

// Bind values and execute
$stmt->bind_param(
    "issssssssss",
    $user_id,
    $lost_item_name,
    $category,
    $lost_image_path,
    $lost_location,
    $lost_date,
    $dropoff_location,
    $lost_contact_phone,
    $lost_description,
    $reporter_name,
    $reporter_email
);

// On success, redirect to list page
if ($stmt->execute()) {
    echo "✅ Lost item uploaded successfully!";
    header("refresh:2;url=lost_list.php");
    exit();
} else {
    echo "❌ Upload failed: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
