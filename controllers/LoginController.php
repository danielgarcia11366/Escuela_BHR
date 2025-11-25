<?php

namespace Controllers;

use Exception;
use Model\Permiso;
use MVC\Router;
use Model\Usuario;

class LoginController
{
    public static function login(Router $router)
    {
        isNotAuth();
        // IMPORTANTE: Especificar el layout de autenticación
        $router->render('auth/login', [], 'layout_auth');
    }

    public static function forbidden(Router $router)
    {
        $router->render('pages/forbidden', []);
    }

    public static function logout()
    {
        isAuth();
        $_SESSION = [];
        session_destroy();
        header('Location: /Escuela_BHR/');
        exit;
    }

    public static function menu(Router $router)
    {
        isAuth();
        hasPermission(['ADMINISTRADOR', 'INSTRUCTOR']);
        // Usa el layout principal por defecto
        $router->render('pages/index', []);
    }

    public static function loginAPI()
    {
        header("Content-type:application/json; charset=utf-8");

        // Validar que lleguen los datos
        if (!isset($_POST['usu_catalogo']) || !isset($_POST['usu_password'])) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Faltan datos',
                'detalle' => 'Catálogo y contraseña son requeridos'
            ]);
            exit;
        }

        $_POST['usu_catalogo'] = filter_var($_POST['usu_catalogo'], FILTER_SANITIZE_NUMBER_INT);
        $_POST['usu_password'] = htmlspecialchars($_POST['usu_password']);

        try {
            $usuario = new Usuario($_POST);

            if (!$usuario->validarUsuarioExistente()) {
                http_response_code(404);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El Usuario No existe',
                    'detalle' => 'Por favor contacte al administrador'
                ]);
                exit;
            }

            $usuarioBD = $usuario->getUsuarioExistente();

            if (!password_verify($_POST['usu_password'], $usuarioBD['usu_password'])) {
                http_response_code(404);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'La Contraseña no Coincide',
                    'detalle' => 'Verifique la contraseña ingresada'
                ]);
                exit;
            }

            // Iniciar sesión
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $_SESSION['user'] = $usuarioBD;

            // Obtener permisos
            $permisos = Permiso::fetchArray("SELECT * FROM permiso 
                INNER JOIN rol ON permiso_rol = rol_id 
                WHERE permiso_usuario = " . $usuarioBD['usu_id']);

            foreach ($permisos as $permiso) {
                $_SESSION[$permiso['rol_nombre_ct']] = 1;
            }

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Bienvenido a la Escuela BHR, ' . $usuarioBD['usu_nombre'],
            ]);
            exit;
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al Ingresar',
                'detalle' => $e->getMessage(),
            ]);
            exit;
        }
    }
}
