<?php

namespace Model;

class Promociones extends ActiveRecord
{
    protected static $tabla = 'promociones';
    protected static $idTabla = 'pro_codigo';
    protected static $columnasDB = [
        'pro_curso',
        'pro_numero',
        'pro_anio',
        'pro_fecha_inicio',
        'pro_fecha_fin',
        'pro_fecha_graduacion',
        'pro_lugar',
        'pro_pais',
        'pro_institucion_imparte',
        'pro_cantidad_graduados',
        'pro_observaciones',
        'pro_activa'
    ];

    public $pro_codigo;
    public $pro_curso;
    public $pro_numero;
    public $pro_anio;
    public $pro_fecha_inicio;
    public $pro_fecha_fin;
    public $pro_fecha_graduacion;
    public $pro_lugar;
    public $pro_pais;
    public $pro_institucion_imparte;
    public $pro_cantidad_graduados;
    public $pro_observaciones;
    public $pro_activa;

    public function __construct($args = [])
    {
        $this->pro_codigo = $args['pro_codigo'] ?? null;
        $this->pro_curso = $args['pro_curso'] ?? null;
        $this->pro_numero = $args['pro_numero'] ?? '';
        $this->pro_anio = $args['pro_anio'] ?? date('Y');
        $this->pro_fecha_inicio = $args['pro_fecha_inicio'] ?? '';
        $this->pro_fecha_fin = $args['pro_fecha_fin'] ?? '';
        $this->pro_fecha_graduacion = $args['pro_fecha_graduacion'] ?? null;
        $this->pro_lugar = $args['pro_lugar'] ?? '';
        $this->pro_pais = $args['pro_pais'] ?? null;
        $this->pro_institucion_imparte = $args['pro_institucion_imparte'] ?? null;
        $this->pro_cantidad_graduados = $args['pro_cantidad_graduados'] ?? 0;
        $this->pro_observaciones = $args['pro_observaciones'] ?? '';
        $this->pro_activa = $args['pro_activa'] ?? 'S';
    }

    public static function obtenerPromocionesConDetalles()
    {
        $sql = "SELECT 
                p.pro_codigo,
                p.pro_numero,
                p.pro_anio,
                CONCAT(p.pro_numero, '-', p.pro_anio) as numero_anio,
                p.pro_fecha_inicio,
                p.pro_fecha_fin,
                p.pro_fecha_graduacion,
                p.pro_lugar,
                p.pro_cantidad_graduados,
                p.pro_observaciones,
                p.pro_activa,
                p.pro_curso,
                p.pro_pais,
                p.pro_institucion_imparte,
                c.cur_nombre as curso_nombre,
                COALESCE(pa.pais_nombre, 'Guatemala') as pais_nombre,
                COALESCE(i.inst_nombre, 'Sin institución') as institucion_nombre
            FROM promociones p
            INNER JOIN cursos c ON p.pro_curso = c.cur_codigo
            LEFT JOIN paises pa ON p.pro_pais = pa.pais_codigo
            LEFT JOIN instituciones i ON p.pro_institucion_imparte = i.inst_codigo
            ORDER BY p.pro_anio DESC, p.pro_numero DESC";

        return self::fetchArray($sql);
    }
}
