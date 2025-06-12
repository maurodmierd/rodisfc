// Función para cambiar entre tabs
function cambiarTab(tab) {
  // Remover clase active de todos los tabs
  document.querySelectorAll(".tab-btn").forEach((btn) => btn.classList.remove("active"))
  document.querySelectorAll(".tab-content").forEach((content) => content.classList.remove("active"))

  // Activar el tab seleccionado
  document.querySelector(`[onclick="cambiarTab('${tab}')"]`).classList.add("active")
  document.getElementById(`tab-${tab}`).classList.add("active")
}

document.addEventListener("DOMContentLoaded", () => {
  // Event listeners para botones de editar
  document.querySelectorAll(".btn-editar").forEach((btn) => {
    btn.addEventListener("click", () => {
      const tipo = btn.dataset.tipo

      // Llenar campos comunes
      document.getElementById("edit-tipo").value = tipo
      document.getElementById("edit-id").value = btn.dataset.id
      document.getElementById("edit-nombre").value = btn.dataset.nombre
      document.getElementById("edit-apellidos").value = btn.dataset.apellidos

      // Mostrar/ocultar campos según el tipo
      const camposUsuario = document.getElementById("campos-usuario-edit")
      const camposJugador = document.getElementById("campos-jugador-edit")

      if (tipo === "usuario") {
        // Mostrar campos de usuario
        camposUsuario.style.display = "block"
        camposJugador.style.display = "none"

        // Llenar campos específicos de usuario
        document.getElementById("edit-email").value = btn.dataset.email || ""
        document.getElementById("edit-telefono").value = btn.dataset.telefono || ""
        document.getElementById("edit-rol").value = btn.dataset.rol

        // Cambiar título del modal
        document.getElementById("modal-titulo").innerHTML = '<i class="fas fa-edit"></i> Editar Usuario'

        // Cambiar action del formulario
        document.getElementById("form-editar").action = "../api/users/actualizarUsuario.php"
      } else if (tipo === "jugador") {
        // Mostrar campos de jugador
        camposUsuario.style.display = "none"
        camposJugador.style.display = "block"

        // Llenar campos específicos de jugador
        document.getElementById("edit-equipo").value = btn.dataset.equipo
        document.getElementById("edit-dorsal").value = btn.dataset.dorsal
        document.getElementById("edit-posicion").value = btn.dataset.posicion || ""
        document.getElementById("edit-edad").value = btn.dataset.edad || ""

        // Cambiar título del modal
        document.getElementById("modal-titulo").innerHTML = '<i class="fas fa-edit"></i> Editar Xogador'

        // Cambiar action del formulario
        document.getElementById("form-editar").action = "../api/users/actualizarJugador.php"
      }

      // Mostrar modal
      document.getElementById("modal-editar").style.display = "block"
    })
  })
})

function cerrarModal() {
  document.getElementById("modal-editar").style.display = "none"
}

function confirmarEdicion() {
  const tipo = document.getElementById("edit-tipo").value
  const tipoTexto = tipo === "usuario" ? "usuario" : "xogador"
  return confirm(`¿Confirmas gardar os cambios deste ${tipoTexto}?`)
}

function confirmarEliminacion(id, tipo) {
  const tipoTexto = tipo === "usuario" ? "usuario" : "xogador"
  const endpoint = tipo === "usuario" ? "../api/users/eliminarUsuario.php" : "../api/users/eliminarJugador.php"

  if (confirm(`¿Seguro que queres eliminar este ${tipoTexto} con ID ${id}?`)) {
    if (confirm(`Perderás toda a información asociada a este ${tipoTexto}. ¿Confirmas?`)) {
      // Petición para eliminar
      fetch(`${endpoint}?id=${id}`)
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            mostrarExito(data.message)
            // Recargar la página después de 2 segundos
            setTimeout(() => {
              window.location.reload()
            }, 2000)
          } else {
            mostrarError("Erro ao eliminar: " + data.message)
          }
        })
        .catch(() => mostrarError("Erro de conexión"))
    }
  }
}

// Notificaciones success/error
function mostrarExito(mensaje) {
  mostrarNotificacion(mensaje, "success")
}

function mostrarError(mensaje) {
  mostrarNotificacion(mensaje, "error")
}

function mostrarNotificacion(mensaje, tipo) {
  let notificacion = document.getElementById("notificacion-gestion")
  if (!notificacion) {
    notificacion = document.createElement("div")
    notificacion.id = "notificacion-gestion"
    notificacion.className = "notificacion-galeria"
    document.body.appendChild(notificacion)
  }
  notificacion.className = `notificacion-galeria ${tipo}`
  notificacion.innerHTML = `<i class="fas fa-${tipo === "success" ? "check-circle" : "exclamation-triangle"}"></i> ${mensaje}`
  notificacion.style.display = "block"

  // La notificación se elimina a los 3s
  setTimeout(() => {
    notificacion.style.display = "none"
  }, 3000)
}
