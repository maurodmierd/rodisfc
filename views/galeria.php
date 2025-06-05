<?php
include '../includes/header.php';
?>

<div class="container galeria-container">
    <h2><span class="icon">📸</span> Galería de Imaxes</h2>
    <p>Explora todas as imaxes do noso club</p>

    <!-- Filtros -->
    <div class="categoria-filtros">
        <button class="filtro-btn active" onclick="filtrarCategoria('todas')">Todas</button>
        <button class="filtro-btn" onclick="filtrarCategoria('logos')">Logos</button>
        <button class="filtro-btn" onclick="filtrarCategoria('jugadoresSenior')">Xogadores Senior</button>
        <button class="filtro-btn" onclick="filtrarCategoria('jugadoresVeteranos')">Xogadores Veteranos</button>
        <button class="filtro-btn" onclick="filtrarCategoria('equipo')">Equipo</button>
        <button class="filtro-btn" onclick="filtrarCategoria('noticias')">Noticias</button>
        <button class="filtro-btn" onclick="filtrarCategoria('otros')">Outros</button>
    </div>

    <!-- Imaxes -->
    <div class="imagenes-grid" id="imagenes-container">
        <div class="loading-galeria">
            <span class="icon">⏳</span>
            <p>Cargando imaxes...</p>
        </div>
    </div>

    <!-- Paxinas -->
    <div class="paginacion" id="paginacion-container" style="display: none;">
        <button class="btn-paginacion" id="btn-anterior" onclick="cambiarPagina(-1)">
            <span class="icon">◀️</span> Anterior
        </button>
        <div class="info-paginacion" id="info-paginacion">
            Página 1 de 1
        </div>
        <button class="btn-paginacion" id="btn-siguiente" onclick="cambiarPagina(1)">
            Siguiente <span class="icon">▶️</span>
        </button>
    </div>
</div>

<!-- Modal para ver imagen en grande -->
<div id="modal-imagen" class="modal-imagen" style="display: none;">
    <div class="modal-imagen-contenido">
        <span class="cerrar-modal-imagen" onclick="cerrarModalImagen()">&times;</span>
        <div class="imagen-modal-wrapper">
            <img id="modal-imagen-foto" src="/placeholder.svg" alt="">
        </div>
        <div class="imagen-modal-info">
            <h3 id="modal-imagen-nombre"></h3>
            <div class="imagen-modal-meta">
                <span class="imagen-modal-categoria" id="modal-imagen-categoria"></span>
                <span class="imagen-modal-fecha" id="modal-imagen-fecha"></span>
            </div>
            <p id="modal-imagen-descripcion"></p>
        </div>
    </div>
</div>

<script src="js/galeria.js"></script>
<?php include '../includes/footer.php'; ?>
