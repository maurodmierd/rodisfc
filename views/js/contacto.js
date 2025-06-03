document.addEventListener("DOMContentLoaded", () => {
    let modal = document.getElementById("contactModal")
    let openBtn = document.getElementById("openModal")
    let closeBtn = document.querySelector(".cerrar-modal")

    function abrirModal() {
        modal.style.display = "block"
        document.body.style.overflow = "hidden"
    }

    function cerrarModal() {
        modal.style.display = "none"
        document.body.style.overflow = "auto"
    }

    // Cerra a ventana se se fai click fora do modal
    function clickOutside(event) {
        if (event.target === modal) {
            cerrarModal()
        }
    }
    
    // Cerra a ventana con escape
    document.addEventListener("keydown", (event) => {
        if (event.key === "Escape" && modal.style.display === "block") {
            cerrarModal()
        }
    })

    // Asignar eventos
    openBtn.addEventListener("click", abrirModal)
    closeBtn.addEventListener("click", cerrarModal)
    window.addEventListener("click", clickOutside)
})
