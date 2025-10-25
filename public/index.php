<?php
require_once __DIR__ . '/../includes/app.php';

use MVC\Router;
use Controllers\AppController;
use Controllers\CursosController;
use Controllers\InicioController;
use Controllers\PruebaController;

$router = new Router();
$router->setBaseURL('/' . $_ENV['APP_NAME']);

// ❌ COMENTA O ELIMINA ESTA LÍNEA
// $router->get('/', [AppController::class, 'index']);

//prueba
$router->get('/prueba', [PruebaController::class, 'index']);

// ✅ Rutas de Inicio
$router->get('/', [InicioController::class, 'index']);
$router->get('/api/estadisticas', [InicioController::class, 'estadisticasAPI']);

//CURSOS
$router->get('/cursos', [CursosController::class, 'index']);
$router->get('/API/cursos/buscar', [CursosController::class, 'buscarAPI']);
$router->post('/API/cursos/guardar', [CursosController::class, 'guardarAPI']);
$router->post('/API/cursos/modificar', [CursosController::class, 'modificarAPI']);
$router->post('/API/cursos/eliminar', [CursosController::class, 'eliminarAPI']);

// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();
