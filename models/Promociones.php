<?php

namespace Model;

class Promociones extends ActiveRecord
{
    protected static $tabla = 'cursos';
    // Si no vas a enviar fecha manualmente, quítala del array
    protected static $columnasDB = ['cur_nombre', 'cur_desc_lg', 'cur_duracion', 'cur_estado'];
    protected static $idTabla = 'cur_codigo';

    public $cur_codigo;
    public $cur_nombre;
    public $cur_desc_lg;
    public $cur_duracion;
    public $cur_estado;
    public $cur_fec_creacion;

    public function __construct($args = [])
    {
        $this->cur_codigo = $args['cur_codigo'] ?? null;
        $this->cur_nombre = $args['cur_nombre'] ?? '';
        $this->cur_desc_lg = $args['cur_desc_lg'] ?? '';
        $this->cur_duracion = $args['cur_duracion'] ?? 0; // Cambié a 0 para SMALLINT
        $this->cur_estado = $args['cur_estado'] ?? 'A';
        $this->cur_fec_creacion = $args['cur_fec_creacion'] ?? null; // null para usar DEFAULT
    }



    public static function obtenerCursos()
    {
        $sql = "SELECT * FROM cursos";
        return self::fetchArray($sql);
    }
}
