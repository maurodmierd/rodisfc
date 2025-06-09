<?php

// Script para evitar acceso non autorizado
include '../includes/header.php';
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
    header("Location: ../views/areaPrivada.php");
}
include '../includes/conexion.php';

// Comproba se o formulario foi enviado e engade a noticia na base de datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fecha = date('Y-m-d');
    $titulo = $_POST['titulo'];
    // Se non se selecciona unha imaxe, asigna a id 1
    if (!isset($_POST['imagen_id'])){
        $foto=1;
    }else{
        $foto= $_POST['imagen_id'];
    }
    $texto = $_POST['texto'];

    $stmt = $conexion->prepare("INSERT INTO noticias (fecha, titulo, imagen_id, contenido) VALUES (?, ?, ?, ?)");
    $stmt->execute([$fecha, $titulo, $foto, $texto]);

    echo "<p>Noticia publicada</p>";
}
?>
<!-- Formulario para engadir unha nova noticia -->
<form method="post">
    <input type="text" name="titulo" placeholder="Título" required>
    <?php
        include '../includes/conexion.php';

        $stmt = $conexion->query("SELECT id, nombre, descripcion FROM img");
        $imagenes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <textarea name="texto" placeholder="Texto da noticia" required></textarea>

    <!--Gardase o id da foto-->
    <input type="hidden" name="foto" id="fotoSeleccionada">
    <div id="previewImagenSeleccionada"></div>
    <button type="button" onclick="abrirGaleria()">Seleccionar imaxe</button>

    <button type="submit">Publicar Noticia</button>
</form>
<script src="js/adminImg.js"></script>
<?php 
    include '../includes/footer.php';
?>
