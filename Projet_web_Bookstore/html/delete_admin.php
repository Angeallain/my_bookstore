<?php
require_once("db_connect.php");
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
if (!$data || !isset($data['id'])) {
    echo json_encode(["success" => false, "message" => "ID requis."]);
    exit;
}

$conn = getDBConnection();
$stmt = $conn->prepare("DELETE FROM users WHERE id = ? AND role = 'admin'");
$stmt->bind_param("i", $data['id']);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Suppression impossible."]);
}
?>
