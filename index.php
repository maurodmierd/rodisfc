<?php
include('includes/header.php');
include('includes/conexion.php');

// // Obtener las 3 últimas noticias
// $resultado_noticias = $conexion->query("SELECT * FROM noticias ORDER BY fecha_publicacion DESC LIMIT 3");

// // Obtener los próximos 3 partidos
// $resultado_partidos = $conexion->query("SELECT * FROM partidos ORDER BY fecha ASC LIMIT 3");
?>

<!-- Contenido de la página -->
<div class="container">
    <h1>Benvido a Ródis F.C.</h1>

    <!-- Sección de Noticias -->
    <section id="noticias">
        <h2>Noticias Recientes</h2>
        <?php if ($resultado_noticias->num_rows > 0): ?>
            <div class="noticias">
                <?php while ($noticia = $resultado_noticias->fetch_assoc()): ?>
                    <div class="noticia">
                        <h3><?php echo $noticia['titulo']; ?></h3>
                        <p><?php echo substr($noticia['contenido'], 0, 150); ?>...</p>
                        <a href="#">Leer más</a>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </section>

    <!-- Sección de Próximos Partidos -->
    <section id="partidos">
        <h2>Próximos Partidos</h2>
        <?php if ($resultado_partidos->num_rows > 0): ?>
            <div class="partidos">
                <?php while ($partido = $resultado_partidos->fetch_assoc()): ?>
                    <div class="partido">
                        <h3><?php echo $partido['equipo_local'] . " vs " . $partido['equipo_visitante']; ?></h3>
                        <p>Fecha: <?php echo $partido['fecha']; ?> | Hora: <?php echo $partido['hora']; ?></p>
                        <p>Lugar: <?php echo $partido['lugar']; ?></p>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </section>
</div>

<?php
include('includes/footer.php');
?>
