<?php 

namespace Model;

class Servicio extends ActiveRecord {
    // Base de datos
    protected static $tabla = 'servicios';
    protected static $columnasDB = ['id', 'nombre', 'precio', 'duracion']; // Añade 'duracion'

    public $id;
    public $nombre;
    public $precio;
    public $duracion; // Nuevo atributo para duración

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->precio = $args['precio'] ?? '';
        $this->duracion = $args['duracion'] ?? ''; // Inicializa duración
    }

    public function validar() {
        if(!$this->nombre) {
            self::$alertas['error'][] = 'El Nombre del Servicio es Obligatorio';
        }
        if(!$this->precio) {
            self::$alertas['error'][] = 'El Precio del Servicio es Obligatorio';
        }
        if(!is_numeric($this->precio)) {
            self::$alertas['error'][] = 'El precio no es válido';
        }
        if(!$this->duracion) {
            self::$alertas['error'][] = 'La Duración del Servicio es Obligatoria'; // Validación para duración
        }
        if(!is_numeric($this->duracion)) {
            self::$alertas['error'][] = 'La duración no es válida'; // Validación para duración
        }

        return self::$alertas;
    }
}
