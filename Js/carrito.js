// CARGAR CARRITO DESDE LOCALSTORAGE
let carrito = JSON.parse(localStorage.getItem("carrito")) || [];

// AGREGAR PRODUCTO
function agregarAlCarrito(id, nombre, precio) {
  const existe = carrito.find(item => item.id === id);

  if (existe) {
    existe.cantidad++;
  } else {
    carrito.push({ id, nombre, precio, cantidad: 1 });
  }

  guardarCarrito();
  actualizarCarrito();
}

// ELIMINAR PRODUCTO
function eliminarProducto(id) {
  carrito = carrito.filter(item => item.id !== id);
  guardarCarrito();
  actualizarCarrito();
}

// CAMBIAR CANTIDAD
function cambiarCantidad(id, cantidad) {
  cantidad = parseInt(cantidad);

  const producto = carrito.find(item => item.id === id);

  if (producto) {
    producto.cantidad = cantidad;

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

  let total = 0;
  let totalItems = 0;

  // Estado vacío
  if (carrito.length === 0) {
    emptyState.hidden = false;
  } else {
    emptyState.hidden = true;
  }

  // Renderizar productos
  carrito.forEach(item => {
    const lineTotal = item.precio * item.cantidad;
    total += lineTotal;
    totalItems += item.cantidad;

    contenedor.innerHTML += `
      <article class="cart-item">
        <div class="thumb"></div>

        <div class="item-info">
          <div class="item-top">
            <div>
              <h3 class="item-title">${item.nombre}</h3>
              <p class="item-meta">Producto</p>
            </div>

            <button class="icon-btn remove" onclick="eliminarProducto(${item.id})">
              ✕
            </button>
          </div>

          <div class="item-bottom">
            <div class="qty">
              <button class="qty-btn dec" onclick="cambiarCantidad(${item.id}, ${item.cantidad - 1})">−</button>

              <input class="qty-input" type="number" min="1" value="${item.cantidad}" 
              onchange="cambiarCantidad(${item.id}, this.value)">

              <button class="qty-btn inc" onclick="cambiarCantidad(${item.id}, ${item.cantidad + 1})">+</button>
            </div>

            <div class="price-block">
              <div class="unit">Unit: $${item.precio}</div>
              <div class="line-price">$${lineTotal.toFixed(2)}</div>
            </div>
          </div>
        </div>
      </article>
    `;
  });

  // Subtotal y total
  if (subtotalEl) subtotalEl.textContent = "$" + total.toFixed(2);
  if (totalEl) totalEl.textContent = "$" + total.toFixed(2);

  // Envío (lógica simple)
  if (shippingEl) {
    shippingEl.textContent = total > 0 ? "$10.00" : "$0.00";
  }

  // Contador de items
  if (contador) {
    contador.textContent = totalItems + " items";
  }
}

// EVENTOS AL CARGAR
document.addEventListener("DOMContentLoaded", () => {
  actualizarCarrito();

  // BOTÓN VACIAR CARRITO
  document.getElementById("clearCart")?.addEventListener("click", () => {
    carrito = [];
    guardarCarrito();
    actualizarCarrito();
  });

  // BOTÓN FINALIZAR COMPRA
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