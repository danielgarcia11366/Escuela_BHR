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

// ⭐ Referencias para la foto
const inputFoto = document.getElementById('per_foto');
const previewContainer = document.getElementById('preview-container');
const previewImage = document.getElementById('preview-image');

// ============================================
// VISTA PREVIA DE LA FOTO
// ============================================
inputFoto.addEventListener('change', (e) => {
    const file = e.target.files[0];

    if (file) {
        // Validar tamaño (5MB)
        if (file.size > 10 * 1024 * 1024) {
            Toast.fire({
                icon: 'error',
                title: 'La imagen no debe superar los 10MB'
            });
            inputFoto.value = '';
            previewContainer.style.display = 'none';
            return;
        }

        // Validar tipo
        const tiposPermitidos = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!tiposPermitidos.includes(file.type)) {
            Toast.fire({
                icon: 'error',
                title: 'Solo se permiten imágenes JPG o PNG'
            });
            inputFoto.value = '';
            previewContainer.style.display = 'none';
            return;
        }

        // Mostrar vista previa
        const reader = new FileReader();
        reader.onload = (e) => {
            previewImage.src = e.target.result;
            previewContainer.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        previewContainer.style.display = 'none';
    }
});

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
            title: 'Foto',
            data: 'per_foto',
            orderable: false,
            searchable: false,
            render: (data, type, row) => {
                if (data) {
                    return `
                <img src="/Escuela_BHR/public/uploads/fotos_personal/${data}" 
                     alt="Foto" 
                     style="width: 70px; height: 70px; object-fit: cover; border-radius: 50%; 
                            box-shadow: 0 2px 8px rgba(0,0,0,0.2); cursor: pointer; 
                            transition: transform 0.3s ease;"
                     onmouseover="this.style.transform='scale(1.1)'"
                     onmouseout="this.style.transform='scale(1)'"
                     onclick="verFotoGrande('${data}', '${row.per_catalogo}')"
                     onerror="this.src='/Escuela_BHR/public/img/no-foto.png'">
            `;
                } else {
                    return `<i class="bi bi-person-circle" style="font-size: 70px; color: #6c757d;"></i>`;
                }
            }
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
                    data-per_nom1="${row.per_nom1 || ''}"
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
                    data-observaciones="${row.observaciones || ''}"
                    data-per_foto="${row.per_foto || ''}">
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

    // ⭐ Limpiar vista previa de foto
    previewContainer.style.display = 'none';
    previewImage.src = '';

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
        // ⭐ Usar FormData para enviar archivos
        const body = new FormData(formulario);

        const url = "/Escuela_BHR/API/personal/guardar";
        const respuesta = await fetch(url, { method: 'POST', body });
        const data = await respuesta.json();

        const { codigo, mensaje } = data;
        let icon = codigo == 1 ? 'success' : 'error';

        if (codigo == 1) {
            formulario.reset();
            previewContainer.style.display = 'none'; // ⭐ Limpiar vista previa
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

    // ⭐ Mostrar foto actual si existe
    if (d.per_foto && d.per_foto !== 'null' && d.per_foto !== '') {
        previewImage.src = `/Escuela_BHR/public/uploads/fotos_personal/${d.per_foto}`;
        previewContainer.style.display = 'block';
    } else {
        previewContainer.style.display = 'none';
    }

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
        // ⭐ Usar FormData para enviar archivos
        const body = new FormData(formulario);

        const url = "/Escuela_BHR/API/personal/modificar";
        const respuesta = await fetch(url, { method: 'POST', body });
        const data = await respuesta.json();

        const { codigo, mensaje } = data;
        let icon = codigo == 1 ? 'success' : 'error';

        if (codigo == 1) {
            formulario.reset();
            formulario.per_catalogo.removeAttribute('readonly');
            previewContainer.style.display = 'none'; // ⭐ Limpiar vista previa
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
        text: '¿Está seguro de eliminar este registro? La foto también será eliminada.',
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
    previewContainer.style.display = 'none'; // ⭐ Limpiar vista previa
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

// Al final de src/js/personal/index.js

window.verFotoGrande = (nombreFoto, catalogo) => {
    const datos = datatable.rows().data().toArray();
    const persona = datos.find(p => p.per_catalogo == catalogo);

    if (persona) {
        Swal.fire({
            html: `
                <div style="
                    background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
                    padding: 3rem 2rem;
                    border-radius: 20px;
                    box-shadow: 0 15px 50px rgba(0,0,0,0.3);
                    color: white;
                ">
                    <!-- Header -->
                    <div style="text-align: center; margin-bottom: 2rem;">
                        <h2 style="color: white; margin: 0; font-weight: 700; font-size: 1.8rem;">
                            <i class="bi bi-shield-fill-check"></i> 
                            Escuela de Adiestramiento de Asistencia Humanitaria y Rescate
                        </h2>
                        <p style="color: #ccc; margin: 0.5rem 0 0 0; font-size: 1.5rem;">
                            Identificación Oficial
                        </p>
                    </div>
                    
                    <!-- Foto Cuadrada -->
                    <div style="text-align: center; margin-bottom: 2rem;">
                        <img src="/Escuela_BHR/public/uploads/fotos_personal/${nombreFoto}" 
                             alt="Foto" 
                             style="
                                width: 500px; 
                                height: 400px; 
                                object-fit: cover; 
                                border-radius: 15px; 
                                border: 5px solid white;
                                box-shadow: 0 10px 30px rgba(0,0,0,0.3);
                             ">
                    </div>
                    
                    <!-- Nombre -->
                    <div style="
                        background: white;
                        color: #1a1a1a;
                        padding: 1.5rem;
                        border-radius: 15px;
                        text-align: center;
                    ">
                        <h3 style="
                            margin: 0; 
                            color: #1a1a1a;
                            font-weight: 700;
                            font-size: 1.5rem;
                        ">
                            ${persona.grado_arma}
                            ${persona.nombre_completo}
                        </h3>
                    </div>
                </div>
            `,
            width: 700,
            showCloseButton: true,
            showConfirmButton: false,
            background: 'transparent',
            backdrop: `rgba(0,0,0,0.85)`,
            customClass: {
                popup: 'no-border-modal'
            }
        });
    }
};

// ============================================
// INICIALIZACIÓN
// ============================================
buscar();