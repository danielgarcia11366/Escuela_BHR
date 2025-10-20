<?php

namespace Model;

class Prueba extends ActiveRecord
{
    protected static $tabla = 'prueba';
    protected static $idTabla = 'prueba_id';
    protected static $columnasDB = ['nombre'];

    public $prueba_id;
    public $nombre;

    public function __construct($args = [])
    {
        $this->prueba_id = $args['prueba_id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
    }
}