<?php

namespace Model;

class Tipos extends ActiveRecord
{
    protected static $tabla = 'tipos';
    protected static $idTabla = 'tip_codigo';
    protected static $columnasDB = ['tip_nombre'];

    public $tip_codigo;
    public $tip_nombre;

    public function __construct($args = [])
    {
        $this->tip_codigo = $args['tip_codigo'] ?? null;
        $this->tip_nombre = $args['tip_nombre'] ?? '';
    }

    public static function obtenerTiposConQuery()
    {
        $sql = "SELECT * FROM tipos ORDER BY tip_codigo";
        return self::fetchArray($sql);
    }
}