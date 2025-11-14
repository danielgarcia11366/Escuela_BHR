import { Dropdown } from "bootstrap";
import { Toast, validarFormulario } from "../funciones";
import Swal from "sweetalert2";
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

// ============================================
// REFERENCIAS A ELEMENTOS DEL DOM
// ============================================
const tablaHistorial = document.getElementById('tablaHistorial');

// Elementos de información
const totalPromociones = document.getElementById('totalPromociones');
const totalParticipantes = document.getElementById('totalParticipantes');
const totalGraduados = document.getElementById('totalGraduados');
const promocionesConCert = document.getElementById('promocionesConCert');

// ============================================
// VARIABLES GLOBALES
// ============================================
let datatableHistorial = null;

// ============================================
// CONFIGURACIÓN DE DATATABLE HISTORIAL
// ============================================
const inicializarTablaHistorial = (datos) => {
    if (datatableHistorial) {
        datatableHistorial.destroy();
    }

    datatableHistorial = new DataTable('#tablaHistorial', {
        language: lenguaje,
        data: datos,
        pageLength: 15,
        lengthMenu: [5, 10, 15, 25, 50, 100],
        order: [[1, 'desc']],
        responsive: true,
        columns: [
            {
                data: null,
                width: '50px',
                className: 'text-center',
                render: (data, type, row, meta) => `<strong>${meta.row + 1}</strong>`
            },
            {
                title: 'Promoción / Curso',
                data: null,
                render: (data, type, row) => {
                    return `
                        <div class="d-flex flex-column align-items-start">
                            <strong>${row.curso_completo}</strong>
                            <span class="badge bg-primary mb-2 fs-6">Promoción ${row.numero_anio}</span>
                        </div>
                    `;
                }
            },
            {
                title: 'Fecha Inicio',
                data: 'pro_fecha_inicio',
                width: '120px',
                className: 'text-center',
                render: data => {
                    const fecha = new Date(data + 'T00:00:00');
                    return `<i class="bi bi-calendar-event text-success"></i> ${fecha.toLocaleDateString('es-GT')}`;
                }
            },
            {
                title: 'Fecha Fin',
                data: 'pro_fecha_fin',
                width: '120px',
                className: 'text-center',
                render: data => {
                    const fecha = new Date(data + 'T00:00:00');
                    return `<i class="bi bi-calendar-check text-danger"></i> ${fecha.toLocaleDateString('es-GT')}`;
                }
            },
            {
                title: 'Lugar',
                data: 'pro_lugar',
                render: data => data ? `<i class="bi bi-geo-alt-fill text-primary"></i> ${data}` : '<span class="text-muted">No especificado</span>'
            },
            {
                title: 'Participantes',
                data: 'total_participantes',
                width: '100px',
                className: 'text-center',
                render: (data, type, row) => {
                    const total = parseInt(data) || 0;
                    const colorClass = total > 0 ? 'bg-info' : 'bg-secondary';
                    return `<span class="badge ${colorClass} fs-6">${total}</span>`;
                }
            },
            {
                title: 'Estado',
                data: 'pro_activa',
                width: '100px',
                className: 'text-center',
                render: data => data === 'S'
                    ? '<span class="badge bg-success"><i class="bi bi-check-circle"></i> Activa</span>'
                    : '<span class="badge bg-secondary"><i class="bi bi-x-circle"></i> Inactiva</span>'
            },
            {
                title: 'Ver Listado',
                data: null,
                width: '150px',
                className: 'text-center',
                searchable: false,
                orderable: false,
                render: (data, type, row) => {
                    const total = parseInt(row.total_participantes) || 0;
                    return `
                        <button class='btn btn-ver-pdf btn-sm'
                            data-pro_codigo="${row.pro_codigo}"
                            data-numero_anio="${row.numero_anio}"
                            type="button"
                            title="Generar PDF con los ${total} participantes">
                            <i class="bi bi-eye"></i> VER 
                        </button>
                    `;
                }
            }
        ]
    });
};

// ============================================
// BUSCAR PROMOCIONES
// ============================================
const buscarPromociones = async () => {
    try {
        const url = "/Escuela_BHR/API/promociones/buscar";
        const respuesta = await fetch(url);
        const data = await respuesta.json();
        const { codigo, datos, mensaje } = data;

        console.log('📊 Datos recibidos:', data);

        if (codigo === 1 && datos && datos.length > 0) {
            // 🔍 DEBUG: Ver estructura de los datos
            console.log('📋 Primer registro:', datos[0]);
            console.log('👥 Total participantes del primer registro:', datos[0].total_participantes);

            inicializarTablaHistorial(datos);
            actualizarEstadisticas(datos);
        } else {
            inicializarTablaHistorial([]);
            Toast.fire({
                icon: 'info',
                title: 'No hay promociones registradas'
            });
        }

    } catch (error) {
        console.error('❌ Error al cargar promociones:', error);
        Toast.fire({
            icon: 'error',
            title: 'Error al cargar los datos'
        });
    }
};

// ============================================
// ACTUALIZAR ESTADÍSTICAS
// ============================================
const actualizarEstadisticas = (datos) => {
    const totalPromos = datos.length;
    const totalParts = datos.reduce((sum, item) => sum + parseInt(item.total_participantes || 0), 0);
    const promosConCert = datos.filter(p => p.pro_fecha_graduacion).length;

    animarNumero(totalPromociones, totalPromos);
    animarNumero(totalParticipantes, totalParts);

    if (promocionesConCert) {
        animarNumero(promocionesConCert, promosConCert);
    }

    if (totalGraduados) {
        totalGraduados.textContent = '-';
    }
};

// ============================================
// ANIMAR NÚMEROS
// ============================================
const animarNumero = (elemento, valorFinal) => {
    if (!elemento) return;

    const duracion = 1000;
    const incremento = Math.ceil(valorFinal / 50);
    let valorActual = 0;

    const intervalo = setInterval(() => {
        valorActual += incremento;
        if (valorActual >= valorFinal) {
            valorActual = valorFinal;
            clearInterval(intervalo);
        }
        elemento.textContent = valorActual;
    }, duracion / 50);
};

// ============================================
// GENERAR PDF
// ============================================
const generarPDF = (pro_codigo, numero_anio) => {
    console.log('📄 Generando PDF para promoción:', { pro_codigo, numero_anio });

    Toast.fire({
        icon: 'info',
        title: 'Generando PDF...',
        timer: 2000
    });

    // Abrir en nueva pestaña
    const url = `/Escuela_BHR/promociones/pdf?pro_codigo=${pro_codigo}`;
    window.open(url, '_blank');
};

// ============================================
// EVENTO DELEGADO PARA BOTONES PDF
// ============================================
document.addEventListener('click', (e) => {
    const boton = e.target.closest('.btn-ver-pdf');

    if (boton) {
        const pro_codigo = boton.getAttribute('data-pro_codigo');
        const numero_anio = boton.getAttribute('data-numero_anio');

        console.log('🔍 Botón PDF clickeado:', { pro_codigo, numero_anio });

        generarPDF(pro_codigo, numero_anio);
    }
});

// ============================================
// INICIALIZACIÓN
// ============================================
document.addEventListener('DOMContentLoaded', () => {
    console.log('✅ DOM cargado, iniciando búsqueda de promociones');
    buscarPromociones();
});

// ============================================
// EXPORTAR FUNCIONES
// ============================================
export { buscarPromociones, generarPDF };