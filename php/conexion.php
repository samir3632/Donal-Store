<?php
 function ConexionBaseDatos(){
 $host="localhost";
 $base_datos="donal_store";
    $usuario="root";
$contraseña="";
try {
    $conexion = new PDO( "mysql:host=$host;dbname=$base_datos;charset=utf8",
    $usuario,
    $contraseña);
 $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 return $conexion;
 }catch(PDOException $e){
die("Error en la conexion: ".$e->getMessage());
 }

 }
 $conexion=ConexionBaseDatos(); 
?>
