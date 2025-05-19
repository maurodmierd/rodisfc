<?php
$host = 'localhost';  // Cambia según tu configuración
$usuario = 'root';    // Cambia según tu configuración
$contraseña = '';     // Cambia según tu configuración
$base_datos = 'rodis_fc';  // Nombre de tu base de datos

$conexion = new mysqli($host, $usuario, $contraseña, $base_datos);

// Verificar si la conexión fue exitosa
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
?>
