<?php
require_once("db_connect.php");
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id'])) {
    $conn = getDBConnection();
    $stmt = $conn->prepare("UPDATE orders SET statut = 'expédiée' WHERE id = ?");
    $stmt->bind_param("i", $data['id']);
    $stmt->execute();
}
