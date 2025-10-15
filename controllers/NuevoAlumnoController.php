<?php

namespace Controllers;

use Exception;
use Model\NuevoAlumno;
use Model\Armas;
use Model\Grados;
use MVC\Router;

class NuevoAlumnoController
{
    public static function index(Router $router)
    {
        $armas = Armas::obtenerarmaconQuery();
        $grados = Grados::obtenergradoconQuery();

        $nuevoalumno = NuevoAlumno::find(2);

        $router->render('nuevoalumno/index', [
            'nuevoalumno' => $nuevoalumno,
            'armas'       => $armas,
            'grados'      => $grados
        ], 'layouts/menu');
    }

    public static function guardarAPI()
    {
        if (empty($_POST)) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'No se recibieron datos',
            ]);
            return;
        }

        try {
            // Sanear campos
            $_POST['per_nom1'] = htmlspecialchars($_POST['per_nom1'] ?? '');
            $_POST['per_nom2'] = htmlspecialchars($_POST['per_nom2'] ?? '');
            $_POST['per_ape1'] = htmlspecialchars($_POST['per_ape1'] ?? '');
            $_POST['per_ape2'] = htmlspecialchars($_POST['per_ape2'] ?? '');
            $_POST['per_nac_lugar'] = htmlspecialchars($_POST['per_nac_lugar'] ?? '');
            $_POST['per_sexo'] = strtoupper($_POST['per_sexo'] ?? '');
            $_POST['per_grado'] = (int)($_POST['per_grado'] ?? 0);
            $_POST['per_arma'] = (int)($_POST['per_arma'] ?? 0);
            $_POST['per_telefono'] = (int)($_POST['per_telefono'] ?? 0);
            $_POST['per_dpi'] = htmlspecialchars($_POST['per_dpi'] ?? '');

            // Validar fecha
            if (isset($_POST['per_fec_nac']) && !empty($_POST['per_fec_nac'])) {
                $parts = explode('-', $_POST['per_fec_nac']);
                if (count($parts) === 3 && checkdate((int)$parts[1], (int)$parts[2], (int)$parts[0])) {
                    $_POST['per_fec_nac'] = $_POST['per_fec_nac'];
                } else {
                    throw new Exception("Fecha de nacimiento inválida");
                }
            } else {
                throw new Exception("Fecha de nacimiento es obligatoria");
            }

            // NO enviar per_fec_creacion, dejar que use DEFAULT en BD
            unset($_POST['per_fec_creacion']);

            $Alumno = new NuevoAlumno($_POST);
            $resultado = $Alumno->crear();

            if ($resultado) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Alumno Registrado Exitosamente',
                ]);
            } else {
                throw new Exception('No se pudo guardar en la base de datos');
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al registrar Alumno',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function buscarAPI()
    {
        try {
            $alumnos = NuevoAlumno::obteneralumnos();
            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Datos encontrados',
                'detalle' => '',
                'datos' => $alumnos
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al buscar Alumnos',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function modificarAPI()
    {
        
        if (empty($_POST)) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'No se recibieron datos',
            ]);
            return;
        }

        try {
            // Sanear campos (igual que en guardarAPI)
            $_POST['per_nom1'] = htmlspecialchars($_POST['per_nom1'] ?? '');
            $_POST['per_nom2'] = htmlspecialchars($_POST['per_nom2'] ?? '');
            $_POST['per_ape1'] = htmlspecialchars($_POST['per_ape1'] ?? '');
            $_POST['per_ape2'] = htmlspecialchars($_POST['per_ape2'] ?? '');
            $_POST['per_nac_lugar'] = htmlspecialchars($_POST['per_nac_lugar'] ?? '');
            $_POST['per_sexo'] = strtoupper($_POST['per_sexo'] ?? '');
            $_POST['per_grado'] = (int)($_POST['per_grado'] ?? 0);
            $_POST['per_arma'] = (int)($_POST['per_arma'] ?? 0);
            $_POST['per_telefono'] = (int)($_POST['per_telefono'] ?? 0);
            $_POST['per_dpi'] = htmlspecialchars($_POST['per_dpi'] ?? '');

            // Validar fecha (igual que en guardarAPI)
            if (isset($_POST['per_fec_nac']) && !empty($_POST['per_fec_nac'])) {
                $parts = explode('-', $_POST['per_fec_nac']);
                if (count($parts) === 3 && checkdate((int)$parts[1], (int)$parts[2], (int)$parts[0])) {
                    $_POST['per_fec_nac'] = $_POST['per_fec_nac'];
                } else {
                    throw new Exception("Fecha de nacimiento inválida");
                }
            } else {
                throw new Exception("Fecha de nacimiento es obligatoria");
            }

            // NO enviar per_fec_creacion en modificaciones
            unset($_POST['per_fec_creacion']);

            $id = filter_var($_POST['per_catalogo'], FILTER_SANITIZE_NUMBER_INT);

            $alumno = NuevoAlumno::find($id);
            if (!$alumno) {
                throw new Exception("Alumno no encontrado");
            }

            $alumno->sincronizar($_POST);
            $resultado = $alumno->actualizar();

            if ($resultado) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Datos del Alumno Modificados Exitosamente',
                ]);
            } else {
                throw new Exception('No se pudo actualizar en la base de datos');
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al Modificar Datos',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function eliminarAPI()
    {
        if (empty($_POST) || !isset($_POST['per_catalogo'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'ID de Alumno requerido',
            ]);
            return;
        }

        $id = filter_var($_POST['per_catalogo'], FILTER_SANITIZE_NUMBER_INT);

        if (!$id || $id <= 0) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'ID de Alumno no válido',
            ]);
            return;
        }

        try {
            $alumno = NuevoAlumno::find($id);
            if (!$alumno) {
                http_response_code(404);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Alumno no encontrado',
                ]);
                return;
            }

            $resultado = $alumno->eliminar();
            if ($resultado) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Alumno Eliminado Exitosamente',
                ]);
            } else {
                throw new Exception('No se pudo eliminar el Alumno');
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al Eliminar Alumno',
                'detalle' => $e->getMessage(),
            ]);
        }
    }
}
