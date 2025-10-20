import { Dropdown } from "bootstrap";
import { Toast, validarFormulario } from "../funciones";
import Swal from "sweetalert2";
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

// Elementos del DOM
const formulario = document.getElementById('formularioCursos');
const tabla = document.getElementById('tablaCursos');
const contenedorFormulario = document.getElementById('contenedorFormulario');
const contenedorTabla = document.getElementById('contenedorTabla');
const tituloFormulario = document.getElementById('tituloFormulario');

// Botones y contenedores
const btnFlotante = document.getElementById('btnFlotante');
const btnGuardar = document.getElementById('btnGuardar');
const btnModificar = document.getElementById('btnModificar');
const btnCancelar = document.getElementById('btnCancelar');
const contenedorBtnGuardar = document.getElementById('contenedorBtnGuardar');
const contenedorBtnModificar = document.getElementById('contenedorBtnModificar');
const contenedorBtnCancelar = document.getElementById('contenedorBtnCancelar');

// Estado inicial - Solo mostrar tabla y botón flotante
contenedorBtnModificar.style.display = 'none';
contenedorFormulario.style.display = 'none';

// Función para mostrar formulario con animación
const mostrarFormulario = (esModificacion = false) => {
    // Cambiar título según el modo
    tituloFormulario.textContent = esModificacion ? 'Modificar Curso' : 'Nuevo Curso';
    
    // Mostrar/ocultar botones según el modo
    if (esModificacion) {
        // Modo modificar: Botón Modificar + Cancelar (cada uno ocupa 50%)
        contenedorBtnGuardar.style.display = 'none';
        contenedorBtnModificar.style.display = 'block';
        contenedorBtnCancelar.style.display = 'block';
    } else {
        // Modo nuevo: Botón Guardar + Cancelar (cada uno ocupa 50%)
        contenedorBtnGuardar.style.display = 'block';
        contenedorBtnModificar.style.display = 'none';
        contenedorBtnCancelar.style.display = 'block';
    }
    
    // Animar la aparición del formulario
    contenedorFormulario.style.display = 'block';
    contenedorFormulario.classList.add('slide-down');
    
    // Ocultar tabla suavemente
    contenedorTabla.style.opacity = '0';
    setTimeout(() => {
        contenedorTabla.style.display = 'none';
    }, 300);
    
    // Ocultar botón flotante
    btnFlotante.style.display = 'none';
};

// Función para ocultar formulario con animación
const ocultarFormulario = () => {
    // Animar la desaparición del formulario
    contenedorFormulario.classList.remove('slide-down');
    contenedorFormulario.classList.add('slide-up');
    
    setTimeout(() => {
        contenedorFormulario.style.display = 'none';
        contenedorFormulario.classList.remove('slide-up');
        
        // Mostrar tabla suavemente
        contenedorTabla.style.display = 'block';
        setTimeout(() => {
            contenedorTabla.style.opacity = '1';
        }, 50);
        
        // Mostrar botón flotante
        btnFlotante.style.display = 'block';
        
        // Limpiar formulario
        formulario.reset();
        
        // Resetear estado de botones
        contenedorBtnGuardar.style.display = 'block';
        contenedorBtnModificar.style.display = 'none';
    }, 300);
};

// Event Listeners
btnFlotante.addEventListener('click', () => mostrarFormulario(false));
btnCancelar.addEventListener('click', ocultarFormulario);

