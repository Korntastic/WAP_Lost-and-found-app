<?php
session_start();

require_once __DIR__ . '/includes/admin_check.php'; // check admin
require_once __DIR__ . '/includes/header.php';      // Include header

require_once __DIR__ . '/includes/db_connect.php';

// Query lost_items
$sql = "
    SELECT li.*, u.name AS user_name 
    FROM lost_items li
    JOIN users u ON li.user_id = u.id 
    ORDER BY li.lost_created_at DESC
";
$result = $conn->query($sql);

// build html 
$listHTML = '';
if ($result && $result->num_rows > 0) {
    while ($item = $result->fetch_assoc()) {
        $id = htmlspecialchars($item['id']);
        $name = htmlspecialchars($item['lost_item_name']);
        $user = htmlspecialchars($item['user_name']);
        $created = htmlspecialchars($item['lost_created_at']);

        $listHTML .= <<<HTML
<li>
  <strong>$name</strong> (by $user at $created)<br>
  <a href="lost_detail.php?id=$id">📝 View</a> |
  <a href="admin_delete_lost.php?id=$id" onclick="return confirm('Delete this lost request?')">❌ Delete</a>
</li>
<hr>
HTML;
    }

    $listHTML = "<ul>$listHTML</ul>";
} else {
    $listHTML = "<p>😌 No lost requests yet.</p>";
}

$template = file_get_contents(__DIR__ . '/views/admin_lost_items.html');
echo str_replace('{{lost_item_list}}', $listHTML, $template);

$conn->close();
require_once __DIR__ . '/includes/footer.php'; // Include footer