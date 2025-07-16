<?php
session_start(); // Start session for user access

// Include DB connection, permission checker, and page header
require_once __DIR__ . '/includes/db_connect.php';
require_once __DIR__ . '/includes/owner_or_admin_check.php';
require_once __DIR__ . '/includes/header.php';

// Get and validate lost item ID from URL
$id = $_GET['id'] ?? '';
if (!$id || !is_numeric($id)) {
    die("❌ Invalid item ID.");
}

// Fetch lost item from the database
$stmt = $conn->prepare("SELECT * FROM lost_items WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows !== 1) {
    die("❌ Item not found.");
}
$item = $result->fetch_assoc();
$stmt->close();

// Check if current user is the owner or an admin
checkOwnerOrAdmin($item['user_id']);
?>

<main class="container">
  <h2>✏️ Edit Lost Item</h2>

  <!-- Lost item edit form -->
  <form action="lost_update.php" method="POST" enctype="multipart/form-data" class="report-form">
    <!-- Hidden input to pass the item ID -->
    <input type="hidden" name="id" value="<?= htmlspecialchars($item['id']) ?>" />

    <!-- Item name -->
    <div class="form-group">
      <label for="lost_item_name">Item Title</label>
      <input type="text" id="lost_item_name" name="lost_item_name" value="<?= htmlspecialchars($item['lost_item_name']) ?>" required />
    </div>

    <!-- Category dropdown -->
    <div class="form-group">
      <label for="category">Category</label>
      <select id="category" name="category" required>
        <option value="">Select a category</option>
        <?php
        $categories = ['Electronics', 'Personal Belongings', 'Keys', 'Bags', 'Pets'];
        foreach ($categories as $cat) {
            $selected = ($item['category'] === $cat) ? 'selected' : '';
            echo "<option value=\"$cat\" $selected>$cat</option>";
        }
        ?>
      </select>
    </div>

    <!-- Location where item was lost -->
    <div class="form-group">
      <label for="lost_location">Location Lost</label>
      <input type="text" id="lost_location" name="lost_location" value="<?= htmlspecialchars($item['lost_location']) ?>" required />
    </div>

    <!-- Date item was lost -->
    <div class="form-group">
      <label for="lost_date">Date Lost</label>
      <input type="date" id="lost_date" name="lost_date" value="<?= htmlspecialchars($item['lost_date']) ?>" required />
    </div>

    <!-- Optional drop-off location -->
    <div class="form-group">
      <label for="dropoff_location">Drop-off Location</label>
      <input type="text" id="dropoff_location" name="dropoff_location" value="<?= htmlspecialchars($item['dropoff_location']) ?>" />
    </div>

    <!-- Contact phone -->
    <div class="form-group">
      <label for="lost_contact_phone">Contact Phone</label>
      <input type="text" id="lost_contact_phone" name="lost_contact_phone" value="<?= htmlspecialchars($item['lost_contact_phone']) ?>" />
    </div>

    <!-- Description -->
    <div class="form-group">
      <label for="lost_description">Description</label>
      <textarea id="lost_description" name="lost_description" rows="4" required><?= htmlspecialchars($item['lost_description']) ?></textarea>
    </div>

    <!-- Optional reporter name -->
    <div class="form-group">
      <label for="reporter_name">Your Name (optional)</label>
      <input type="text" id="reporter_name" name="reporter_name" value="<?= htmlspecialchars($item['reporter_name']) ?>" />
    </div>

    <!-- Optional reporter email -->
    <div class="form-group">
      <label for="reporter_email">Your Email (optional)</label>
      <input type="email" id="reporter_email" name="reporter_email" value="<?= htmlspecialchars($item['reporter_email']) ?>" />
    </div>

    <!-- Current image preview -->
    <div class="form-group">
      <label>Current Image:</label><br>
      <img src="<?= htmlspecialchars($item['lost_image_path']) ?>" alt="Current Image" width="150" />
    </div>

    <!-- New image upload -->
    <div class="form-group">
      <label for="lost_image">Replace Image (optional):</label>
      <input type="file" id="lost_image" name="lost_image" accept="image/*" />
    </div>

    <!-- Submit and cancel buttons -->
    <div class="form-actions">
      <button type="submit" class="btn btn-primary">Update Item</button>
      <a href="lost_list.php" class="btn btn-secondary">Cancel</a>
    </div>
  </form>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
