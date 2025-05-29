<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Rodís F.C.</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Rodís F.C. - Toda a actualidade do club: últimas noticias, próximos partidos, plantilla e contacto. Club de fútbol galego en constante crecemento.">
    <meta name="keywords" content="Rodís F.C., fútbol, Galicia, noticias, partidos, plantilla, club de fútbol, deportes, equipo galego,rodis,cerceda">
    <meta name="author" content="Rodís F.C.">
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

<?php
include 'includes/conexion.php';

// Obter as úktimas 3 noticias publicas.
$stmt_noticias = $conexion->prepare("SELECT * FROM noticias WHERE categoria = 'publica' ORDER BY fecha DESC LIMIT 3");
$stmt_noticias->execute();
$noticias = $stmt_noticias->fetchAll(PDO::FETCH_ASSOC);

// Obter os próximos 3 partidos.
$stmt_partidos = $conexion->prepare("SELECT * FROM partido ORDER BY fecha ASC LIMIT 3");
$stmt_partidos->execute();
$partidos = $stmt_partidos->fetchAll(PDO::FETCH_ASSOC);
?>



<div class="container">
    <h1>Benvido ó Rodís F.C.</h1>
    <br>
    <br>
    <!-- Noticias -->
    <section id="noticias">
        <h2>📰 Noticias Recentes</h2>
        <div class="etiquetas">
            <?php if ($noticias): ?>
                <!-- Iterar sobre as noticias e amosalas -->
                <?php foreach ($noticias as $noticia): ?>
                    <div class="etiqueta noticia">
                        <h3><?php echo htmlspecialchars($noticia['titulo']); ?></h3>
                        <p><?php echo substr(strip_tags($noticia['contenido']), 0, 100); ?>...</p>
                        <a href="views/verNoticia.php?id=<?php echo $noticia['id']; ?>" class="leer-mas">Leer máis</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Non hai noticias públicas para amosar.</p>
            <?php endif; ?>
        </div>
    </section>
    <br>
    <br>
    <br>
    <!-- Partidos -->
    <section id="partidos">
        <h2>⚽ Próximos Partidos</h2>
        <div class="etiquetas">
            <?php if ($partidos): ?>
                <!-- Iterar sobre os partidos e amosalos -->
                <?php foreach ($partidos as $partido): ?>
                    <div class="etiqueta partido">
                        <h3><?php echo htmlspecialchars($partido['equipo_local'] . " vs " . $partido['equipo_visitante']); ?></h3>
                        <p><strong>Data:</strong> <?php echo htmlspecialchars($partido['fecha']); ?> - <strong>Hora:</strong> <?php echo htmlspecialchars($partido['hora']); ?></p>
                        <p><strong>Lugar:</strong> <?php echo htmlspecialchars($partido['lugar']); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Non hai partidos próximos rexistrados.</p>
            <?php endif; ?>
        </div>
    </section>
</div>

<?php include 'includes/footer.php'; ?>
