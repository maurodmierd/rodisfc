<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

include '../includes/header.php';
?>

<div class="container">
    <h2>Benvido, <?php echo $_SESSION['usuario']['nombre']?></h2>

    <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
        <div class="admin-botonera" style='padding: 15px 100px;'>
            <a href="../procesos/insertarSocio.php" class="btn">Insertar Socio</a>
            <a href="../procesos/insertarJugador.php" class="btn">Insertar Jugador</a>
            <a href="../procesos/escribirNoticia.php" class="btn">Escribir Noticia</a>
            <a href="../procesos/gestionarUsuarios.php" class="btn">Gestionar Usuarios</a>
            <a href="../procesos/subirImagenes.php" class="btn">Subir Imagen</a>
        </div>
    <?php else: ?>
        <p>No tienes permisos para acceder a esta sección.</p>
    <?php endif; ?>

    <a href="logout.php" class="btn">Cerrar sesión</a>
</div>
<div class='container'>
    <h3>Comunicados para Socios</h3>
    <?php
    include '../includes/conexion.php';

    $stmt = $conexion->prepare("SELECT * FROM noticias WHERE categoria = 'privada' ORDER BY fecha DESC LIMIT 5");
    $stmt->execute();
    $noticias = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($noticias):
        foreach ($noticias as $noticia):
    ?>
        <article class="noticia">
            <h4><?php echo htmlspecialchars($noticia['titulo']); ?></h4>
            <p><?php echo nl2br(htmlspecialchars($noticia['texto'])); ?></p>
        </article>
    <?php
        endforeach;
    else:
        echo "<p>No hay comunicados privados disponibles.</p>";
    endif;
    ?>
</div>



<?php include '../includes/footer.php'; ?>
