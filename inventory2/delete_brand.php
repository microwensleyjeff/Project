<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['brand_id'])) {
    $brandId = intval($_POST['brand_id']);

    $conn = new mysqli("localhost", "root", "", "inventory_system");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("DELETE FROM brands WHERE id = ?");
    $stmt->bind_param("i", $brandId);
    $stmt->execute();

    $stmt->close();
    $conn->close();
}

header("Location: admin.php");
exit;
