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

// 🆕 REFERENCIAS PARA CERTIFICACIÓN
const inputCalificacion = document.getElementById('par_calificacion');
const inputPromocion = document.getElementById('par_promocion');
const inputPosicion = document.getElementById('par_posicion');
const camposCertificado = document.getElementById('camposCertificado');
const mensajeCertificacion = document.getElementById('mensajeCertificacion');
const textoCertificacion = document.getElementById('textoCertificacion');
const inputCertificadoNumero = document.getElementById('par_certificado_numero');
const inputCertificadoFecha = document.getElementById('par_certificado_fecha');

// 🆕 VARIABLE GLOBAL PARA SABER SI EMITE CERTIFICADO
let cursoEmiteCertificado = false;

let timeoutCalculoPosicion = null;

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
            width: '28%',
            render: (data) => {
                return data.replace(/(Básico|Intermedio|Avanzado)/gi, '<strong>$1</strong>');
            }
        },
        {
            title: 'Promoción y Curso',
            data: 'promocion_info',
            width: '26%',
            render: (data) => {
                return data.replace(/(Promoción \d+ \d+)/g, '<strong>$1</strong>')
                    .replace(/(Básico|Intermedio|Avanzado)/gi, '<strong>$1</strong>');
            }
        },
        {
            title: 'Calificación',
            data: 'par_calificacion',
            width: '8%',
            render: (data) => {
                if (!data) return '-';
                const nota = parseFloat(data);
                const color = nota >= 80 ? 'text-success' : nota >= 60 ? 'text-warning' : 'text-danger';
                return `<strong class="${color}">${nota.toFixed(2)}</strong>`;
            }
        },
        {
            title: '🏆 Posición',
            data: 'par_posicion',
            width: '8%',
            render: (data) => {
                if (!data) return '-';
                const posicion = parseInt(data);

                let emoji = '';
                if (posicion === 1) emoji = '🥇';
                else if (posicion === 2) emoji = '🥈';
                else if (posicion === 3) emoji = '🥉';

                return `<strong class="text-primary">${emoji} #${posicion}</strong>`;
            }
        },
        {
            title: 'Estado',
            data: 'par_estado',
            width: '10%',
            render: (data) => {
                const estados = {
                    'C': '<span class="badge bg-primary">Cursando</span>',
                    'G': '<span class="badge bg-success">Graduado</span>',
                    'R': '<span class="badge bg-warning">Retirado</span>',
                    'D': '<span class="badge bg-danger">Desertor</span>'
                };
                return estados[data] || data;
            }
        },
        {
            title: 'Acciones',
            data: 'par_codigo',
            orderable: false,
            searchable: false,
            render: (data, type, row) => `
            <button class="btn btn-acciones btn-modificar modificar"
                data-par_codigo="${data}"
                data-par_promocion="${row.par_promocion}"
                data-par_catalogo="${row.par_catalogo}"
                data-par_calificacion="${row.par_calificacion || ''}"
                data-par_posicion="${row.par_posicion || ''}"
                data-par_estado="${row.par_estado}"
                data-par_certificado_numero="${row.par_certificado_numero || ''}"
                data-par_certificado_fecha="${row.par_certificado_fecha || ''}"
                data-par_observaciones="${row.par_observaciones || ''}">
                <i class="bi bi-pencil-square"></i>
            </button>
            <button class="btn btn-acciones btn-eliminar eliminar"
                data-par_codigo="${data}">
                <i class="bi bi-trash"></i>
            </button>
            `
        }
    ]
});

btnModificar.parentElement.style.display = 'none';
btnCancelar.parentElement.style.display = 'none';

btnFlotante.addEventListener('click', () => {
    if (contenedorFormulario.style.display === 'none') mostrarFormulario();
    else ocultarFormulario();
});

