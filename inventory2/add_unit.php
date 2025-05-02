<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['unit_name'])) {
    $unit = trim($_POST['unit_name']);

    $conn = new mysqli("localhost", "root", "", "inventory_system");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT IGNORE INTO units (name) VALUES (?)");
    $stmt->bind_param("s", $unit);
    $stmt->execute();

    $stmt->close();
    $conn->close();
}

header("Location: admin.php");
exit;
