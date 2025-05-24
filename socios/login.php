<?php
include('../includes/header.php');
include('../includes/conexion.php');

//comprobar si ya viene con cookie
$dni_guardado = '';
if (isset($_COOKIE['dni_usuario'])) {
    $dni_guardado = $_COOKIE['dni_usuario'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dni = $_POST['dni'];
    $password = $_POST['password'];
    $recordar = isset($_POST['recordar']) ? true : false;

    $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->execute([$dni]);
    $usuario = $stmt->fetch();

    if ($usuario && password_verify($password, $usuario['contraseña'])) {
        $_SESSION['usuario'] = $usuario;
        $_SESSION['rol'] = $usuario['rol'];

        
        if ($recordar) {
            setcookie('dni_usuario', $dni, time() + (86400 * 30), '/');
        } else {
            
            if (isset($_COOKIE['dni_usuario'])) {
                setcookie('dni_usuario', '', time() - 3600, '/');
            }
        }
        
        header("Location: /index.php");
        exit;
    } else {
        $error_message = "Credenciales incorrectas";
    }
}
?>

<div class="login-container">
    <form method="POST">
        <h2>Acceso de Socios</h2>
        <?php if (isset($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <input type="text" name="dni" placeholder="DNI" value="<?php echo $dni_guardado; ?>" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <div class="recordar-usuario">
        <label for='recordar'>Gardar Usuario</label>
        <input type="checkbox" name="recordar" id="recordar" <?php echo $dni_guardado ? 'checked' : ''; ?>>
            
        </div>
        <button type="submit">Iniciar Sesión</button>
    </form>
</div>
<?php
include('../includes/footer.php');
?>