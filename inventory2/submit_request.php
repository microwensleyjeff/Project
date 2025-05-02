<?php
session_start();

// Ensure user is logged in and has 'user' role
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit;
}

// Validate required POST data
if (!isset($_POST['unit_id'], $_POST['brand_id'], $_POST['quantity'])) {
    header("Location: user.php?error=missing_data");
    exit;
}

// Sanitize and validate input
$unitId = (int)$_POST['unit_id'];
$brandId = (int)$_POST['brand_id'];
$quantity = (int)$_POST['quantity'];

// Fetch username from session
$username = $_SESSION['username'] ?? null;

if (!$username) {
    header("Location: login.php");
    exit;
}

// Connect to DB
$conn = new mysqli("localhost", "root", "", "inventory_system");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user ID from database based on username
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($userId);
$stmt->fetch();
$stmt->close();

if (!$userId) {
    $conn->close();
    header("Location: user.php?error=user_not_found");
    exit;
}

// Insert request
$stmt = $conn->prepare("INSERT INTO requests (user_id, unit_id, brand_id, quantity) VALUES (?, ?, ?, ?)");
$stmt->bind_param("iiii", $userId, $unitId, $brandId, $quantity);

if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    header("Location: user.php?submitted=1");
    exit;
} else {
    $stmt->close();
    $conn->close();
    header("Location: user.php?error=submit_failed");
    exit;
}
?>
