<?php

namespace Model;

class Armas extends ActiveRecord
{
    protected static $tabla = 'armas';
    protected static $idTabla = 'arm_codigo';
    protected static $columnasDB = ['arm_desc_lg', 'arm_desc_md', 'arm_desc_ct'];

    public $arm_codigo;
    public $arm_desc_lg;
    public $arm_desc_md;
    public $arm_desc_ct;


    public function __construct($args = [])
    {
        $this->arm_codigo = $args['arm_codigo'] ?? null;
        $this->arm_desc_lg = $args['arm_desc_lg'] ?? '';
        $this->arm_desc_md = $args['arm_desc_md'] ?? '';
        $this->arm_desc_ct = $args['arm_desc_ct'] ?? '';
    }

    public static function obtenerarmaconQuery()
    {
        // Concatenar tutor_nombre y tutor_apellido con un espacio entre ellos
        $sql = "SELECT * FROM armas";

        return self::fetchArray($sql);
    }

}
