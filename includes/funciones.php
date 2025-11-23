<?php

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function numeroOrdinalCorto($num) {
    // En español el ordinal corto siempre usa "°" (masculino) o "ª" (femenino)
    return $num . '°';
}


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
        // Redirigir a página de acceso denegado
        header('Location: /Escuela_BHR/forbidden');
        exit;
    }
}

// Para APIs - Verificar autenticación
function isAuthApi()
{
    getHeadersApi();

    if (!isset($_SESSION['user'])) {
        http_response_code(401);
        echo json_encode([
            "codigo" => 4,
            "mensaje" => "No está autenticado",
        ]);
        exit;
    }
}

// Para APIs - Verificar permisos
function hasPermissionApi(array $permisos)
{
    getHeadersApi();

    $comprobaciones = [];
    foreach ($permisos as $permiso) {
        $comprobaciones[] = isset($_SESSION[$permiso]) ? true : false;
    }

    if (array_search(true, $comprobaciones) === false) {
        http_response_code(403);
        echo json_encode([
            "codigo" => 4,
            "mensaje" => "No tiene permisos para realizar esta acción",
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
