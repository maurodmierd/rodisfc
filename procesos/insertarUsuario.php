<?php
// Script para evitar acceso non autorizado
include '../includes/header.php';
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
    header("Location: ../login/areaPrivada.php");
    exit();
}
include '../includes/conexion.php';

$errores = [];
$mensaje_exito = '';

// Comproba se o formulario foi enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $tipo_usuario = trim($_POST['tipo_usuario']);
    $dni = trim($_POST['dni']);
    $nombre = trim($_POST['nombre']);
    $nombref = trim($_POST['nombref']);
    $apellidos = trim($_POST['apellidos']);
    
    // Validaciones básicas
    if (empty($tipo_usuario)) {
        $errores[] = "Debes seleccionar un tipo de usuario";
    }
    
    if (empty($dni)) {
        $errores[] = "O DNI é obrigatorio";
    } elseif (!preg_match('/^[0-9]{8}[A-Za-z]$/', $dni)) {
        $errores[] = "O formato do DNI non é válido (8 números + 1 letra)";
    }
    
    if (empty($nombre)) {
        $errores[] = "O nome é obrigatorio";
    } elseif (strlen($nombre) < 2) {
        $errores[] = "O nome debe ter polo menos 2 caracteres";
    }
    
    if (empty($apellidos)) {
        $errores[] = "Os apelidos son obrigatorios";
    } elseif (strlen($apellidos) < 2) {
        $errores[] = "Os apelidos deben ter polo menos 2 caracteres";
    }
    
    // Verificar se o DNI xa existe
    if (!empty($dni)) {
        try {
            if ($tipo_usuario === 'jugador') {
                $stmt = $conexion->prepare("SELECT id FROM jugadores WHERE id = ?");
            } else {
                $stmt = $conexion->prepare("SELECT id FROM usuarios WHERE id = ?");
            }
            $stmt->execute([$dni]);
            if ($stmt->fetch()) {
                $errores[] = "Xa existe un usuario con ese DNI";
            }
        } catch (PDOException $e) {
            $errores[] = "Erro ao verificar o DNI na base de datos";
        }
    }
    
    // Validaciones específicas por tipo
    if ($tipo_usuario === 'socio' || $tipo_usuario === 'admin') {
        $telefono = trim($_POST['telefono'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        
        // Validar teléfono se se proporciona
        if (!empty($telefono) && !preg_match('/^[0-9]{9}$/', $telefono)) {
            $errores[] = "O teléfono debe ter 9 díxitos";
        }
        
        // Validar email se se proporciona
        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errores[] = "O formato do email non é válido";
        }
        
        // Verificar se o email xa existe
        if (!empty($email)) {
            try {
                $stmt = $conexion->prepare("SELECT id FROM usuarios WHERE email = ? AND id != ?");
                $stmt->execute([$email, $dni]);
                if ($stmt->fetch()) {
                    $errores[] = "Xa existe un usuario con ese email";
                }
            } catch (PDOException $e) {
                $errores[] = "Erro ao verificar o email na base de datos";
            }
        }
        
        // Validar contraseña
        if (empty($password)) {
            $errores[] = "A contraseña é obrigatoria";
        } elseif (strlen($password) < 6) {
            $errores[] = "A contraseña debe ter polo menos 6 caracteres";
        }
        
    } elseif ($tipo_usuario === 'jugador') {
        $equipo = trim($_POST['equipo'] ?? '');
        $fechaNac = $_POST['fechaNac'];
        $dorsal = $_POST['dorsal'] ?? '';
        $posicion = trim($_POST['posicion'] ?? '');
        $foto_id = $_POST['imagen_id'] ?? null;
        
        // Validar equipo
        if (empty($equipo)) {
            $errores[] = "O equipo é obrigatorio para os xogadores";
        } elseif (!in_array($equipo, ['senior', 'veteranos'])) {
            $errores[] = "O equipo debe ser 'senior' ou 'veteranos'";
        }
        
        // Validar dorsal
        if (empty($dorsal)) {
            $errores[] = "O dorsal é obrigatorio para os xogadores";
        } elseif (!is_numeric($dorsal) || $dorsal < 1 || $dorsal > 99) {
            $errores[] = "O dorsal debe ser un número entre 1 e 99";
        } else {
            // Verificar se o dorsal xa existe no mesmo equipo
            try {
                $stmt = $conexion->prepare("SELECT id FROM jugadores WHERE dorsal = ? AND equipo = ? AND id != ?");
                $stmt->execute([$dorsal, $equipo, $dni]);
                if ($stmt->fetch()) {
                    $errores[] = "Xa existe un xogador con ese dorsal no equipo " . ucfirst($equipo);
                }
            } catch (PDOException $e) {
                $errores[] = "Erro ao verificar o dorsal na base de datos";
            }
        }
        
        // Validar idade se se proporciona
        if (!empty($edad)) {
            if (!is_numeric($edad) || $edad < 16 || $edad > 50) {
                $errores[] = "A idade debe ser un número entre 16 e 50 anos";
            }
        }
        
        // Validar foto se se proporciona
        if (!empty($foto_id)) {
            if (!is_numeric($foto_id)) {
                $errores[] = "ID de foto non válido";
            } else {
                try {
                    $stmt = $conexion->prepare("SELECT id FROM img WHERE id = ? AND activo = 1");
                    $stmt->execute([$foto_id]);
                    if (!$stmt->fetch()) {
                        $errores[] = "A imaxe seleccionada non existe ou non está activa";
                    }
                } catch (PDOException $e) {
                    $errores[] = "Erro ao verificar a imaxe na base de datos";
                }
            }
        }
    } else {
        $errores[] = "Tipo de usuario non válido";
    }
    
    // Se non hai erros, insertar na base de datos
    if (empty($errores)) {
        try {
            $conexion->beginTransaction();
            
            if ($tipo_usuario === 'socio' || $tipo_usuario === 'admin') {
                // Insertar socio ou admin
                $fecha = date('Y-m-d');
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                
                $stmt = $conexion->prepare("INSERT INTO usuarios (id, nombre, apellidos, telefono, email, fecha_registro, contraseña, rol) 
                                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $dni, 
                    $nombre, 
                    $apellidos, 
                    !empty($telefono) ? $telefono : null, 
                    !empty($email) ? $email : null, 
                    $fecha, 
                    $password_hash, 
                    $tipo_usuario
                ]);
                
                $mensaje_exito = ucfirst($tipo_usuario) . " '$nombre $apellidos' insertado correctamente";
                
            } elseif ($tipo_usuario === 'jugador') {
                // Insertar jugador
                $stmt = $conexion->prepare("INSERT INTO jugadores (id, nombre_futbolistico,nombre,apellidos,fechaNac, equipo, dorsal, posicion, edad, foto_id) 
                                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $dni, 
                    $nombre, 
                    $apellidos, 
                    $equipo, 
                    $dorsal, 
                    !empty($posicion) ? $posicion : null, 
                    !empty($edad) ? $edad : null, 
                    !empty($foto_id) ? $foto_id : null
                ]);
                
                $mensaje_exito = "Xogador '$nombre $apellidos' insertado correctamente no equipo " . ucfirst($equipo);
            }
            
            $conexion->commit();
            
            // Limpiar variables para resetear o formulario
            $tipo_usuario = $dni = $nombre = $apellidos = '';
            $telefono = $email = $password = '';
            $equipo = $dorsal = $posicion = $edad = $foto_id = '';
            
        } catch (PDOException $e) {
            $conexion->rollBack();
            $errores[] = "Erro ao insertar na base de datos: " . $e->getMessage();
        } catch (Exception $e) {
            $conexion->rollBack();
            $errores[] = "Erro interno do servidor";
        }
    }
}
?>

