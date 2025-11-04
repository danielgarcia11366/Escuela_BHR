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
        header('Content-Type: application/json; charset=utf-8');
        try {
            $promocion = new Promociones($_POST);
            $resultado = $promocion->crear();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Promoción guardada exitosamente'
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al guardar la promoción',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function buscarAPI()
    {
        header('Content-Type: application/json; charset=utf-8');
        try {
            $promociones = Promociones::obtenerPromocionesConDetalles();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Datos encontrados',
                'datos' => $promociones
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al buscar promociones',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function modificarAPI()
    {
        header('Content-Type: application/json; charset=utf-8');
        try {
            $id = $_POST['pro_codigo'];
            $promocion = Promociones::find($id);
            $promocion->sincronizar($_POST);
            $resultado = $promocion->actualizar();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Promoción modificada exitosamente'
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al modificar la promoción',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function eliminarAPI()
    {
        header('Content-Type: application/json; charset=utf-8');
        try {
            $id = $_POST['pro_codigo'];
            $promocion = Promociones::find($id);
            $resultado = $promocion->eliminar();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Promoción eliminada exitosamente'
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al eliminar la promoción',
                'detalle' => $e->getMessage()
            ]);
        }
    }
}
