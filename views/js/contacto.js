document.addEventListener("DOMContentLoaded", () => {
    let modal = document.getElementById("contactModal");
    let openBtn = document.getElementById("openModal");
    let closeBtn = document.querySelector(".cerrar-modal");

    function abrirModal() {
        modal.classList.add("show");
        modal.style.display = "flex";
        document.body.style.overflow = "hidden";
        setTimeout(() => {
            modal.querySelector('.modal-contenido').style.transform = 'scale(1)';
            modal.querySelector('.modal-contenido').style.opacity = '1';
        }, 10);
    }
    function cerrarModal() {
        modal.querySelector('.modal-contenido').style.transform = 'scale(0.9)';
        modal.querySelector('.modal-contenido').style.opacity = '0';
        setTimeout(() => {
            modal.classList.remove("show");
            modal.style.display = "none";
            document.body.style.overflow = "auto";
        }, 200);
    }

    // Cerrar facendo clic fora ou ca tecla escape
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            cerrarModal();
        }
    })
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            cerrarModal()
        }
    })

    // eventos
    openBtn.addEventListener("click", abrirModal);
    closeBtn.addEventListener("click", cerrarModal);
    modal.addEventListener("click", clickOutside);

    
    let modalContent = modal.querySelector('.modal-contenido');
        modalContent.style.transform = 'scale(0.9)';
        modalContent.style.opacity = '0';
        modalContent.style.transition = 'all 0.2s ease';
});