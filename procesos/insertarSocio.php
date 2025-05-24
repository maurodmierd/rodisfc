<?php

include '../includes/header.php';
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
    header("Location: ../areaPrivada.php");
}
include '../includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['dni'];
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $telefono = $_POST['telefono'] ?? null;
    $email = $_POST['email'] ?? null;
    $fecha = date('Y-m-d');
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $rol = 'socio';

    $stmt = $conexion->prepare("INSERT INTO usuarios (id, nombre, apellidos, telefono, email, fecha_registro, contraseña, rol) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$id, $nombre, $apellidos, $telefono, $email, $fecha, $password, $rol]);

    echo "<p>Socio insertado correctamente</p>";
}
?>

<form method="post">
    <input type="text" name="dni" placeholder="DNI" required>
    <input type="text" name="nombre" placeholder="Nombre" required>
    <input type="text" name="apellidos" placeholder="Apellidos" required>
    <input type="text" name="telefono" placeholder="Teléfono">
    <input type="email" name="email" placeholder="Email">
    <input type="password" name="password" placeholder="Contraseña" required>
    <button type="submit">Insertar Socio</button>
</form>

<?php include '../includes/footer.php'; ?>
