<?php

namespace Model;

class Usuario extends ActiveRecord {
    // Base de datos
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'email', 'password', 'telefono', 'admin', 'confirmado', 'token'];

    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $telefono;
    public $admin;
    public $confirmado;
    public $token;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->admin = $args['admin'] ?? '0';
        $this->confirmado = $args['confirmado'] ?? '0';
        $this->token = $args['token'] ?? '';
    }

    public function validarNuevaCuenta() {
        $nombreClass = '';
        $apellidoClass = '';
        $emailClass='';
        $passwordClass='';
        $passwordConfirmadoClass='';
        
        if (!$this->nombre) {
            self::$alertas['error'][] = 'El nombre es obligatorio';
            $nombreClass = 'campo-error';
        }
        if (!$this->apellido) {
            self::$alertas['error'][] = 'El apellido es obligatorio';
            $apellidoClass = 'campo-error';
        }
        if (!$this->email) {
            self::$alertas['error'][] = 'El email es obligatorio';
            $emailClass = 'campo-error';
        } else {
            if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
                self::$alertas['error'][] = 'El formato del email es inválido';
                $emailClass = 'campo-error';
            } else if (!preg_match('/^[a-zA-Z0-9._%+-]+@(gmail|outlook|hotmail|yahoo)\.com$/', $this->email)) {
                self::$alertas['error'][] = 'El email debe contener un dominio valido';
                $emailClass = 'campo-error';
            }
        }
            
        if (!$this->password) {
            self::$alertas['error'][] = 'La contraseña es obligatoria';
            $passwordClass='campo-error';
        } else {
            // Solo se realiza esta validación si se ingresó una contraseña
            if (strlen($this->password) < 6) {
                self::$alertas['error'][] = 'La contraseña debe contener al menos 6 caracteres';
                $passwordClass='campo-error';
            }
    
            // Validar confirmación de contraseña solo si se ingresó una contraseña
            if ($this->password !== $_POST['passwordConfirmado']) {
                self::$alertas['error'][] = 'Las contraseñas no coinciden';
                $passwordConfirmadoClass='campo-error';
            }
                
        }
    
        return [
            'alertas' => self::$alertas,
            'nombreClass' => $nombreClass,
            'apellidoClass' => $apellidoClass,
            'emailClass' => $emailClass,
            'passwordClass' => $passwordClass,
            'passwordConfirmadoClass' =>$passwordConfirmadoClass  
        ];
    }

    public function validarLogin() {
        if(!$this->email) {
            self::$alertas['error'][] = 'El email es Obligatorio';
        }
        if(!$this->password) {
            self::$alertas['error'][] = 'El Password es Obligatorio';
        }

        return self::$alertas;
    }
    public function validarEmail() {
        if(!$this->email) {
            self::$alertas['error'][] = 'El email es Obligatorio';
        }
        return self::$alertas;
    }

    public function validarPassword() {
        if(!$this->password) {
            self::$alertas['error'][] = 'El Password es obligatorio';
        }
        if(strlen($this->password) < 6) {
            self::$alertas['error'][] = 'El Password debe tener al menos 6 caracteres';
        }

        return self::$alertas;
    }

    // Revisa si el usuario ya existe
    public function existeUsuario() {
        $query = " SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1";

        $resultado = self::$db->query($query);

        if($resultado->num_rows) {
            self::$alertas['error'][] = 'El Usuario ya esta registrado';
        }

        return $resultado;
    }

    public function hashPassword() {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function crearToken() {
        $this->token = uniqid();
    }

    public function comprobarPasswordAndVerificado($password) {
        $resultado = password_verify($password, $this->password);
        
        if(!$resultado || !$this->confirmado) {
            self::$alertas['error'][] = 'Password Incorrecto o tu cuenta no ha sido confirmada';
        } else {
            return true;
        }
    }

}