const mostrarFormulario = () => {
    contenedorFormulario.style.display = '';
    contenedorFormulario.classList.add('slide-down');
    contenedorTabla.style.display = 'none';
    tituloFormulario.textContent = 'Asigne una Promoción';
    formulario.reset();

    btnGuardar.parentElement.style.display = '';
    btnModificar.parentElement.style.display = 'none';
    btnCancelar.parentElement.style.display = 'none';

    btnFlotante.innerHTML = '<i class="bi bi-skip-backward"></i>';
    btnFlotante.setAttribute('title', 'Volver a la tabla');

    limpiarPosicionEstimada();
    ocultarCamposCertificado(); // 🆕 Ocultar campos por defecto
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
    btnFlotante.setAttribute('title', 'Asigne una Promoción');
};

// 🆕 ================================================
// VERIFICAR SI EL CURSO EMITE CERTIFICACIÓN
// ================================================
const verificarCertificacion = async (promocionId) => {
    if (!promocionId) {
        ocultarCamposCertificado();
        return;
    }

    try {
        const body = new FormData();
        body.append('par_promocion', promocionId);

        const url = "/Escuela_BHR/API/participantes/verificar-certificacion";
        const resp = await fetch(url, { method: 'POST', body });
        const data = await resp.json();

        if (data.codigo === 1 && data.datos) {
            cursoEmiteCertificado = data.datos.emite_certificado;

            if (cursoEmiteCertificado) {
                mostrarCamposCertificado(data.datos.mensaje);
            } else {
                ocultarCamposCertificado(data.datos.mensaje);
            }
        } else {
            ocultarCamposCertificado();
        }

    } catch (error) {
        console.error('Error al verificar certificación:', error);
        ocultarCamposCertificado();
    }
};

const mostrarCamposCertificado = (mensaje) => {
    // Mostrar campos
    camposCertificado.classList.remove('oculto');
    camposCertificado.style.display = '';

    // Habilitar inputs
    inputCertificadoNumero.disabled = false;
    inputCertificadoFecha.disabled = false;

    // Mostrar mensaje informativo
    mensajeCertificacion.style.display = 'flex';
    mensajeCertificacion.classList.remove('no-certifica');
    textoCertificacion.textContent = mensaje || '✅ Este curso emite certificación';
};

const ocultarCamposCertificado = (mensaje = null) => {
    // Ocultar campos con animación
    camposCertificado.classList.add('oculto');

    // Limpiar y deshabilitar inputs
    inputCertificadoNumero.value = '';
    inputCertificadoFecha.value = '';
    inputCertificadoNumero.disabled = true;
    inputCertificadoFecha.disabled = true;

    // Mostrar mensaje informativo si lo hay
    if (mensaje) {
        mensajeCertificacion.style.display = 'flex';
        mensajeCertificacion.classList.add('no-certifica');
        textoCertificacion.textContent = mensaje;
    } else {
        mensajeCertificacion.style.display = 'none';
    }
};

// 🆕 EVENTO CUANDO CAMBIA LA PROMOCIÓN
inputPromocion.addEventListener('change', async () => {
    const promocionId = inputPromocion.value;

    // Verificar si el curso emite certificado
    await verificarCertificacion(promocionId);

    // Si hay calificación, recalcular posición
    if (inputCalificacion.value.trim()) {
        calcularPosicionEstimada();
    }
});

// ================================================
// CALCULAR POSICIÓN ESTIMADA
// ================================================
const calcularPosicionEstimada = async () => {
    const calificacion = inputCalificacion.value.trim();
    const promocion = inputPromocion.value;

    if (!calificacion || !promocion) {
        limpiarPosicionEstimada();
        return;
    }

    const nota = parseFloat(calificacion);
    if (nota < 0 || nota > 100) {
        mostrarPosicionError('La calificación debe estar entre 0 y 100');
        return;
    }

    try {
        inputPosicion.value = 'Calculando...';
        inputPosicion.classList.add('text-muted');
        inputPosicion.disabled = true;

        const body = new FormData();
        body.append('par_promocion', promocion);
        body.append('par_calificacion', calificacion);

        const url = "/Escuela_BHR/API/participantes/calcular-posicion";
        const resp = await fetch(url, { method: 'POST', body });
        const data = await resp.json();

        if (data.codigo === 1 && data.datos) {
            const { posicion, total_participantes, mensaje } = data.datos;

            let emoji = '';
            if (posicion === 1) emoji = '🥇';
            else if (posicion === 2) emoji = '🥈';
            else if (posicion === 3) emoji = '🥉';
            else emoji = '🏆';

            inputPosicion.value = `${emoji} Lugar #${posicion} de ${total_participantes}`;
            inputPosicion.classList.remove('text-muted');
            inputPosicion.classList.add('text-success', 'fw-bold');

            Toast.fire({
                icon: 'info',
                title: mensaje,
                timer: 3000
            });
        } else {
            mostrarPosicionError('No se pudo calcular la posición');
        }

    } catch (error) {
        console.error('Error al calcular posición:', error);
        mostrarPosicionError('Error al calcular');
    }
};

