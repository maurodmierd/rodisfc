let currentSlide = 0
let slides = []
let totalSlides = 0
let indicadores = []
let autoPlayInterval = null

// Carrusel de noticias
function iniciarCarrusel() {
    slides = document.querySelectorAll(".carrusel-slide")
    totalSlides = slides.length
    indicadores = document.querySelectorAll(".indicador")
    if (totalSlides == 0) {
        console.log("non hai slides.")
        return false;
    }

    // eventos para navegacion
    let prevBtn = document.getElementById("prev-noticia")
    let nextBtn = document.getElementById("next-noticia")
    prevBtn.addEventListener("click", prevSlide)
    nextBtn.addEventListener("click", nextSlide)
    indicadores.forEach((indicador, index) => {
        indicador.addEventListener("click", () => goToSlide(index))
    });

    configurarBotonesLeerMais()
    startAutoPlay()

    // pausar autoplay ao detectar o raton
    let carrusel = document.querySelector(".carrusel-container")
    carrusel.addEventListener("mouseenter", stopAutoPlay)
    carrusel.addEventListener("mouseleave", startAutoPlay)
}

function configurarBotonesLeerMais() {
    let botonsLeerMais = document.querySelectorAll(".btn-leer-mas")

    botonsLeerMais.forEach((boton, index) => {
        let href = boton.getAttribute("href")
        if (href) {
            boton.setAttribute("href", `verNoticia.php?id=${index}&ref=carousel`)
            boton.style.display = "inline-flex"
            boton.style.alignItems = "center"
            boton.style.justifyContent = "center"
            boton.style.position = "relative"
            boton.style.zIndex = "10"
            boton.style.cursor = "pointer"
        }
    })
}

function goToSlide(slideIndex) {
    // quitar clase active do slide actual
    slides[currentSlide].classList.remove("active")
    indicadores[currentSlide].classList.remove("active")

    currentSlide = slideIndex

    // añadir clase active o novo slide
    slides[currentSlide].classList.add("active")
    indicadores[currentSlide].classList.add("active")

    // Reconfigurar botones después del cambio de slide
    setTimeout(() => {
        configurarBotonesLeerMais()
    }, 100)
}

function nextSlide() {
    // Se o indice actual é o ultimo reiniciar ao primeiro
    let nextIndex = currentSlide + 1 < totalSlides ? currentSlide + 1 : 0
    goToSlide(nextIndex)
}

function prevSlide() {
    // Se o indice actual é o primeiro ir ao ultimo
    let prevIndex = currentSlide - 1 < 0 ? totalSlides - 1 : currentSlide - 1
    goToSlide(prevIndex)
}

function startAutoPlay() {
    // porque se duplicaban os intervalos
    stopAutoPlay()

    autoPlayInterval = setInterval(() => {nextSlide()}, 5000)
}

function stopAutoPlay() {
    if (autoPlayInterval) {
        clearInterval(autoPlayInterval);
        autoPlayInterval = null
    }
}

// Funcions para animacions
function animarElementos(selector, className) {
    let elementos = document.querySelectorAll(selector)
    elementos.forEach((elemento) => {
        elemento.classList.add(className)
    });
}

function animarElementosDelay(selector, className, delay) {
    let elementos = document.querySelectorAll(selector)
    elementos.forEach((elemento, index) => {
        setTimeout(() => {
        elemento.classList.add(className)
        }, index * delay)
    });
}

function iniciarAnimacions() {
    // aplicar animacions despois de un timeout
    setTimeout(() => {
        animarElementos(".seccion-animada", "visible")
        animarElementosDelay(".partido-card", "visible", 100)
    }, 300)
}

function abrirModalNoticia(noticiaId) {
    if (!document.getElementById("modal-noticia")) {
        crearModalNoticia();
    }
    stopAutoPlay();
    document.getElementById("modal-noticia").style.display = "flex";
    cargarNoticia(noticiaId);
}

function cerrarModalNoticia() {
    let modal = document.getElementById("modal-noticia")
    modal.style.display = "none"
    startAutoPlay()
}

function crearModalNoticia() {
    let modalHTML = `
        <div id="modal-noticia" class="modal-noticia" style="display: none;">
        <div class="modal-noticia-contenido">
            <div class="modal-noticia-header">
            <h3 id="modal-noticia-titulo">📰 Noticia</h3>
            <span class="cerrar-modal-noticia" onclick="cerrarModalNoticia()">&times;</span>
            </div>
            
            <div class="modal-noticia-body" id="modal-noticia-body">
            <div class="loading-noticia">
                <span class="icon">⏳</span>
                <p>Cargando noticia...</p>
            </div>
            </div>
            
            <div class="modal-noticia-footer">
            <button class="btn-cerrar-noticia" onclick="cerrarModalNoticia()">
                <span class="icon">✖️</span> Cerrar
            </button>
            </div>
        </div>
        </div>
    `
    document.body.insertAdjacentHTML("beforeend", modalHTML)
    iniciarEventosModalNoticia();
}

function iniciarEventosModalNoticia() {
    let modal = document.getElementById("modal-noticia")
    modal.addEventListener("click", (e) => {
        if (e.target === modal) {
        cerrarModalNoticia()
        }
    })
    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape" && modal.style.display === "flex") {
        cerrarModalNoticia()
        }
    })
}

function cargarNoticia(noticiaId) {
    let modalBody = document.getElementById("modal-noticia-body");
    let modalTitulo = document.getElementById("modal-noticia-titulo");
    modalBody.innerHTML = `
        <div class="loading-noticia">
        <span class="icon">⏳</span>
        <p>Cargando noticia...</p>
        </div>
    `
    // peticion para obter noticia
    fetch("src/obterNoticia.php?id="+noticiaId)
        .then((response) => response.json())
        .then((data) => {
        if (data.success) {
            let noticia = data.data;
            modalTitulo.innerHTML = `📰 ${noticia.titulo}`;
            modalBody.innerHTML = `
            <div class="noticia-completa">
                <div class="noticia-meta">
                <span class="noticia-fecha">
                    <span class="icon">📅</span>
                    ${formatearFecha(noticia.fecha)}
                </span>
                <span class="noticia-categoria">
                    <span class="icon">🏷️</span>
                    ${noticia.categoria}
                </span>
                </div>
                
                <div class="noticia-contenido-completo">
                ${noticia.contenido}
                </div>
                ${noticia.ruta ?
                 `
                <div class="noticia-imagen-completa">
                    <img src="../${noticia.ruta}" alt="${noticia.titulo}" onerror="this.style.display='none'">
                </div>
                ` : ""}
            </div>
            `
        } else {
            mostrarErrorNoticia(data.message || "Erro ao cargar a noticia")
        }
        })
        .catch(mostrarErrorNoticia("Erro de conexión"));
}

function mostrarErrorNoticia(mensaje) {
    let modalBody = document.getElementById("modal-noticia-body");
    modalBody.innerHTML =
     `
        <div class="error-noticia">
        <span class="icon">❌</span>
        <p>${mensaje}</p>
        </div>
    `
}

function formatearFecha(fecha) {
    let date = new Date(fecha)
    return date.toLocaleDateString("gl-ES", {
        year: "numeric",
        month: "long",
        day: "numeric",
    })
}

// iniciar todo
function iniciar() {
    iniciarCarrusel()
    iniciarAnimacions()
    console.log("Carrusel:Ok")
}

document.addEventListener("DOMContentLoaded", iniciar)