<?php

namespace Model;

class Cursos extends ActiveRecord
{
    protected static $tabla = 'cursos';
    protected static $columnasDB = [
        'cur_nombre', 
        'cur_nombre_corto', 
        'cur_duracion_dias', 
        'cur_nivel', 
        'cur_tipo', 
        'cur_certificado', 
        'cur_institucion_certifica', 
        'cur_descripcion', 
        'cur_activo', 
        'fecha_registro'
    ];
    protected static $idTabla = 'cur_codigo';

    public $cur_codigo;
    public $cur_nombre;
    public $cur_nombre_corto;
    public $cur_duracion_dias;
    public $cur_nivel;
    public $cur_tipo;
    public $cur_certificado;
    public $cur_institucion_certifica;
    public $cur_descripcion;
    public $cur_activo;
    public $fecha_registro;

    public function __construct($args = [])
    {
        $this->cur_codigo = $args['cur_codigo'] ?? null;
        $this->cur_nombre = $args['cur_nombre'] ?? '';
        $this->cur_nombre_corto = $args['cur_nombre_corto'] ?? '';
        $this->cur_duracion_dias = $args['cur_duracion_dias'] ?? 0;
        $this->cur_nivel = $args['cur_nivel'] ?? '';
        $this->cur_tipo = $args['cur_tipo'] ?? '';
        $this->cur_certificado = $args['cur_certificado'] ?? '';
        $this->cur_institucion_certifica = $args['cur_institucion_certifica'] ?? '';
        $this->cur_descripcion = $args['cur_descripcion'] ?? '';
        $this->cur_activo = $args['cur_activo'] ?? 'S';
        $this->fecha_registro = $args['fecha_registro'] ?? date('Y-m-d H:i:s');
    }

    public static function obtenerCursos()
    {
        $sql = "SELECT * FROM cursos";
        return self::fetchArray($sql);
    }

    public static function obtenerCursosActivos()
    {
        $sql = "SELECT * FROM cursos WHERE cur_activo = 'S' ORDER BY cur_nombre ASC";
        return self::fetchArray($sql);
    }
}