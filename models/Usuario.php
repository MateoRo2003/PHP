<?php
namespace Model;

// Define la clase Usuario que extiende de ActiveRecord
class Usuario extends ActiveRecord  {
    // Nombre de la tabla en la base de datos
    protected static $tabla = 'usuarios';
    // Columnas de la base de datos que corresponden a las propiedades del modelo
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'email', 'password', 'telefono', 'admin', 'confirmado', 'token'];

    // Propiedades del modelo
    public $id;
    public $nombre;
    public $apellido;
    public $password;
    public $telefono;
    public $email;
    public $admin;
    public $confirmado;
    public $token;

    // Constructor para inicializar las propiedades del objeto con los valores proporcionados
    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->admin = $args['admin'] ?? 0;
        $this->confirmado = $args['confirmado'] ?? 0;
        $this->token = $args['token'] ?? '';
    }

    public function validarNuevaCuenta() {
        if (!$this->nombre) {
            self::$alertas['error'][] = 'El nombre es obligatorio';
        }
        if (!$this->apellido) {
            self::$alertas['error'][] = 'El apellido es obligatorio';
        }
        if (!$this->email) {
            self::$alertas['error'][] = 'El email es obligatorio';
        } else {
            if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
                self::$alertas['error'][] = 'El formato del email es inválido';
            } else if (!preg_match('/^[a-zA-Z0-9._%+-]+@(gmail|outlook|hotmail|yahoo)\.com$/', $this->email)) {
                self::$alertas['error'][] = 'El email debe contener un dominio valido';
            }
        }
    
        if (!$this->password) {
            self::$alertas['error'][] = 'La contraseña es obligatoria';
        } else {
            if (strlen($this->password) < 6) {
                self::$alertas['error'][] = 'La contraseña debe contener al menos 6 caracteres';
            }
    
            if ($this->password !== $_POST['passwordConfirmado']) {
                self::$alertas['error'][] = 'Las contraseñas no coinciden';
            }
        }
    
        return self::$alertas;  // Solo retornamos las alertas
    }


        public function validarLogin(){
            if(!$this->email){
                self::$alertas['error'][]='El E-mail es obligatorio';
            }
            if(!$this->password){
                self::$alertas['error'][]='La Contraseña es obligatoria';
            }
            return self::$alertas;
        }

        public function validarEmail(){
            if(!$this->email){
                self::$alertas['error'][]='El email es obligatorio';
            
            }
                return self::$alertas;
         }

         public function validarPassword() {
            if (!$this->password) {
                self::$alertas['error'][] = 'La contraseña es obligatoria';
            } else {
                // Validación de longitud solo si se ingresó una contraseña
                if (strlen($this->password) < 6) {
                    self::$alertas['error'][] = 'La contraseña debe tener al menos 6 caracteres';
                }
        
                
            }
        
            return self::$alertas;
         }

        //revisa si el usuario ya existe
        public function existeUsuario(){
            $query=" SELECT *FROM ". self::$tabla ." WHERE email = '" . $this->email. "' LIMIT 1";

            $resultado = self::$db->query($query);
           if($resultado->num_rows){
            self::$alertas['error'][] = 'El Usuario ya esta registrado';
           }
           return $resultado;
        }

        public function hashPassword(){
            $this -> password=password_hash($this->password,PASSWORD_BCRYPT);
        }
        //Numero aleatorio de toke 
        public function crearToken(){
            $this->token = uniqid();
        }

        public function comprobarPasswordAndVerificado($password){
            $resultado=password_verify($password, $this->password);
            if(!$resultado || !$this->confirmado){
               self::$alertas['error'][]='Contraseña Incorrecta o tu cuenta no ha sido confirmada';
            }else{
               return true;
            }
        }


    }
    