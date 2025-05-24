<?php

include '../includes/header.php';
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
    header("Location: ../areaPrivada.php");
}
include '../includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fecha = date('Y-m-d');
    $titulo = $_POST['titulo'];
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

<form method="post">
    <input type="text" name="titulo" placeholder="Título" required>
    <?php
        include '../includes/conexion.php';

        $stmt = $conexion->query("SELECT id, nombre, descripcion FROM img");
        $imagenes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <textarea name="texto" placeholder="Texto de la noticia" required></textarea>

    <!--Se guarda el id de la foto-->
    <input type="hidden" name="foto" id="fotoSeleccionada">
    <div id="previewImagenSeleccionada"></div>
    <button type="button" onclick="abrirGaleria()">Seleccionar imagen</button>

    <button type="submit">Publicar Noticia</button>
</form>

<?php 
    include 'galeria.php';
    include '../includes/footer.php';
?>