// DataTable con configuración mejorada
const datatable = new DataTable('#tablaCursos', {
    data: null,
    language: lenguaje,
    pageLength: 10,
    lengthMenu: [5, 10, 25, 50, 100],
    responsive: true,
    columns: [
        {
            title: 'No.',
            data: 'id_curso',
            render: (data, type, row, meta) => meta.row + 1,
            width: '5%',
            className: 'text-center'
        },
        { 
            title: 'Código', 
            data: 'codigo_curso',
            className: 'fw-bold'
        },
        { 
            title: 'Nombre del Curso', 
            data: 'nombre_curso'
        },
        { 
            title: 'Descripción', 
            data: 'descripcion',
            render: (data) => {
                return data && data.length > 50 ? data.substring(0, 50) + '...' : (data || '');
            }
        },
        { 
            title: 'Duración', 
            data: 'duracion_horas',
            render: (data) => `${data} horas`,
            className: 'text-center'
        },
        { 
            title: 'Tipo', 
            data: 'tipo_curso',
            className: 'text-center'
        },
        { 
            title: 'Área', 
            data: 'area_especialidad',
            render: (data) => {
                return data && data.length > 30 ? data.substring(0, 30) + '...' : (data || '');
            }
        },
        { 
            title: 'Estado', 
            data: 'estado_curso',
            render: (data) => {
                const badge = data === 'A' ? 'success' : 'danger';
                const texto = data === 'A' ? 'Activo' : 'Inactivo';
                return `<span class="badge bg-${badge}">${texto}</span>`;
            },
            className: 'text-center'
        },
        { 
            title: 'Fecha Creación', 
            data: 'fecha_creacion',
            render: (data) => {
                if (data) {
                    const fecha = new Date(data);
                    return fecha.toLocaleDateString('es-ES');
                }
                return '';
            },
            className: 'text-center'
        },
        {
            title: 'Acciones',
            data: 'id_curso',
            orderable: false,
            searchable: false,
            className: 'text-center',
            width: '10%',
            render: (data, type, row) => `
                <div class="btn-group" role="group">
                    <button class='btn btn-outline-warning btn-sm modificar'
                        data-id_curso="${data}"
                        data-codigo_curso="${row.codigo_curso}"
                        data-nombre_curso="${row.nombre_curso}"
                        data-descripcion="${row.descripcion || ''}"
                        data-duracion_horas="${row.duracion_horas}"
                        data-requisitos="${row.requisitos || ''}"
                        data-tipo_curso="${row.tipo_curso || ''}"
                        data-area_especialidad="${row.area_especialidad || ''}"
                        data-estado_curso="${row.estado_curso}"
                        title="Modificar curso">
                        <i class='bi bi-pencil-square'></i>
                    </button>
                    <button class='btn btn-outline-danger btn-sm eliminar' 
                        data-id_curso="${data}"
                        title="Eliminar curso">
                        <i class='bi bi-trash'></i>
                    </button>
                </div>
            `
        }
    ],
    order: [[8, 'desc']], // Ordenar por fecha de creación descendente
    dom: '<"row"<"col-md-6"l><"col-md-6"f>>rtip'
});

// Buscar cursos
const buscar = async () => {
    try {
        const respuesta = await fetch("/Escuela_BHR/API/cursos/buscar");
        const data = await respuesta.json();
        
        datatable.clear().draw();
        
        if (data.datos && data.datos.length > 0) {
            datatable.rows.add(data.datos).draw();
        }
    } catch (error) {
        console.error('Error al buscar cursos:', error);
        Toast.fire({ 
            icon: 'error', 
            title: 'Error al cargar los cursos' 
        });
    }
};

// Cargar datos iniciales
buscar();

// Función para guardar curso
const guardar = async (e) => {
    e.preventDefault();
    
    // Deshabilitar botón para evitar doble envío
    btnGuardar.disabled = true;
    const textoOriginal = btnGuardar.innerHTML;
    btnGuardar.innerHTML = '<i class="bi bi-hourglass-split"></i> Guardando...';

    // Validar formulario
    if (!validarFormulario(formulario, ['id_curso'])) {
        Swal.fire({ 
            title: "Campos incompletos", 
            text: "Por favor, complete todos los campos requeridos", 
            icon: "warning",
            confirmButtonColor: '#007bff'
        });
        btnGuardar.disabled = false;
        btnGuardar.innerHTML = textoOriginal;
        return;
    }

    try {
        const body = new FormData(formulario);
        const respuesta = await fetch("/Escuela_BHR/API/cursos/guardar", { 
            method: 'POST', 
            body 
        });
        
        const { codigo, mensaje, detalle } = await respuesta.json();

        if (codigo == 1) {
            Toast.fire({ 
                icon: 'success', 
                title: mensaje || 'Curso guardado exitosamente' 
            });
            
            await buscar(); // Recargar datos
            ocultarFormulario(); // Ocultar formulario
        } else {
            Toast.fire({ 
                icon: 'error', 
                title: mensaje || 'Error al guardar el curso' 
            });
            console.error('Error del servidor:', detalle);
        }
    } catch (error) {
        console.error('Error en la petición:', error);
        Toast.fire({ 
            icon: 'error', 
            title: 'Error de conexión' 
        });
    } finally {
        btnGuardar.disabled = false;
        btnGuardar.innerHTML = textoOriginal;
    }
};

// Event listener para el formulario
formulario.addEventListener('submit', guardar);

