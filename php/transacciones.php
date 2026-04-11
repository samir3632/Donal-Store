<?php
require_once "conexion.php";

$conexion = ConexionBaseDatos();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $productoId = $_POST["producto_id"];
    $cantidad = $_POST["cantidad"];

    try {
        $conexion->beginTransaction();

    
        $sql = "SELECT nombre, precio, stock 
                FROM productos 
                WHERE id = :id";

        $stmt = $conexion->prepare($sql);
        $stmt->execute([
            ":id" => $productoId
        ]);

        $producto = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$producto) {
            throw new Exception("Producto no encontrado.");
        }

        if ($producto["stock"] < $cantidad) {
            throw new Exception("Stock insuficiente.");
        }

    
        $token = "TXN-" . strtoupper(uniqid());

     
        $sqlPedido = "INSERT INTO pedidos
                     (token_transaccion, producto_id, cantidad, total)
                     VALUES
                     (:token, :producto_id, :cantidad, :total)";

        $stmt = $conexion->prepare($sqlPedido);
        $stmt->execute([
            ":token" => $token,
            ":producto_id" => $productoId,
            ":cantidad" => $cantidad,
            ":total" => $producto["precio"] * $cantidad
        ]);

    
        $sqlStock = "UPDATE productos
                     SET stock = stock - :cantidad
                     WHERE id = :id";

        $stmt = $conexion->prepare($sqlStock);
        $stmt->execute([
            ":cantidad" => $cantidad,
            ":id" => $productoId
        ]);

        $conexion->commit();


    } catch(Exception $e) {
        $conexion->rollBack();
        die("Error: " . $e->getMessage());
    }
}
?>