<?php
// Script para evitar acceso non autorizado
include '../includes/header.php';
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
    header("Location: ../areaPrivada.php");
}
include '../includes/conexion.php';

// Comproba se o formulario foi enviado e se se subiu unha imaxe
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['imagen'])) {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'] ?? null;
    $fecha = date('Y-m-d');
    $nombreArchivo = $_FILES['imagen']['name'];
    $ruta = '../img/' . basename($nombreArchivo);
    // Xestiona o aquivo subido e garda a información na base de datos
    if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta)) {
        $stmt = $conexion->prepare("INSERT INTO img (nombre, descripcion, fecha) VALUES (?, ?, ?)");
        $stmt->execute([$nombreArchivo, $descripcion, $fecha]);
        echo "<p>Imaxe subida correctamente</p>";
    } else {
        echo "<p>Erro ao subir a imaxe</p>";
    }
}
?>
<!-- Formulario para subir imaxes -->
<form method="post" enctype="multipart/form-data">
    <input type="text" name="nombre" placeholder="Nome da imaxen" required>
    <textarea name="descripcion" placeholder="Descrición (opcional)"></textarea>
    <input type="file" name="imagen" required>
    <button type="submit">Subir Imaxe</button>
</form>

<?php include '../includes/footer.php'; ?>
