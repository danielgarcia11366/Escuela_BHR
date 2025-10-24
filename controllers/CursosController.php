<?php

namespace Controllers;

use Exception;
use Model\Cursos;
use Model\Instituciones;
use Model\Niveles;
use Model\Tipos;
use MVC\Router;

class CursosController
{
    public static function index(Router $router)
    {
        // Usar el método del modelo para obtener los tutores
        $instituciones = Instituciones::obtenerinstitucionQuery();
        $niveles = Niveles::obtenerNivelesConQuery();
        $tipos = Tipos::obtenerTiposConQuery();
        $instituciones = Instituciones::obtenerinstitucionQuery();

        // Pasar los tutores a la vista
        $router->render('cursos/index', [
            'instituciones' => $instituciones,
            'niveles' => $niveles,
            'tipos' => $tipos
        ]);
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
            $cursos = Cursos::obtenerCursos1();

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
        // DEBUG: Ver qué datos llegan
        error_log("POST recibido: " . print_r($_POST, true));

        // Sanitizar datos
        $_POST['cur_nombre'] = htmlspecialchars($_POST['cur_nombre']);
        $_POST['cur_nombre_corto'] = htmlspecialchars($_POST['cur_nombre_corto']);
        $_POST['cur_descripcion'] = htmlspecialchars($_POST['cur_descripcion'] ?? '');

        $id = filter_var($_POST['cur_codigo'], FILTER_SANITIZE_NUMBER_INT);

        error_log("ID extraído: " . $id);

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

            error_log("Curso encontrado: " . print_r($curso, true));

            if (!$curso) {
                http_response_code(404);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Curso no encontrado',
                ]);
                return;
            }

            // Sincronizar los datos del POST con el objeto
            $curso->sincronizar($_POST);

            error_log("Curso después de sincronizar: " . print_r($curso, true));

            // Actualizar en base de datos
            $resultado = $curso->actualizar();

            error_log("Resultado de actualización: " . print_r($resultado, true));

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Datos del Curso Modificados Exitosamente',
            ]);
        } catch (Exception $e) {
            error_log("Error en modificar: " . $e->getMessage());
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
