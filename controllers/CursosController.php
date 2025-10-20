<?php

namespace Controllers;

use Exception;
use Model\Cursos;
use MVC\Router;


class CursosController
{
    public static function index(Router $router)
    {
        $curso = Cursos::find(2);
        $router->render('cursos/index', [
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

        $_POST['codigo_curso'] = htmlspecialchars($_POST['codigo_curso']);
        $_POST['nombre_curso'] = htmlspecialchars($_POST['nombre_curso']);
        $_POST['descripcion'] = htmlspecialchars($_POST['descripcion'] ?? '');
        $_POST['duracion_horas'] = (int)($_POST['duracion_horas'] ?? 0);
        $_POST['requisitos'] = htmlspecialchars($_POST['requisitos'] ?? '');
        $_POST['tipo_curso'] = htmlspecialchars($_POST['tipo_curso'] ?? '');
        $_POST['area_especialidad'] = htmlspecialchars($_POST['area_especialidad'] ?? '');

        // NO enviar fecha_creacion, dejar que use el DEFAULT CURRENT_TIMESTAMP
        unset($_POST['fecha_creacion']);

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
        // Activar errores para depuración
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        try {
            $cursos = Cursos::obtenerCursos();

            // Verificar si hay datos
            if ($cursos === false || $cursos === null) {
                throw new Exception('No se pudieron obtener los cursos de la base de datos');
            }

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Datos encontrados',
                'detalle' => '',
                'datos' => $cursos
            ], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al buscar Cursos',
                'detalle' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    public static function modificarAPI()
    {
        $_POST['nombre_curso'] = htmlspecialchars($_POST['nombre_curso']);
        $_POST['descripcion'] = htmlspecialchars($_POST['descripcion'] ?? '');
        $_POST['requisitos'] = htmlspecialchars($_POST['requisitos'] ?? '');
        $_POST['tipo_curso'] = htmlspecialchars($_POST['tipo_curso'] ?? '');
        $_POST['area_especialidad'] = htmlspecialchars($_POST['area_especialidad'] ?? '');

        $id = filter_var($_POST['id_curso'], FILTER_SANITIZE_NUMBER_INT);

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

        // Verificar que existe el campo id_curso
        if (!isset($_POST['id_curso'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'ID de curso requerido',
            ]);
            return;
        }

        $id = filter_var($_POST['id_curso'], FILTER_SANITIZE_NUMBER_INT);

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
