<?php
session_start();
include '../includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dni = $_POST['dni'];
    $password = $_POST['password'];

    $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->execute([$dni]);
    $usuario = $stmt->fetch();

    if ($usuario && password_verify($password, $usuario['contraseña'])) {
        $_SESSION['usuario'] = $usuario;
        header("Location: /index.php");
    } else {
        echo "Credenciales incorrectas";
    }
}
?>
<form method="POST">
    <input type="text" name="dni" placeholder="DNI">
    <input type="password" name="password" placeholder="Contraseña">
    <button type="submit">Entrar</button>
</form>
