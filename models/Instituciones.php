<?php

namespace Model;

class Instituciones extends ActiveRecord
{
    protected static $tabla = 'instituciones';
    protected static $idTabla = 'inst_codigo';
    protected static $columnasDB = ['inst_nombre', 'inst_siglas', 'inst_tipo', 'inst_activa'];

    public $inst_codigo;
    public $inst_nombre;
    public $inst_siglas;
    public $inst_tipo;
    public $inst_activa;


    public function __construct($args = [])
    {
        $this->inst_codigo = $args['inst_codigo'] ?? null;
        $this->inst_nombre = $args['inst_nombre'] ?? '';
        $this->inst_siglas = $args['inst_siglas'] ?? '';
        $this->inst_tipo = $args['inst_tipo'] ?? '';
        $this->inst_activa = $args['inst_activa'] ?? 1;
    }

    public static function obtenerinstitucionQuery()
    {
        // Concatenar tutor_nombre y tutor_apellido con un espacio entre ellos
        $sql = "SELECT * FROM instituciones";

        return self::fetchArray($sql);
    }

}
