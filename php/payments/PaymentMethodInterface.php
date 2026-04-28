<?php // Inicio de archivo PHP.
// Interfaz base, pa' que todos los metodos hablen el mismo idioma.
interface PaymentMethodInterface { // Definimos el contrato comun.
    public function getCode(): string; // Codigo interno, tal cual lo manda el front.
    public function isBalanceRequired(): bool; // Dice si toca mirar saldo del usuario.
} // Cerramos la interfaz.
