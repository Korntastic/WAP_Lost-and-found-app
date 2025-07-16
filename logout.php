<?php
session_start();
session_unset();    // Clear all SESSION variables
session_destroy();  // Destroying the SESSION
header("Location: index.php"); // Log out and go to the home page
exit();
