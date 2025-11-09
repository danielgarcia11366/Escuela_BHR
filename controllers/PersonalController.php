<?php

namespace Controllers;

use Exception;
use Model\Personal;
use Model\Armas;
use Model\Grados;
use MVC\Router;

class PersonalController
{
    public static function index(Router $router)
    {
        // ⭐ PROTEGER LA VISTA
        isAuth();
        hasPermission(['ADMINISTRADOR']);

        $armas = Armas::obtenerarmaconQuery();
        $grados = Grados::obtenergradoconQuery();

        $router->render('personal/index', [
            'armas' => $armas,
            'grados' => $grados
        ]);
    }

    public static function guardarAPI()
    {
        // ⭐ PROTEGER LA API
        isAuthApi();
        hasPermissionApi(['ADMINISTRADOR']);

        header('Content-Type: application/json; charset=UTF-8');

        error_log("========== GUARDAR PERSONAL ==========");
        error_log("POST recibido: " . print_r($_POST, true));

        try {
            // Validar campos requeridos
            $camposRequeridos = ['per_catalogo', 'per_nom1', 'per_ape1', 'per_grado', 'per_arma', 'per_sexo', 'per_fec_nac', 'per_nac_lugar', 'per_tipo'];

            foreach ($camposRequeridos as $campo) {
                if (empty($_POST[$campo])) {
                    error_log("❌ Campo requerido faltante: {$campo}");
                    http_response_code(400);
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => "El campo {$campo} es requerido"
                    ], JSON_UNESCAPED_UNICODE);
                    return;
                }
            }

            // Sanitizar datos
            $datos = [
                'per_catalogo' => $_POST['per_catalogo'],
                'per_serie' => $_POST['per_serie'] ?? '',
                'per_grado' => $_POST['per_grado'],
                'per_arma' => $_POST['per_arma'],
                'per_nom1' => htmlspecialchars($_POST['per_nom1']),
                'per_nom2' => htmlspecialchars($_POST['per_nom2'] ?? ''),
                'per_ape1' => htmlspecialchars($_POST['per_ape1']),
                'per_ape2' => htmlspecialchars($_POST['per_ape2'] ?? ''),
                'per_telefono' => $_POST['per_telefono'] ?? '',
                'per_sexo' => $_POST['per_sexo'],
                'per_fec_nac' => $_POST['per_fec_nac'],
                'per_nac_lugar' => htmlspecialchars($_POST['per_nac_lugar']),
                'per_dpi' => $_POST['per_dpi'] ?? '',
                'per_tipo_doc' => $_POST['per_tipo_doc'] ?? 'DPI',
                'per_email' => $_POST['per_email'] ?? '',
                'per_direccion' => htmlspecialchars($_POST['per_direccion'] ?? ''),
                'per_estado' => $_POST['per_estado'] ?? 'A',
                'per_tipo' => $_POST['per_tipo'],
                'observaciones' => htmlspecialchars($_POST['observaciones'] ?? '')
            ];

            error_log("✅ Datos sanitizados correctamente");

            $persona = new Personal($datos);
            error_log("✅ Objeto Personal creado");

            $resultado = $persona->crear();
            error_log("Resultado de crear(): " . print_r($resultado, true));

