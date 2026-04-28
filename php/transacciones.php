<?php
require_once __DIR__ . "/conexion.php";
require_once __DIR__ . "/payments/PaymentMethodFactory.php";

header("Content-Type: application/json; charset=utf-8");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["ok" => false, "msg" => "Método no permitido"]);
    exit;
}

$email = trim($_POST["email"] ?? "");
$metodoPago = trim($_POST["metodo_pago"] ?? "");
$numeroReferencia = trim($_POST["numero_referencia"] ?? "");
$productoId = (int)($_POST["producto_id"] ?? 0);
$cantidad = (int)($_POST["cantidad"] ?? 0);

$paymentMethod = PaymentMethodFactory::create($metodoPago);

if ($email === "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(422);
    echo json_encode(["ok" => false, "msg" => "Email inválido"]);
    exit;
}

if (!$paymentMethod) {
    http_response_code(422);
    echo json_encode(["ok" => false, "msg" => "Método de pago inválido"]);
    exit;
}

if ($productoId <= 0 || $cantidad <= 0) {
    http_response_code(422);
    echo json_encode(["ok" => false, "msg" => "Producto o cantidad inválida"]);
    exit;
}

try {
    $conexion = ConexionBaseDatos();
    $conexion->beginTransaction();

    $stmtUser = $conexion->prepare("SELECT id, balance FROM usuarios WHERE email = :email LIMIT 1");
    $stmtUser->execute([":email" => $email]);
    $usuario = $stmtUser->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        throw new Exception("Usuario no encontrado");
    }

    $sql = "SELECT nombre, precio, stock
            FROM productos
            WHERE id = :id";

    $stmt = $conexion->prepare($sql);
    $stmt->execute([":id" => $productoId]);
    $producto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$producto) {
        throw new Exception("Producto no encontrado");
    }

    if ((int)$producto["stock"] < $cantidad) {
        throw new Exception("Stock insuficiente");
    }

    $subtotal = (float)$producto["precio"] * $cantidad;
    $envio = $subtotal > 0 ? 10000 : 0;
    $total = $subtotal + $envio;

    if ($paymentMethod->isBalanceRequired() && (float)$usuario["balance"] < $total) {
        throw new Exception("Fondos insuficientes. Balance actual: " . number_format((float)$usuario["balance"], 2));
    }

    $sqlPedido = "INSERT INTO pedidos (usuario_id, total)
                 VALUES (:usuario_id, :total)";

    $stmt = $conexion->prepare($sqlPedido);
    $stmt->execute([
        ":usuario_id" => (int)$usuario["id"],
        ":total" => $total
    ]);

    $pedidoId = (int)$conexion->lastInsertId();

    $sqlDetalle = "INSERT INTO detalle_pedido
                   (pedido_id, producto_id, cantidad, precio_unitario)
                   VALUES
                   (:pedido_id, :producto_id, :cantidad, :precio_unitario)";

    $stmt = $conexion->prepare($sqlDetalle);
    $stmt->execute([
        ":pedido_id" => $pedidoId,
        ":producto_id" => $productoId,
        ":cantidad" => $cantidad,
        ":precio_unitario" => (float)$producto["precio"]
    ]);

    $referencia = $numeroReferencia !== "" ? $numeroReferencia : "SIM-" . strtoupper(uniqid());

    $sqlTrans = "INSERT INTO transacciones
                 (pedido_id, referencia, monto, metodo_pago, estado)
                 VALUES
                 (:pedido_id, :referencia, :monto, :metodo_pago, :estado)";

    $stmt = $conexion->prepare($sqlTrans);
    $stmt->execute([
        ":pedido_id" => $pedidoId,
        ":referencia" => $referencia,
        ":monto" => $total,
        ":metodo_pago" => $paymentMethod->getCode(),
        ":estado" => "aprobada"
    ]);

    if ($paymentMethod->isBalanceRequired()) {
        $sqlSaldo = "UPDATE usuarios
                     SET balance = balance - :monto
                     WHERE id = :id";
        $stmt = $conexion->prepare($sqlSaldo);
        $stmt->execute([
            ":monto" => $total,
            ":id" => (int)$usuario["id"]
        ]);
    }

    $sqlStock = "UPDATE productos
                 SET stock = stock - :cantidad
                 WHERE id = :id";

    $stmt = $conexion->prepare($sqlStock);
    $stmt->execute([
        ":cantidad" => $cantidad,
        ":id" => $productoId
    ]);

    $conexion->commit();

    echo json_encode([
        "ok" => true,
        "msg" => "Transacción simulada aprobada",
        "pedido_id" => $pedidoId,
        "referencia" => $referencia,
        "total" => $total
    ]);
} catch (Exception $e) {
    if (isset($conexion) && $conexion->inTransaction()) {
        $conexion->rollBack();
    }
    http_response_code(400);
    echo json_encode(["ok" => false, "msg" => $e->getMessage()]);
}
?>