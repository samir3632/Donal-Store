<?php
require_once __DIR__ . "/conexion.php";
header("Content-Type: application/json; charset=utf-8");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["ok" => false, "msg" => "Método no permitido"]);
    exit;
}

$raw = file_get_contents("php://input");
$data = json_decode($raw, true);

if (!is_array($data)) {
    http_response_code(400);
    echo json_encode(["ok" => false, "msg" => "JSON inválido"]);
    exit;
}

$email = trim($data["email"] ?? "");
$metodoPago = trim($data["metodo_pago"] ?? "");
$numeroReferencia = trim($data["numero_referencia"] ?? "");
$carrito = $data["carrito"] ?? [];

$metodosPermitidos = ["tarjeta", "pse", "efectivo"];

if ($email === "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(422);
    echo json_encode(["ok" => false, "msg" => "Email inválido"]);
    exit;
}

if (!in_array($metodoPago, $metodosPermitidos, true)) {
    http_response_code(422);
    echo json_encode(["ok" => false, "msg" => "Método de pago inválido"]);
    exit;
}

if (!is_array($carrito) || count($carrito) === 0) {
    http_response_code(422);
    echo json_encode(["ok" => false, "msg" => "El carrito está vacío"]);
    exit;
}

try {
    $pdo = ConexionBaseDatos();
    $pdo->beginTransaction();

    $stmtUser = $pdo->prepare("SELECT id, nombre, email, balance FROM usuarios WHERE email = :email LIMIT 1");
    $stmtUser->execute([":email" => $email]);
    $usuario = $stmtUser->fetch();

    if (!$usuario) {
        throw new Exception("Usuario no encontrado");
    }

    $itemsNormalizados = [];
    $subtotal = 0.0;

    $stmtProducto = $pdo->prepare("SELECT id, nombre, precio, stock FROM productos WHERE id = :id LIMIT 1");

    foreach ($carrito as $item) {
        $productoId = (int)($item["id"] ?? 0);
        $cantidad = (int)($item["cantidad"] ?? 0);

        if ($productoId <= 0 || $cantidad <= 0) {
            throw new Exception("Producto o cantidad inválida");
        }

        $stmtProducto->execute([":id" => $productoId]);
        $producto = $stmtProducto->fetch();

        if (!$producto) {
            throw new Exception("Producto no encontrado: " . $productoId);
        }

        if ((int)$producto["stock"] < $cantidad) {
            throw new Exception("Stock insuficiente para " . $producto["nombre"]);
        }

        $precioUnitario = (float)$producto["precio"];
        $lineTotal = $precioUnitario * $cantidad;
        $subtotal += $lineTotal;

        $itemsNormalizados[] = [
            "producto_id" => (int)$producto["id"],
            "cantidad" => $cantidad,
            "precio_unitario" => $precioUnitario
        ];
    }

    $envio = $subtotal > 0 ? 10000 : 0;
    $total = $subtotal + $envio;

    if ($metodoPago !== "efectivo" && (float)$usuario["balance"] < $total) {
        throw new Exception("Fondos insuficientes. Balance actual: " . number_format((float)$usuario["balance"], 2));
    }

    $stmtPedido = $pdo->prepare("
        INSERT INTO pedidos (usuario_id, total)
        VALUES (:usuario_id, :total)
    ");
    $stmtPedido->execute([
        ":usuario_id" => (int)$usuario["id"],
        ":total" => $total
    ]);

    $pedidoId = (int)$pdo->lastInsertId();

    $stmtDetalle = $pdo->prepare("
        INSERT INTO detalle_pedido (pedido_id, producto_id, cantidad, precio_unitario)
        VALUES (:pedido_id, :producto_id, :cantidad, :precio_unitario)
    ");

    $stmtStock = $pdo->prepare("
        UPDATE productos
        SET stock = stock - :cantidad
        WHERE id = :id
    ");

    foreach ($itemsNormalizados as $it) {
        $stmtDetalle->execute([
            ":pedido_id" => $pedidoId,
            ":producto_id" => $it["producto_id"],
            ":cantidad" => $it["cantidad"],
            ":precio_unitario" => $it["precio_unitario"]
        ]);

        $stmtStock->execute([
            ":cantidad" => $it["cantidad"],
            ":id" => $it["producto_id"]
        ]);
    }

    $referencia = $numeroReferencia !== "" ? $numeroReferencia : "SIM-" . strtoupper(uniqid());

    $stmtTrans = $pdo->prepare("
        INSERT INTO transacciones (pedido_id, referencia, monto, metodo_pago, estado)
        VALUES (:pedido_id, :referencia, :monto, :metodo_pago, :estado)
    ");
    $stmtTrans->execute([
        ":pedido_id" => $pedidoId,
        ":referencia" => $referencia,
        ":monto" => $total,
        ":metodo_pago" => $metodoPago,
        ":estado" => "aprobada"
    ]);

    if ($metodoPago !== "efectivo") {
        $stmtSaldo = $pdo->prepare("
            UPDATE usuarios
            SET balance = balance - :monto
            WHERE id = :id
        ");
        $stmtSaldo->execute([
            ":monto" => $total,
            ":id" => (int)$usuario["id"]
        ]);
    }

    $pdo->commit();

    echo json_encode([
        "ok" => true,
        "msg" => "Pago simulado aprobado",
        "pedido_id" => $pedidoId,
        "referencia" => $referencia,
        "total" => $total
    ]);
} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(400);
    echo json_encode([
        "ok" => false,
        "msg" => $e->getMessage()
    ]);
}
?>