// Función para traer datos al formulario (modificar)
const traerDatos = (e) => {
    const dataset = e.currentTarget.dataset;
    
    // Llenar campos del formulario
    formulario.id_curso.value = dataset.id_curso;
    formulario.codigo_curso.value = dataset.codigo_curso;
    formulario.nombre_curso.value = dataset.nombre_curso;
    formulario.descripcion.value = dataset.descripcion;
    formulario.duracion_horas.value = dataset.duracion_horas;
    formulario.requisitos.value = dataset.requisitos;
    formulario.tipo_curso.value = dataset.tipo_curso;
    formulario.area_especialidad.value = dataset.area_especialidad;
    formulario.estado_curso.value = dataset.estado_curso;
    
    // Mostrar formulario en modo modificación
    mostrarFormulario(true);
};

// Función para modificar curso
const modificar = async (e) => {
    e.preventDefault();

    // Validar formulario
    if (!validarFormulario(formulario)) {
        Swal.fire({ 
            title: "Campos incompletos", 
            text: "Por favor, complete todos los campos requeridos", 
            icon: "warning",
            confirmButtonColor: '#007bff'
        });
        return;
    }

    // Deshabilitar botón
    btnModificar.disabled = true;
    const textoOriginal = btnModificar.innerHTML;
    btnModificar.innerHTML = '<i class="bi bi-hourglass-split"></i> Modificando...';

    try {
        const body = new FormData(formulario);
        const respuesta = await fetch("/Escuela_BHR/API/cursos/modificar", { 
            method: 'POST', 
            body 
        });
        
        const { codigo, mensaje, detalle } = await respuesta.json();

        if (codigo == 1) {
            Toast.fire({ 
                icon: 'success', 
                title: mensaje || 'Curso modificado exitosamente' 
            });
            
            await buscar(); // Recargar datos
            ocultarFormulario(); // Ocultar formulario
        } else {
            Toast.fire({ 
                icon: 'error', 
                title: mensaje || 'Error al modificar el curso' 
            });
            console.error('Error del servidor:', detalle);
        }
    } catch (error) {
        console.error('Error en la petición:', error);
        Toast.fire({ 
            icon: 'error', 
            title: 'Error de conexión' 
        });
    } finally {
        btnModificar.disabled = false;
        btnModificar.innerHTML = textoOriginal;
    }
};

// Event listener para modificar
btnModificar.addEventListener('click', modificar);

// Función para eliminar curso
const eliminar = async (e) => {
    const id_curso = e.currentTarget.dataset.id_curso;

    // Confirmación mejorada
    const confirmacion = await Swal.fire({
        icon: 'warning',
        title: 'Confirmar Eliminación',
        text: '¿Está seguro que desea eliminar este curso?',
        html: '<p>Esta acción <strong>no se puede deshacer</strong>.</p>',
        showCancelButton: true,
        confirmButtonText: '<i class="bi bi-trash"></i> Sí, eliminar',
        cancelButtonText: '<i class="bi bi-x"></i> Cancelar',
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        reverseButtons: true,
        focusCancel: true
    });

    if (confirmacion.isConfirmed) {
        try {
            const body = new FormData();
            body.append('id_curso', id_curso);
            
            const respuesta = await fetch("/Escuela_BHR/API/cursos/eliminar", { 
                method: 'POST', 
                body 
            });
            
            const { codigo, mensaje, detalle } = await respuesta.json();

            if (codigo == 1) {
                Toast.fire({ 
                    icon: 'success', 
                    title: mensaje || 'Curso eliminado exitosamente' 
                });
                await buscar(); // Recargar datos
            } else {
                Toast.fire({ 
                    icon: 'error', 
                    title: mensaje || 'Error al eliminar el curso' 
                });
                console.error('Error del servidor:', detalle);
            }
        } catch (error) {
            console.error('Error en la petición:', error);
            Toast.fire({ 
                icon: 'error', 
                title: 'Error de conexión' 
            });
        }
    }
};

// Event listeners para las acciones de la tabla
datatable.on('click', '.modificar', traerDatos);
datatable.on('click', '.eliminar', eliminar);

// Animación suave para la tabla al cargar
window.addEventListener('load', () => {
    contenedorTabla.style.opacity = '0';
    contenedorTabla.style.transition = 'opacity 0.5s ease-in-out';
    setTimeout(() => {
        contenedorTabla.style.opacity = '1';
    }, 100);
});

// Manejar tecla Escape para cerrar formulario
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && contenedorFormulario.style.display === 'block') {
        ocultarFormulario();
    }
});