<?php
include('../includes/header.php');
$archivo = '../datos/contacto.json';
$mensajes = [];

if (file_exists($archivo)) {
    $contenido = file_get_contents($archivo);
    $mensajes = json_decode($contenido, true);
}
?>

<div class="container">
    <h2>Mensajes de Contacto</h2>
    <?php if (!empty($mensajes)): ?>
        <ul>
            <?php foreach ($mensajes as $msg): ?>
                <li>
                    <strong><?php echo $msg['nombre']; ?></strong> (<?php echo $msg['email']; ?>) escribió el <?php echo $msg['fecha']; ?>:<br>
                    <?php echo nl2br(htmlspecialchars($msg['mensaje'])); ?>
                </li>
                <hr>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No hay mensajes aún.</p>
    <?php endif; ?>
    <a href="../socios/areaPrivada.php" class="btn">Volver atrás</a>
</div>

<?php include('../includes/footer.php'); ?>
