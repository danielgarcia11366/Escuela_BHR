import { Dropdown } from "bootstrap";
import { Toast, validarFormulario } from "../funciones";
import Swal from "sweetalert2";
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

// ============================================
// REFERENCIAS A ELEMENTOS DEL DOM
// ============================================
const formulario = document.getElementById('formularioPersonal');
const tabla = document.getElementById('tablaPersonal');
const btnGuardar = document.getElementById('btnGuardar');
const btnModificar = document.getElementById('btnModificar');
const btnCancelar = document.getElementById('btnCancelar');
const btnFlotante = document.getElementById('btnFlotante');
const contenedorFormulario = document.getElementById('contenedorFormulario');
const contenedorTabla = document.getElementById('contenedorTabla');
const tituloFormulario = document.getElementById('tituloFormulario');

// ============================================
// CONFIGURACIÓN DE DATATABLE
// ============================================
const datatable = new DataTable('#tablaPersonal', {
    language: lenguaje,
    pageLength: 15,
    lengthMenu: [5, 15, 25, 100],
    columns: [
        {
            title: 'No.',
            data: 'per_catalogo',
            render: (data, type, row, meta) => meta.row + 1
        },
        {
            title: 'Grado y Arma',
            data: 'grado_arma'
        },
        {
            title: 'Nombres',
            data: 'nombre_completo'
        },
        {
            title: 'Catálogo',
            data: 'per_catalogo'
        },
        {
            title: 'Tipo',
            data: 'per_tipo',
            render: data => {
                const tipos = {
                    'A': '<span class="badge bg-primary"><i class="bi bi-person"></i> Alumno</span>',
                    'I': '<span class="badge bg-success"><i class="bi bi-person-badge"></i> Instructor</span>',
                    'J': '<span class="badge bg-warning text-dark"><i class="bi bi-star-fill"></i> Jefe</span>',
                    'O': '<span class="badge bg-secondary"><i class="bi bi-people"></i> Otro</span>'
                };
                return tipos[data] || `<span class="badge bg-light text-dark">${data}</span>`;
            }
        },
        {
            title: 'Sexo',
            data: 'per_sexo',
            render: data => data === 'M' ? 'Masculino' : 'Femenino'
        },
        {
            title: 'Teléfono',
            data: 'per_telefono'
        },
        {
            title: 'Correo',
            data: 'per_email'
        },
        {
            title: 'Acciones',
            data: 'per_catalogo',
            searchable: false,
            orderable: false,
            render: (data, type, row) => `
                <button class='btn btn-acciones btn-modificar modificar'
                    title='Modificar persona'
                    data-per_catalogo="${row.per_catalogo}"
                    data-per_serie="${row.per_serie || ''}"
                    data-per_nom1="${row.per_nom1}"
                    data-per_nom2="${row.per_nom2 || ''}"
                    data-per_ape1="${row.per_ape1}"
                    data-per_ape2="${row.per_ape2 || ''}"
                    data-per_grado="${row.per_grado}"
                    data-per_arma="${row.per_arma}"
                    data-per_telefono="${row.per_telefono || ''}"
                    data-per_email="${row.per_email || ''}"
                    data-per_direccion="${row.per_direccion || ''}"
                    data-per_sexo="${row.per_sexo}"
                    data-per_fec_nac="${row.per_fec_nac}"
                    data-per_nac_lugar="${row.per_nac_lugar || ''}"
                    data-per_dpi="${row.per_dpi || ''}"
                    data-per_tipo_doc="${row.per_tipo_doc || 'DPI'}"
                    data-per_estado="${row.per_estado}"
                    data-per_tipo="${row.per_tipo}"
                    data-observaciones="${row.observaciones || ''}">
                    <i class='bi bi-pencil-square'></i>
                </button>
                <button class='btn btn-acciones btn-eliminar eliminar'
                    title='Eliminar persona'
                    data-per_catalogo="${data}">
                    <i class='bi bi-trash'></i>
                </button>
            `
        }
    ]
});

// ============================================
// ESTADO INICIAL
// ============================================
btnModificar.parentElement.style.display = 'none';
btnCancelar.parentElement.style.display = 'none';

