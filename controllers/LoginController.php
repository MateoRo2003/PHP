<?php

namespace Controllers;

// Importa la clase Router del espacio de nombres MVC y la clase Usuario del espacio de nombres Model
use MVC\Router;
use Model\Usuario;
use Classes\Email;

class LoginController {
    // Método para manejar la lógica de inicio de sesión
    public static function login(Router $router) {
        // Renderiza la vista 'auth/login' usando el objeto Router
        $alertas=[];
        
        if($_SERVER['REQUEST_METHOD']=== 'POST'){
            $auth=new Usuario($_POST);
            $alertas=$auth->validarLogin();  
           
            if(empty($alertas)){
                //Comrobar que exista ese usuario
                $usuario=Usuario::where('email',$auth->email);
                if($usuario){
                    //verificar contraseña
                    if( $usuario->compContraAndVerifi($auth->password)){
                        //Autenticar el usuario
                        session_start();
                        $_SESSION['id']=$usuario->id;
                        $_SESSION['nombre']=$usuario->nombre. " " .$usuario->apellido;                       
                        $_SESSION['email']=$usuario->email;
                        $_SESSION['login']=true;
                        //redireccionamiento
                        if($usuario->admin==="1"){
                            $_SESSION['admin']=$usuario->admin ?? null;
                            header('Location:/admin');
                        }else{
                            
                            header('Location:/cita');
                        }                                                
                    }

                }else{
                    Usuario::setAlerta('error','Usuario No Encontrado');
                }
            }
        }
        $alertas=Usuario::getAlertas();
        $router->render('auth/login',[
            'alertas'=>$alertas
            
        ]);
       
    }
    
    // Método para manejar la lógica de cierre de sesión
    public static function logout() {
        // Muestra un mensaje en pantalla cuando se llama a logout
        echo "Desde Login";
    }

    // Método para manejar la lógica de la vista 'olvide-password'
    public static function olvide(Router $router) {
        // Renderiza la vista 'auth/olvide-password'
        $alertas=[];
        if($_SERVER['REQUEST_METHOD']==='POST'){
            $auth = new Usuario($_POST);
            $auth->validarEmail();
            if(empty($alertas)){
                $usuario=Usuario::where('email',$auth->email);
                if($usuario && $usuario->confirmado==="1"){
                    $usuario->crearToken();
                    $usuario->guardar();
                    //enviar mail
                    $email=new Email($usuario->email, $usuario->nombre, $usuario->token);                
                    $email->enviarInstrucciones();



                    Usuario::setAlerta('exito','Revisa tu email');
                    
                }else{
                    Usuario::setAlerta('error','El Usuario no existe o no esta confirmado');
                    $alertas=Usuario::getAlertas();
                }
            }
        }



        $alertas=Usuario::getAlertas();
        $router->render('auth/olvide-password', [
            'alertas'=>$alertas
        ]);
    }

    // Método para manejar la lógica de recuperación de contraseña
    public static function recuperar(Router $router) {
        // Muestra un mensaje en pantalla cuando se llama a recuperar
        $alertas=[];
        $error=false;
        $token=s($_GET['token']);
        
        $usuario= Usuario::where('token', $token);
        if(empty($usuario)){
            Usuario::setAlerta('error', 'Token no valido');
            $error=true;
        }
        if($_SERVER['REQUEST_METHOD']==='POST'){
            $password=new Usuario($_POST);
            $password->validarPassword();
            
            if(empty($alertas)){
                $usuario->password=null;
                $usuario->password=$password->password;
                $usuario->hashPassword();
                $usuario->token = null;
                $resultado=$usuario->guardar();
                if($resultado){
                    header('Location:/');
                }
               
            }


        }

        $alertas=Usuario::getAlertas();
        $router->render('auth/recuperar-cuenta',[
            'alertas'=>$alertas,
            'error'=>$error
        ]);
    }

    // Método para crear una nueva cuenta de usuario
    public static function crear(Router $router) {
        // Crea una nueva instancia de Usuario
        $usuario = new Usuario;

        // Inicializa un array vacío para alertas
        $alertas = [];

        // Comprueba si la solicitud es de tipo POST (cuando se envía el formulario)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sincroniza los datos recibidos del formulario con la instancia de Usuario
            $usuario->sincronizar($_POST);
            
            // Valida los datos de la nueva cuenta y almacena cualquier alerta en el array $alertas
            $resultadoValidacion=$alertas = $usuario->validarNuevaCuenta();
            $alertas = $resultadoValidacion['alertas']; // Extrae solo las alertas
            // Verifica si no hay alertas, lo que significa que la validación fue exitosa
            if (empty($alertas['error'])) {
                // Llama al método existeUsuario para verificar si el usuario ya existe en la base de datos
               $resultado= $usuario->existeUsuario();
               
               if($resultado->num_rows){
                $alertas=Usuario::getAlertas();
               }else{
                //hashear la contra
                $usuario->hashPassword();
                //GENEREAR UN TOKE UNICO
                $usuario->crearToken();
                //enviar mail
                $email=new Email($usuario->email, $usuario->nombre, $usuario->token);                
                $email->enviarConfirmacion();
                //crear el usuario
                $resultado=$usuario->guardar();
                if($resultado){
                    header('location:/mensaje');
                    exit;
                }                    
               }
            }
        }   
           

        $alertas=Usuario::getAlertas();// Renderiza la vista 'auth/crear-cuenta', pasando la instancia de Usuario y las alertas al template
        $router->render('auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
       
    }

    public static function mensaje(Router $router){
        $router->render('auth/mensaje');
    }
    public static function confirmar(Router $router){
        $alertas = [];
        $token=s($_GET['token']); //sinitizar 
        $usuario= Usuario::where('token',$token);
            if(empty($usuario)){
                Usuario::setAlerta('error', 'Token No Valido');
            }else{
                    $usuario->confirmado ="1";
                    $usuario->token=null;
                    $usuario->guardar();
                    Usuario::setAlerta('exito', 'Su Cuenta ha sido confirmada');
                    
            }
        
        $alertas =Usuario::getAlertas();
        $router->render('auth/confirmar-cuenta',[
            'alertas'=>$alertas
        ]);
    }


}

