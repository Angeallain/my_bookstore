<?php
session_start();
require_once("db_connect.php");

if (!isset($_SESSION['user']) || !isset($_GET['id'])) {
    header("Location: cart.php");
    exit;
}

$cart_item_id = intval($_GET['id']);
$conn = getDBConnection();

// Vérification que l'élément appartient au panier de l'utilisateur
$user_id = $_SESSION['user']['id'];

$conn->query("
    DELETE ci FROM cart_items ci
    JOIN cart c ON ci.cart_id = c.id
    WHERE ci.id = $cart_item_id AND c.user_id = $user_id
");

// Mettre à jour le total
$conn->query("
    UPDATE cart SET total = (
        SELECT COALESCE(SUM(b.prix * ci.quantité), 0)
        FROM cart_items ci
        JOIN books b ON ci.book_id = b.id
        WHERE ci.cart_id = cart.id
    )
    WHERE user_id = $user_id
");

header("Location: cart.php");
exit;
?>
