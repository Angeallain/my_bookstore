// Remplacez votre script actuel dans cart.php par celui-ci
fetch('nav_bar.php').then(r => r.text()).then(data => {
    document.getElementById('navbar').innerHTML = data;

    // Attendre un court instant pour s'assurer que le DOM est mis à jour
    setTimeout(() => {
        // Chargement de main.js correctement après que la navbar est chargée
        let script = document.createElement('script');
        script.src = '../js/main.js';
        document.body.appendChild(script);
        
        // Ajouter des gestionnaires d'événements spécifiques pour les liens du dropdown
        // après que main.js ait été chargé
        script.onload = function() {
            console.log("main.js chargé, configuration des liens du dropdown...");
            
            // Gestionnaire spécifique pour le lien Commandes
            const ordersLink = document.querySelector('a[href="order_history.php"]');
            if (ordersLink) {
                console.log("Lien Commandes trouvé, ajout du gestionnaire...");
                ordersLink.addEventListener('click', function(e) {
                    e.stopPropagation(); // Important: arrêter la propagation
                    console.log("Clic sur Commandes détecté");
                    window.location.href = "order_history.php";
                });
            }
            
            // Gestionnaire spécifique pour le lien Déconnexion
            const logoutLink = document.querySelector('a[href="logout.php"]');
            if (logoutLink) {
                console.log("Lien Déconnexion trouvé, ajout du gestionnaire...");
                logoutLink.addEventListener('click', function(e) {
                    e.stopPropagation(); // Important: arrêter la propagation
                    console.log("Clic sur Déconnexion détecté");
                    window.location.href = "logout.php";
                });
            }
            
            // Si le dropdown a déjà un gestionnaire qui arrête la propagation
            // nous devons ajouter des gestionnaires directs aux liens
            const dropdownLinks = document.querySelectorAll('.dropdown__link');
            dropdownLinks.forEach(link => {
                const href = link.getAttribute('href');
                link.onclick = function(e) {
                    e.stopPropagation();
                    window.location.href = href;
                    return false;
                };
            });
        };
    }, 100);
});

fetch('footer.html').then(r => r.text()).then(data => {
    document.getElementById('footer').innerHTML = data;
});

// Charger le compte du panier
document.addEventListener("DOMContentLoaded", () => {
    // S'assurer que le script se charge après le chargement de la navbar
    setTimeout(() => {
        fetch("get_cart_count.php")
            .then(response => response.json())
            .then(data => {
                const cartCountElement = document.getElementById("cart-count");
                if (cartCountElement) {
                    cartCountElement.textContent = data.cart_count;
                }
            });
    }, 700); // Augmenter le délai pour s'assurer que tout est chargé
});