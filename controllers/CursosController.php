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
        $instituciones = Instituciones::obtenerinstitucionQuery();
        $niveles = Niveles::obtenerNivelesConQuery();
        $tipos = Tipos::obtenerTiposConQuery();

        $router->render('cursos/index', [
            'instituciones' => $instituciones,
            'niveles' => $niveles,
            'tipos' => $tipos
        ]);
    }

    public static function guardarAPI()
    {
        header('Content-Type: application/json; charset=UTF-8'); // ⭐ AÑADIR ESTA LÍNEA

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
            ], JSON_UNESCAPED_UNICODE); // ⭐ AÑADIR JSON_UNESCAPED_UNICODE
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al registrar Curso',
                'detalle' => $e->getMessage(),
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    public static function buscarAPI()
    {
        header('Content-Type: application/json; charset=UTF-8'); // ⭐ AÑADIR ESTA LÍNEA

        try {
            $cursos = Cursos::obtenerCursos1();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Datos encontrados',
                'detalle' => '',
                'datos' => $cursos
            ], JSON_UNESCAPED_UNICODE); // ⭐ AÑADIR JSON_UNESCAPED_UNICODE
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al buscar Cursos',
                'detalle' => $e->getMessage(),
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    public static function modificarAPI()
    {
        header('Content-Type: application/json; charset=UTF-8'); // ⭐ AÑADIR ESTA LÍNEA

        $_POST['cur_nombre'] = htmlspecialchars($_POST['cur_nombre']);
        $_POST['cur_nombre_corto'] = htmlspecialchars($_POST['cur_nombre_corto']);
        $_POST['cur_descripcion'] = htmlspecialchars($_POST['cur_descripcion'] ?? '');

        $id = filter_var($_POST['cur_codigo'], FILTER_SANITIZE_NUMBER_INT);

        if (!$id) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'ID de curso no válido',
            ], JSON_UNESCAPED_UNICODE);
            return;
        }

        try {
            $curso = Cursos::find($id);

            if (!$curso) {
                http_response_code(404);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Curso no encontrado',
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            $curso->sincronizar($_POST);
            $resultado = $curso->actualizar();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Datos del Curso Modificados Exitosamente',
            ], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al Modificar Datos',
                'detalle' => $e->getMessage(),
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    public static function eliminarAPI()
    {
        header('Content-Type: application/json; charset=UTF-8'); // ⭐ AÑADIR ESTA LÍNEA

        $id = filter_var($_POST['cur_codigo'], FILTER_SANITIZE_NUMBER_INT);

        if (!$id) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'ID de curso no válido',
            ], JSON_UNESCAPED_UNICODE);
            return;
        }

        try {
            $curso = Cursos::find($id);

            if (!$curso) {
                http_response_code(404);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Curso no encontrado',
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            $curso->eliminar();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Curso Eliminado Exitosamente',
            ], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al Eliminar Curso',
                'detalle' => $e->getMessage(),
            ], JSON_UNESCAPED_UNICODE);
        }
    }
}
