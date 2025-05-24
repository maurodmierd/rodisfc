<?php

include '../includes/header.php';
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
    header("Location: ../areaPrivada.php");
}
include '../includes/conexion.php';

$stmt = $conexion->query("SELECT * FROM usuarios");
$usuarios = $stmt->fetchAll();
?>

<h2>Lista de Usuarios</h2>
<table>
    <tr>
        <th>DNI</th><th>Nombre</th><th>Email</th><th>Rol</th>
    </tr>
    <?php foreach ($usuarios as $usuario): ?>
        <tr>
            <td><?= $usuario['id'] ?></td>
            <td><?= $usuario['nombre'] . ' ' . $usuario['apellidos'] ?></td>
            <td><?= $usuario['email'] ?></td>
            <td><?= $usuario['rol'] ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<?php include '../includes/footer.php'; ?>
