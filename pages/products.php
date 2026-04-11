<?php
require_once __DIR__ . "/../php/conexion.php";
$pdo = ConexionBaseDatos();

$stmt = $pdo->query("SELECT id, nombre, descripcion, precio, stock FROM productos ORDER BY id DESC");
$productos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tienda | Donal Store</title>
  <link rel="stylesheet" href="../styles/productos.css" />
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
        <a href="products.php" class="nav-link is-active">Tienda</a>
        <a href="carrito.php" class="nav-link">Carrito</a>
      </nav>

      <div class="header-actions">
        <a class="btn btn-ghost" href="carrito.php">Ver carrito</a>
      </div>
    </div>
  </header>

  <main class="section">
    <div class="container">
      <div class="page-head">
        <div>
          <h1 class="page-title">Tienda</h1>
          <p class="page-subtitle">Productos cargados desde MySQL (XAMPP).</p>
        </div>

        <form class="search" action="#" method="get">
          <label class="sr-only" for="q">Buscar</label>
          <input id="q" class="input" type="search" placeholder="Buscar..." />
        </form>
      </div>

      <section class="products" aria-label="Listado de productos">
        <div class="products-head">
          <h2 class="products-title">Colección</h2>

          <div class="sort">
            <label class="sort-label" for="sort">Ordenar</label>
            <select id="sort" class="select">
              <option>Recomendados</option>
              <option>Precio: menor a mayor</option>
              <option>Precio: mayor a menor</option>
              <option>Novedades</option>
            </select>
          </div>
        </div>

        <div class="grid">
          <?php foreach ($productos as $p): ?>
            <article class="product-card"
              data-id="<?= (int)$p['id'] ?>"
              data-name="<?= htmlspecialchars($p['nombre'], ENT_QUOTES, 'UTF-8') ?>"
              data-price="<?= (float)$p['precio'] ?>"
            >
              <div class="media"></div>

              <div class="body">
                <div class="top">
                  <h3 class="title"><?= htmlspecialchars($p['nombre']) ?></h3>
                  <span class="tag">Ropa</span>
                </div>

                <p class="desc"><?= htmlspecialchars($p['descripcion'] ?? '') ?></p>

                <div class="bottom">
                  <span class="price">$<?= number_format((float)$p['precio'], 2) ?></span>
                  <button class="btn btn-primary btn-small add-to-cart" type="button">Agregar al carrito</button>
                </div>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      </section>
    </div>
  </main>

  <footer class="footer">
    <div class="container footer-inner">
      <div>
        <div class="footer-brand">Donal Store</div>
        <div class="footer-muted">© 2026 Todos los derechos reservados.</div>
      </div>

      <div class="footer-links">
        <a href="home.php">Inicio</a>
        <a href="products.php">Tienda</a>
        <a href="carrito.php">Carrito</a>
      </div>
    </div>
  </footer>

  <script src="../Js/products.js"></script>
</body>
</html>