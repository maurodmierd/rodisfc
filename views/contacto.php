<?php
include '../includes/header.php';
?>

<div class="contact-container">
    <h2><?php echo icon('fas fa-phone'); ?> Contacto</h2>
    <p>¿Tes algunha pregunta ou suxestión? Non dubides en contactar connosco. Estaremos encantados de axudarte.</p>
    
    <button id="openModal" class="contact-button">
        <?php echo icon('fas fa-envelope'); ?> Abrir Formulario de Contacto
    </button>
</div>

<div id="contactModal" class="modal">
    <div class="modal-contenido">
        <span class="cerrar-modal">&times;</span>
        <h3><?php echo icon('fas fa-edit'); ?> Formulario de Contacto</h3>
        <form action="../api/procesarContacto.php" method="POST">
            <input type="text" name="nombre" placeholder="Teu Nome Completo" required>
            <input type="email" name="email" placeholder="Teu Correo Electrónico" required>
            <textarea name="mensaje" placeholder="Escribe aquí a túa mensaxe..." required></textarea>
            <button type="submit" id='submitBtn'><?php echo icon('fas fa-paper-plane'); ?> Enviar Mensaxe</button>
        </form>
    </div>
</div>

<script src="js/contacto.js"></script>
<?php include '../includes/footer.php'; ?>
