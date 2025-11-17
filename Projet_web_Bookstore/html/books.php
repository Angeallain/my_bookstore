<?php
require_once("db_connect.php");
$conn = getDBConnection();

// Récupération des filtres depuis l'URL (GET)
$titre = isset($_GET['titre']) ? trim($_GET['titre']) : '';
$auteur = isset($_GET['auteur']) ? trim($_GET['auteur']) : '';
$genre = isset($_GET['genre']) ? trim($_GET['genre']) : '';

$sql = "SELECT * FROM books WHERE 1=1";
$params = [];
$types = '';

if (!empty($titre)) {
    $sql .= " AND titre LIKE ?";
    $params[] = "%" . $titre . "%";
    $types .= "s";
}
if (!empty($auteur)) {
    $sql .= " AND auteur LIKE ?";
    $params[] = "%" . $auteur . "%";
    $types .= "s";
}
if (!empty($genre)) {
    $sql .= " AND genre = ?";
    $params[] = $genre;
    $types .= "s";
}

$sql .= " ORDER BY id DESC";

if (!empty($params)) {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Pas de filtre => requête normale
    $result = $conn->query("SELECT * FROM books ORDER BY id DESC");
}

if ($result->num_rows > 0) {
    while ($book = $result->fetch_assoc()) {
        echo "<div class='book' data-id='{$book['id']}'>
                <img src='../{$book['image']}' alt='Image de couverture du livre'>
                <h3 class='book-title'>" . htmlspecialchars($book['titre']) . "</h3>
                <p class='book-author'>Auteur : <span class='book-author-name'>" . htmlspecialchars($book['auteur']) . "</span></p>
                <p class='book-genre'>Genre : <span class='book-genre-name'>" . htmlspecialchars($book['genre']) . "</span></p>
                <p class='book-price'>Prix : <span class='book-price-value'>{$book['prix']}</span>€</p>
                <div class='buttons-container'>
                    <button class='btn view-summary'><i class='ri-eye-line'></i> Voir Résumé</button>
                    <button class='btn add-to-cart'><i class='ri-shopping-cart-line'></i> Ajouter au Panier</button>
                </div>
                <div class='book-summary default-hidden'>
                    <p>" . htmlspecialchars($book['description']) . "</p>
                </div>
              </div>";
    }
} else {
    echo "<p>Aucun livre trouvé pour votre recherche.</p>";
}
?>
