<?php
require_once __DIR__ . '/includes/auth_check.php';
require_once __DIR__ . '/includes/db_connect.php';
require_once __DIR__ . '/includes/owner_or_admin_check.php';

$item_id = $_GET['id'] ?? '';
if (!$item_id || !is_numeric($item_id)) {
    die("❌ Invalid item ID.");
}


$stmt = $conn->prepare("SELECT user_id, lost_image_path FROM lost_items WHERE id = ?");
$stmt->bind_param("i", $item_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("❌ Item not found.");
}

$item = $result->fetch_assoc();
$stmt->close();

// Authentication (author or administrator)
checkOwnerOrAdmin($item['user_id']);

// delete image
if (!empty($item['lost_image_path'])) {
    $lost_imageFullPath = __DIR__ . '/' . $item['lost_image_path'];
    if (file_exists($lost_imageFullPath)) {
        unlink($lost_imageFullPath);  // delete image file
    }
}

// delete record
$del_stmt = $conn->prepare("DELETE FROM lost_items WHERE id = ?");
$del_stmt->bind_param("i", $item_id);

if ($del_stmt->execute()) {
    echo "✅ Item deleted successfully.";
    header("refresh:2;url=lost_list.php");
    exit();
} else {
    echo "❌ Failed to delete item: " . $conn->error;
}

$del_stmt->close();
$conn->close();
?>
