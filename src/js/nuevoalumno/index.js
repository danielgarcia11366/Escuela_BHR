import { Dropdown } from "bootstrap";
import { Toast, validarFormulario } from "../funciones";
import Swal from "sweetalert2";
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

const formulario = document.getElementById('formularioAlumnos');
const tabla = document.getElementById('tablaAlumnos')
const btnGuardar = document.getElementById('btnGuardar')
const btnModificar = document.getElementById('btnModificar')
const btnCancelar = document.getElementById('btnCancelar')

let contador = 1;
btnModificar.disabled = true;
btnModificar.parentElement.style.display = 'none';
btnCancelar.disabled = true;
    

const datatable = new DataTable('#tablaAlumnos', {
    data: null,
    language: lenguaje,
    pageLength: '15',
    lengthMenu: [3, 9, 11, 25, 100],
    columns: [
        {
            title: 'No.',
            data: 'per_catalogo',
            width: '2%',
            render: (data, type, row, meta) => {
                // console.log(meta.ro);
                return meta.row + 1;
            }
        },
        {
            title: 'Primer Nombre',
            data: 'per_nom1'
        },
        {
            title: 'Segundo Nombre',
            data: 'per_nom2'
        }, {
            title: 'Primer Apellido',
            data: 'per_ape1'
        },
        {
            title: 'Segundo Apellido',
            data: 'per_ape2'
        },
        {
            title: 'Grado',
            data: 'per_grado'
        },
        {
            title: 'Arma',
            data: 'per_arma'
        },
        {
            title: 'Telefono',
            data: 'per_telefono'
        },
        {
            title: 'Sexo',
            data: 'per_sexo'
        },
        {
            title: 'Fecha Nacimiento',
            data: 'per_fec_nac'
        },
        {
            title: 'Lugar Nacimiento',
            data: 'per_nac_lugar'
        },
        {
            title: 'Numero DPI',
            data: 'per_dpi'
        },
        {
            title: 'Acciones',
            data: 'cur_codigo',
            searchable: false,
            orderable: false,
            render: (data, type, row, meta) => {
                let html = `
                <button class='btn btn-warning modificar' data-per_catalogo="${data}" data-per_nom1="${row.per_nom1}" data-per_nom2="${row.per_nom2}" data-per_ape1="${row.per_ape1}" data-per_ape2="${row.per_ape2}" data-per_grado="${row.per_grado}" data-per_arma="${row.per_arma}" data-per_telefono="${row.per_telefono}" data-per_sexo="${row.per_sexo}" data-per_fec_nac="${row.per_fec_nac}" data-per_nac_lugar="${row.per_nac_lugar}" data-per_dpi="${row.per_dpi}">
                    <i class='bi bi-pencil-square'></i> 
                </button>
                <button class='btn btn-danger eliminar' data-per_catalogo="${data}">
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

    if (!validarFormulario(formulario, ['per_catalogo'])) {
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
        const url = "/Escuela_BHR/API/alumnonuevo/guardar"
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
        const url = "/Escuela_BHR/API/alumnonuevo/buscar";
        const config = {
            method: 'GET'
        };

        const respuesta = await fetch(url, config);
        const data = await respuesta.json();
        const { datos } = data; // Obtén los datos correctamente

        datatable.clear().draw(); // Limpia la tabla antes de añadir los nuevos datos

        if (datos) {
            datatable.rows.add(datos).draw(); // Añade los datos a la tabla y dibuja
        }
    } catch (error) {
        console.log(error);
    }
};
buscar();

const traerDatos = (e) => {
    const elemento = e.currentTarget.dataset

    formulario.per_catalogo.value = elemento.per_catalogo
    formulario.per_nom1.value = elemento.per_nom1
    formulario.per_nom2.value = elemento.per_nom2
    formulario.per_ape1.value = elemento.per_ape1
    formulario.per_ape2.value = elemento.per_ape2
    formulario.per_grado.value = elemento.per_grado
    formulario.per_arma.value = elemento.per_arma
    formulario.per_telefono.value = elemento.per_telefono
    formulario.per_sexo.value = elemento.per_sexo
    formulario.per_fec_nac.value = elemento.per_fec_nac
    formulario.per_nac_lugar.value = elemento.per_nac_lugar
    formulario.per_dpi.value = elemento.per_dpi
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
        const url = "/Escuela_BHR/API/alumnonuevo/modificar"
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
            const url = "/Escuela_BHR/API/alumnonuevo/eliminar"
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