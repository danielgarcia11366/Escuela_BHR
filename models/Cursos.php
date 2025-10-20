<?php

namespace Model;

class Cursos extends ActiveRecord
{
    protected static $tabla = 'cursos';
    protected static $columnasDB = ['codigo_curso', 'nombre_curso', 'descripcion', 'duracion_horas', 'requisitos', 'tipo_curso', 'area_especialidad', 'estado_curso'];
    protected static $idTabla = 'id_curso';

    public $id_curso;
    public $codigo_curso;
    public $nombre_curso;
    public $descripcion;
    public $duracion_horas;
    public $requisitos;
    public $tipo_curso;
    public $area_especialidad;
    public $estado_curso;
    public $fecha_creacion;

    public function __construct($args = [])
    {
        $this->id_curso = $args['id_curso'] ?? null;
        $this->codigo_curso = $args['codigo_curso'] ?? '';
        $this->nombre_curso = $args['nombre_curso'] ?? '';
        $this->descripcion = $args['descripcion'] ?? '';
        $this->duracion_horas = $args['duracion_horas'] ?? 0;
        $this->requisitos = $args['requisitos'] ?? '';
        $this->tipo_curso = $args['tipo_curso'] ?? '';
        $this->area_especialidad = $args['area_especialidad'] ?? '';
        $this->estado_curso = $args['estado_curso'] ?? 'A';
        $this->fecha_creacion = $args['fecha_creacion'] ?? null;
    }

    public static function obtenerCursos()
    {
        $sql = "SELECT * FROM cursos";
        return self::fetchArray($sql);
    }
}