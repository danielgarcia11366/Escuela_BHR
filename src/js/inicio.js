import { Dropdown } from "bootstrap";
import { Toast } from './funciones';

// Animación de números contadores
function animateNumber(element, start, end, duration) {
    if (!element) return;

    let startTimestamp = null;
    const step = (timestamp) => {
        if (!startTimestamp) startTimestamp = timestamp;
        const progress = Math.min((timestamp - startTimestamp) / duration, 1);
        element.textContent = Math.floor(progress * (end - start) + start);
        if (progress < 1) {
            window.requestAnimationFrame(step);
        }
    };
    window.requestAnimationFrame(step);
}

// Cargar estadísticas desde la API
async function cargarEstadisticas() {
    try {
        // ⭐ CAMBIO: La URL debe usar /API/ en mayúsculas
        const url = '/Escuela_BHR/API/estadisticas';
        const response = await fetch(url);

        // Verificar si la respuesta es OK
        if (!response.ok) {
            console.error('Error HTTP:', response.status);
            usarDatosEjemplo();
            return;
        }

        // Obtener el texto de respuesta primero
        const textoRespuesta = await response.text();
        console.log('Respuesta del servidor:', textoRespuesta);

        // Intentar parsear como JSON
        const resultado = JSON.parse(textoRespuesta);

        if (resultado.codigo === 1) {
            const datos = resultado.datos;

            // Animar los números
            animateNumber(document.getElementById('totalAlumnos'), 0, datos.totalAlumnos, 2000);
            animateNumber(document.getElementById('cursosActivos'), 0, datos.cursosActivos, 2000);
            animateNumber(document.getElementById('promocionesActivas'), 0, datos.promocionesActivas, 2000);
            animateNumber(document.getElementById('graduados'), 0, datos.graduados, 2000);

            // Actualizar actividades si existen
            actualizarActividades(datos.actividades);

            console.log('✅ Estadísticas cargadas correctamente');
        } else {
            console.error('Error en la respuesta:', resultado.mensaje);
            Toast.fire({
                icon: 'warning',
                title: 'No se pudieron cargar las estadísticas'
            });
            usarDatosEjemplo();
        }

    } catch (error) {
        console.error('Error al cargar estadísticas:', error);
        Toast.fire({
            icon: 'error',
            title: 'Error al conectar con el servidor'
        });
        usarDatosEjemplo();
    }
}

// Usar datos de ejemplo si falla la API
function usarDatosEjemplo() {
    console.warn('⚠️ Usando valores por defecto (0)');
    animateNumber(document.getElementById('totalAlumnos'), 0, 0, 1000);
    animateNumber(document.getElementById('cursosActivos'), 0, 0, 1000);
    animateNumber(document.getElementById('promocionesActivas'), 0, 0, 1000);
    animateNumber(document.getElementById('graduados'), 0, 0, 1000);
}

// Actualizar sección de actividades recientes
function actualizarActividades(actividades) {
    if (!actividades || actividades.length === 0) {
        console.log('ℹ️ No hay actividades para mostrar');
        return;
    }

    const activityBody = document.querySelector('.activity-body');
    if (!activityBody) {
        console.warn('⚠️ No se encontró el elemento .activity-body');
        return;
    }

    // Limpiar actividades actuales
    activityBody.innerHTML = '';

    actividades.forEach(actividad => {
        const activityItem = document.createElement('div');
        activityItem.className = 'activity-item';

        const fecha = new Date(actividad.fecha);
        const ahora = new Date();
        const diffHoras = Math.floor((ahora - fecha) / (1000 * 60 * 60));

        let tiempoTexto = '';
        if (diffHoras < 1) {
            tiempoTexto = 'Hace menos de 1 hora';
        } else if (diffHoras < 24) {
            tiempoTexto = `Hace ${diffHoras} hora${diffHoras > 1 ? 's' : ''}`;
        } else {
            const diffDias = Math.floor(diffHoras / 24);
            tiempoTexto = `Hace ${diffDias} día${diffDias > 1 ? 's' : ''}`;
        }

        activityItem.innerHTML = `
            <div class="activity-icon bg-success">
                <i class="bi bi-person-check"></i>
            </div>
            <div class="activity-content">
                <h6>Nuevo Alumno Registrado</h6>
                <p>${actividad.descripcion}</p>
                <small><i class="bi bi-clock"></i> ${tiempoTexto}</small>
            </div>
        `;

        activityBody.appendChild(activityItem);
    });

    console.log(`✅ ${actividades.length} actividades actualizadas`);
}

// Cargar estadísticas cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function () {
    console.log('🚀 Cargando dashboard...');
    cargarEstadisticas();

    // Actualizar cada 5 minutos (opcional)
    setInterval(() => {
        console.log('🔄 Actualizando estadísticas...');
        cargarEstadisticas();
    }, 5 * 60 * 1000);
});