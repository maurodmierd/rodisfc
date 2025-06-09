<?php

// Script para evitar acceso non autorizado
include '../includes/header.php';
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
    header("Location: ../views/areaPrivada.php");
}
$archivo = '../datos/contacto.json';
$mensajes = [];

if (file_exists($archivo)) {
    $contenido = file_get_contents($archivo);
    $mensajes = json_decode($contenido, true);
}
?>
<!-- Amosa as mensaxes recibidas a través do formulario de contacto -->
<div class="container">
    <h2>Mensaxes de contacto</h2>
    <?php if (!empty($mensajes)): ?>
        <ul>
            <!-- Recorre as mensaxes recibidas e amosa cada unha -->
            <?php foreach ($mensajes as $msg): ?>
                <li>
                    <strong><?php echo $msg['nombre']; ?></strong> (<?php echo $msg['email']; ?>) escribiu o <?php echo $msg['fecha']; ?>:<br>
                    <?php echo nl2br(htmlspecialchars($msg['mensaje'])); ?>
                </li>
                <hr>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Non hai mensaxes aínda.</p>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
