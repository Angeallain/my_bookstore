/*-------------------------------------------------------------------------------------*/
// Gestion d'affichage des messages

document.addEventListener("DOMContentLoaded", () => {
    const contactForm = document.getElementById("contactForm");
    if (!contactForm) return;

    contactForm.addEventListener("submit", function (e) {
        e.preventDefault();

        const formData = new FormData(contactForm);

        fetch("send_message.php", {
            method: "POST",
            body: formData,
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message);
            if (data.success) contactForm.reset();
        })
        .catch(err => {
            console.error("Erreur lors de l'envoi :", err);
            alert("Une erreur est survenue.");
        });
    });
});