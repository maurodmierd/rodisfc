<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Rodís F.C.</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../img/logos/logo.png">
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
<!-- Si hai unha sesión iniciada, mostra o botón de Área Privada e Cerrar sesión -->
<?php if (session_status() === PHP_SESSION_NONE) session_start() ?>
<header class="header">
    <img src="../img/logos/logo.png" alt="Escudo do Rodís F.C." class="logo">

    <?php if (isset($_SESSION['usuario'])): ?>
        <a href="../socios/areaPrivada.php" class="btn-cuenta">Área Privada</a>
        <a href="../socios/logout.php" class="btn-cuenta">Cerrar sesión</a>
    <?php else: ?>
        <a href="../socios/login.php" class="btn-cuenta">Iniciar Sesión</a>
    <?php endif; ?>
</header>

<!-- Barra de navegación -->
<nav>
    <a href="../index.php">Inicio</a>
    <a href="../views/plantilla.php">Plantilla</a>
    <a href="../views/partidos.php">Partidos</a>
    <a href="../views/contacto.php">Contacto</a>
</nav>

