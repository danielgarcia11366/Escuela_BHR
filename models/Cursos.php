<?php

namespace Model;

class Cursos extends ActiveRecord
{
    protected static $tabla = 'cursos';
    protected static $idTabla = 'cur_codigo';
    protected static $columnasDB = [
        'cur_codigo',
        'cur_nombre',
        'cur_nombre_corto',
        'cur_duracion_dias',
        'cur_nivel',
        'cur_tipo',
        'cur_certificado',
        'cur_institucion_certifica',
        'cur_descripcion'
    ];

    public $cur_codigo;
    public $cur_nombre;
    public $cur_nombre_corto;
    public $cur_duracion_dias;
    public $cur_nivel;
    public $cur_tipo;
    public $cur_certificado;
    public $cur_institucion_certifica;
    public $cur_descripcion;

    public function __construct($args = [])
    {
        $this->cur_codigo = $args['cur_codigo'] ?? null;
        $this->cur_nombre = $args['cur_nombre'] ?? '';
        $this->cur_nombre_corto = $args['cur_nombre_corto'] ?? '';
        $this->cur_duracion_dias = $args['cur_duracion_dias'] ?? 0;
        $this->cur_nivel = $args['cur_nivel'] ?? '';
        $this->cur_tipo = $args['cur_tipo'] ?? '';
        $this->cur_certificado = $args['cur_certificado'] ?? '';
        $this->cur_institucion_certifica = $args['cur_institucion_certifica'] ?? null;
        $this->cur_descripcion = $args['cur_descripcion'] ?? '';
    }

    public static function obtenerCursos()
    {
        $sql = "SELECT * FROM cursos";
        return self::fetchArray($sql);
    }

    public static function obtenerCursos1()
    {
        $sql = "SELECT 
            c.cur_codigo,
            c.cur_nombre,
            CONCAT(c.cur_nombre, ' - ', COALESCE(n.niv_nombre, 'Sin nivel')) AS curso_completo,
            c.cur_nombre_corto,
            c.cur_duracion_dias,
            c.cur_nivel,
            n.niv_nombre AS nivel_nombre,
            c.cur_tipo,
            t.tip_nombre AS tipo_nombre,
            c.cur_certificado,
            c.cur_institucion_certifica,
            i.inst_nombre AS institucion_nombre,
            i.inst_siglas AS institucion_siglas,
            c.cur_descripcion
        FROM cursos c
        LEFT JOIN niveles n ON c.cur_nivel = n.niv_codigo
        LEFT JOIN tipos t ON c.cur_tipo = t.tip_codigo
        LEFT JOIN instituciones i ON c.cur_institucion_certifica = i.inst_codigo";

        return self::fetchArray($sql);
    }

    // ========================================================
    // 🆕 FUNCIONES PARA VERIFICACIÓN DE CERTIFICACIÓN
    // ========================================================

    /**
     * 🆕 VERIFICAR SI UN CURSO EMITE CERTIFICADO
     * @param int $cur_codigo - ID del curso
     * @return array|null - Información del curso y certificación
     */
    public static function verificarCertificacion($cur_codigo)
    {
        $sql = "SELECT 
                    c.cur_codigo,
                    c.cur_nombre,
                    c.cur_nombre_corto,
                    c.cur_certificado,
                    c.cur_institucion_certifica,
                    i.inst_nombre AS institucion_nombre,
                    i.inst_siglas AS institucion_siglas
                FROM cursos c
                LEFT JOIN instituciones i ON c.cur_institucion_certifica = i.inst_codigo
                WHERE c.cur_codigo = " . intval($cur_codigo);

        $resultado = self::fetchFirst($sql);

        if ($resultado) {
            // Normalizar el valor de certificado
            $resultado['emite_certificado'] = (
                strtolower(trim($resultado['cur_certificado'])) === 'si' ||
                $resultado['cur_certificado'] === '1' ||
                $resultado['cur_certificado'] === 1
            );

            // Mensaje descriptivo con siglas
            $inst_text = $resultado['institucion_nombre'];
            if ($resultado['inst_siglas']) {
                $inst_text .= ' (' . $resultado['inst_siglas'] . ')';
            }

            $resultado['mensaje'] = $resultado['emite_certificado']
                ? "✅ Este curso emite certificación" . ($inst_text ? " por " . $inst_text : "")
                : "ℹ️ Este curso no emite certificación oficial";
        }

        return $resultado;
    }

