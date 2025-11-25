import { Modal } from "bootstrap";
import { Toast, validarFormulario } from '../funciones';
import Swal from 'sweetalert2';
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

// ============================================
// REFERENCIAS A ELEMENTOS DEL DOM
// ============================================
const formUsuario = document.getElementById('formUsuario');
const btnGuardar = document.getElementById('btnGuardar');
const btnModificar = document.getElementById('btnModificar');
const btnCancelar = document.getElementById('btnCancelar');
const btnFlotante = document.getElementById('btnFlotante');
const contenedorFormulario = document.getElementById('contenedorFormulario');
const contenedorTabla = document.getElementById('contenedorTabla');
const tituloFormulario = document.getElementById('tituloFormulario');
let tablaUsuarios;

// ============================================
// ESTADO INICIAL
// ============================================
btnModificar.parentElement.style.display = 'none';
btnCancelar.parentElement.style.display = 'none';

// ============================================
// CONFIGURACIÓN DE DATATABLE
// ============================================
const inicializarTabla = () => {
    tablaUsuarios = new DataTable('#tablaUsuarios', {
        language: lenguaje,
        pageLength: 15,
        lengthMenu: [5, 15, 25, 100],
        data: [],
        columns: [
            {
                title: 'No.',
                data: null,
                width: '60px',
                render: (data, type, row, meta) => meta.row + 1
            },
            {
                title: 'Catálogo',
                data: 'usu_catalogo',
                width: '120px',
                render: (data) => `<strong>${data}</strong>`
            },
            {
                title: 'Nombre',
                data: 'usu_nombre'
            },
            {
                title: 'Roles',
                data: null,
                width: '120px',
                render: (data) => {
                    return '<span class="badge bg-info"><i class="bi bi-person"></i> Usuario</span>';
                }
            },
            {
                title: 'Estado',
                data: 'usu_situacion',
                width: '100px',
                render: (data) => {
                    if (data == 1) {
                        return '<span class="badge bg-success"><i class="bi bi-check-circle"></i> Activo</span>';
                    } else {
                        return '<span class="badge bg-danger"><i class="bi bi-x-circle"></i> Inactivo</span>';
                    }
                }
            },
            {
                title: 'Acciones',
                data: null,
                width: '140px',
                searchable: false,
                orderable: false,
                render: (data) => {
                    return `
                        <button class='btn btn-acciones btn-modificar modificar'
                            title='Modificar usuario'
                            data-usu_id="${data.usu_id}"
                            data-usu_nombre="${data.usu_nombre}"
                            data-usu_catalogo="${data.usu_catalogo}">
                            <i class='bi bi-pencil-square'></i>
                        </button>
                        <button class='btn btn-acciones btn-eliminar eliminar'
                            title='Eliminar usuario'
                            data-usu_id="${data.usu_id}"
                            data-usu_nombre="${data.usu_nombre}">
                            <i class='bi bi-trash'></i>
                        </button>
                    `;
                }
            }
        ]
    });
};

// ============================================
// FUNCIONES DE FORMULARIO
// ============================================

