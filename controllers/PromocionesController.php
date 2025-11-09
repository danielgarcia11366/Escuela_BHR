<?php

namespace Controllers;

use Exception;
use Model\Promociones;
use Model\Cursos;
use Model\Paises;
use Model\Instituciones;
use MVC\Router;

class PromocionesController
{
    public static function index(Router $router)
    {
        // ⭐ PROTEGER LA VISTA
        isAuth();
        hasPermission(['ADMINISTRADOR']);

        $instituciones = Instituciones::obtenerinstitucionQuery();
        $cursos = Cursos::obtenerCursos1();
        $paises = Paises::obtenerPaisesOrdenados();

        $router->render('promociones/index', [
            'instituciones' => $instituciones,
            'cursos' => $cursos,
            'paises' => $paises
        ]);
    }

    public static function guardarAPI()
    {
        // ⭐ PROTEGER LA API
        isAuthApi();
        hasPermissionApi(['ADMINISTRADOR']);

        header('Content-Type: application/json; charset=utf-8');

        try {
            $promocion = new Promociones($_POST);
            $resultado = $promocion->crear();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Promoción guardada exitosamente'
            ], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al guardar la promoción',
                'detalle' => $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    public static function buscarAPI()
    {
        // ⭐ PROTEGER LA API
        isAuthApi();
        hasPermissionApi(['ADMINISTRADOR', 'INSTRUCTOR']);

        header('Content-Type: application/json; charset=utf-8');

        try {
            $promociones = Promociones::obtenerPromocionesConDetalles();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Datos encontrados',
                'datos' => $promociones
            ], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al buscar promociones',
                'detalle' => $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    public static function modificarAPI()
    {
        // ⭐ PROTEGER LA API
        isAuthApi();
        hasPermissionApi(['ADMINISTRADOR']);

        header('Content-Type: application/json; charset=utf-8');

        try {
            $id = filter_var($_POST['pro_codigo'], FILTER_SANITIZE_NUMBER_INT);

            if (!$id) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'ID de promoción no válido'
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            $promocion = Promociones::find($id);

            if (!$promocion) {
                http_response_code(404);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Promoción no encontrada'
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            $promocion->sincronizar($_POST);
            $resultado = $promocion->actualizar();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Promoción modificada exitosamente'
            ], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al modificar la promoción',
                'detalle' => $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    public static function eliminarAPI()
    {
        // ⭐ PROTEGER LA API
        isAuthApi();
        hasPermissionApi(['ADMINISTRADOR']);

        header('Content-Type: application/json; charset=utf-8');

        try {
            $id = filter_var($_POST['pro_codigo'], FILTER_SANITIZE_NUMBER_INT);

            if (!$id) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'ID de promoción no válido'
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            $promocion = Promociones::find($id);

            if (!$promocion) {
                http_response_code(404);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Promoción no encontrada'
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            $resultado = $promocion->eliminar();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Promoción eliminada exitosamente'
            ], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al eliminar la promoción',
                'detalle' => $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
    }
}
