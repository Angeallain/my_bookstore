<?php
require_once("db_connect.php");
$conn = getDBConnection();

$orders = $conn->query("
  SELECT o.id, u.nom AS client, o.date_commande, o.total, o.statut
  FROM orders o
  JOIN users u ON o.user_id = u.id
  ORDER BY o.date_commande DESC
");

echo json_encode($orders->fetch_all(MYSQLI_ASSOC));
