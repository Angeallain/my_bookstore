<?php
require_once("db_connect.php");

$conn = getDBConnection();

$result = $conn->query("SELECT id, titre, auteur, genre, prix, image FROM books");

$books = [];

while ($row = $result->fetch_assoc()) {
    $books[] = $row;
}

echo json_encode($books);
?>
