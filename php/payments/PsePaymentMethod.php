<?php // Inicio de archivo PHP.
// Metodo de pago PSE (simulado).
require_once __DIR__ . "/PaymentMethodInterface.php"; // Traemos el contrato base, sin esto no compila.

class PsePaymentMethod implements PaymentMethodInterface { // Clase concreta para PSE.
    public function getCode(): string { // Codigo que se guarda en BD.
        return "pse"; // Se mantiene igual que el valor del select.
    } // Cerramos getCode.

    public function isBalanceRequired(): bool { // Con PSE tambien toca mirar saldo.
        return true; // Se descuenta balance en este metodo.
    } // Cerramos isBalanceRequired.
} // Cerramos la clase.
