import { Toast, validarFormulario } from '../funciones';
import Swal from 'sweetalert2';

const formLogin = document.getElementById('formLogin');

const login = async (e) => {
    e.preventDefault();

    if (!validarFormulario(formLogin, [])) {
        Toast.fire({
            icon: 'warning',
            title: 'Debe llenar todos los campos'
        });
        return;
    }

    // ⭐ CAMBIO AQUÍ: Loader más moderno
    Swal.fire({
        title: 'Iniciando sesión...',
        html: 'Por favor espere',
        icon: 'info',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        draggable: true,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    try {
        const body = new FormData(formLogin);
        const url = '/Escuela_BHR/API/login';

        const config = {
            method: 'POST',
            body
        };

        const respuesta = await fetch(url, config);
        const data = await respuesta.json();

        Swal.close();

        if (data.codigo == 1) {
            // ⭐ ÉXITO: Swal draggable
            Swal.fire({
                title: '¡Bienvenido!',
                html: data.mensaje,
                icon: 'success',
                draggable: true,
                confirmButtonText: 'Continuar',
                timer: 2000,
                timerProgressBar: true
            }).then(() => {
                location.href = '/Escuela_BHR/menu';
            });
        } else {
            // ⭐ ERROR: Swal draggable
            Swal.fire({
                title: 'Error de acceso',
                html: data.mensaje,
                icon: 'error',
                draggable: true,
                confirmButtonText: 'Intentar de nuevo'
            });
        }
    } catch (error) {
        Swal.close();
        console.error(error);

        // ⭐ ERROR DE RED: Swal draggable
        Swal.fire({
            title: 'Error de conexión',
            html: 'No se pudo conectar con el servidor',
            icon: 'error',
            draggable: true,
            confirmButtonText: 'Reintentar'
        });
    }
};

formLogin.addEventListener('submit', login);