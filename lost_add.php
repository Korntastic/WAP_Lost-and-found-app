<?php
session_start();

// check login
require_once __DIR__ . '/includes/auth_check.php';
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $_SESSION['user_name'] ?? '';

//header
include __DIR__ . '/includes/header.php';  
?>

<main>
    <?php include __DIR__ . '/views/report-lost.html'; ?>  
</main> 

<?php
include __DIR__ . '/includes/footer.php';   
?>
