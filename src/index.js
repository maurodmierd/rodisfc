let currentSlide = 0
let slides = []
let totalSlides = 0
let indicadores = []
let autoPlayInterval = null

// Carrusel de noticias
function iniciarCarrusel() {
    slides = document.querySelectorAll('.carrusel-slide')
    totalSlides = slides.length
    indicadores = document.querySelectorAll('.indicador')
    
    if (totalSlides == 0){
        console.log('non hai slides.')
        return false;
    }
    
    // eventos para navegacion
    let prevBtn = document.getElementById('prev-noticia')
    let nextBtn = document.getElementById('next-noticia')
    prevBtn.addEventListener('click', prevSlide)
    nextBtn.addEventListener('click', nextSlide)
    
    // indicadores de navegacion
    indicadores.forEach((indicador, index) => {
        indicador.addEventListener('click', () => goToSlide(index));
    })
    
    // iniciar autoplay
    startAutoPlay()
    
    // pausar autoplay ao detectar o raton
    let carrusel = document.querySelector('.carrusel-container')
    carrusel.addEventListener('mouseenter', stopAutoPlay)
    carrusel.addEventListener('mouseleave', startAutoPlay)
}

function goToSlide(slideIndex) {
    // quitar clase active do slide actual
    slides[currentSlide].classList.remove('active')
    indicadores[currentSlide].classList.remove('active')
    
    // actualizar slide actual
    currentSlide = slideIndex
    
    // añadir clase active o novo slide
    slides[currentSlide].classList.add('active')
    indicadores[currentSlide].classList.add('active');
}

function nextSlide() {
    // Se o indice actual é o ultimo reiniciar ao primeiro
    let nextIndex = (currentSlide + 1)<totalSlides ? (currentSlide + 1) : 0
    goToSlide(nextIndex)
}

function prevSlide() {
    // Se o indice actual é o primeiro ir ao ultimo
    let prevIndex = (currentSlide - 1)<0 ? (totalSlides - 1) : (currentSlide - 1)
    goToSlide(prevIndex);
}

function startAutoPlay() {
    // porque se duplicaban os intervalos
    stopAutoPlay();
    
    autoPlayInterval = setInterval(() => {
        nextSlide()
    }, 5000); // Cambiar cada 5 segundos
}

function stopAutoPlay() {
    if (autoPlayInterval) {
        clearInterval(autoPlayInterval)
        autoPlayInterval = null
    }
}

// Funcions para animacions
function animarElementos(selector, className) {
    let elementos = document.querySelectorAll(selector)
    elementos.forEach(elemento => {
        elemento.classList.add(className)
    })
}

function animarElementosDelay(selector, className, delay) {
    let elementos = document.querySelectorAll(selector)
    elementos.forEach((elemento, index) => {
        setTimeout(() => {
            elemento.classList.add(className)
        }, index * delay)
    })
}

function iniciarAnimacions() {
    // aplicar animacions despois de un timeout
    setTimeout(() => {
        animarElementos('.seccion-animada', 'visible')
        animarElementosConRetraso('.partido-card', 'visible', 100)
    }, 300)
}


// iniciar todo
function iniciar() {
    iniciarCarrusel()
    iniciarAnimacions();
    console.log('Javascript iniciado');
}

document.addEventListener('DOMContentLoaded', iniciar);