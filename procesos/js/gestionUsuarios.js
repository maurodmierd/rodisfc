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
            window.location.href = "eliminarUsuario.php?id=" + encodeURIComponent(dni);
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