<div class="container">
    <h2><?php echo icon('fas fa-user-plus'); ?> Insertar Usuario</h2>
    <p>Engade novos usuarios ao sistema: socios, xogadores ou administradores</p>

    <?php if (!empty($errores)): ?>
        <div class="mensaje-error">
            <h4><i class="fas fa-exclamation-triangle"></i> Erros encontrados:</h4>
            <ul style="margin: 0; padding-left: 1.5rem;">
                <?php foreach ($errores as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if (!empty($mensaje_exito)): ?>
        <div class="mensaje-exito">
            <i class="fas fa-check-circle"></i>
            <p><?php echo htmlspecialchars($mensaje_exito); ?></p>
        </div>
    <?php endif; ?>

    <form method="POST" id="form-insertar-usuario">
        <!-- Selector de tipo de usuario -->
        <div class="form-group">
            <label for="tipo_usuario">
                <?php echo icon('fas fa-user-tag'); ?> Tipo de Usuario:
            </label>
            <select name="tipo_usuario" id="tipo_usuario" required onchange="cambiarCampos()">
                <option value="">Seleccionar tipo...</option>
                <option value="socio" <?php echo (isset($tipo_usuario) && $tipo_usuario === 'socio') ? 'selected' : ''; ?>>
                    Socio
                </option>
                <option value="jugador" <?php echo (isset($tipo_usuario) && $tipo_usuario === 'jugador') ? 'selected' : ''; ?>>
                    Xogador
                </option>
                <option value="admin" <?php echo (isset($tipo_usuario) && $tipo_usuario === 'admin') ? 'selected' : ''; ?>>
                    Administrador
                </option>
            </select>
        </div>

        <!-- Campos comunes -->
        <div class="campos-comunes">
            <div class="form-group">
                <label for="dni">
                    <?php echo icon('fas fa-id-card'); ?> DNI:
                </label>
                <input type="text" name="dni" id="dni" placeholder="12345678A" 
                       value="<?php echo isset($dni) ? htmlspecialchars($dni) : ''; ?>" 
                       pattern="[0-9]{8}[A-Za-z]" 
                       title="8 números seguidos de una letra" required>
            </div>

            <div class="form-group">
                <label for="nombre">
                    <?php echo icon('fas fa-user'); ?> Nome:
                </label>
                <input type="text" name="nombre" id="nombre" placeholder="Nome" 
                       value="<?php echo isset($nombre) ? htmlspecialchars($nombre) : ''; ?>" 
                       minlength="2" maxlength="50" required>
            </div>

            <div class="form-group">
                <label for="apellidos">
                    <?php echo icon('fas fa-users'); ?> Apelidos:
                </label>
                <input type="text" name="apellidos" id="apellidos" placeholder="Apelidos" 
                       value="<?php echo isset($apellidos) ? htmlspecialchars($apellidos) : ''; ?>" 
                       minlength="2" maxlength="100" required>
            </div>
        </div>

        <!-- Campos específicos para socio/admin -->
        <div class="campos-socio" id="campos-socio" style="display: none;">
            <h4><?php echo icon('fas fa-user-tie'); ?> Datos de Socio/Administrador</h4>
            <div class="form-group">
                <label for="telefono">
                    <?php echo icon('fas fa-phone'); ?> Teléfono:
                </label>
                <input type="tel" name="telefono" id="telefono" placeholder="123456789" 
                       value="<?php echo isset($telefono) ? htmlspecialchars($telefono) : ''; ?>" 
                       pattern="[0-9]{9}" title="9 díxitos">
            </div>

            <div class="form-group">
                <label for="email">
                    <?php echo icon('fas fa-envelope'); ?> Email:
                </label>
                <input type="email" name="email" id="email" placeholder="usuario@exemplo.com" 
                       value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" 
                       maxlength="100">
            </div>

            <div class="form-group">
                <label for="password">
                    <?php echo icon('fas fa-lock'); ?> Contrasinal:
                </label>
                <input type="password" name="password" id="password" placeholder="Mínimo 6 caracteres" 
                       minlength="6" maxlength="255">
                <small style="color: #666; font-size: 0.9rem;">
                    A contraseña debe ter polo menos 6 caracteres
                </small>
            </div>
        </div>

        <!-- Campos específicos para jugador -->
        <div class="campos-jugador" id="campos-jugador" style="display: none;">
            <h4><?php echo icon('fas fa-futbol'); ?> Datos de Xogador</h4>
            <div class="form-group">
                <label for="equipo">
                    <?php echo icon('fas fa-users'); ?> Equipo:
                </label>
                <select name="equipo" id="equipo">
                    <option value="">Seleccionar equipo...</option>
                    <option value="senior" <?php echo (isset($equipo) && $equipo === 'senior') ? 'selected' : ''; ?>>Senior</option>
                    <option value="veteranos" <?php echo (isset($equipo) && $equipo === 'veteranos') ? 'selected' : ''; ?>>Veteranos</option>
                </select>
            </div>

            <div class="form-group">
                <label for="dorsal">
                    <?php echo icon('fas fa-hashtag'); ?> Dorsal:
                </label>
                <input type="number" name="dorsal" id="dorsal" placeholder="Número de dorsal" 
                       value="<?php echo isset($dorsal) ? htmlspecialchars($dorsal) : ''; ?>" 
                       min="1" max="99">
            </div>

            <div class="form-group">
                <label for="posicion">
                    <?php echo icon('fas fa-running'); ?> Posición:
                </label>
                <select name="posicion" id="posicion">
                    <option value="">Seleccionar posición...</option>
                    <option value="Porteiro" <?php echo (isset($posicion) && $posicion === 'Porteiro') ? 'selected' : ''; ?>>Porteiro</option>
                    <option value="Defensa" <?php echo (isset($posicion) && $posicion === 'Defensa') ? 'selected' : ''; ?>>Defensa</option>
                    <option value="Centrocampista" <?php echo (isset($posicion) && $posicion === 'Centrocampista') ? 'selected' : ''; ?>>Centrocampista</option>
                    <option value="Delanteiro" <?php echo (isset($posicion) && $posicion === 'Delanteiro') ? 'selected' : ''; ?>>Delanteiro</option>
                </select>
            </div>

            <div class="form-group">
                <label for="edad">
                    <?php echo icon('fas fa-birthday-cake'); ?> Idade:
                </label>
                <input type="number" name="edad" id="edad" placeholder="Idade" 
                       value="<?php echo isset($edad) ? htmlspecialchars($edad) : ''; ?>" 
                       min="16" max="50">
            </div>

            <div class="form-group">
                <label for="imagen_id">
                    <?php echo icon('fas fa-camera'); ?> Foto do xogador:
                </label>
                <!-- Campo oculto para gardar o ID da imaxe seleccionada -->
                <input type="hidden" name="imagen_id" id="fotoSeleccionada" 
                       value="<?php echo isset($foto_id) ? htmlspecialchars($foto_id) : ''; ?>">
                
                <!-- Vista previa da imaxe -->
                <div id="previewImagenSeleccionada"></div>
                
                <!-- Botón para abrir a galería -->
                <button type="button" class="btn-galeria" onclick="abrirGaleria()">
                    <?php echo icon('fas fa-images'); ?> Seleccionar imaxe
                </button>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-insertar">
                <?php echo icon('fas fa-save'); ?> Insertar Usuario
            </button>
            <button type="button" class="btn-limpiar" onclick="limpiarFormulario()">
                <?php echo icon('fas fa-broom'); ?> Limpar
            </button>
        </div>
    </form>
</div>

<script src="js/insertarUsuario.js"></script>
<script src="js/adminImg.js"></script>
<?php include '../includes/footer.php'; ?>
