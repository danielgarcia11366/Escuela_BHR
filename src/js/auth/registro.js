import { Toast, validarFormulario } from '../funciones';
import Swal from 'sweetalert2';

const formRegistro = document.getElementById('formRegistro');

const registro = async (e) => {
    e.preventDefault();

    // Usando tu función validarFormulario
    if (!validarFormulario(formRegistro, [])) {
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

    // Mostrar loader
    Swal.fire({
        title: 'Registrando usuario...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    try {
        const body = new FormData(formRegistro);
        const url = '/Escuela_BHR/API/registro';

        const config = {
            method: 'POST',
            body
        };

        const respuesta = await fetch(url, config);
        const data = await respuesta.json();

        Swal.close();

        if (data.codigo == 1) {
            Swal.fire({
                icon: 'success',
                title: '¡Registro exitoso!',
                text: data.mensaje,
                confirmButtonText: 'Ir al login'
            }).then(() => {
                location.href = '/Escuela_BHR/';
            });
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
            title: 'Error al registrar usuario',
            text: 'Por favor, intente nuevamente'
        });
    }
};

formRegistro.addEventListener('submit', registro);