<?php
session_start();
require_once "includes/db_connect.php";

// Check required POST data
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

if (empty($name) || empty($email) || empty($password)) {
    die("❌ All fields are required.");
}

if ($password !== $confirm_password) {
    die("❌ Passwords do not match.");
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("❌ Invalid email format.");
}

// Check if email already exists
$check = $conn->prepare("SELECT id FROM users WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    die("❌ This email is already registered.");
}

// Hash password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert new user
$stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')");
$stmt->bind_param("sss", $name, $email, $hashed_password);

if ($stmt->execute()) {
    echo "✅ Registration successful! Redirecting to homepage...";
    header("refresh:2;url=index.php");

    exit();
} else {
    echo "❌ Registration failed: " . $conn->error;
}

$stmt->close();
$conn->close();
