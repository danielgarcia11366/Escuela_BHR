<?php
require_once __DIR__ . '/../includes/app.php';

use Controllers\AlumnosController;
use MVC\Router;
use Controllers\AppController;
use Controllers\CursosController;
use Controllers\InicioController;
use Controllers\PersonalController;
use Controllers\PromocionesController;
use Controllers\PruebaController;
use Model\Promociones;

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

//PROMOCIONES
$router->get('/promociones', [PromocionesController::class, 'index']);
$router->get('/API/promociones/buscar', [PromocionesController::class, 'buscarAPI']);
$router->post('/API/promociones/guardar', [PromocionesController::class, 'guardarAPI']);
$router->post('/API/promociones/modificar', [PromocionesController::class, 'modificarAPI']);
$router->post('/API/promociones/eliminar', [PromocionesController::class, 'eliminarAPI']);


//PERSONAL
$router->get('/personal', [PersonalController::class, 'index']);
$router->get('/API/personal/buscar', [PersonalController::class, 'buscarAPI']);
$router->post('/API/personal/guardar', [PersonalController::class, 'guardarAPI']);
$router->post('/API/personal/modificar', [PersonalController::class, 'modificarAPI']);
$router->post('/API/personal/eliminar', [PersonalController::class, 'eliminarAPI']);
// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();
