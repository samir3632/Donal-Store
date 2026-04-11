document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".add-to-cart").forEach((btn) => {
    btn.addEventListener("click", () => {
      const card = btn.closest(".product-card");
      const id = card.dataset.id;
      const nombre = card.dataset.name;
      const precio = parseFloat(card.dataset.price);

      let carrito = JSON.parse(localStorage.getItem("carrito")) || [];
      const existe = carrito.find((p) => String(p.id) === String(id));

      if (existe) existe.cantidad++;
      else carrito.push({ id, nombre, precio, cantidad: 1 });

      localStorage.setItem("carrito", JSON.stringify(carrito));

      btn.textContent = "Agregado";
      setTimeout(() => (btn.textContent = "Agregar al carrito"), 700);
    });
  });
});