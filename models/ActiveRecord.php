<?php

namespace Model;

use PDO;
use PDOException;

class ActiveRecord
{
    /** @var PDO */
    protected static $db;
    protected static $tabla = '';
    protected static $columnasDB = [];
    protected static $idTabla = '';

    protected static $alertas = [];

    /** ===============================
     *   CONFIGURACIÓN GENERAL
     *  =============================== */

    // Asignar conexión PDO
    public static function setDB($database)
    {
        self::$db = $database;
    }

    // Manejo de alertas (mensajes)
    public static function setAlerta($tipo, $mensaje)
    {
        static::$alertas[$tipo][] = $mensaje;
    }

    public static function getAlertas()
    {
        return static::$alertas;
    }

    public function validar()
    {
        static::$alertas = [];
        return static::$alertas;
    }

    /** ===============================
     *   CRUD PRINCIPAL
     *  =============================== */

    public function guardar()
    {
        $id = static::$idTabla ?: 'id';
        return isset($this->$id) && !empty($this->$id)
            ? $this->actualizar()
            : $this->crear();
    }

    public static function all()
    {
        $query = "SELECT * FROM " . static::$tabla;
        return self::consultarSQL($query);
    }

    public static function find($id)
    {
        $idCampos = static::$idTabla ?: 'id';
        $query = "SELECT * FROM " . static::$tabla;

        if (is_array($idCampos)) {
            $query .= " WHERE " . implode(" AND ", array_map(function ($campo) use ($id) {
                return "$campo = " . self::$db->quote($id[$campo] ?? null);
            }, $idCampos));
        } else {
            $query .= " WHERE $idCampos = " . self::$db->quote($id);
        }

        $resultado = self::consultarSQL($query);
        return $resultado ? array_shift($resultado) : null;
    }

    public static function get($limite)
    {
        $limite = (int)$limite;
        $query = "SELECT * FROM " . static::$tabla . " LIMIT $limite";
        $resultado = self::consultarSQL($query);
        return $resultado ? array_shift($resultado) : null;
    }

    public static function where($columna, $valor, $condicion = '=')
    {
        $query = "SELECT * FROM " . static::$tabla . " WHERE {$columna} {$condicion} " . self::$db->quote($valor);
        return self::consultarSQL($query);
    }

    public static function SQL($consulta)
    {
        return self::$db->query($consulta);
    }

    /** ===============================
     *   CREAR / ACTUALIZAR / ELIMINAR
     *  =============================== */

    public function crear()
    {
        try {
            $atributos = $this->sanitizarAtributos();
            $query = "INSERT INTO " . static::$tabla . " (";
            $query .= join(', ', array_keys($atributos)) . ") VALUES (";
            $query .= join(', ', array_values($atributos)) . ")";

            $resultado = self::$db->exec($query);

            return [
                'resultado' => $resultado,
                'id' => self::$db->lastInsertId(static::$tabla)
            ];
        } catch (PDOException $e) {
            self::setAlerta('error', 'Error al crear registro: ' . $e->getMessage());
            return false;
        }
    }

    public function actualizar()
    {
        try {
            $atributos = $this->sanitizarAtributos();
            $idCampos = static::$idTabla ?: 'id';

            $valores = [];
            foreach ($atributos as $key => $value) {
                $valores[] = "{$key} = {$value}";
            }

            $query = "UPDATE " . static::$tabla . " SET " . join(', ', $valores);

            if (is_array($idCampos)) {
                $query .= " WHERE " . implode(" AND ", array_map(function ($campo) {
                    return "$campo = " . self::$db->quote($this->$campo);
                }, $idCampos));
            } else {
                $query .= " WHERE $idCampos = " . self::$db->quote($this->$idCampos);
            }

            $resultado = self::$db->exec($query);

            return ['resultado' => $resultado];
        } catch (PDOException $e) {
            self::setAlerta('error', 'Error al actualizar registro: ' . $e->getMessage());
            return false;
        }
    }

    public function eliminar()
    {
        try {
            $idCampos = static::$idTabla ?: 'id';
            $query = "DELETE FROM " . static::$tabla;

            if (is_array($idCampos)) {
                $query .= " WHERE " . implode(" AND ", array_map(function ($campo) {
                    return "$campo = " . self::$db->quote($this->$campo);
                }, $idCampos));
            } else {
                $query .= " WHERE $idCampos = " . self::$db->quote($this->$idCampos);
            }

            return self::$db->exec($query);
        } catch (PDOException $e) {
            self::setAlerta('error', 'Error al eliminar registro: ' . $e->getMessage());
            return false;
        }
    }

    /** ===============================
     *   CONSULTAS GENERALES
     *  =============================== */

    protected static function consultarSQL($query)
    {
        try {
            $resultado = self::$db->query($query);
            if (!$resultado) return [];

            $array = [];
            while ($registro = $resultado->fetch(PDO::FETCH_ASSOC)) {
                $array[] = static::crearObjeto($registro);
            }

            $resultado->closeCursor();
            return $array;
        } catch (PDOException $e) {
            self::setAlerta('error', 'Error en consulta SQL: ' . $e->getMessage());
            return [];
        }
    }

    public static function fetchArray($query)
    {
        $resultado = self::$db->query($query);
        $respuesta = $resultado->fetchAll(PDO::FETCH_ASSOC);
        $data = [];

        foreach ($respuesta as $fila) {
            $data[] = array_change_key_case($fila); // ⭐ QUITA utf8_encode
        }

        $resultado->closeCursor();
        return $data;
    }

    public static function fetchFirst($query)
    {
        $resultado = self::$db->query($query);
        $fila = $resultado->fetch(PDO::FETCH_ASSOC);
        $resultado->closeCursor();

        return $fila ? array_change_key_case($fila) : null; // ⭐ QUITA utf8_encode
    }

    protected static function crearObjeto($registro)
    {
        $objeto = new static;

        foreach ($registro as $key => $value) {
            $prop = strtolower($key);
            if (property_exists($objeto, $prop)) {
                $objeto->$prop = $value; // ⭐ QUITA utf8_encode, solo asigna directo
            }
        }

        return $objeto;
    }

    /** ===============================
     *   UTILIDADES DE ATRIBUTOS
     *  =============================== */

    public function atributos()
    {
        $atributos = [];
        foreach (static::$columnasDB as $columna) {
            $columna = strtolower($columna);
            if ($columna === 'id' || $columna === static::$idTabla) continue;
            $atributos[$columna] = $this->$columna ?? null;
        }
        return $atributos;
    }

    public function sanitizarAtributos()
    {
        $atributos = $this->atributos();
        $sanitizado = [];

        foreach ($atributos as $key => $value) {
            $sanitizado[$key] = is_null($value)
                ? 'NULL'
                : self::$db->quote(trim($value));
        }

        return $sanitizado;
    }

    public function sincronizar($args = [])
    {
        foreach ($args as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
}
