<?php
session_start(); // Start the session

// Connect to database and include header
require_once __DIR__ . '/includes/db_connect.php'; // Include database connection
require_once __DIR__ . '/includes/header.php';     // Include page header

// Get item ID from URL and validate it
$item_id = $_GET['id'] ?? '';
if (!$item_id || !is_numeric($item_id)) {
    die("<p>❌ Invalid item ID.</p>");
}

// Fetch item details and reporter's email from database
$stmt = $conn->prepare("
    SELECT fi.*, u.email AS user_email 
    FROM found_items fi
    JOIN users u ON fi.user_id = u.id
    WHERE fi.id = ?
");
$stmt->bind_param("i", $item_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("<p>❌ Item not found.</p>"); // If ID is missing or invalid
}

$item = $result->fetch_assoc();

// Check if current user is the owner or an admin
$isOwnerOrAdmin = isset($_SESSION['user_id']) && 
    ($_SESSION['user_id'] == $item['user_id'] || ($_SESSION['role'] ?? '') === 'admin');

// Build contact info string
$contact = '';
if (!empty($item['found_contact_phone'])) {
    $contact .= "📞 " . htmlspecialchars($item['found_contact_phone']) . "<br>";
}
$contact .= "📧 " . htmlspecialchars($item['user_email']);

// Show Edit/Delete buttons if user is the owner or admin
$editDeleteLinks = '';
if ($isOwnerOrAdmin) {
    $id = $item['id'];
    $editDeleteLinks = <<<HTML
    <p>
        <a href="found_edit.php?id=$id" class="btn btn-outline">✏️ Edit</a>
        <a href="found_delete.php?id=$id" onclick="return confirm('Are you sure you want to delete this item?');" class="btn btn-danger">🗑️ Delete</a>
    </p>
HTML;
}
?>

<main class="detail-page">
  <div class="container">
    <h2>🧾 Found Item Details</h2>

    <div class="item-detail-card">
      <img src="<?= htmlspecialchars($item['found_image_path']) ?>" alt="Found Item" class="detail-image" />

      <h3><?= htmlspecialchars($item['found_item_name']) ?></h3>

      <p><strong>📂 Category:</strong> <?= htmlspecialchars($item['category']) ?></p>
      <p><strong>📍 Location Found:</strong> <?= htmlspecialchars($item['found_location']) ?></p>
      <p><strong>📅 Date Found:</strong> <?= htmlspecialchars($item['found_date']) ?></p>
      <p><strong>📦 Drop-off Location:</strong> <?= htmlspecialchars($item['dropoff_location']) ?: 'Not provided' ?></p>

      <p><strong>📝 Description:</strong><br><?= nl2br(htmlspecialchars($item['found_description'])) ?></p>

      <h4>📞 Contact Information</h4>
      <p>
        <?= $contact ?>
        <?php if (!empty($item['reporter_name'])): ?>
          <br>🧑 <?= htmlspecialchars($item['reporter_name']) ?>
        <?php endif; ?>
        <?php if (!empty($item['reporter_email'])): ?>
          <br>📧 <?= htmlspecialchars($item['reporter_email']) ?>
        <?php endif; ?>
      </p>

      <?= $editDeleteLinks ?>
    </div>
  </div>
</main>

<?php
// Close database resources and include footer
$stmt->close();
$conn->close();
include __DIR__ . '/includes/footer.php';
?>
