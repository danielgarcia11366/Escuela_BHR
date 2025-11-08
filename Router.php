<?php

namespace MVC;

class Router
{
    public $getRoutes = [];
    public $postRoutes = [];
    protected $base = '';

    public function get($url, $fn)
    {
        $this->getRoutes[$this->base . $url] = $fn;
    }

    public function post($url, $fn)
    {
        $this->postRoutes[$this->base . $url] = $fn;
    }

    public function setBaseURL($base)
    {
        $this->base = $base;
    }

    public function comprobarRutas()
    {
        $currentUrl = $_SERVER['REQUEST_URI'] ? str_replace("?" . $_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']) : $this->base . '/';
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'GET') {
            $fn = $this->getRoutes[$currentUrl] ?? null;
        } else {
            $fn = $this->postRoutes[$currentUrl] ?? null;
        }

        if ($fn) {
            call_user_func($fn, $this);
        } else {
            if (empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
                $this->render('pages/notfound');
            } else {
                getHeadersApi();
                echo json_encode(["ERROR" => "PÁGINA NO ENCONTRADA"]);
            }
        }
    }

    // MÉTODO ACTUALIZADO: Ahora acepta un tercer parámetro para el layout
    public function render($view, $datos = [], $layout = 'layout')
    {
        foreach ($datos as $key => $value) {
            $$key = $value;
        }

        ob_start();
        include_once __DIR__ . "/views/$view.php";
        $contenido = ob_get_clean();

        // Usa el layout especificado o 'layout' por defecto
        include_once __DIR__ . "/views/layouts/$layout.php";
    }

    public function load($view, $datos = [])
    {
        foreach ($datos as $key => $value) {
            $$key = $value;
        }

        ob_start();
        include_once __DIR__ . "/views/$view.php";
        $contenido = ob_get_clean();
        return $contenido;
    }

    public function printPDF($ruta)
    {
        header("Content-type: application/pdf");
        header("Content-Disposition: inline; filename=filename.pdf");
        @readfile(__DIR__ . '/storage/' . $ruta);
    }
}
