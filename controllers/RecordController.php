<?php

namespace Controllers;

use Exception;
use Model\Participantes;
use MVC\Router;
use Mpdf\Mpdf;

class RecordController
{
    public static function index(Router $router)
    {
        // ⭐ PROTEGER LA VISTA
        isAuth();
        hasPermission(['ADMINISTRADOR', 'INSTRUCTOR']);

        $router->render('record/index', []);
    }

    public static function historialPDF(Router $router)
    {
        isAuth();
        hasPermission(['ADMINISTRADOR', 'INSTRUCTOR']);

        $per_catalogo = $_GET['per_catalogo'] ?? null;

        if (!$per_catalogo) {
            header('Location: /Escuela_BHR/record');
            exit;
        }

        try {
            error_log("========== DEBUG PDF ==========");
            error_log("📋 Catálogo solicitado: " . $per_catalogo);

            $persona = Participantes::getCursosPersona($per_catalogo);

            if (!$persona || empty($persona)) {
                error_log("❌ No se encontraron datos para el catálogo: " . $per_catalogo);
                header('Location: /Escuela_BHR/record');
                exit;
            }

            $datosPersona = $persona[0];
            error_log("✅ Datos obtenidos: " . print_r($datosPersona, true));

            // ⭐ PROCESAR LA FOTO DE FORMA MÁS SEGURA
            $fotoBase64 = '';
            $tieneFoto = false;

            if (!empty($datosPersona['per_foto'])) {
                error_log("📸 Foto en BD: " . $datosPersona['per_foto']);

                // Probar múltiples rutas
                $rutas = [
                    __DIR__ . '/../public/uploads/fotos_personal/' . $datosPersona['per_foto'],
                    $_SERVER['DOCUMENT_ROOT'] . '/Escuela_BHR/public/uploads/fotos_personal/' . $datosPersona['per_foto'],
                    '/var/www/html/Escuela_BHR/public/uploads/fotos_personal/' . $datosPersona['per_foto']
                ];

                foreach ($rutas as $rutaFoto) {
                    error_log("🔍 Probando ruta: " . $rutaFoto);

                    if (file_exists($rutaFoto)) {
                        error_log("✅ Archivo encontrado en: " . $rutaFoto);
                        error_log("📊 Tamaño: " . filesize($rutaFoto) . " bytes");

                        try {
                            $tipoImagen = strtolower(pathinfo($rutaFoto, PATHINFO_EXTENSION));
                            error_log("🖼️ Extensión: " . $tipoImagen);

                            // Validar que sea una imagen válida
                            if (!in_array($tipoImagen, ['jpg', 'jpeg', 'png'])) {
                                error_log("⚠️ Extensión no válida, saltando foto");
                                break;
                            }

                            $imagenData = @file_get_contents($rutaFoto);

                            if ($imagenData === false) {
                                error_log("❌ No se pudo leer el archivo");
                                break;
                            }

                            // Normalizar JPEG
                            if ($tipoImagen === 'jpg') {
                                $tipoImagen = 'jpeg';
                            }

                            $fotoBase64 = 'data:image/' . $tipoImagen . ';base64,' . base64_encode($imagenData);
                            $tieneFoto = true;

                            error_log("✅ Foto convertida a Base64");
                            error_log("📏 Tamaño Base64: " . strlen($fotoBase64) . " caracteres");

                            break; // Salir del loop si se encontró

                        } catch (Exception $e) {
                            error_log("❌ Error al procesar imagen: " . $e->getMessage());
                        }
                    }
                }

                if (!$tieneFoto) {
                    error_log("⚠️ No se pudo cargar la foto, continuando sin ella");
                }
            } else {
                error_log("ℹ️ La persona no tiene foto registrada");
            }

            error_log("🎯 Tiene foto para el PDF: " . ($tieneFoto ? 'SÍ' : 'NO'));
            error_log("===============================");

            // Crear PDF con mPDF
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'Letter',
                'margin_left' => 15,
                'margin_right' => 15,
                'margin_top' => 15,
                'margin_bottom' => 15,
            ]);

            // === ENCABEZADO CON FOTO ===
            $header = '
        <div style="border-bottom: 3px solid #ff7b00; padding-bottom: 15px; margin-bottom: 20px;">
            <table style="width: 100%; border-collapse: collapse;">
                <tr>';

            if ($tieneFoto) {
                $header .= '
                    <td style="width: 25%; text-align: center; vertical-align: top; padding-right: 15px;">
                        <img src="' . htmlspecialchars($fotoBase64) . '" 
                             style="width: 150px; height: 150px; object-fit: cover; border-radius: 10px; border: 3px solid #ff7b00; box-shadow: 0 4px 10px rgba(0,0,0,0.2);">
                    </td>';
            }

            $header .= '
                    <td style="' . ($tieneFoto ? 'width: 75%;' : 'width: 100%;') . ' text-align: center; vertical-align: bottom; padding-bottom: 10px;">
                        <h2 style="color: #111111; margin: 0; font-size: 22px; font-weight: 600; line-height: 1.4;">
                            ESCUELA DE ADIESTRAMIENTO DE ASISTENCIA HUMANITARIA Y DE RESCATE
                        </h2>
                        <br>
                        <h1 style="color: #ff7b00; margin: 0 0 5px 0; font-size: 20px; font-weight: bold;">
                            HISTORIAL DE CURSOS
                        </h1>
                        <br>
                        <h1 style="color: #111111; margin: 0 0 5px 0; font-size: 20px; font-weight: bold;">
                            ' . strtoupper(htmlspecialchars($datosPersona['grado_arma'])) . '
                        </h1>
                        <h1 style="color: #111111; margin: 0 0 10px 0; font-size: 20px; font-weight: bold;">
                            ' . strtoupper(htmlspecialchars($datosPersona['nombre_completo'])) . '
                        </h1>
                    </td>
                </tr>
            </table>
        </div>';

