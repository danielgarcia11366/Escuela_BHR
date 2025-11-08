<?php

function debuguear($variable)
{
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

function s($html)
{
    $s = htmlspecialchars($html);
    return $s;
}

// Verificar si NO está autenticado (para login/registro)
function isNotAuth()
{
    if (isset($_SESSION['user'])) {
        header('Location: /Escuela_BHR/menu');
        exit;
    }
}

// Verificar si está autenticado
function isAuth()
{
    if (!isset($_SESSION['user'])) {
        header('Location: /Escuela_BHR/');
        exit;
    }
}

// Verificar permisos de usuario
function hasPermission(array $permisos)
{
    $comprobaciones = [];
    foreach ($permisos as $permiso) {
        $comprobaciones[] = isset($_SESSION[$permiso]) ? true : false;
    }

    if (array_search(true, $comprobaciones) === false) {
        header('Location: /Escuela_BHR/logout');
        exit;
    }
}

// Para APIs
function isAuthApi()
{
    getHeadersApi();
    if (!isset($_SESSION['user'])) {
        echo json_encode([
            "mensaje" => "No está autenticado",
            "codigo" => 4,
        ]);
        exit;
    }
}

function hasPermissionApi(array $permisos)
{
    getHeadersApi();
    $comprobaciones = [];
    foreach ($permisos as $permiso) {
        $comprobaciones[] = isset($_SESSION[$permiso]) ? true : false;
    }

    if (array_search(true, $comprobaciones) === false) {
        echo json_encode([
            "mensaje" => "No tiene permisos",
            "codigo" => 4,
        ]);
        exit;
    }
}

function getHeadersApi()
{
    return header("Content-type:application/json; charset=utf-8");
}

function asset($ruta)
{
    return "/Escuela_BHR/public/" . $ruta;
}
