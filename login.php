<?php
session_start();
require_once "includes/db_connect.php";


$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    die("❌ Email and password are required.");
}

// Fetch user
$stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("❌ No user found with that email.");
}

$user = $result->fetch_assoc();

if (!password_verify($password, $user['password'])) {
    die("❌ Incorrect password.");
}

// Set session
$_SESSION['user_id'] = $user['id'];
$_SESSION['user_name'] = $user['name'];
$_SESSION['role'] = $user['role'];
$_SESSION['user_email'] = $email;

// Redirect
header("Location: index.php");
exit();
