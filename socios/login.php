<?php include("../includes/header.php"); ?>
<h2>Iniciar sesión</h2>
<form action="../procesos/procesar_login.php" method="POST">
    <input type="email" name="email" placeholder="Correo" required>
    <input type="password" name="password" placeholder="Contraseña" required>
    <button type="submit">Entrar</button>
</form>
<?php include("../includes/footer.php"); ?>