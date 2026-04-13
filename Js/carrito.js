let carrito = JSON.parse(localStorage.getItem("carrito")) || [];

function eliminarProducto(id) {
  carrito = carrito.filter(item => String(item.id) !== String(id));
  guardarCarrito();
  actualizarCarrito();
}

function cambiarCantidad(id, cantidad) {
  cantidad = parseInt(cantidad, 10);

  const producto = carrito.find(item => String(item.id) === String(id));
  if (producto) {
    producto.cantidad = Number.isFinite(cantidad) ? cantidad : 1;
    if (producto.cantidad <= 0) {
      eliminarProducto(id);
      return;
    }
  }

  guardarCarrito();
  actualizarCarrito();
}

function guardarCarrito() {
  localStorage.setItem("carrito", JSON.stringify(carrito));
}

function money(n) {
  return "$" + Number(n || 0).toFixed(2);
}

function actualizarCarrito() {
  const contenedor = document.getElementById("cartList");
  const subtotalEl = document.getElementById("subtotal");
  const totalEl = document.getElementById("total");
  const contador = document.getElementById("itemsCountPill");
  const emptyState = document.getElementById("emptyState");
  const shippingEl = document.getElementById("shipping");

  if (!contenedor) return;

  contenedor.innerHTML = "";

  let subtotal = 0;
  let totalItems = 0;

  if (carrito.length === 0) emptyState.hidden = false;
  else emptyState.hidden = true;

  carrito.forEach(item => {
    const qty = Math.max(1, parseInt(item.cantidad, 10) || 1);
    item.cantidad = qty;

    const price = Number(item.precio || 0);
    const lineTotal = price * qty;

    subtotal += lineTotal;
    totalItems += qty;

    contenedor.innerHTML += `
      <article class="cart-item">
        <div class="thumb"></div>
        <div class="item-info">
          <div class="item-top">
            <div>
              <h3 class="item-title">${item.nombre}</h3>
              <p class="item-meta">Producto</p>
            </div>
            <button class="icon-btn remove" type="button" onclick="eliminarProducto('${item.id}')">✕</button>
          </div>
          <div class="item-bottom">
            <div class="qty">
              <button class="qty-btn dec" type="button" onclick="cambiarCantidad('${item.id}', ${qty - 1})">−</button>
              <input class="qty-input" type="number" min="1" value="${qty}" onchange="cambiarCantidad('${item.id}', this.value)">
              <button class="qty-btn inc" type="button" onclick="cambiarCantidad('${item.id}', ${qty + 1})">+</button>
            </div>
            <div class="price-block">
              <div class="unit">Unit: ${money(price)}</div>
              <div class="line-price">${money(lineTotal)}</div>
            </div>
          </div>
        </div>
      </article>
    `;
  });

  const shipping = subtotal > 0 ? 10000 : 0;
  const total = subtotal + shipping;

  if (subtotalEl) subtotalEl.textContent = money(subtotal);
  if (shippingEl) shippingEl.textContent = money(shipping);
  if (totalEl) totalEl.textContent = money(total);
  if (contador) contador.textContent = totalItems + (totalItems === 1 ? " item" : " items");

  guardarCarrito();
}

function mostrarEstadoCheckout(msg, ok) {
  let box = document.getElementById("checkoutStatus");
  if (!box) {
    box = document.createElement("div");
    box.id = "checkoutStatus";
    box.style.marginTop = "10px";
    box.style.padding = "10px";
    box.style.borderRadius = "10px";
    box.style.fontWeight = "700";
    const btn = document.getElementById("checkoutBtn");
    btn?.insertAdjacentElement("afterend", box);
  }
  box.textContent = msg;
  box.style.background = ok ? "rgba(34,197,94,.12)" : "rgba(239,68,68,.12)";
  box.style.color = ok ? "#166534" : "#991b1b";
  box.style.border = ok ? "1px solid rgba(34,197,94,.25)" : "1px solid rgba(239,68,68,.25)";
}

document.addEventListener("DOMContentLoaded", () => {
  actualizarCarrito();

  document.getElementById("clearCart")?.addEventListener("click", () => {
    carrito = [];
    guardarCarrito();
    actualizarCarrito();
  });

  document.getElementById("checkoutBtn")?.addEventListener("click", async () => {
    if (carrito.length === 0) {
      mostrarEstadoCheckout("El carrito está vacío.", false);
      return;
    }

    const email = document.getElementById("checkoutEmail")?.value?.trim() || "";
    const metodo = document.getElementById("checkoutMetodo")?.value || "";
    const ref = document.getElementById("checkoutRef")?.value?.trim() || "";

    if (!email) {
      mostrarEstadoCheckout("Debes ingresar un correo.", false);
      return;
    }

    try {
      const resp = await fetch("../php/checkout.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          email: email,
          metodo_pago: metodo,
          numero_referencia: ref,
          carrito: carrito.map(i => ({ id: i.id, cantidad: i.cantidad }))
        })
      });

      const data = await resp.json();

      if (!resp.ok || !data.ok) {
        mostrarEstadoCheckout(data.msg || "No se pudo procesar la compra.", false);
        return;
      }

      carrito = [];
      guardarCarrito();
      actualizarCarrito();

      const q = new URLSearchParams({
        pedido: String(data.pedido_id || ""),
        ref: String(data.referencia || ""),
        total: String(data.total || 0),
        metodo: metodo
      }).toString();

      window.location.href = `success.php?${q}`;
    } catch (e) {
      mostrarEstadoCheckout("Error de red al procesar la compra.", false);
    }
  });
});