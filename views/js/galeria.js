let categoriaActual = "todas"
let imagenes = []
let imagenesFiltradas = []
let paginaActual = 1
let imagenesPorPagina = 12
let totalPaginas = 1

document.addEventListener("DOMContentLoaded", () => {
    cargarImagenes()
})

function cargarImagenes() {
    fetch("../../api/img/obter.php")
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                imagenes = data.data;
                aplicarFiltro();
            } else {
                mostrarError("Erro ao cargar imaxes: " + data.message)
            }
        })
        .catch((error) => {
            mostrarError("Erro de conexión: " + error.message)
        })
}

function aplicarFiltro() {
    imagenesFiltradas = categoriaActual === "todas" ? [...imagenes] : imagenes.filter((img) => img.categoria === categoriaActual);
    paginaActual = 1;
    calcularPaginacion();
    mostrarImagenes();
    actualizarPaginacion();
}

function calcularPaginacion() {
    totalPaginas = Math.ceil(imagenesFiltradas.length / imagenesPorPagina);
    if (totalPaginas == 0) totalPaginas = 1;
}

function mostrarImagenes() {
    let container = document.getElementById("imagenes-container");

    if (imagenesFiltradas.length == 0) {
        container.innerHTML = `
            <div class="no-imagenes">
                <span class="icon">📭</span>
                <p>Non hai imaxes nesta categoría</p>
            </div>
        `;
        document.getElementById("paginacion-container").style.display = "none"
        return
    }

    let inicio = (paginaActual - 1) * imagenesPorPagina
    let fin = inicio + imagenesPorPagina
    let imagenesPagina = imagenesFiltradas.slice(inicio, fin);
    container.innerHTML = imagenesPagina.map((imagen) => 
        `
        <div class="imagen-item" data-categoria="${imagen.categoria}">
            <div class="imagen-wrapper">
                <img src="../img/${imagen.categoria}/${imagen.nombre}" 
                     alt="${imagen.descripcion || imagen.nombre}"
                     onclick="abrirModalImagen(${imagen.id})"
                     data-id="${imagen.id}"
                     onerror="this.src='../img/logos/placeholder.png'">
                <div class="imagen-overlay">
                    <button class="btn-ver" onclick="abrirModalImagen(${imagen.id
                })">
                        <span class="icon">👁️</span> Ver
                    </button>
                </div>
            </div>
            <div class="imagen-info">
                <h4>${imagen.nombre}</h4>
                <span class="categoria-badge ${imagen.categoria
                }">${formatearCategoria(imagen.categoria)}</span>
                <div class="imagen-fecha">
                    <span class="icon">📅</span>
                    ${formatearFecha(imagen.fecha)}
                </div>
                ${imagen.descripcion ? `<p>${imagen.descripcion}</p>` : ""}
            </div>
        </div>
        `).join("");

    document.getElementById("paginacion-container").style.display =
        totalPaginas > 1 ? "flex" : "none"
}

function filtrarCategoria(categoria) {
    categoriaActual = categoria
    document.querySelectorAll(".filtro-btn").forEach((btn) => btn.classList.remove("active"))
    document.querySelector(`[onclick="filtrarCategoria('${categoria}')"]`).classList.add("active")
    aplicarFiltro();
}

function cambiarPagina(direccion) {
    let nuevaPagina = paginaActual + direccion

    if (nuevaPagina >= 1 && nuevaPagina <= totalPaginas) {
        paginaActual = nuevaPagina
        mostrarImagenes()
        actualizarPaginacion()
        document.querySelector(".galeria-container").scrollIntoView({
            behavior: "smooth",
            block: "start",
        });
    }
}

function actualizarPaginacion() {
    let btnAnterior = document.getElementById("btn-anterior")
    let btnSiguiente = document.getElementById("btn-siguiente")
    let infoPaginacion = document.getElementById("info-paginacion")

    btnAnterior.disabled = paginaActual === 1
    btnSiguiente.disabled = paginaActual === totalPaginas
    infoPaginacion.textContent = `Páxina ${paginaActual} de ${totalPaginas}`;
}

function abrirModalImagen(imagenId) {
    let imagen = imagenes.find((img) => img.id == imagenId);
    if (!imagen){
        return
    } 

    let modal = document.getElementById("modal-imagen")
    let foto = document.getElementById("modal-imagen-foto")
    let nombre = document.getElementById("modal-imagen-nombre")
    let categoria = document.getElementById("modal-imagen-categoria")
    let fecha = document.getElementById("modal-imagen-fecha")
    let descripcion = document.getElementById("modal-imagen-descripcion")

    foto.src = "../img/"+imagen.categoria+"/"+imagen.nombre
    foto.alt = imagen.nombre
    nombre.textContent = imagen.nombre
    categoria.textContent = formatearCategoria(imagen.categoria)
    categoria.className = `imagen-modal-categoria categoria-badge ${imagen.categoria}`
    fecha.innerHTML = `<span class="icon">📅</span> ${formatearFecha(imagen.fecha)}`;
    descripcion.textContent = imagen.descripcion || "Sen descripción";

    modal.style.display = "flex";
    document.body.style.overflow = "hidden";
}

function cerrarModalImagen() {
    let modal = document.getElementById("modal-imagen");
    modal.style.display = "none";
    document.body.style.overflow = "auto";
}

function formatearCategoria(categoria) {
    let nombres = {
        logos: "Logos",
        jugadoresSenior: "Xogadores Senior",
        jugadoresVeteranos: "Xogadores Veteranos",
        equipo: "Equipo",
        noticias: "Noticias",
        otros: "Outros",
    };
    return nombres[categoria] || categoria;
}

function formatearFecha(fecha) {
    let date = new Date(fecha);
    return date.toLocaleDateString("gl-ES", {
        year: "numeric",
        month: "long",
        day: "numeric",
    });
}

function mostrarError(mensaje) {
    let container = document.getElementById("imagenes-container");
    container.innerHTML = `
        <div class="error-galeria">
            <span class="icon">❌</span>
            <p>${mensaje}</p>
        </div>
    `;
}

// Event listeners para cerrar modal
document.addEventListener("DOMContentLoaded", () => {
    let modal = document.getElementById("modal-imagen");
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
});