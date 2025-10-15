<?php

namespace Model;

class Grados extends ActiveRecord
{
    protected static $tabla = 'grados';
    protected static $idTabla = 'gra_codigo';
    protected static $columnasDB = ['gra_desc_lg', 'gra_desc_md', 'gra_desc_ct'];

    public $gra_codigo;
    public $gra_desc_lg;
    public $gra_desc_md;
    public $gra_desc_ct;


    public function __construct($args = [])
    {
        $this->gra_codigo = $args['gra_codigo'] ?? null;
        $this->gra_desc_lg = $args['gra_desc_lg'] ?? '';
        $this->gra_desc_md = $args['gra_desc_md'] ?? '';
        $this->gra_desc_ct = $args['gra_desc_ct'] ?? '';
    }

    public static function obtenergradoconQuery()
    {
        // Concatenar tutor_nombre y tutor_apellido con un espacio entre ellos
        $sql = "SELECT * FROM grados";

        return self::fetchArray($sql);
    }

}