// ============================================
// FUNCIONES DE FORMULARIO
// ============================================

// Mostrar formulario con animación
const mostrarFormulario = () => {
    contenedorFormulario.style.display = '';
    contenedorFormulario.classList.add('slide-down');
    contenedorTabla.style.display = 'none';
    tituloFormulario.textContent = 'Nueva Persona';
    formulario.reset();

    btnGuardar.parentElement.style.display = '';
    btnModificar.parentElement.style.display = 'none';
    btnCancelar.parentElement.style.display = '';

    // Cambiar ícono y añadir clase activa
    btnFlotante.innerHTML = '<i class="bi bi-x-lg"></i>';
    btnFlotante.classList.add('activo');
    btnFlotante.title = 'Cerrar formulario';
};

// Ocultar formulario con animación
const ocultarFormulario = () => {
    contenedorFormulario.classList.remove('slide-down');
    contenedorFormulario.classList.add('slide-up');

    setTimeout(() => {
        contenedorFormulario.style.display = 'none';
        contenedorFormulario.classList.remove('slide-up');
        contenedorTabla.style.display = '';
    }, 300);

    // Restaurar ícono y quitar clase activa
    btnFlotante.innerHTML = '<i class="bi bi-plus"></i>';
    btnFlotante.classList.remove('activo');
    btnFlotante.title = 'Nueva Persona';
};

// ============================================
// EVENTO BOTÓN FLOTANTE
// ============================================
btnFlotante.addEventListener('click', () => {
    if (contenedorFormulario.style.display === 'none' || contenedorFormulario.style.display === '') {
        mostrarFormulario();
    } else {
        ocultarFormulario();
    }
});

// ============================================
// GUARDAR NUEVO REGISTRO
// ============================================
const guardar = async (e) => {
    e.preventDefault();

    if (!validarFormulario(formulario, ['per_catalogo'])) {
        Swal.fire({
            icon: 'info',
            title: 'Campos vacíos',
            text: 'Debe llenar todos los campos obligatorios'
        });
        return;
    }

    try {
        const body = new FormData(formulario);
        const url = "/Escuela_BHR/API/personal/guardar";
        const respuesta = await fetch(url, { method: 'POST', body });
        const data = await respuesta.json();

        const { codigo, mensaje } = data;
        let icon = codigo == 1 ? 'success' : 'error';

        if (codigo == 1) {
            formulario.reset();
            buscar();
            ocultarFormulario();
        }

        Toast.fire({ icon, title: mensaje });
    } catch (error) {
        console.error('Error al guardar:', error);
        Toast.fire({ icon: 'error', title: 'Error al guardar el registro' });
    }
};

// ============================================
// BUSCAR REGISTROS
// ============================================
const buscar = async () => {
    try {
        const url = "/Escuela_BHR/API/personal/buscar";
        const respuesta = await fetch(url);
        const data = await respuesta.json();
        const { datos } = data;

        datatable.clear().draw();
        if (datos && datos.length > 0) {
            datatable.rows.add(datos).draw();
        }
    } catch (error) {
        console.error('Error al buscar registros:', error);
        Toast.fire({ icon: 'error', title: 'Error al cargar los datos' });
    }
};

