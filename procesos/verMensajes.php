<?php
// Script para evitar acceso non autorizado
include '../includes/header.php';
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
    header("Location: ../login/areaPrivada.php");
    exit();
}

$archivo = '../datos/contacto.json';
$mensajes = [];
$mensaje_exito = '';
$mensaje_error = '';

// Cargar mensajes
if (file_exists($archivo)) {
    $contenido = file_get_contents($archivo);
    $mensajes = json_decode($contenido, true) ?: [];
}

// Procesar eliminación de mensaje
if (isset($_POST['eliminar']) && isset($_POST['mensaje_index'])) {
    $index = intval($_POST['mensaje_index']);
    if (isset($mensajes[$index])) {
        array_splice($mensajes, $index, 1);
        file_put_contents($archivo, json_encode($mensajes, JSON_PRETTY_PRINT));
        $mensaje_exito = "Mensaxe eliminada correctamente";
    } else {
        $mensaje_error = "Mensaxe non atopada";
    }
}

// Procesar eliminación de todos los mensajes
if (isset($_POST['eliminar_todos'])) {
    $mensajes = [];
    file_put_contents($archivo, json_encode($mensajes, JSON_PRETTY_PRINT));
    $mensaje_exito = "Todas as mensaxes foron eliminadas";
}
?>

<div class="container">
    <h2><?php echo icon('fas fa-envelope'); ?> Mensaxes de contacto</h2>
    
    <?php if (!empty($mensaje_exito)): ?>
        <div class="mensaje-exito">
            <i class="fas fa-check-circle"></i>
            <p><?php echo htmlspecialchars($mensaje_exito); ?></p>
        </div>
    <?php endif; ?>

    <?php if (!empty($mensaje_error)): ?>
        <div class="mensaje-error">
            <i class="fas fa-exclamation-triangle"></i>
            <p><?php echo htmlspecialchars($mensaje_error); ?></p>
        </div>
    <?php endif; ?>

    <?php if (!empty($mensajes)): ?>
        <div class="mensajes-header">
            <p>Total de mensaxes: <strong><?php echo count($mensajes); ?></strong></p>
            <form method="POST" style="display: inline;" onsubmit="return confirm('¿Estás seguro de que queres eliminar TODAS as mensaxes?')">
                <button type="submit" name="eliminar_todos" class="btn-eliminar-todos">
                    <i class="fas fa-trash-alt"></i> Eliminar Todas
                </button>
            </form>
        </div>

        <div class="mensajes-lista">
            <?php foreach ($mensajes as $index => $msg): ?>
                <div class="mensaje-card">
                    <div class="mensaje-header">
                        <div class="mensaje-info">
                            <h4><?php echo icon('fas fa-user'); ?> <?php echo htmlspecialchars($msg['nombre']); ?></h4>
                            <p class="mensaje-email">
                                <?php echo icon('fas fa-envelope'); ?> 
                                <a href="mailto:<?php echo htmlspecialchars($msg['email']); ?>">
                                    <?php echo htmlspecialchars($msg['email']); ?>
                                </a>
                            </p>
                            <p class="mensaje-fecha">
                                <?php echo icon('fas fa-calendar-alt'); ?> 
                                <?php echo htmlspecialchars($msg['fecha']); ?>
                            </p>
                        </div>
                        <form method="POST" style="display: inline;" onsubmit="return confirm('¿Estás seguro de que queres eliminar esta mensaxe?')">
                            <input type="hidden" name="mensaje_index" value="<?php echo $index; ?>">
                            <button type="submit" name="eliminar" class="btn-eliminar-mensaje">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                    <div class="mensaje-contenido">
                        <h5><?php echo icon('fas fa-comment'); ?> Mensaxe:</h5>
                        <p><?php echo nl2br(htmlspecialchars($msg['mensaje'])); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="no-mensajes">
            <i class="fas fa-inbox no-content-icon"></i>
            <p>Non hai mensaxes aínda.</p>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
