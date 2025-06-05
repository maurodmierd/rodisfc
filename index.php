<?php
include 'includes/header.php';
include 'includes/conexion.php';

// Obter as últimas 5 noticias públicas para o carrusel.
$stmt_noticias = $conexion->prepare("SELECT * FROM noticias WHERE categoria = 'publica' ORDER BY fecha DESC LIMIT 5");
$stmt_noticias->execute();
$noticias = $stmt_noticias->fetchAll(PDO::FETCH_ASSOC);

// Obter partidos anteriores e próximos
$stmt_partidos_anteriores = $conexion->prepare("
    SELECT * FROM partido 
    WHERE fecha < CURDATE() 
    ORDER BY fecha DESC, equipo 
    LIMIT 2
");
$stmt_partidos_anteriores->execute();
$partidos_anteriores = $stmt_partidos_anteriores->fetchAll(PDO::FETCH_ASSOC);
$stmt_partidos_proximos = $conexion->prepare("
    SELECT * FROM partido 
    WHERE fecha >= CURDATE() 
    ORDER BY fecha ASC, equipo 
    LIMIT 2
");
$stmt_partidos_proximos->execute();
$partidos_proximos = $stmt_partidos_proximos->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="hero-section">
    <div class="hero-content">
        <h1 class="hero-title">
            <span class="icon">⚽</span>
            Benvido ó Rodís F.C.
        </h1>
        <p class="hero-subtitle">RODÍS, GHU GHA!</p>
    </div>
</div>

<div class="container">
    <!-- Sección de Noticias Carrusel -->
    <section id="noticias" class="seccion-animada">
        <div class="seccion-header">
            <h2><span class="icon">📰</span> Noticias Recentes</h2>
            <p>Mantente ao día coas últimas novidades do club</p>
        </div>
        
        <div class="carrusel-container">
            <div class="carrusel-wrapper">
                <div class="carrusel-track" id="carrusel-noticias">
                    <?php if ($noticias): ?>
                        <?php foreach ($noticias as $index => $noticia): ?>
                            <div class="carrusel-slide <?php echo $index === 0 ? 'active' : ''; ?>">
                                <div class="noticia-card">
                                    <div class="noticia-imagen">
                                        <?php if (!empty($noticia['imagen'])): ?>
                                            <img src="<?php echo htmlspecialchars($noticia['imagen']); ?>" alt="<?php echo htmlspecialchars($noticia['titulo']); ?>">
                                        <?php else: ?>
                                            <div class="imagen-placeholder">
                                                <span class="icon">📰</span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="noticia-contenido">
                                        <h3><?php echo htmlspecialchars($noticia['titulo']); ?></h3>
                                        <p><?php echo substr(strip_tags($noticia['contenido']), 0, 100); ?>...</p>
                                        <button class="btn-leer-mas" 
                                                onclick="abrirModalNoticia(<?php echo $noticia['id']; ?>)"
                                                data-noticia-id="<?php echo $noticia['id']; ?>"
                                                data-slide-index="<?php echo $index; ?>"
                                                type="button">
                                            <span class="icon">👁️</span> Ler máis
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="carrusel-slide active">
                            <div class="no-content">
                                <span class="icon">📭</span>
                                <p>Non hai noticias públicas para amosar.</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Controis do carrusel  -->
            <div class="carrusel-controles">
                <button class="carrusel-btn prev" id="prev-noticia">
                    <span class="icon">◀️</span>
                </button>
                <div class="carrusel-indicadores" id="indicadores-noticias">
                    <?php for ($i = 0; $i < count($noticias); $i++): ?>
                        <span class="indicador <?php echo $i === 0 ? 'active' : ''; ?>" data-slide="<?php echo $i; ?>"></span>
                    <?php endfor; ?>
                </div>
                <button class="carrusel-btn next" id="next-noticia">
                    <span class="icon">▶️</span>
                </button>
            </div>
        </div>
    </section>

    <!-- Sección dos partidos -->
    <section id="partidos" class="seccion-animada">
        <div class="seccion-header">
            <h2><span class="icon">⚽</span> Partidos</h2>
            <p>Resultados recentes e próximos encontros</p>
        </div>

        <!-- Partidos Anteriores -->
        <div class="partidos-grupo">
            <h3 class="grupo-titulo">
                <span class="icon">📊</span> Últimos Resultados
            </h3>
            <div class="partidos-grid">
                <?php if ($partidos_anteriores): ?>
                    <?php foreach ($partidos_anteriores as $partido): ?>
                        <div class="partido-card anterior">
                            <div class="partido-header">
                                <span class="equipo-badge <?php echo strtolower($partido['equipo']); ?>">
                                    <?php echo ucfirst($partido['equipo']); ?>
                                </span>
                                <span class="partido-fecha">
                                    <span class="icon">📅</span>
                                    <?php echo date('d/m/Y', strtotime($partido['fecha'])); ?>
                                </span>
                            </div>
                            <div class="partido-equipos">
                                <div class="equipo local">
                                    <span class="equipo-nombre"><?php echo htmlspecialchars($partido['equipo_local']); ?></span>
                                </div>
                                <div class="vs">
                                    <span class="resultado">
                                        <?php if (isset($partido['goles_local']) && isset($partido['goles_visitante'])): ?>
                                            <?php echo $partido['goles_local']; ?> - <?php echo $partido['goles_visitante']; ?>
                                        <?php else: ?>
                                            VS
                                        <?php endif; ?>
                                    </span>
                                </div>
                                <div class="equipo visitante">
                                    <span class="equipo-nombre"><?php echo htmlspecialchars($partido['equipo_visitante']); ?></span>
                                </div>
                            </div>
                            <div class="partido-info">
                                <span class="icon">📍</span>
                                <?php echo htmlspecialchars($partido['lugar']); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-content">
                        <span class="icon">📭</span>
                        <p>Non hai resultados recentes.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Próximos Partidos -->
        <div class="partidos-grupo">
            <h3 class="grupo-titulo">
                <span class="icon">🔜</span> Próximos Partidos
            </h3>
            <div class="partidos-grid">
                <?php if ($partidos_proximos): ?>
                    <?php foreach ($partidos_proximos as $partido): ?>
                        <div class="partido-card proximo">
                            <div class="partido-header">
                                <span class="equipo-badge <?php echo strtolower($partido['equipo']); ?>">
                                    <?php echo ucfirst($partido['equipo']); ?>
                                </span>
                                <span class="partido-fecha">
                                    <span class="icon">📅</span>
                                    <?php echo date('d/m/Y', strtotime($partido['fecha'])); ?>
                                </span>
                            </div>
                            <div class="partido-equipos">
                                <div class="equipo local">
                                    <span class="equipo-nombre"><?php echo htmlspecialchars($partido['equipo_local']); ?></span>
                                </div>
                                <div class="vs">
                                    <span class="hora">
                                        <span class="icon">🕐</span>
                                        <?php echo htmlspecialchars($partido['hora']); ?>
                                    </span>
                                </div>
                                <div class="equipo visitante">
                                    <span class="equipo-nombre"><?php echo htmlspecialchars($partido['equipo_visitante']); ?></span>
                                </div>
                            </div>
                            <div class="partido-info">
                                <span class="icon">📍</span>
                                <?php echo htmlspecialchars($partido['lugar']); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-content">
                        <span class="icon">📭</span>
                        <p>Non hai partidos próximos rexistrados.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>

<script src="../src/index.js"></script>
<?php include 'includes/footer.php'; ?>