// Mostrar formulario con animación
const mostrarFormulario = () => {
    contenedorFormulario.style.display = '';
    contenedorFormulario.classList.add('slide-down');
    contenedorTabla.style.display = 'none';
    tituloFormulario.textContent = 'Nuevo Usuario';
    formUsuario.reset();

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
    btnFlotante.title = 'Nuevo Usuario';
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
// BUSCAR USUARIOS
// ============================================
const buscar = async () => {
    try {
        const url = '/Escuela_BHR/API/usuarios/buscar';
        const config = {
            method: 'GET'
        };

        const respuesta = await fetch(url, config);
        const data = await respuesta.json();

        tablaUsuarios.clear().draw();

        if (data.codigo == 1 && data.datos && data.datos.length > 0) {
            tablaUsuarios.rows.add(data.datos).draw();
        } else {
            Toast.fire({
                icon: 'info',
                title: 'No hay usuarios registrados'
            });
        }
    } catch (error) {
        console.error('Error al buscar usuarios:', error);
        Toast.fire({
            icon: 'error',
            title: 'Error al buscar usuarios'
        });
    }
};

// ============================================
// GUARDAR USUARIO
// ============================================
const guardar = async (e) => {
    e.preventDefault();

    // Validar formulario
    if (!validarFormulario(formUsuario, [])) {
        Toast.fire({
            icon: 'warning',
            title: 'Debe llenar todos los campos'
        });
        return;
    }

    // Validar que las contraseñas coincidan
    const password = document.getElementById('usu_password').value;
    const password2 = document.getElementById('usu_password2').value;

    if (password !== password2) {
        Toast.fire({
            icon: 'error',
            title: 'Las contraseñas no coinciden'
        });
        document.getElementById('usu_password').classList.add('is-invalid');
        document.getElementById('usu_password2').classList.add('is-invalid');
        return;
    }

    // Validar longitud mínima de contraseña
    if (password.length < 6) {
        Toast.fire({
            icon: 'warning',
            title: 'La contraseña debe tener al menos 6 caracteres'
        });
        return;
    }

    // Mostrar loader
    btnGuardar.disabled = true;
    btnGuardar.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Guardando...';

    try {
        const body = new FormData(formUsuario);
        const url = '/Escuela_BHR/API/usuarios/crear';

        const config = {
            method: 'POST',
            body
        };

        const respuesta = await fetch(url, config);
        const data = await respuesta.json();

        if (data.codigo == 1) {
            Toast.fire({
                icon: 'success',
                title: data.mensaje
            });

            formUsuario.reset();
            buscar();
            ocultarFormulario();
        } else {
            Toast.fire({
                icon: 'error',
                title: data.mensaje,
                text: data.detalle || ''
            });
        }
    } catch (error) {
        console.error('Error al crear usuario:', error);
        Toast.fire({
            icon: 'error',
            title: 'Error al crear usuario'
        });
    } finally {
        btnGuardar.disabled = false;
        btnGuardar.innerHTML = '<i class="bi bi-save-fill"></i> Crear Usuario';
    }
};

// ============================================
// CARGAR DATOS PARA MODIFICAR
// ============================================
const traerDatos = (e) => {
    const d = e.currentTarget.dataset;

    formUsuario.usu_nombre.value = d.usu_nombre;
    formUsuario.usu_catalogo.value = d.usu_catalogo;

    // Limpiar campos de contraseña
    formUsuario.usu_password.value = '';
    formUsuario.usu_password2.value = '';

    // Cambiar título y mostrar formulario
    tituloFormulario.textContent = 'Modificar Usuario';
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
    formUsuario.usu_catalogo.setAttribute('readonly', true);

    // Guardar ID para modificación
    formUsuario.dataset.usu_id = d.usu_id;
};

// ============================================
// MODIFICAR USUARIO
// ============================================
const modificar = async (e) => {
    e.preventDefault();

    if (!validarFormulario(formUsuario, [])) {
        Toast.fire({
            icon: 'warning',
            title: 'Debe llenar todos los campos'
        });
        return;
    }

    // Validar contraseñas si se están cambiando
    const password = document.getElementById('usu_password').value;
    const password2 = document.getElementById('usu_password2').value;

    if (password || password2) {
        if (password !== password2) {
            Toast.fire({
                icon: 'error',
                title: 'Las contraseñas no coinciden'
            });
            return;
        }

        if (password.length < 6) {
            Toast.fire({
                icon: 'warning',
                title: 'La contraseña debe tener al menos 6 caracteres'
            });
            return;
        }
    }

    // Mostrar loader
    btnModificar.disabled = true;
    btnModificar.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Modificando...';

    try {
        const body = new FormData(formUsuario);
        body.append('usu_id', formUsuario.dataset.usu_id);

        const url = '/Escuela_BHR/API/usuarios/modificar';
        const config = {
            method: 'POST',
            body
        };

        const respuesta = await fetch(url, config);
        const data = await respuesta.json();

        if (data.codigo == 1) {
            Toast.fire({
                icon: 'success',
                title: data.mensaje
            });

            formUsuario.reset();
            formUsuario.usu_catalogo.removeAttribute('readonly');
            delete formUsuario.dataset.usu_id;
            buscar();
            ocultarFormulario();
        } else {
            Toast.fire({
                icon: 'error',
                title: data.mensaje,
                text: data.detalle || ''
            });
        }
    } catch (error) {
        console.error('Error al modificar usuario:', error);
        Toast.fire({
            icon: 'error',
            title: 'Error al modificar usuario'
        });
    } finally {
        btnModificar.disabled = false;
        btnModificar.innerHTML = '<i class="bi bi-pencil-square"></i> Modificar Usuario';
    }
};

// ============================================
// ELIMINAR USUARIO
// ============================================
const eliminar = async (e) => {
    const usu_id = e.currentTarget.dataset.usu_id;
    const usu_nombre = e.currentTarget.dataset.usu_nombre;

    const confirmacion = await Swal.fire({
        icon: 'question',
        title: 'Confirmación',
        html: `¿Está seguro de eliminar al usuario <strong>${usu_nombre}</strong>?`,
        showCancelButton: true,
        confirmButtonText: '<i class="bi bi-check-circle"></i> Sí, eliminar',
        cancelButtonText: '<i class="bi bi-x-circle"></i> No, cancelar',
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        reverseButtons: true,
        draggable: true
    });

    if (confirmacion.isConfirmed) {
        try {
            const body = new FormData();
            body.append('usu_id', usu_id);

            const url = '/Escuela_BHR/API/usuarios/eliminar';
            const config = {
                method: 'POST',
                body
            };

            const respuesta = await fetch(url, config);
            const data = await respuesta.json();

            if (data.codigo == 1) {
                Toast.fire({
                    icon: 'success',
                    title: data.mensaje
                });
                buscar();
            } else {
                Toast.fire({
                    icon: 'error',
                    title: data.mensaje
                });
            }
        } catch (error) {
            console.error('Error al eliminar usuario:', error);
            Toast.fire({
                icon: 'error',
                title: 'Error al eliminar usuario'
            });
        }
    }
};

// ============================================
// CANCELAR OPERACIÓN
// ============================================
const cancelar = () => {
    formUsuario.reset();
    formUsuario.usu_catalogo.removeAttribute('readonly');
    delete formUsuario.dataset.usu_id;
    ocultarFormulario();

    btnGuardar.parentElement.style.display = '';
    btnModificar.parentElement.style.display = 'none';
    btnCancelar.parentElement.style.display = 'none';
};

// ============================================
// ASIGNACIÓN DE EVENTOS
// ============================================
formUsuario.addEventListener('submit', guardar);
btnModificar.addEventListener('click', modificar);
btnCancelar.addEventListener('click', cancelar);

// ============================================
// INICIALIZACIÓN
// ============================================
inicializarTabla();

// Eventos de la tabla (delegación de eventos) - DESPUÉS de inicializar
tablaUsuarios.on('click', '.modificar', traerDatos);
tablaUsuarios.on('click', '.eliminar', eliminar);

// Cargar datos
buscar();