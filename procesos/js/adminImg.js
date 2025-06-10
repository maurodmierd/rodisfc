let categoriaActual = 'todas'
let imagenes = []


function abrirGaleria() {
    if (!document.getElementById('galeria')) {
        crearModalGaleria();
    }
    document.getElementById('galeria').style.display = 'flex';
    cargarImagenes()
}

function cerrarGaleria() {
    let modal = document.getElementById('galeria')
    if (modal) {
        modal.style.display = 'none'
    }
}

function crearModalGaleria() {
    let modalHTML = `
        <div id="galeria" class="modal-galeria" style="display: none;">
            <div class="galeria-contenido">
                <div class="galeria-header">
                    <h3><i class="fas fa-camera"></i> Galería de Imaxes</h3>
                    <span class="cerrar-galeria" onclick="cerrarGaleria()">&times;</span>
                </div>

                <div class="galeria-tabs">
                    <button class="tab-btn active" onclick="cambiarTab('ver')">
                        <i class="fas fa-eye"></i> Ver Imaxes
                    </button>
                    <button class="tab-btn" onclick="cambiarTab('subir')">
                        <i class="fas fa-envelope-open"></i> Subir Imaxe
                    </button>
                </div>

                <div id="tab-ver" class="tab-content active">
                    <div class="categoria-filtros">
                        <button class="filtro-btn active" onclick="filtrarCategoria('todas')">Todas</button>
                        <button class="filtro-btn" onclick="filtrarCategoria('logos')">Logos</button>
                        <button class="filtro-btn" onclick="filtrarCategoria('jugadoresSenior')">Xogadores Senior</button>
                        <button class="filtro-btn" onclick="filtrarCategoria('jugadoresVeteranos')">Xogadores Veteranos</button>
                        <button class="filtro-btn" onclick="filtrarCategoria('equipo')">Equipo</button>
                        <button class="filtro-btn" onclick="filtrarCategoria('noticias')">Noticias</button>
                        <button class="filtro-btn" onclick="filtrarCategoria('otros')">Outros</button>
                    </div>

                    <div class="imagenes-grid" id="imagenes-container">

                    </div>
                </div>

                <div id="tab-subir" class="tab-content">
                    <form id="form-subir-imagen" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="imagen-archivo">Seleccionar Imaxe:</label>
                            <input type="file" id="imagen-archivo" name="imagen" accept="image/*" required>
                            <div class="preview-container" id="preview-container" style="display: none;">
                                <img id="preview-imagen" src="../../img/logos/placeholder.png" alt="Preview">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="imagen-nombre">Nome da imaxe:</label>
                            <input type="text" id="imagen-nombre" name="nombre" placeholder="Nome da imaxe" required>
                        </div>

                        <div class="form-group">
                            <label for="imagen-categoria">Categoría:</label>
                            <select id="imagen-categoria" name="categoria" required>
                                <option value="">Seleccionar categoría</option>
                                <option value="logos">Logos</option>
                                <option value="jugadoresSenior">Xogadores Senior</option>
                                <option value="jugadoresVeteranos">Xogadores Veteranos</option>
                                <option value="equipo">Equipo</option>
                                <option value="noticias">Noticias</option>
                                <option value="otros">Outros</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="imagen-descripcion">Descripción (opcional):</label>
                            <textarea id="imagen-descripcion" name="descripcion" placeholder="Descrición da imaxe"></textarea>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn-subir">
                                <i class="fas fa-envelope-open"></i> Subir Imaxe
                            </button>
                            <button type="button" onclick="limpiarFormulario()" class="btn-limpiar">
                                <i class="fas fa-trash"></i> Limpar
                            </button>
                        </div>

                        <div id="upload-progress" class="upload-progress" style="display: none;">
                            <div class="progress-bar">
                                <div class="progress-fill"></div>
                            </div>
                            <span class="progress-text">Subindo...</span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    `
    
    // engadir a body
    document.body.insertAdjacentHTML('beforeend', modalHTML)
    
    // iniciar event listeners
    iniciarEventos();
}

