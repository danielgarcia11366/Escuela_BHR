<?php

namespace Model;

class Personal extends ActiveRecord
{
    protected static $tabla = 'mper';
    protected static $idTabla = 'per_catalogo';
    protected static $columnasDB = [
        'per_catalogo',
        'per_serie',
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
        'per_dpi',
        'per_tipo_doc',
        'per_email',
        'per_direccion',
        'per_estado',
        'per_tipo',
        'fecha_registro',
        'fecha_modificacion',
        'observaciones',
        'per_foto'  // ⭐ NUEVO CAMPO
    ];

    public $per_catalogo;
    public $per_serie;
    public $per_grado;
    public $per_arma;
    public $per_nom1;
    public $per_nom2;
    public $per_ape1;
    public $per_ape2;
    public $per_telefono;
    public $per_sexo;
    public $per_fec_nac;
    public $per_nac_lugar;
    public $per_dpi;
    public $per_tipo_doc;
    public $per_email;
    public $per_direccion;
    public $per_estado;
    public $per_tipo;
    public $fecha_registro;
    public $fecha_modificacion;
    public $observaciones;
    public $per_foto;  // ⭐ NUEVA PROPIEDAD

    public function __construct($args = [])
    {
        $this->per_catalogo = $args['per_catalogo'] ?? null;
        $this->per_serie = $args['per_serie'] ?? '';
        $this->per_grado = $args['per_grado'] ?? '';
        $this->per_arma = $args['per_arma'] ?? '';
        $this->per_nom1 = $args['per_nom1'] ?? '';
        $this->per_nom2 = $args['per_nom2'] ?? '';
        $this->per_ape1 = $args['per_ape1'] ?? '';
        $this->per_ape2 = $args['per_ape2'] ?? '';
        $this->per_telefono = $args['per_telefono'] ?? '';
        $this->per_sexo = $args['per_sexo'] ?? '';
        $this->per_fec_nac = $args['per_fec_nac'] ?? '';
        $this->per_nac_lugar = $args['per_nac_lugar'] ?? '';
        $this->per_dpi = $args['per_dpi'] ?? '';
        $this->per_tipo_doc = $args['per_tipo_doc'] ?? 'DPI';
        $this->per_email = $args['per_email'] ?? '';
        $this->per_direccion = $args['per_direccion'] ?? '';
        $this->per_estado = $args['per_estado'] ?? 'A';
        $this->per_tipo = $args['per_tipo'] ?? 'A';
        $this->fecha_registro = date('Y-m-d H:i:s');
        $this->fecha_modificacion = date('Y-m-d H:i:s');
        $this->observaciones = $args['observaciones'] ?? '';
        $this->per_foto = $args['per_foto'] ?? null;  // ⭐ NUEVO
    }

    public function atributos()
    {
        $atributos = [];
        foreach (static::$columnasDB as $columna) {
            $columna = strtolower($columna);
            $atributos[$columna] = $this->$columna ?? null;
        }
        return $atributos;
    }

    public static function obtenerPersonal()
    {
        $sql = "SELECT 
            m.per_catalogo,
            m.per_serie,
            m.per_grado,
            m.per_arma,
            m.per_nom1,
            m.per_nom2,
            m.per_ape1,
            m.per_ape2,
            CONCAT_WS(' ', m.per_nom1, m.per_nom2, m.per_ape1, m.per_ape2) AS nombre_completo,
            CONCAT(g.gra_desc_lg, ' de ', a.arm_desc_lg) AS grado_arma,
            m.per_telefono,
            m.per_sexo,
            m.per_fec_nac,
            m.per_nac_lugar,
            m.per_dpi,
            m.per_tipo_doc,
            m.per_email,
            m.per_direccion,
            m.per_estado,
            m.per_tipo,
            m.fecha_registro,
            m.fecha_modificacion,
            m.observaciones,
            m.per_foto
        FROM mper m
        INNER JOIN grados g ON m.per_grado = g.gra_codigo
        INNER JOIN armas a ON m.per_arma = a.arm_codigo
        ORDER BY m.per_catalogo DESC";

        return self::fetchArray($sql);
    }
}
