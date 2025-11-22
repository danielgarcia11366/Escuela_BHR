import { Dropdown } from "bootstrap";
import { Toast, validarFormulario } from "../funciones";
import Swal from "sweetalert2";
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

// Referencias DOM
const tabla = document.getElementById('tablaParticipantes');

// ✅ DATATABLE - Muestra personas con sus cursos
const datatable = new DataTable('#tablaParticipantes', {
    language: lenguaje,
    pageLength: 15,
    lengthMenu: [5, 10, 25, 50, 100],
    columns: [
        {
            title: 'No.',
            data: 'per_catalogo',
            width: '5%',
            render: (data, type, row, meta) => meta.row + 1
        },
        {
            title: 'Catálogo',
            data: 'per_catalogo',
            width: '8%',
            render: (data) => `<span class="badge bg-dark">${data}</span>`
        },
        {
            title: 'Nombre Completo',
            data: 'nombre_completo',
            width: '35%',
            render: (data, type, row) => `
                <div>
                    <strong>${row.grado_arma}</strong><br>
                    <span class="text-muted">${data}</span>
                </div>
            `
        },
        {
            title: 'Total de Cursos',
            data: 'total_cursos',
            width: '12%',
            className: 'text-center',
            render: (data) => {
                const color = data > 0 ? 'success' : 'secondary';
                return `<span class="badge bg-${color} fs-6">${data}</span>`;
            }
        },
        {
            title: 'Último Curso Completado',
            data: 'ultimo_curso',
            width: '25%',
            render: (data) => data || '<span class="text-muted fst-italic">Sin cursos registrados</span>'
        },
        {
            title: 'Ver Historial',
            data: 'per_catalogo',
            orderable: false,
            searchable: false,
            width: '15%',
            className: 'text-center',
            render: (data, type, row) => `
                <button class="btn btn-warning btn-lg btn-ver-cursos"
        data-catalogo="${data}"
        data-nombre="${row.nombre_completo}"
        type="button"
        data-bs-toggle="tooltip"
        data-bs-placement="top"
        title="Ver historial de cursos">
    <i class="bi bi-eye-fill"></i>  
    <i class="bi bi-journal-text"></i>
</button>

            `
        }
    ]
});

// ✅ BUSCAR - Obtiene el resumen por persona
const buscar = async () => {
    try {
        const url = "/Escuela_BHR/API/participantes/buscarPersonal";
        const resp = await fetch(url);

        if (!resp.ok) {
            console.error("❌ Error HTTP:", resp.status, resp.statusText);
            return;
        }

        const data = await resp.json();
        const { datos } = data;
        console.log("✅ Datos recibidos:", datos);

        datatable.clear().draw();
        if (datos && datos.length > 0) {
            datatable.rows.add(datos).draw();
        } else {
            console.warn("⚠️ No se encontraron datos de personal");
        }
    } catch (error) {
        console.error("💥 Error al buscar personal:", error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Ocurrió un error al cargar los datos.',
            confirmButtonColor: '#dc3545'
        });
    }
};

// ============================================
// EVENTO DELEGADO PARA VER CURSOS PDF
// ============================================
document.addEventListener('click', (e) => {
    const boton = e.target.closest('.btn-ver-cursos');

    if (boton) {
        const catalogo = boton.getAttribute('data-catalogo');
        const nombre = boton.getAttribute('data-nombre');

        console.log('📄 Generando PDF para:', { catalogo, nombre });

        Toast.fire({
            icon: 'info',
            title: 'Generando PDF...',
            timer: 1500
        });

        // Abrir PDF en nueva pestaña
        const url = `/Escuela_BHR/record/historialPDF?per_catalogo=${catalogo}`;
        window.open(url, '_blank');
    }
});

// Cargar al inicio
buscar();