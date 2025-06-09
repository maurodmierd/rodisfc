<?php
// Script para evitar acceso non autorizado
include '../includes/header.php';
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
    header("Location: ../login/areaPrivada.php");
    exit();
}
include '../includes/conexion.php';

// Comproba se o formulario foi enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $tipo_usuario = $_POST['tipo_usuario'];
    $dni = $_POST['dni'];
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    
    try {
        if ($tipo_usuario === 'socio' || $tipo_usuario === 'admin') {
            $telefono = $_POST['telefono'] ?? null;
            $email = $_POST['email'] ?? null;
            $fecha = date('Y-m-d');
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $rol = $tipo_usuario; // 'socio' ou 'admin'

            $stmt = $conexion->prepare("INSERT INTO usuarios (id, nombre, apellidos, telefono, email, fecha_registro, contraseña, rol) 
                                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$dni, $nombre, $apellidos, $telefono, $email, $fecha, $password, $rol]);

            echo "<div class='mensaje-exito'><p>✅ " . ucfirst($tipo_usuario) . " insertado correctamente</p></div>";
            
        } elseif ($tipo_usuario === 'jugador') {
            // Insertar jugador
            $equipo = $_POST['equipo'];
            $dorsal = $_POST['dorsal'];
            $posicion = $_POST['posicion'] ?? null;
            $edad = $_POST['edad'] ?? null;
            $foto_id = $_POST['foto'] ?? null;

            $stmt = $conexion->prepare("INSERT INTO jugadores (dni, nombre, apellidos, equipo, dorsal, posicion, edad, foto_id) 
                                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$dni, $nombre, $apellidos, $equipo, $dorsal, $posicion, $edad, $foto_id]);

            echo "<div class='mensaje-exito'><p>✅ Xogador insertado correctamente</p></div>";
        }
    } catch (PDOException $e) {
        echo "<div class='mensaje-error'><p>❌ Erro ao insertar: " . $e->getMessage() . "</p></div>";
    }
}
?>

<div class="container">
    <h2><span class="icon">👥</span> Insertar Usuario</h2>
    <p>Engade novos usuarios ao sistema: socios, xogadores ou administradores</p>

    <form method="POST" id="form-insertar-usuario">
        <div class="form-group">
            <label for="tipo_usuario">
                <span class="icon">🎯</span> Tipo de Usuario:
            </label>
            <select name="tipo_usuario" id="tipo_usuario" required onchange="cambiarCampos()">
                <option value="">Seleccionar tipo...</option>
                <option value="socio">👤 Socio</option>
                <option value="jugador">⚽ Xogador</option>
                <option value="admin">🔧 Administrador</option>
            </select>
        </div>
        <!-- comunes -->
        <div class="campos-comunes">
            <div class="form-group">
                <label for="dni">
                    <span class="icon">🆔</span> DNI:
                </label>
                <input type="text" name="dni" id="dni" placeholder="DNI" required>
            </div>

            <div class="form-group">
                <label for="nombre">
                    <span class="icon">👤</span> Nome:
                </label>
                <input type="text" name="nombre" id="nombre" placeholder="Nome" required>
            </div>

            <div class="form-group">
                <label for="apellidos">
                    <span class="icon">👥</span> Apelidos:
                </label>
                <input type="text" name="apellidos" id="apellidos" placeholder="Apelidos" required>
            </div>

            
        </div>
        <!-- socios y admins -->
        <div class="campos-socio" id="campos-socio" style="display: none;">
            <div class="form-group">
                <label for="telefono">
                    <span class="icon">📞</span> Teléfono:
                </label>
                <input type="text" name="telefono" id="telefono" placeholder="Teléfono">
            </div>

            <div class="form-group">
                <label for="email">
                    <span class="icon">📧</span> Email:
                </label>
                <input type="email" name="email" id="email" placeholder="Email">
            </div>

            <div class="form-group">
                <label for="password">
                    <span class="icon">🔒</span> Contrasinal:
                </label>
                <input type="password" name="password" id="password" placeholder="Contrasinal">
            </div>
        </div>

        <!-- xogadores -->
        <div class="campos-jugador" id="campos-jugador" style="display: none;">
            <div class="form-group">
                <label for="equipo">
                    <span class="icon">⚽</span> Equipo:
                </label>
                <select name="equipo" id="equipo">
                    <option value="">Seleccionar equipo...</option>
                    <option value="senior">Senior</option>
                    <option value="veteranos">Veteranos</option>
                </select>
            </div>

            <div class="form-group">
                <label for="dorsal">
                    <span class="icon">🔢</span> Dorsal:
                </label>
                <input type="number" name="dorsal" id="dorsal" placeholder="Número de dorsal" min="1" max="99">
            </div>

            <div class="form-group">
                <label for="posicion">
                    <span class="icon">🎯</span> Posición:
                </label>
                <select name="posicion" id="posicion">
                    <option value="">Seleccionar posición...</option>
                    <option value="Porteiro">Porteiro</option>
                    <option value="Defensa">Defensa</option>
                    <option value="Centrocampista">Centrocampista</option>
                    <option value="Delanteiro">Delanteiro</option>
                </select>
            </div>

            <div class="form-group">
                <label for="edad">
                    <span class="icon">📅</span> Idade:
                </label>
                <input type="number" name="edad" id="edad" placeholder="Idade" min="15" max="50">
            </div>

            <div class="form-group">
                <label for="foto">
                    <span class="icon">📸</span> Foto do xogador:
                </label>
                <!-- Campo oculto para gardar o ID da imaxe seleccionada -->
                <input type="hidden" name="foto" id="fotoSeleccionada">
                
                <!-- Vista previa da imaxe -->
                <div id="previewImagenSeleccionada"></div>
                
                <!-- Botón para abrir a galería -->
                <button type="button" class="btn-galeria" onclick="abrirGaleria()">
                    <span class="icon">🖼️</span> Seleccionar imaxe
                </button>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-insertar">
                <span class="icon">✅</span> Insertar Usuario
            </button>
            <button type="button" class="btn-limpiar" onclick="limpiarFormulario()">
                <span class="icon">🗑️</span> Limpar
            </button>
        </div>
    </form>
</div>

<script src="js/insertarUsuarios.js"></script>
<script src="js/adminImg.js"></script>
<?php include '../includes/footer.php'; ?>
