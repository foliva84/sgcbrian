<?php
require '../seguridad/seguridad.php';
include_once '../includes/herramientas.php';


//print_r($_POST);
//exit();


// Toma las variables del formulario de alta
$tipoPrestador_id = isset($_POST["tipoPrestador_id"])?$_POST["tipoPrestador_id"]:'';
$tipoPrestador_nombre_n = isset($_POST["tipoPrestador_nombre_n"])?$_POST["tipoPrestador_nombre_n"]:'';

// Toma las variables del formulario de modificación
$tipoPrestador_id = isset($_POST["tipoPrestador_id"])?$_POST["tipoPrestador_id"]:'';
$tipoPrestador_nombre = isset($_POST["tipoPrestador_nombre"])?$_POST["tipoPrestador_nombre"]:'';
$tipoPrestador_habilitado = isset($_POST["tipoPrestador_habilitado"])?$_POST["tipoPrestador_habilitado"]:'';


// Toma la tipoPrestador_id para una baja
$tipoPrestador_id_b = isset($_POST["tipoPrestador_id_b"])?$_POST["tipoPrestador_id_b"]:'';


// Toma el tipoPrestador_id para volver a habilitarlo
$tipoPrestador_id_a = isset($_POST["tipoPrestador_id_a"])?$_POST["tipoPrestador_id_a"]:'';


// Definición del ABML (Alta, Baja, Modificacion, Lectura -- objeto_ABML)
$opcion = isset($_POST["opcion"])?$_POST["opcion"]:'';


switch($opcion){
    
    // Acciones de los formularios
    case 'formulario_alta':
        formulario_alta($tipoPrestador_nombre_n);
        break;
     
    case 'formulario_baja':
        formulario_baja($tipoPrestador_id_b);
        break;
    
    case 'formulario_habilita':
        formulario_habilita($tipoPrestador_id_a);
        break;
    
    case 'formulario_modificacion':
        formulario_modificacion($tipoPrestador_nombre, $tipoPrestador_id);
        break;
    
    case 'formulario_lectura':
        formulario_lectura($tipoPrestador_id);
        break;
    
    case 'grilla_listar':
        grilla_listar($permisos);
        break;
     
// Acciones auxiliares en el formulario

    case 'tipoPrestador_existe':
        tipoPrestador_existe($tipoPrestador_nombre_n);
        break;

    case 'tipoPrestador_existe_modificacion':
        tipoPrestador_existe_modificacion($tipoPrestador_nombre, $tipoPrestador_id);
        break;
        
 
    
    
    default:
       echo("Está mal seleccionada la funcion");
        
        
}


// Funciones de Formulario

function formulario_alta($tipoPrestador_nombre){
    
    TipoPrestador::insertar($tipoPrestador_nombre);
            
}

function formulario_baja($tipoPrestador_id_b){
    
    $resultado = TipoPrestador::borradoLogico($tipoPrestador_id_b);
    
    echo json_encode($resultado);    
}

function formulario_habilita($tipoPrestador_id_a){
    
    $resultado = TipoPrestador::reActivar($tipoPrestador_id_a);
    
    echo json_encode($resultado);    
}

function formulario_modificacion($tipoPrestador_nombre, $tipoPrestador_id){
    
    TipoPrestador::actualizar($tipoPrestador_nombre, $tipoPrestador_id);

}

function formulario_lectura($tipoPrestador_id){
    $tipoPrestador = TipoPrestador::buscarPorId($tipoPrestador_id);
    echo json_encode($tipoPrestador);
}

// Funciones auxiliares de formulario

function tipoPrestador_existe($tipoPrestador_nombre_n){
 
    $tipoPrestador_existente = TipoPrestador::existe($tipoPrestador_nombre_n);
    
    if($tipoPrestador_existente == 1) {
       echo(json_encode("El tipo de prestador ingresado ya existe"));
    }else{    
       echo(json_encode("true"));
    }
    
}

function tipoPrestador_existe_modificacion($tipoPrestador_nombre, $tipoPrestador_id){
 
    
    $tipoPrestador_existente = TipoPrestador::existeUpdate($tipoPrestador_nombre, $tipoPrestador_id);
    
    if($tipoPrestador_existente == 1) {
        
       echo(json_encode("El tipo de prestador ingresado ya existe"));
        
    }else{
        
       echo(json_encode("true"));

    }
}


// Funciones de Grilla

function grilla_listar($permisos){
    $tipoPrestadores = TipoPrestador::listar();

    $grilla = "<div class='row'>";
    $grilla .= "<div id='grilla_abm' class='col-sm-12'>";
    $grilla .=  "<div class='card-box table-responsive'>";
    $grilla .=      "<h4 class='m-t-0 header-title'><b>Tabla de tipos de prestadores</b></h4>";
    $grilla .=      "<table id='dt_tipoPrestador' class='table table-hover table-striped m-0 table-responsive'>";    
    $grilla .=          "<thead>";
    $grilla .=              "<tr>";
    $grilla .=                  "<th>Nombre</th>";
    $grilla .=                  "<th>Activo</th>";
    $grilla .=                  "<th>Acciones</th>";
    $grilla .=              "</tr>";
    $grilla .=          "</thead>";
    $grilla .=          "<tbody>";  
    foreach($tipoPrestadores as $tipoPrestador){
            $tipoPrestador_id = $tipoPrestador["tipoPrestador_id"];
            $tipoPrestador_nombre = $tipoPrestador["tipoPrestador_nombre"];
            $tipoPrestador_activo = $tipoPrestador["tipoPrestador_activo"];
    $grilla .=              "<tr>";
    $grilla .=                  "<td>";
    $grilla .=                      $tipoPrestador_nombre;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    if($tipoPrestador_activo == 1){
    $grilla .=                 "<span class='label label-success'>Activo</span>";
    }else{
    $grilla .=                 "<span class='label label-danger'>Inactivo</span>";
    }
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      "<a href='javascript:void(0)'> <i onclick='formulario_lectura($tipoPrestador_id)' class='fa fa-edit'></i></a>";
    
    $tipoPrestadores_baja = array_search('tipoPrestadores_baja', array_column($permisos, 'permiso_variable'));
    if (!empty($tipoPrestadores_baja) || ($tipoPrestadores_baja === 0)) {
        if($tipoPrestador_activo == 1){
        $grilla .=                      "<a href='javascript:void(0)'> <i onclick='modal_baja($tipoPrestador_id)' class='fa fa-user-times'></i></a>";
        }else{
        $grilla .=                      "<a href='javascript:void(0)'> <i onclick='modal_alta($tipoPrestador_id)' class='fa fa-user-plus'></i></a>";
        }
    }
    
    $grilla .=                  "</td>";
    $grilla .=              "</tr>";
    }
    
    $grilla .=          "</tbody>";
    $grilla .=       "</table>";
    $grilla .=    "</div>";
    $grilla .=  "</div>";
    $grilla .="</div>";   
    
    echo $grilla;
}









