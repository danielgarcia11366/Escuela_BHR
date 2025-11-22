<?php

namespace Model;

class Participantes extends ActiveRecord
{
    protected static $tabla = 'participantes';
    protected static $idTabla = 'par_codigo';

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

    public function __construct($args = [])
    {
        $this->par_codigo = $args['par_codigo'] ?? null;
        $this->par_promocion = $args['par_promocion'] ?? null;
        $this->par_catalogo = $args['par_catalogo'] ?? null;
        $this->par_calificacion = $args['par_calificacion'] ?? null;
        $this->par_posicion = $args['par_posicion'] ?? null;
        $this->par_certificado_numero = $args['par_certificado_numero'] ?? '';
        $this->par_certificado_fecha = $args['par_certificado_fecha'] ?? null;
        $this->par_estado = $args['par_estado'] ?? 'C';
        $this->par_observaciones = $args['par_observaciones'] ?? '';
        $this->fecha_registro = $args['fecha_registro'] ?? date('Y-m-d H:i:s');
    }

    /**
     * ✅ Validar que un alumno no esté ya registrado en una promoción
     * @param int $catalogo - Catálogo del alumno
     * @param int $promocion - ID de la promoción
     * @param int|null $excluir_id - ID del registro a excluir (para modificaciones)
     * @return bool - true si ya existe, false si no existe
     */
    public static function existeEnPromocion($catalogo, $promocion, $excluir_id = null)
    {
        $sql = "SELECT par_codigo FROM " . static::$tabla . " 
                WHERE par_catalogo = " . intval($catalogo) . " 
                AND par_promocion = " . intval($promocion);

        if ($excluir_id) {
            $sql .= " AND par_codigo != " . intval($excluir_id);
        }

        $resultado = self::fetchFirst($sql);
        return !empty($resultado);
    }

    /**
     * ✅ Validar que el número de certificado sea único
     * @param string $numero_certificado - Número del certificado
     * @param int|null $excluir_id - ID del registro a excluir (para modificaciones)
     * @return bool - true si ya existe, false si no existe
     */
    public static function existeCertificado($numero_certificado, $excluir_id = null)
    {
        if (empty(trim($numero_certificado))) {
            return false; // Si no hay certificado, no validamos
        }

        $numero_certificado = self::$db->quote($numero_certificado);
        $sql = "SELECT par_codigo FROM " . static::$tabla . " 
                WHERE par_certificado_numero = {$numero_certificado}";

        if ($excluir_id) {
            $sql .= " AND par_codigo != " . intval($excluir_id);
        }

        $resultado = self::fetchFirst($sql);
        return !empty($resultado);
    }

    /**
     * ✅ Validar que la posición no esté ocupada en la promoción
     * @param int $promocion - ID de la promoción
     * @param int $posicion - Número de posición
     * @param int|null $excluir_id - ID del registro a excluir (para modificaciones)
     * @return bool - true si ya existe, false si no existe
     */
    public static function existePosicionEnPromocion($promocion, $posicion, $excluir_id = null)
    {
        if (empty($posicion)) {
            return false; // Si no hay posición, no validamos
        }

        $sql = "SELECT par_codigo FROM " . static::$tabla . " 
                WHERE par_promocion = " . intval($promocion) . " 
                AND par_posicion = " . intval($posicion);

        if ($excluir_id) {
            $sql .= " AND par_codigo != " . intval($excluir_id);
        }

        $resultado = self::fetchFirst($sql);
        return !empty($resultado);
    }

    public static function obtenerParticipantes()
    {
        $sql = "SELECT 
        p.par_codigo,
        p.par_promocion,
        p.par_catalogo,
        -- Información de promoción con nivel del curso
        CONCAT(
            pr.pro_numero, ' ', 
            pr.pro_anio, ' - ', 
            IFNULL(c.cur_nombre, ''), ' - ', n.niv_nombre) AS promocion_info,
        pr.pro_fecha_inicio,
        pr.pro_fecha_fin,
        c.cur_nombre_corto AS curso_corto,
        c.cur_nombre AS curso_nombre,
        IFNULL(n.niv_nombre, 'Sin nivel') AS nivel_curso,
        -- Nombre completo del participante
        CONCAT(
            g.gra_desc_lg, ' de ', 
            a.arm_desc_lg, ' ', 
            m.per_nom1, ' ', 
            IFNULL(m.per_nom2, ''), ' ', 
            m.per_ape1, ' ', 
            IFNULL(m.per_ape2, '')
        ) AS participante_nombre,
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
    LEFT JOIN niveles n ON c.cur_nivel = n.niv_codigo
    INNER JOIN mper m ON p.par_catalogo = m.per_catalogo
    LEFT JOIN grados g ON m.per_grado = g.gra_codigo
    LEFT JOIN armas a ON m.per_arma = a.arm_codigo
    ORDER BY p.par_codigo DESC";

        return self::fetchArray($sql);
    }

