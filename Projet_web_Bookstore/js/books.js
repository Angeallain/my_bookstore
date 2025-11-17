document.addEventListener("DOMContentLoaded", () => {
    const booksContainer = document.querySelector(".books-container");
    const bookElements = booksContainer.querySelectorAll(".book");

    const bookModal = document.getElementById("bookModal");
    const bookModalBody = document.getElementById("bookModalBody");
    const modalClose = document.querySelector(".book-modal-close");


    bookElements.forEach((bookElement) => {
        const summaryBtn = bookElement.querySelector(".view-summary");
        const summaryBox = bookElement.querySelector(".book-summary");

        summaryBtn.addEventListener("click", () => {
            bookElement.classList.toggle("show-summary");

            if (bookElement.classList.contains("show-summary")) {
                summaryBtn.innerHTML = `<i class="ri-close-line"></i> Fermer Résumé`;
            } else {
                summaryBtn.innerHTML = `<i class="ri-eye-line"></i> Voir Résumé`;
            }
        });

        const addToCartBtn = bookElement.querySelector(".add-to-cart");
        addToCartBtn.addEventListener("click", () => {
            const bookId = bookElement.getAttribute("data-id");
        
            fetch("add_to_cart.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: `book_id=${bookId}`
            })
            .then(response => response.json())
            .then(data => {
                console.log(data); 
                if (data.success) {
                    alert("Livre ajouté au panier !");
                    document.getElementById("cart-count").textContent = data.cart_count;
                } else {
                    alert(data.message || "Erreur lors de l'ajout.");
                }
            });
        });

        // Événement d'ouverture du modal
        bookElement.addEventListener("click", (e) => {
            if (!e.target.closest("button") && !e.target.closest(".book-summary")) {
                const title = bookElement.querySelector(".book-title")?.innerText || "Titre non disponible";
                const author = bookElement.querySelector(".book-author-name")?.innerText || "Auteur inconnu";
                const genre = bookElement.querySelector(".book-genre-name")?.innerText || "Genre non spécifié";
                const price = bookElement.querySelector(".book-price-value")?.innerText || "Prix non disponible";
                const description = bookElement.querySelector(".book-summary p")?.innerText || "Aucune description disponible";
                const imgSrc = bookElement.querySelector("img")?.src || "";

                if (bookModalBody) {
                    bookModalBody.innerHTML = `
                        <h2>${title}</h2>
                        <p><strong>Auteur :</strong> ${author}</p>
                        <p><strong>Genre :</strong> ${genre}</p>
                        <p><strong>Prix :</strong> ${price}€</p>
                        <p><strong>Description :</strong> ${description}</p>
                        ${imgSrc ? `<img src="${imgSrc}" style="max-width: 100%; margin-top: 1rem;" alt="Couverture">` : ''}
                    `;
                }

                if (bookModal) {
                    bookModal.style.display = "flex";
                }
            }
        });


    });

    // Fermer le modal
    if (modalClose) {
        modalClose.addEventListener("click", () => {
            if (bookModal) bookModal.style.display = "none";
        });
    }

    window.addEventListener("click", (e) => {
        if (e.target === bookModal) {
            bookModal.style.display = "none";
        }
    });

    // Fermer le modal avec la touche Escape
    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape" && bookModal && bookModal.style.display === "flex") {
            bookModal.style.display = "none";
        }
    });
});