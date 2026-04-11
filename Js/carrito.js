// CARGAR CARRITO DESDE LOCALSTORAGE
let carrito = JSON.parse(localStorage.getItem("carrito")) || [];

// ELIMINAR PRODUCTO
function eliminarProducto(id) {
  carrito = carrito.filter(item => String(item.id) !== String(id));
  guardarCarrito();
  actualizarCarrito();
}

// CAMBIAR CANTIDAD
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

// GUARDAR EN LOCALSTORAGE
function guardarCarrito() {
  localStorage.setItem("carrito", JSON.stringify(carrito));
}

// FORMATO DINERO
function money(n) {
  return "$" + Number(n || 0).toFixed(2);
}

// ACTUALIZAR CARRITO (ADAPTADO A TU HTML)
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

  if (carrito.length === 0) {
    emptyState.hidden = false;
  } else {
    emptyState.hidden = true;
  }

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

            <button class="icon-btn remove" type="button" onclick="eliminarProducto('${item.id}')">
              ✕
            </button>
          </div>

          <div class="item-bottom">
            <div class="qty">
              <button class="qty-btn dec" type="button" onclick="cambiarCantidad('${item.id}', ${qty - 1})">−</button>

              <input class="qty-input" type="number" min="1" value="${qty}"
                onchange="cambiarCantidad('${item.id}', this.value)">

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

  const shipping = subtotal > 0 ? 10 : 0;
  const total = subtotal + shipping;

  if (subtotalEl) subtotalEl.textContent = money(subtotal);
  if (shippingEl) shippingEl.textContent = money(shipping);
  if (totalEl) totalEl.textContent = money(total);

  if (contador) {
    contador.textContent = totalItems + (totalItems === 1 ? " item" : " items");
  }

  guardarCarrito();
}

// EVENTOS AL CARGAR
document.addEventListener("DOMContentLoaded", () => {
  actualizarCarrito();

  document.getElementById("clearCart")?.addEventListener("click", () => {
    carrito = [];
    guardarCarrito();
    actualizarCarrito();
  });

  document.getElementById("checkoutBtn")?.addEventListener("click", () => {
    if (carrito.length === 0) {
      alert("El carrito está vacío");
      return;
    }

    alert("Compra realizada (demo)");
    carrito = [];
    guardarCarrito();
    actualizarCarrito();
  });
});