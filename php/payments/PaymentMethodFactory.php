<?php // Inicio de archivo PHP.
// Fabrica para crear metodos de pago segun el codigo recibido.
require_once __DIR__ . "/EfectivoPaymentMethod.php"; // Incluimos efectivo, el de toda la vida.
require_once __DIR__ . "/TarjetaPaymentMethod.php"; // Incluimos tarjeta, por si pagan con plastico.
require_once __DIR__ . "/PsePaymentMethod.php"; // Incluimos PSE, el del banco.

class PaymentMethodFactory { // Fabrica simple y directa.
    public static function create(string $code): ?PaymentMethodInterface { // Crea una instancia o devuelve null.
        switch ($code) { // Decidimos segun el codigo.
            case "efectivo": // Opcion efectivo.
                return new EfectivoPaymentMethod(); // Instancia de efectivo.
            case "tarjeta": // Opcion tarjeta.
                return new TarjetaPaymentMethod(); // Instancia de tarjeta.
            case "pse": // Opcion PSE.
                return new PsePaymentMethod(); // Instancia de PSE.
            default: // Cualquier otro valor.
                return null; // Metodo no soportado, aqui no se cuela ninguno raro.
        } // Cerramos el switch.
    } // Cerramos create.

    public static function allowedCodes(): array { // Lista de codigos permitidos.
        return ["tarjeta", "pse", "efectivo"]; // Se mantiene el mismo orden y valores.
    } // Cerramos allowedCodes.
} // Cerramos la clase.
