<?php
session_start();
require_once("db_connect.php");

if (!isset($_SESSION['user'])) {
    header("Location: register.html");
    exit;
}

$conn = getDBConnection();
$user_id = $_SESSION['user']['id'];

$conn->query("CALL FinalizeOrder($user_id, @order_id)");

header("Location: order_history.php");
exit;
?>
