<?php include("../includes/header.php"); ?>
<h2>Registro de Socios</h2>
<form action="../procesos/procesar_registro.php" method="POST">
    <input type="text" name="nombre" placeholder="Nombre completo" required>
    <input type="email" name="email" placeholder="Correo" required>
    <input type="password" name="password" placeholder="Contraseña" required>
    <button type="submit">Registrarse</button>
</form>
<?php include("../includes/footer.php"); ?>