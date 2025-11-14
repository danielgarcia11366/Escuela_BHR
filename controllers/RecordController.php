<?php

namespace Controllers;

use Exception;
use MVC\Router;



class RecordController
{
    public static function index(Router $router)
    {
        // ⭐ PROTEGER LA VISTA
        isAuth();
        hasPermission(['ADMINISTRADOR', 'INSTRUCTOR']);

        $router->render('record/index', []);
    }
}
