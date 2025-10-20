<?php

namespace Controllers;

use Exception;
use Model\Prueba;
use MVC\Router;


class PruebaController
{
    public static function index(Router $router)
    {
        $prueba = Prueba::find(2);
        $router->render('prueba/index', [
            'prueba' => $prueba
        ], 'layouts/menu');
    }

}
