<?php
    include '../includes/header.php';
?>
<!-- Botón para abrir form de contacto -->
<div class="contact-container">
    <button id="openModal" class="contact-button">Contactar</button>
</div>

<!-- Formulario de contacto -->
<div id="contactModal" class="modal">
    <div class="modal-contenido">
        <span class="cerrar-modal">&times;</span>
        <h3>Contacto</h3>
        <form action="../procesos/procesarContacto.php" method="POST">
            <input type="text" name="nombre" placeholder="Teu Nome" required>
            <input type="email" name="email" placeholder="Teu correo" required>
            <textarea name="mensaje" placeholder="Mensaxe" required></textarea>
            <button type="submit">Enviar</button>
        </form>
    </div>
</div>

<script src="js/contactModal.js"></script>
<?php
    include '../includes/footer.php';
?>
