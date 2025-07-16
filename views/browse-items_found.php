<main class="browse-page">
  <div class="container browse-container">
    <section class="browse-content">
      <h2>🔍 Found Items</h2>

      <?php if ($isLoggedIn): ?>
        <p><a href="found_add.php" class="btn btn-primary">➕ Add Found Item</a></p>
      <?php else: ?>
        <p><a href="login.php" class="btn btn-outline">🔐 Login to post a found item</a></p>
      <?php endif; ?>

      <div class="item-grid">
        <?php while ($item = $result->fetch_assoc()): ?>
          <div class="item-card">
            <div class="item-image">
              <a href="found_detail.php?id=<?= htmlspecialchars($item['id']) ?>">
                <img src="<?= htmlspecialchars($item['found_image_path']) ?>" alt="<?= htmlspecialchars($item['found_item_name']) ?>" />
              </a>
            </div>
            <h3><?= htmlspecialchars($item['found_item_name']) ?></h3>
            <p class="meta">
              <span>📍 <?= htmlspecialchars($item['found_location']) ?></span>
              <span>📅 <?= htmlspecialchars($item['found_created_at']) ?></span>
            </p>
          </div>
        <?php endwhile; ?>
      </div>
    </section>
  </div>
</main>
