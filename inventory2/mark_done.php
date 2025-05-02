<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if (isset($_POST['request_id'])) {
    $requestId = $_POST['request_id'];
    $doneAt = date('Y-m-d H:i:s');

    $conn = new mysqli("localhost", "root", "", "inventory_system");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("UPDATE requests SET status = 'done', done_at = ? WHERE id = ?");
    $stmt->bind_param("si", $doneAt, $requestId);
    $stmt->execute();
    $stmt->close();
    $conn->close();
}

header("Location: admin.php");
exit;
?>
