<?php
$host = 'localhost';
$db = 'rodisfc';
$user = 'rodisfc';
$pass = '';

try {
    $conexion = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
} catch (PDOException $e) {
    echo('Err base d datos');
}