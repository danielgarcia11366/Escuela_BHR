<?php

namespace Model;

use Exception;

class NuevoAlumno extends ActiveRecord
{
    protected static $tabla = 'mper';
    protected static $columnasDB = [
        'per_grado',
        'per_arma',
        'per_nom1',
        'per_nom2',
        'per_ape1',
        'per_ape2',
        'per_telefono',
        'per_sexo',
        'per_fec_nac',
        'per_nac_lugar',
        'per_dpi'
    ];
    protected static $idTabla = 'per_catalogo';

    public $per_catalogo;
    public $per_nom1;
    public $per_nom2;
    public $per_ape1;
    public $per_ape2;
    public $per_grado;
    public $per_arma;
    public $per_telefono;
    public $per_sexo;
    public $per_fec_nac;
    public $per_nac_lugar;
    public $per_dpi;
    public $per_fec_creacion;
    public $per_estado;

    // EN TU MODELO - Método alternativo para el constructor:
    public function __construct($args = [])
    {
        $this->per_catalogo   = $args['per_catalogo'] ?? null;
        $this->per_nom1       = trim($args['per_nom1'] ?? '');
        $this->per_nom2       = trim($args['per_nom2'] ?? '');
        $this->per_ape1       = trim($args['per_ape1'] ?? '');
        $this->per_ape2       = trim($args['per_ape2'] ?? '');
        $this->per_grado      = $args['per_grado'] ?? null;
        $this->per_arma       = $args['per_arma'] ?? null;
        $this->per_telefono   = $args['per_telefono'] ?? null;
        $this->per_sexo       = strtoupper($args['per_sexo'] ?? '');
        $this->per_nac_lugar  = trim($args['per_nac_lugar'] ?? '');
        $this->per_dpi        = trim($args['per_dpi'] ?? '');
        $this->per_fec_creacion = date('Y-m-d H:i:s');
        $this->per_estado     = $args['per_estado'] ?? '1';

        // DEBUG: Ver qué fecha llega al constructor
        error_log("FECHA EN CONSTRUCTOR: " . ($args['per_fec_nac'] ?? 'VACÍA'));

        // FECHA DE NACIMIENTO - Validación mejorada
        $this->per_fec_nac = $this->validarFechaNacimiento($args['per_fec_nac'] ?? '');

        // DEBUG: Ver qué fecha se asignó finalmente
        error_log("FECHA FINAL ASIGNADA AL OBJETO: " . $this->per_fec_nac);
    }

    private function validarFechaNacimiento($fecha)
    {
        // Si está vacía, lanzar excepción
        if (empty($fecha)) {
            throw new Exception("La fecha de nacimiento es obligatoria");
        }

        // Validar formato YYYY-MM-DD
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
            throw new Exception("El formato de fecha debe ser YYYY-MM-DD");
        }

        $parts = explode('-', $fecha);
        $year = (int)$parts[0];
        $month = (int)$parts[1];
        $day = (int)$parts[2];

        // Validar que sea una fecha válida
        if (!checkdate($month, $day, $year)) {
            throw new Exception("La fecha de nacimiento no es válida");
        }

        // VALIDACIONES ESPECÍFICAS PARA INFORMIX
        // Informix tiene un rango válido de años (típicamente 0001-9999)
        if ($year < 1900 || $year > 2100) {
            throw new Exception("El año debe estar entre 1900 y 2100");
        }

        // Validar que no sea fecha futura
        $fechaNac = new \DateTime($fecha);
        $hoy = new \DateTime();
        if ($fechaNac > $hoy) {
            throw new Exception("La fecha de nacimiento no puede ser futura");
        }

        // Validar rango de edad razonable
        $hace120Anos = new \DateTime('-120 years');
        if ($fechaNac < $hace120Anos) {
            throw new Exception("La fecha de nacimiento no puede ser mayor a 120 años");
        }

        // CONVERTIR A FORMATO INFORMIX ESPECÍFICO
        // Informix prefiere formato MDY o DMY dependiendo de la configuración
        return $this->formatearFechaParaInformix($fecha);
    }

    private function formatearFechaParaInformix($fecha)
    {
        try {
            $dt = new \DateTime($fecha);

            // Probar diferentes formatos que acepta Informix
            // Formato más común: 'YYYY-MM-DD' pero sin leading zeros problemáticos
            $year = $dt->format('Y');
            $month = (int)$dt->format('m'); // Sin leading zero
            $day = (int)$dt->format('d');   // Sin leading zero

            // Retornar en formato que Informix entienda mejor
            return sprintf('%04d-%02d-%02d', $year, $month, $day);
        } catch (Exception $e) {
            throw new Exception("Error al formatear fecha para Informix: " . $e->getMessage());
        }
    }

    // Método para obtener todos los alumnos
    public static function obteneralumnos()
    {
        $sql = "SELECT 
        TRIM(g.gra_desc_lg) || ' DE ' || TRIM(a.arm_desc_lg) AS grado_arma,
        TRIM(p.per_nom1) || ' ' || TRIM(p.per_nom2) || ' ' || TRIM(p.per_ape1) || ' ' || TRIM(p.per_ape2) AS nombre_completo,
        p.per_catalogo,
        p.per_telefono AS telefono,
        CASE 
            WHEN p.per_sexo = 'M' THEN 'Masculino'
            WHEN p.per_sexo = 'F' THEN 'Femenino'
            ELSE 'No especificado'
        END AS sexo,
        p.per_fec_nac AS fecha_nacimiento,
        TRIM(p.per_nac_lugar) AS lugar_nacimiento,
        TRIM(p.per_dpi) AS numero_dpi,
        -- CAMPOS INDIVIDUALES PARA EL FORMULARIO
        TRIM(p.per_nom1) AS per_nom1,
        TRIM(p.per_nom2) AS per_nom2,
        TRIM(p.per_ape1) AS per_ape1,
        TRIM(p.per_ape2) AS per_ape2,
        p.per_grado,
        p.per_arma,
        p.per_sexo,
        p.per_fec_nac,
        TRIM(p.per_nac_lugar) AS per_nac_lugar,
        TRIM(p.per_dpi) AS per_dpi
    FROM informix.mper p
    LEFT JOIN informix.grados g ON p.per_grado = g.gra_codigo
    LEFT JOIN informix.armas a ON p.per_arma = a.arm_codigo
    ORDER BY p.per_catalogo";
        return self::fetchArray($sql);
    }





    
}
