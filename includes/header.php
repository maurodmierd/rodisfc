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
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Roboto:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../src/estilos.css">
</head>
<body>
<?php if (session_status() === PHP_SESSION_NONE) session_start() ?>
<header class="header">
    <div class="header-content">
        <img src="../img/logos/logo.png" alt="Escudo do Rodís F.C." class="logo">
        
        <div class="header-buttons">
            <?php if (isset($_SESSION['usuario'])): ?>
                <a href="../views/areaPrivada.php" class="btn-cuenta btn-area-privada">
                    🏠 Área Privada
                </a>
                <a href="../login/logout.php" class="btn-cuenta btn-cerrar-sesion">
                    🚪 Cerrar Sesión
                </a>
            <?php else: ?>
                <a href="../login/login.php" class="btn-cuenta">
                    🔑 Iniciar Sesión
                </a>
            <?php endif; ?>
        </div>
    </div>
</header>

<nav>
    <a href="../index.php">Inicio</a>
    <a href="../views/historia.php">Historia</a>
    <a href="../views/plantilla.php">Plantilla</a>
    <a href="../views/partidos.php">Partidos</a>
    <a href="../views/contacto.php">Contacto</a>
</nav>