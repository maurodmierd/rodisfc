<?php
    include '../includes/header.php';
?>
<!-- Formulario de contacto -->
<form action="../procesos/procesarContacto.php" method="POST">
    <input type="text" name="nombre" placeholder="Teu Nome" required>
    <input type="email" name="email" placeholder="Teu correo" required>
    <textarea name="mensaje" placeholder="Mensaxe" required></textarea>
    <button type="submit">Enviar</button>
</form>
<?php
    include '../includes/footer.php';
?>