            if ($resultado && isset($resultado['resultado'])) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Personal registrado exitosamente',
                    'id' => $resultado['id'] ?? null
                ], JSON_UNESCAPED_UNICODE);
            } else {
                throw new Exception('No se pudo insertar el registro en la base de datos');
            }
        } catch (Exception $e) {
            error_log("❌ ERROR en guardarAPI: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());

            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al registrar personal',
                'detalle' => $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    public static function buscarAPI()
    {
        // ⭐ PROTEGER LA API
        isAuthApi();
        hasPermissionApi(['ADMINISTRADOR', 'INSTRUCTOR']);

        header('Content-Type: application/json; charset=UTF-8');

        try {
            $personal = Personal::obtenerPersonal();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Datos encontrados',
                'datos' => $personal
            ], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            error_log("❌ ERROR en buscarAPI: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener datos',
                'detalle' => $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    public static function modificarAPI()
    {
        // ⭐ PROTEGER LA API
        isAuthApi();
        hasPermissionApi(['ADMINISTRADOR']);

        header('Content-Type: application/json; charset=UTF-8');

        error_log("========== MODIFICAR PERSONAL ==========");
        error_log("POST recibido: " . print_r($_POST, true));

        $id = filter_var($_POST['per_catalogo'] ?? null, FILTER_SANITIZE_NUMBER_INT);

        if (!$id) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'ID de catálogo no válido'
            ], JSON_UNESCAPED_UNICODE);
            return;
        }

        try {
            error_log("🔍 Buscando persona con ID: " . $id);
            $persona = Personal::find($id);

            if (!$persona) {
                error_log("❌ Persona NO encontrada");
                http_response_code(404);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Persona no encontrada con catálogo: ' . $id
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            error_log("✅ Persona encontrada");
            error_log("Datos ANTES de sincronizar: " . print_r($persona, true));

            // Sanitizar datos
            $datosActualizados = [
                'per_serie' => $_POST['per_serie'] ?? '',
                'per_grado' => $_POST['per_grado'],
                'per_arma' => $_POST['per_arma'],
                'per_nom1' => htmlspecialchars($_POST['per_nom1']),
                'per_nom2' => htmlspecialchars($_POST['per_nom2'] ?? ''),
                'per_ape1' => htmlspecialchars($_POST['per_ape1']),
                'per_ape2' => htmlspecialchars($_POST['per_ape2'] ?? ''),
                'per_telefono' => $_POST['per_telefono'] ?? '',
                'per_sexo' => $_POST['per_sexo'],
                'per_fec_nac' => $_POST['per_fec_nac'],
                'per_nac_lugar' => htmlspecialchars($_POST['per_nac_lugar']),
                'per_dpi' => $_POST['per_dpi'] ?? '',
                'per_tipo_doc' => $_POST['per_tipo_doc'] ?? 'DPI',
                'per_email' => $_POST['per_email'] ?? '',
                'per_direccion' => htmlspecialchars($_POST['per_direccion'] ?? ''),
                'per_estado' => $_POST['per_estado'] ?? 'A',
                'per_tipo' => $_POST['per_tipo'],
                'fecha_modificacion' => date('Y-m-d H:i:s'),
                'observaciones' => htmlspecialchars($_POST['observaciones'] ?? '')
            ];

            error_log("Datos a sincronizar: " . print_r($datosActualizados, true));

            $persona->sincronizar($datosActualizados);

            error_log("Datos DESPUÉS de sincronizar: " . print_r($persona, true));
            error_log("Llamando a actualizar()...");

            $resultado = $persona->actualizar();

            error_log("Resultado de actualizar(): " . print_r($resultado, true));

            if ($resultado && isset($resultado['resultado'])) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Datos del personal modificados exitosamente'
                ], JSON_UNESCAPED_UNICODE);
            } else {
                throw new Exception('No se pudo actualizar el registro - resultado es false o no tiene la clave resultado');
            }
        } catch (\Exception $e) {
            error_log("❌ ERROR en modificarAPI: " . $e->getMessage());
            error_log("Línea: " . $e->getLine());
            error_log("Archivo: " . $e->getFile());
            error_log("Stack trace: " . $e->getTraceAsString());

            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al modificar datos',
                'detalle' => $e->getMessage(),
                'linea' => $e->getLine()
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    public static function eliminarAPI()
    {
        // ⭐ PROTEGER LA API
        isAuthApi();
        hasPermissionApi(['ADMINISTRADOR']);

        header('Content-Type: application/json; charset=UTF-8');

        $id = filter_var($_POST['per_catalogo'] ?? null, FILTER_SANITIZE_NUMBER_INT);

        if (!$id) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'ID de catálogo no válido'
            ], JSON_UNESCAPED_UNICODE);
            return;
        }

        try {
            $persona = Personal::find($id);

            if (!$persona) {
                http_response_code(404);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Persona no encontrada'
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            $resultado = $persona->eliminar();

            if ($resultado) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Personal eliminado exitosamente'
                ], JSON_UNESCAPED_UNICODE);
            } else {
                throw new Exception('No se pudo eliminar el registro');
            }
        } catch (Exception $e) {
            error_log("❌ ERROR en eliminarAPI: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al eliminar registro',
                'detalle' => $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
    }
}
