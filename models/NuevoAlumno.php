<?php

namespace Model;

class NuevoAlumno extends ActiveRecord
{
    protected static $tabla = 'mper';
    protected static $columnasDB = ['per_grado', 'per_arma', 'per_nom1', 'per_nom2', 'per_ape1', 'per_ape2', 'per_telefono', 'per_sexo', 'per_fec_nac', 'per_nac_lugar', 'per_dpi'];
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

    public function __construct($args = [])
    {
        $this->per_catalogo = $args['per_catalogo'] ?? null;
        $this->per_nom1 = $args['per_nom1'] ?? '';
        $this->per_nom2 = $args['per_nom2'] ?? '';
        $this->per_ape1 = $args['per_ape1'] ?? '';
        $this->per_ape2 = $args['per_ape2'] ?? '';
        $this->per_grado = $args['per_grado'] ?? '';
        $this->per_arma = $args['per_arma'] ?? '';
        $this->per_telefono = $args['per_telefono'] ?? '';
        $this->per_sexo = $args['per_sexo'] ?? '';
        $this->per_fec_nac = $args['per_fec_nac'] ?? '';
        $this->per_nac_lugar = $args['per_nac_lugar'] ?? '';
        $this->per_dpi = $args['per_dpi'] ?? '';
        $this->per_fec_creacion = date('Y-m-d');
        $this->per_estado = $args['per_estado'] ?? '1';
    }


    public static function obteneralumnos()
    {
        $sql = "SELECT * FROM mper";
        return self::fetchArray($sql);
    }
}
