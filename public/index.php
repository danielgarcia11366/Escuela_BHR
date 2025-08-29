<?php 
require_once __DIR__ . '/../includes/app.php';


use MVC\Router;
use Controllers\AppController;
use Controllers\CursosController;
use Controllers\NuevoAlumnoController;
use Controllers\PromocionesController;

$router = new Router();
$router->setBaseURL('/' . $_ENV['APP_NAME']);

$router->get('/', [AppController::class,'index']);

//CURSOS
$router->get('/cursos', [CursosController::class,'index']);
$router->get('/API/cursos/buscar', [CursosController::class,'buscarAPI']);
$router->post('/API/cursos/guardar', [CursosController::class,'guardarAPI']);
$router->post('/API/cursos/modificar', [CursosController::class,'modificarAPI']);
$router->post('/API/cursos/eliminar', [CursosController::class,'eliminarAPI']);

//PROMOCIONES
$router->get('/promociones', [PromocionesController::class,'index']);
$router->get('/API/promociones/buscar', [PromocionesController::class,'buscarAPI']);
$router->post('/API/promociones/guardar', [PromocionesController::class,'guardarAPI']);
$router->post('/API/promociones/modificar', [PromocionesController::class,'modificarAPI']);
$router->post('/API/promociones/eliminar', [PromocionesController::class,'eliminarAPI']);

//NUEVO ALUMNO
$router->get('/nuevoalumno', [NuevoAlumnoController::class,'index']);
$router->get('/API/nuevoalumno/buscar', [NuevoAlumnoController::class,'buscarAPI']);
$router->post('/API/nuevoalumno/guardar', [NuevoAlumnoController::class,'guardarAPI']);
$router->post('/API/nuevoalumno/modificar', [NuevoAlumnoController::class,'modificarAPI']);
$router->post('/API/nuevoalumno/eliminar', [NuevoAlumnoController::class,'eliminarAPI']);

// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();
