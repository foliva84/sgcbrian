<?php
include_once '../includes/herramientas.php';

// Variables generales
$prestador_id = isset($_POST["prestador_id"])?$_POST["prestador_id"]:'';
$pais_id = isset($_POST["pais_id"])?$_POST["pais_id"]:'';
$ciudad_id = isset($_POST["ciudad_id"])?$_POST["ciudad_id"]:'';
$prestadorPais_id = isset($_POST["prestadorPais_id"])?$_POST["prestadorPais_id"]:'';
$prestadorCiudad_id = isset($_POST["prestadorCiudad_id"])?$_POST["prestadorCiudad_id"]:'';

//Variables para el autocomplete
$pais_autocomplete = isset($_POST["pais_autocomplete"])?$_POST["pais_autocomplete"]:'';
$ciudad_autocomplete = isset($_POST["ciudad_autocomplete"])?$_POST["ciudad_autocomplete"]:'';

// Definición del método
$opcion = isset($_POST["opcion"])?$_POST["opcion"]:'';


switch($opcion){
    
    // Método a ejecutar
    
    case 'paises_prestador_llenar':
        paises_prestador_llenar($prestador_id);
        break;
    
    case 'ciudades_prestador_llenar':
        ciudades_prestador_llenar($prestador_id);
        break;
    
    case 'paises_autocomplete':
        paises_autocomplete($pais_autocomplete);
        break;
          
    case 'ciudades_autocomplete':
        ciudades_autocomplete($ciudad_autocomplete, $pais_id);
        break;
       
    case 'paises_prestador_insertar':
        paises_prestador_insertar($prestador_id, $pais_id);
        break;
    
    case 'ciudades_prestador_insertar':
        ciudades_prestador_insertar($prestador_id, $ciudad_id);
        break;
    
    case 'paises_prestador_eliminar':
        paises_prestador_eliminar($prestadorPais_id);
        break;
    
    case 'ciudades_prestador_eliminar':
        ciudades_prestador_eliminar($prestadorCiudad_id);
        break;
   
    
    default:
        echo("Está mal seleccionada la funcion");        
}


// Funciones 

function paises_autocomplete($pais_autocomplete){   
    $paises = Pais::listar_filtrado($pais_autocomplete);
    $data = array();
        foreach ($paises as $pais) {
            $name = $pais['pais_nombreEspanol'] . '|' . $pais['pais_id'];
            array_push($data, $name);	
        }	
    echo json_encode($data);
}

function ciudades_autocomplete($ciudad_autocomplete, $pais_id){
    $ciudades = Ciudad::listar_filtrado($ciudad_autocomplete, $pais_id);
    $data = array();
        foreach ($ciudades as $ciudad) {
            $name = $ciudad['ciudad_nombre'] . '|' . $ciudad['ciudad_id'];
            array_push($data, $name);	
        }	
    echo json_encode($data);
}

function paises_prestador_llenar($prestador_id){
    $paises = Prestador::listar_paises($prestador_id);
    echo json_encode($paises, JSON_UNESCAPED_UNICODE);
}

function ciudades_prestador_llenar($prestador_id){
    $ciudades = Prestador::listar_ciudades($prestador_id);
    echo json_encode($ciudades, JSON_UNESCAPED_UNICODE);
}

function paises_prestador_insertar($prestador_id, $pais_id){
    $existe = Prestador::insertar_pais($prestador_id, $pais_id);
    echo json_encode($existe, JSON_UNESCAPED_UNICODE);                                
}

function ciudades_prestador_insertar($prestador_id, $ciudad_id){
    $existe = Prestador::insertar_ciudad($prestador_id, $ciudad_id);
    echo json_encode($existe, JSON_UNESCAPED_UNICODE);                                
}

function paises_prestador_eliminar($prestadorPais_id){
    Prestador::eliminar_pais($prestadorPais_id);
}

function ciudades_prestador_eliminar($prestadorCiudad_id){
    Prestador::eliminar_ciudad($prestadorCiudad_id);
}




