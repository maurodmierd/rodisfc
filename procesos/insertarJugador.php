<?php

// Script para evitar acceso non autorizado
include '../includes/header.php';
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
    header("Location: ../login/areaPrivada.php");
}
include '../includes/conexion.php';

// Comproba se o formulario foi enviado e engade o xogador na base de datos
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $dni = $_POST['dni'];
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $equipo_id = $_POST['equipo_id'];
    $foto_id = $_POST['foto'];

    $stmt = $conexion->prepare("INSERT INTO jugadores (dni, nombre, apellidos, equipo_id, foto_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$dni, $nombre, $apellidos, $equipo_id, $foto_id]);
    echo "<p>Xogador insertado correctamente.</p>";
}
?>

<h2>Insertar Jugador</h2>
<form method="POST">
    <input type="text" name="dni" placeholder="DNI" required><br>
    <input type="text" name="nombre" placeholder="Nome" required><br>
    <input type="text" name="apellidos" placeholder="Apelidos" required><br>
    <input type="number" name="equipo_id" placeholder="ID do equipo" required><br>

    <!-- Campo oculto para gardar o ID da imaxe seleccionada -->
    <input type="hidden" name="foto" id="fotoSeleccionada">

    <!-- Vista previa da imaxe -->
    <div id="previewImagenSeleccionada"></div>

    <!-- Botón para abrir a galería -->
    <button type="button" onclick="abrirGaleria()">Seleccionar imaxe</button>


    <button type="submit">Engadir</button>
</form>

<?php 
include 'galeria.php';
include '../includes/footer.php';
?>
