<?php
require_once("db_connect.php");

$conn = getDBConnection();
$result = $conn->query("SELECT * FROM messages ORDER BY date_envoi DESC");

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

header('Content-Type: application/json');
echo json_encode($messages);
?>
