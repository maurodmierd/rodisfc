<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ródis F.C.</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php session_start(); ?>

<header>
    <div class="header-flex">
        <img src="img/logos/logo.png" alt="Escudo de Ródis F.C." class="logo">

        <?php if (isset($_SESSION['usuario'])): ?>
            <a href="socios/areaPrivada.php" class="btn-cuenta">Zona Privada</a>
            <a href="socios/logout.php" class="btn-cuenta">Cerrar sesión</a>
        <?php else: ?>
            <a href="socios/login.php" class="btn-cuenta">Mi cuenta</a>
        <?php endif; ?>
    </div>
</header>

<nav>
    <a href="/index.php">Inicio</a>
    <a href="/socios/login.php">Acceso Socios</a>
    <a href="/views/plantilla.php">Plantilla</a>
    <a href="/views/calendario.php">Calendario</a>
    <a href="/views/contacto.php">Contacto</a>
</nav>

