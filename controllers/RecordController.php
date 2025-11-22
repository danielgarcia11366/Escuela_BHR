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
            $persona = Participantes::getCursosPersona($per_catalogo);

            if (!$persona || empty($persona)) {
                header('Location: /Escuela_BHR/record');
                exit;
            }

            $datosPersona = $persona[0];

            // Crear PDF con mPDF
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'Letter',
                'margin_left' => 15,
                'margin_right' => 15,
                'margin_top' => 15,
                'margin_bottom' => 15,
            ]);

            // === ENCABEZADO CON TÍTULO PERSONALIZADO ===
            $header = '
        <div style="text-align: center; border-bottom: 3px solid #ff7b00; padding-bottom: 10px; margin-bottom: 15px;">
            <h1 style="color: #1a1a1a; margin: 0; font-size: 22px; font-weight: bold;">
                HISTORIAL DE CURSOS DEL ' . '
            </h1>
            <h1 style="color: #1a1a1a; margin: 0; font-size: 22px; font-weight: bold;">
               ' . strtoupper(htmlspecialchars($datosPersona['grado_arma'])) . '
            </h1>
            <h1 style="color: #1a1a1a; margin: 0; font-size: 22px; font-weight: bold;">
            ' . strtoupper(htmlspecialchars($datosPersona['nombre_completo'])) . '
            </h1>
            <h2 style="color: #ff7b00; margin: 5px 0; font-size: 18px; font-weight: 600;">
                ESCUELA DE ADIESTRAMIENTO DE ASISTENCIA HUMANITARIA Y DE RESCATE
            </h2>
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
        <table style="width: 100%; border-collapse: collapse; font-size: 14px; margin-top: 10px;">
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

                // Color según calificación
                $calColor = '#6c757d';
                if ($calificacion !== '-' && $calificacion !== null) {
                    $calNum = floatval($calificacion);
                    if ($calNum >= 90) $calColor = '#28a745';
                    elseif ($calNum >= 80) $calColor = '#007bff';
                    elseif ($calNum >= 70) $calColor = '#ffc107';
                    else $calColor = '#dc3545';
                }

                $estadoMap = [
                    'G' => 'Graduado',
                    'C' => 'Cursando',
                    'R' => 'Retirado',
                    'D' => 'Desertor'
                ];
                $estado = $estadoMap[$curso['par_estado']] ?? $curso['par_estado'];

                $tabla .= '
            <tr style="background: ' . $bgColor . ';">
                <td style="border: 1px solid #ddd; padding: 6px; text-align: center; font-weight: bold;">' . $contador . '</td>
                <td style="border: 1px solid #ddd; padding: 6px;">' . htmlspecialchars(substr($curso['curso_completo'], 0, 60)) . '</td>
                <td style="border: 1px solid #ddd; padding: 6px; text-align: center;">' . htmlspecialchars($curso['pro_numero'] . ' ' . $curso['pro_anio']) . '</td>
                <td style="border: 1px solid #ddd; padding: 6px; text-align: center;">' . $fechaInicio . '</td>
                <td style="border: 1px solid #ddd; padding: 6px; text-align: center;">' . $fechaFin . '</td>
                <td style="border: 1px solid #ddd; padding: 6px; text-align: center; font-weight: bold; color: ' . $calColor . ';">' .
                    ($calificacion !== '-' ? number_format(floatval($calificacion), 2) : '-') .
                    '</td>
                <td style="border: 1px solid #ddd; padding: 6px; text-align: center;">' . $estado . '</td>
            </tr>';

                $contador++;
            }

            $tabla .= '</tbody></table>';

            // Footer
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

            // Escribir contenido
            $html = $header . $infoPersona . $tabla . $footer;
            $mpdf->WriteHTML($html);

            // Salida del PDF
            $nombreArchivo = 'cursos-' . $datosPersona['per_catalogo'] . '.pdf';
            $mpdf->Output($nombreArchivo, 'I'); // 'I' = inline, 'D' = descarga

        } catch (Exception $e) {
            error_log("❌ Error al generar PDF: " . $e->getMessage());
            header('Location: /Escuela_BHR/record');
            exit;
        }
    }
}
