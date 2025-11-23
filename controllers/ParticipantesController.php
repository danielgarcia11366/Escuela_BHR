<?php

namespace Controllers;

use Exception;
use Model\Participantes;
use Model\Personal;
use Model\Promociones;
use MVC\Router;
use Throwable;

class ParticipantesController
{
    public static function index(Router $router)
    {
        isAuth();
        hasPermission(['ADMINISTRADOR']);

        $promociones = Promociones::obtenerPromocionesConDetalles();
        $persona = Personal::obtenerPersonal();

        $router->render('participantes/index', [
            'promociones' => $promociones,
            'persona' => $persona,
        ]);
    }

    /**
     * 🆕 API PARA CALCULAR POSICIÓN ESTIMADA (Frontend en tiempo real)
     */
    public static function calcularPosicionAPI()
    {
        isAuthApi();
        hasPermissionApi(['ADMINISTRADOR', 'INSTRUCTOR']);
        
        header('Content-Type: application/json; charset=UTF-8');

        try {
            $promocion = $_POST['par_promocion'] ?? null;
            $calificacion = $_POST['par_calificacion'] ?? null;

            if (empty($promocion) || empty($calificacion)) {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Faltan datos para calcular la posición'
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            $resultado = Participantes::obtenerPosicionEstimada($promocion, $calificacion);

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Posición calculada',
                'datos' => $resultado
            ], JSON_UNESCAPED_UNICODE);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al calcular posición',
                'detalle' => $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    public static function guardarAPI()
    {
        isAuthApi();
        hasPermissionApi(['ADMINISTRADOR']);

        header('Content-Type: application/json; charset=UTF-8');

        try {
            // Validación 1: Campos requeridos
            if (empty($_POST['par_promocion']) || empty($_POST['par_catalogo'])) {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'La promoción y el alumno son obligatorios.',
                    'campo' => empty($_POST['par_promocion']) ? 'par_promocion' : 'par_catalogo'
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            // Validación 2: Alumno ya registrado en la promoción
            if (Participantes::existeEnPromocion($_POST['par_catalogo'], $_POST['par_promocion'])) {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Este alumno ya está registrado en esta promoción.',
                    'campo' => 'par_catalogo'
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            // Validación 3: Número de certificado duplicado
            if (
                !empty($_POST['par_certificado_numero']) &&
                Participantes::existeCertificado($_POST['par_certificado_numero'])
            ) {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El número de certificado ya existe.',
                    'campo' => 'par_certificado_numero'
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            // Validación 4: Calificación en rango válido
            if (!empty($_POST['par_calificacion'])) {
                $calificacion = floatval($_POST['par_calificacion']);
                if ($calificacion < 0 || $calificacion > 100) {
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => 'La calificación debe estar entre 0 y 100.',
                        'campo' => 'par_calificacion'
                    ], JSON_UNESCAPED_UNICODE);
                    return;
                }
            }

            // Validación 5: Fecha de certificado no puede ser futura
            if (!empty($_POST['par_certificado_fecha'])) {
                $fecha_cert = strtotime($_POST['par_certificado_fecha']);
                $fecha_hoy = strtotime(date('Y-m-d'));

                if ($fecha_cert > $fecha_hoy) {
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => 'La fecha del certificado no puede ser futura.',
                        'campo' => 'par_certificado_fecha'
                    ], JSON_UNESCAPED_UNICODE);
                    return;
                }
            }

            // 🆕 CALCULAR POSICIÓN AUTOMÁTICA (si hay calificación)
            if (!empty($_POST['par_calificacion'])) {
                $_POST['par_posicion'] = Participantes::calcularPosicionAutomatica(
                    $_POST['par_promocion'],
                    $_POST['par_calificacion']
                );
            } else {
                $_POST['par_posicion'] = null; // Sin calificación = sin posición
            }

            // Guardar participante
            $participante = new Participantes($_POST);
            $resultado = $participante->crear();

            // 🆕 RECALCULAR TODAS LAS POSICIONES DE LA PROMOCIÓN
            Participantes::recalcularPosicionesPromocion($_POST['par_promocion']);

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Participante registrado exitosamente',
                'posicion_asignada' => $_POST['par_posicion']
            ], JSON_UNESCAPED_UNICODE);

        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al guardar participante',
                'error' => $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    public static function buscarAPI()
    {
        isAuthApi();
        hasPermissionApi(['ADMINISTRADOR', 'INSTRUCTOR']);

        header('Content-Type: application/json; charset=UTF-8');

        try {
            $participantes = Participantes::obtenerParticipantes();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Datos encontrados',
                'datos' => $participantes
            ], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al buscar participantes',
                'detalle' => $e->getMessage(),
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    public static function buscarPersonalAPI()
    {
        isAuthApi();
        hasPermissionApi(['ADMINISTRADOR', 'INSTRUCTOR']);

        header('Content-Type: application/json; charset=UTF-8');

        try {
            $personal = Participantes::obtenerResumenPersonal();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Datos encontrados',
                'datos' => $personal
            ], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al buscar personal',
                'detalle' => $e->getMessage(),
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    public static function modificarAPI()
    {
        isAuthApi();
        hasPermissionApi(['ADMINISTRADOR']);

        header('Content-Type: application/json; charset=UTF-8');

        $id = filter_var($_POST['par_codigo'], FILTER_SANITIZE_NUMBER_INT);

        if (!$id) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'ID de participante no válido',
            ], JSON_UNESCAPED_UNICODE);
            return;
        }

