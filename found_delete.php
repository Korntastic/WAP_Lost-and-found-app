<?php
require_once __DIR__ . '/includes/auth_check.php';
require_once __DIR__ . '/includes/db_connect.php';
require_once __DIR__ . '/includes/owner_or_admin_check.php';

$item_id = $_GET['id'] ?? '';
if (!$item_id || !is_numeric($item_id)) {
    die("❌ Invalid item ID.");
}


$stmt = $conn->prepare("SELECT user_id, found_image_path FROM found_items WHERE id = ?");
$stmt->bind_param("i", $item_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("❌ Item not found.");
}

$item = $result->fetch_assoc();
$stmt->close();


checkOwnerOrAdmin($item['user_id']);

// delete image
$imageWebPath = $item['found_image_path'];  
$imageFullPath = realpath(__DIR__ . '/' . $imageWebPath);

if ($imageFullPath && file_exists($imageFullPath)) {
    unlink($imageFullPath);
}

// delete databse 
$del_stmt = $conn->prepare("DELETE FROM found_items WHERE id = ?");
$del_stmt->bind_param("i", $item_id);

if ($del_stmt->execute()) {
    echo "✅ Item deleted successfully.";
    header("refresh:2;url=found_list.php");
    exit();
} else {
    echo "❌ Failed to delete item: " . $conn->error;
}

$del_stmt->close();
$conn->close();
?>
