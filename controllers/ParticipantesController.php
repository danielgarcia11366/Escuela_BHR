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
        $promociones = Promociones::obtenerPromocionesConDetalles();
        $persona = Personal::obtenerPersonal();

        $router->render('participantes/index', [
            'promociones' => $promociones,
            'persona' => $persona,
        ]);
    }

    public static function guardarAPI()
    {
        header('Content-Type: application/json; charset=UTF-8');

        try {
            // ✅ Validación 1: Campos requeridos
            if (empty($_POST['par_promocion']) || empty($_POST['par_catalogo'])) {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'La promoción y el alumno son obligatorios.',
                    'campo' => empty($_POST['par_promocion']) ? 'par_promocion' : 'par_catalogo'
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            // ✅ Validación 2: Alumno ya registrado en la promoción
            if (Participantes::existeEnPromocion($_POST['par_catalogo'], $_POST['par_promocion'])) {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Este alumno ya está registrado en esta promoción. No puede estar inscrito dos veces en el mismo curso.',
                    'campo' => 'par_catalogo'
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            // ✅ Validación 3: Número de certificado duplicado
            if (
                !empty($_POST['par_certificado_numero']) &&
                Participantes::existeCertificado($_POST['par_certificado_numero'])
            ) {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El número de certificado ya existe. Por favor ingresa uno diferente.',
                    'campo' => 'par_certificado_numero'
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            // ✅ Validación 4: Posición duplicada en la promoción
            if (
                !empty($_POST['par_posicion']) &&
                Participantes::existePosicionEnPromocion($_POST['par_promocion'], $_POST['par_posicion'])
            ) {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Esta posición ya está ocupada por otro alumno en esta promoción.',
                    'campo' => 'par_posicion'
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            // ✅ Validación 5: Calificación en rango válido
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

            // ✅ Validación 6: Posición debe ser positiva
            if (!empty($_POST['par_posicion'])) {
                $posicion = intval($_POST['par_posicion']);
                if ($posicion < 1) {
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => 'La posición debe ser un número mayor a 0.',
                        'campo' => 'par_posicion'
                    ], JSON_UNESCAPED_UNICODE);
                    return;
                }
            }

            // ✅ Validación 7: Fecha de certificado no puede ser futura
            if (!empty($_POST['par_certificado_fecha'])) {
                $fecha_cert = strtotime($_POST['par_certificado_fecha']);
                $fecha_hoy = strtotime(date('Y-m-d'));

                if ($fecha_cert > $fecha_hoy) {
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => 'La fecha del certificado no puede ser posterior a la fecha actual.',
                        'campo' => 'par_certificado_fecha'
                    ], JSON_UNESCAPED_UNICODE);
                    return;
                }
            }

            // Si todas las validaciones pasan, guardar
            $participante = new Participantes($_POST);
            $resultado = $participante->crear();

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Participante registrado exitosamente',
                'debug' => $resultado
            ], JSON_UNESCAPED_UNICODE);
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al guardar participante',
                'error' => $e->getMessage(),
                'linea' => $e->getLine(),
                'archivo' => $e->getFile()
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    public static function buscarAPI()
    {
        header('Content-Type: application/json; charset=UTF-8');

        try {
            $participantes = Participantes::obtenerParticipantes();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Datos encontrados',
                'detalle' => '',
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

    public static function modificarAPI()
    {
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

            // ✅ MISMAS VALIDACIONES QUE EN GUARDAR, pero excluyendo el registro actual

            // Validación 1: Campos requeridos
            if (empty($_POST['par_promocion']) || empty($_POST['par_catalogo'])) {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'La promoción y el alumno son obligatorios.',
                    'campo' => empty($_POST['par_promocion']) ? 'par_promocion' : 'par_catalogo'
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            // Validación 2: Alumno ya registrado en la promoción (excluyendo este registro)
            if (Participantes::existeEnPromocion($_POST['par_catalogo'], $_POST['par_promocion'], $id)) {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Este alumno ya está registrado en esta promoción.',
                    'campo' => 'par_catalogo'
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            // Validación 3: Número de certificado duplicado (excluyendo este registro)
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

            // Validación 4: Posición duplicada (excluyendo este registro)
            if (
                !empty($_POST['par_posicion']) &&
                Participantes::existePosicionEnPromocion($_POST['par_promocion'], $_POST['par_posicion'], $id)
            ) {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Esta posición ya está ocupada por otro alumno.',
                    'campo' => 'par_posicion'
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            // Validación 5: Calificación válida
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

            // Validación 6: Posición positiva
            if (!empty($_POST['par_posicion'])) {
                $posicion = intval($_POST['par_posicion']);
                if ($posicion < 1) {
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => 'La posición debe ser mayor a 0.',
                        'campo' => 'par_posicion'
                    ], JSON_UNESCAPED_UNICODE);
                    return;
                }
            }

            // Validación 7: Fecha del certificado
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

            // Limpiar valores vacíos
            $_POST['par_calificacion'] = $_POST['par_calificacion'] ?? null;
            $_POST['par_posicion'] = $_POST['par_posicion'] ?? null;
            $_POST['par_certificado_numero'] = htmlspecialchars($_POST['par_certificado_numero'] ?? '');
            $_POST['par_certificado_fecha'] = $_POST['par_certificado_fecha'] ?? null;
            $_POST['par_estado'] = $_POST['par_estado'] ?? 'C';
            $_POST['par_observaciones'] = htmlspecialchars($_POST['par_observaciones'] ?? '');

            $participante->sincronizar($_POST);
            $resultado = $participante->actualizar();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Participante modificado exitosamente',
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

            $participante->eliminar();

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
