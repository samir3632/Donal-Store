<?php
 function ConexionBaseDatos(){
 $host="localhost";
 $base_datos="donal_store";
    $usuario="root";
$contraseña="";
try {
    $conexion = new PDO("mysql:host=$host;dbname=$base_datos", $usuario, $contraseña);
 $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 return "Conexion exitosa :D";
 }catch(PDOException $e){
 return "Error en la conexion :(".$e->getMessage();
 }

 }
 echo ConexionBaseDatos(); 
?>