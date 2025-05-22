<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
include '../includes/header.php';
?>
<div class="container">
    <h2>Bienvenido, <?php echo $_SESSION['usuario']; ?>!</h2>
    <p>Este es el área privada de socios.</p>
    <a href="logout.php">Cerrar sesión</a>
</div>
<?php include '../includes/footer.php'; ?>
