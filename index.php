<?php
include 'includes/header.php';
include 'includes/conexion.php';

// Obtener las 3 últimas noticias públicas
$stmt_noticias = $conexion->prepare("SELECT * FROM noticias WHERE categoria = 'publica' ORDER BY fecha DESC LIMIT 3");
$stmt_noticias->execute();
$noticias = $stmt_noticias->fetchAll(PDO::FETCH_ASSOC);

// Obtener los próximos 3 partidos
$stmt_partidos = $conexion->prepare("SELECT * FROM partido ORDER BY fecha ASC LIMIT 3");
$stmt_partidos->execute();
$partidos = $stmt_partidos->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container">
    <h1>Benvido ó Ródis F.C.</h1>
    <br>
    <br>
    <!-- Noticias -->
    <section id="noticias">
        <h2>📰 Noticias Recientes</h2>
        <div class="etiquetas">
            <?php if ($noticias): ?>
                <?php foreach ($noticias as $noticia): ?>
                    <div class="etiqueta noticia">
                        <h3><?php echo htmlspecialchars($noticia['titulo']); ?></h3>
                        <p><?php echo substr(strip_tags($noticia['contenido']), 0, 100); ?>...</p>
                        <a href="views/verNoticia.php?id=<?php echo $noticia['id']; ?>" class="leer-mas">Leer más</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No hay noticias públicas disponibles.</p>
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
                <?php foreach ($partidos as $partido): ?>
                    <div class="etiqueta partido">
                        <h3><?php echo htmlspecialchars($partido['equipo_local'] . " vs " . $partido['equipo_visitante']); ?></h3>
                        <p><strong>Fecha:</strong> <?php echo htmlspecialchars($partido['fecha']); ?> - <strong>Hora:</strong> <?php echo htmlspecialchars($partido['hora']); ?></p>
                        <p><strong>Lugar:</strong> <?php echo htmlspecialchars($partido['lugar']); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No hay partidos próximos registrados.</p>
            <?php endif; ?>
        </div>
    </section>
</div>

<?php include 'includes/footer.php'; ?>
