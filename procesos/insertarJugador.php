<?php

include '../includes/header.php';
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
    header("Location: ../areaPrivada.php");
}
include '../includes/conexion.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $dni = $_POST['dni'];
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $equipo_id = $_POST['equipo_id'];
    $foto_id = $_POST['foto'];

    $stmt = $conexion->prepare("INSERT INTO jugadores (dni, nombre, apellidos, equipo_id, foto_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$dni, $nombre, $apellidos, $equipo_id, $foto_id]);
    echo "<p>Jugador insertado correctamente.</p>";
}
?>

<h2>Insertar Jugador</h2>
<form method="POST">
    <input type="text" name="dni" placeholder="DNI" required><br>
    <input type="text" name="nombre" placeholder="Nombre" required><br>
    <input type="text" name="apellidos" placeholder="Apellidos" required><br>
    <input type="number" name="equipo_id" placeholder="ID del equipo" required><br>

    <!-- Campo oculto para guardar el ID de la imagen seleccionada -->
    <input type="hidden" name="foto" id="fotoSeleccionada">

    <!-- Vista previa de la imagen -->
    <div id="previewImagenSeleccionada"></div>

    <!-- Botón para abrir la galería -->
    <button type="button" onclick="abrirGaleria()">Seleccionar imagen</button>


    <button type="submit">Guardar</button>
</form>

<?php 
include 'galeria.php';
include '../includes/footer.php';
?>
