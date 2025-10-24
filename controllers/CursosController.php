<?php

namespace Controllers;

use Exception;
use Model\Cursos;
use MVC\Router;

class CursosController
{
    public static function index(Router $router)
    {
        $router->render('cursos/index', [], 'layouts/menu');
    }

    public static function guardarAPI()
    {
        // Sanitizar datos
        $_POST['cur_nombre'] = htmlspecialchars($_POST['cur_nombre']);
        $_POST['cur_nombre_corto'] = htmlspecialchars($_POST['cur_nombre_corto']);
        $_POST['cur_descripcion'] = htmlspecialchars($_POST['cur_descripcion'] ?? '');

        try {
            $curso = new Cursos($_POST);
            $resultado = $curso->crear();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Curso registrado exitosamente',
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al registrar Curso',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function buscarAPI()
    {
        try {
            $cursos = Cursos::obtenerCursos();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Datos encontrados',
                'detalle' => '',
                'datos' => $cursos
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al buscar Cursos',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function modificarAPI()
    {
        // Sanitizar datos
        $_POST['cur_nombre'] = htmlspecialchars($_POST['cur_nombre']);
        $_POST['cur_nombre_corto'] = htmlspecialchars($_POST['cur_nombre_corto']);
        $_POST['cur_descripcion'] = htmlspecialchars($_POST['cur_descripcion'] ?? '');

        $id = filter_var($_POST['cur_codigo'], FILTER_SANITIZE_NUMBER_INT);

        if (!$id) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'ID de curso no válido',
            ]);
            return;
        }

        try {
            $curso = Cursos::find($id);

            if (!$curso) {
                http_response_code(404);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Curso no encontrado',
                ]);
                return;
            }

            $curso->sincronizar($_POST);
            $curso->actualizar();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Datos del Curso Modificados Exitosamente',
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al Modificar Datos',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function eliminarAPI()
    {
        $id = filter_var($_POST['cur_codigo'], FILTER_SANITIZE_NUMBER_INT);

        if (!$id) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'ID de curso no válido',
            ]);
            return;
        }

        try {
            $curso = Cursos::find($id);

            if (!$curso) {
                http_response_code(404);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Curso no encontrado',
                ]);
                return;
            }

            $curso->eliminar();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Curso Eliminado Exitosamente',
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al Eliminar Curso',
                'detalle' => $e->getMessage(),
            ]);
        }
    }
}
