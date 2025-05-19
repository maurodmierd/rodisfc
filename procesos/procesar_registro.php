<?php
// Conexión a la base de datos
include('../includes/conexion.php');

// Obtener los datos del formulario
$nombre = $_POST['nombre'];
$email = $_POST['email'];

// Insertar los datos en la base de datos
$sql = "INSERT INTO socios (nombre, email) VALUES ('$nombre', '$email')";

if ($conexion->query($sql) === TRUE) {
    echo "Registro exitoso.";
} else {
    echo "Error: " . $sql . "<br>" . $conexion->error;
}

// Cerrar la conexión
$conexion->close();
?>
