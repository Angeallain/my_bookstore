const wraper = document.querySelector('.wraper');
const images = wraper.querySelectorAll('img');

let isDown = false;
let startX;
let scrollLeft;
let autoScrollInterval;

// Fonction pour détecter l'image au centre et lui ajouter la classe "active"
function updateActiveImage() {
    let center = wraper.scrollLeft + wraper.clientWidth / 2;
    let minDiff = Infinity;
    let activeImg = null;

    images.forEach(img => {
        let imgCenter = img.offsetLeft + img.clientWidth / 2;
        let diff = Math.abs(center - imgCenter);
        
        if (diff < minDiff) {
            minDiff = diff;
            activeImg = img;
        }
    });

    images.forEach(img => img.classList.remove('active')); // Supprime 'active' de toutes les images
    if (activeImg) activeImg.classList.add('active'); // Ajoute 'active' à l’image au centre
}

// Fonction pour activer le swipe automatique
function startAutoSwipe() {
    stopAutoSwipe(); // On arrête d'abord l'ancien intervalle s'il existe
    autoScrollInterval = setInterval(() => {
        wraper.scrollLeft += 150; // Fait défiler vers la droite

        // Vérifie si on est à la fin, et revient au début
        if (wraper.scrollLeft + wraper.clientWidth >= wraper.scrollWidth) {
            wraper.scrollLeft = 0; 
        }

        updateActiveImage(); // Met à jour l'effet visuel
    }, 2000); 
}

// Fonction pour stopper le swipe automatique
function stopAutoSwipe() {
    clearInterval(autoScrollInterval);
}

// Démarre le swipe automatique au chargement
window.addEventListener('load', () => {
    startAutoSwipe();
    updateActiveImage(); // Pour que la première image soit bien mise en avant dès le début
});

// Désactive le swipe automatique si l'utilisateur interagit
wraper.addEventListener('mousedown', stopAutoSwipe);
wraper.addEventListener('mouseup', () => {
    startAutoSwipe();
    updateActiveImage();
});
wraper.addEventListener('touchstart', stopAutoSwipe);
wraper.addEventListener('touchend', () => {
    startAutoSwipe();
    updateActiveImage();
});

// Swipe manuel (drag)
wraper.addEventListener('mousedown', (e) => {
    isDown = true;
    wraper.classList.add('active');
    startX = e.pageX - wraper.offsetLeft;
    scrollLeft = wraper.scrollLeft;
});

wraper.addEventListener('mouseleave', () => {
    isDown = false;
    wraper.classList.remove('active');
});

wraper.addEventListener('mouseup', () => {
    isDown = false;
    wraper.classList.remove('active');
    updateActiveImage(); // Met à jour l'effet après le drag
});

wraper.addEventListener('mousemove', (e) => {
    if (!isDown) return;
    e.preventDefault();
    const x = e.pageX - wraper.offsetLeft;
    const walk = (x - startX) * 2;
    wraper.scrollLeft = scrollLeft - walk;
});

// Gestion du swipe tactile
wraper.addEventListener('touchstart', (e) => {
    startX = e.touches[0].pageX - wraper.offsetLeft;
    scrollLeft = wraper.scrollLeft;
});

wraper.addEventListener('touchmove', (e) => {
    e.preventDefault();
    const x = e.touches[0].pageX - wraper.offsetLeft;
    const walk = (x - startX) * 2;
    wraper.scrollLeft = scrollLeft - walk;
});

// Met à jour l'image active après chaque scroll
wraper.addEventListener('scroll', updateActiveImage);
