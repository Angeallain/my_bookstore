// stock_orders_admin.js

document.addEventListener("DOMContentLoaded", () => {
  const stockTable = document.getElementById("stockTableBody");
  const ordersTable = document.getElementById("ordersTableBody");

  const fetchStock = () => {
    fetch("get_stock.php")
      .then(res => res.json())
      .then(data => {
        stockTable.innerHTML = "";
        data.forEach(book => {
          stockTable.innerHTML += `
            <tr>
              <td>${book.id}</td>
              <td>${book.titre}</td>
              <td><input type="number" min="0" value="${book.stock}" data-id="${book.id}" class="stock-input"></td>
              <td><button class="update-stock" data-id="${book.id}">Mettre à jour</button></td>
            </tr>
          `;
        });
      });
  };

  const fetchOrders = () => {
    fetch("get_all_orders.php")
      .then(res => res.json())
      .then(data => {
        ordersTable.innerHTML = "";
        data.forEach(order => {
          const isPending = order.statut === "en attente";
          ordersTable.innerHTML += `
            <tr>
              <td>${order.id}</td>
              <td>${order.client}</td>
              <td>${new Date(order.date_commande).toLocaleString()}</td>
              <td>${order.total}€</td>
              <td>${order.statut}</td>
              <td>
                ${isPending ? `<button class="ship-order" data-id="${order.id}">Expédier</button>` : "-"}
              </td>
            </tr>
          `;
        });
      });
  };

  document.addEventListener("click", e => {
    if (e.target.classList.contains("update-stock")) {
      const id = e.target.dataset.id;
      const input = document.querySelector(`input[data-id='${id}']`);
      const stock = parseInt(input.value);

      fetch("update_stock.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id, stock })
      }).then(() => fetchStock());
    }

    if (e.target.classList.contains("ship-order")) {
      const id = e.target.dataset.id;
      fetch("mark_order_shipped.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id })
      }).then(() => fetchOrders());
    }
  });

  fetchStock();
  fetchOrders();
});
