<?php
require_once __DIR__ . '/includes/admin_check.php'; // Check if user is admin
require_once __DIR__ . '/includes/header.php';      // Include header


$template = file_get_contents(__DIR__ . '/views/admin_panel.html');



echo $template;

require_once __DIR__ . '/includes/footer.php'; // Include footer
