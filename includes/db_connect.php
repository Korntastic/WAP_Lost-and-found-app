<?php
$conn = new mysqli("localhost:3316", "root", "", "lost_found_db");// hostmane maybe will different so please check your own xampp
if ($conn->connect_error) {
    die("❌ DB connection failed: " . $conn->connect_error);
}

?>
    