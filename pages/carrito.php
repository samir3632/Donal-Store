<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Carrito | Donal Store</title>
  <link rel="stylesheet" href="../styles/carrito.css" />
</head>

<body>
  <header class="site-header">
    <div class="container header-inner">
      <a class="brand" href="home.php" aria-label="Donal Store - Inicio">
        <span class="brand-mark">DS</span>
        <span class="brand-name">Donal Store</span>
      </a>

      <nav class="nav" aria-label="Navegación principal">
        <a href="home.php" class="nav-link">Inicio</a>
        <a href="products.php" class="nav-link">Tienda</a>
        <a href="carrito.php" class="nav-link is-active">Carrito</a>
      </nav>

      <div class="header-actions">
        <a class="btn btn-ghost" href="products.php">Seguir comprando</a>
      </div>
    </div>
  </header>

  <main class="section">
    <div class="container">
      <div class="page-head">
        <div>
          <h1 class="page-title">Tu carrito</h1>
          <p class="page-subtitle">Revisa tus productos y finaliza la compra cuando estés listo.</p>
        </div>

        <button id="clearCart" class="btn btn-ghost btn-danger" type="button">
          Vaciar carrito
        </button>
      </div>

      <div class="cart-layout">
        <section class="panel" aria-label="Productos en el carrito">
          <div class="panel-head">
            <h2 class="panel-title">Productos</h2>
            <span class="pill" id="itemsCountPill">0 items</span>
          </div>

          <div id="cartList" class="cart-list"></div>

          <div id="emptyState" class="empty" hidden>
            <div class="empty-card">
              <div class="empty-title">Tu carrito está vacío</div>
              <div class="empty-text">Agrega productos para verlos aquí.</div>
              <a class="btn btn-primary" href="products.php">Ir a productos</a>
            </div>
          </div>
        </section>

        <aside class="panel summary" aria-label="Resumen de compra">
          <h2 class="panel-title">Resumen</h2>

          <div class="summary-row">
            <span>Subtotal</span>
            <strong id="subtotal">$0.00</strong>
          </div>

          <div class="summary-row">
            <span>Envío</span>
            <strong id="shipping">$0.00</strong>
          </div>

          <div class="divider"></div>

          <div class="summary-row total">
            <span>Total</span>
            <strong id="total">$0.00</strong>
          </div>

          <button class="btn btn-primary btn-full" type="button" id="checkoutBtn">
            Finalizar compra
          </button>

          <p class="fineprint">
            Al continuar, aceptas los términos y condiciones. (Demo)
          </p>
        </aside>
      </div>
    </div>
  </main>

  <footer class="footer">
    <div class="container footer-inner">
      <div>
        <div class="footer-brand">Donal Store</div>
        <div class="footer-muted">© <span id="year">2026</span> Todos los derechos reservados.</div>
      </div>

      <div class="footer-links">
        <a href="home.php">Inicio</a>
        <a href="products.php">Tienda</a>
        <a href="carrito.php">Carrito</a>
      </div>
    </div>
  </footer>

  <script src="../Js/carrito.js"></script>
</body>
</html>