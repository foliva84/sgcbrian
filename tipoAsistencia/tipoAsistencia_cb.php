<?php
require '../seguridad/seguridad.php';
include_once '../includes/herramientas.php';


// Toma las variables del formulario de alta
$tipoAsistencia_id = isset($_POST["tipoAsistencia_id"])?$_POST["tipoAsistencia_id"]:'';
$tipoAsistencia_clasificacion_id_n = isset($_POST["tipoAsistencia_clasificacion_id_n"])?$_POST["tipoAsistencia_clasificacion_id_n"]:'';
$tipoAsistencia_nombre_n = isset($_POST["tipoAsistencia_nombre_n"])?$_POST["tipoAsistencia_nombre_n"]:'';


// Toma las variables del formulario de modificación
$tipoAsistencia_id = isset($_POST["tipoAsistencia_id"])?$_POST["tipoAsistencia_id"]:'';
$tipoAsistencia_clasificacion_id = isset($_POST["tipoAsistencia_clasificacion_id"])?$_POST["tipoAsistencia_clasificacion_id"]:'';
$tipoAsistencia_nombre = isset($_POST["tipoAsistencia_nombre"])?$_POST["tipoAsistencia_nombre"]:'';


// Toma el tipoAsistencia_id para deshabilitar
$tipoAsistencia_id_b = isset($_POST["tipoAsistencia_id_b"])?$_POST["tipoAsistencia_id_b"]:'';


// Toma el tipoAsistencia_id para volver a habilitar
$tipoAsistencia_id_a = isset($_POST["tipoAsistencia_id_a"])?$_POST["tipoAsistencia_id_a"]:'';


// Definición del ABML (Alta, Baja, Modificacion, Lectura -- objeto_ABML)
$opcion = isset($_POST["opcion"])?$_POST["opcion"]:'';


switch($opcion){
    
    // Acciones de los formularios
    case 'formulario_alta':
        formulario_alta($tipoAsistencia_clasificacion_id_n, $tipoAsistencia_nombre_n);
        break;
     
    case 'formulario_baja':
        formulario_baja($tipoAsistencia_id_b);
        break;
    
     case 'formulario_habilita':
        formulario_habilita($tipoAsistencia_id_a);
        break;
    
    case 'formulario_modificacion':
        formulario_modificacion($tipoAsistencia_clasificacion_id, $tipoAsistencia_nombre, $tipoAsistencia_id);
        break;
    
    case 'formulario_lectura':
        formulario_lectura($tipoAsistencia_id);
        break;
    
    case 'grilla_listar':
        grilla_listar($permisos);
        break;
 
    
    // Acciones auxiliares en el formulario    
    case 'tipoAsistencia_existe_modificacion':
        tipoAsistencia_existe_modificacion($tipoAsistencia_nombre, $tipoAsistencia_id);
        break;
    
    case 'tipoAsistencia_existe':
        tipoAsistencia_existe($tipoAsistencia_nombre_n);
        break;
    
    
    default:
       echo("Está mal seleccionada la funcion");
}


// Funciones de Formulario
function formulario_alta($tipoAsistencia_clasificacion_id_n, $tipoAsistencia_nombre_n){
    
    TipoAsistencia::insertar($tipoAsistencia_clasificacion_id_n, $tipoAsistencia_nombre_n);            
}


function formulario_baja($tipoAsistencia_id_b){
    
    $resultado = TipoAsistencia::borradoLogico($tipoAsistencia_id_b);
    
    echo json_encode($resultado);    
}


function formulario_habilita($tipoAsistencia_id_a){
    
    $resultado = TipoAsistencia::reActivar($tipoAsistencia_id_a);
    
    echo json_encode($resultado);    
}


function formulario_modificacion($tipoAsistencia_clasificacion_id, $tipoAsistencia_nombre, $tipoAsistencia_id){
    
    TipoAsistencia::actualizar($tipoAsistencia_clasificacion_id, $tipoAsistencia_nombre, $tipoAsistencia_id);
}


function formulario_lectura($tipoAsistencia_id){
    
    $tipoAsistencia = TipoAsistencia::buscarPorId($tipoAsistencia_id);
    
    echo json_encode($tipoAsistencia);
}


// Funciones auxiliares de formulario
function tipoAsistencia_existe($tipoAsistencia_nombre_n){
 
    $tipoAsistencia_existente = TipoAsistencia::existe($tipoAsistencia_nombre_n);
    
    if($tipoAsistencia_existente == 1) {
       echo(json_encode("El tipo de asistencia ya existe"));
    }else{    
       echo(json_encode("true"));
    }
    
}

function tipoAsistencia_existe_modificacion($tipoAsistencia_nombre, $tipoAsistencia_id){
 
    
    $tipoAsistencia_existente = TipoAsistencia::existeUpdate($tipoAsistencia_nombre,$tipoAsistencia_id);
    
    if($tipoAsistencia_existente == 1) {
        
       echo(json_encode("El tipo de asistencia ya existe"));
        
    }else{
        
       echo(json_encode("true"));

    }
}


// Funciones de Grilla

function grilla_listar($permisos){
    $tiposAsistencia = TipoAsistencia::listar();

    $grilla = "<div class='row'>";
    $grilla .= "<div id='grilla_abm' class='col-sm-12'>";
    $grilla .=  "<div class='card-box table-responsive'>";
    $grilla .=      "<h4 class='m-t-0 header-title'><b>Tabla de tipos de asistencia</b></h4>";
    $grilla .=      "<table id='dt_tipoAsistencia' class='table table-hover table-striped m-0 table-responsive'>";    
    $grilla .=          "<thead>";
    $grilla .=              "<tr>";
    $grilla .=                  "<th>Nombre</th>";
    $grilla .=                  "<th>Activa</th>";
    $grilla .=                  "<th>Acciones</th>";
    $grilla .=              "</tr>";
    $grilla .=          "</thead>";
    $grilla .=          "<tbody>";  
    foreach($tiposAsistencia as $tipoAsistencia){
            $tipoAsistencia_id = $tipoAsistencia["tipoAsistencia_id"];
            $tipoAsistencia_nombre = $tipoAsistencia["tipoAsistencia_nombre"];
            $tipoAsistencia_activa = $tipoAsistencia["tipoAsistencia_activa"];
    $grilla .=              "<tr>";
    $grilla .=                  "<td>";
    $grilla .=                      $tipoAsistencia_nombre;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    if($tipoAsistencia_activa == 1){
    $grilla .=                 "<span class='label label-success'>Activa</span>";
    }else{
    $grilla .=                 "<span class='label label-danger'>Inactiva</span>";
    }
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      "<a href='javascript:void(0)'> <i onclick='formulario_lectura($tipoAsistencia_id)' class='fa fa-edit'></i></a>";
    
    $tipoAsistencia_baja = array_search('tipoAsistencia_baja', array_column($permisos, 'permiso_variable'));
    if (!empty($tipoAsistencia_baja) || ($tipoAsistencia_baja === 0)) {
        if($tipoAsistencia_activa == 1){
        $grilla .=                      "<a href='javascript:void(0)'> <i onclick='modal_baja($tipoAsistencia_id)' class='fa fa-user-times'></i></a>";
        }else{
        $grilla .=                      "<a href='javascript:void(0)'> <i onclick='modal_alta($tipoAsistencia_id)' class='fa fa-user-plus'></i></a>";
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