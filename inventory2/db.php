<?php
$host = 'localhost';
$dbname = 'inventory_system';
$username = 'root';
$password = ''; // Default XAMPP password

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
