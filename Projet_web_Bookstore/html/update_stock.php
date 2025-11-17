<?php
require_once("db_connect.php");
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id']) && isset($data['stock'])) {
    $conn = getDBConnection();
    $stmt = $conn->prepare("UPDATE books SET stock = ? WHERE id = ?");
    $stmt->bind_param("ii", $data['stock'], $data['id']);
    $stmt->execute();
}
