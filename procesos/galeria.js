function abrirGaleria() {
    document.getElementById('galeria').style.display = 'flex';
}

function cerrarGaleria() {
    document.getElementById('galeria').style.display = 'none';
}

function seleccionarImagen(img) {
    const id = img.getAttribute('data-id');
    const nombre = img.getAttribute('data-nombre');

    const input = document.getElementById('fotoSeleccionada');
    const preview = document.getElementById('previewImagenSeleccionada');

    if (input && preview) {
        input.value = id;
        preview.innerHTML = `<p><strong>Imagen seleccionada:</strong></p>
            <img src="../img/${nombre}" style="max-width: 200px;">`;
    }

    cerrarGaleria();
}