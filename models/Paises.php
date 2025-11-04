<?php

namespace Model;

class Paises extends ActiveRecord
{
    protected static $tabla = 'paises';
    protected static $idTabla = 'pais_codigo';
    protected static $columnasDB = ['pais_nombre', 'pais_codigo_iso'];

    public $pais_codigo;
    public $pais_nombre;
    public $pais_codigo_iso;

    public function __construct($args = [])
    {
        $this->pais_codigo = $args['pais_codigo'] ?? null;
        $this->pais_nombre = $args['pais_nombre'] ?? '';
        $this->pais_codigo_iso = $args['pais_codigo_iso'] ?? '';
    }

    public static function obtenerPaisesOrdenados()
    {
        $sql = "SELECT * FROM paises ORDER BY pais_nombre ASC";
        return self::fetchArray($sql);
    }
}
