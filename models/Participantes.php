<?php

namespace Model;

class Participantes extends ActiveRecord
{
    // Nombre de la tabla y su llave primaria
    protected static $tabla = 'participantes';
    protected static $idTabla = 'par_codigo';

    // Columnas de la tabla
    protected static $columnasDB = [
        'par_codigo',
        'par_promocion',
        'par_catalogo',
        'par_calificacion',
        'par_posicion',
        'par_certificado_numero',
        'par_certificado_fecha',
        'par_estado',
        'par_observaciones',
        'fecha_registro'
    ];

    // Propiedades públicas
    public $par_codigo;
    public $par_promocion;
    public $par_catalogo;
    public $par_calificacion;
    public $par_posicion;
    public $par_certificado_numero;
    public $par_certificado_fecha;
    public $par_estado;
    public $par_observaciones;
    public $fecha_registro;

    // Constructor
    public function __construct($args = [])
    {
        $this->par_codigo = $args['par_codigo'] ?? null;
        $this->par_promocion = $args['par_promocion'] ?? null;
        $this->par_catalogo = $args['par_catalogo'] ?? null;
        $this->par_calificacion = $args['par_calificacion'] ?? null;
        $this->par_posicion = $args['par_posicion'] ?? null;
        $this->par_certificado_numero = $args['par_certificado_numero'] ?? '';
        $this->par_certificado_fecha = $args['par_certificado_fecha'] ?? null;
        $this->par_estado = $args['par_estado'] ?? 'C'; // Valor por defecto: Cursando
        $this->par_observaciones = $args['par_observaciones'] ?? '';
        $this->fecha_registro = $args['fecha_registro'] ?? date('Y-m-d H:i:s');
    }

    // Método para obtener todos los participantes
    public static function obtenerParticipantes()
    {
        $sql = "SELECT 
    p.par_codigo,
    p.par_promocion,
    p.par_catalogo,
    -- Campos concatenados
    CONCAT(pr.pro_numero,' ',pr.pro_anio, ' - ', c.cur_nombre) AS promocion_info,
    pr.pro_fecha_inicio,
    pr.pro_fecha_fin,
    c.cur_nombre_corto AS curso_corto,
    p.par_catalogo,
    -- Nombre completo del participante
    CONCAT(g.gra_desc_lg, ' de ', a.arm_desc_lg, ' ' , m.per_nom1, ' ', IFNULL(m.per_nom2, ''), ' ', m.per_ape1, ' ', IFNULL(m.per_ape2, '')) AS participante_nombre,
    g.gra_desc_md AS grado,
    a.arm_desc_md AS arma,
    p.par_calificacion,
    p.par_posicion,
    p.par_certificado_numero,
    p.par_certificado_fecha,
    p.par_estado,
    p.par_observaciones
    
FROM participantes p
INNER JOIN promociones pr ON p.par_promocion = pr.pro_codigo
INNER JOIN cursos c ON pr.pro_curso = c.cur_codigo
INNER JOIN mper m ON p.par_catalogo = m.per_catalogo
LEFT JOIN grados g ON m.per_grado = g.gra_codigo
LEFT JOIN armas a ON m.per_arma = a.arm_codigo
ORDER BY p.par_codigo DESC";

        return self::fetchArray($sql);
    }

    // Ejemplo de método para obtener participantes de una promoción específica
    public static function obtenerPorPromocion($promocion_id)
    {
        $sql = "SELECT * FROM " . static::$tabla . " WHERE par_promocion = " . intval($promocion_id);
        return self::fetchArray($sql);
    }
}
