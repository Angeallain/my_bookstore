<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nav_bar</title>

    <!--REMIXICONS FOR ICONS-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.css">

    <!--CSS-->
    <link rel="stylesheet" href="../css/nav_bar.css">
    <link rel="stylesheet" href="../css/image_profil.css">

    <!--GOOGLE FONTS-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Poppins:wght@400;600&display=swap" rel="stylesheet">

</head>

<body>
   <header class="header">
      <nav class="nav container">
         <a href="index.php" class="nav__logo">Malak's Bookstore</a>

         <div class="nav__menu" id="nav-menu">
            <ul class="nav__list">
               <li><a href="index.php#" class="nav__link">Accueil</a></li>
               <li><a href="index.php#books" class="nav__link">Livres</a></li>
               <li><a href="index.php#genres" class="nav__link">Genres</a></li>
               <li><a href="index.php#faq" class="nav__link">FAQ</a></li>
               <li><a href="index.php#contact" class="nav__link">Contact</a></li>
            </ul>

            <!-- Bouton de fermeture -->
            <div class="nav__close" id="nav-close">
               <i class="ri-close-large-line"></i>
            </div>
         </div>

         <div class="nav__actions">
            <!-- Panier -->
            <a href="cart.php" class="cart">
               <i class="ri-shopping-cart-line"></i>
               <span id="cart-count">0</span>
            </a>

            <!-- Profil / Connexion -->
            <div class="dropdown" id="dropdown">
               <div class="dropdown__profile">
                  <!-- on affiche "Se connecter" puis quand l'utilsateur se connecte on affiche son prénom -->
                  <div class="dropdown__names">
                  <?php
                    if (isset($_SESSION['user'])) {
                     echo "<h3 id='user-name'>" . htmlspecialchars($_SESSION['user']['nom']) . "</h3>";
                    } else {
                     echo "<h3 id='user-name'>Se connecter</h3>";
                    }
                  ?>
                  </div>
                  
                  <!-- image de profil sera unique pour tous les utilisateurs -->
                  <div class="dropdown__image">
                     <svg id="profile-icon" class="user-icon" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10.46 16.07C4.92 18.28 0.99 23.68 0.99 30A1 1 0 0 0 2 31h28a1 1 0 0 0 1-1c0-6.32-3.93-11.73-9.47-13.93-1.53 1.2-3.45 1.93-5.53 1.93-2.08 0-4-0.72-5.53-1.93z" fill="currentColor"/>
                        <path d="M16 1c-4.41 0-8 3.59-8 8s3.59 8 8 8 8-3.59 8-8-3.59-8-8-8z" fill="currentColor"/>
                     </svg>
                     <span class="status-dot"></span>
                  </div>


                  <!-- on affiche le dropdown qu'après la connexion -->
               <?php
                 if (isset($_SESSION['user'])) {
                  echo '<div class="dropdown__list" id="dropdown-list">
                  <a href="order_history.php" class="dropdown__link"><i class="ri-bookmark-line"></i> <span>Commandes</span></a>
                  <a href="logout.php" class="dropdown__link" target="_self"><i class="ri-logout-box-r-line"></i> <span>Déconnexion</span></a>
                  </div>';
                 } else {
                  echo '<div class="dropdown__list" id="dropdown-list"></div>';
                 }
              ?>

               </div>

               
               
            </div>

            <!-- Toggle menu -->
            <div class="nav__toggle" id="nav-toggle">
               <i class="ri-menu-line"></i>
            </div>
         </div>

         <div class="theme-selector">
            <i id="theme-icon" class="ri-palette-line"></i>
            <div class="theme-dropdown" id="theme-dropdown">
                <ul>
                    <li data-theme="default">Pistachio</li>
                    <li data-theme="purple">Purple</li>
                    <li data-theme="coffee">Coffee</li>
                    <li data-theme="blue">Blue</li>
                </ul>
            </div>
        </div>
      </nav>
   </header>

   <script>
document.addEventListener('DOMContentLoaded', function() {
    // Sélectionner spécifiquement le lien de déconnexion
    const logoutLink = document.querySelector('a[href="logout.php"]');
    
    if (logoutLink) {
        console.log('Lien de déconnexion trouvé');
        
        // Remplacer le comportement par défaut par une navigation explicite
        logoutLink.addEventListener('click', function(event) {
            console.log('Clic sur le lien de déconnexion détecté');
            event.preventDefault(); // Empêcher le comportement par défaut
            console.log('Navigation vers logout.php...');
            
            // Attendre un peu pour voir les logs dans la console
            setTimeout(function() {
                window.location.href = "logout.php";
            }, 300);
        });
    }

    // Ajouter un gestionnaire d'événements pour le lien "Commandes"
    const ordersLink = document.querySelector('a[href="order_history.php"]');
    
    if (ordersLink) {
        console.log('Lien Commandes trouvé');
        
        ordersLink.addEventListener('click', function(event) {
            console.log('Clic sur le lien Commandes détecté');
            event.preventDefault();
            console.log('Navigation vers order_history.php...');
            
            window.location.href = "order_history.php";
        });
    }

    // Vérifier si les liens du dropdown fonctionnent correctement
    const allDropdownLinks = document.querySelectorAll('.dropdown__link');
    
    allDropdownLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            console.log('Clic sur le lien dropdown:', this.getAttribute('href'));
        });
    });

    // Surveiller tous les événements de clic sur la page
    document.addEventListener('click', function(event) {
        console.log('Clic détecté sur :', event.target);
        
        // Vérifier si le clic est à l'intérieur du dropdown
        if (event.target.closest('.dropdown')) {
            console.log('Clic dans le dropdown');
        }
    }, true); // Phase de capture pour voir tous les événements
});
</script>

<script src="../js/main.js"></script>
<script src="../js/user_status.js"></script>

</body>
</html>