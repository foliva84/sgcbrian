<?php
include_once '../includes/herramientas.php';


// Toma las variables del formulario de modificación
$prestador_id = isset($_POST["prestador_id"])?$_POST["prestador_id"]:'';
$practica_id = isset($_POST["practica_id"])?$_POST["practica_id"]:'';
$prestadorPractica_id = isset($_POST["prestadorPractica_id"])?$_POST["prestadorPractica_id"]:'';
$presuntoOrigen = isset($_POST["presuntoOrigen"])?$_POST["presuntoOrigen"]:0.00;



// Definición del ABML (Alta, Baja, Modificacion, Lectura -- objeto_ABML)
$opcion = isset($_POST["opcion"])?$_POST["opcion"]:'';


switch($opcion){
    
    // Acciones de los formularios
    
     
    case 'practicas_llenar':
        practicas_llenar();
        break;
    
    case 'practicas_prestador_llenar':
        practicas_prestador_llenar($prestador_id);
        break;
      
    case 'practicas_prestador_insertar':
        practicas_prestador_insertar($prestador_id, $practica_id, $presuntoOrigen);
        break;
    
    case 'practicas_prestador_eliminar':
        practicas_prestador_eliminar($prestadorPractica_id);
        break;
    
     case 'practicas_prestador_existe':
        practicas_prestador_existe($practica_id, $prestador_id);
        break;
    
    
    default:
        echo("Está mal seleccionada la funcion");        
}


// Funciones 

function practicas_llenar(){
    
    $practicas = Practica::listar();

    echo json_encode($practicas);
      
}

function practicas_prestador_llenar($prestador_id){
    
    $practicas = Prestador::listar_practicas($prestador_id);
    
    echo json_encode($practicas, JSON_UNESCAPED_UNICODE);
}

function practicas_prestador_insertar($prestador_id, $practica_id, $presuntoOrigen){
    
    $existe = Prestador::insertar_practica($prestador_id, $practica_id, $presuntoOrigen);
    
    echo json_encode($existe, JSON_UNESCAPED_UNICODE);                                
}

function practicas_prestador_eliminar($prestadorPractica_id){
    
    Prestador::eliminar_practica($prestadorPractica_id);
  
}




