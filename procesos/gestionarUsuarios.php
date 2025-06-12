<?php
include '../includes/header.php';
include '../includes/conexion.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
    header("Location: ../login/areaPrivada.php");
    exit();
}

// Obtener usuarios y jugadores
$stmt_usuarios = $conexion->query("SELECT * FROM usuarios ORDER BY rol, nombre");
$usuarios = $stmt_usuarios->fetchAll(PDO::FETCH_ASSOC);

$stmt_jugadores = $conexion->query("SELECT * FROM jugadores ORDER BY equipo, dorsal");
$jugadores = $stmt_jugadores->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container">
    <h2><?php echo icon('fas fa-users-cog'); ?> Gestionar Usuarios</h2>
    
    <!-- Tabs para alternar entre usuarios y jugadores -->
    <div class="tabs-container">
        <div class="tabs-header">
            <button class="tab-btn active" onclick="cambiarTab('usuarios')">
                <?php echo icon('fas fa-user-tie'); ?> Usuarios (Socios/Admins)
            </button>
            <button class="tab-btn" onclick="cambiarTab('jugadores')">
                <?php echo icon('fas fa-futbol'); ?> Xogadores
            </button>
        </div>
        
        <!-- Tab de Usuarios -->
        <div id="tab-usuarios" class="tab-content active">
            <h3>Lista de Usuarios</h3>
            <?php if (!empty($usuarios)): ?>
                <div class="tabla-responsive">
                    <table class="tabla-usuarios">
                        <thead>
                            <tr>
                                <th>DNI</th>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Teléfono</th>
                                <th>Rol</th>
                                <th>Accións</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usuarios as $usuario): ?>
                                <tr>
                                    <td><?= htmlspecialchars($usuario['id']) ?></td>
                                    <td><?= htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellidos']) ?></td>
                                    <td><?= htmlspecialchars($usuario['email'] ?? 'Sen email') ?></td>
                                    <td><?= htmlspecialchars($usuario['telefono'] ?? 'Sen teléfono') ?></td>
                                    <td>
                                        <span class="rol-badge <?= $usuario['rol'] ?>">
                                            <?= ucfirst($usuario['rol']) ?>
                                        </span>
                                    </td>
                                    <td class="acciones">
                                        <button class="btn-editar" 
                                            data-tipo="usuario"
                                            data-id="<?= $usuario['id'] ?>" 
                                            data-nombre="<?= $usuario['nombre'] ?>"
                                            data-apellidos="<?= $usuario['apellidos'] ?>" 
                                            data-email="<?= $usuario['email'] ?>"
                                            data-telefono="<?= $usuario['telefono'] ?>" 
                                            data-rol="<?= $usuario['rol'] ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn-eliminar" onclick="confirmarEliminacion('<?= $usuario['id'] ?>', 'usuario')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="no-content">
                    <i class="fas fa-inbox no-content-icon"></i>
                    <p>Non hai usuarios rexistrados.</p>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Tab de Jugadores -->
        <div id="tab-jugadores" class="tab-content">
            <h3>Lista de Xogadores</h3>
            <?php if (!empty($jugadores)): ?>
                <div class="tabla-responsive">
                    <table class="tabla-usuarios">
                        <thead>
                            <tr>
                                <th>DNI</th>
                                <th>Nome</th>
                                <th>Equipo</th>
                                <th>Dorsal</th>
                                <th>Posición</th>
                                <th>Idade</th>
                                <th>Accións</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($jugadores as $jugador): ?>
                                <tr>
                                    <td><?= htmlspecialchars($jugador['dni']) ?></td>
                                    <td><?= htmlspecialchars($jugador['nombre'] . ' ' . $jugador['apellidos']) ?></td>
                                    <td>
                                        <span class="equipo-badge <?= $jugador['equipo'] ?>">
                                            <?= ucfirst($jugador['equipo']) ?>
                                        </span>
                                    </td>
                                    <td class="dorsal-cell"><?= htmlspecialchars($jugador['dorsal']) ?></td>
                                    <td><?= htmlspecialchars($jugador['posicion'] ?? 'Sen posición') ?></td>
                                    <td><?= htmlspecialchars($jugador['edad'] ?? 'Sen idade') ?></td>
                                    <td class="acciones">
                                        <button class="btn-editar" 
                                            data-tipo="jugador"
                                            data-id="<?= $jugador['dni'] ?>" 
                                            data-nombre="<?= $jugador['nombre'] ?>"
                                            data-apellidos="<?= $jugador['apellidos'] ?>" 
                                            data-equipo="<?= $jugador['equipo'] ?>"
                                            data-dorsal="<?= $jugador['dorsal'] ?>"
                                            data-posicion="<?= $jugador['posicion'] ?>"
                                            data-edad="<?= $jugador['edad'] ?>"
                                            data-foto-id="<?= $jugador['foto_id'] ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn-eliminar" onclick="confirmarEliminacion('<?= $jugador['dni'] ?>', 'jugador')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="no-content">
                    <i class="fas fa-inbox no-content-icon"></i>
                    <p>Non hai xogadores rexistrados.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal para editar usuario -->
