<?php
session_start();
include '../includes/header.php';
include '../includes/conexion.php';

// Función para obter xogadores por equipo
function obtenerJugadores($conexion, $equipo) {
    try {
        $stmt = $conexion->prepare("SELECT * FROM jugadores WHERE equipo = ? ORDER BY dorsal ASC");
        $stmt->execute([$equipo]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo ("Error recibindo xogadores: " . $e->getMessage());
        return [];
    }
}

// Obter xogadores de ambos equipos
$jugadores_senior = obtenerJugadores($conexion, 'senior');
$jugadores_veteranos = obtenerJugadores($conexion, 'veteranos');
?>


<div class="container">
    <h2 class="anton">Plantilla del Club</h2>
    <p>Conoce a nuestros jugadores de ambos equipos</p>

    <!-- Equipo Senior -->
    <div class="equipo-seccion">
        <h3 class="titulo-equipo senior">🏆 EQUIPO SENIOR</h3>
        <div class="jugadores-grid" id="jugadores-senior">
            <?php if (empty($jugadores_senior)): ?>
                <div class="no-jugadores">
                    <p>No hay jugadores registrados en el equipo senior</p>
                </div>
            <?php else: ?>
                <?php foreach ($jugadores_senior as $jugador): ?>
                    <div class="jugador-card" onclick="mostrarDetallesXogador(<?php echo htmlspecialchars(json_encode($jugador)); ?>)">
                        <div class="jugador-foto">
                            <?php if (!empty($jugador['foto']) && file_exists($jugador['foto'])): ?>
                                <img src="<?php echo htmlspecialchars($jugador['foto']); ?>" alt="<?php echo htmlspecialchars($jugador['nombre']); ?>">
                            <?php else: ?>
                                <div class="foto-placeholder">
                                    <span class="inicial"><?php echo strtoupper(substr($jugador['nombre'], 0, 1)); ?></span>
                                </div>
                            <?php endif; ?>
                            <div class="dorsal-overlay">
                                <span class="dorsal"><?php echo htmlspecialchars($jugador['dorsal']); ?></span>
                            </div>
                        </div>
                        <div class="jugador-info">
                            <h4><?php echo htmlspecialchars($jugador['nombre']); ?></h4>
                            <p class="posicion"><?php echo htmlspecialchars($jugador['posicion'] ?? 'Sin posición'); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>




    <!-- Equipo Veteranos -->
    <div class="equipo-seccion">
        <h3 class="titulo-equipo veteranos">🥇 EQUIPO VETERANOS</h3>
        <div class="jugadores-grid" id="jugadores-veteranos">
            <?php if (empty($jugadores_veteranos)): ?>
                <div class="no-jugadores">
                    <p>No hay jugadores registrados en el equipo veteranos</p>
                </div>
            <?php else: ?>
                <?php foreach ($jugadores_veteranos as $jugador): ?>
                    <div class="jugador-card" onclick="mostrarDetallesXogador(<?php echo htmlspecialchars(json_encode($jugador)); ?>)">
                        <div class="jugador-foto">
                            <?php if (!empty($jugador['foto']) && file_exists($jugador['foto'])): ?>
                                <img src="<?php echo htmlspecialchars($jugador['foto']); ?>" alt="<?php echo htmlspecialchars($jugador['nombre']); ?>">
                            <?php else: ?>
                                <div class="foto-placeholder">
                                    <span class="inicial"><?php echo strtoupper(substr($jugador['nombre'], 0, 1)); ?></span>
                                </div>
                            <?php endif; ?>
                            <div class="dorsal-overlay">
                                <span class="dorsal"><?php echo htmlspecialchars($jugador['dorsal']); ?></span>
                            </div>
                        </div>
                        <div class="jugador-info">
                            <h4><?php echo htmlspecialchars($jugador['nombre']); ?></h4>
                            <p class="posicion"><?php echo htmlspecialchars($jugador['posicion'] ?? 'Sin posición'); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Ventana para mostrar detalles del jugador -->
<div id="modal-jugador" class="modal" style="display: none;">
    <div class="modal-contenido jugador-modal">
        <span class="cerrar-modal" onclick="cerrarModal()">&times;</span>
        <div class="jugador-detalle">
            <div class="jugador-foto-grande">
                <img id="modal-foto" src="/placeholder.svg" alt="">
                <div class="dorsal-grande">
                    <span id="modal-dorsal"></span>
                </div>
            </div>
            <div class="jugador-datos">
                <h3 id="modal-nombre"></h3>
                <div class="datos-grid">
                    <div class="dato">
                        <label>Equipo:</label>
                        <span id="modal-equipo"></span>
                    </div>
                    <div class="dato">
                        <label>Posición:</label>
                        <span id="modal-posicion"></span>
                    </div>
                    <div class="dato">
                        <label>Edad:</label>
                        <span id="modal-edad"></span>
                    </div>
                    <div class="dato">
                        <label>Altura:</label>
                        <span id="modal-altura"></span>
                    </div>
                    <div class="dato">
                        <label>Peso:</label>
                        <span id="modal-peso"></span>
                    </div>
                    <div class="dato">
                        <label>Teléfono:</label>
                        <span id="modal-telefono"></span>
                    </div>
                    <div class="dato">
                        <label>Email:</label>
                        <span id="modal-email"></span>
                    </div>
                    <div class="dato dato-completo">
                        <label>Observaciones:</label>
                        <span id="modal-observaciones"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="jugadores.js"></script>

</body>
</html>

<?php include '../includes/footer.php'; ?>