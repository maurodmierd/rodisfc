function cambiarCampos() {
    let tipoUsuario = document.getElementById("tipo_usuario").value
    let camposSocio = document.getElementById("campos-socio")
    let camposJugador = document.getElementById("campos-jugador")

    camposSocio.style.display = "none"
    camposJugador.style.display = "none"
    limpiarCamposEspecificos()

// Campos dependendo de tipoUsuario
    if (tipoUsuario === "socio" || tipoUsuario === "admin") {

        camposSocio.style.display = "block"
        document.getElementById("password").required = true
        document.getElementById("equipo").required = false
        document.getElementById("dorsal").required = false

    } else if (tipoUsuario === "jugador") {

        camposJugador.style.display = "block"
        document.getElementById("equipo").required = true
        document.getElementById("dorsal").required = true
        document.getElementById("password").required = false
    }

    if (tipoUsuario) {
        setTimeout(() => {
            let camposVisibles = tipoUsuario === "jugador" ? camposJugador : camposSocio
            camposVisibles.style.opacity = "0"
            camposVisibles.style.transform = "translateY(20px)"
            camposVisibles.style.transition = "all 0.3s ease"

            setTimeout(() => {
                camposVisibles.style.opacity = "1"
                camposVisibles.style.transform = "translateY(0)"
            }, 50)
        }, 50)
    }
}

// Limpar campos
function limpiarCamposEspecificos() {
    // socio/admin
    document.getElementById("telefono").value = ""
    document.getElementById("email").value = ""
    document.getElementById("password").value = ""

    // xogador
    document.getElementById("equipo").value = ""
    document.getElementById("dorsal").value = ""
    document.getElementById("posicion").value = ""
    document.getElementById("edad").value = ""
    document.getElementById("fotoSeleccionada").value = ""
    document.getElementById("previewImagenSeleccionada").innerHTML = ""
}

function limpiarFormulario() {
    if (confirm("¿Queres limpar todo o formulario?")) {
        document.getElementById("form-insertar-usuario").reset()
        document.getElementById("campos-socio").style.display = "none"
        document.getElementById("campos-jugador").style.display = "none"
        document.getElementById("previewImagenSeleccionada").innerHTML = ""
        document.getElementById("password").required = false
        document.getElementById("equipo").required = false
        document.getElementById("dorsal").required = false
    }
}

document.getElementById("form-insertar-usuario").addEventListener("submit", (e) => {
    let tipoUsuario = document.getElementById("tipo_usuario").value
    let dni = document.getElementById("dni").value
    let nombre = document.getElementById("nombre").value
    let apellidos = document.getElementById("apellidos").value

    if (!tipoUsuario) {
        e.preventDefault()
        alert("Selecciona un tipo de usuario")
        return
    }

    if (!dni || !nombre || !apellidos) {
        e.preventDefault()
        alert("Os campos DNI, Nome e Apelidos son obrigatorios")
        return
    }

    let tipoTexto = tipoUsuario === "socio" ? "socio" : tipoUsuario === "admin" ? "administrador" : "xogador"

    if (!confirm(`¿Confirmas que queres insertar este ${tipoTexto}?`)) {
        e.preventDefault()
    }
})

// Inicializar el formulario
document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("campos-socio").style.display = "none"
    document.getElementById("campos-jugador").style.display = "none"
})
