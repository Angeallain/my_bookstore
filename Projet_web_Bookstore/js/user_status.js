document.addEventListener("DOMContentLoaded", () => {
  const dot = document.querySelector(".status-dot");
  const username = document.getElementById("user-name");

  if (dot && username) {
    const isConnected = username.textContent.trim().toLowerCase() !== "se connecter";
    if (isConnected) {
      dot.classList.add("connected");
    } else {
      dot.classList.remove("connected");
    }
  }
});
