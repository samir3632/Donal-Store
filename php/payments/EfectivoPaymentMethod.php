<?php // Inicio de archivo PHP.
// Metodo de pago en efectivo (simulado).
require_once __DIR__ . "/PaymentMethodInterface.php"; // Traemos el contrato base, sin esto no compila.

class EfectivoPaymentMethod implements PaymentMethodInterface { // Clase concreta para efectivo.
    public function getCode(): string { // Codigo que se guarda en BD.
        return "efectivo"; // Se mantiene igual que el valor del select.
    } // Cerramos getCode.

    public function isBalanceRequired(): bool { // En efectivo no hay saldo virtual que revisar.
        return false; // No se descuenta balance en este metodo.
    } // Cerramos isBalanceRequired.
} // Cerramos la clase.
