<main class="browse-page">
  <div class="container browse-container">
    <h2>🧳 Recently Lost Items</h2>
    <div class="item-grid">
      <?php while ($lost = $lost_result->fetch_assoc()): ?>
        <div class="item-card">
          <div class="item-image">
            <a href="lost_detail.php?id=<?= $lost['id'] ?>">
              <img src="<?= htmlspecialchars($lost['lost_image_path']) ?>" alt="<?= htmlspecialchars($lost['lost_item_name']) ?>" />
            </a>
          </div>
          <h3><?= htmlspecialchars($lost['lost_item_name']) ?></h3>
          <p class="meta">
            <span>📍 <?= htmlspecialchars($lost['lost_location']) ?></span>
            <span>📅 <?= htmlspecialchars($lost['lost_created_at']) ?></span>
          </p>
        </div>
      <?php endwhile; ?>
    </div>
  </div>
</main>
