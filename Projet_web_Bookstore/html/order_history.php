<?php
session_start();
require_once("db_connect.php");

if (!isset($_SESSION['user'])) {
    header("Location: register.php");
    exit;
}

$user_id = $_SESSION['user']['id'];
$conn = getDBConnection();

$orders = $conn->query("
    SELECT * FROM orders
    WHERE user_id = $user_id
    ORDER BY date_commande DESC
");

$details = [];

while ($order = $orders->fetch_assoc()) {
    $order_id = $order['id'];
    $items = $conn->query("
        SELECT b.titre, oi.quantité, oi.prix_unitaire
        FROM order_items oi
        JOIN books b ON oi.book_id = b.id
        WHERE oi.order_id = $order_id
    ");

    $details[] = [
        'order' => $order,
        'items' => $items->fetch_all(MYSQLI_ASSOC)
    ];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Historique des Commandes</title>
    <link rel="stylesheet" href="../css/order_history.css">
    <link rel="stylesheet" href="../css/fixes.css">
</head>
<body>
    <header><div id="navbar"></div></header>

    <section class="order-history-section">
        <h1 class="heading">Mon <span>Historique d'Achat</span></h1>

        <?php if (empty($details)): ?>
            <p>Vous n'avez encore passé aucune commande.</p>
        <?php else: ?>
            <?php foreach ($details as $entry): ?>
                <div class="order">
                    <div class="order-header">
                        <p><strong>Commande #<?= $entry['order']['id'] ?></strong></p>
                        <p>Date : <?= $entry['order']['date_commande'] ?></p>
                        <p>Statut : <span class="status <?= str_replace(' ', '-', strtolower($entry['order']['statut'])) ?>">
                            <?= ucfirst($entry['order']['statut']) ?>
                        </span></p>
                        <p>Total : <?= number_format($entry['order']['total'], 2) ?>€</p>
                        <?php if ($entry['order']['statut'] === 'en attente'): ?>
                           <button class="cancel-order-btn" style="background: #e74c3c; color: white; border-radius: 0.5rem; border: none; cursor: pointer; font-weight: bold; margin-top: 0.5rem; padding: 0.4rem 1rem;" data-id="<?= $entry['order']['id'] ?>">Annuler</button>
                        <?php endif; ?>
                    </div>
                    <div class="order-items">
                        <?php foreach ($entry['items'] as $item): ?>
                            <div class="order-item">
                                <p><strong><?= htmlspecialchars($item['titre']) ?></strong> - <?= $item['quantité'] ?> × <?= number_format($item['prix_unitaire'], 2) ?>€</p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>

    <footer><div id="footer"></div></footer>

    <script src="../js/loading.js"></script>
    <script>
    document.addEventListener("click", (e) => {
    if (e.target.classList.contains("cancel-order-btn")) {
        const orderId = e.target.dataset.id;
        if (confirm("Voulez-vous vraiment annuler cette commande ?")) {
            fetch("cancel_order.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ order_id: orderId })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert("Commande annulée.");
                    location.reload();
                }
            });
        }
    }
    });
    </script>

    
</body>
</html>
