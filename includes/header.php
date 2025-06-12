<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Rodís F.C.</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Rodís F.C. - Portal de contido do club con toda a actualidade: últimas noticias, próximos partidos, plantilla e contacto. Club de fútbol galego en constante crecemento.">
    <meta name="keywords" content="Rodís FC, Rodis, Rodís F.C., fútbol, Galicia, noticias, partidos, plantilla, club de fútbol, deportes, equipo galego, rodis, cerceda">
    <meta name="author" content="Rodís F.C.">
    <link rel="icon" href="../img/logos/logo.png">
    <!-- Fuentes de texto de Google(Anton,Roboto) -->
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Roboto:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Iconos de Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- css -->
    <link rel="stylesheet" href="../src/estilos.css">
</head>
<body>
<?php 
if (session_status() === PHP_SESSION_NONE) session_start();

// funcion para usar iconos de Font Awesome
function icon($name, $class = '') {
    return "<i class=\"{$name} {$class}\"></i>";
}
?>
<header class="header">
    <div class="header-content">
        <img src="../img/logos/logo.png" alt="Escudo do Rodís F.C." class="logo">
        
        <div class="header-buttons">
            <?php if (isset($_SESSION['usuario'])): ?>
                <a href="../views/areaPrivada.php" class="btn-cuenta btn-area-privada">
                    <?php echo icon('fas fa-home'); ?> Área Privada
                </a>
                <a href="../login/logout.php" class="btn-cuenta btn-cerrar-sesion">
                    <?php echo icon('fas fa-sign-out-alt'); ?> Cerrar Sesión
                </a>
            <?php else: ?>
                <a href="../login/login.php" class="btn-cuenta">
                    <?php echo icon('fas fa-key'); ?> Iniciar Sesión
                </a>
            <?php endif; ?>
        </div>
    </div>
</header>

<nav>
    <a href="../index.php"><?php echo icon('fas fa-home'); ?> Inicio</a>
    <a href="../views/plantilla.php"><?php echo icon('fas fa-users'); ?> Plantilla</a>
    <a href="../views/partidos.php"><?php echo icon('fas fa-futbol'); ?> Partidos</a>
    <a href="../views/galeria.php"><?php echo icon('fas fa-images'); ?> Galería</a>
    <a href="../views/historia.php"><?php echo icon('fas fa-book'); ?> Historia</a>
    <a href="../views/contacto.php"><?php echo icon('fas fa-envelope'); ?> Contacto</a>
</nav>
