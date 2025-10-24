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
            data: 'cur_nombre'
        },
        {
            title: 'Nombre Corto',
            data: 'cur_nombre_corto'
        },
        {
            title: 'Duración (días)',
            data: 'cur_duracion_dias'
        },
        {
            title: 'Nivel',
            data: 'cur_nivel'
        },
        {
            title: 'Tipo',
            data: 'cur_tipo'
        },
        {
            title: 'Certificación',
            data: 'cur_certificado'
        },
        {
            title: 'Institución',
            data: 'cur_institucion_certifica'
        },
        {
            title: 'Estado',
            data: 'cur_activo',
            render: (data) => {
                return data === 'S' ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-danger">Inactivo</span>';
            }
        },
        {
            title: 'Acciones',
            data: 'cur_codigo',
            searchable: false,
            orderable: false,
            render: (data, type, row) => {
                return `
                    <button class='btn btn-warning btn-sm modificar' 
                        data-cur_codigo="${data}" 
                        data-cur_nombre="${row.cur_nombre}"
                        data-cur_nombre_corto="${row.cur_nombre_corto}"
                        data-cur_duracion_dias="${row.cur_duracion_dias}"
                        data-cur_nivel="${row.cur_nivel}"
                        data-cur_tipo="${row.cur_tipo}"
                        data-cur_certificado="${row.cur_certificado}"
                        data-cur_institucion_certifica="${row.cur_institucion_certifica}"
                        data-cur_descripcion="${row.cur_descripcion}"
                        data-cur_activo="${row.cur_activo}">
                        <i class='bi bi-pencil-square'></i> 
                    </button>
                    <button class='btn btn-danger btn-sm eliminar' data-cur_codigo="${data}">
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
    btnGuardar.parentElement.style.display = '';
    btnGuardar.disabled = false;
    btnModificar.parentElement.style.display = 'none';
    btnModificar.disabled = true;
};

const ocultarFormulario = () => {
    contenedorFormulario.classList.remove('slide-down');
    contenedorFormulario.classList.add('slide-up');
    setTimeout(() => {
        contenedorFormulario.style.display = 'none';
        contenedorFormulario.classList.remove('slide-up');
        contenedorTabla.style.display = '';
    }, 300);
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
    formulario.cur_certificado.value = elemento.cur_certificado;
    formulario.cur_institucion_certifica.value = elemento.cur_institucion_certifica;
    formulario.cur_descripcion.value = elemento.cur_descripcion;
    formulario.cur_activo.value = elemento.cur_activo;

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

// Cargar datos al inicio
buscar();