document.addEventListener("DOMContentLoaded", () => {
    const bookTable = document.getElementById("bookTableBody");
    const adminTable = document.getElementById("adminTableBody");

    const fetchBooks = () => {
        fetch("get_books.php")
            .then(res => res.json())
            .then(data => {
                bookTable.innerHTML = "";
                data.forEach(book => {
                    const row = document.createElement("tr");
                
                    row.innerHTML = `
                        <td>${book.id}</td>
                        <td>${book.titre}</td>
                        <td>${book.auteur}</td>
                        <td>${book.genre}</td>
                        <td>${book.prix}€</td>
                    `;
                
                    // Cellule image
                    const imageCell = document.createElement("td");
                    const img = document.createElement("img");
                    console.log("Image path:", book.image);
                    img.src = `../${book.image}`; 
                    img.alt = "Couverture";
                    img.classList.add("book-thumbnail");
                    imageCell.appendChild(img);
                    row.appendChild(imageCell);
                
                    // Bouton de suppression
                    const actionCell = document.createElement("td");
                    const deleteBtn = document.createElement("button");
                    deleteBtn.classList.add("delete-book");
                    deleteBtn.dataset.id = book.id;
                    deleteBtn.textContent = "Supprimer";
                    actionCell.appendChild(deleteBtn);
                    row.appendChild(actionCell);
                
                    bookTable.appendChild(row);
                });
                
            });
    };

    const fetchAdmins = () => {
        fetch("get_admins.php")
            .then(res => res.json())
            .then(data => {
                adminTable.innerHTML = "";
                data.forEach(admin => {
                    adminTable.innerHTML += `
                        <tr>
                            <td>${admin.id}</td>
                            <td>${admin.nom}</td>
                            <td>${admin.email}</td>
                            <td><button class="delete-admin" data-id="${admin.id}">Supprimer</button></td>
                        </tr>
                    `;
                });
            });
    };

    fetchBooks();
    fetchAdmins();

    document.getElementById("bookForm").addEventListener("submit", e => {
        e.preventDefault();
        const book = {
            titre: document.getElementById("bookTitle").value,
            auteur: document.getElementById("bookAuthor").value,
            genre: document.getElementById("bookGenre").value,
            prix: parseFloat(document.getElementById("bookPrice").value)
        };
        fetch("add_book.php", {
            method: "POST",
            body: JSON.stringify(book)
        }).then(() => {
            fetchBooks();
            document.getElementById("bookModal").style.display = "none";
        });
    });

    document.getElementById("adminForm").addEventListener("submit", e => {
        e.preventDefault();
        const admin = {
            nom: document.getElementById("adminName").value,
            email: document.getElementById("adminEmail").value
        };
        fetch("add_admin.php", {
            method: "POST",
            body: JSON.stringify(admin)
        }).then(() => {
            fetchAdmins();
            document.getElementById("adminModal").style.display = "none";
        });
    });

    document.addEventListener("click", e => {
        if (e.target.classList.contains("delete-book")) {
            const id = e.target.dataset.id;
            fetch("delete_book.php", {
                method: "POST",
                body: JSON.stringify({ id })
            }).then(() => fetchBooks());
        }

        if (e.target.classList.contains("delete-admin")) {
            const id = e.target.dataset.id;
            fetch("delete_admin.php", {
                method: "POST",
                body: JSON.stringify({ id })
            }).then(() => fetchAdmins());
        }
    });

    // Modal logic 
    const modals = document.querySelectorAll(".modal");
    const closes = document.querySelectorAll(".close");

    closes.forEach(close => {
        close.onclick = () => modals.forEach(m => m.style.display = "none");
    });

    window.onclick = e => {
        if (e.target.classList.contains("modal")) {
            modals.forEach(m => m.style.display = "none");
        }
    };

    document.getElementById("addBookBtn").onclick = () => {
        document.getElementById("bookModal").style.display = "flex";
    };

    document.getElementById("addAdminBtn").onclick = () => {
        document.getElementById("adminModal").style.display = "flex";
    };

    document.getElementById("logout").onclick = () => {
        window.location.href = "logout.php";
    };
});


