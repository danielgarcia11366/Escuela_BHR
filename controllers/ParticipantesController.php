<?php

namespace Controllers;

use Exception;
use Model\Participantes;
use Model\Personal;
use Model\Promociones;
use MVC\Router;

class ParticipantesController
{


    public static function index(Router $router)
    {
        $promociones = Promociones::obtenerPromocionesConDetalles();
        $persona = Personal::obtenerPersonal();
        //$tipos = Tipos::obtenerTiposConQuery();

        $router->render('participantes/index', [
            'promociones' => $promociones,
            'persona' => $persona,
            //'tipos' => $tipos
        ]);
    }




    // ✅ Guardar nuevo participante
    public static function guardarAPI()
    {
        header('Content-Type: application/json; charset=UTF-8');

        // Sanitización de campos
        $_POST['par_calificacion'] = $_POST['par_calificacion'] ?? null;
        $_POST['par_posicion'] = $_POST['par_posicion'] ?? null;
        $_POST['par_certificado_numero'] = htmlspecialchars($_POST['par_certificado_numero'] ?? '');
        $_POST['par_certificado_fecha'] = $_POST['par_certificado_fecha'] ?? null;
        $_POST['par_estado'] = $_POST['par_estado'] ?? 'C';
        $_POST['par_observaciones'] = htmlspecialchars($_POST['par_observaciones'] ?? '');

        try {
            $participante = new Participantes($_POST);
            $resultado = $participante->crear();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Participante registrado exitosamente',
            ], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al registrar participante',
                'detalle' => $e->getMessage(),
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    // ✅ Buscar todos los participantes
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

    // ✅ Modificar un participante
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
                'mensaje' => 'Datos del participante modificados exitosamente',
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

    // ✅ Eliminar un participante
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
