import { Dropdown } from "bootstrap";

// Animación de números contadores
function animateNumber(element, start, end, duration) {
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

// Simula carga de datos (reemplaza esto con llamadas reales a tu API/base de datos)
document.addEventListener('DOMContentLoaded', function () {
    // Aquí deberías hacer fetch a tu API para obtener datos reales
    // Por ahora usamos datos de ejemplo

    setTimeout(() => {
        animateNumber(document.getElementById('totalAlumnos'), 0, 150, 2000);
        animateNumber(document.getElementById('cursosActivos'), 0, 12, 2000);
        animateNumber(document.getElementById('promocionesActivas'), 0, 5, 2000);
        animateNumber(document.getElementById('graduados'), 0, 320, 2000);
    }, 300);
});