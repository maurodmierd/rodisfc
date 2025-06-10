<?php
include '../includes/header.php';
include '../includes/conexion.php';

$equipo = isset($_GET['equipo']) ? $_GET['equipo'] : 'todos';
$mes = isset($_GET['mes']) ? intval($_GET['mes']) : date('m');
$ano = isset($_GET['ano']) ? intval($_GET['ano']) : date('Y');

function obtenerPartidosMes($conexion, $mes, $ano, $equipo)
{
    $sql = "SELECT * FROM partido WHERE MONTH(fecha) = :mes AND YEAR(fecha) = :ano";
    if ($equipo !== 'todos') {
        $sql .= " AND equipo = :equipo";
    }
    $sql .= " ORDER BY fecha ASC, hora ASC";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':mes', $mes);
    $stmt->bindParam(':ano', $ano);

    if ($equipo !== 'todos') {
        $stmt->bindParam(':equipo', $equipo);
    }

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$partidos_mes = obtenerPartidosMes($conexion, $mes, $ano, $equipo);
$partidos_por_dia = [];
foreach ($partidos_mes as $partido) {
    $dia = date('j', strtotime($partido['fecha']));
    $partidos_por_dia[$dia][] = $partido;
}

function formatearFecha($fecha)
{
    return date('d/m/Y', strtotime($fecha));
}

$primer_dia = mktime(0, 0, 0, $mes, 1, $ano);
$dias_en_mes = date('t', $primer_dia);
$dia_semana_inicio = date('w', $primer_dia); // 0 = domingo
$nombre_mes = strftime('%B', $primer_dia);
$nombre_mes = ucfirst($nombre_mes);
$dia_semana_inicio = ($dia_semana_inicio == 0) ? 6 : $dia_semana_inicio - 1;
?>

<div class="container calendario-container">
    <h2><?php echo icon('fas fa-calendar-alt'); ?> Calendario de Partidos</h2>

    <!-- Filtros -->
    <div class="filtros-calendario">
        <div class="filtro-grupo">
            <label><?php echo icon('fas fa-filter'); ?> Equipo:</label>
            <div class="filtro-equipos">
                <a href="?equipo=todos&mes=<?php echo $mes; ?>&ano=<?php echo $ano; ?>"
                    class="filtro-btn <?php echo $equipo === 'todos' ? 'active' : ''; ?>">
                    Todos
                </a>
                <a href="?equipo=senior&mes=<?php echo $mes; ?>&ano=<?php echo $ano; ?>"
                    class="filtro-btn <?php echo $equipo === 'senior' ? 'active' : ''; ?>">
                    Senior
                </a>
                <a href="?equipo=veteranos&mes=<?php echo $mes; ?>&ano=<?php echo $ano; ?>"
                    class="filtro-btn <?php echo $equipo === 'veteranos' ? 'active' : ''; ?>">
                    Veteranos
                </a>
            </div>
        </div>
    </div>

    <!-- Calendario -->
    <div class="navegacion-calendario">
        <?php
        // Calcular mes anterior
        $prev_mes = $mes - 1;
        $prev_ano = $ano;
        if ($prev_mes < 1) {
            $prev_mes = 12;
            $prev_ano--;
        }

        // Calcular mes siguiente
        $next_mes = $mes + 1;
        $next_ano = $ano;
        if ($next_mes > 12) {
            $next_mes = 1;
            $next_ano++;
        }
        ?>
        <a href="?mes=<?php echo $prev_mes; ?>&ano=<?php echo $prev_ano; ?>&equipo=<?php echo $equipo; ?>"
            class="btn-nav-calendario">
            <?php echo icon('fas fa-chevron-left'); ?>
        </a>

        <h3 class="mes-calendario"><?php echo $nombre_mes; ?> <?php echo $ano; ?></h3>

        <a href="?mes=<?php echo $next_mes; ?>&ano=<?php echo $next_ano; ?>&equipo=<?php echo $equipo; ?>"
            class="btn-nav-calendario">
            <?php echo icon('fas fa-chevron-right'); ?>
        </a>
    </div>
    <div class="calendario">
        <div class="calendario-header">
            <div class="dia-semana">Lun</div>
            <div class="dia-semana">Mar</div>
            <div class="dia-semana">Mér</div>
            <div class="dia-semana">Xov</div>
            <div class="dia-semana">Ven</div>
            <div class="dia-semana">Sáb</div>
            <div class="dia-semana">Dom</div>
        </div>
        <div class="calendario-grid">
            <?php
            for ($i = 0; $i < $dia_semana_inicio; $i++) {
                echo '<div class="dia-vacio"></div>';
            }
            for ($dia = 1; $dia <= $dias_en_mes; $dia++) {
                $es_hoy = ($dia == date('j') && $mes == date('m') && $ano == date('Y'));
                $tiene_partidos = isset($partidos_por_dia[$dia]);

                echo '<div class="dia-calendario ' . ($es_hoy ? 'hoy' : '') . ' ' . ($tiene_partidos ? 'con-partidos' : '') . '">';
                echo '<span class="numero-dia">' . $dia . '</span>';

                if ($tiene_partidos) {
                    echo '<div class="partidos-dia">';
                    foreach ($partidos_por_dia[$dia] as $partido) {
                        $es_pasado = strtotime($partido['fecha']) < strtotime('today');
                        echo '<div class="partido-mini ' . strtolower($partido['equipo']) . ' ' . ($es_pasado ? 'pasado' : 'futuro') . '" 
                                   onclick="mostrarDetallePartido(' . $partido['id'] . ')">';
                        echo '<div class="hora-mini">' . substr($partido['hora'], 0, 5) . '</div>';
                        echo '<div class="equipos-mini">' .
                            substr($partido['equipo_local'], 0, 3) . ' vs ' .
                            substr($partido['equipo_visitante'], 0, 3) . '</div>';
                        if ($es_pasado && isset($partido['goles_local']) && isset($partido['goles_visitante'])) {
                            echo '<div class="resultado-mini">' . $partido['goles_local'] . '-' . $partido['goles_visitante'] . '</div>';
                        }
                        echo '</div>';
                    }
                    echo '</div>';
                }

                echo '</div>';
            }
            ?>
        </div>
    </div>

    <div class="leyenda-calendario">
        <h4>Leyenda:</h4>
        <div class="leyenda-items">
            <div class="leyenda-item">
                <div class="color-muestra senior"></div>
                <span>Equipo Senior</span>
            </div>
            <div class="leyenda-item">
                <div class="color-muestra veteranos"></div>
                <span>Equipo Veteranos</span>
            </div>
            <div class="leyenda-item">
                <div class="color-muestra pasado"></div>
                <span>Partido xogado</span>
            </div>
            <div class="leyenda-item">
                <div class="color-muestra futuro"></div>
                <span>Próximo partido</span>
            </div>
        </div>
    </div>
</div>

<!-- Modal para detalles do partido -->
<div id="modal-partido-detalle" class="modal">
    <div class="modal-contenido">
        <span class="cerrar-modal" onclick="cerrarModalPartido()">&times;</span>
        <div id="contenido-partido-detalle">
        </div>
    </div>
</div>

<script src="js/partidos.js"></script>
<?php include '../includes/footer.php'; ?>