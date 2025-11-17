<?php
require_once("db_connect.php");
$conn = getDBConnection();
$result = $conn->query("SELECT id, titre, stock FROM books");
echo json_encode($result->fetch_all(MYSQLI_ASSOC));
