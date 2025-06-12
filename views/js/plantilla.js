class Xogador {
  constructor(datos) {
    this.dni = datos.dni
    this.nombre = datos.nombre
    this.apellidos = datos.apellidos
    this.dorsal = datos.dorsal
    this.equipo = datos.equipo
    this.posicion = datos.posicion
    this.edad = datos.edad
    this.foto_id = datos.foto_id
    this.observaciones = datos.observaciones || "Sin observaciones"
  }

  // Obter o nome do equipo ca primeira letra en maiúscula
  getEquipo() {
    return this.equipo.charAt(0).toUpperCase() + this.equipo.slice(1)
  }

  // Obter a idade
  getIdade() {
    return this.edad ? this.edad + " anos" : "Sen idade"
  }

  // Verificar si tiene foto válida
  tieneFoto() {
    return this.foto_id && this.foto_id !== null
  }

  mostrarDetalles() {
    // Datos do xogador
    document.getElementById("modal-nombre").textContent = this.nombre + " " + this.apellidos
    document.getElementById("modal-dorsal").textContent = this.dorsal
    document.getElementById("modal-equipo").textContent = this.getEquipo()
    document.getElementById("modal-posicion").textContent = this.posicion || "Sen posición"
    document.getElementById("modal-edad").textContent = this.getIdade()
    document.getElementById("modal-observaciones").textContent = this.observaciones

    // Xestión das fotos
    const modalFoto = document.getElementById("modal-foto")
    if (this.tieneFoto()) {
      // Buscar la imagen en el DOM para obtener la ruta
      const jugadorCard = document.querySelector(`[onclick*="${this.dni}"]`)
      const imgElement = jugadorCard ? jugadorCard.querySelector("img") : null

      if (imgElement && imgElement.src) {
        modalFoto.src = imgElement.src
        modalFoto.alt = this.nombre
        modalFoto.style.display = "block"
      } else {
        modalFoto.style.display = "none"
      }
    } else {
      modalFoto.style.display = "none"
    }

    // Mostrar
    const modal = document.getElementById("modal-jugador")
    modal.style.display = "flex"
    document.body.style.overflow = "hidden"
  }
}

function mostrarDetallesXogador(datosXogador) {
  const xogador = new Xogador(datosXogador)
  xogador.mostrarDetalles()
}

function cerrarModal() {
  const modal = document.getElementById("modal-jugador")
  modal.style.display = "none"
  document.body.style.overflow = "auto"
}

// Event listeners
document.addEventListener("DOMContentLoaded", () => {
  // Cerrar facendo clic fora ou ca tecla escape
  const modal = document.getElementById("modal-jugador")
  modal.addEventListener("click", (e) => {
    if (e.target === modal) {
      cerrarModal()
    }
  })

  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
      cerrarModal()
    }
  })
})
    