function iniciarEventos() {
    let archivoInput = document.getElementById('imagen-archivo')
    let previewContainer = document.getElementById('preview-container')
    let previewImg = document.getElementById('preview-imagen')
    let form = document.getElementById('form-subir-imagen')
    
    // Preview
    archivoInput.addEventListener('change', function(e) {
        let file = e.target.files[0]
        if (file) {
            let reader = new FileReader()
            reader.onload = function(e) {
                previewImg.src = e.target.result
                previewContainer.style.display = 'block'
            };
            reader.readAsDataURL(file)
            
            // Nome default
            let nombreInput = document.getElementById('imagen-nombre')
            if (!nombreInput.value) {
                nombreInput.value = file.name.split('.')[0]
            }
        }
    })
    
    // Evitar que se envie o formulario
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        subirImagen()
    })
    
    // Cerrar facendo clic fora ou ca tecla escape
    let modal = document.getElementById('modal-jugador')
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
}
function cambiarTab(tab) {
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'))
    document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'))
    document.querySelector(`[onclick="cambiarTab('${tab}')"]`).classList.add('active')
    document.getElementById(`tab-${tab}`).classList.add('active')
    // Se cambiamos a pestaña de ver, recargamos as imaxes
    if (tab === 'ver') {
        cargarImagenes();
    }
}

// Obter imxes
function cargarImagenes() {
    fetch('../../src/img/obter.php')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            imagenes = data.data
            mostrarImagenes()
        } else {
            console.error('Erro ao cargar imaxes:', data.message)
            mostrarError('Erro ao cargar imaxes')
        }
    })
}

// Mostrar imaxes filtradas
function mostrarImagenes() {
    let container = document.getElementById('imagenes-container')
    let imagenesFiltradas = categoriaActual === 'todas' ? imagenes : imagenes.filter(img => img.categoria === categoriaActual)
    if (imagenesFiltradas.length === 0) {
        container.innerHTML = `
            <div class="no-imagenes">
                <span class="icon">📭</span>
                <p>Non hai imaxes</p>
            </div>
        `
        return;
    }
    
    container.innerHTML = imagenesFiltradas.map(imagen => `
        <div class="imagen-item" data-categoria="${imagen.categoria}">
            <div class="imagen-wrapper">
                <img src="../img/${imagen.categoria}/${imagen.nombre}" 
                     alt="${imagen.descripcion || imagen.nombre}"
                     onclick="seleccionarImagen(this)"
                     data-id="${imagen.id}"
                     data-nombre="${imagen.nombre}"
                     data-categoria="${imagen.categoria}"
                     onerror="this.src='../../img/logos/placeholder.png?height=150&width=200'">
                <div class="imagen-overlay">
                    <button class="btn-seleccionar" onclick="seleccionarImagen(this.parentElement.previousElementSibling)">
                        <span class="icon">✓</span> Seleccionar
                    </button>
                    <button class="btn-eliminar" onclick="eliminarImagen(${imagen.id}, '${imagen.categoria}', '${imagen.nombre}')">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            <div class="imagen-info">
                <h4>${imagen.nombre}</h4>
                <span class="categoria-badge ${imagen.categoria}">${formatearCategoria(imagen.categoria)}</span>
                ${imagen.descripcion ? `<p>${imagen.descripcion}</p>` : ''}
            </div>
        </div>
    `).join('')
}

// Filtrar categorias
function filtrarCategoria(categoria) {
    categoriaActual = categoria
    // Actualizar botons activos
    document.querySelectorAll('.filtro-btn').forEach(btn => btn.classList.remove('active'))
    document.querySelector(`[onclick="filtrarCategoria('${categoria}')"]`).classList.add('active')
    mostrarImagenes()
}

// Seleccionar imaxe
function seleccionarImagen(img) {
    let id = img.getAttribute('data-id')
    let nombre = img.getAttribute('data-nombre')
    let categoria = img.getAttribute('data-categoria')
    let input = document.getElementById('fotoSeleccionada')
    let preview = document.getElementById('previewImagenSeleccionada')

    if (input && preview) {
        input.value = id
        preview.innerHTML = `
            <div class="imagen-seleccionada">
                <p><strong>Imaxe seleccionada:</strong></p>
                <img src="../img/${categoria}/${nombre}" alt="${nombre}" onerror="this.src='../../img/logos/placeholder.png?height=150&width=200'">
                <p>${nombre}</p>
            </div>
        `
    }
    cerrarGaleria();
}

