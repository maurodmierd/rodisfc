<?php
include 'includes/header.php';
include 'includes/conexion.php';

$mes = isset($_GET['mes']) ? intval($_GET['mes']) : date('m');
$ano = isset($_GET['ano']) ? intval($_GET['ano']) : date('Y');
$fecha_inicio = "$ano-$mes-01";
$fecha_fin = date("Y-m-t", strtotime($fecha_inicio));

$stmt = $conexion->prepare("SELECT * FROM noticias WHERE fecha BETWEEN :inicio AND :fin");
$stmt->bindParam(':inicio', $fecha_inicio);
$stmt->bindParam(':fin', $fecha_fin);
$stmt->execute();
$noticias = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container">
    <h2>Noticias de <?php echo strftime('%B %Y', strtotime($fecha_inicio)); ?></h2>
    <div class="navegacion-fechas">
    <?php
        $prev_mes = $mes - 1;
        $prev_ano = $ano;
        if ($prev_mes < 1) {
            $prev_mes = 12;
            $prev_ano--;
        }

        $next_mes = $mes + 1;
        $next_ano = $ano;
        if ($next_mes > 12) {
            $next_mes = 1;
            $next_ano++;
        }
    ?>
        <a href="noticias.php?mes=<?php echo $prev_mes; ?>&ano=<?php echo $prev_ano; ?>">⬅️ Mes anterior</a>
        <a href="noticias.php?mes=<?php echo $next_mes; ?>&ano=<?php echo $next_ano; ?>">Mes seguinte ➡️</a>
    </div>

    <div class="listado-noticias">
        <?php foreach ($noticias as $noticia): ?>
            <?php
            $es_privada = $noticia['categoria'] === 'privada';
            if (!$es_privada || isset($_SESSION['usuario'])):
            ?>
                <div class="noticia">
                    <h3>
                        <?php echo htmlspecialchars($noticia['titulo']); ?>
                        <span class="etiqueta <?php echo $es_privada ? 'privada' : 'publica'; ?>">
                            <?php echo $es_privada ? 'Privada' : 'Pública'; ?>
                        </span>
                    </h3>
                    <p><strong>Data:</strong> <?php echo date('d/m/Y', strtotime($noticia['fecha'])); ?></p>
                    <p><?php echo nl2br(htmlspecialchars(substr($noticia['contenido'], 0, 300))) . '...'; ?></p>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>