<?php
$pedido = isset($_GET["pedido"]) ? (int)$_GET["pedido"] : 0;
$ref = isset($_GET["ref"]) ? trim($_GET["ref"]) : "";
$total = isset($_GET["total"]) ? (float)$_GET["total"] : 0;
$metodo = isset($_GET["metodo"]) ? trim($_GET["metodo"]) : "";
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Compra exitosa | Donal Store</title>
  <link rel="stylesheet" href="../styles/success.css" />
</head>
<body>
  <main class="wrap">
    <section class="card">
      <h1>Compra realizada</h1>
      <p class="sub">Tu transacción simulada fue aprobada.</p>

      <div class="grid">
        <div class="row">
          <span>Pedido</span>
          <strong>#<?= htmlspecialchars((string)$pedido, ENT_QUOTES, "UTF-8") ?></strong>
        </div>
        <div class="row">
          <span>Referencia</span>
          <strong><?= htmlspecialchars($ref, ENT_QUOTES, "UTF-8") ?></strong>
        </div>
        <div class="row">
          <span>Método</span>
          <strong><?= htmlspecialchars($metodo, ENT_QUOTES, "UTF-8") ?></strong>
        </div>
        <div class="row total">
          <span>Total</span>
          <strong>$<?= number_format($total, 2) ?></strong>
        </div>
      </div>

      <div class="actions">
        <a class="btn ghost" href="products.php">Seguir comprando</a>
        <a class="btn primary" href="home.php">Ir al inicio</a>
      </div>
    </section>
  </main>
</body>
</html>