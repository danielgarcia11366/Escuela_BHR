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

            // ⭐ PROCESAR LA FOTO CON MÁS LOGS
            $nombreFoto = null;

            error_log("🔍 Verificando archivo...");
            error_log("isset FILES: " . (isset($_FILES['per_foto']) ? 'SI' : 'NO'));

            if (isset($_FILES['per_foto'])) {
                error_log("Error code: " . $_FILES['per_foto']['error']);
                error_log("Tamaño: " . $_FILES['per_foto']['size']);
                error_log("Tipo: " . $_FILES['per_foto']['type']);
                error_log("Tmp name: " . $_FILES['per_foto']['tmp_name']);
            }

            if (isset($_FILES['per_foto']) && $_FILES['per_foto']['error'] === UPLOAD_ERR_OK) {
                error_log("✅ Archivo recibido correctamente");

                $catalogo = $_POST['per_catalogo'];
                $extension = strtolower(pathinfo($_FILES['per_foto']['name'], PATHINFO_EXTENSION));
                $nombreFoto = $catalogo . '.' . $extension;

                // Construir ruta absoluta
                $rutaBase = __DIR__ . '/../public/uploads/fotos_personal/';
                $rutaDestino = $rutaBase . $nombreFoto;

                error_log("📁 Ruta base: {$rutaBase}");
                error_log("📁 Ruta destino: {$rutaDestino}");
                error_log("📁 Directorio existe: " . (is_dir($rutaBase) ? 'SI' : 'NO'));
                error_log("📁 Directorio escribible: " . (is_writable($rutaBase) ? 'SI' : 'NO'));

                // Validar extensión
                $extensionesPermitidas = ['jpg', 'jpeg', 'png'];
                if (!in_array($extension, $extensionesPermitidas)) {
                    error_log("❌ Extensión no permitida: {$extension}");
                    http_response_code(400);
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => "Solo se permiten imágenes JPG, JPEG o PNG. Extensión recibida: {$extension}"
                    ], JSON_UNESCAPED_UNICODE);
                    return;
                }

                // Validar tipo MIME real
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mimeType = finfo_file($finfo, $_FILES['per_foto']['tmp_name']);
                finfo_close($finfo);

                error_log("🔍 MIME Type detectado: {$mimeType}");

                $tiposPermitidos = ['image/jpeg', 'image/png', 'image/jpg'];
                if (!in_array($mimeType, $tiposPermitidos)) {
                    error_log("❌ Tipo MIME no permitido: {$mimeType}");
                    http_response_code(400);
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => "El archivo no es una imagen válida. Tipo detectado: {$mimeType}"
                    ], JSON_UNESCAPED_UNICODE);
                    return;
                }

                // Validar tamaño (5MB)
                $tamanoMB = round($_FILES['per_foto']['size'] / 1024 / 1024, 2);
                error_log("📊 Tamaño del archivo: {$tamanoMB}MB");

                if ($_FILES['per_foto']['size'] > 10 * 1024 * 1024) {
                    http_response_code(400);
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => "La imagen no debe superar los 10MB. Tamaño actual: {$tamanoMB}MB"
                    ], JSON_UNESCAPED_UNICODE);
                    return;
                }

                // Intentar mover archivo
                error_log("📤 Intentando mover archivo...");
                error_log("Desde: " . $_FILES['per_foto']['tmp_name']);
                error_log("Hacia: " . $rutaDestino);

                if (move_uploaded_file($_FILES['per_foto']['tmp_name'], $rutaDestino)) {
                    error_log("✅ ¡Foto guardada exitosamente! {$nombreFoto}");
                    error_log("✅ Archivo existe: " . (file_exists($rutaDestino) ? 'SI' : 'NO'));

                    // Verificar que el archivo realmente se guardó
                    if (file_exists($rutaDestino)) {
                        $tamanoGuardado = filesize($rutaDestino);
                        error_log("✅ Tamaño del archivo guardado: " . round($tamanoGuardado / 1024, 2) . "KB");
                    }
                } else {
                    $error = error_get_last();
                    error_log("❌ Error al mover archivo");
                    error_log("Error PHP: " . print_r($error, true));

                    http_response_code(500);
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => 'Error al subir la foto. Verifica los permisos de la carpeta.',
                        'detalle' => $error['message'] ?? 'Error desconocido'
                    ], JSON_UNESCAPED_UNICODE);
                    return;
                }
            } else if (isset($_FILES['per_foto']) && $_FILES['per_foto']['error'] !== UPLOAD_ERR_OK) {
                // Mapear códigos de error
                $errores = [
                    UPLOAD_ERR_INI_SIZE => 'El archivo excede upload_max_filesize en php.ini',
                    UPLOAD_ERR_FORM_SIZE => 'El archivo excede MAX_FILE_SIZE del formulario',
                    UPLOAD_ERR_PARTIAL => 'El archivo se subió parcialmente',
                    UPLOAD_ERR_NO_FILE => 'No se subió ningún archivo',
                    UPLOAD_ERR_NO_TMP_DIR => 'Falta la carpeta temporal',
                    UPLOAD_ERR_CANT_WRITE => 'Error al escribir en el disco',
                    UPLOAD_ERR_EXTENSION => 'Extensión de PHP detuvo la subida'
                ];

                $codigoError = $_FILES['per_foto']['error'];
                $mensajeError = $errores[$codigoError] ?? "Error desconocido (código: {$codigoError})";

                error_log("❌ Error al subir archivo: {$mensajeError}");

                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => "Error al subir la foto: {$mensajeError}"
                ], JSON_UNESCAPED_UNICODE);
                return;
            } else {
                error_log("ℹ️ No se envió ninguna foto");
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
                'observaciones' => htmlspecialchars($_POST['observaciones'] ?? ''),
                'per_foto' => $nombreFoto
            ];

            error_log("✅ Datos sanitizados correctamente");
            error_log("per_foto en datos: " . ($nombreFoto ?? 'NULL'));

            $persona = new Personal($datos);
            error_log("✅ Objeto Personal creado");

            $resultado = $persona->crear();
            error_log("Resultado de crear(): " . print_r($resultado, true));

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

        error_log("========== MODIFICAR PERSONAL ==========");
        error_log("POST recibido: " . print_r($_POST, true));
        error_log("FILES recibido: " . print_r($_FILES, true));

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

            // ⭐ PROCESAR NUEVA FOTO (si se envió)
            $nombreFoto = $persona->per_foto; // Mantener la foto actual por defecto

            if (isset($_FILES['per_foto']) && $_FILES['per_foto']['error'] === UPLOAD_ERR_OK) {
                $extension = pathinfo($_FILES['per_foto']['name'], PATHINFO_EXTENSION);
                $nombreFoto = $id . '.' . $extension;

                $rutaDestino = __DIR__ . '/../public/uploads/fotos_personal/' . $nombreFoto;

                // Validar tipo
                $tiposPermitidos = ['image/jpeg', 'image/png', 'image/jpg'];
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mimeType = finfo_file($finfo, $_FILES['per_foto']['tmp_name']);
                finfo_close($finfo);

                if (!in_array($mimeType, $tiposPermitidos)) {
                    http_response_code(400);
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => 'Solo se permiten imágenes JPG, JPEG o PNG'
                    ], JSON_UNESCAPED_UNICODE);
                    return;
                }

                // Validar tamaño
                if ($_FILES['per_foto']['size'] > 5 * 1024 * 1024) {
                    http_response_code(400);
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => 'La imagen no debe superar los 5MB'
                    ], JSON_UNESCAPED_UNICODE);
                    return;
                }

                // Eliminar foto anterior si existe y es diferente
                if ($persona->per_foto && $persona->per_foto !== $nombreFoto) {
                    $rutaAnterior = __DIR__ . '/../public/uploads/fotos_personal/' . $persona->per_foto;
                    if (file_exists($rutaAnterior)) {
                        unlink($rutaAnterior);
                        error_log("🗑️ Foto anterior eliminada: {$persona->per_foto}");
                    }
                }

                // Guardar nueva foto
                if (!move_uploaded_file($_FILES['per_foto']['tmp_name'], $rutaDestino)) {
                    http_response_code(500);
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => 'Error al subir la nueva foto'
                    ], JSON_UNESCAPED_UNICODE);
                    return;
                }

                error_log("✅ Nueva foto guardada: {$nombreFoto}");
            }

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
                'observaciones' => htmlspecialchars($_POST['observaciones'] ?? ''),
                'per_foto' => $nombreFoto  // ⭐ INCLUIR LA FOTO
            ];

            $persona->sincronizar($datosActualizados);
            $resultado = $persona->actualizar();

            if ($resultado && isset($resultado['resultado'])) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Datos del personal modificados exitosamente'
                ], JSON_UNESCAPED_UNICODE);
            } else {
                throw new Exception('No se pudo actualizar el registro');
            }
        } catch (\Exception $e) {
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

            // ⭐ Eliminar foto física si existe
            if ($persona->per_foto) {
                $rutaFoto = __DIR__ . '/../public/uploads/fotos_personal/' . $persona->per_foto;
                if (file_exists($rutaFoto)) {
                    unlink($rutaFoto);
                    error_log("🗑️ Foto eliminada: {$persona->per_foto}");
                }
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
