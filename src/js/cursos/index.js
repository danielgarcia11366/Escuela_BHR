import { Dropdown } from "bootstrap";
import { Toast, validarFormulario } from "../funciones";
import Swal from "sweetalert2";
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

const formulario = document.getElementById('formularioCursos');
const tabla = document.getElementById('tablaCursos');
const btnGuardar = document.getElementById('btnGuardar');
const btnModificar = document.getElementById('btnModificar');
const btnCancelar = document.getElementById('btnCancelar');
const btnFlotante = document.getElementById('btnFlotante');
const contenedorFormulario = document.getElementById('contenedorFormulario');
const contenedorTabla = document.getElementById('contenedorTabla');
const tituloFormulario = document.getElementById('tituloFormulario');

let contador = 1;

const datatable = new DataTable('#tablaCursos', {
    language: lenguaje,
    pageLength: '15',
    lengthMenu: [3, 9, 11, 25, 100],
    columns: [
        {
            title: 'No.',
            data: 'cur_codigo',
            width: '2%',
            render: (data, type, row, meta) => {
                return meta.row + 1;
            }
        },
        {
            title: 'Curso',
            data: 'curso_completo',
            width: '30%',
        },
        {
            title: 'Nombre Corto',
            data: 'cur_nombre_corto'
        },
        {
            title: 'Duración (días)',
            data: 'cur_duracion_dias',
            width: '8%',
        },
        {
            title: 'Tipo',
            data: 'tipo_nombre'
        },
        {
            title: 'Certificación',
            data: 'cur_certificado',
            width: '8%',
        },
        {
            title: 'Institución',
            data: 'institucion_nombre',
            width: '20%'
        },
        {
            title: 'Acciones',
            width: '13%',
            data: 'cur_codigo',
            searchable: false,
            orderable: false,
            render: (data, type, row) => {
                return `
        <button class='btn btn-acciones btn-modificar modificar' 
            title='Modificar curso'
            data-cur_codigo="${data}" 
            data-cur_nombre="${row.cur_nombre}"
            data-cur_nombre_corto="${row.cur_nombre_corto}"
            data-cur_duracion_dias="${row.cur_duracion_dias}"
            data-cur_nivel="${row.cur_nivel}"
            data-cur_tipo="${row.cur_tipo}"
            data-cur_certificado="${row.cur_certificado}"
            data-cur_institucion_certifica="${row.cur_institucion_certifica}"
            data-cur_descripcion="${row.cur_descripcion}">
            <i class='bi bi-pencil-square'></i> 
        </button>
        <button class='btn btn-acciones btn-eliminar eliminar' 
            title='Eliminar curso'
            data-cur_codigo="${data}">
            <i class='bi bi-trash'></i> 
        </button>
    `;
            }
        }

    ]
});

// Ocultar botones de modificar y cancelar al inicio
btnModificar.parentElement.style.display = 'none';
btnModificar.disabled = true;
btnCancelar.parentElement.style.display = 'none';
btnCancelar.disabled = true;

// Mostrar/ocultar formulario con el botón flotante
btnFlotante.addEventListener('click', () => {
    if (contenedorFormulario.style.display === 'none') {
        mostrarFormulario();
    } else {
        ocultarFormulario();
    }
});

const mostrarFormulario = () => {
    contenedorFormulario.style.display = '';
    contenedorFormulario.classList.add('slide-down');
    contenedorTabla.style.display = 'none';
    tituloFormulario.textContent = 'Nuevo Curso';
    formulario.reset();

    // ⭐ ESTABLECER "NO" por defecto y restablecer select a "Seleccione..."
    radioCertificadoNo.checked = true;
    selectInstitucion.value = '#'; // Restablecer a "Seleccione..."
    manejarCambioCertificado(); // Esto ocultará la institución y pondrá "SIN INSTITUCION"

    btnGuardar.parentElement.style.display = '';
    btnGuardar.disabled = false;
    btnModificar.parentElement.style.display = 'none';
    btnModificar.disabled = true;

    btnFlotante.classList.add('activo');
    btnFlotante.innerHTML = '<i class="bi bi-skip-backward"></i>';
    btnFlotante.setAttribute('title', 'Volver a la tabla');
};


