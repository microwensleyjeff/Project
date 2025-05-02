<?php
session_start();
require 'db.php';

// Initialize attempts if not set
if (!isset($_SESSION['attempts'])) {
    $_SESSION['attempts'] = 0;
}

// Lock out after 3 failed attempts
if ($_SESSION['attempts'] >= 3) {
    echo "Too many failed login attempts. Please try again later.<br><a href='login.php'>Back to login</a>";
    exit;
}

$username = $_POST['username'];
$password = $_POST['password'];
$role = $_POST['role'];

$stmt = $conn->prepare("SELECT password FROM users WHERE username = ? AND role = ?");
$stmt->bind_param("ss", $username, $role);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($hashedPassword);
    $stmt->fetch();

    if (password_verify($password, $hashedPassword)) {
        // Reset attempts on success
        $_SESSION['attempts'] = 0;
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;

        header("Location: " . ($role === 'admin' ? 'admin.php' : 'user.php'));
        exit;
    }
}

// Failed login
$_SESSION['attempts']++;
$remaining = 3 - $_SESSION['attempts'];
echo "Invalid login. Attempts left: $remaining<br><a href='login.php'>Try again</a>";

$stmt->close();
$conn->close();