// Subir nova imaxe
function subirImagen() {
    let form = document.getElementById('form-subir-imagen')
    let formData = new FormData(form)
    let progressContainer = document.getElementById('upload-progress')
    let progressFill = document.querySelector('.progress-fill')
    let progressText = document.querySelector('.progress-text')
    
    // Mostrar progreso
    progressContainer.style.display = 'block'
    let xhr = new XMLHttpRequest()
    xhr.upload.addEventListener('progress', function(e) {
        if (e.lengthComputable) {
            let percentComplete = (e.loaded / e.total) * 100
            progressFill.style.width = percentComplete + '%'
            progressText.textContent = `Subindo... ${Math.round(percentComplete)}%`
        }
    })
    
    xhr.addEventListener('load', function() {
        if (xhr.status === 200) {
            try {
                let response = JSON.parse(xhr.responseText)
                if (response.success) {
                    mostrarExito('Imaxe subida correctamente')
                    limpiarFormulario()
                    cargarImagenes()
                    cambiarTab('ver')
                } else {
                    mostrarError('Erro: ' + response.message)
                }
            } catch (e) {
                mostrarError('Erro ao procesar');
            }
        } else {
            mostrarError('Error ao subir a imaxe')
        }
        progressContainer.style.display = 'none'
    });
    
    xhr.addEventListener('error', function() {
        mostrarError('Erro de conexión')
        progressContainer.style.display = 'none'
    })
    xhr.open('POST', '../../src/img/subir.php')
    xhr.send(formData)
}

// Limpar formulario
function limpiarFormulario() {
    let form = document.getElementById('form-subir-imagen')
        form.reset()
        document.getElementById('preview-container').style.display = 'none'
}

// eliminar imagen
function eliminarImagen(id, categoria, nombre) {
    if (confirm('Está seguro de querer eliminar esta imaxe?')) {
        fetch('../../src/img/eliminar.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarExito('Imaxe eliminada correctamente')
                cargarImagenes()
            } else {
                mostrarError('Erro: ' + data.message)
            }
        })
        .catch(error => {
            console.error('Erro:', error)
            mostrarError('Erro ó eliminar a imaxe')
        })
    }
}

function formatearCategoria(categoria) {
    let nombres = {
        'logos': 'Logos',
        'jugadoresSenior': 'Jugadores Senior',
        'jugadoresVeteranos': 'Jugadores Veteranos',
        'equipo': 'Equipo',
        'noticias': 'Noticias',
        'otros': 'Otros'
    };
    return nombres[categoria] || categoria;
}


//Notificacions success error
function mostrarExito(mensaje) {
    mostrarNotificacion(mensaje, 'success');
}
function mostrarError(mensaje) {
    mostrarNotificacion(mensaje, 'error');
}
function mostrarNotificacion(mensaje, tipo) {
    let notificacion = document.getElementById('notificacion-galeria');
    if (!notificacion) {
        notificacion = document.createElement('div');
        notificacion.id = 'notificacion-galeria';
        notificacion.className = 'notificacion-galeria';
        document.body.appendChild(notificacion);
    }
    notificacion.className = `notificacion-galeria ${tipo}`;
    notificacion.textContent = mensaje;
    notificacion.style.display = 'block';
    // A notificacion eliminase aos 3s
    setTimeout(() => {
        notificacion.style.display = 'none';
    }, 3000);
}

function crearElementosNecesarios() {
    // Crear input oculto para imaxe seleccionada
    if (!document.getElementById('fotoSeleccionada')) {
        let input = document.createElement('input');
        input.type = 'hidden';
        input.id = 'fotoSeleccionada';
        input.name = 'imagen_id';
        input.value = '';
        document.body.appendChild(input);
    }
    // div para preview da imaxe seleccionada
    if (!document.getElementById('previewImagenSeleccionada')) {
        let preview = document.createElement('div');
        preview.id = 'previewImagenSeleccionada';
        document.body.appendChild(preview);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    crearElementosNecesarios();
})