        try {
            $participante = Participantes::find($id);

            if (!$participante) {
                http_response_code(404);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Participante no encontrado',
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            // VALIDACIONES (mismas que en guardar, excluyendo el registro actual)
            
            if (empty($_POST['par_promocion']) || empty($_POST['par_catalogo'])) {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'La promoción y el alumno son obligatorios.',
                    'campo' => empty($_POST['par_promocion']) ? 'par_promocion' : 'par_catalogo'
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            if (Participantes::existeEnPromocion($_POST['par_catalogo'], $_POST['par_promocion'], $id)) {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Este alumno ya está registrado en esta promoción.',
                    'campo' => 'par_catalogo'
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            if (
                !empty($_POST['par_certificado_numero']) &&
                Participantes::existeCertificado($_POST['par_certificado_numero'], $id)
            ) {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El número de certificado ya existe.',
                    'campo' => 'par_certificado_numero'
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            if (!empty($_POST['par_calificacion'])) {
                $calificacion = floatval($_POST['par_calificacion']);
                if ($calificacion < 0 || $calificacion > 100) {
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => 'La calificación debe estar entre 0 y 100.',
                        'campo' => 'par_calificacion'
                    ], JSON_UNESCAPED_UNICODE);
                    return;
                }
            }

            if (!empty($_POST['par_certificado_fecha'])) {
                $fecha_cert = strtotime($_POST['par_certificado_fecha']);
                $fecha_hoy = strtotime(date('Y-m-d'));

                if ($fecha_cert > $fecha_hoy) {
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => 'La fecha del certificado no puede ser futura.',
                        'campo' => 'par_certificado_fecha'
                    ], JSON_UNESCAPED_UNICODE);
                    return;
                }
            }

            // 🆕 RECALCULAR POSICIÓN (si hay calificación)
            if (!empty($_POST['par_calificacion'])) {
                $_POST['par_posicion'] = Participantes::calcularPosicionAutomatica(
                    $_POST['par_promocion'],
                    $_POST['par_calificacion'],
                    $id // Excluir este registro del cálculo
                );
            } else {
                $_POST['par_posicion'] = null;
            }

            $participante->sincronizar($_POST);
            $participante->actualizar();

            // 🆕 RECALCULAR TODAS LAS POSICIONES
            Participantes::recalcularPosicionesPromocion($_POST['par_promocion']);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Participante modificado exitosamente',
                'posicion_asignada' => $_POST['par_posicion']
            ], JSON_UNESCAPED_UNICODE);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al modificar participante',
                'detalle' => $e->getMessage(),
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    public static function eliminarAPI()
    {
        isAuthApi();
        hasPermissionApi(['ADMINISTRADOR']);

        header('Content-Type: application/json; charset=UTF-8');

        $id = filter_var($_POST['par_codigo'], FILTER_SANITIZE_NUMBER_INT);

        if (!$id) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'ID de participante no válido',
            ], JSON_UNESCAPED_UNICODE);
            return;
        }

        try {
            $participante = Participantes::find($id);

            if (!$participante) {
                http_response_code(404);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Participante no encontrado',
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            // Guardar el ID de la promoción antes de eliminar
            $promocion_id = $participante->par_promocion;

            $participante->eliminar();

            // 🆕 RECALCULAR POSICIONES después de eliminar
            Participantes::recalcularPosicionesPromocion($promocion_id);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Participante eliminado exitosamente',
            ], JSON_UNESCAPED_UNICODE);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al eliminar participante',
                'detalle' => $e->getMessage(),
            ], JSON_UNESCAPED_UNICODE);
        }
    }
}