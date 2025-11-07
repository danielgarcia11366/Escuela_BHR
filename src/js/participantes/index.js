import { Dropdown } from "bootstrap";
import { Toast, validarFormulario } from "../funciones";
import Swal from "sweetalert2";
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

// Referencias DOM
const formulario = document.getElementById('formularioParticipantes');
const tabla = document.getElementById('tablaParticipantes');
const btnGuardar = document.getElementById('btnGuardar');
const btnModificar = document.getElementById('btnModificar');
const btnCancelar = document.getElementById('btnCancelar');
const btnFlotante = document.getElementById('btnFlotante');
const contenedorFormulario = document.getElementById('contenedorFormulario');
const contenedorTabla = document.getElementById('contenedorTabla');
const tituloFormulario = document.getElementById('tituloFormulario');

const datatable = new DataTable('#tablaParticipantes', {
    language: lenguaje,
    pageLength: 15,
    lengthMenu: [5, 10, 25, 50, 100],
    columns: [
        {
            title: 'No.',
            data: 'par_codigo',
            width: '3%',
            render: (data, type, row, meta) => meta.row + 1
        },
        {
            title: 'Información del Alumno',
            data: 'participante_nombre',
            width: '30%',

        },
        {
            title: 'Promoción y Curso',
            data: 'promocion_info',

        },
        {
            title: 'Calificación',
            data: 'par_calificacion'
        },
        {
            title: 'Estado',
            data: 'par_estado',
            render: (data) => {
                const estados = {
                    'C': 'Cursando',
                    'G': 'Graduado',
                    'R': 'Retirado',
                    'D': 'Desertor'
                };
                return estados[data] || data;
            }
        },
        {
            title: 'Certificado',
            data: 'par_certificado_numero'
        },
        {
            title: 'Acciones',
            data: 'par_codigo',
            orderable: false,
            searchable: false,
            render: (data, type, row) => `
                <button class="btn btn-outline-warning modificar"
                    data-par_codigo="${data}"
                    data-par_promocion="${row.par_promocion}"
                    data-par_catalogo="${row.par_catalogo}"
                    data-par_calificacion="${row.par_calificacion}"
                    data-par_posicion="${row.par_posicion}"
                    data-par_estado="${row.par_estado}"
                    data-par_certificado_numero="${row.par_certificado_numero}"
                    data-par_certificado_fecha="${row.par_certificado_fecha}"
                    data-par_observaciones="${row.par_observaciones || ''}">
                    <i class="bi bi-pencil-square"></i>
                </button>
                <button class="btn btn-outline-danger eliminar"
                    data-par_codigo="${data}">
                    <i class="bi bi-trash"></i>
                </button>
            `
        }
    ]
});

// Estado inicial
btnModificar.parentElement.style.display = 'none';
btnCancelar.parentElement.style.display = 'none';

// Mostrar/ocultar formulario
btnFlotante.addEventListener('click', () => {
    if (contenedorFormulario.style.display === 'none') mostrarFormulario();
    else ocultarFormulario();
});

const mostrarFormulario = () => {
    contenedorFormulario.style.display = '';
    contenedorFormulario.classList.add('slide-down');
    contenedorTabla.style.display = 'none';
    tituloFormulario.textContent = 'Nuevo Participante';
    formulario.reset();

    btnGuardar.parentElement.style.display = '';
    btnModificar.parentElement.style.display = 'none';
    btnCancelar.parentElement.style.display = 'none';

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
    btnFlotante.innerHTML = '<i class="bi bi-plus"></i>';
    btnFlotante.setAttribute('title', 'Nuevo Participante');
};

