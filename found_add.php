<?php
session_start(); // Start the session

// Check if user is logged in
require_once __DIR__ . '/includes/auth_check.php';  
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $_SESSION['user_name'] ?? '';

// Load the common header
include __DIR__ . '/includes/header.php';
?>

<main>
  <?php 
  // Load the Report Found Item form
  include __DIR__ . '/views/report-found.html'; 
  ?>
</main>

<?php
// Load the common footer
include __DIR__ . '/includes/footer.php';
