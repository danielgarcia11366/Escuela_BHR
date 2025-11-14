import { Dropdown } from "bootstrap";
import { Toast, validarFormulario } from "../funciones";
import Swal from "sweetalert2";
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

// ============================================
// REFERENCIAS A ELEMENTOS DEL DOM
// ============================================
const formulario = document.getElementById('formularioPromocion');
const tabla = document.getElementById('tablaPromociones');
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
const datatable = new DataTable('#tablaPromociones', {
    language: lenguaje,
    pageLength: 15,
    lengthMenu: [5, 15, 25, 100],
    columns: [
        {
            title: 'No.',
            data: 'pro_codigo',
            render: (data, type, row, meta) => meta.row + 1
        },
        {
            title: 'Promoción',
            data: 'numero_anio',
            width: '5%',

        },
        {
            title: 'Curso',
            data: 'curso_completo',
            width: '30%',
        },
        {
            title: 'Fecha Inicio',
            data: 'pro_fecha_inicio',
            render: data => {
                const fecha = new Date(data + 'T00:00:00');
                return fecha.toLocaleDateString('es-GT');
            }
        },
        {
            title: 'Fecha Fin',
            data: 'pro_fecha_fin',
            render: data => {
                const fecha = new Date(data + 'T00:00:00');
                return fecha.toLocaleDateString('es-GT');
            }
        },
        {
            title: 'Estado',
            data: 'pro_activa',
            render: data => data === 'S'
                ? '<span class="badge bg-success"><i class="bi bi-check-circle"></i> Activa</span>'
                : '<span class="badge bg-secondary"><i class="bi bi-x-circle"></i> Inactiva</span>'
        },
        {
            title: 'Acciones',
            data: 'pro_codigo',
            searchable: false,
            orderable: false,
            render: (data, type, row) => `
                <button class='btn btn-acciones btn-modificar modificar'
                    title='Modificar promoción'
                    data-pro_codigo="${row.pro_codigo}"
                    data-pro_curso="${row.pro_curso}"
                    data-pro_numero="${row.pro_numero}"
                    data-pro_anio="${row.pro_anio}"
                    data-pro_fecha_inicio="${row.pro_fecha_inicio}"
                    data-pro_fecha_fin="${row.pro_fecha_fin}"
                    data-pro_fecha_graduacion="${row.pro_fecha_graduacion || ''}"
                    data-pro_lugar="${row.pro_lugar || ''}"
                    data-pro_pais="${row.pro_pais || ''}"
                    data-pro_institucion_imparte="${row.pro_institucion_imparte || ''}"
                    data-pro_cantidad_graduados="${row.pro_cantidad_graduados}"
                    data-pro_observaciones="${row.pro_observaciones || ''}"
                    data-pro_activa="${row.pro_activa}">
                    <i class='bi bi-pencil-square'></i>
                </button>
                <button class='btn btn-acciones btn-eliminar eliminar'
                    title='Eliminar promoción'
                    data-pro_codigo="${data}">
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
    tituloFormulario.textContent = 'Nueva Promoción';
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
    btnFlotante.title = 'Nueva Promoción';
};

// ============================================
// EVENTO BOTÓN FLOTANTE
// ============================================
btnFlotante.addEventListener('click', () => {
    if (contenedorFormulario.style.display === 'none' || contenedorFormulario.style.display === '') {
        mostrarFormulario();
    } else {
        cancelar();
    }
});

// ============================================
// GUARDAR NUEVO REGISTRO
// ============================================
const guardar = async (e) => {
    e.preventDefault();

    if (!validarFormulario(formulario, ['pro_codigo'])) {
        Swal.fire({
            icon: 'info',
            title: 'Campos vacíos',
            text: 'Debe llenar todos los campos obligatorios'
        });
        return;
    }

    try {
        const body = new FormData(formulario);
        const url = "/Escuela_BHR/API/promociones/guardar";
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
        const url = "/Escuela_BHR/API/promociones/buscar";
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
    formulario.pro_codigo.value = d.pro_codigo;
    formulario.pro_curso.value = d.pro_curso;
    formulario.pro_numero.value = d.pro_numero;
    formulario.pro_anio.value = d.pro_anio;
    formulario.pro_fecha_inicio.value = d.pro_fecha_inicio;
    formulario.pro_fecha_fin.value = d.pro_fecha_fin;
    formulario.pro_fecha_graduacion.value = d.pro_fecha_graduacion || '';
    formulario.pro_lugar.value = d.pro_lugar || '';
    formulario.pro_pais.value = d.pro_pais || '';
    formulario.pro_institucion_imparte.value = d.pro_institucion_imparte || '';
    formulario.pro_cantidad_graduados.value = d.pro_cantidad_graduados;
    formulario.pro_observaciones.value = d.pro_observaciones || '';
    formulario.pro_activa.value = d.pro_activa;

    // Cambiar título y mostrar formulario
    tituloFormulario.textContent = 'Modificar Promoción';
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
        const url = "/Escuela_BHR/API/promociones/modificar";
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
        console.error('Error al modificar:', error);
        Toast.fire({ icon: 'error', title: 'Error al modificar el registro' });
    }
};

// ============================================
// ELIMINAR REGISTRO
// ============================================
const eliminar = async (e) => {
    const pro_codigo = e.currentTarget.dataset.pro_codigo;

    const confirmacion = await Swal.fire({
        icon: 'question',
        title: 'Confirmación',
        text: '¿Está seguro de eliminar esta promoción?',
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
            body.append('pro_codigo', pro_codigo);
            const url = "/Escuela_BHR/API/promociones/eliminar";
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
    ocultarFormulario();

    // Resetear después de que termine la animación
    setTimeout(() => {
        formulario.reset();
        btnGuardar.parentElement.style.display = '';
        btnModificar.parentElement.style.display = 'none';
        btnCancelar.parentElement.style.display = 'none';
    }, 350);
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