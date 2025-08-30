<?php
include_once '../includes/herramientas.php';

// Toma las variables 

$permiso_id = isset($_POST["permiso_id"])?$_POST["permiso_id"]:'';
$rol_id = isset($_POST["rol_id"])?$_POST["rol_id"]:'';

// Definición del ABML (Alta, Baja, Modificacion, Lectura -- objeto_ABML)

$opcion = isset($_POST["opcion"])?$_POST["opcion"]:'';

switch($opcion){
    
    // Acciones de los formularios
    case 'permiso_alta':
        permiso_alta($permiso_id, $rol_id);
        break;
     
    case 'permiso_baja':
        permiso_baja($permiso_id, $rol_id);
        break;
  
    
    default:
       echo("Está mal seleccionada la funcion");
             
}


// Funciones 

function permiso_alta($permiso_id, $rol_id){
  
    $resultado = Permiso::asignar_permiso_rol($permiso_id, $rol_id);
 
    echo json_encode($resultado); 
}

function permiso_baja($permiso_id, $rol_id){
  
    $resultado = Permiso::quitar_permiso_rol($permiso_id, $rol_id);
        
    echo json_encode($resultado); 
}











