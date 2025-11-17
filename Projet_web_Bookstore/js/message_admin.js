const messagesTable = document.getElementById("messagesTableBody");

const fetchMessages = () => {
    fetch("get_message.php")
        .then(res => res.json())
        .then(data => {
            messagesTable.innerHTML = "";
            data.forEach(msg => {
                messagesTable.innerHTML += `
                    <tr>
                        <td>${msg.id}</td>
                        <td>${msg.nom}</td>
                        <td>${msg.email}</td>
                        <td>${msg.message}</td>
                        <td>${new Date(msg.date_envoi).toLocaleString()}</td>
                        <td>
                           ${new Date(msg.date_envoi).toLocaleString()}<br>
                           <button class="delete-message" data-id="${msg.id}">Supprimer</button>
                        </td>
                    </tr>
                `;
            });
        });
};

fetchMessages(); // Appel initial

document.addEventListener("click", (e) => {
    if (e.target.classList.contains("delete-message")) {
        const id = e.target.dataset.id;

        fetch("delete_message.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ id })
        }).then(() => fetchMessages());
    }
});


