<?php // Inicio de archivo PHP.
require_once __DIR__ . "/conexion.php"; // Cargamos la conexion a la base de datos.
require_once __DIR__ . "/payments/PaymentMethodFactory.php"; // Cargamos la fabrica de metodos de pago, pa' tener orden.
header("Content-Type: application/json; charset=utf-8"); // Respondemos siempre en JSON.

if ($_SERVER["REQUEST_METHOD"] !== "POST") { // Solo aceptamos POST, el resto no va.
    http_response_code(405); // Metodo no permitido.
    echo json_encode(["ok" => false, "msg" => "Método no permitido"]); // Avisamos en JSON.
    exit; // Cortamos ejecucion.
} // Cerramos el if del metodo.

$raw = file_get_contents("php://input"); // Leemos el cuerpo crudo de la peticion.
$data = json_decode($raw, true); // Convertimos el JSON a arreglo.

if (!is_array($data)) { // Si no es arreglo, el JSON esta mal.
    http_response_code(400); // Error de cliente.
    echo json_encode(["ok" => false, "msg" => "JSON inválido"]); // Respondemos el error.
    exit; // Cortamos ejecucion.
} // Cerramos el if del JSON.

$email = trim($data["email"] ?? ""); // Tomamos el correo.
$metodoPago = trim($data["metodo_pago"] ?? ""); // Tomamos el metodo de pago.
$numeroReferencia = trim($data["numero_referencia"] ?? ""); // Tomamos la referencia si llega.
$carrito = $data["carrito"] ?? []; // Tomamos el carrito.

$paymentMethod = PaymentMethodFactory::create($metodoPago); // Instanciamos el metodo segun el codigo que llega.

if ($email === "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) { // Validamos correo vacio o invalido.
    http_response_code(422); // Error de validacion.
    echo json_encode(["ok" => false, "msg" => "Email inválido"]); // Respondemos el error.
    exit; // Cortamos ejecucion.
} // Cerramos el if del email.

if (!$paymentMethod) { // Si no existe, ese metodo no va.
    http_response_code(422); // Error de validacion.
    echo json_encode(["ok" => false, "msg" => "Método de pago inválido"]); // Respondemos el error.
    exit; // Cortamos ejecucion.
} // Cerramos el if del metodo.

if (!is_array($carrito) || count($carrito) === 0) { // Validamos carrito vacio.
    http_response_code(422); // Error de validacion.
    echo json_encode(["ok" => false, "msg" => "El carrito está vacío"]); // Respondemos el error.
    exit; // Cortamos ejecucion.
} // Cerramos el if del carrito.

