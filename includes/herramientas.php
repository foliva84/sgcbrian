<?php
/* 
 * Herramientas
 * Aquí van todas las rutinas necesarias para todas las páginas.
 * Como manejo de sesiones y autocarga de clases. --
 */

//Definir la zona horaria del sistema - Ojo que esta puede ser diferente a la seteada en el Apache
date_default_timezone_set('America/Argentina/Buenos_Aires');

// Inicio de la session

if (!isset($_SESSION)) { 
    session_start(); 
}

  
// Función de autocarga de clases modificado para que busque en los directorios indicados.
spl_autoload_register(function ($nombre_clase) {
    
    
    //Directorio de clases y componentes
        $directorios_clases = array(
            'clases/',
            '../clases/'
        );
        
        //Por cada directorio
        foreach($directorios_clases as $directorio){
            //see if the file exsists
            if(file_exists($directorio.$nombre_clase . '.php'))
            {
                require_once($directorio.$nombre_clase . '.php');
               
                return;
            }            
        }

});



//Función para que cuando se quiere imprimir una variable nula no genere un error
//Utilizada en los ABM
// echo ifsetor($variable);
function ifsetor(&$variable, $default = null) {
    if (isset($variable)) {
        $tmp = $variable;
    } else {
        $tmp = $default;
    }
    return $tmp;
}

?>