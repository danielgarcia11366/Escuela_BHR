<?php

namespace Model;

class Niveles extends ActiveRecord
{
    protected static $tabla = 'niveles';
    protected static $idTabla = 'niv_codigo';
    protected static $columnasDB = ['niv_nombre'];

    public $niv_codigo;
    public $niv_nombre;

    public function __construct($args = [])
    {
        $this->niv_codigo = $args['niv_codigo'] ?? null;
        $this->niv_nombre = $args['niv_nombre'] ?? '';
    }

    public static function obtenerNivelesConQuery()
    {
        $sql = "SELECT * FROM niveles ORDER BY niv_codigo";
        return self::fetchArray($sql);
    }
}