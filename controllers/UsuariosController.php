<?php

namespace Controllers;

use Exception;
use Model\Usuario;
use MVC\Router;

class UsuariosController
{
    /**
     * Muestra la vista para gestionar usuarios (solo admin)
     */
    public static function index(Router $router)
    {
        // Verificar que esté autenticado
        isAuth();

        // Verificar que sea administrador
        hasPermission(['ADMINISTRADOR']);

        // Renderizar vista
        $router->render('usuarios/index', []);
    }

    /**
     * API para crear un nuevo usuario (solo admin)
     */
    public static function crearAPI()
    {
        header("Content-type:application/json; charset=utf-8");

        // Verificar que esté autenticado y sea admin
        isAuth();
        hasPermission(['ADMINISTRADOR']);

        // Sanitizar datos
        $_POST['usu_nombre'] = htmlspecialchars($_POST['usu_nombre']);
        $_POST['usu_catalogo'] = filter_var($_POST['usu_catalogo'], FILTER_SANITIZE_NUMBER_INT);
        $_POST['usu_password'] = htmlspecialchars($_POST['usu_password']);
        $_POST['usu_password2'] = htmlspecialchars($_POST['usu_password2']);

        // Validar que las contraseñas coincidan
        if ($_POST['usu_password'] != $_POST['usu_password2']) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Las Contraseñas no Coinciden',
                'detalle' => 'Verifique las contraseñas ingresadas',
            ]);
            exit;
        }

        try {
            // Encriptar contraseña
            $_POST['usu_password'] = password_hash($_POST['usu_password'], PASSWORD_DEFAULT);
            $usuario = new Usuario($_POST);

            // Verificar si ya existe
            if ($usuario->validarUsuarioExistente()) {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Ya existe un Usuario Registrado',
                    'detalle' => 'Verifique la información y catálogo',
                ]);
                exit;
            }

            // Crear usuario
            $usuario->crear();

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Usuario Creado Exitosamente'
            ]);
            exit;
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al Crear Usuario',
                'detalle' => $e->getMessage(),
            ]);
            exit;
        }
    }

    /**
     * API para buscar/listar usuarios
     */
    public static function buscarAPI()
    {
        header("Content-type:application/json; charset=utf-8");

        // Verificar autenticación y permisos
        isAuth();
        hasPermission(['ADMINISTRADOR']);

        try {
            // Obtener todos los usuarios activos
            $sql = "SELECT usu_id, usu_nombre, usu_catalogo, usu_situacion 
                    FROM usuario 
                    WHERE usu_situacion = 1 
                    ORDER BY usu_nombre ASC";

            $usuarios = Usuario::fetchArray($sql);

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Datos encontrados',
                'datos' => $usuarios
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al buscar usuarios',
                'detalle' => $e->getMessage()
            ]);
        }
    }
}