    public static function obtenerPorPromocion($promocion_id)
    {
        $sql = "SELECT * FROM " . static::$tabla . " WHERE par_promocion = " . intval($promocion_id);
        return self::fetchArray($sql);
    }

    // En models/Participantes.php
    public static function getCursosPersona($per_catalogo)
    {
        $sql = "SELECT 
    m.per_catalogo,
    m.per_foto,
    CONCAT_WS(' ', m.per_nom1, m.per_nom2, m.per_ape1, m.per_ape2) AS nombre_completo,
    CONCAT(g.gra_desc_lg, ' de ', a.arm_desc_lg) AS grado_arma,

    -- Aquí agregamos el nivel al nombre del curso
    CONCAT(c.cur_nombre, ' - Nivel ', n.niv_nombre) AS curso_completo,

    p.pro_numero,
    p.pro_anio,
    p.pro_fecha_inicio,
    p.pro_fecha_fin,
    par.par_calificacion,
    par.par_estado
FROM participantes par
INNER JOIN mper m ON par.par_catalogo = m.per_catalogo
INNER JOIN grados g ON m.per_grado = g.gra_codigo
INNER JOIN armas a ON m.per_arma = a.arm_codigo
INNER JOIN promociones p ON par.par_promocion = p.pro_codigo
INNER JOIN cursos c ON p.pro_curso = c.cur_codigo
INNER JOIN niveles n ON c.cur_nivel = n.niv_codigo
WHERE m.per_catalogo = {$per_catalogo}
ORDER BY p.pro_fecha_inicio DESC;";

        return self::fetchArray($sql);
    }
    /**
     *
     * ✅ Obtener resumen de personal con sus cursos
     * @return array Lista de personas con total de cursos y último curso
     */
    public static function obtenerResumenPersonal()
    {
        $sql = "SELECT 
        m.per_catalogo,
        CONCAT_WS(' ', m.per_nom1, m.per_nom2, m.per_ape1, m.per_ape2) AS nombre_completo,
        CONCAT(g.gra_desc_lg, ' de ', a.arm_desc_lg) AS grado_arma,
        COUNT(p.par_codigo) AS total_cursos,
        (SELECT CONCAT(c2.cur_nombre_corto, ' - Promoción ', pr2.pro_numero, ' ', pr2.pro_anio)
         FROM participantes p2
         INNER JOIN promociones pr2 ON p2.par_promocion = pr2.pro_codigo
         INNER JOIN cursos c2 ON pr2.pro_curso = c2.cur_codigo
         WHERE p2.par_catalogo = m.per_catalogo
         ORDER BY pr2.pro_fecha_inicio DESC
         LIMIT 1
        ) AS ultimo_curso
    FROM mper m
    LEFT JOIN grados g ON m.per_grado = g.gra_codigo
    LEFT JOIN armas a ON m.per_arma = a.arm_codigo
    LEFT JOIN participantes p ON m.per_catalogo = p.par_catalogo
    GROUP BY m.per_catalogo, m.per_nom1, m.per_nom2, m.per_ape1, m.per_ape2, 
             g.gra_desc_lg, a.arm_desc_lg
    HAVING total_cursos > 0
    ORDER BY total_cursos DESC, m.per_catalogo";

        return self::fetchArray($sql);
    }
    /**
     * Contar total de cursos de una persona
     * @param int $per_catalogo
     * @return int Cantidad de cursos
     */
    public static function contarCursosPersona($per_catalogo)
    {
        $sql = "SELECT COUNT(*) as total
        FROM participantes pa
        WHERE pa.par_catalogo = " . self::$db->quote($per_catalogo);

        $resultado = self::fetchArray($sql);
        return $resultado[0]['total'] ?? 0;
    }
}
