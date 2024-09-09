<?php
namespace Model;

class Servicio extends ActiveRecord{
    //
    protected static $tabla = 'servicios';
    protected static $columnasDB = ['id','nombre','precio',''];

    public $id;
    public $nombre;
    public $precio;
    
}