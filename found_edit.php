<?php
session_start(); // Start the session

// Load required files: DB connection, access check, header
require_once __DIR__ . '/includes/db_connect.php';
require_once __DIR__ . '/includes/owner_or_admin_check.php';
require_once __DIR__ . '/includes/header.php';

// Get and validate item ID from URL
$id = $_GET['id'] ?? '';
if (!$id || !is_numeric($id)) {
    die("❌ Invalid item ID.");
}

// Fetch item data from the database
$stmt = $conn->prepare("SELECT * FROM found_items WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows !== 1) {
    die("❌ Item not found.");
}
$item = $result->fetch_assoc();
$stmt->close();

// Check if the current user is the owner or an admin
checkOwnerOrAdmin($item['user_id']);
?>

<main class="container">
  <h2>✏️ Edit Found Item</h2>

  <!-- Edit form with pre-filled item data -->
  <form action="found_update.php" method="POST" enctype="multipart/form-data" class="report-form">
    <input type="hidden" name="id" value="<?= htmlspecialchars($item['id']) ?>" />

    <div class="form-group">
      <label for="found_item_name">Item Title</label>
      <input type="text" id="found_item_name" name="found_item_name" value="<?= htmlspecialchars($item['found_item_name']) ?>" required />
    </div>

    <div class="form-group">
      <label for="category">Category</label>
      <select id="category" name="category" required>
        <option value="">Select a category</option>
        <?php
        // Render category options with pre-selected value
        $categories = ['Electronics', 'Personal Belongings', 'Keys', 'Bags', 'Pets'];
        foreach ($categories as $cat) {
            $selected = ($item['category'] === $cat) ? 'selected' : '';
            echo "<option value=\"$cat\" $selected>$cat</option>";
        }
        ?>
      </select>
    </div>

    <div class="form-group">
      <label for="found_location">Location Found</label>
      <input type="text" id="found_location" name="found_location" value="<?= htmlspecialchars($item['found_location']) ?>" required />
    </div>

    <div class="form-group">
      <label for="found_date">Date Found</label>
      <input type="date" id="found_date" name="found_date" value="<?= htmlspecialchars($item['found_date']) ?>" required />
    </div>

    <div class="form-group">
      <label for="dropoff_location">Drop-off Location</label>
      <input type="text" id="dropoff_location" name="dropoff_location" value="<?= htmlspecialchars($item['dropoff_location']) ?>" />
    </div>

    <div class="form-group">
      <label for="found_contact_phone">Contact Phone</label>
      <input type="text" id="found_contact_phone" name="found_contact_phone" value="<?= htmlspecialchars($item['found_contact_phone']) ?>" />
    </div>

    <div class="form-group">
      <label for="found_description">Description</label>
      <textarea id="found_description" name="found_description" rows="4" required><?= htmlspecialchars($item['found_description']) ?></textarea>
    </div>

    <div class="form-group">
      <label for="reporter_name">Your Name (optional)</label>
      <input type="text" id="reporter_name" name="reporter_name" value="<?= htmlspecialchars($item['reporter_name']) ?>" />
    </div>

    <div class="form-group">
      <label for="reporter_email">Your Email (optional)</label>
      <input type="email" id="reporter_email" name="reporter_email" value="<?= htmlspecialchars($item['reporter_email']) ?>" />
    </div>

    <div class="form-group">
      <label>Current Image:</label><br>
      <img src="<?= htmlspecialchars($item['found_image_path']) ?>" alt="Current Image" width="150" />
    </div>

    <div class="form-group">
      <label for="image">Replace Image (optional):</label>
      <input type="file" id="image" name="image" accept="image/*" />
    </div>

    <div class="form-actions">
      <button type="submit" class="btn btn-primary">Update Item</button>
      <a href="found_list.php" class="btn btn-secondary">Cancel</a>
    </div>
  </form>
</main>

<?php include __DIR__ . '/includes/footer.php'; // Load footer ?>