            // === INFORMACIÓN DE LA PERSONA ===
            $infoPersona = '
        <div style="background: #f8f9fa; border: 2px solid #ff7b00; border-radius: 8px; padding: 15px; margin-bottom: 20px;">
            <table style="width: 100%; font-size: 15px;">
                <tr>
                    <td style="padding: 5px; width: 25%;"><strong>Catálogo:</strong></td>
                    <td style="padding: 5px;">' . htmlspecialchars($datosPersona['per_catalogo']) . '</td>
                    <td style="padding: 5px; width: 25%;"><strong>Total de Cursos:</strong></td>
                    <td style="padding: 5px;">' . count($persona) . '</td>
                </tr>
            </table>
        </div>';

            // === TABLA DE CURSOS ===
            $tabla = '
        <table style="width: 100%; border-collapse: collapse; font-size: 13px; margin-top: 10px;">
            <thead>
                <tr style="background: linear-gradient(135deg, #ff8400ff 0%, #197f00ff 100%); color: white;">
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: center; width: 7%;">No.</th>
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: left; width: 30%;">Curso</th>
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: center; width: 14%;">Promoción</th>
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: center; width: 12%;">Inicio</th>
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: center; width: 12%;">Fin</th>
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: center; width: 10%;">Calific.</th>
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: center; width: 11%;">Estado</th>
                </tr>
            </thead>
            <tbody>';

            $contador = 1;
            foreach ($persona as $curso) {
                $bgColor = ($contador % 2 == 0) ? '#f8f9fa' : '#ffffff';
                $fechaInicio = date('d/m/Y', strtotime($curso['pro_fecha_inicio']));
                $fechaFin = date('d/m/Y', strtotime($curso['pro_fecha_fin']));
                $calificacion = $curso['par_calificacion'] ?? '-';

                $calColor = '#6c757d';
                if ($calificacion !== '-' && $calificacion !== null) {
                    $calNum = floatval($calificacion);
                    if ($calNum >= 90) $calColor = '#28a745';
                    elseif ($calNum >= 80) $calColor = '#007bff';
                    elseif ($calNum >= 70) $calColor = '#ffc107';
                    else $calColor = '#dc3545';
                }

                $estadoMap = ['G' => 'Graduado', 'C' => 'Cursando', 'R' => 'Retirado', 'D' => 'Desertor'];
                $estado = $estadoMap[$curso['par_estado']] ?? $curso['par_estado'];

                $tabla .= '
            <tr style="background: ' . $bgColor . ';">
                <td style="border: 1px solid #ddd; padding: 6px; text-align: center; font-weight: bold;">' . $contador . '</td>
                <td style="border: 1px solid #ddd; padding: 6px;">' . htmlspecialchars(substr($curso['curso_completo'], 0, 60)) . '</td>
                <td style="border: 1px solid #ddd; padding: 6px; text-align: center;">' . htmlspecialchars($curso['pro_numero'] . ' ' . $curso['pro_anio']) . '</td>
                <td style="border: 1px solid #ddd; padding: 6px; text-align: center;">' . $fechaInicio . '</td>
                <td style="border: 1px solid #ddd; padding: 6px; text-align: center;">' . $fechaFin . '</td>
                <td style="border: 1px solid #ddd; padding: 6px; text-align: center; font-weight: bold; color: ' . $calColor . ';">' .
                    ($calificacion !== '-' ? number_format(floatval($calificacion), 2) : '-') . '</td>
                <td style="border: 1px solid #ddd; padding: 6px; text-align: center;">' . $estado . '</td>
            </tr>';
                $contador++;
            }
            $tabla .= '</tbody></table>';

            $fechaGeneracion = date('d/m/Y H:i:s');
            $nombreUsuario = $_SESSION['user']['usu_nombre'] ?? 'Sistema';
            $footer = '
        <div style="margin-top: 20px; padding-top: 10px; border-top: 2px solid #ddd; font-size: 9px; color: #666;">
            <table style="width: 100%;">
                <tr>
                    <td style="text-align: left;">Generado el: ' . $fechaGeneracion . '</td>
                    <td style="text-align: right;">Usuario: ' . htmlspecialchars($nombreUsuario) . '</td>
                </tr>
            </table>
        </div>';

            $html = $header . $infoPersona . $tabla . $footer;
            $mpdf->WriteHTML($html);

            $nombreArchivo = 'historial_cursos_' . $datosPersona['per_catalogo'] . '_' . date('Ymd') . '.pdf';
            $mpdf->Output($nombreArchivo, 'I');
        } catch (Exception $e) {
            error_log("❌ ERROR FATAL al generar PDF: " . $e->getMessage());
            error_log("📍 Archivo: " . $e->getFile());
            error_log("📍 Línea: " . $e->getLine());
            error_log("🔍 Stack trace: " . $e->getTraceAsString());

            header('Location: /Escuela_BHR/record');
            exit;
        }
    }
}
