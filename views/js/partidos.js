function mostrarDetallePartido(partidoId) {
    let modal = document.getElementById("modal-partido-detalle");
    let contenido = document.getElementById("contenido-partido-detalle");
    contenido.innerHTML = `
        <div class="loading-partido">
            <span class="icon">⏳</span>
            <p>Cargando detalles del partido...</p>
        </div>
    `;
    modal.style.display = "flex";
    document.body.style.overflow = "hidden";

    // fetch para o detalle dos partidos
    fetch('../api/obterPartidos.php?id=' + partidoId)
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                mostrarDetallesPartido(data.data);
            } else {
                mostrarErrorPartido(data.message);
            }
        })
        .catch((error) => {
            mostrarErrorPartido("Error de conexión:" + error.message);
        });
}

function mostrarDetallesPartido(partido) {
    let contenido = document.getElementById("contenido-partido-detalle");
    let esPasado = new Date(partido.fecha) < new Date();
    let fecha = new Date(partido.fecha).toLocaleDateString("gl-ES", { weekday: "long", year: "numeric", month: "long", day: "numeric", });
    contenido.innerHTML = `
        <div class="partido-detalle">
            <div class="partido-header-detalle">
                <span class="equipo-badge ${partido.equipo.toLowerCase()}">${partido.equipo.charAt(0).toUpperCase() + partido.equipo.slice(1)
        }</span>
                <span class="fecha-detalle">${fecha}</span>
            </div>
            
            <div class="partido-equipos-detalle">
                <div class="equipo-detalle local">
                    <h3>${partido.equipo_local}</h3>
                    ${esPasado && partido.goles_local !== null
            ? `<div class="goles-detalle">${partido.goles_local}</div>`
            : ""
        }
                </div>
                
                <div class="vs-detalle">
                    ${esPasado &&
            partido.goles_local !== null &&
            partido.goles_visitante !== null
            ? `<div class="resultado-detalle">${partido.goles_local} - ${partido.goles_visitante}</div>`
            : `<div class="hora-detalle">
                             <span class="icon">🕐</span>
                             ${partido.hora.substring(0, 5)}
                           </div>`
        }
                </div>
                
                <div class="equipo-detalle visitante">
                    <h3>${partido.equipo_visitante}</h3>
                    ${esPasado && partido.goles_visitante !== null
            ? `<div class="goles-detalle">${partido.goles_visitante}</div>`
            : ""
        }
                </div>
            </div>
            
            <div class="partido-info-detalle">
                <div class="info-item">
                    <span class="icon">📍</span>
                    <strong>Lugar:</strong> ${partido.lugar}
                </div>
                <div class="info-item">
                    <span class="icon">📅</span>
                    <strong>Data:</strong> ${fecha}
                </div>
                <div class="info-item">
                    <span class="icon">🕐</span>
                    <strong>Hora:</strong> ${partido.hora.substring(0, 5)}
                </div>
            </div>
        </div>
    `;
}

function mostrarErrorPartido(mensaje) {
    let contenido = document.getElementById("contenido-partido-detalle");
    contenido.innerHTML = `
        <div class="error-partido">
            <span class="icon">❌</span>
            <p>${mensaje}</p>
        </div>
    `;
}

function cerrarModalPartido() {
    let modal = document.getElementById("modal-partido-detalle");
    modal.style.display = "none";
    document.body.style.overflow = "auto";
}

// eventos
document.addEventListener("DOMContentLoaded", () => {
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

    animarCalendario();
});

function animarCalendario() {
    let dias = document.querySelectorAll(".dia-calendario");
    dias.forEach((dia, index) => {
        setTimeout(() => {
            dia.classList.add("visible");
        }, index * 20);
    });
}