const limpiarPosicionEstimada = () => {
    inputPosicion.value = '';
    inputPosicion.classList.remove('text-muted', 'text-success', 'text-danger', 'fw-bold');
    inputPosicion.disabled = false;
    inputPosicion.placeholder = 'Se calculará automáticamente';
};

const mostrarPosicionError = (mensaje) => {
    inputPosicion.value = mensaje;
    inputPosicion.classList.remove('text-muted', 'text-success');
    inputPosicion.classList.add('text-danger');
    inputPosicion.disabled = true;
};

inputCalificacion.addEventListener('input', () => {
    if (timeoutCalculoPosicion) {
        clearTimeout(timeoutCalculoPosicion);
    }

    timeoutCalculoPosicion = setTimeout(() => {
        calcularPosicionEstimada();
    }, 500);
});

// GUARDAR
const guardar = async (e) => {
    e.preventDefault();
    btnGuardar.disabled = true;

    // 🆕 VALIDAR CAMPOS OBLIGATORIOS (excluyendo certificado si no aplica)
    const camposExcluir = ['par_codigo', 'par_posicion'];
    if (!cursoEmiteCertificado) {
        camposExcluir.push('par_certificado_numero', 'par_certificado_fecha');
    }

    if (!validarFormulario(formulario, camposExcluir)) {
        Swal.fire({
            icon: "warning",
            title: "Campos incompletos",
            text: "Debe llenar todos los campos obligatorios marcados con (*)",
            confirmButtonText: 'Entendido',
            confirmButtonColor: '#f39c12'
        });
        btnGuardar.disabled = false;
        return;
    }

    try {
        const body = new FormData(formulario);
        body.delete('par_posicion');

        // 🆕 Si no emite certificado, limpiar campos
        if (!cursoEmiteCertificado) {
            body.set('par_certificado_numero', '');
            body.set('par_certificado_fecha', '');
        }

        const url = "/Escuela_BHR/API/participantes/guardar";
        const resp = await fetch(url, { method: 'POST', body });
        const data = await resp.json();

        const { codigo, mensaje, campo, posicion_asignada } = data;

        if (codigo === 1) {
            let textoMensaje = mensaje;
            if (posicion_asignada) {
                let emoji = '';
                if (posicion_asignada === 1) emoji = '🥇';
                else if (posicion_asignada === 2) emoji = '🥈';
                else if (posicion_asignada === 3) emoji = '🥉';
                textoMensaje += `<br><strong>${emoji} Posición asignada: #${posicion_asignada}</strong>`;
            }

            Swal.fire({
                icon: "success",
                title: "¡Registro exitoso!",
                html: textoMensaje,
                timer: 3000,
                showConfirmButton: false
            });

            formulario.reset();
            buscar();
            ocultarFormulario();

        } else {
            Swal.fire({
                icon: "error",
                title: "No se pudo guardar",
                text: mensaje,
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#dc3545'
            });

            if (campo) {
                const campoDuplicado = document.getElementById(campo);
                if (campoDuplicado) {
                    campoDuplicado.focus();
                    campoDuplicado.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        }

    } catch (err) {
        console.error("Error en guardar():", err);
        Swal.fire({
            icon: "error",
            title: "Error del sistema",
            text: "Ocurrió un error inesperado.",
            confirmButtonText: 'Entendido',
            confirmButtonColor: '#dc3545'
        });
    }

    btnGuardar.disabled = false;
};

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

const traerDatos = async (e) => {
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

    // 🆕 VERIFICAR CERTIFICACIÓN Y CALCULAR POSICIÓN
    await verificarCertificacion(d.par_promocion);
    if (d.par_calificacion) {
        calcularPosicionEstimada();
    }
};

const modificar = async (e) => {
    e.preventDefault();

    const camposExcluir = ['par_posicion'];
    if (!cursoEmiteCertificado) {
        camposExcluir.push('par_certificado_numero', 'par_certificado_fecha');
    }

    if (!validarFormulario(formulario, camposExcluir)) {
        Swal.fire({
            icon: "warning",
            title: "Campos incompletos",
            text: "Debe llenar todos los campos obligatorios",
            confirmButtonText: 'Entendido',
            confirmButtonColor: '#f39c12'
        });
        return;
    }

    try {
        const body = new FormData(formulario);
        body.delete('par_posicion');

        if (!cursoEmiteCertificado) {
            body.set('par_certificado_numero', '');
            body.set('par_certificado_fecha', '');
        }

        const url = "/Escuela_BHR/API/participantes/modificar";
        const resp = await fetch(url, { method: 'POST', body });
        const data = await resp.json();
        const { codigo, mensaje, campo, posicion_asignada } = data;

        if (codigo == 1) {
            let textoMensaje = mensaje;
            if (posicion_asignada) {
                let emoji = '';
                if (posicion_asignada === 1) emoji = '🥇';
                else if (posicion_asignada === 2) emoji = '🥈';
                else if (posicion_asignada === 3) emoji = '🥉';
                textoMensaje += `<br><strong>${emoji} Nueva posición: #${posicion_asignada}</strong>`;
            }

            Swal.fire({
                icon: "success",
                title: "¡Actualizado!",
                html: textoMensaje,
                timer: 3000,
                showConfirmButton: false
            });
            formulario.reset();
            buscar();
            cancelar();
        } else {
            Swal.fire({
                icon: "error",
                title: "No se pudo modificar",
                text: mensaje,
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#dc3545'
            });

            if (campo) {
                const campoError = document.getElementById(campo);
                if (campoError) {
                    campoError.focus();
                    campoError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        }
    } catch (err) {
        console.error(err);
        Swal.fire({
            icon: "error",
            title: "Error del sistema",
            text: "Error al modificar el participante",
            confirmButtonText: 'Entendido',
            confirmButtonColor: '#dc3545'
        });
    }
};

const eliminar = async (e) => {
    const par_codigo = e.currentTarget.dataset.par_codigo;

    const confirmacion = await Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción recalculará las posiciones de todos los participantes.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    });

    if (!confirmacion.isConfirmed) return;

    try {
        const body = new FormData();
        body.append('par_codigo', par_codigo);

        const url = "/Escuela_BHR/API/participantes/eliminar";
        const resp = await fetch(url, { method: 'POST', body });
        const data = await resp.json();
        const { codigo, mensaje } = data;

        if (codigo == 1) {
            Swal.fire({
                icon: 'success',
                title: '¡Eliminado!',
                text: mensaje + ' Las posiciones han sido recalculadas.',
                timer: 3000,
                showConfirmButton: false
            });
            buscar();
        } else {
            Toast.fire({
                icon: 'error',
                title: mensaje
            });
        }
    } catch (err) {
        console.error(err);
        Toast.fire({
            icon: 'error',
            title: 'Error al eliminar el participante'
        });
    }
};

const cancelar = () => {
    ocultarFormulario();
    formulario.reset();
    btnGuardar.parentElement.style.display = '';
    btnModificar.parentElement.style.display = 'none';
    btnCancelar.parentElement.style.display = 'none';
    limpiarPosicionEstimada();
    ocultarCamposCertificado();
};

formulario.addEventListener('submit', guardar);
btnCancelar.addEventListener('click', cancelar);
btnModificar.addEventListener('click', modificar);
datatable.on('click', '.modificar', traerDatos);
datatable.on('click', '.eliminar', eliminar);

buscar();