<?php
require '../seguridad/seguridad.php';
include_once '../includes/herramientas.php';


// Definición del ABML (Alta, Baja, Modificacion, Lectura -- objeto_ABML)
$opcion = isset($_POST["opcion"])?$_POST["opcion"]:'';


switch($opcion){
    
    case 'estadisticas_sistema':
        estadisticas_sistema();
        break;
       
    default:
       echo("Está mal seleccionada la funcion");
}


function estadisticas_sistema(){
    
    $resultado = Dashboard::estadisticas_casos();
    
    echo json_encode($resultado);
}