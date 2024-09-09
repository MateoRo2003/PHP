
<h1 class="nombre-pagina">Login</h1> <!-- Título principal de la página -->

<p class="descripcion-pagina">Inicia sesión con tus datos</p> <!-- Descripción o instrucción para el usuario -->
<?php
include_once __DIR__ ."/../templates/alertas.php";
?>
<form class="form" method="POST" action="/" novalidate> <!-- Formulario para enviar datos con el método POST -->
    <!--para email-->
    <div class="campo">         
        <input type="email" 
                id="email" 
                name="email" required>
        <label class="lbl-nombre" for="email">
            <span class="text-nomb">Email</span>
        <label>                                        
    </div>

    <div class="campo">
        <input type="password"
                id="password"
                name="password" required>
        <label class="lbl-nombre" for="password">
            <span class="text-nomb">Contraseña</span>
        </label>  
        <button type="button" class="toggle-password">Mostrar</button>                              
    </div>
    <input type="submit" class="boton" value="Iniciar Sesión"/>
    
    <div id="loading-spinner" class="loading-spinner">
    <div class="razor"></div>
    </div>
</form>

<div class="acciones">
    <a href="/crear-cuenta">
         ¿Aún no tienes una cuenta? Crear una
    </a>
    <a href="/olvide">
        ¿Olvidaste tu contraseña?
    </a>
</div>
<!-- HTML para el Modal -->


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


