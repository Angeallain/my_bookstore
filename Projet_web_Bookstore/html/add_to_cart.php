<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once("db_connect.php");
header("Content-Type: application/json");

if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => 'Vous devez être connecté.']);
    exit;
}

if (!isset($_POST['book_id'])) {
    echo json_encode(['success' => false, 'message' => 'ID du livre manquant.']);
    exit;
}

$user_id = $_SESSION['user']['id'];
$book_id = intval($_POST['book_id']);

$conn = getDBConnection();

// 1. Vérifier si l'utilisateur a un panier
$cart_check = $conn->prepare("SELECT id FROM cart WHERE user_id = ?");
$cart_check->bind_param("i", $user_id);
$cart_check->execute();
$cart_check->store_result();

if ($cart_check->num_rows === 0) {
    $create_cart = $conn->prepare("INSERT INTO cart (user_id, total) VALUES (?, 0)");
    $create_cart->bind_param("i", $user_id);
    $create_cart->execute();
    $cart_id = $create_cart->insert_id;
} else {
    $cart_check->bind_result($cart_id);
    $cart_check->fetch();
    $cart_check->free_result();
}

// 2. Vérifier si le livre est déjà dans le panier
$item_check = $conn->prepare("SELECT id, quantité FROM cart_items WHERE cart_id = ? AND book_id = ?");
$item_check->bind_param("ii", $cart_id, $book_id);
$item_check->execute();
$item_check->store_result();

if ($item_check->num_rows > 0) {
    $item_check->bind_result($item_id, $quantité);
    $item_check->fetch();
    $item_check->free_result();

    $new_quantité = $quantité + 1;
    $update_item = $conn->prepare("UPDATE cart_items SET quantité = ? WHERE id = ?");
    $update_item->bind_param("ii", $new_quantité, $item_id);
    $update_item->execute();
} else {
    $item_check->free_result(); // Toujours libérer même sans fetch si on fait une autre requête ensuite

    $insert_item = $conn->prepare("INSERT INTO cart_items (cart_id, book_id, quantité) VALUES (?, ?, 1)");
    $insert_item->bind_param("ii", $cart_id, $book_id);
    $insert_item->execute();
}

// 3. Recalculer le total du panier
$total_query = $conn->prepare("SELECT SUM(b.prix * ci.quantité) 
                               FROM cart_items ci 
                               JOIN books b ON ci.book_id = b.id 
                               WHERE ci.cart_id = ?");
$total_query->bind_param("i", $cart_id);
$total_query->execute();
$total_query->bind_result($total);
$total_query->fetch();
$total_query->free_result();

// Mettre à jour le total dans `cart`
$update_total = $conn->prepare("UPDATE cart SET total = ? WHERE id = ?");
$update_total->bind_param("di", $total, $cart_id);
$update_total->execute();

// 4. Compter les articles du panier
$count_query = $conn->prepare("SELECT SUM(quantité) FROM cart_items WHERE cart_id = ?");
$count_query->bind_param("i", $cart_id);
$count_query->execute();
$count_query->bind_result($cart_count);
$count_query->fetch();
$count_query->free_result();

echo json_encode([
    'success' => true,
    'message' => 'Livre ajouté au panier',
    'cart_count' => $cart_count ?? 0
]);
?>
