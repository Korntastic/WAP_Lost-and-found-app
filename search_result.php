<?php 
// Start session and include required files
session_start();
include __DIR__ . '/includes/db_connect.php';
include __DIR__ . '/includes/header.php';

$isLoggedIn = isset($_SESSION['user_id']);

// ------------------------------
// Handle Search for Found Items
// ------------------------------
if (isset($_GET['search'])) {
    // Escape user input to prevent SQL injection
    $filtervalues = mysqli_real_escape_string($conn, $_GET['search']);
    // Search in item name and category
    $sql_found = "SELECT * FROM found_items WHERE CONCAT(found_item_name, category) LIKE '%$filtervalues%'";
} else {
    // Default: show all found items sorted by creation date
    $sql_found = "SELECT * FROM found_items ORDER BY found_created_at DESC";
}
$result_found = $conn->query($sql_found); // Execute found query

// -----------------------------
// Handle Search for Lost Items
// -----------------------------
if (isset($_GET['search'])) {
    $filtervalues = mysqli_real_escape_string($conn, $_GET['search']);
    // Search in name, category, and location
    $sql_lost = "SELECT * FROM lost_items WHERE CONCAT(lost_item_name, category, lost_location) LIKE '%$filtervalues%'";
} else {
    // Default: show all lost items sorted by creation date
    $sql_lost = "SELECT * FROM lost_items ORDER BY lost_created_at DESC";
}
$result_lost = $conn->query($sql_lost); // Execute lost query
?>

<!-- Display Found Items -->
<h2>🧾 Found Item</h2>
<div class="item-grid">
  <?php if ($result_found && $result_found->num_rows > 0): ?>
    <?php while ($item_found = $result_found->fetch_assoc()): ?>
      <div class="item-card">
        <div class="item-image">
          <a href="found_detail.php?id=<?= htmlspecialchars($item_found['id']) ?>">
            <img src="<?= htmlspecialchars($item_found['found_image_path']) ?>" alt="<?= htmlspecialchars($item_found['found_item_name']) ?>" />
          </a>
        </div>
        <h3><?= htmlspecialchars($item_found['found_item_name']) ?></h3>
        <p class="meta">
          <span>📍 <?= htmlspecialchars($item_found['found_location']) ?></span>
          <span>📅 <?= htmlspecialchars($item_found['found_created_at']) ?></span>
        </p>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p>No matching items found.</p>
  <?php endif; ?>
</div>

<br>

<!-- Display Lost Items -->
<h2>📌 Lost Item</h2>
<div class="item-grid">
  <?php if ($result_lost && $result_lost->num_rows > 0): ?>
    <?php while ($item_lost = $result_lost->fetch_assoc()): ?>
      <div class="item-card">
        <div class="item-image">
          <a href="lost_detail.php?id=<?= htmlspecialchars($item_lost['id']) ?>">
            <img src="<?= htmlspecialchars($item_lost['lost_image_path']) ?>" alt="<?= htmlspecialchars($item_lost['lost_item_name']) ?>" />
          </a>
        </div>
        <h3><?= htmlspecialchars($item_lost['lost_item_name']) ?></h3>
        <p class="meta">
          <span>📍 <?= htmlspecialchars($item_lost['lost_location']) ?></span>
          <span>📅 <?= htmlspecialchars($item_lost['lost_created_at']) ?></span>
        </p>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p>No matching items found.</p>
  <?php endif; ?>
</div>

<?php 
// Include footer
include __DIR__ . '/includes/footer.php';
?>
