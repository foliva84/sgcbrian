<?php
include_once '../includes/herramientas.php';

$permiso_id = isset($_POST["permiso_id"])?$_POST["permiso_id"]:'';

// Toma las variables del formulario de alta
$permiso_nombre_n = isset($_POST["permiso_nombre_n"])?$_POST["permiso_nombre_n"]:'';
$permiso_variable_n = isset($_POST["permiso_variable_n"])?$_POST["permiso_variable_n"]:'';


// Toma las variables del formulario de modificación
$permiso_nombre = isset($_POST["permiso_nombre"])?$_POST["permiso_nombre"]:'';
$permiso_variable = isset($_POST["permiso_variable"])?$_POST["permiso_variable"]:'';


// Definición del ABML (Alta, Baja, Modificacion, Lectura -- objeto_ABML)
$opcion = isset($_POST["opcion"])?$_POST["opcion"]:'';

switch($opcion){
    
    // Acciones de los formularios
    case 'formulario_alta':
        formulario_alta($permiso_nombre_n, $permiso_variable_n);
        break;
     
    case 'formulario_lectura':
        formulario_lectura($permiso_id);
        break;
    
    case 'formulario_modificacion':
        formulario_modificacion($permiso_nombre,$permiso_variable,$permiso_id);
        break;
    
        
    case 'grilla_listar':
        grilla_listar();
        break;
    
    case 'nombre_existe':
        nombre_existe($permiso_nombre_n);
        break;
    
    case 'variable_existe':
        variable_existe($permiso_variable_n);
        break;
    
     case 'nombre_existe_modificacion':
        nombre_existe_modificacion($permiso_nombre, $permiso_id);
        break;
    
    case 'variable_existe_modificacion':
        variable_existe_modificacion($permiso_variable, $permiso_id);
        break;
    
    default:
       echo("Está mal seleccionada la funcion");
        
}


// Funciones de Formulario

function formulario_alta($p_permiso_nombre, $p_permiso_variable){
 
  $resultado = Permiso::insertar($p_permiso_nombre, $p_permiso_variable);
  return $resultado;
            
}


function formulario_lectura($permiso_id){
   
   $permiso = Permiso::buscarPorId($permiso_id);
   echo json_encode($permiso);
}


function formulario_modificacion($permiso_nombre,$permiso_variable,$permiso_id){
    
   $resultado = Permiso::actualizar($permiso_nombre, $permiso_variable, $permiso_id);
   return $resultado;
}




function nombre_existe($permiso_nombre_n){
 
   $nombre_existente = Permiso::nombre_existe($permiso_nombre_n);
    
    if($nombre_existente === true) {
       echo(json_encode("El nombre del permiso ya existe"));
    }else{    
       echo(json_encode("true"));
    }
    
}

function variable_existe($permiso_variable_n){
 
   $variable_existente = Permiso::variable_existe($permiso_variable_n);
    
    if($variable_existente === true) {
       echo(json_encode("La variable del permiso ya existe"));
    }else{    
       echo(json_encode("true"));
    }
    
}



function nombre_existe_modificacion($permiso_nombre, $permiso_id){
 
   $nombre_existente = Permiso::nombre_existe_modificacion($permiso_nombre, $permiso_id);
    
    if($nombre_existente === true) {
       echo(json_encode("El nombre del permiso ya existe"));
    }else{    
       echo(json_encode("true"));
    }
    
}

function variable_existe_modificacion($permiso_variable, $permiso_id){
 
   $variable_existente = Permiso::variable_existe_modificacion($permiso_variable, $permiso_id);
    
    if($variable_existente === true) {
       echo(json_encode("La variable del permiso ya existe"));
    }else{    
       echo(json_encode("true"));
    }
    
}



// Funciones de Grilla

function grilla_listar(){
    $permisos = Permiso::listar();

    $grilla = "<div class='row'>";
    $grilla .= "<div id='grilla_abm' class='col-sm-12'>";
    $grilla .=  "<div class='card-box table-responsive'>";
    $grilla .=      "<h4 class='m-t-0 header-title'><b>Tabla de usuarios</b></h4>";
    $grilla .=      "<table id='dt_permiso' class='table table-hover table-striped m-0 table-responsive'>";
    $grilla .=          "<thead>";
    $grilla .=              "<tr>";
    $grilla .=                  "<th>Permiso</th>";
    $grilla .=                  "<th>Variable</th>";
    $grilla .=                  "<th>Acciones</th>";
    $grilla .=              "</tr>";
    $grilla .=          "</thead>";
    $grilla .=          "<tbody>";  
    foreach($permisos as $permiso){
            $permiso_id = $permiso["permiso_id"];
            $permiso_nombre = $permiso["permiso_nombre"];
            $permiso_variable = $permiso["permiso_variable"];           
    $grilla .=              "<tr>";
    $grilla .=                  "<td>";
    $grilla .=                      $permiso_nombre;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $permiso_variable;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      "<a href='javascript:void(0)'> <i onclick='formulario_lectura($permiso_id)' class='fa fa-edit'></i></a>";
    $grilla .=                      "<a href='javascript:void(0)'> <i onclick='modal_alta($permiso_id)' class='fa fa-user-plus'></i></a>";
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