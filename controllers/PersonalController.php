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
        isAuthApi();
        hasPermissionApi(['ADMINISTRADOR']);

        header('Content-Type: application/json; charset=UTF-8');

        error_log("========== GUARDAR PERSONAL ==========");
        error_log("POST recibido: " . print_r($_POST, true));
        error_log("FILES recibido: " . print_r($_FILES, true));
        error_log("Directorio actual: " . __DIR__);

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

            // ⭐ PROCESAR FOTO
            $nombreFoto = null;

            if (isset($_FILES['per_foto']) && $_FILES['per_foto']['error'] === UPLOAD_ERR_OK) {
                $catalogo = $_POST['per_catalogo'];
                $extension = strtolower(pathinfo($_FILES['per_foto']['name'], PATHINFO_EXTENSION));
                $nombreFoto = $catalogo . '.' . $extension;

                $rutaBase = __DIR__ . '/../public/uploads/fotos_personal/';
                $rutaDestino = $rutaBase . $nombreFoto;

                $extensionesPermitidas = ['jpg', 'jpeg', 'png'];
                if (!in_array($extension, $extensionesPermitidas)) {
                    http_response_code(400);
                    echo json_encode(['codigo' => 0, 'mensaje' => "Solo se permiten imágenes JPG, JPEG o PNG."], JSON_UNESCAPED_UNICODE);
                    return;
                }

                // MIME
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mimeType = finfo_file($finfo, $_FILES['per_foto']['tmp_name']);
                finfo_close($finfo);

                $tiposPermitidos = ['image/jpeg', 'image/png', 'image/jpg'];
                if (!in_array($mimeType, $tiposPermitidos)) {
                    http_response_code(400);
                    echo json_encode(['codigo' => 0, 'mensaje' => "El archivo no es una imagen válida."], JSON_UNESCAPED_UNICODE);
                    return;
                }

                // Tamaño
                if ($_FILES['per_foto']['size'] > 10 * 1024 * 1024) {
                    http_response_code(400);
                    echo json_encode(['codigo' => 0, 'mensaje' => "La imagen no debe superar los 10MB."], JSON_UNESCAPED_UNICODE);
                    return;
                }

                if (!move_uploaded_file($_FILES['per_foto']['tmp_name'], $rutaDestino)) {
                    http_response_code(500);
                    echo json_encode(['codigo' => 0, 'mensaje' => 'Error al subir la foto.'], JSON_UNESCAPED_UNICODE);
                    return;
                }
            }

            // Sanitizar datos SIN OBSERVACIONES
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
                // ❌ SE QUITA 'observaciones'
                'per_foto' => $nombreFoto
            ];

            $persona = new Personal($datos);
            $resultado = $persona->crear();

            if ($resultado && isset($resultado['resultado'])) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Personal registrado exitosamente' . ($nombreFoto ? ' con foto' : ''),
                    'id' => $resultado['id'] ?? null,
                    'foto' => $nombreFoto
                ], JSON_UNESCAPED_UNICODE);
            } else {
                throw new Exception('No se pudo insertar el registro en la base de datos');
            }
        } catch (Exception $e) {
            error_log("❌ ERROR en guardarAPI: " . $e->getMessage());
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

            // ⭐ MANEJO DE FOTO - VARIABLES AL INICIO
            $nombreFotoNueva = null;           // Guarda el nombre de la nueva foto subida
            $huboCambioFoto = false;           // ✅ Flag para saber si se cambió la foto
            $nombreFoto = $persona->per_foto;  // Mantiene la foto actual por defecto

            // ⭐ PROCESAR NUEVA FOTO (si se subió)
            if (isset($_FILES['per_foto']) && $_FILES['per_foto']['error'] === UPLOAD_ERR_OK) {
                $extension = strtolower(pathinfo($_FILES['per_foto']['name'], PATHINFO_EXTENSION));

                // Generar nombre único con timestamp para evitar caché
                $nombreFotoNueva = $id . '_' . time() . '.' . $extension;

                $rutaDestino = __DIR__ . '/../public/uploads/fotos_personal/' . $nombreFotoNueva;

                // Validación MIME
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mimeType = finfo_file($finfo, $_FILES['per_foto']['tmp_name']);
                finfo_close($finfo);

                $tiposPermitidos = ['image/jpeg', 'image/png', 'image/jpg'];
                if (!in_array($mimeType, $tiposPermitidos)) {
                    http_response_code(400);
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => 'Solo se permiten imágenes JPG, JPEG o PNG'
                    ], JSON_UNESCAPED_UNICODE);
                    return;
                }

                // Validación de tamaño (10MB)
                if ($_FILES['per_foto']['size'] > 10 * 1024 * 1024) {
                    http_response_code(400);
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => 'La imagen no debe superar los 10MB'
                    ], JSON_UNESCAPED_UNICODE);
                    return;
                }

                // Mover archivo
                if (move_uploaded_file($_FILES['per_foto']['tmp_name'], $rutaDestino)) {
                    // Eliminar foto anterior SOLO si es diferente
                    if ($persona->per_foto && $persona->per_foto !== $nombreFotoNueva) {
                        $rutaAnterior = __DIR__ . '/../public/uploads/fotos_personal/' . $persona->per_foto;
                        if (file_exists($rutaAnterior)) {
                            unlink($rutaAnterior);
                        }
                    }

                    // Usar la nueva foto
                    $nombreFoto = $nombreFotoNueva;
                    $huboCambioFoto = true; // ✅ Marcar que sí hubo cambio
                } else {
                    http_response_code(500);
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => 'Error al subir la nueva foto'
                    ], JSON_UNESCAPED_UNICODE);
                    return;
                }
            }

            // Datos actualizados
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
                'per_foto' => $nombreFoto  // ✅ Usa la foto actualizada o la anterior
            ];

            $persona->sincronizar($datosActualizados);
            $resultado = $persona->actualizar();

            if ($resultado && isset($resultado['resultado'])) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Datos modificados exitosamente',
                    'nueva_foto' => $huboCambioFoto ? $nombreFoto : null, // ✅ Solo envía si cambió
                    'per_catalogo' => $id
                ], JSON_UNESCAPED_UNICODE);
            } else {
                throw new Exception('No se pudo actualizar el registro');
            }
        } catch (Exception $e) {
            error_log("❌ ERROR en modificarAPI: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al modificar datos',
                'detalle' => $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    public static function eliminarAPI()
    {
        isAuthApi();
        hasPermissionApi(['ADMINISTRADOR']);

        header('Content-Type: application/json; charset=UTF-8');

        $id = filter_var($_POST['per_catalogo'] ?? null, FILTER_SANITIZE_NUMBER_INT);

        if (!$id) {
            http_response_code(400);
            echo json_encode(['codigo' => 0, 'mensaje' => 'ID inválido'], JSON_UNESCAPED_UNICODE);
            return;
        }

        try {
            $persona = Personal::find($id);

            if (!$persona) {
                http_response_code(404);
                echo json_encode(['codigo' => 0, 'mensaje' => 'Persona no encontrada'], JSON_UNESCAPED_UNICODE);
                return;
            }

            // eliminar foto
            if ($persona->per_foto) {
                $ruta = __DIR__ . '/../public/uploads/fotos_personal/' . $persona->per_foto;
                if (file_exists($ruta)) unlink($ruta);
            }

            $resultado = $persona->eliminar();

            if ($resultado) {
                http_response_code(200);
                echo json_encode(['codigo' => 1, 'mensaje' => 'Personal eliminado exitosamente'], JSON_UNESCAPED_UNICODE);
            } else {
                throw new Exception('No se pudo eliminar');
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['codigo' => 0, 'mensaje' => 'Error al eliminar', 'detalle' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
        }
    }
}
