<h1 class="nombre-pagina">Crear cuenta</h1>
<p class="descripcion-pagina">Llena el siguiente formulario para crear una cuenta</p>

<?php 
include_once __DIR__ ."/../templates/alertas.php";
?>
<?php
// Asumiendo que esta función es llamada en el controlador y los datos se pasan a la vista
$validacion = $usuario->validarNuevaCuenta();
?>
<form class="form" method="POST" action="/crear-cuenta" novalidate>
    <!-- Campo para el nombre -->
    <div class="campo <?php echo isset($alertas['error']) ? 'campo-error' : ''; ?>">
    <label for="nombre">Nombre</label>
    <input
        type="text"
        id="nombre"
        name="nombre"
        placeholder="Tu Nombre"
        value="<?php echo s($usuario->nombre); ?>"
    />
    </div>

    <!-- Campo para el apellido -->
    
    <div class="campo <?php echo isset($alertas['error'])? 'campo-error': '';?>">
        <input 
        type="text" 
        id="apellido" 
        name="apellido" 
        value="<?php echo s($usuario->apellido); ?>" required>
        <label class="lbl-nombre" for="apellido">
            <span class="text-nomb">Apellido</span>
        </label>
    </div>
    

    <!-- Campo para el teléfono -->
    <div class="campo">
        <input type="tel" id="telefono" name="telefono" value="<?php echo s($usuario->telefono); ?>" required>
        <label class="lbl-nombre" for="telefono">
            <span class="text-nomb">Teléfono</span>
        </label>
    </div>

    <!-- Campo para el email -->
    <div class="campo">
    <div class="campo <?php echo isset($validacion['emailClass']) && !empty($_POST) ? $validacion['emailClass'] : ''; ?>">
        <input type="email" id="email" name="email" value="<?php echo s($usuario->email); ?>" required>
        <label class="lbl-nombre" for="email">
            <span class="text-nomb">Email</span>
        </label>
</div>
    </div>

    <!-- Campo para la contraseña -->
    <div class="campo">
    <div class="campo <?php echo isset($validacion['passwordClass']) && !empty($_POST) ? $validacion['passwordClass'] : ''; ?>">
        <input type="password" id="password" name="password" required>
        <label class="lbl-nombre" for="password">
            <span class="text-nomb">Contraseña</span>
        </label>

        <button type="button" class="toggle-password">Mostrar</button>     
        </div>     
    </div>

    <!-- Campo para confirmar la contraseña -->
    <div class="campo">
    <div class="campo <?php echo isset($validacion['passwordConfirmadoClass']) && !empty($_POST) ? $validacion['passwordConfirmadoClass'] : ''; ?>">
        <input type="password" id="passwordConfirmado" name="passwordConfirmado" autocomplete="off" required>
        <label class="lbl-nombre" for="passwordConfirmado">
            <span class="text-nomb">Confirmar Contraseña </span>
        </label>
</div>
    </div>
    
    <!-- Botón de enviar -->
    <input type="submit" value="Crear Cuenta" class="boton">
    
    <div id="loading-spinner" class="loading-spinner">
    <div class="razor"></div>
    </div>

</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Inicia Sesión</a>
    <a href="/olvide">¿Olvidaste tu contraseña?</a>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const togglePassword = document.querySelector('.toggle-password');
        const passwordField = document.querySelector('#password');

        togglePassword.addEventListener('click', function() {
            // Alternar el tipo de input entre 'password' y 'text'
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            
            // Cambiar el texto del botón según el estado de visibilidad
            this.textContent = type === 'password' ? 'Mostrar' : 'Ocultar';
        });
    });
    </script>

    <script>
        document.querySelector('form').addEventListener('submit', function(event) {
    event.preventDefault(); // Previene el envío inmediato del formulario

    // Muestra el spinner de carga
    document.getElementById('loading-spinner').classList.add('show');

    // Simula un retraso antes de enviar el formulario
    setTimeout(function() {
        event.target.submit(); // Envía el formulario después del retraso
    }, 1000); // Puedes ajustar este tiempo de retraso según tus necesidades
});

    </script>