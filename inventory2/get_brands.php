<?php
if (!isset($_GET['unit_id'])) {
    echo json_encode([]);
    exit;
}

$conn = new mysqli("localhost", "root", "", "inventory_system");
if ($conn->connect_error) {
    echo json_encode([]);
    exit;
}

$unitId = intval($_GET['unit_id']);
$stmt = $conn->prepare("SELECT id, name FROM brands WHERE unit_id = ?");
$stmt->bind_param("i", $unitId);
$stmt->execute();
$result = $stmt->get_result();

$brands = [];
while ($row = $result->fetch_assoc()) {
    $brands[] = $row;
}

echo json_encode($brands);
