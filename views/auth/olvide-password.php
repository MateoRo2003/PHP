<h1 class="nombre-pagina">Olvide Contraseña</h1>
<p class="descripcion-pagina">Reestablece tu contraseña escribiendo tu email a continuación</p>
<?php 
include_once __DIR__ ."/../templates/alertas.php"

?>
<form class="form" action="/olvide" method="POST" novalidate>
    <div class="campo">
        
        <input type="email"
                id="email"
                name="email" required>
        <label class="lbl-nombre"for="email">
            <span class="text-nomb">Email</span>
        </label>
                
        
    </div>
    <input type="submit" class="boton" value="Enviar Instrucciones">
</form>
<div class="acciones">
    <a href="/">
         ¿Ya tienes una cuenta? Inicia Sesión
    </a>
    <a href="/crear-cuenta">
        ¿Aún no tienes una cuenta? Crear una
    </a>
</div>
