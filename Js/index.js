let carrito = JSON.parse(localStorage.getItem("carrito")) || [];

function AgregarAlCarrito(id, nombre, precio) {
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

// ACTUALIZAR CANTIDAD
function cambiarCantidad(id, cantidad) {
  const producto = carrito.find(item => item.id === id);

  if (producto) {
    producto.cantidad = cantidad;

    if (producto.cantidad <= 0) {
      eliminarProducto(id);
    }
  }

  guardarCarrito();
  actualizarCarrito();
}

// CALCULAR TOTAL
function calcularTotal() {
  return carrito.reduce((total, item) => {
    return total + item.precio * item.cantidad;
  }, 0);
}

// GUARDAR EN LOCALSTORAGE
function guardarCarrito() {
  localStorage.setItem("carrito", JSON.stringify(carrito));
}

// MOSTRAR CARRITO
function actualizarCarrito() {
  const contenedor = document.getElementById("carrito");
  const total = document.getElementById("total");

  if (!contenedor) return;

  contenedor.innerHTML = "";

  carrito.forEach(item => {
    contenedor.innerHTML += `
      <div>
        <h4>${item.nombre}</h4>
        <p>Precio: $${item.precio}</p>
        <p>Cantidad: 
          <input type="number" min="1" value="${item.cantidad}" 
          onchange="cambiarCantidad(${item.id}, this.value)">
        </p>
        <button onclick="eliminarProducto(${item.id})">Eliminar</button>
      </div>
      <hr>
    `;
  });

  if (total) {
    total.textContent = "Total: $" + calcularTotal();
  }
}

// INICIAR AL CARGAR
document.addEventListener("DOMContentLoaded", actualizarCarrito);








