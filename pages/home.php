<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Donal Store | Moda</title>
  <link rel="stylesheet" href="../styles/home.css" />
</head>

<body>
  <header class="site-header">
    <div class="container header-inner">
      <a class="brand" href="home.php" aria-label="Donal Store - Inicio">
        <span class="brand-mark">DS</span>
        <span class="brand-name">Donal Store</span>
      </a>

      <nav class="nav" aria-label="Navegación principal">
        <a href="home.php" class="nav-link is-active">Inicio</a>
        <a href="products.php" class="nav-link">Tienda</a>
        <a href="carrito.php" class="nav-link">Carrito</a>
      </nav>

      <div class="header-actions">
        <a class="btn btn-ghost" href="products.php">Ver colección</a>
        <a class="btn btn-primary" href="carrito.php">Ir al carrito</a>
      </div>
    </div>
  </header>

  <main>
    <section class="hero">
      <div class="container hero-grid">
        <div class="hero-content">
          <p class="badge">Nueva temporada • Envíos 2–5 días • Pagos seguros</p>
          <h1 class="hero-title">Moda que se ve bien. Y se siente mejor.</h1>
          <p class="hero-subtitle">
            Colecciones para <strong>mujer</strong>, <strong>hombre</strong> y <strong>accesorios</strong>.
            Estilo urbano, casual y básico premium.
          </p>

          <div class="hero-cta">
            <a class="btn btn-primary" href="products.php">Comprar ahora</a>
            <a class="btn btn-ghost" href="#categorias">Ver categorías</a>
          </div>

          <div class="hero-stats" role="list">
            <div class="stat" role="listitem">
              <div class="stat-number">Free</div>
              <div class="stat-label">Cambios (demo)</div>
            </div>
            <div class="stat" role="listitem">
              <div class="stat-number">-20%</div>
              <div class="stat-label">Semana de ofertas</div>
            </div>
            <div class="stat" role="listitem">
              <div class="stat-number">Top</div>
              <div class="stat-label">Calidad/Precio</div>
            </div>
          </div>
        </div>

        <div class="hero-card hero-card-fashion" aria-hidden="true">
          <div class="hero-card-inner">
            <div class="mock-top">
              <span class="dot dot-red"></span>
              <span class="dot dot-yellow"></span>
              <span class="dot dot-green"></span>
            </div>
            <div class="mock-body">
              <div class="mock-line w80"></div>
              <div class="mock-line w60"></div>
              <div class="mock-line w90"></div>
              <div class="mock-grid">
                <div class="mock-tile"></div>
                <div class="mock-tile"></div>
                <div class="mock-tile"></div>
                <div class="mock-tile"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="section" id="categorias">
      <div class="container">
        <div class="section-head">
          <h2 class="section-title">Compra por categoría</h2>
          <p class="section-subtitle">Todo lo que necesitas para armar tu outfit.</p>
        </div>

        <div class="cards-3">
          <a class="card" href="products.php">
            <div class="card-icon">♀</div>
            <h3 class="card-title">Mujer</h3>
            <p class="card-text">Vestidos, tops, jeans, básicos y más.</p>
          </a>

          <a class="card" href="products.php">
            <div class="card-icon">♂</div>
            <h3 class="card-title">Hombre</h3>
            <p class="card-text">Camisas, pantalones, hoodies y denim.</p>
          </a>

          <a class="card" href="products.php">
            <div class="card-icon">⌁</div>
            <h3 class="card-title">Accesorios</h3>
            <p class="card-text">Gorras, bolsos, cinturones y más.</p>
          </a>
        </div>
      </div>
    </section>

    <section class="section section-muted" id="destacados">
      <div class="container">
        <div class="section-head">
          <h2 class="section-title">Destacados</h2>
          <p class="section-subtitle">Piezas clave para combinar fácil.</p>
        </div>

        <div class="product-grid">
          <article class="product">
            <div class="product-media"></div>
            <div class="product-body">
              <h3 class="product-title">Camiseta Básica Premium</h3>
              <p class="product-desc">Algodón suave, fit cómodo y colores neutros.</p>
              <div class="product-row">
                <span class="price">$19.99</span>
                <a class="btn btn-small btn-primary" href="products.php">Ver</a>
              </div>
            </div>
          </article>

          <article class="product">
            <div class="product-media"></div>
            <div class="product-body">
              <h3 class="product-title">Hoodie Oversize</h3>
              <p class="product-desc">Calidez y estilo urbano para diario.</p>
              <div class="product-row">
                <span class="price">$39.99</span>
                <a class="btn btn-small btn-primary" href="products.php">Ver</a>
              </div>
            </div>
          </article>

          <article class="product">
            <div class="product-media"></div>
            <div class="product-body">
              <h3 class="product-title">Jeans Slim Fit</h3>
              <p class="product-desc">Corte moderno, ideal para cualquier look.</p>
              <div class="product-row">
                <span class="price">$29.99</span>
                <a class="btn btn-small btn-primary" href="products.php">Ver</a>
              </div>
            </div>
          </article>

          <article class="product">
            <div class="product-media"></div>
            <div class="product-body">
              <h3 class="product-title">Bolso/Accesorio</h3>
              <p class="product-desc">Complemento perfecto para elevar tu outfit.</p>
              <div class="product-row">
                <span class="price">$14.99</span>
                <a class="btn btn-small btn-primary" href="products.php">Ver</a>
              </div>
            </div>
          </article>
        </div>
      </div>
    </section>

    <section class="section">
      <div class="container">
        <div class="split">
          <div class="panel">
            <h2 class="section-title">Compra con confianza</h2>
            <p class="section-subtitle">
              Experiencia simple, diseño moderno y todo listo para escalar tu tienda.
            </p>
            <ul class="list">
              <li><strong>Tallas y guía</strong> (puedes agregarla en products)</li>
              <li><strong>Pagos seguros</strong> y checkout claro</li>
              <li><strong>Envíos</strong> con seguimiento (demo)</li>
            </ul>
            <div class="hero-cta">
              <a class="btn btn-primary" href="products.php">Ir a la tienda</a>
              <a class="btn btn-ghost" href="carrito.php">Ver carrito</a>
            </div>
          </div>

          <div class="panel panel-glass" aria-hidden="true">
            <div class="mini-cards">
              <div class="mini-card">
                <div class="mini-title">Mujer</div>
                <div class="mini-text">Looks casual y street.</div>
              </div>
              <div class="mini-card">
                <div class="mini-title">Hombre</div>
                <div class="mini-text">Básicos premium.</div>
              </div>
              <div class="mini-card">
                <div class="mini-title">Accesorios</div>
                <div class="mini-text">Detalles que suman.</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <footer class="footer">
    <div class="container footer-inner">
      <div class="footer-left">
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
</body>
</html>