<?php
require_once("db_connect.php");

$data = json_decode(file_get_contents("php://input"), true);
$id = intval($data["id"]);

$conn = getDBConnection();
$stmt = $conn->prepare("DELETE FROM messages WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

echo json_encode(["success" => true]);
?>
