<?php
session_start();

require_once __DIR__ . '/includes/admin_check.php'; // check admin
require_once __DIR__ . '/includes/header.php';      // Include header


$id = $_GET['id'] ?? '';
if (!$id || !is_numeric($id)) {
    die("❌ Invalid ID.");
}


require_once __DIR__ . '/includes/db_connect.php';//connect database

// image path
$stmt = $conn->prepare("SELECT lost_image_path FROM lost_items WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("❌ Lost item not found.");
}

$item = $result->fetch_assoc();
$imagePath = $item['lost_image_path'];

// delete image
if (!empty($imagePath)) {
    $fullPath = __DIR__ . '/' . $imagePath;
    if (file_exists($fullPath)) {
        unlink($fullPath);
    }
}

// delete database record
$delStmt = $conn->prepare("DELETE FROM lost_items WHERE id = ?");
$delStmt->bind_param("i", $id);
$delStmt->execute();

$delStmt->close();
$conn->close();

// go back 
header("Location: admin_lost_items.php");
exit();
