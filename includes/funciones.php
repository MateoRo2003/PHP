<?php

function debuguear($variable) : string {
    // Define la función `debuguear` que acepta una variable.

    echo "<pre>";
    // Aplica formato predefinido para una salida más legible(sin interrupciones de linea).

    var_dump($variable);
    // Muestra el tipo y contenido de la variable.

    echo "</pre>";
    // Cierra el formato predefinido.

    exit;
    // Detiene la ejecución del script.
}


// Escapa / Sanitizar(limpiar y filtrar) el HTML
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}