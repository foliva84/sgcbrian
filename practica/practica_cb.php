<?php
require '../seguridad/seguridad.php';
include_once '../includes/herramientas.php';


//print_r($_POST);
//exit();


// Toma las variables del formulario de alta
$practica_id = isset($_POST["practica_id"])?$_POST["practica_id"]:'';
$practica_nombre_n = isset($_POST["practica_nombre_n"])?$_POST["practica_nombre_n"]:'';

// Toma las variables del formulario de modificación
$practica_id = isset($_POST["practica_id"])?$_POST["practica_id"]:'';
$practica_nombre = isset($_POST["practica_nombre"])?$_POST["practica_nombre"]:'';
$practica_habilitado = isset($_POST["practica_habilitado"])?$_POST["practica_habilitado"]:'';

// Toma la practica_id para una baja
$practica_id_b = isset($_POST["practica_id_b"])?$_POST["practica_id_b"]:'';

// Toma el practica_id para volver a habilitarlo
$practica_id_a = isset($_POST["practica_id_a"])?$_POST["practica_id_a"]:'';

// Definición del ABML (Alta, Baja, Modificacion, Lectura -- objeto_ABML)
$opcion = isset($_POST["opcion"])?$_POST["opcion"]:'';


switch($opcion){
    
    // Acciones de los formularios
    case 'formulario_alta':
        formulario_alta($practica_nombre_n);
        break;
     
    case 'formulario_baja':
        formulario_baja($practica_id_b);
        break;
    
    case 'formulario_habilita':
        formulario_habilita($practica_id_a);
        break;
    
    case 'formulario_modificacion':
        formulario_modificacion($practica_nombre, $practica_id);
        break;
    
    case 'formulario_lectura':
        formulario_lectura($practica_id);
        break;
    
    case 'grilla_listar':
        grilla_listar($permisos);
        break;
     
// Acciones auxiliares en el formulario

    case 'practica_existe':
        practica_existe($practica_nombre_n);
        break;

    case 'practica_existe_modificacion':
        practica_existe_modificacion($practica_nombre, $practica_id);
        break;
        
    default:
       echo("Está mal seleccionada la funcion");
        
}


// Funciones de Formulario

function formulario_alta($practica_nombre){
    
    Practica::insertar($practica_nombre);
            
}

function formulario_baja($practica_id_b){
    
    $resultado = Practica::borradoLogico($practica_id_b);
    
    echo json_encode($resultado);    
}

function formulario_habilita($practica_id_a){
    
    $resultado = Practica::reActivar($practica_id_a);
    
    echo json_encode($resultado);    
}

function formulario_modificacion($practica_nombre, $practica_id){
    
    Practica::actualizar($practica_nombre, $practica_id);

}

function formulario_lectura($practica_id){
    $practica = Practica::buscarPorId($practica_id);
    echo json_encode($practica);
}

// Funciones auxiliares de formulario

function practica_existe($practica_nombre_n){
 
    $practica_existente = Practica::existe($practica_nombre_n);
    
    if($practica_existente == 1) {
       echo(json_encode("La practica ingresada ya existe"));
    }else{    
       echo(json_encode("true"));
    }
    
}

function practica_existe_modificacion($practica_nombre, $practica_id){
 
    
    $practica_existente = Practica::existeUpdate($practica_nombre, $practica_id);
    
    if($practica_existente == 1) {
        
       echo(json_encode("La practica ingresada ya existe"));
        
    }else{
        
       echo(json_encode("true"));

    }
}


// Funciones de Grilla

function grilla_listar($permisos){
    $practicas = Practica::listar();

    $grilla = "<div class='row'>";
    $grilla .= "<div id='grilla_abm' class='col-sm-12'>";
    $grilla .=  "<div class='card-box table-responsive'>";
    $grilla .=      "<h4 class='m-t-0 header-title'><b>Tabla de practicas</b></h4>";
    $grilla .=      "<table id='dt_practica' class='table table-hover table-striped m-0 table-responsive'>";
    $grilla .=          "<thead>";
    $grilla .=              "<tr>";
    $grilla .=                  "<th>Nombre</th>";
    $grilla .=                  "<th>Activo</th>";
    $grilla .=                  "<th>Acciones</th>";
    $grilla .=              "</tr>";
    $grilla .=          "</thead>";
    $grilla .=          "<tbody>";  
    foreach($practicas as $practica){
            $practica_id = $practica["practica_id"];
            $practica_nombre = $practica["practica_nombre"];
            $practica_activa = $practica["practica_activa"];
    $grilla .=              "<tr>";
    $grilla .=                  "<td>";
    $grilla .=                      $practica_nombre;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    if($practica_activa == 1){
    $grilla .=                 "<span class='label label-success'>Activo</span>";
    }else{
    $grilla .=                 "<span class='label label-danger'>Inactivo</span>";
    }
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      "<a href='javascript:void(0)'> <i onclick='formulario_lectura($practica_id)' class='fa fa-edit'></i></a>";
    
    $practicas_baja = array_search('practicas_baja', array_column($permisos, 'permiso_variable'));
    if (!empty($practicas_baja) || ($practicas_baja === 0)) {
        if($practica_activa == 1){
        $grilla .=                      "<a href='javascript:void(0)'> <i onclick='modal_baja($practica_id)' class='fa fa-user-times'></i></a>";
        }else{
        $grilla .=                      "<a href='javascript:void(0)'> <i onclick='modal_alta($practica_id)' class='fa fa-user-plus'></i></a>";
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