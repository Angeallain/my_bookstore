<?php
session_start();
require_once("db_connect.php");

if (!isset($_SESSION['user'])) {
    header("Location: register.html");
    exit;
}

$conn = getDBConnection();
$user_id = $_SESSION['user']['id'];

// Récupérer le panier
$cartQuery = $conn->prepare("
    SELECT ci.id as cart_item_id, b.id as book_id, b.titre, b.auteur, b.image, b.prix, ci.quantité
    FROM cart_items ci
    JOIN cart c ON ci.cart_id = c.id
    JOIN books b ON ci.book_id = b.id
    WHERE c.user_id = ?
");
$cartQuery->bind_param("i", $user_id);
$cartQuery->execute();
$result = $cartQuery->get_result();

// Calcul du total
$total = 0;
$books = [];
while ($row = $result->fetch_assoc()) {
    $books[] = $row;
    $total += $row['prix'] * $row['quantité'];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Panier</title>
    <link rel="stylesheet" href="../css/cart.css">
    <link rel="stylesheet" href="../css/fixes.css">
</head>
<body>
    <header><div id="navbar"></div></header>

    <section class="cart-section">
        <h1 class="heading">🛒 Mon <span>Panier</span></h1>

        <div class="cart-list">
            <?php if (empty($books)): ?>
                <p>Votre panier est vide.</p>
            <?php else: ?>
                <?php foreach ($books as $book): ?>
                    <div class="cart-item">
                        <img src="../<?= htmlspecialchars($book['image']) ?>" alt="Image livre">
                        <div class="cart-info">
                            <h3><?= htmlspecialchars($book['titre']) ?></h3>
                            <p>Auteur : <?= htmlspecialchars($book['auteur']) ?></p>
                            <p>Prix : <?= number_format($book['prix'], 2) ?>€</p>
                            <p>Quantité : <?= $book['quantité'] ?></p>
                            <a href="remove_from_cart.php?id=<?= $book['cart_item_id'] ?>" class="remove-btn">❌ Supprimer</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="cart-summary">
            <p class="total-price">Total : <?= number_format($total, 2) ?>€</p>
            <?php if (!empty($books)): ?>
                <form method="POST" action="confirm_order.php">
                    <button class="confirm-btn" type="submit">Confirmer l'achat</button>
                </form>
            <?php endif; ?>
        </div>
    </section>

    <footer><div id="footer"></div></footer>

    <script src="../js/loading.js"></script>
</body>
</html>
