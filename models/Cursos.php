<?php

namespace Model;

class Cursos extends ActiveRecord
{
    protected static $tabla = 'cursos';
    protected static $idTabla = 'cur_codigo';
    protected static $columnasDB = [
        'cur_codigo', // <-- agregar
        'cur_nombre',
        'cur_nombre_corto',
        'cur_duracion_dias',
        'cur_nivel',
        'cur_tipo',
        'cur_certificado',
        'cur_institucion_certifica',
        'cur_descripcion'
    ];

    public $cur_codigo;
    public $cur_nombre;
    public $cur_nombre_corto;
    public $cur_duracion_dias;
    public $cur_nivel;
    public $cur_tipo;
    public $cur_certificado;
    public $cur_institucion_certifica;
    public $cur_descripcion;

    public function __construct($args = [])
    {
        $this->cur_codigo = $args['cur_codigo'] ?? null;
        $this->cur_nombre = $args['cur_nombre'] ?? '';
        $this->cur_nombre_corto = $args['cur_nombre_corto'] ?? '';
        $this->cur_duracion_dias = $args['cur_duracion_dias'] ?? 0;
        $this->cur_nivel = $args['cur_nivel'] ?? '';
        $this->cur_tipo = $args['cur_tipo'] ?? '';
        $this->cur_certificado = $args['cur_certificado'] ?? '';
        $this->cur_institucion_certifica = $args['cur_institucion_certifica'] ?? 'NULL';
        $this->cur_descripcion = $args['cur_descripcion'] ?? '';
    }

    public static function obtenerCursos()
    {
        $sql = "SELECT * FROM cursos";
        return self::fetchArray($sql);
    }


    public static function obtenerCursos1()
    {
        $sql = "SELECT 
            c.cur_codigo,
            c.cur_nombre,
            c.cur_nombre_corto,
            c.cur_duracion_dias,
            c.cur_nivel,
            n.niv_nombre AS nivel_nombre,
            c.cur_tipo,
            t.tip_nombre AS tipo_nombre,
            c.cur_certificado,
            c.cur_institucion_certifica,
            i.inst_nombre AS institucion_nombre,
            c.cur_descripcion
        FROM cursos c
        LEFT JOIN niveles n ON c.cur_nivel = n.niv_codigo
        LEFT JOIN tipos t ON c.cur_tipo = t.tip_codigo
        LEFT JOIN instituciones i ON c.cur_institucion_certifica = i.inst_codigo";

        $resultado = self::fetchArray($sql);

        // DEBUG: Ver qué sale de la BD
        error_log("Resultado de BD: " . print_r($resultado, true));

        return $resultado;
    }
}
