<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['unit_id'], $_POST['brand_name'])) {
    $unit_id = $_POST['unit_id'];
    $brand_name = trim($_POST['brand_name']);

    $conn = new mysqli("localhost", "root", "", "inventory_system");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO brands (unit_id, name) VALUES (?, ?)");
    $stmt->bind_param("is", $unit_id, $brand_name);
    $stmt->execute();

    $stmt->close();
    $conn->close();
}

header("Location: admin.php");
exit;
