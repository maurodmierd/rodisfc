<?php
include '../includes/header.php';
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
    header("Location: ../areaPrivada.php");
}
include '../includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['imagen'])) {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'] ?? null;
    $fecha = date('Y-m-d');
    $nombreArchivo = $_FILES['imagen']['name'];
    $ruta = '../img/' . basename($nombreArchivo);

    if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta)) {
        $stmt = $conexion->prepare("INSERT INTO img (nombre, descripcion, fecha) VALUES (?, ?, ?)");
        $stmt->execute([$nombreArchivo, $descripcion, $fecha]);
        echo "<p>Imagen subida correctamente</p>";
    } else {
        echo "<p>Error al subir la imagen</p>";
    }
}
?>

<form method="post" enctype="multipart/form-data">
    <input type="text" name="nombre" placeholder="Nombre de la imagen" required>
    <textarea name="descripcion" placeholder="Descripción (opcional)"></textarea>
    <input type="file" name="imagen" required>
    <button type="submit">Subir Imagen</button>
</form>

<?php include '../includes/footer.php'; ?>
