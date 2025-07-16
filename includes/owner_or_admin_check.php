<?php
function checkOwnerOrAdmin($item_user_id) {
    if (!isset($_SESSION['user_id'])) {
        die("❌ Not logged in.");
    }
    if ($_SESSION['user_id'] != $item_user_id && ($_SESSION['role'] ?? '') !== 'admin') {
        die("🚫 No permission to perform this action.");
    }
}
?>
