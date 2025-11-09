<?php
require_once __DIR__ . '/../includes/app.php';

use Controllers\AlumnosController;
use MVC\Router;
use Controllers\AppController;
use Controllers\CursosController;
use Controllers\HistorialController;
use Controllers\InicioController;
use Controllers\LoginController;
use Controllers\ParticipantesController;
use Controllers\PersonalController;
use Controllers\PromocionesController;
use Controllers\PruebaController;
use Model\Promociones;

$router = new Router();
$router->setBaseURL('/' . $_ENV['APP_NAME']);

// RUTAS DE AUTENTICACIÓN
$router->get('/', [LoginController::class, 'login']);
$router->get('/registro', [LoginController::class, 'registro']);
$router->post('/API/login', [LoginController::class, 'loginAPI']);
$router->get('/forbidden', [LoginController::class, 'forbidden']);
//$router->post('/API/registro', [LoginController::class, 'registroAPI']);
$router->get('/logout', [LoginController::class, 'logout']);
// ============================================
// RUTAS PROTEGIDAS (REQUIEREN LOGIN)
// ============================================
$router->get('/menu', [LoginController::class, 'menu']);

// API DE ESTADÍSTICAS (PROTEGIDA)
$router->get('/API/estadisticas', [InicioController::class, 'estadisticasAPI']);


// GESTIÓN DE USUARIOS (Solo Administrador)
$router->get('/usuarios', [LoginController::class, 'usuarios']);
$router->post('/API/usuarios/crear', [LoginController::class, 'crearUsuarioAPI']);


//HISTORIAL
$router->get('/historial', [HistorialController::class, 'index']);


//ESTADISTICAS
$router->get('/estadisticas', [HistorialController::class, 'index']);


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


//PERSONAL
$router->get('/participantes', [ParticipantesController::class, 'index']);
$router->get('/API/participantes/buscar', [ParticipantesController::class, 'buscarAPI']);
$router->post('/API/participantes/guardar', [ParticipantesController::class, 'guardarAPI']);
$router->post('/API/participantes/modificar', [ParticipantesController::class, 'modificarAPI']);
$router->post('/API/participantes/eliminar', [ParticipantesController::class, 'eliminarAPI']);
// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();
