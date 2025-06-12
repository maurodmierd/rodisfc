<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Verificamos se a sesión está iniciada.
if (!isset($_SESSION['usuario'])) {
    header("Location: ../login/login.php");
    exit();
}

include '../includes/header.php';
?>

<!-- Botóns para administradores -->
<div class="container">
    <h2>Benvido, <?php echo $_SESSION['usuario']['nombre']?></h2>
    <!-- Comproba o rol do usuario para saber se ten acceso a esta sección. -->
    <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
        <div class="admin-botonera" style='padding: 15px 100px;'>
            <a href="../procesos/insertarUsuario.php" class="btn">Insertar usuario</a>
            <a href="../procesos/verMensajes.php" class="btn">Ver mensajes de contacto</a>
            <a href="../procesos/escribirNoticia.php" class="btn">Escribir Noticia</a>
            <a href="../procesos/gestionarUsuarios.php" class="btn">Gestionar Usuarios</a>
        </div>
    <?php else: ?>
        <p>Non tes permisos para acceder a esta sección.</p>
    <?php endif; ?>
</div>


<!-- Comunicados privados -->
<div class='container'>
    <h3>Comunicados para Socios</h3>
    <?php
    include '../includes/conexion.php';

    $stmt = $conexion->prepare("SELECT * FROM noticias WHERE categoria = 'privada' ORDER BY fecha DESC LIMIT 5");
    $stmt->execute();
    $noticias = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Se hai noticias, amosámolas
    if ($noticias):
        foreach ($noticias as $noticia):
    ?>
        <article class="noticia">
            <h4><?php echo htmlspecialchars($noticia['titulo']); ?></h4>
            <p><?php echo nl2br(htmlspecialchars($noticia['contenido'])); ?></p>
        </article>
    <?php
        endforeach;
    else:
        echo "<p>Non hai comunicados privados para amosar.</p>";
    endif;
    ?>
</div>



<?php include '../includes/footer.php';