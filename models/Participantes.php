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
        $this->fecha_registro = $args['fecha_registro'] ?? date('Y-m-d H:i:s');
    }

    /**
     * ✅ Validar que un alumno no esté ya registrado en una promoción
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
     */
    public static function existeCertificado($numero_certificado, $excluir_id = null)
    {
        if (empty(trim($numero_certificado))) {
            return false;
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
     * 🆕 CALCULAR POSICIÓN AUTOMÁTICA BASADA EN CALIFICACIÓN
     * Retorna la posición que le corresponde según su nota
     * 
     * @param int $promocion - ID de la promoción
     * @param float $calificacion - Calificación del alumno
     * @param int|null $excluir_id - ID del participante a excluir (para modificaciones)
     * @return int - Posición calculada
     */
    public static function calcularPosicionAutomatica($promocion, $calificacion, $excluir_id = null)
    {
        if (empty($calificacion)) {
            return null;
        }

        $sql = "SELECT COUNT(*) + 1 as posicion 
                FROM " . static::$tabla . " 
                WHERE par_promocion = " . intval($promocion) . " 
                AND par_calificacion IS NOT NULL 
                AND par_calificacion > " . floatval($calificacion);

        if ($excluir_id) {
            $sql .= " AND par_codigo != " . intval($excluir_id);
        }

        $resultado = self::fetchFirst($sql);
        return $resultado['posicion'] ?? 1;
    }

    /**
     * 🆕 RECALCULAR TODAS LAS POSICIONES DE UNA PROMOCIÓN
     * Se ejecuta después de guardar/modificar/eliminar
     * 
     * @param int $promocion - ID de la promoción
     * @return bool - true si se actualizó correctamente
     */
    public static function recalcularPosicionesPromocion($promocion)
    {
        try {
            // Obtener todos los participantes con calificación ordenados por nota DESC
            $sql = "SELECT par_codigo, par_calificacion 
                    FROM " . static::$tabla . " 
                    WHERE par_promocion = " . intval($promocion) . " 
                    AND par_calificacion IS NOT NULL 
                    ORDER BY par_calificacion DESC, par_codigo ASC";

            $participantes = self::fetchArray($sql);

            if (empty($participantes)) {
                return true; // No hay participantes con calificación
            }

            // Asignar posiciones considerando empates
            $posicion = 1;
            $calificacion_anterior = null;
            $contador = 0;

            foreach ($participantes as $participante) {
                $contador++;

                // Si la calificación es diferente a la anterior, actualizar posición
                if ($calificacion_anterior !== $participante['par_calificacion']) {
                    $posicion = $contador;
                }

                // Actualizar la posición en la base de datos
                $update_sql = "UPDATE " . static::$tabla . " 
                               SET par_posicion = {$posicion} 
                               WHERE par_codigo = " . intval($participante['par_codigo']);

                self::$db->query($update_sql);

                $calificacion_anterior = $participante['par_calificacion'];
            }

            // Limpiar posiciones de participantes sin calificación
            $limpiar_sql = "UPDATE " . static::$tabla . " 
                           SET par_posicion = NULL 
                           WHERE par_promocion = " . intval($promocion) . " 
                           AND par_calificacion IS NULL";

            self::$db->query($limpiar_sql);

            return true;
        } catch (\Exception $e) {
            error_log("Error al recalcular posiciones: " . $e->getMessage());
            return false;
        }
    }

    /**
     * 🆕 OBTENER POSICIÓN ESTIMADA (para mostrar en frontend antes de guardar)
     * 
     * @param int $promocion - ID de la promoción
     * @param float $calificacion - Calificación propuesta
     * @return array - ['posicion' => int, 'total_participantes' => int]
     */
    public static function obtenerPosicionEstimada($promocion, $calificacion)
    {
        if (empty($calificacion)) {
            return [
                'posicion' => null,
                'total_participantes' => 0,
                'mensaje' => 'Ingrese una calificación para calcular la posición'
            ];
        }

        // Contar cuántos tienen mejor nota
        $sql_posicion = "SELECT COUNT(*) + 1 as posicion 
                        FROM " . static::$tabla . " 
                        WHERE par_promocion = " . intval($promocion) . " 
                        AND par_calificacion > " . floatval($calificacion);

        // Contar total de participantes con calificación
        $sql_total = "SELECT COUNT(*) as total 
                     FROM " . static::$tabla . " 
                     WHERE par_promocion = " . intval($promocion) . " 
                     AND par_calificacion IS NOT NULL";

        $posicion_data = self::fetchFirst($sql_posicion);
        $total_data = self::fetchFirst($sql_total);

        $posicion = $posicion_data['posicion'] ?? 1;
        $total = $total_data['total'] ?? 0;

        return [
            'posicion' => $posicion,
            'total_participantes' => $total + 1, // +1 porque se incluirá este nuevo
            'mensaje' => "Este alumno quedaría en el lugar #{$posicion} de " . ($total + 1) . " participantes"
        ];
    }

    public static function obtenerParticipantes()
    {
        $sql = "SELECT 
        p.par_codigo,
        p.par_promocion,
        p.par_catalogo,
        CONCAT(
            pr.pro_numero, ' ', 
            pr.pro_anio, ' - ', 
            IFNULL(c.cur_nombre, ''), ' - ', n.niv_nombre) AS promocion_info,
        pr.pro_fecha_inicio,
        pr.pro_fecha_graduacion,
        c.cur_nombre_corto AS curso_corto,
        c.cur_nombre AS curso_nombre,
        IFNULL(n.niv_nombre, 'Sin nivel') AS nivel_curso,
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
        p.par_estado
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

    public static function getCursosPersona($per_catalogo)
    {
        $sql = "SELECT 
    m.per_catalogo,
    m.per_foto,
    CONCAT_WS(' ', m.per_nom1, m.per_nom2, m.per_ape1, m.per_ape2) AS nombre_completo,
    CONCAT(g.gra_desc_lg, ' de ', a.arm_desc_lg) AS grado_arma,
    CONCAT(c.cur_nombre, ' - Nivel ', n.niv_nombre) AS curso_completo,
    p.pro_numero,
    p.pro_anio,
    p.pro_fecha_inicio,
    p.pro_fecha_graduacion,
    par.par_calificacion,
    par.par_posicion AS puesto_obtenido,
    par.par_estado,
    pa.pais_nombre AS pais_promocion,

    -- 🟢 Mostrar SI / NO de certificado
    c.cur_certificado AS emite_certificado,

    -- Conteo de cursos que SÍ emiten certificado
    (
        SELECT COUNT(*) 
        FROM participantes par2
        INNER JOIN promociones p2 ON par2.par_promocion = p2.pro_codigo
        INNER JOIN cursos c2 ON p2.pro_curso = c2.cur_codigo
        WHERE par2.par_catalogo = m.per_catalogo
          AND c2.cur_certificado = 'Si'
    ) AS total_cursos_certificados

FROM participantes par
INNER JOIN mper m ON par.par_catalogo = m.per_catalogo
INNER JOIN grados g ON m.per_grado = g.gra_codigo
INNER JOIN armas a ON m.per_arma = a.arm_codigo
INNER JOIN promociones p ON par.par_promocion = p.pro_codigo
INNER JOIN paises pa ON p.pro_pais = pa.pais_codigo
INNER JOIN cursos c ON p.pro_curso = c.cur_codigo
INNER JOIN niveles n ON c.cur_nivel = n.niv_codigo
WHERE m.per_catalogo = {$per_catalogo}
ORDER BY p.pro_fecha_inicio DESC";

        return self::fetchArray($sql);
    }

    public static function obtenerResumenPersonal()
    {
        $sql = "SELECT 
    m.per_catalogo,
    CONCAT_WS(' ', m.per_nom1, m.per_nom2, m.per_ape1, m.per_ape2) AS nombre_completo,
    CONCAT(g.gra_desc_lg, ' de ', a.arm_desc_lg) AS grado_arma,

    -- Total de cursos (ya lo tenías)
    COUNT(p.par_codigo) AS total_cursos,

    -- 🔥 Total de cursos que emiten certificado
    (
        SELECT COUNT(*)
        FROM participantes p2
        INNER JOIN promociones pr2 ON p2.par_promocion = pr2.pro_codigo
        INNER JOIN cursos c2 ON pr2.pro_curso = c2.cur_codigo
        WHERE p2.par_catalogo = m.per_catalogo
          AND c2.cur_certificado = 'Si'
    ) AS total_cursos_certificados,

    -- Último curso (ya lo tenías)
    (
        SELECT CONCAT(c2.cur_nombre_corto, ' - Promoción ', pr2.pro_numero, ' ', pr2.pro_anio)
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

GROUP BY 
    m.per_catalogo, m.per_nom1, m.per_nom2, m.per_ape1, m.per_ape2,
    g.gra_desc_lg, a.arm_desc_lg

HAVING total_cursos > 0
ORDER BY total_cursos DESC, m.per_catalogo";

        return self::fetchArray($sql);
    }

    public static function contarCursosPersona($per_catalogo)
    {
        $sql = "SELECT COUNT(*) as total
        FROM participantes pa
        WHERE pa.par_catalogo = " . self::$db->quote($per_catalogo);

        $resultado = self::fetchArray($sql);
        return $resultado[0]['total'] ?? 0;
    }
}
