<?php
include '../includes/header.php';
include '../includes/conexion.php';

// Parámetros de filtrado
$equipo = isset($_GET['equipo']) ? $_GET['equipo'] : 'todos';
$mes = isset($_GET['mes']) ? intval($_GET['mes']) : date('n');
$ano = isset($_GET['ano']) ? intval($_GET['ano']) : date('Y');

// Función para obtener partidos del mes
function obtenerPartidosMes($conexion, $mes, $ano, $equipo) {
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

// Obtener partidos del mes actual
$partidos_mes = obtenerPartidosMes($conexion, $mes, $ano, $equipo);

// Agrupar partidos por día
$partidos_por_dia = [];
foreach ($partidos_mes as $partido) {
    $dia = date('j', strtotime($partido['fecha']));
    $partidos_por_dia[$dia][] = $partido;
}

// Función para formatear fecha
function formatearFecha($fecha) {
    return date('d/m/Y', strtotime($fecha));
}

// Obtener información del mes
$primer_dia = mktime(0, 0, 0, $mes, 1, $ano);
$dias_en_mes = date('t', $primer_dia);
$dia_semana_inicio = date('w', $primer_dia); // 0 = domingo
$meses = [
    1 => 'Xaneiro', 2 => 'Febreiro', 3 => 'Marzo', 4 => 'Abril',
    5 => 'Maio', 6 => 'Xuño', 7 => 'Xullo', 8 => 'Agosto',
    9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Decembro'
];
$nombre_mes = $meses[$mes];

// Ajustar para que lunes sea 0
$dia_semana_inicio = ($dia_semana_inicio == 0) ? 6 : $dia_semana_inicio - 1;
?>

<div class="container calendario-container">
    <h2><?php echo icon('fas fa-calendar-alt'); ?> Calendario de Partidos</h2>
    
    <!-- Filtros -->
    <div class="filtros-calendario">
        <!-- Filtro por equipo -->
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

    <!-- Navegación del calendario -->
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

    <!-- Calendario -->
    <div class="calendario">
        <!-- Cabecera de días de la semana -->
        <div class="calendario-header">
            <div class="dia-semana">Lun</div>
            <div class="dia-semana">Mar</div>
            <div class="dia-semana">Mér</div>
            <div class="dia-semana">Xov</div>
            <div class="dia-semana">Ven</div>
            <div class="dia-semana">Sáb</div>
            <div class="dia-semana">Dom</div>
        </div>
        
        <!-- Días del calendario -->
        <div class="calendario-grid">
            <?php
            // Días vacíos al inicio
            for ($i = 0; $i < $dia_semana_inicio; $i++) {
                echo '<div class="dia-vacio"></div>';
            }
            
            // Días del mes
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
                                   onclick="mostrarDetallePartido('. $partido['fecha'] . $partido['equipo'] . ')">';
                        echo '<div class="hora-mini">' . substr($partido['hora'], 0, 5) . '</div>';
                        echo '<div class="equipos-mini">' . 
                             ($partido['equipo']==='Veteranos'? 'VETERANOS' : 'SENIOR' ) . ' vs ' . 
                             $partido['equipo_rival'] . '</div>';
                        if ($es_pasado && isset($partido['goles_a_favor']) && isset($partido['goles_en_contra'])) {
                            echo '<div class="resultado-mini">' . $partido['goles_a_favor'] . '-' . $partido['goles_en_contra'] . '</div>';
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

    <!-- Leyenda -->
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

<!-- Modal para detalles del partido -->
<div id="modal-partido-detalle" class="modal">
    <div class="modal-contenido">
        <span class="cerrar-modal" onclick="cerrarModalPartido()">&times;</span>
        <div id="contenido-partido-detalle">
            <!-- El contenido se carga dinámicamente -->
        </div>
    </div>
</div>

<script src="js/partidos.js"></script>
<?php include '../includes/footer.php'; ?>
