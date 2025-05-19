<?php include('../includes/header.php'); ?>
<main>
    <h1>Registro de Socio</h1>
    <form action="procesar_registro.php" method="POST">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required>

        <label for="email">Correo Electrónico:</label>
        <input type="email" id="email" name="email" required>

        <button type="submit">Registrarse</button>
    </form>
</main>
<?php include('../includes/footer.php'); ?>
