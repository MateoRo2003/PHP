<?php 


require_once __DIR__ . '/../includes/app.php';
//Llamo a la instancia logincontroller
use Controllers\LoginController;
use MVC\Router;
use Controllers\CitaController;
use Controllers\APIController;

$router = new Router();

//Iniciar Seccion
//Cuando se llene el formulario
$router->get('/', [LoginController::class, 'login']);
//Cuando se complete el formulario
$router->post('/', [LoginController::class, 'login']);
//Cuando cierre seccion son todos redirecionamientos, con diferentes metodos
$router->get('/logout', [LoginController::class, 'logout']);
//Busca el metodo login en LoginController cuando se inicia 

//olvidar contraseña
$router->get('/olvide', [LoginController::class, 'olvide']);
$router->post('/olvide', [LoginController::class, 'olvide']);
//Recuperar contraseña
$router->get('/recuperar', [LoginController::class, 'recuperar']);
$router->post('/recuperar', [LoginController::class, 'recuperar']);

//Crear Cuentas
$router->get('/crear-cuenta', [LoginController::class, 'crear']);
$router->post('/crear-cuenta', [LoginController::class, 'crear']);

//Confirmar Cuenta
$router->get('/confirmar-cuenta', [LoginController::class, 'confirmar']);
$router->get('/mensaje', [LoginController::class, 'mensaje']);

//area privada
$router->get('/cita', [CitaController::class, 'index']);
$router->comprobarRutas();
// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador


//API DE TURNOS
$router->get('/api/servicios',[APIController::class,'index']);
