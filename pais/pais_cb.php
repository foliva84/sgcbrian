<?php
require '../seguridad/seguridad.php';
include_once '../includes/herramientas.php';


// Toma las variables
$pais_id = isset($_POST["pais_id"])?$_POST["pais_id"]:'';

// Definición del ABML (Alta, Baja, Modificacion, Lectura -- objeto_ABML)
$opcion = isset($_POST["opcion"])?$_POST["opcion"]:'';


switch($opcion){
    
    // Acciones generales
    case 'buscar_prefijo_pais':
    buscar_prefijo_pais($pais_id);
        break;

    default:
       echo("Está mal seleccionada la funcion");       
}


// Funciones generales
function buscar_prefijo_pais($pais_id){
    
    $prefijo = Pais::buscar_prefijo($pais_id);

    echo json_encode($prefijo);
}