<div id="modal-editar" class="modal" style="display:none;">
    <div class="modal-contenido">
        <span class="cerrar-modal" onclick="cerrarModal()">&times;</span>
        <h3 id="modal-titulo"><?php echo icon('fas fa-edit'); ?> Editar Usuario</h3>
        <form id="form-editar" method="POST" action="../api/users/actualizarUsuario.php" onsubmit="return confirmarEdicion()">
            <input type="hidden" name="tipo" id="edit-tipo">
            <input type="hidden" name="id" id="edit-id">
            
            <!-- Campos comunes -->
            <div class="form-group">
                <label for="edit-nombre"><?php echo icon('fas fa-user'); ?> Nome:</label>
                <input type="text" name="nombre" id="edit-nombre" required>
            </div>
            
            <div class="form-group">
                <label for="edit-apellidos"><?php echo icon('fas fa-users'); ?> Apelidos:</label>
                <input type="text" name="apellidos" id="edit-apellidos" required>
            </div>
            
            <!-- Campos para usuarios -->
            <div id="campos-usuario-edit" style="display: none;">
                <div class="form-group">
                    <label for="edit-email"><?php echo icon('fas fa-envelope'); ?> Email:</label>
                    <input type="email" name="email" id="edit-email">
                </div>
                
                <div class="form-group">
                    <label for="edit-telefono"><?php echo icon('fas fa-phone'); ?> Teléfono:</label>
                    <input type="text" name="telefono" id="edit-telefono">
                </div>
                
                <div class="form-group">
                    <label for="edit-rol"><?php echo icon('fas fa-user-tag'); ?> Rol:</label>
                    <select name="rol" id="edit-rol">
                        <option value="socio">Socio</option>
                        <option value="admin">Administrador</option>
                    </select>
                </div>
            </div>
            
            <!-- Campos para jugadores -->
            <div id="campos-jugador-edit" style="display: none;">
                <div class="form-group">
                    <label for="edit-equipo"><?php echo icon('fas fa-users'); ?> Equipo:</label>
                    <select name="equipo" id="edit-equipo">
                        <option value="senior">Senior</option>
                        <option value="veteranos">Veteranos</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="edit-dorsal"><?php echo icon('fas fa-hashtag'); ?> Dorsal:</label>
                    <input type="number" name="dorsal" id="edit-dorsal" min="1" max="99">
                </div>
                
                <div class="form-group">
                    <label for="edit-posicion"><?php echo icon('fas fa-running'); ?> Posición:</label>
                    <select name="posicion" id="edit-posicion">
                        <option value="">Seleccionar posición...</option>
                        <option value="Porteiro">Porteiro</option>
                        <option value="Defensa">Defensa</option>
                        <option value="Centrocampista">Centrocampista</option>
                        <option value="Delanteiro">Delanteiro</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="edit-edad"><?php echo icon('fas fa-birthday-cake'); ?> Idade:</label>
                    <input type="number" name="edad" id="edit-edad" min="16" max="50">
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn-insertar">
                    <?php echo icon('fas fa-save'); ?> Gardar
                </button>
                <button type="button" class="btn-limpiar" onclick="cerrarModal()">
                    <?php echo icon('fas fa-times'); ?> Cancelar
                </button>
            </div>
        </form>
    </div>
</div>

<script src="js/gestionUsuarios.js"></script>
<?php include '../includes/footer.php'; ?>
