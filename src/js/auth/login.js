import { Toast, validarFormulario } from '../funciones';
import Swal from 'sweetalert2';

const formLogin = document.getElementById('formLogin');

const login = async (e) => {
    e.preventDefault();

    // Usando tu función validarFormulario que recibe excepciones
    if (!validarFormulario(formLogin, [])) {
        Toast.fire({
            icon: 'warning',
            title: 'Debe llenar todos los campos'
        });
        return;
    }

    // Mostrar loader
    Swal.fire({
        title: 'Iniciando sesión...',
        allowOutsideClick: false,
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
            Toast.fire({
                icon: 'success',
                title: data.mensaje
            });

            setTimeout(() => {
                location.href = '/Escuela_BHR/menu';
            }, 1500);
        } else {
            Toast.fire({
                icon: 'error',
                title: data.mensaje,
                text: data.detalle || ''
            });
        }
    } catch (error) {
        Swal.close();
        console.error(error);
        Toast.fire({
            icon: 'error',
            title: 'Error al iniciar sesión',
            text: 'Por favor, intente nuevamente'
        });
    }
};

formLogin.addEventListener('submit', login);