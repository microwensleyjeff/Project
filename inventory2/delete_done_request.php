<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if (isset($_POST['request_id'])) {
    $requestId = $_POST['request_id'];

    $conn = new mysqli("localhost", "root", "", "inventory_system");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("DELETE FROM requests WHERE id = ? AND status = 'done'");
    $stmt->bind_param("i", $requestId);
    $stmt->execute();
    $stmt->close();
    $conn->close();
}

header("Location: admin.php");
exit;
?>
