<?php
session_start();
require_once("db_connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user']['id'];
    $data = json_decode(file_get_contents("php://input"), true);
    $order_id = intval($data['order_id']);
    $raison = "Annulation client";

    $conn = getDBConnection();
    $stmt = $conn->prepare("CALL CancelOrder(?, ?, ?)");
    $stmt->bind_param("iis", $order_id, $user_id, $raison);
    $stmt->execute();

    echo json_encode(["success" => true]);
}
?>
