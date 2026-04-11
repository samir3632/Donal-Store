<?php
function ConexionBaseDatos(): PDO {
  $host = "localhost";
  $db   = "donal_store";
  $user = "root";
  $pass = "";

  $dsn = "mysql:host={$host};dbname={$db};charset=utf8mb4";

  try {
    return new PDO($dsn, $user, $pass, [
      PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
  } catch (PDOException $e) {
    die("Error en la conexión: " . $e->getMessage());
  }
}