try { // Bloque principal con control de errores.
    $pdo = ConexionBaseDatos(); // Abrimos conexion.
    $pdo->beginTransaction(); // Iniciamos transaccion.

    $stmtUser = $pdo->prepare("SELECT id, nombre, email, balance FROM usuarios WHERE email = :email LIMIT 1"); // Preparamos consulta del usuario.
    $stmtUser->execute([":email" => $email]); // Ejecutamos la consulta con el email.
    $usuario = $stmtUser->fetch(); // Traemos el usuario.

    if (!$usuario) { // Si no existe usuario.
        throw new Exception("Usuario no encontrado"); // Lanzamos error.
    } // Cerramos el if del usuario.

    $itemsNormalizados = []; // Arreglo para items validados.
    $subtotal = 0.0; // Acumulamos el subtotal.

    $stmtProducto = $pdo->prepare("SELECT id, nombre, precio, stock FROM productos WHERE id = :id LIMIT 1"); // Preparamos consulta de producto.

    foreach ($carrito as $item) { // Recorremos el carrito.
        $productoId = (int)($item["id"] ?? 0); // Id del producto.
        $cantidad = (int)($item["cantidad"] ?? 0); // Cantidad pedida.

        if ($productoId <= 0 || $cantidad <= 0) { // Validamos id y cantidad.
            throw new Exception("Producto o cantidad inválida"); // Lanzamos error.
        } // Cerramos el if de validacion.

        $stmtProducto->execute([":id" => $productoId]); // Buscamos el producto.
        $producto = $stmtProducto->fetch(); // Traemos el producto.

        if (!$producto) { // Si no existe el producto.
            throw new Exception("Producto no encontrado: " . $productoId); // Lanzamos error con el id.
        } // Cerramos el if del producto.

        if ((int)$producto["stock"] < $cantidad) { // Validamos stock.
            throw new Exception("Stock insuficiente para " . $producto["nombre"]); // Lanzamos error con el nombre.
        } // Cerramos el if del stock.

        $precioUnitario = (float)$producto["precio"]; // Precio unitario.
        $lineTotal = $precioUnitario * $cantidad; // Total por linea.
        $subtotal += $lineTotal; // Sumamos al subtotal.

        $itemsNormalizados[] = [ // Guardamos item normalizado.
            "producto_id" => (int)$producto["id"], // Id validado.
            "cantidad" => $cantidad, // Cantidad validada.
            "precio_unitario" => $precioUnitario // Precio validado.
        ]; // Cerramos el array del item.
    } // Cerramos el foreach.

    $envio = $subtotal > 0 ? 10000 : 0; // Envio fijo si hay compra.
    $total = $subtotal + $envio; // Total final.

    if ($paymentMethod->isBalanceRequired() && (float)$usuario["balance"] < $total) { // Validamos saldo segun el metodo.
        throw new Exception("Fondos insuficientes. Balance actual: " . number_format((float)$usuario["balance"], 2)); // Lanzamos error de saldo.
    } // Cerramos el if de saldo.

    $stmtPedido = $pdo->prepare("\n        INSERT INTO pedidos (usuario_id, total)\n        VALUES (:usuario_id, :total)\n    "); // Preparamos el pedido.
    $stmtPedido->execute([ // Ejecutamos el insert del pedido.
        ":usuario_id" => (int)$usuario["id"], // Usuario comprador.
        ":total" => $total // Total del pedido.
    ]); // Cerramos el execute del pedido.

    $pedidoId = (int)$pdo->lastInsertId(); // Capturamos el id del pedido.

    $stmtDetalle = $pdo->prepare("\n        INSERT INTO detalle_pedido (pedido_id, producto_id, cantidad, precio_unitario)\n        VALUES (:pedido_id, :producto_id, :cantidad, :precio_unitario)\n    "); // Preparamos el detalle.

    $stmtStock = $pdo->prepare("\n        UPDATE productos\n        SET stock = stock - :cantidad\n        WHERE id = :id\n    "); // Preparamos la actualizacion de stock.

    foreach ($itemsNormalizados as $it) { // Recorremos items ya limpios.
        $stmtDetalle->execute([ // Insertamos cada detalle.
            ":pedido_id" => $pedidoId, // Pedido actual.
            ":producto_id" => $it["producto_id"], // Producto actual.
            ":cantidad" => $it["cantidad"], // Cantidad actual.
            ":precio_unitario" => $it["precio_unitario"] // Precio actual.
        ]); // Cerramos el execute del detalle.

        $stmtStock->execute([ // Descontamos stock.
            ":cantidad" => $it["cantidad"], // Cantidad a restar.
            ":id" => $it["producto_id"] // Id del producto.
        ]); // Cerramos el execute del stock.
    } // Cerramos el foreach de items.

    $referencia = $numeroReferencia !== "" ? $numeroReferencia : "SIM-" . strtoupper(uniqid()); // Definimos referencia.

    $stmtTrans = $pdo->prepare("\n        INSERT INTO transacciones (pedido_id, referencia, monto, metodo_pago, estado)\n        VALUES (:pedido_id, :referencia, :monto, :metodo_pago, :estado)\n    "); // Preparamos la transaccion.
    $stmtTrans->execute([ // Insertamos la transaccion.
        ":pedido_id" => $pedidoId, // Pedido asociado.
        ":referencia" => $referencia, // Referencia final.
        ":monto" => $total, // Monto final.
        ":metodo_pago" => $paymentMethod->getCode(), // Guardamos el codigo estandarizado.
        ":estado" => "aprobada" // Estado simulado.
    ]); // Cerramos el execute de transaccion.

    if ($paymentMethod->isBalanceRequired()) { // Para metodos que requieren saldo.
        $stmtSaldo = $pdo->prepare("\n            UPDATE usuarios\n            SET balance = balance - :monto\n            WHERE id = :id\n        "); // Preparamos el descuento de saldo.
        $stmtSaldo->execute([ // Ejecutamos el descuento.
            ":monto" => $total, // Monto a descontar.
            ":id" => (int)$usuario["id"] // Usuario al que se descuenta.
        ]); // Cerramos el execute del saldo.
    } // Cerramos el if del saldo.

    $pdo->commit(); // Confirmamos la transaccion.

    echo json_encode([ // Respondemos exito.
        "ok" => true, // Indicamos que todo salio bien.
        "msg" => "Pago simulado aprobado", // Mensaje de exito.
        "pedido_id" => $pedidoId, // Id del pedido.
        "referencia" => $referencia, // Referencia de la transaccion.
        "total" => $total // Total final.
    ]); // Cerramos el JSON de respuesta.
} catch (Exception $e) { // Capturamos cualquier error.
    if (isset($pdo) && $pdo->inTransaction()) { // Si hay transaccion abierta.
        $pdo->rollBack(); // Deshacemos todo.
    } // Cerramos el if del rollback.
    http_response_code(400); // Respondemos error.
    echo json_encode([ // Enviamos el error al front.
        "ok" => false, // Marcamos fallo.
        "msg" => $e->getMessage() // Mandamos el mensaje real.
    ]); // Cerramos el JSON de error.
} // Cerramos el try-catch.
?>