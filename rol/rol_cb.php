<?php
include_once '../includes/herramientas.php';



// Toma las variables del formulario de alta

$rol_nombre_n = isset($_POST["rol_nombre_n"])?$_POST["rol_nombre_n"]:'';
$rol_orden_n = isset($_POST["rol_orden_n"])?$_POST["rol_orden_n"]:'';
$rol_jerarquia_n = isset($_POST["rol_jerarquia_n"])?$_POST["rol_jerarquia_n"]:'';


// Toma las variables del formulario de modificación
$rol_id = isset($_POST["rol_id"])?$_POST["rol_id"]:'';
$rol_nombre = isset($_POST["rol_nombre"])?$_POST["rol_nombre"]:'';
$rol_orden = isset($_POST["rol_orden"])?$_POST["rol_orden"]:'';
$rol_jerarquia = isset($_POST["rol_jerarquia"])?$_POST["rol_jerarquia"]:'';




// Definición del ABML (Alta, Baja, Modificacion, Lectura -- objeto_ABML)

$opcion = isset($_POST["opcion"])?$_POST["opcion"]:'';

switch($opcion){
    
    // Acciones de los formularios
    case 'formulario_alta':
        formulario_alta($rol_nombre_n, $rol_orden_n, $rol_jerarquia_n);
        break;
     
    case 'formulario_baja':
        formulario_baja($usuario_id_b);
        break;
    
     case 'formulario_habilita':
        formulario_habilita($usuario_id_a);
        break;
    
    
    case 'formulario_modificacion':
        formulario_modificacion($rol_id, $rol_nombre, $rol_orden, $rol_jerarquia);

        break;
    
    case 'formulario_lectura':
        formulario_lectura($rol_id);
        break;
    
    case 'grilla_listar':
        grilla_listar();
        break;
     
    
    // Acciones auxiliares en el formulario
    
    case 'formulario_alta_roles':
        formulario_alta_roles();
        break;
    
    case 'formulario_modificacion_roles':
        formulario_modificacion_roles($usuario_id);
        break;
    
    
    case 'usuario_existe_modificacion':
        usuario_existe_modificacion($usuario_usuario, $usuario_id);
        break;
    
    case 'rol_existe':
        rol_existe($rol_nombre_n);
        break;
    
    case 'rol_existe_modificacion':
        rol_existe_modificacion($rol_nombre);
        break;
    
    
    default:
       echo("Está mal seleccionada la funcion");
        
      
        
}


// Funciones de Formulario

function formulario_alta($rol_nombre, $rol_orden, $rol_jerarquia){
  
    $resultado = Rol::alta($rol_nombre, $rol_orden, $rol_jerarquia);
    
    echo json_encode($resultado); 
}


function formulario_baja($usuario_id_b){
    
    $resultado = Usuario::borradoLogico($usuario_id_b);
    
    
     echo json_encode($resultado);    
}

function formulario_habilita($usuario_id_a){
    
    $resultado = Usuario::reActivar($usuario_id_a);
    
    echo json_encode($resultado);    
}

function rol_existe($rol_nombre_n){
 
    $rol_existente = rol::existe($rol_nombre_n);
    
    if($rol_existente == 1) {
       echo(json_encode("Ese rol ya existe"));
    }else{    
       echo(json_encode("true"));
    }
}
function rol_existe_modificacion($rol_nombre){
 
    $rol_existente = rol::existe($rol_nombre);
    
    if($rol_existente == 1) {
       echo(json_encode("Ese rol ya existe"));
    }else{    
       echo(json_encode("true"));
    }
}

function formulario_modificacion($rol_id, $rol_nombre, $rol_orden, $rol_jerarquia){
    
    

    $resultado = Rol::modificar($rol_id, $rol_nombre, $rol_orden, $rol_jerarquia);
    
    echo json_encode($resultado);
}


function formulario_lectura($rol_id){
    
    $rol = Rol::buscarPorid($rol_id);
    
    echo json_encode($rol);
}


// Funciones auxiliares de formulario

function formulario_alta_roles(){
    
    $roles = Rol::formulario_alta_roles();
    
    echo json_encode($roles);
    
}

function formulario_modificacion_roles($usuario_id){
    
    $roles = Rol::formulario_modificacion_roles($usuario_id);
    
    echo json_encode($roles);
    
}





// Funciones de Grilla

function grilla_listar(){
    $roles = Rol::listar();

    $grilla = "<div class='row'>";
    $grilla .= "<div id='grilla_abm' class='col-sm-12'>";
    $grilla .=  "<div class='card-box table-responsive'>";
    $grilla .=      "<h4 class='m-t-0 header-title'><b>Tabla de roles</b></h4>";
    $grilla .=      "<table id='dt_rol' class='table table-hover table-striped m-0 table-responsive'>";
    $grilla .=          "<thead>";
    $grilla .=              "<tr>";
    $grilla .=                  "<th>Nombre</th>";
    $grilla .=                  "<th>Orden</th>";
    $grilla .=                  "<th>Jerarquía</th>";
    $grilla .=                  "<th>Acciones</th>";
    $grilla .=              "</tr>";
    $grilla .=          "</thead>";
    $grilla .=          "<tbody>";  
    foreach($roles as $rol){
            $rol_id = $rol["rol_id"];
            $rol_nombre = $rol["rol_nombre"];
            $rol_orden = $rol["rol_orden"];
            $rol_jerarquia = $rol["rol_jerarquia"];
    $grilla .=              "<tr>";
    $grilla .=                  "<td>";
    $grilla .=                      $rol_nombre;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $rol_orden;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $rol_jerarquia;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      "<a href='javascript:void(0)'> <i onclick='formulario_lectura($rol_id)' class='fa fa-edit'></i></a>";
    $grilla .=                      "<a href='javascript:void(0)'> <i onclick='permisos($rol_id)' class='fa fa-unlock'></i></a>";
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









