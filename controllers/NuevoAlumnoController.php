<?php

namespace Controllers;

use Exception;
use Model\NuevoAlumno;
use MVC\Router;


class NuevoAlumnoController
{
    public static function index(Router $router)
    {
        $nuevoalumno = NuevoAlumno::find(2);
        $router->render('nuevoalumno/index', [
            'nuevoalumno' => $nuevoalumno
        ], 'layouts/menu');
    }

    
    public static function buscarAPI()
    {
        try {
            $nuevoalumno = NuevoAlumno::obteneralumnos();
            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Datos encontrados',
                'detalle' => '',
                'datos' => $nuevoalumno
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

}
