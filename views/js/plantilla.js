class Xogador {
    constructor(datos) {
        this.id = datos.id
        this.nombre = datos.nombre
        this.dorsal = datos.dorsal
        this.equipo = datos.equipo
        this.posicion = datos.posicion
        this.edad = datos.edad
        this.foto = datos.foto
        this.observaciones = datos.observaciones || 'Sin observaciones'
    }

    // Obter o nome do equipo ca primeira letra en maiúscula
    getEquipo() {
        return this.equipo.charAt(0).toUpperCase() + this.equipo.slice(1)
    }

    // Obter a idade
    getIdade() {
        return this.edad + ' anos'
    }

    // Verificar si tiene foto válida
    tieneFoto() {
        return this.foto && this.foto.trim() !== ''
    }

    mostrarDetalles() {
        // Datos do xogador
        document.getElementById('modal-nombre').textContent = this.nombre
        document.getElementById('modal-dorsal').textContent = this.dorsal
        document.getElementById('modal-equipo').textContent = this.getEquipo()
        document.getElementById('modal-posicion').textContent = this.posicion
        document.getElementById('modal-edad').textContent = this.getEdadFormateada()
        document.getElementById('modal-observaciones').textContent = this.observaciones

        // Xestión das fotos
        let modalFoto = document.getElementById('modal-foto')
        if (this.tieneFoto()) {
            modalFoto.src = this.foto
            modalFoto.alt = this.nombre
            modalFoto.style.display = 'block'
        } else {
            modalFoto.style.display = 'none'
        }
        // Mostrar
        let modal = document.getElementById('modal-jugador')
        modal.style.display = 'flex'
        document.body.style.overflow = 'hidden'
    }
}
function mostrarDetallesXogador(datosXogador) {
    let xogador = new Xogador(datosXogador)
    xogador.mostrarDetalles()
}
function cerrarModal() {
    let modal = document.getElementById('modal-jugador')
    modal.style.display = 'none'
    document.body.style.overflow = 'auto'
}


// Cerrar facendo clic fora ou ca tecla escape
let modal = document.getElementById('modal-jugador');
modal.addEventListener('click', (e) => {
    if (e.target === modal) {
        cerrarModal();
    }
})
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        cerrarModal();
    }
})