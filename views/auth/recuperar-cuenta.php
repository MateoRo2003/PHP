<h1 class="nombre-pagina">Restablecer Contraseña</h1>
<p class="descripcion-pagina">Coloca tu nueva contraseña a continuacion</p>
<?php 
include_once __DIR__ ."/../templates/alertas.php"

?>
<?php if($error) return;?>

<form class="form" method="POST" novalidate>

    <div class="campo">
        <input type="password"
                id="password"
                name="password" required>
        <label class="lbl-nombre"for="password">
            <span class="text-nomb">Tu nueva contraseña</span>
        </label>
        
        
    </div>
    <input type="submit" class="boton"  value="Guardar Nueva Contraseña">

</form>

<div class="acciones">
    <a href="/">¿Ya tienes Cuenta? Iniciar Sessión</a>
    <a href="/crear-cuenta">¿Aun no tienes cuenta? Obtener Una</a>

</div>