const ocultarFormulario = () => {
    contenedorFormulario.classList.remove('slide-down');
    contenedorFormulario.classList.add('slide-up');
    setTimeout(() => {
        contenedorFormulario.style.display = 'none';
        contenedorFormulario.classList.remove('slide-up');
        contenedorTabla.style.display = '';
    }, 300);

    // Restaurar el botón flotante a modo "agregar"
    btnFlotante.classList.remove('activo');
    btnFlotante.innerHTML = '<i class="bi bi-plus"></i>';
    btnFlotante.setAttribute('title', 'Nuevo Curso');
};

const guardar = async (e) => {
    e.preventDefault();
    btnGuardar.disabled = true;

    if (!validarFormulario(formulario, ['cur_codigo'])) {
        Swal.fire({
            title: "Campos vacíos",
            text: "Debe llenar todos los campos obligatorios",
            icon: "info"
        });
        btnGuardar.disabled = false;
        return;
    }

    try {
        const body = new FormData(formulario);
        const url = "/Escuela_BHR/API/cursos/guardar";
        const config = {
            method: 'POST',
            body
        };

        const respuesta = await fetch(url, config);
        const data = await respuesta.json();
        const { codigo, mensaje, detalle } = data;

        let icon = 'info';
        if (codigo == 1) {
            icon = 'success';
            formulario.reset();
            buscar();
            ocultarFormulario();
        } else {
            icon = 'error';
            console.log(detalle);
        }

        Toast.fire({
            icon: icon,
            title: mensaje
        });

    } catch (error) {
        console.log(error);
        Toast.fire({
            icon: 'error',
            title: 'Error al guardar el curso'
        });
    }
    btnGuardar.disabled = false;
};

const buscar = async () => {
    try {
        const url = "/Escuela_BHR/API/cursos/buscar";
        const config = {
            method: 'GET'
        };

        console.log('Buscando cursos...');
        const respuesta = await fetch(url, config);
        console.log('Respuesta recibida:', respuesta);

        const data = await respuesta.json();
        console.log('Datos recibidos:', data);

        const { datos } = data;
        console.log('Array de cursos:', datos);

        datatable.clear().draw();

        if (datos && datos.length > 0) {
            console.log('Agregando ' + datos.length + ' cursos a la tabla');
            datatable.rows.add(datos).draw();
        } else {
            console.log('No hay datos para mostrar');
        }
    } catch (error) {
        console.error('Error al buscar cursos:', error);
    }
};

const traerDatos = (e) => {
    const elemento = e.currentTarget.dataset;

    formulario.cur_codigo.value = elemento.cur_codigo;
    formulario.cur_nombre.value = elemento.cur_nombre;
    formulario.cur_nombre_corto.value = elemento.cur_nombre_corto;
    formulario.cur_duracion_dias.value = elemento.cur_duracion_dias;
    formulario.cur_nivel.value = elemento.cur_nivel;
    formulario.cur_tipo.value = elemento.cur_tipo;
    formulario.cur_descripcion.value = elemento.cur_descripcion;

    // ⭐ MARCAR EL RADIO BUTTON CORRECTO
    if (elemento.cur_certificado === 'SI') {
        radioCertificadoSi.checked = true;
    } else {
        radioCertificadoNo.checked = true;
    }

    // ⭐ ESTABLECER LA INSTITUCIÓN ANTES de ejecutar la lógica
    formulario.cur_institucion_certifica.value = elemento.cur_institucion_certifica;

    // ⭐ EJECUTAR LA LÓGICA DE MOSTRAR/OCULTAR INSTITUCIÓN
    manejarCambioCertificado();

    // Mostrar formulario y cambiar título
    contenedorFormulario.style.display = '';
    contenedorFormulario.classList.add('slide-down');
    contenedorTabla.style.display = 'none';
    tituloFormulario.textContent = 'Modificar Curso';

    // Cambiar botones
    btnGuardar.parentElement.style.display = 'none';
    btnGuardar.disabled = true;
    btnModificar.parentElement.style.display = '';
    btnModificar.disabled = false;
    btnCancelar.parentElement.style.display = '';
    btnCancelar.disabled = false;

    // Cambiar el botón flotante a modo "cerrar"
    btnFlotante.classList.add('activo');
    btnFlotante.innerHTML = '<i class="bi bi-x"></i>';
    btnFlotante.setAttribute('title', 'Cerrar formulario');
};

