<?php // Inicio de archivo PHP.
// Metodo de pago con tarjeta (simulado).
require_once __DIR__ . "/PaymentMethodInterface.php"; // Traemos el contrato base, sin esto no compila.

class TarjetaPaymentMethod implements PaymentMethodInterface { // Clase concreta para tarjeta.
    public function getCode(): string { // Codigo que se guarda en BD.
        return "tarjeta"; // Se mantiene igual que el valor del select.
    } // Cerramos getCode.

    public function isBalanceRequired(): bool { // Con tarjeta si toca mirar saldo.
        return true; // Se descuenta balance en este metodo.
    } // Cerramos isBalanceRequired.
} // Cerramos la clase.
