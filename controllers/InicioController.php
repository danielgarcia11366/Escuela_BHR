<?php

namespace Controllers;

use Exception;
use Model\ActiveRecord;
use MVC\Router;

class InicioController
{
    public static function index(Router $router)
    {
        $router->render('pages/index', []);
    }

    public static function estadisticasAPI()
    {
        header('Content-Type: application/json; charset=UTF-8');

        try {
            // Consultas individuales para mejor manejo de errores
            $totalAlumnos = 0;
            $cursosActivos = 0;
            $promocionesActivas = 0;
            $graduados = 0;

            // Total de alumnos
            try {
                $sqlAlumnos = "SELECT COUNT(*) as total FROM mper WHERE per_tipo = 'A' AND per_estado = 'A'";
                $resultAlumnos = ActiveRecord::fetchFirst($sqlAlumnos);
                $totalAlumnos = $resultAlumnos ? (int)$resultAlumnos['total'] : 0;
            } catch (Exception $e) {
                // Si falla, deja en 0
            }

            // Total de cursos activos
            try {
                $sqlCursos = "SELECT COUNT(*) as total FROM cursos";
                $resultCursos = ActiveRecord::fetchFirst($sqlCursos);
                $cursosActivos = $resultCursos ? (int)$resultCursos['total'] : 0;
            } catch (Exception $e) {
                // Si falla, deja en 0
            }

            // Total de promociones activas
            try {
                $sqlPromociones = "SELECT COUNT(*) as total FROM promociones WHERE pro_activa = 'S'";
                $resultPromociones = ActiveRecord::fetchFirst($sqlPromociones);
                $promocionesActivas = $resultPromociones ? (int)$resultPromociones['total'] : 0;
            } catch (Exception $e) {
                // Si falla, deja en 0
            }

            // Total de graduados
            try {
                $sqlGraduados = "SELECT COUNT(*) as total FROM participantes WHERE par_estado = 'G'";
                $resultGraduados = ActiveRecord::fetchFirst($sqlGraduados);
                $graduados = $resultGraduados ? (int)$resultGraduados['total'] : 0;
            } catch (Exception $e) {
                // Si falla, deja en 0
            }

            // Obtener actividades recientes
            $actividades = [];
            try {
                $sqlActividad = "
                    SELECT 
                        CONCAT(per_nom1, ' ', per_ape1) as descripcion,
                        fecha_registro as fecha
                    FROM mper 
                    WHERE per_tipo = 'A'
                    ORDER BY fecha_registro DESC 
                    LIMIT 3
                ";
                $actividades = ActiveRecord::fetchArray($sqlActividad);
            } catch (Exception $e) {
                // Si falla, deja array vacío
            }

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Estadísticas obtenidas exitosamente',
                'datos' => [
                    'totalAlumnos' => $totalAlumnos,
                    'cursosActivos' => $cursosActivos,
                    'promocionesActivas' => $promocionesActivas,
                    'graduados' => $graduados,
                    'actividades' => $actividades
                ]
            ], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener estadísticas',
                'detalle' => $e->getMessage(),
            ], JSON_UNESCAPED_UNICODE);
        }
    }
}
