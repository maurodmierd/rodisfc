<?php include '../includes/header.php'; ?>

<div class="contact-container">
    <h2>📞 Contacto</h2>
    <p>¿Tes algunha pregunta ou suxestión? Non dubides en contactar connosco. Estaremos encantados de axudarte.</p>
    
    <button id="openModal" class="contact-button">
        ✉️ Abrir Formulario de Contacto
    </button>
</div>

<div id="contactModal" class="modal">
    <div class="modal-contenido">
        <span class="cerrar-modal">&times;</span>
        <h3>📝 Formulario de Contacto</h3>
        <form action="../procesos/procesarContacto.php" method="POST">
            <input type="text" name="nombre" placeholder="Teu Nome Completo" required>
            <input type="email" name="email" placeholder="Teu Correo Electrónico" required>
            <textarea name="mensaje" placeholder="Escribe aquí a túa mensaxe..." required></textarea>
            <button type="submit">📤 Enviar Mensaxe</button>
        </form>
    </div>
</div>

<script src="../js/contactModal.js"></script>
<?php include '../includes/footer.php'; ?>