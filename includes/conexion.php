<?php
$host = 'localhost';
$db = 'rodisfc';
$user = 'rodisfc';
$pass = '';

//Probamos a conexión ca base de datos
try {
    $conexion = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
} catch (PDOException $e) {
    echo('Err base d datos');
}