    /**
     * 🆕 OBTENER INFO DE CURSO POR PROMOCIÓN
     * ⭐ Esta es la función PRINCIPAL que usa tu JavaScript
     * 
     * @param int $promocion_codigo - ID de la promoción
     * @return array|null - Información del curso de esa promoción
     */
    public static function obtenerPorPromocion($promocion_codigo)
    {
        $sql = "SELECT 
                    c.cur_codigo,
                    c.cur_nombre,
                    c.cur_nombre_corto,
                    c.cur_duracion_dias,
                    c.cur_certificado,
                    c.cur_institucion_certifica,
                    c.cur_descripcion,
                    i.inst_codigo,
                    i.inst_nombre,
                    i.inst_siglas,
                    i.inst_tipo,
                    n.niv_nombre AS nivel_nombre,
                    t.tip_nombre AS tipo_nombre
                FROM promociones p
                INNER JOIN cursos c ON p.pro_curso = c.cur_codigo
                LEFT JOIN instituciones i ON c.cur_institucion_certifica = i.inst_codigo
                LEFT JOIN niveles n ON c.cur_nivel = n.niv_codigo
                LEFT JOIN tipos t ON c.cur_tipo = t.tip_codigo
                WHERE p.pro_codigo = " . intval($promocion_codigo);

        $resultado = self::fetchFirst($sql);

        if ($resultado) {
            // Normalizar valor de certificado
            $resultado['emite_certificado'] = (
                strtolower(trim($resultado['cur_certificado'])) === 'si' ||
                $resultado['cur_certificado'] === '1' ||
                $resultado['cur_certificado'] === 1
            );

            // Mensaje descriptivo con nombre completo e siglas
            $inst_text = '';
            if ($resultado['inst_nombre']) {
                $inst_text = $resultado['inst_nombre'];
                if ($resultado['inst_siglas']) {
                    $inst_text .= ' (' . $resultado['inst_siglas'] . ')';
                }
            }

            $resultado['mensaje'] = $resultado['emite_certificado']
                ? "✅ Este curso emite certificación" . ($inst_text ? " por " . $inst_text : "")
                : "ℹ️ Este curso no emite certificación oficial";
        }

        return $resultado;
    }

    /**
     * 🆕 OBTENER CURSOS QUE EMITEN CERTIFICACIÓN
     * @return array - Lista de cursos certificados
     */
    public static function obtenerCursosCertificados()
    {
        $sql = "SELECT 
                    c.cur_codigo,
                    c.cur_nombre,
                    c.cur_nombre_corto,
                    i.inst_nombre AS institucion_nombre,
                    i.inst_siglas AS institucion_siglas,
                    COUNT(DISTINCT p.pro_codigo) AS total_promociones
                FROM cursos c
                LEFT JOIN instituciones i ON c.cur_institucion_certifica = i.inst_codigo
                LEFT JOIN promociones p ON c.cur_codigo = p.pro_curso
                WHERE LOWER(TRIM(c.cur_certificado)) = 'si' 
                   OR c.cur_certificado = '1'
                   OR c.cur_certificado = 1
                GROUP BY c.cur_codigo, c.cur_nombre, c.cur_nombre_corto, 
                         i.inst_nombre, i.inst_siglas
                ORDER BY c.cur_nombre";

        return self::fetchArray($sql);
    }

    /**
     * 🆕 OBTENER CURSOS SIN CERTIFICACIÓN
     * @return array - Lista de cursos sin certificación
     */
    public static function obtenerCursosSinCertificacion()
    {
        $sql = "SELECT 
                    c.cur_codigo,
                    c.cur_nombre,
                    c.cur_nombre_corto,
                    n.niv_nombre AS nivel_nombre,
                    t.tip_nombre AS tipo_nombre,
                    COUNT(DISTINCT p.pro_codigo) AS total_promociones
                FROM cursos c
                LEFT JOIN niveles n ON c.cur_nivel = n.niv_codigo
                LEFT JOIN tipos t ON c.cur_tipo = t.tip_codigo
                LEFT JOIN promociones p ON c.cur_codigo = p.pro_curso
                WHERE LOWER(TRIM(c.cur_certificado)) != 'si' 
                   OR c.cur_certificado IS NULL
                   OR c.cur_certificado = ''
                   OR c.cur_certificado = 'no'
                   OR c.cur_certificado = 'No'
                   OR c.cur_certificado = '0'
                   OR c.cur_certificado = 0
                GROUP BY c.cur_codigo, c.cur_nombre, c.cur_nombre_corto, 
                         n.niv_nombre, t.tip_nombre
                ORDER BY c.cur_nombre";

        return self::fetchArray($sql);
    }

    /**
     * 🆕 VALIDAR SI UN CURSO REQUIERE CERTIFICACIÓN
     * Método rápido para validaciones
     * 
     * @param int $cur_codigo - ID del curso
     * @return bool - true si emite certificado, false si no
     */
    public static function emiteCertificado($cur_codigo)
    {
        $sql = "SELECT cur_certificado 
                FROM cursos 
                WHERE cur_codigo = " . intval($cur_codigo);

        $resultado = self::fetchFirst($sql);

        if (!$resultado) {
            return false;
        }

        $certificado = strtolower(trim($resultado['cur_certificado']));

        return (
            $certificado === 'si' ||
            $certificado === '1' ||
            $resultado['cur_certificado'] === 1
        );
    }
}
