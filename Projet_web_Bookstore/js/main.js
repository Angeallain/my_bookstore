const navMenu = document.getElementById('nav-menu');
const navToggle = document.getElementById('nav-toggle');
const navClose = document.getElementById('nav-close');

/* Menu show */
if(navToggle){
   navToggle.addEventListener('click', () =>{
      navMenu.classList.add('show-menu')
   })
}

/* Menu hidden */
if(navClose){
   navClose.addEventListener('click', () =>{
      navMenu.classList.remove('show-menu')
   })
}

/*=============== REMOVE MENU MOBILE ===============*/
const navLink = document.querySelectorAll('.nav__link')

const linkAction = () =>{
   // When we click on each nav__link, we remove the show-menu class
   navMenu.classList.remove('show-menu')
}
navLink.forEach(n => n.addEventListener('click', linkAction))

/*=============== SHOW DROPDOWN ===============*/
const userDropdown = () => {
    const dropdownProfile = document.querySelector('.dropdown__profile');
    const dropdown = document.getElementById('dropdown');
    const userName = document.getElementById('user-name');
    
    // Si l'utilisateur est connecté (vérifier si dropdown__list contient des éléments)
    const isLoggedIn = document.querySelector('.dropdown__list').children.length > 0;
    
    if (dropdownProfile && dropdown) {
        // Si l'utilisateur est connecté, activer le dropdown
        if (isLoggedIn) {
            // Ajout d'événement sur toute la zone du profil
            dropdownProfile.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropdown.classList.toggle('show-dropdown');
            });
            
            // Fermer le dropdown quand on clique ailleurs sur la page
            document.addEventListener('click', (e) => {
                if (!dropdown.contains(e.target)) {
                    dropdown.classList.remove('show-dropdown');
                }
            });

            // NOUVEAU: Ajouter des gestionnaires d'événements spécifiques pour les liens du dropdown
            const ordersLink = document.querySelector('a[href="order_history.php"]');
            const logoutLink = document.querySelector('a[href="logout.php"]');
            
            if (ordersLink) {
                console.log('Lien Commandes trouvé');
                ordersLink.addEventListener('click', function(event) {
                    console.log('Clic sur Commandes détecté');
                    // Ne pas appeler preventDefault() pour permettre la navigation normale
                    console.log('Navigation vers ' + this.getAttribute('href'));
                });
            }
            
            if (logoutLink) {
                console.log('Lien Déconnexion trouvé');
                logoutLink.addEventListener('click', function(event) {
                    console.log('Clic sur Déconnexion détecté');
                    // Ne pas appeler preventDefault() pour permettre la navigation normale
                    console.log('Navigation vers ' + this.getAttribute('href'));
                });
            }
        } else {
            // Si non connecté, rediriger vers la page d'inscription/connexion
            if (userName) {
                userName.addEventListener("click", function() {
                    window.location.href = "../html/register.html"; 
                });
            }
        }
    }
};

userDropdown();

const themeIcon = document.getElementById("theme-icon");
const themeDropdown = document.getElementById("theme-dropdown");
const themeOptions = document.querySelectorAll(".theme-dropdown ul li");

themeIcon.addEventListener("click", () => {
    themeDropdown.classList.toggle("show");
});

themeOptions.forEach(option => {
    option.addEventListener("click", (event) => {
        let selectedTheme = event.target.dataset.theme;
        applyTheme(selectedTheme);
        localStorage.setItem("selectedTheme", selectedTheme);
        themeDropdown.classList.remove("show");
    });
});

function applyTheme(themeName) {
    const themes = {
        default: {
            "--first-color": "#FEFAE0",
            "--second-color": "#78593a",
            "--third-color": "#C0C78C",
            "--fourth-color": "#A6B37D",
            "--body-color": "#FEFAE0",
            "--white-color": "#fff",
            "--back-color": "#C0C78C", 
            "--lighter-second-color": "#a27950" 
        },
        purple: {
            "--first-color": "#F0EBE3",
            "--second-color": "hsl(0, 0%, 100%)",
            "--third-color": "#AA60C8",
            "--fourth-color": "#F0EBE3",
            "--body-color": "#D69ADE",
            "--back-color": "#AA60C8", 
            "--white-color": "#D69ADE"
        },
        coffee: {
            "--first-color": "#48484c",
            "--second-color": "#d8842c",
            "--third-color": "#281424",
            "--fourth-color": "#48484c",
            "--body-color": "#382424",
            "--white-color": "#fff",
            "--back-color": "#848496",
            "--lighter-second-color": "#d4914a"

        },
        blue: {
            "--first-color": "#71BBB2",
            "--second-color": "#EFE9D5",
            "--third-color": "#7E99A3",
            "--fourth-color": "#71BBB2",
            "--body-color": "#213555",
            "--back-color": "#7E99A3", 
            "--white-color": "#213555"
        }
    };

    
    let themeColors = themes[themeName];
    for (let key in themeColors) {
        document.documentElement.style.setProperty(key, themeColors[key]);
    }
}

// Charger le thème sauvegardé
document.addEventListener("DOMContentLoaded", () => {
    let savedTheme = localStorage.getItem("selectedTheme") || "default";
    applyTheme(savedTheme);
});


/*.....................................................................................*/

document.getElementById("user-name").addEventListener("click", function() {
    window.location.href = "../html/register.html"; 
  });

document.addEventListener("DOMContentLoaded", () => {
    fetch("get_cart_count.php")
        .then(response => response.json())
        .then(data => {
            document.getElementById("cart-count").textContent = data.cart_count;
        });
});


/*-------------------------------------------------------------------------------------*/
// Ajouter un effet de défilement fluide en JavaScript

document.addEventListener("DOMContentLoaded", () => {
    const navLinks = document.querySelectorAll('.nav__list a[href^="index.html#"]');

    navLinks.forEach(link => {
        link.addEventListener("click", (e) => {
            e.preventDefault();
            const targetId = link.getAttribute("href").split("#")[1];
            const targetSection = document.getElementById(targetId);

            if (targetSection) {
                window.scrollTo({
                    top: targetSection.offsetTop - 80, // Ajuste la hauteur pour éviter d'être caché par la navbar
                    behavior: "smooth"
                });
            }
        });
    });
});

// Fonction de débogage pour vérifier si les événements sont bien attachés
document.addEventListener('DOMContentLoaded', function() {
    console.log('Vérification des gestionnaires d\'événements pour les liens du dropdown');
    
    // Test des liens dropdown après un court délai pour s'assurer que tout est chargé
    setTimeout(() => {
        const allLinks = document.querySelectorAll('a');
        console.log('Nombre total de liens dans la page:', allLinks.length);
        
        const ordersLink = document.querySelector('a[href="order_history.php"]');
        const logoutLink = document.querySelector('a[href="logout.php"]');
        
        console.log('Lien Commandes trouvé:', !!ordersLink);
        console.log('Lien Déconnexion trouvé:', !!logoutLink);
        
        // Test de l'accès direct aux liens pour vérifier s'ils sont bien visibles dans le DOM
        if (ordersLink) {
            console.log('URL du lien Commandes:', ordersLink.href);
            console.log('Texte du lien Commandes:', ordersLink.textContent);
            
            // On crée une solution alternative
            ordersLink.onclick = function(e) {
                console.log('Clic alternatif sur Commandes');
                window.location.href = 'order_history.php';
                return false; // Empêche la navigation par défaut
            };
        }
    }, 1000);
});


