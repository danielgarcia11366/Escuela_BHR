<?php

namespace Controllers;

use Exception;
use MVC\Router;



class EstadisticasController
{
    public static function index(Router $router)
    {
        // ⭐ PROTEGER LA VISTA
        isAuth();
        hasPermission(['ADMINISTRADOR', 'INSTRUCTOR']);

        $router->render('estadisticas/index', []);
    }
}