// ============================================
// CARGAR DATOS PARA MODIFICAR
// ============================================
const traerDatos = (e) => {
    const d = e.currentTarget.dataset;

    // Cargar todos los campos del formulario
    formulario.per_catalogo.value = d.per_catalogo;
    formulario.per_serie.value = d.per_serie || '';
    formulario.per_nom1.value = d.per_nom1;
    formulario.per_nom2.value = d.per_nom2 || '';
    formulario.per_ape1.value = d.per_ape1;
    formulario.per_ape2.value = d.per_ape2 || '';
    formulario.per_grado.value = d.per_grado;
    formulario.per_arma.value = d.per_arma;
    formulario.per_telefono.value = d.per_telefono || '';
    formulario.per_email.value = d.per_email || '';
    formulario.per_direccion.value = d.per_direccion || '';
    formulario.per_sexo.value = d.per_sexo;
    formulario.per_fec_nac.value = d.per_fec_nac;
    formulario.per_nac_lugar.value = d.per_nac_lugar || '';
    formulario.per_dpi.value = d.per_dpi || '';
    formulario.per_tipo_doc.value = d.per_tipo_doc || 'DPI';
    formulario.per_estado.value = d.per_estado;
    formulario.per_tipo.value = d.per_tipo;
    formulario.observaciones.value = d.observaciones || '';

    // Cambiar título y mostrar formulario
    tituloFormulario.textContent = 'Modificar Persona';
    contenedorFormulario.style.display = '';
    contenedorFormulario.classList.add('slide-down');
    contenedorTabla.style.display = 'none';

    // Mostrar botón modificar, ocultar guardar
    btnGuardar.parentElement.style.display = 'none';
    btnModificar.parentElement.style.display = '';
    btnCancelar.parentElement.style.display = '';

    // Cambiar ícono del botón flotante
    btnFlotante.innerHTML = '<i class="bi bi-x-lg"></i>';
    btnFlotante.classList.add('activo');
    btnFlotante.title = 'Cerrar formulario';

    // Deshabilitar campo catálogo en modo edición
    formulario.per_catalogo.setAttribute('readonly', true);
};

// ============================================
// MODIFICAR REGISTRO EXISTENTE
// ============================================
const modificar = async (e) => {
    e.preventDefault();

    if (!validarFormulario(formulario)) {
        Swal.fire({
            icon: 'info',
            title: 'Campos vacíos',
            text: 'Debe llenar todos los campos obligatorios'
        });
        return;
    }

    try {
        const body = new FormData(formulario);
        const url = "/Escuela_BHR/API/personal/modificar";
        const respuesta = await fetch(url, { method: 'POST', body });
        const data = await respuesta.json();

        const { codigo, mensaje } = data;
        let icon = codigo == 1 ? 'success' : 'error';

        if (codigo == 1) {
            formulario.reset();
            formulario.per_catalogo.removeAttribute('readonly');
            buscar();
            ocultarFormulario();
        }

        Toast.fire({ icon, title: mensaje });
    } catch (error) {
        console.error('Error al modificar:', error);
        Toast.fire({ icon: 'error', title: 'Error al modificar el registro' });
    }
};

// ============================================
// ELIMINAR REGISTRO
// ============================================
const eliminar = async (e) => {
    const per_catalogo = e.currentTarget.dataset.per_catalogo;

    const confirmacion = await Swal.fire({
        icon: 'question',
        title: 'Confirmación',
        text: '¿Está seguro de eliminar este registro?',
        showCancelButton: true,
        confirmButtonText: '<i class="bi bi-check-circle"></i> Sí, eliminar',
        cancelButtonText: '<i class="bi bi-x-circle"></i> No, cancelar',
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        reverseButtons: true
    });

    if (confirmacion.isConfirmed) {
        try {
            const body = new FormData();
            body.append('per_catalogo', per_catalogo);
            const url = "/Escuela_BHR/API/personal/eliminar";
            const respuesta = await fetch(url, { method: 'POST', body });
            const data = await respuesta.json();

            const { codigo, mensaje } = data;
            let icon = codigo == 1 ? 'success' : 'error';

            if (codigo == 1) {
                buscar();
            }

            Toast.fire({ icon, title: mensaje });
        } catch (error) {
            console.error('Error al eliminar:', error);
            Toast.fire({ icon: 'error', title: 'Error al eliminar el registro' });
        }
    }
};

// ============================================
// CANCELAR OPERACIÓN
// ============================================
const cancelar = () => {
    formulario.reset();
    formulario.per_catalogo.removeAttribute('readonly');
    ocultarFormulario();

    btnGuardar.parentElement.style.display = '';
    btnModificar.parentElement.style.display = 'none';
    btnCancelar.parentElement.style.display = 'none';
};

// ============================================
// ASIGNACIÓN DE EVENTOS
// ============================================
formulario.addEventListener('submit', guardar);
btnModificar.addEventListener('click', modificar);
btnCancelar.addEventListener('click', cancelar);

// Eventos de la tabla (delegación de eventos)
datatable.on('click', '.modificar', traerDatos);
datatable.on('click', '.eliminar', eliminar);

// ============================================
// INICIALIZACIÓN
// ============================================
buscar();