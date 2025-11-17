<?php
// admin.php
session_start();
require_once("db_connect.php");

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: register.php");
    exit;
}

$conn = getDBConnection();
$books = $conn->query("SELECT * FROM books ORDER BY id DESC");
$admins = $conn->query("SELECT * FROM users WHERE role = 'admin' ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="../css/stock_orders_admin.css">
    <link rel="stylesheet" href="../css/nav_admin.css">
</head>
<body>
    <header style="position: fixed; top: 0; left: 0; right: 0; z-index: 1000;">
        <h1>Panneau d'administration</h1>
        <nav class="admin-nav">
            <a href="#livres">Livres</a>
            <a href="#admins">Admins</a>
            <a href="#messages">Messages</a>
            <a href="#stock">Stock</a>
            <a href="#commandes">Commandes</a>
        </nav>
        <button id="logout">Déconnexion</button>
    </header>

    <main>
        <section class="dashboard" id="livres">
            <h2>Livres</h2>
            <button id="addBookBtn">Ajouter un livre</button>
            <table>
                <thead>
                    <tr><th>ID</th><th>Titre</th><th>Auteur</th><th>Genre</th><th>Prix</th><th>Image</th><th>Actions</th></tr>
                </thead>
                <tbody id="bookTableBody">
                    <?php while ($book = $books->fetch_assoc()): ?>
                    <tr>
                        <td><?= $book['id'] ?></td>
                        <td><?= htmlspecialchars($book['titre']) ?></td>
                        <td><?= htmlspecialchars($book['auteur']) ?></td>
                        <td><?= htmlspecialchars($book['genre']) ?></td>
                        <td><?= $book['prix'] ?>€</td>
                        <td><button class="delete-book" data-id="<?= $book['id'] ?>">Supprimer</button></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <h2 id="messages">Messages de Contact</h2>
            <table>
               <thead>
                <tr>
                   <th>ID</th><th>Nom</th><th>Email</th><th>Message</th><th>Date</th>
                </tr>
               </thead>
               <tbody id="messagesTableBody">
                <!-- Les messages seront injectés ici -->
               </tbody>
            </table>
        </section>

        <section class="dashboard" id="admins">
            <h2>Admins</h2>
            <button id="addAdminBtn">Ajouter un admin</button>
            <table>
                <thead>
                    <tr><th>ID</th><th>Nom</th><th>Email</th><th>Actions</th></tr>
                </thead>
                <tbody id="adminTableBody">
                    <?php while ($admin = $admins->fetch_assoc()): ?>
                    <tr>
                        <td><?= $admin['id'] ?></td>
                        <td><?= htmlspecialchars($admin['nom']) ?></td>
                        <td><?= htmlspecialchars($admin['email']) ?></td>
                        <td><button class="delete-admin" data-id="<?= $admin['id'] ?>">Supprimer</button></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>
    </main>

    <!-- Modals -->
    <div id="bookModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Ajouter un livre</h2>
            <form id="bookForm">
                <input type="text" id="bookTitle" placeholder="Titre" required>
                <input type="text" id="bookAuthor" placeholder="Auteur" required>
                <input type="text" id="bookGenre" placeholder="Genre" required>
                <input type="number" id="bookPrice" placeholder="Prix" step="0.01" required>
                <input type="file" id="bookImage" accept="image/*" required>
                <input type="file" id="bookPDF" accept="application/pdf" required>

                <button type="submit">Enregistrer</button>
            </form>
        </div>
    </div>

    <div id="adminModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Ajouter un admin</h2>
            <form id="adminForm">
                <input type="text" id="adminName" placeholder="Nom" required>
                <input type="email" id="adminEmail" placeholder="Email" required>
                <input type="password" id="adminPassword" placeholder="Mot de passe" required>
                <button type="submit">Enregistrer</button>
            </form>
        </div>
    </div>

    <!-- Section GESTION DE STOCK -->
<section class="dashboard" id="stock">
  <h2>Gestion de Stock</h2>
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Titre</th>
        <th>Stock</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody id="stockTableBody"></tbody>
  </table>
</section>

<!-- Section HISTORIQUE COMMANDES -->
<section class="dashboard" id="commandes">
  <h2>Historique des Commandes</h2>
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Client</th>
        <th>Date</th>
        <th>Total</th>
        <th>Statut</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody id="ordersTableBody"></tbody>
  </table>
</section>


    <script src="../js/admin.js"></script>
    <script src="../js/message_admin.js"></script>
    <script src="../js/stock_orders_admin.js"></script>
</body>
</html>
