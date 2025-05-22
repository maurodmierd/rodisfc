<?php
include '../includes/conexion.php';

$datos = [
    'id' => '12345678A',
    'nombre' => 'Juan',
    'apellidos' => 'Pérez Gómez',
    'telefono' => '666777888',
    'email' => 'juan@ejemplo.com',
    'contraseña' => password_hash('clave123', PASSWORD_DEFAULT),
    'rol' => 'socio'
];

$stmt = $conexion->prepare("INSERT INTO usuarios (id, nombre, apellidos, telefono, email, contraseña, rol) 
VALUES (:id, :nombre, :apellidos, :telefono, :email, :contraseña, :rol)");
$stmt->execute($datos);

echo "Usuario registrado";