// GUARDAR
const guardar = async (e) => {
    e.preventDefault();
    btnGuardar.disabled = true;

    // Validación de campos obligatorios
    if (!validarFormulario(formulario, ['par_codigo'])) {
        Swal.fire({
            icon: "info",
            title: "Campos vacíos",
            text: "Debe llenar todos los campos obligatorios"
        });
        btnGuardar.disabled = false;
        return;
    }

    try {
        const body = new FormData(formulario);
        const url = "/Escuela_BHR/API/participantes/guardar";

        console.log("=== DATOS A ENVIAR ===");
        for (const [key, value] of body.entries()) {
            console.log(`${key}: ${value}`);
        }

        const resp = await fetch(url, { method: 'POST', body });

        console.log("=== RESPUESTA CRUDA ===");
        console.log(resp);

        const data = await resp.json();
        console.log("=== RESPUESTA DEL SERVIDOR ===");
        console.log(data);

        const { codigo, mensaje, campo } = data;

        if (codigo === 1) {
            // Éxito al guardar
            Swal.fire({
                position: "top-end",
                icon: "success",
                title: "Éxito",
                text: mensaje,
                timer: 2000,
                showConfirmButton: false
            });

            formulario.reset();
            buscar();
            ocultarFormulario();

        } else if (codigo === 0 && campo === "par_certificado_numero") {
            // Certificado duplicado
            Swal.fire({
                icon: "warning",
                title: "Número de certificado duplicado",
                text: mensaje
            });

            const campoDuplicado = document.getElementById(campo);
            if (campoDuplicado) campoDuplicado.focus();

        } else {
            // Otro tipo de error
            Toast.fire({
                icon: "error",
                title: mensaje || "Ocurrió un error al guardar el participante"
            });
        }

    } catch (err) {
        console.error("Error en guardar():", err);
        Toast.fire({
            icon: "error",
            title: "Error al guardar el participante (catch)"
        });
    }

    btnGuardar.disabled = false;
};




// BUSCAR
const buscar = async () => {
    try {
        const url = "/Escuela_BHR/API/participantes/buscar";
        const resp = await fetch(url);
        const data = await resp.json();
        const { datos } = data;

        datatable.clear().draw();
        if (datos && datos.length > 0) datatable.rows.add(datos).draw();
    } catch (error) {
        console.error("Error al buscar participantes:", error);
    }
};

// MODIFICAR
const traerDatos = (e) => {
    const d = e.currentTarget.dataset;

    formulario.par_codigo.value = d.par_codigo;
    formulario.par_promocion.value = d.par_promocion;
    formulario.par_catalogo.value = d.par_catalogo;
    formulario.par_calificacion.value = d.par_calificacion;
    formulario.par_posicion.value = d.par_posicion;
    formulario.par_estado.value = d.par_estado;
    formulario.par_certificado_numero.value = d.par_certificado_numero;
    formulario.par_certificado_fecha.value = d.par_certificado_fecha;
    formulario.par_observaciones.value = d.par_observaciones;

    contenedorFormulario.style.display = '';
    contenedorTabla.style.display = 'none';
    tituloFormulario.textContent = 'Modificar Participante';

    btnGuardar.parentElement.style.display = 'none';
    btnModificar.parentElement.style.display = '';
    btnCancelar.parentElement.style.display = '';

    btnFlotante.innerHTML = '<i class="bi bi-x"></i>';
    btnFlotante.setAttribute('title', 'Cerrar formulario');
};

const modificar = async (e) => {
    e.preventDefault();

    if (!validarFormulario(formulario)) {
        Swal.fire("Campos vacíos", "Debe llenar todos los campos", "info");
        return;
    }

    try {
        const body = new FormData(formulario);
        const url = "/Escuela_BHR/API/participantes/modificar";
        const resp = await fetch(url, { method: 'POST', body });
        const data = await resp.json();
        const { codigo, mensaje } = data;

        Toast.fire({ icon: codigo == 1 ? 'success' : 'error', title: mensaje });
        if (codigo == 1) {
            formulario.reset();
            buscar();
            cancelar();
        }
    } catch (err) {
        console.error(err);
    }
};

// ELIMINAR
const eliminar = async (e) => {
    const par_codigo = e.currentTarget.dataset.par_codigo;

    const confirmacion = await Swal.fire({
        icon: 'question',
        title: 'Confirmación',
        text: '¿Desea eliminar este participante?',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'No, cancelar',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33'
    });

    if (!confirmacion.isConfirmed) return;

    try {
        const body = new FormData();
        body.append('par_codigo', par_codigo);

        const url = "/Escuela_BHR/API/participantes/eliminar";
        const resp = await fetch(url, { method: 'POST', body });
        const data = await resp.json();
        const { codigo, mensaje } = data;

        Toast.fire({ icon: codigo == 1 ? 'success' : 'error', title: mensaje });
        if (codigo == 1) buscar();
    } catch (err) {
        console.error(err);
    }
};

// CANCELAR
const cancelar = () => {
    ocultarFormulario();
    formulario.reset();
    btnGuardar.parentElement.style.display = '';
    btnModificar.parentElement.style.display = 'none';
    btnCancelar.parentElement.style.display = 'none';
};

// EVENTOS
formulario.addEventListener('submit', guardar);
btnCancelar.addEventListener('click', cancelar);
btnModificar.addEventListener('click', modificar);
datatable.on('click', '.modificar', traerDatos);
datatable.on('click', '.eliminar', eliminar);

// Cargar al inicio
buscar();