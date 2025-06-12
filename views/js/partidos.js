// Funciones para el calendario de partidos

function mostrarDetallePartido(ano,mes,equipo) {
  const modal = document.getElementById("modal-partido-detalle")
  const contenido = document.getElementById("contenido-partido-detalle")

  // Mostrar loading
  contenido.innerHTML = `
        <div class="loading-partido">
            <i class="fas fa-spinner fa-spin loading-icon"></i>
            <p>Cargando detalles del partido...</p>
        </div>
    `

  modal.style.display = "flex"
  document.body.style.overflow = "hidden"

  // Hacer petición para obtener detalles del partido
  fetch(`../api/obtenerPartido.php?ano=${ano}&mes=${mes}&equipo=${equipo}`)
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        mostrarDetallesPartido(data.data)
      } else {
        mostrarErrorPartido(data.message)
      }
    })
    .catch((error) => {
      console.error("Error:", error)
      mostrarErrorPartido("Error de conexión")
    })
}

function mostrarDetallesPartido(partido) {
  const contenido = document.getElementById("contenido-partido-detalle")
  const esPasado = new Date(partido.fecha) < new Date()
  const fecha = new Date(partido.fecha).toLocaleDateString("gl-ES", {
    weekday: "long",
    year: "numeric",
    month: "long",
    day: "numeric",
  })

  contenido.innerHTML = `
        <div class="partido-detalle">
            <div class="partido-header-detalle">
                <span class="equipo-badge ${partido.equipo.toLowerCase()}">${partido.equipo.charAt(0).toUpperCase() + partido.equipo.slice(1)}</span>
                <span class="fecha-detalle">${fecha}</span>
            </div>
            
            <div class="partido-equipos-detalle">
                <div class="equipo-detalle local">
                    <h3>${partido.equipo_local}</h3>
                    ${esPasado && partido.goles_local !== null ? `<div class="goles-detalle">${partido.goles_local}</div>` : ""}
                </div>
                
                <div class="vs-detalle">
                    ${
                      esPasado && partido.goles_local !== null && partido.goles_visitante !== null
                        ? `<div class="resultado-detalle">${partido.goles_local} - ${partido.goles_visitante}</div>`
                        : `<div class="hora-detalle">
                             <i class="fas fa-clock"></i>
                             ${partido.hora.substring(0, 5)}
                           </div>`
                    }
                </div>
                
                <div class="equipo-detalle visitante">
                    <h3>${partido.equipo_visitante}</h3>
                    ${esPasado && partido.goles_visitante !== null ? `<div class="goles-detalle">${partido.goles_visitante}</div>` : ""}
                </div>
            </div>
            
            <div class="partido-info-detalle">
                <div class="info-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <strong>Lugar:</strong> ${partido.lugar}
                </div>
                <div class="info-item">
                    <i class="fas fa-calendar-alt"></i>
                    <strong>Data:</strong> ${fecha}
                </div>
                <div class="info-item">
                    <i class="fas fa-clock"></i>
                    <strong>Hora:</strong> ${partido.hora.substring(0, 5)}
                </div>
            </div>
        </div>
    `
}

function mostrarErrorPartido(mensaje) {
  const contenido = document.getElementById("contenido-partido-detalle")
  contenido.innerHTML = `
        <div class="error-partido">
            <i class="fas fa-exclamation-triangle"></i>
            <p>${mensaje}</p>
        </div>
    `
}

function cerrarModalPartido() {
  const modal = document.getElementById("modal-partido-detalle")
  modal.style.display = "none"
  document.body.style.overflow = "auto"
}

// Event listeners
document.addEventListener("DOMContentLoaded", () => {
  // Cerrar modal al hacer clic fuera
  const modal = document.getElementById("modal-partido-detalle")
  modal.addEventListener("click", (event) => {
    if (event.target === modal) {
      cerrarModalPartido()
    }
  })

  // Cerrar modal con Escape
  document.addEventListener("keydown", (event) => {
    if (event.key === "Escape") {
      cerrarModalPartido()
    }
  })

  // Animar elementos del calendario
  animarCalendario()
})

function animarCalendario() {
  const dias = document.querySelectorAll(".dia-calendario")
  dias.forEach((dia, index) => {
    setTimeout(() => {
      dia.classList.add("visible")
    }, index * 20)
  })
}
