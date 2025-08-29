import { Dropdown } from "bootstrap";
import { Toast, validarFormulario } from "../funciones";
import Swal from "sweetalert2";
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

const formulario = document.getElementById('formularioCursos');
const tabla = document.getElementById('tablaCursos')
const btnGuardar = document.getElementById('btnGuardar')
const btnModificar = document.getElementById('btnModificar')
const btnCancelar = document.getElementById('btnCancelar')

let contador = 1;
btnModificar.disabled = true;
btnModificar.parentElement.style.display = 'none';
btnCancelar.disabled = true;



const datatable = new DataTable('#tablaCursos', {
    data: null,
    language: lenguaje,
    pageLength: '15',
    lengthMenu: [3, 9, 11, 25, 100],
    columns: [
        {
            title: 'No.',
            data: 'cur_codigo',
            width: '2%',
            render: (data, type, row, meta) => {
                // console.log(meta.ro);
                return meta.row + 1;
            }
        },
        {
            title: 'Nombre del Curso',
            data: 'cur_nombre'
        },
        {
            title: 'Descripción del Curso',
            data: 'cur_desc_lg'
        }, {
            title: 'Duración del Curso',
            data: 'cur_duracion'
        },
        {
            title: 'Fecha de Creación',
            data: 'cur_fec_creacion'
        },
        {
            title: 'Acciones',
            data: 'cur_codigo',
            searchable: false,
            orderable: false,
            render: (data, type, row, meta) => {
                let html = `
                <button class='btn btn-warning modificar' data-cur_codigo="${data}" data-cur_nombre="${row.cur_nombre}" data-cur_desc_lg="${row.cur_desc_lg}" data-cur_duracion="${row.cur_duracion}" data-cur_fec_creacion="${row.cur_fec_creacion}">
                    <i class='bi bi-pencil-square'></i> 
                </button>
                <button class='btn btn-danger eliminar' data-cur_codigo="${data}">
                  <i class='bi bi-trash'></i> 
                </button>
            `;
            
                return html;
            }
        },

    ]
}
);


btnModificar.parentElement.style.display = 'none'
btnModificar.disabled = true
btnCancelar.parentElement.style.display = 'none'
btnCancelar.disabled = true



const guardar = async (e) => {
    btnGuardar.disabled = true,
    e.preventDefault()

    if (!validarFormulario(formulario, ['cur_codigo'])) {
        Swal.fire({
            title: "Campos vacios",
            text: "Debe llenar todos los campos",
            icon: "info"
        })
        btnGuardar.disabled = false
        return
    }

    try {
        const body = new FormData(formulario)
        const url = "/Escuela_BHR/API/cursos/guardar"
        const config = {
            method: 'POST',
            body
        }

        const respuesta = await fetch(url, config);
        const data = await respuesta.json();
        const { codigo, mensaje, detalle } = data;
        let icon = 'info'
        if (codigo == 1) {
            icon = 'success'
            formulario.reset();
            buscar();
            btnGuardar.disabled = false
        } else {
            btnGuardar.disabled = false
            icon = 'error'
            console.log(detalle);
        }

        Toast.fire({
            icon: icon,
            title: mensaje
        })

    } catch (error) {
        console.log(error);
    }
    btnGuardar.disabled = false
}



const buscar = async () => {
    try {
        const url = "/Escuela_BHR/API/cursos/buscar"
        const config = {
            method: 'GET',
        }

        const respuesta = await fetch(url, config);
        const data = await respuesta.json();
        const { codigo, mensaje, detalle, datos } = data;

        // tabla.tBodies[0].innerHTML = ''
        // const fragment = document.createDocumentFragment();
        console.log(datos);
        datatable.clear().draw();

        if (datos) {
            datatable.rows.add(datos).draw();
        }

    } catch (error) {
        console.log(error);
    }
}
buscar();

const traerDatos = (e) => {
    const elemento = e.currentTarget.dataset

    formulario.cur_codigo.value = elemento.cur_codigo
    formulario.cur_nombre.value = elemento.cur_nombre
    formulario.cur_desc_lg.value = elemento.cur_desc_lg
    formulario.cur_duracion.value = elemento.cur_duracion
    formulario.cur_fec_creacion.value = elemento.cur_fec_creacion
    tabla.parentElement.parentElement.style.display = 'none'

    btnGuardar.parentElement.style.display = 'none'
    btnGuardar.disabled = true
    btnModificar.parentElement.style.display = ''
    btnModificar.disabled = false
    btnCancelar.parentElement.style.display = ''
    btnCancelar.disabled = false
}

const cancelar = () => {
    tabla.parentElement.parentElement.style.display = ''
    formulario.reset();
    btnGuardar.parentElement.style.display = ''
    btnGuardar.disabled = false
    btnModificar.parentElement.style.display = 'none'
    btnModificar.disabled = true
    btnCancelar.parentElement.style.display = 'none'
    btnCancelar.disabled = true
}



const modificar = async (e) => {
    e.preventDefault()

    if (!validarFormulario(formulario)) {
        Swal.fire({
            title: "Campos vacios",
            text: "Debe llenar todos los campos",
            icon: "info"
        })
        return
    }

    try {
        const body = new FormData(formulario)
        const url = "/Escuela_BHR/API/cursos/modificar"
        const config = {
            method: 'POST',
            body
        }

        const respuesta = await fetch(url, config);
        const data = await respuesta.json();
        const { codigo, mensaje, detalle } = data;
        console.log(data);
        let icon = 'info'
        if (codigo == 1) {
            icon = 'success'
            formulario.reset();
            buscar();
            cancelar();
        } else {
            icon = 'error'
            console.log(detalle);
        }

        Toast.fire({
            icon: icon,
            title: mensaje
        })

    } catch (error) {
        console.log(error);
    }
}


const eliminar = async (e) => {
    const cur_codigo = e.currentTarget.dataset.cur_codigo
    console.log("ID a eliminar:", cur_codigo); // Agrega esta línea
    let confirmacion = await Swal.fire({
        icon: 'question',
        title: 'Confirmacion',
        text: '¿Esta seguro que desea eliminar este registro?',
        showCancelButton: true,
        confirmButtonText: 'Si, eliminar',
        cancelButtonText: 'No, cancelar',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        // input: 'text'
    })
    console.log(confirmacion);
    if (confirmacion.isConfirmed) {
        try {
            const body = new FormData()
            body.append('cur_codigo', cur_codigo)
            const url = "/Escuela_BHR/API/cursos/eliminar"
            const config = {
                method: 'POST',
                body
            }

            const respuesta = await fetch(url, config);
            const data = await respuesta.json();
            const { codigo, mensaje, detalle } = data;
            let icon = 'info'
            if (codigo == 1) {
                icon = 'success'
                formulario.reset();
                buscar();
            } else {
                icon = 'error'
                console.log(detalle);
            }

            Toast.fire({
                icon: icon,
                title: mensaje
            })
        } catch (error) {
            console.log(error);
        }
    }

}

formulario.addEventListener('submit', guardar)
btnCancelar.addEventListener('click', cancelar)
btnModificar.addEventListener('click', modificar)
datatable.on('click', '.modificar', traerDatos)
datatable.on('click', '.eliminar', eliminar)