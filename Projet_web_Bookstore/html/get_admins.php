<?php
require_once("db_connect.php");
header("Content-Type: application/json");

$conn = getDBConnection();
$result = $conn->query("SELECT id, nom, email FROM users WHERE role = 'admin'");

$admins = [];
while ($row = $result->fetch_assoc()) {
    $admins[] = $row;
}

echo json_encode($admins);
?>
