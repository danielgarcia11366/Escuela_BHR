<?php

namespace Controllers;

use Exception;
use Model\Promociones;
use MVC\Router;


class PromocionesController
{
    public static function index(Router $router)
    {
        $curso = Promociones::find(2);
        $router->render('promociones/index', [
            'curso' => $curso
        ], 'layouts/menu');
    }

    public static function guardarAPI()
    {
        if (empty($_POST)) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'No se recibieron datos',
            ]);
            return;
        }

        $_POST['cur_nombre'] = htmlspecialchars($_POST['cur_nombre']);
        $_POST['cur_desc_lg'] = htmlspecialchars($_POST['cur_desc_lg'] ?? '');
        $_POST['cur_duracion'] = (int)($_POST['cur_duracion'] ?? 0);

        // NO enviar cur_fec_creacion, dejar que use el DEFAULT TODAY
        unset($_POST['cur_fec_creacion']);

        try {
            $Curso = new Cursos($_POST);
            $resultado = $Curso->crear();

            if ($resultado) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Curso Registrado Exitosamente',
                ]);
            } else {
                throw new Exception('No se pudo guardar en la base de datos');
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al registrar curso',
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
                'mensaje' => 'Error al buscar Profesores',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function modificarAPI()
    {
        $_POST['cur_nombre'] = htmlspecialchars($_POST['cur_nombre']);
        $id = filter_var($_POST['cur_codigo'], FILTER_SANITIZE_NUMBER_INT);

        try {
            $cursos = Cursos::find($id);
            $cursos->sincronizar($_POST);
            $cursos->actualizar();
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
        // Verificar que lleguen datos
        if (empty($_POST)) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'No se recibieron datos',
            ]);
            return;
        }

        // Verificar que existe el campo cur_codigo
        if (!isset($_POST['cur_codigo'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Código de curso requerido',
            ]);
            return;
        }

        $id = filter_var($_POST['cur_codigo'], FILTER_SANITIZE_NUMBER_INT);

        if (!$id || $id <= 0) {
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

            // Verificar si la eliminación fue exitosa
            $resultado = $curso->eliminar();

            if ($resultado) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Curso Eliminado Exitosamente',
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'No se pudo eliminar el curso',
                ]);
            }
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
