<?php

namespace Controllers;

use Exception;
use Model\Promociones;
use Model\Cursos;
use Model\Paises;
use Model\Instituciones;
use MVC\Router;
use Mpdf\Mpdf;

class PromocionesController
{
    public static function index(Router $router)
    {
        // ⭐ PROTEGER LA VISTA
        isAuth();
        hasPermission(['ADMINISTRADOR']);

        $instituciones = Instituciones::obtenerinstitucionQuery();
        $cursos = Cursos::obtenerCursos1();
        $paises = Paises::obtenerPaisesOrdenados();

        $router->render('promociones/index', [
            'instituciones' => $instituciones,
            'cursos' => $cursos,
            'paises' => $paises
        ]);
    }

    public static function guardarAPI()
    {
        // ⭐ PROTEGER LA API
        isAuthApi();
        hasPermissionApi(['ADMINISTRADOR']);

        header('Content-Type: application/json; charset=utf-8');

        try {
            $promocion = new Promociones($_POST);
            $resultado = $promocion->crear();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Promoción guardada exitosamente'
            ], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al guardar la promoción',
                'detalle' => $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    public static function buscarAPI()
    {
        // ⭐ PROTEGER LA API
        isAuthApi();
        hasPermissionApi(['ADMINISTRADOR', 'INSTRUCTOR']);

        header('Content-Type: application/json; charset=utf-8');

        try {
            $promociones = Promociones::obtenerPromocionesConDetalles();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Datos encontrados',
                'datos' => $promociones
            ], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al buscar promociones',
                'detalle' => $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    public static function modificarAPI()
    {
        // ⭐ PROTEGER LA API
        isAuthApi();
        hasPermissionApi(['ADMINISTRADOR']);

        header('Content-Type: application/json; charset=utf-8');

        try {
            $id = filter_var($_POST['pro_codigo'], FILTER_SANITIZE_NUMBER_INT);

            if (!$id) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'ID de promoción no válido'
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            $promocion = Promociones::find($id);

            if (!$promocion) {
                http_response_code(404);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Promoción no encontrada'
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            $promocion->sincronizar($_POST);
            $resultado = $promocion->actualizar();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Promoción modificada exitosamente'
            ], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al modificar la promoción',
                'detalle' => $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    public static function eliminarAPI()
    {
        // ⭐ PROTEGER LA API
        isAuthApi();
        hasPermissionApi(['ADMINISTRADOR']);

        header('Content-Type: application/json; charset=utf-8');

        try {
            $id = filter_var($_POST['pro_codigo'], FILTER_SANITIZE_NUMBER_INT);

            if (!$id) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'ID de promoción no válido'
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            $promocion = Promociones::find($id);

            if (!$promocion) {
                http_response_code(404);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Promoción no encontrada'
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            $resultado = $promocion->eliminar();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Promoción eliminada exitosamente'
            ], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al eliminar la promoción',
                'detalle' => $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
    }
    /**
     * ⭐ NUEVA VISTA: Historial de promociones (solo lectura)
     */
    public static function historial(Router $router)
    {
        isAuth();
        hasPermission(['ADMINISTRADOR', 'INSTRUCTOR']);

        $router->render('promociones/historial', [
            'titulo' => 'Historial de Promociones'
        ]);
    }

    /**
     * ⭐ NUEVO MÉTODO: Obtener participantes de una promoción
     */
    public static function participantesAPI()
    {
        isAuthApi();
        hasPermissionApi(['ADMINISTRADOR', 'INSTRUCTOR']);

        header('Content-Type: application/json; charset=utf-8');

        try {
            $pro_codigo = filter_var($_GET['pro_codigo'] ?? 0, FILTER_SANITIZE_NUMBER_INT);

            if (!$pro_codigo) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'ID de promoción no válido'
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            $participantes = Promociones::obtenerParticipantesPorPromocion($pro_codigo);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Participantes encontrados',
                'datos' => $participantes
            ], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al buscar participantes',
                'detalle' => $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * ⭐ NUEVO MÉTODO: Generar PDF de participantes
     */
    public static function generarPDFParticipantes()
    {
        // Proteger el acceso
        isAuth();
        hasPermission(['ADMINISTRADOR', 'INSTRUCTOR']);

        try {
            $pro_codigo = filter_var($_GET['pro_codigo'] ?? 0, FILTER_SANITIZE_NUMBER_INT);

            if (!$pro_codigo) {
                header('Location: /Escuela_BHR/promociones/historial');
                return;
            }

            // Obtener datos de la promoción
            $promocion = Promociones::find($pro_codigo);
            if (!$promocion) {
                header('Location: /Escuela_BHR/promociones/historial');
                return;
            }

            // Obtener participantes
            $participantes = Promociones::obtenerParticipantesPorPromocion($pro_codigo);

            // Obtener información completa de la promoción
            $promociones = Promociones::obtenerPromocionesConDetalles();
            $infoPromocion = array_filter($promociones, fn($p) => $p['pro_codigo'] == $pro_codigo);
            $infoPromocion = reset($infoPromocion);

            // Crear PDF
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'Letter',
                'orientation' => 'L', // Landscape (horizontal)
                'margin_left' => 10,
                'margin_right' => 10,
                'margin_top' => 20,
                'margin_bottom' => 15,
                'margin_header' => 10,
                'margin_footer' => 10
            ]);

            // Configurar propiedades del PDF
            $mpdf->SetTitle('Listado de Participantes - Promoción ' . $infoPromocion['numero_anio']);
            $mpdf->SetAuthor('Sistema de Gestión Escolar');
            $mpdf->SetCreator('Escuela BHR');

            // Header del PDF
            $header = '
            <div style="text-align: center; border-bottom: 3px solid #ff7b00; padding-bottom: 5px; margin-bottom: 10px;">
                <h1 style="color: #1a1a1a; margin: 0; font-size: 28px; font-weight: bold;">
                    LISTADO DE PARTICIPANTES
                </h1>
                <h2 style="color: #ff7b00; margin: 5px 0; font-size: 22px; font-weight: 600;">
                    Curso ' . htmlspecialchars($infoPromocion['curso_nombre']) . ' - ' . htmlspecialchars($infoPromocion['nivel_nombre']) . ' - Promoción ' . htmlspecialchars($infoPromocion['numero_anio']) . '
                </h2>
            </div>
            ';

            // Información de la promoción
            $fechaInicio = date('d/m/Y', strtotime($infoPromocion['pro_fecha_inicio']));
            $fechaFin = date('d/m/Y', strtotime($infoPromocion['pro_fecha_fin']));
            $fechaGeneracion = date('d/m/Y H:i:s');

            // Obtener nombre del usuario logueado
            $nombreUsuario = $_SESSION['user']['usu_nombre'] ?? 'Sistema';

            $infoBox = '
            <div style="background: #f8f9fa; border: 2px solid #ff7b00; border-radius: 10px; padding: 15px; margin-bottom: 20px;">
                <table style="width: 100%; font-size: 18px; color: #333;">
                    <tr>
                        <td style="padding: 5px;">
                            <strong style="color: #ff7b00;">• Lugar:</strong> ' . htmlspecialchars($infoPromocion['pro_lugar']) . '
                        </td>
                        <td style="padding: 5px;">
                            <strong style="color: #ff7b00;">• País:</strong> ' . htmlspecialchars($infoPromocion['pais_nombre']) . '
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 5px;">
                            <strong style="color: #ff7b00;">• Fecha Inicio:</strong> ' . $fechaInicio . '
                        </td>
                        <td style="padding: 5px;">
                            <strong style="color: #ff7b00;">• Fecha Fin:</strong> ' . $fechaFin . '
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 5px;">
                            <strong style="color: #ff7b00;">• Institución:</strong> ' . htmlspecialchars($infoPromocion['institucion_nombre']) . '
                        </td>
                        <td style="padding: 5px;">
                            <strong style="color: #ff7b00;">• Total Participantes:</strong> ' . count($participantes) . '
                        </td>
                    </tr>
                </table>
            </div>
            ';

            // Tabla de participantes
            $tabla = '
            <table style="width: 100%; border-collapse: collapse; font-size: 15px; margin-top: 10px;">
                <thead>
                    <tr style="background: linear-gradient(135deg, #ff8400ff 0%, #197f00ff 100%); color: white;">
                        <th style="border: 1px solid #ddd; padding: 10px; text-align: center; width: 5%;">#</th>
                        <th style="border: 1px solid #ddd; padding: 10px; text-align: center; width: 10%;">CATÁLOGO</th>
                        <th style="border: 1px solid #ddd; padding: 10px; text-align: left; width: 50%;">GRADO/ARMA Y NOMBRE COMPLETO</th>
                        <th style="border: 1px solid #ddd; padding: 10px; text-align: center; width: 12%;">CALIFICACIÓN</th>
                        <th style="border: 1px solid #ddd; padding: 10px; text-align: center; width: 10%;">POSICIÓN</th>
                        <th style="border: 1px solid #ddd; padding: 10px; text-align: center; width: 13%;">ESTADO</th>
                    </tr>
                </thead>
                <tbody>';

            // Agregar participantes
            $contador = 1;
            foreach ($participantes as $part) {
                // Color de fila alternado
                $bgColor = ($contador % 2 == 0) ? '#f8f9fa' : '#ffffff';

                // Color según calificación
                $calificacion = $part['par_calificacion'] ?? '';
                $calColor = '##000000';
                if ($calificacion !== '' && $calificacion !== null) {
                    $calNum = floatval($calificacion);
                    if ($calNum >= 90) $calColor = '#28a745';
                    elseif ($calNum >= 80) $calColor = '#007bff';
                    elseif ($calNum >= 70) $calColor = '#ffc107';
                    else $calColor = '#dc3545';
                }

                // Estados
                $estados = [
                    'G' => 'Graduado',
                    'C' => 'Cursando',
                    'R' => 'Retirado',
                    'D' => 'Desertor'
                ];
                $estadoTexto = $estados[$part['par_estado']] ?? $part['par_estado'];

                // Combinar grado/arma completo con nombre
                $grado = trim($part['grado_completo'] ?? '');
                $arma = trim($part['arma_completa'] ?? '');
                $nombreCompleto = trim($part['nombre_completo']);

                // Construir grado y arma completo
                $gradoArmaCompleto = '';
                if ($grado && $arma) {
                    $gradoArmaCompleto = "{$grado} {$arma}";
                } elseif ($grado) {
                    $gradoArmaCompleto = $grado;
                } elseif ($arma) {
                    $gradoArmaCompleto = $arma;
                }

                $nombreConGrado = $gradoArmaCompleto ? "<strong>{$gradoArmaCompleto}</strong><br/>{$nombreCompleto}" : $nombreCompleto;

                $tabla .= '
                <tr style="background: ' . $bgColor . ';">
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: center; font-weight: bold;">' . $contador . '</td>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: center; font-weight: bold; color: #ff7b00;">' . htmlspecialchars($part['per_catalogo']) . '</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">' . $nombreConGrado . '</td>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: center; font-weight: bold; color: ' . $calColor . ';">' .
                    ($calificacion !== '' ? number_format(floatval($calificacion), 2) : '-') .
                    '</td>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">' . ($part['par_posicion'] ?? '-') . '</td>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: center; font-size: 15px;">' . $estadoTexto . '</td>
                </tr>';

                $contador++;
            }

            $tabla .= '</tbody></table>';

            // Footer
            $footer = '
            <div style="margin-top: 30px; padding-top: 15px; border-top: 2px solid #ddd; font-size: 9px; color: #666;">
                <table style="width: 100%;">
                    <tr>
                        <td style="text-align: left;">
                            Generado el: ' . $fechaGeneracion . '
                        </td>
                        <td style="text-align: right;">
                            Usuario: ' . htmlspecialchars($nombreUsuario) . '
                        </td>
                    </tr>
                </table>
            </div>';

            // Escribir contenido
            $html = $header . $infoBox . $tabla . $footer;
            $mpdf->WriteHTML($html);

            // Salida del PDF
            $nombreArchivo = 'Participantes_' . $infoPromocion['numero_anio'] . '_' . date('YmdHis') . '.pdf';
            $mpdf->Output($nombreArchivo, 'I'); // 'I' = inline en navegador, 'D' = descarga

        } catch (Exception $e) {
            error_log("Error al generar PDF: " . $e->getMessage());
            header('Location: /Escuela_BHR/promociones/historial?error=pdf');
        }
    }

    
}
