<?php
include_once '../includes/herramientas.php';


//print_r($_POST);
//exit();


// Toma las variables del formulario de alta
$usuario_id = isset($_POST["usuario_id"])?$_POST["usuario_id"]:'';
$usuario_nombre_n = isset($_POST["usuario_nombre_n"])?$_POST["usuario_nombre_n"]:'';
$usuario_apellido_n = isset($_POST["usuario_apellido_n"])?$_POST["usuario_apellido_n"]:'';
$usuario_usuario_n = isset($_POST["usuario_usuario_n"])?$_POST["usuario_usuario_n"]:'';
$usuario_rol_id_n = isset($_POST["usuario_rol_id_n"])?$_POST["usuario_rol_id_n"]:'';
$usuario_password_n = isset($_POST["usuario_password_n"])?$_POST["usuario_password_n"]:'';


// Toma las variables del formulario de modificación
$usuario_id = isset($_POST["usuario_id"])?$_POST["usuario_id"]:'';
$usuario_nombre = isset($_POST["usuario_nombre"])?$_POST["usuario_nombre"]:'';
$usuario_apellido = isset($_POST["usuario_apellido"])?$_POST["usuario_apellido"]:'';
$usuario_usuario = isset($_POST["usuario_usuario"])?$_POST["usuario_usuario"]:'';
$usuario_rol_id = isset($_POST["usuario_rol_id"])?$_POST["usuario_rol_id"]:'';
$usuario_habilitado = isset($_POST["usuario_habilitado"])?$_POST["usuario_habilitado"]:'';


// Toma el usuario_id para una baja
$usuario_id_b = isset($_POST["usuario_id_b"])?$_POST["usuario_id_b"]:'';


// Toma el usuario_id para volver a habilitarlo
$usuario_id_a = isset($_POST["usuario_id_a"])?$_POST["usuario_id_a"]:'';


// Definición del ABML (Alta, Baja, Modificacion, Lectura -- objeto_ABML)
$opcion = isset($_POST["opcion"])?$_POST["opcion"]:'';


switch($opcion){
    
    // Acciones de los formularios
    case 'formulario_alta':
        formulario_alta($usuario_nombre_n, $usuario_apellido_n, $usuario_usuario_n, $usuario_rol_id_n, $usuario_password_n);
        break;
     
    case 'formulario_baja':
        formulario_baja($usuario_id_b);
        break;
    
     case 'formulario_habilita':
        formulario_habilita($usuario_id_a);
        break;
    
    
    case 'formulario_modificacion':
        formulario_modificacion($usuario_nombre, $usuario_apellido, $usuario_usuario, $usuario_rol_id, $usuario_id);
        break;
    
    case 'formulario_lectura':
        formulario_lectura($usuario_id);
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
    
    case 'usuario_existe':
        usuario_existe($usuario_usuario_n);
        break;
    
    
    default:
       echo("Está mal seleccionada la funcion");
        
      
        
}


// Funciones de Formulario

function formulario_alta($usuario_nombre, $usuario_apellido, $usuario_usuario, $usuario_rol_id, $usuario_password){
    
    Usuario::insertar($usuario_nombre, $usuario_apellido, $usuario_usuario, $usuario_rol_id, $usuario_password);
            
}


function formulario_baja($usuario_id_b){
    
    $resultado = Usuario::borradoLogico($usuario_id_b);
    
    
     echo json_encode($resultado);    
}

function formulario_habilita($usuario_id_a){
    
    $resultado = Usuario::reActivar($usuario_id_a);
    
    echo json_encode($resultado);    
}



function formulario_modificacion($usuario_nombre, $usuario_apellido, $usuario_usuario, $usuario_rol_id, $usuario_id){
    
    Usuario::actualizar($usuario_nombre, $usuario_apellido, $usuario_usuario, $usuario_rol_id, $usuario_id);

}


function formulario_lectura($usuario_id){
    $usuario = Usuario::buscarPorId($usuario_id);
    echo json_encode($usuario);
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


function usuario_existe($usuario_usuario_n){
 
    $usuario_existente = Usuario::existe($usuario_usuario_n);
    
    if($usuario_existente == 1) {
       echo(json_encode("Ese usuario ya existe"));
    }else{    
       echo(json_encode("true"));
    }
    
}

function usuario_existe_modificacion($usuario_usuario, $usuario_id){
 
    
    $usuario_existente = Usuario::existeUpdate($usuario_usuario, $usuario_id);
    
    if($usuario_existente == 1) {
        
       echo(json_encode("Ese usuario ya existe"));
        
    }else{
        
       echo(json_encode("true"));

    }
}


// Funciones de Grilla

function grilla_listar(){
    $usuarios = Usuario::listar();

    $grilla = "<div class='row'>";
    $grilla .= "<div id='grilla_abm' class='col-sm-12'>";
    $grilla .=  "<div class='card-box table-responsive'>";
    $grilla .=      "<h4 class='m-t-0 header-title'><b>Tabla de usuarios</b></h4>";
    $grilla .=      "<table id='dt_usuario' class='table table-hover table-striped m-0 table-responsive'>";
    $grilla .=          "<thead>";
    $grilla .=              "<tr>";
    $grilla .=                  "<th>Nombre</th>";
    $grilla .=                  "<th>Apellido</th>";
    $grilla .=                  "<th>Usuario</th>";
    $grilla .=                  "<th>Rol</th>";
    $grilla .=                  "<th>Activo</th>";
    $grilla .=                  "<th>Acciones</th>";
    $grilla .=              "</tr>";
    $grilla .=          "</thead>";
    $grilla .=          "<tbody>";  
    foreach($usuarios as $usuario){
            $usuario_id = $usuario["usuario_id"];
            $usuario_nombre = $usuario["usuario_nombre"];
            $usuario_apellido = $usuario["usuario_apellido"];
            $usuario_usuario = $usuario["usuario_usuario"];
            $usuario_activo = $usuario["usuario_activo"];
            $usuario_rol_nombre = $usuario["usuario_rol_nombre"];
    $grilla .=              "<tr>";
    $grilla .=                  "<td>";
    $grilla .=                      $usuario_nombre;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $usuario_apellido;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $usuario_usuario;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $usuario_rol_nombre;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    if($usuario_activo == 1){
    $grilla .=                 "<span class='label label-success'>Activo</span>";
    }else{
    $grilla .=                 "<span class='label label-danger'>Inactivo</span>";
    }
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      "<a href='javascript:void(0)'> <i onclick='formulario_lectura($usuario_id)' class='fa fa-edit'></i></a>";
    if($usuario_activo == 1){
    $grilla .=                      "<a href='javascript:void(0)'> <i onclick='modal_baja($usuario_id)' class='fa fa-user-times'></i></a>";
    }else{
    $grilla .=                      "<a href='javascript:void(0)'> <i onclick='modal_alta($usuario_id)' class='fa fa-user-plus'></i></a>";
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









