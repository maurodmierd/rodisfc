<?php
include 'includes/header.php';
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
    <h1>Benvido ó Ródis F.C.</h1>
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
