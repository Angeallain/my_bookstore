<?php

require_once("db_connect.php");
$conn = getDBConnection();

// Requête pour les images du slider
$sqlSlider = "SELECT image FROM books ORDER BY id DESC LIMIT 12";
$resultSlider = $conn->query($sqlSlider);

// Requête pour les genres
$sqlGenres = "SELECT DISTINCT genre FROM books ORDER BY genre ASC";
$resultGenres = $conn->query($sqlGenres);

// Si l'utilisateur accepte les cookies via le formulaire
if (isset($_POST['accept_cookies'])) {
    setcookie("cookies_accepted", "true", time() + (86400 * 30)); // 30 jours
    header("Location: " . $_SERVER['PHP_SELF']); // Redirection pour éviter le repost
    exit;
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookstore</title>
    <link rel="stylesheet" href="../css/home.css">
    <link rel="stylesheet" href="../css/principal.css">
    <link rel="stylesheet" href="../css/contact.css">
    <link rel="stylesheet" href="../css/about.css">
    <link rel="stylesheet" href="../css/genres.css">
    <link rel="stylesheet" href="../css/faq.css">
    <link rel="stylesheet" href="../css/books.css">
    <link rel="stylesheet" href="../css/filtre.css">
    <link rel="stylesheet" href="../css/modal.css">
    <link rel="stylesheet" href="../css/cookie.css">
</head>
<body>
    <?php include("nav_bar.php"); ?>

    <section class="principal" id="principal">
        <div class="row">
            <div class="content">
                <h3>Bienvenue chez <br> Malak's Bookstore</h3>
                <p>Plongez dans un univers de lecture ! Que vous soyez passionné de romans,
                amateur de science-fiction ou friand de livres pratiques, notre boutique a ce qu'il vous faut.
                Profitez de réductions exceptionnelles et commandez dès maintenant !</p>
                <a href="#books" class="bttn">Voir Boutique</a>
            </div>
  
          <div class="books-slider">
          <div class="wraper">
              <?php
               if ($resultSlider->num_rows > 0) {
               while ($row = $resultSlider->fetch_assoc()) {
               echo "<a><img src='../" . htmlspecialchars($row['image']) . "' alt='Livre'></a>";
                }
                }
               ?>
         </div>

              <button class="next">&#10095;</button>
              <div class="stand">
                  Livres
              </div>
          </div>
        </div>
        <div class="decoration"></div> <!--pour la décoration-->
      </section>

    <section class="about" id="about">
        <h1 class="heading">À <span>Propos</span></h1>
        <p>Malak's Bookstore est une librairie en ligne offrant une large sélection de livres de différents genres...</p>
    </section>

    <section class="genres" id="genres">
        <h1 class="heading">Nos <span>Genres</span></h1>
        <div class="genre-list">
            <?php
             if ($resultGenres->num_rows > 0) {
             while ($row = $resultGenres->fetch_assoc()) {
             echo "<div class='genre'>" . htmlspecialchars($row['genre']) . "</div>";
             }
             }
           ?>
       </div>

    </section>

    <section class="books" id="books">
        <h1 class="heading">Nos <span>Livres</span></h1>
        <form method="GET" action="#books" class="book-filter-form">
        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
        <input type="text" name="titre" placeholder="Titre du livre" class="box" style="flex: 1;">
        <input type="text" name="auteur" placeholder="Auteur" class="box" style="flex: 1;">
        <select name="genre" class="box" style="flex: 1;">
        <option value="">Tous les genres</option>
        <option value="Roman">Roman</option>
        <option value="Science-Fiction">Science-Fiction</option>
        <option value="Fantasy">Fantasy</option>
        <option value="Biographies">Biographies</option>
        <option value="Mangas">Mangas</option>
        <option value="Livres Scolaires">Livres Scolaires</option>
        </select>
        <button type="submit" class="btn" style="flex: none;">Chercher</button>
        </div>
        </form>
        <div class="books-container">
            <?php include("books.php"); ?>
        </div>
    </section>

    <section class="faq" id="faq">
        <h1 class="heading">FAQ</h1>
        <div class="faq-item">
            <h3>Comment commander un livre ?</h3>
            <p>Ajoutez le livre souhaité à votre panier et suivez le processus de commande.</p>
        </div>
        <div class="faq-item">
            <h3>Quels sont les modes de paiement acceptés ?</h3>
            <p>Nous acceptons les paiements par carte bancaire et PayPal.</p>
        </div>
        <div class="faq-item">
            <h3>Quels sont les délais de livraison ?</h3>
            <p>La livraison prend entre 3 à 5 jours ouvrables.</p>
        </div>
    </section>

    <section class="contact" id="contact">
        <h1 class="heading"><span> Contactez </span> Nous</h1>
        <div class="row">

            <form id="contactForm">
                <input type="text" name="nom" placeholder="Nom" class="box" required>
                <input type="email" name="email" placeholder="Email" class="box" required>
                <textarea name="message" placeholder="Message" class="box" cols="30" rows="10" required></textarea>
                <input type="submit" value="envoyer" class="btn">
            </form>

            <div class="map">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d13291.796525914323!2d3.050038!3d36.7528871!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x128fadf27dcb4b8f%3A0x47d0e32e3a205c7!2sAlger!5e0!3m2!1sfr!2sdz!4v1715162295880!5m2!1sfr!2sdz" 
                    width="100%" height="350" style="border:0;" allowfullscreen="" loading="lazy">
                </iframe>
            </div>
        </div>
    </section>

    <!-- Modal pour détails du livre -->
    <div id="bookModal" class="book-modal">
       <div class="book-modal-content">
        <span class="book-modal-close">&times;</span>
        <div class="book-modal-body" id="bookModalBody">
           <!-- Le contenu sera injecté par JavaScript -->
        </div>
       </div>
    </div>

    <!-- Bannière cookies affichée uniquement si le cookie n’existe pas -->
    <?php if (!isset($_COOKIE['cookies_accepted'])): ?>
        <div class="cookie-banner">
            <p>Ce site utilise des cookies pour améliorer votre expérience. En continuant, vous acceptez leur utilisation.</p>
            <form method="post">
                <button type="submit" name="accept_cookies">J'accepte</button>
            </form>
        </div>
    <?php endif; ?>

    <?php include("footer.html"); ?>

    <script src="../js/principal.js"></script>
    <script src="../js/books.js?v=<?php echo time(); ?>"></script>
    <script src="../js/main.js"></script>
    <script src="../js/message.js"></script>
</body>
</html>
