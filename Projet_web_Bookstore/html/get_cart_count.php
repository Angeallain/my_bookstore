<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user'])) {
    echo json_encode(['cart_count' => 0]);
    exit;
}

require_once("db_connect.php");
$conn = getDBConnection();

$user_id = $_SESSION['user']['id'];

$query = $conn->prepare("SELECT c.id FROM cart c WHERE c.user_id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$query->bind_result($cart_id);
$query->fetch();
$query->close();

if (!$cart_id) {
    echo json_encode(['cart_count' => 0]);
    exit;
}

$count_query = $conn->prepare("SELECT SUM(quantité) FROM cart_items WHERE cart_id = ?");
$count_query->bind_param("i", $cart_id);
$count_query->execute();
$count_query->bind_result($count);
$count_query->fetch();
$count_query->close();

echo json_encode(['cart_count' => $count ?: 0]);
?>