const cancelar = () => {
    ocultarFormulario();
    formulario.reset();
    btnGuardar.parentElement.style.display = '';
    btnGuardar.disabled = false;
    btnModificar.parentElement.style.display = 'none';
    btnModificar.disabled = true;
    btnCancelar.parentElement.style.display = 'none';
    btnCancelar.disabled = true;
};

const modificar = async (e) => {
    e.preventDefault();

    if (!validarFormulario(formulario)) {
        Swal.fire({
            title: "Campos vacíos",
            text: "Debe llenar todos los campos",
            icon: "info"
        });
        return;
    }

    try {
        const body = new FormData(formulario);
        const url = "/Escuela_BHR/API/cursos/modificar";
        const config = {
            method: 'POST',
            body
        };

        const respuesta = await fetch(url, config);
        const data = await respuesta.json();
        const { codigo, mensaje, detalle } = data;

        let icon = 'info';
        if (codigo == 1) {
            icon = 'success';
            formulario.reset();
            buscar();
            cancelar();
        } else {
            icon = 'error';
            console.log(detalle);
        }

        Toast.fire({
            icon: icon,
            title: mensaje
        });

    } catch (error) {
        console.log(error);
    }
};

const eliminar = async (e) => {
    const cur_codigo = e.currentTarget.dataset.cur_codigo;

    let confirmacion = await Swal.fire({
        icon: 'question',
        title: 'Confirmación',
        text: '¿Está seguro que desea eliminar este registro?',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'No, cancelar',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33'
    });

    if (confirmacion.isConfirmed) {
        try {
            const body = new FormData();
            body.append('cur_codigo', cur_codigo);
            const url = "/Escuela_BHR/API/cursos/eliminar";
            const config = {
                method: 'POST',
                body
            };

            const respuesta = await fetch(url, config);
            const data = await respuesta.json();
            const { codigo, mensaje, detalle } = data;

            let icon = 'info';
            if (codigo == 1) {
                icon = 'success';
                formulario.reset();
                buscar();
            } else {
                icon = 'error';
                console.log(detalle);
            }

            Toast.fire({
                icon: icon,
                title: mensaje
            });
        } catch (error) {
            console.log(error);
        }
    }
};

// Event listeners
formulario.addEventListener('submit', guardar);
btnCancelar.addEventListener('click', cancelar);
btnModificar.addEventListener('click', modificar);
datatable.on('click', '.modificar', traerDatos);
datatable.on('click', '.eliminar', eliminar);


// Al final del archivo, antes de buscar()

// Referencias a los elementos
const radioCertificadoSi = document.getElementById('certificado_si');
const radioCertificadoNo = document.getElementById('certificado_no');
const contenedorInstitucion = document.getElementById('contenedorInstitucion');
const selectInstitucion = document.getElementById('cur_institucion_certifica');

// Función para manejar el cambio de certificado
const manejarCambioCertificado = () => {
    if (radioCertificadoSi.checked) {
        // Mostrar el select de institución
        contenedorInstitucion.style.display = '';
        selectInstitucion.required = true;
    } else {
        // Ocultar el select
        contenedorInstitucion.style.display = 'none';
        selectInstitucion.required = false;

        // SIEMPRE establecer "SIN INSTITUCION" cuando se selecciona NO
        // Ya sea nuevo o editando
        const opciones = Array.from(selectInstitucion.options);
        const sinInstitucion = opciones.find(opt => opt.text === 'SIN INSTITUCION');

        if (sinInstitucion) {
            selectInstitucion.value = sinInstitucion.value;
        }
    }
};

// Escuchar cambios en los radio buttons
radioCertificadoSi.addEventListener('change', manejarCambioCertificado);
radioCertificadoNo.addEventListener('change', manejarCambioCertificado);

// Ejecutar al cargar para establecer el estado inicial
manejarCambioCertificado();

// Cargar datos al inicio
buscar();