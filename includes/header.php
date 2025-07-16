<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isLoggedIn = isset($_SESSION['user_id']);
$userName = $_SESSION['user_name'] ?? '';
$userRole = $_SESSION['role'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Lost & Found Hub</title>
  <link rel="stylesheet" href="views/css/styles.css" />
  <link rel="stylesheet" href="views/css/login.css" />
</head>
<body>

<header>
  <div class="container header-container">
    <a href="index.php" class="logo">
      <img src="views/img/logo.png" alt="Lost & Found Hub Logo" />
      <span>Lost & Found Hub</span>
    </a>

    <nav class="main-nav">
      <ul>
        <li class="dropdown">
          <a href="#" class="dropbtn">Browse Items ▾</a>
          <div class="dropdown-content">
            <a href="found_list.php">📦 Found Items</a>
            <a href="lost_list.php">🔍 Lost Items</a>
          </div>
        </li>
        <li><a href="lost_add.php">Report Lost Item</a></li>
        <li><a href="found_add.php">Report Found Item</a></li>
      </ul>
    </nav>

    <div class="header-actions">

      <!-- Search Box -->
      <form action="search_result.php" method="GET" class="search-box">
        <input type="text" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" class="form-control" placeholder="Search for items…" required />
        <button type="submit" class="btn btn-primary">Search</button>
      </form>

      <!-- Admin Button -->
      <?php if ($isLoggedIn && $userRole === 'admin'): ?>
        <a href="admin_panel.php" class="btn btn-admin">🛠️ Admin Panel</a>
      <?php endif; ?>

      <!-- Auth Buttons -->
      <?php if ($isLoggedIn): ?>
        <span class="welcome-msg">👋 Welcome, <?= htmlspecialchars($userName) ?></span>
        <a href="logout.php" class="btn btn-outline">Logout</a>
      <?php else: ?>
        <button id="openLogin" class="btn btn-outline">Login/Register</button>
      <?php endif; ?>

    </div>
  </div>
</header>
