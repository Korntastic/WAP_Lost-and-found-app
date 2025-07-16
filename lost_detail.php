<?php
session_start(); // Start the session

// Include database connection and header
require_once __DIR__ . '/includes/db_connect.php';
require_once __DIR__ . '/includes/header.php';

// Get and validate item ID from URL
$item_id = $_GET['id'] ?? '';
if (!$item_id || !is_numeric($item_id)) {
    die("<p>❌ Invalid item ID.</p>");
}

// Fetch lost item info and reporter's email from DB
$stmt = $conn->prepare("
    SELECT li.*, u.email AS user_email 
    FROM lost_items li
    JOIN users u ON li.user_id = u.id
    WHERE li.id = ?
");
$stmt->bind_param("i", $item_id);
$stmt->execute();
$result = $stmt->get_result();

// Show error if item not found
if ($result->num_rows !== 1) {
    die("<p>❌ Item not found.</p>");
}

$item = $result->fetch_assoc();

// Check if current user is the item owner or an admin
$isOwnerOrAdmin = isset($_SESSION['user_id']) && 
    ($_SESSION['user_id'] == $item['user_id'] || ($_SESSION['role'] ?? '') === 'admin');

// Build contact info string
$contact = '';
if (!empty($item['lost_contact_phone'])) {
    $contact .= "📞 " . htmlspecialchars($item['lost_contact_phone']) . "<br>";
}
$contact .= "📧 " . htmlspecialchars($item['user_email']);

// If owner or admin, show Edit/Delete buttons
$editDeleteLinks = '';
if ($isOwnerOrAdmin) {
    $id = $item['id'];
    $editDeleteLinks = <<<HTML
    <p>
        <a href="lost_edit.php?id=$id" class="btn btn-outline">✏️ Edit</a>
        <a href="lost_delete.php?id=$id" onclick="return confirm('Are you sure you want to delete this item?');" class="btn btn-danger">🗑️ Delete</a>
    </p>
HTML;
}
?>

<main class="detail-page">
  <div class="container">
    <h2>📌 Lost Item Details</h2>

    <div class="item-detail-card">
      <!-- Item image -->
      <img src="<?= htmlspecialchars($item['lost_image_path']) ?>" alt="Lost Item" class="detail-image" />

      <!-- Basic item details -->
      <h3><?= htmlspecialchars($item['lost_item_name']) ?></h3>
      <p><strong>📁 Category:</strong> <?= htmlspecialchars($item['category']) ?></p>
      <p><strong>📍 Location Lost:</strong> <?= htmlspecialchars($item['lost_location']) ?></p>
      <p><strong>📅 Date Lost:</strong> <?= htmlspecialchars($item['lost_date']) ?></p>

      <!-- Optional drop-off location -->
      <?php if (!empty($item['dropoff_location'])): ?>
        <p><strong>🏢 Drop-off Location:</strong> <?= htmlspecialchars($item['dropoff_location']) ?></p>
      <?php endif; ?>

      <!-- Item description -->
      <p><strong>📄 Description:</strong><br><?= nl2br(htmlspecialchars($item['lost_description'])) ?></p>

      <!-- Optional proof of ownership -->
      <?php if (!empty($item['lost_proof'])): ?>
        <p><strong>📑 Proof of Ownership:</strong><br><?= nl2br(htmlspecialchars($item['lost_proof'])) ?></p>
      <?php endif; ?>

      <!-- Contact info section -->
      <h4>📞 Contact Information</h4>
      <p>
        <?php if (!empty($item['lost_contact_phone'])): ?>
          📞 <?= htmlspecialchars($item['lost_contact_phone']) ?><br>
        <?php endif; ?>
        📧 <?= htmlspecialchars($item['reporter_email'] ?: $item['user_email']) ?>
      </p>

      <!-- Optional reporter name -->
      <?php if (!empty($item['reporter_name'])): ?>
        <p><strong>🧍 Reported By:</strong> <?= htmlspecialchars($item['reporter_name']) ?></p>
      <?php endif; ?>

      <!-- Edit/Delete buttons (if authorized) -->
      <?= $editDeleteLinks ?>
    </div>
  </div>
</main>

<?php
// Clean up
$stmt->close();
$conn->close();
include __DIR__ . '/includes/footer.php';
?>
