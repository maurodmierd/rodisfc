document.addEventListener("DOMContentLoaded", () => {
    // A cada input asignamoslle o valor do dataset do botón
    document.querySelectorAll(".btn-editar").forEach((btn) => {
        btn.addEventListener("click", () => {
            document.getElementById("edit-id").value = btn.dataset.id;
            document.getElementById("edit-nombre").value = btn.dataset.nombre;
            document.getElementById("edit-apellidos").value = btn.dataset.apellidos;
            document.getElementById("edit-email").value = btn.dataset.email;
            document.getElementById("edit-telefono").value = btn.dataset.telefono;
            document.getElementById("edit-rol").value = btn.dataset.rol;
            document.getElementById("modal-editar").style.display = "block";
        })
    })
})

function cerrarModal() {
    document.getElementById("modal-editar").style.display = "none";
}

function confirmarEdicion() {
    return confirm("¿Confirmas gardar os cambios deste usuario?");
}

function confirmarEliminacion(dni) {
    if (confirm("¿Seguro que queres eliminar o usuario con DNI " + dni + "?")) {
        if (confirm("Perderás toda a información asociada a este usuario. ¿Confirmas?")) {
            // peticion para obter noticia
            fetch("../api/eliminarUsuario.php?id="+dni)
                .then((response) => response.json())
                .then((data) => {
                if (data.success) {
                    mostrarExito(data.message);
                } else {
                    mostrarError("Erro ao eliminar o usuario: " + data.message);
                }
                })
                .catch(mostrarErrorNoticia("Erro de conexión"));

        }
    }
}

function anotar(id) {
    document.getElementById("nota-id").value = id;
    document.getElementById("modal-notas").style.display = "block";
}

function cerrarNotas() {
    document.getElementById("modal-notas").style.display = "none";
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