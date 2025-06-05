<?php
    include '../includes/header.php';
    include '../includes/conexion.php';

    if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
        header("Location: ../login/areaPrivada.php");
        exit();
    }

    $stmt = $conexion->query("SELECT * FROM usuarios");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<script src="js/gestionUsuarios.js"></script>
<h2>Lista de Usuarios</h2>
<table class="tabla-usuarios">
    <tr>
        <th>DNI</th>
        <th>Nombre</th>
        <th>Email</th>
        <th>Rol</th>
        <th>Acciones</th>
    </tr>
    <?php foreach ($usuarios as $usuario): ?>
        <tr>
            <td><?= htmlspecialchars($usuario['id']) ?></td>
            <td><?= htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellidos']) ?></td>
            <td><?= htmlspecialchars($usuario['email']) ?></td>
            <td><?= htmlspecialchars($usuario['rol']) ?></td>
            <td>
                <button class="btn-editar" 
                    data-id="<?= $usuario['id'] ?>" 
                    data-nombre="<?= $usuario['nombre'] ?>"
                    data-apellidos="<?= $usuario['apellidos'] ?>" 
                    data-email="<?= $usuario['email'] ?>"
                    data-telefono="<?= $usuario['telefono'] ?>" 
                    data-rol="<?= $usuario['rol'] ?>">📝 Editar</button>
                <button onclick="confirmarEliminacion('<?= $usuario['id'] ?>')">🗑️ Eliminar</button>
                <button onclick="anotar('<?= $usuario['id'] ?>')">📌 Anotaciones</button>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<!-- Formulario para a edición -->
<div id="modal-editar" class="modal" style="display:none;">
    <form id="form-editar" method="POST" action="../api/users/actualizarUsuario.php" onsubmit="return confirmarEdicion()">
        <h3>Editar Usuario</h3>
        <input type="hidden" name="id" id="edit-id">
        <input type="text" name="nombre" id="edit-nombre" placeholder="Nombre" required>
        <input type="text" name="apellidos" id="edit-apellidos" placeholder="Apellidos" required>
        <input type="email" name="email" id="edit-email" placeholder="Correo electrónico">
        <input type="text" name="telefono" id="edit-telefono" placeholder="Teléfono">
        <select name="rol" id="edit-rol" required>
            <option value="socio">Socio</option>
            <option value="jugador">Xogador</option>
            <option value="admin">Administrador</option>
        </select>
        <br><br>
        <button type="submit">Gardar</button>
        <button type="button" onclick="cerrarModal()">Cancelar</button>
    </form>
</div>

<!-- Formulario de anotaciones -->
<div id="modal-notas" class="modal" style="display:none;">
    <form method="POST" action="../api/users/guardarNotas.php">
        <h3>Anotación para usuario</h3>
        <input type="hidden" name="usuario_id" id="nota-id">
        <textarea name="nota" id="nota-texto" placeholder="Escribe unha nota..." rows="5" cols="40" required></textarea>
        <br>
        <button type="submit">Gardar nota</button>
        <button type="button" onclick="cerrarNotas()">Pechar</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
