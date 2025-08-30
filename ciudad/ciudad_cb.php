<?php
require '../seguridad/seguridad.php';
include_once '../includes/herramientas.php';


//print_r($_POST);
//exit();


// Toma las variables del formulario de alta
$ciudad_pais_id_n = isset($_POST["ciudad_pais_id_n"])?$_POST["ciudad_pais_id_n"]:'';
$ciudad_nombre_n = isset($_POST["ciudad_nombre_n"])?$_POST["ciudad_nombre_n"]:'';

// Toma las variables del formulario de modificación
$ciudad_id = isset($_POST["ciudad_id"])?$_POST["ciudad_id"]:'';
$ciudad_pais_id = isset($_POST["ciudad_pais_id"])?$_POST["ciudad_pais_id"]:'';
$ciudad_nombre = isset($_POST["ciudad_nombre"])?$_POST["ciudad_nombre"]:'';

// Toma el id de país para validacion de existe
$pais_id = isset($_POST["id_pais"])?$_POST["id_pais"]:'';


// Toma el ciudad_id para una baja
$ciudad_id_b = isset($_POST["ciudad_id_b"])?$_POST["ciudad_id_b"]:'';


// Toma el ciudad_id para volver a habilitarlo
$ciudad_id_a = isset($_POST["ciudad_id_a"])?$_POST["ciudad_id_a"]:'';


$ciudad_nombre_buscar = isset($_POST["ciudad_nombre_buscar"])?$_POST["ciudad_nombre_buscar"]:'';
$ciudad_pais_buscar = isset($_POST["ciudad_pais_buscar"])?$_POST["ciudad_pais_buscar"]:'';



// Definición del ABML (Alta, Baja, Modificacion, Lectura -- objeto_ABML)
$opcion = isset($_POST["opcion"])?$_POST["opcion"]:'';


switch($opcion){
    
    // Acciones de los formularios
    case 'formulario_alta':
        formulario_alta($ciudad_pais_id_n, $ciudad_nombre_n);
        break;
     
    case 'formulario_baja':
        formulario_baja($ciudad_id_b);
        break;
    
    case 'formulario_habilita':
        formulario_habilita($ciudad_id_a);
        break;
    
    case 'formulario_modificacion':
        formulario_modificacion($ciudad_pais_id, $ciudad_nombre, $ciudad_id);
        break;
    
    case 'formulario_lectura':
        formulario_lectura($ciudad_id);
        break;
    
    case 'grilla_listar':
        grilla_listar($ciudad_nombre_buscar,$ciudad_pais_buscar, $permisos);
        break;
     
    
    case 'grilla_listar_contar':
        grilla_listar_contar($ciudad_nombre_buscar,$ciudad_pais_buscar);
        break;
    
    
    
    
// Acciones auxiliares en el formulario

    case 'ciudad_existe':
        ciudad_existe($ciudad_nombre_n, $ciudad_pais_id_n);
        break;

    case 'ciudad_existe_modificacion':
        ciudad_existe_modificacion($ciudad_nombre, $ciudad_id, $ciudad_pais_id);
        break;
    
    case 'formulario_alta_paises':
        formulario_alta_paises();
        break;

    case 'formulario_modificacion_paises':
        formulario_modificacion_paises($ciudad_id);
        break;
        
 
    
    
    default:
       echo("Está mal seleccionada la funcion");
        
        
}


// Funciones de Formulario

function formulario_alta($ciudad_pais_id_n, $ciudad_nombre_n){
    
    Ciudad::insertar($ciudad_pais_id_n, $ciudad_nombre_n);
            
}

function formulario_baja($ciudad_id_b){
    
    $resultado = Ciudad::borradoLogico($ciudad_id_b);
    
    echo json_encode($resultado);    
}

function formulario_habilita($ciudad_id_a){
    
    $resultado = Ciudad::reActivar($ciudad_id_a);
    
    echo json_encode($resultado);    
}

function formulario_modificacion($ciudad_pais_id, $ciudad_nombre, $ciudad_id){
    
    Ciudad::actualizar($ciudad_pais_id, $ciudad_nombre, $ciudad_id);

}

function formulario_lectura($ciudad_id){
    $ciudad = Ciudad::buscarPorId($ciudad_id);
    echo json_encode($ciudad);
}

// Funciones auxiliares de formulario

function formulario_alta_paises(){

        $paises = Pais::formulario_alta_paises();

        echo json_encode($paises);   
    }
    
function formulario_modificacion_paises($ciudad_id){

        $paises = Pais::formulario_modificacion_ciudad_paises($ciudad_id);

        echo json_encode($paises);   
    }

function ciudad_existe($ciudad_nombre_n, $ciudad_pais_id_n){
 
    $ciudad_existente = Ciudad::existe($ciudad_nombre_n, $ciudad_pais_id_n);
    
    if($ciudad_existente == 1) {
       echo(json_encode("Ya existe la ciudad para el pais elegido"));
    }else{    
       echo(json_encode("true"));
    }
    
}

function ciudad_existe_modificacion($ciudad_nombre, $ciudad_id, $ciudad_pais_id){
 
    $ciudad_existente = Ciudad::existeUpdate($ciudad_nombre, $ciudad_id, $ciudad_pais_id);
    
    if($ciudad_existente == 1) {
       echo(json_encode("Ya existe la ciudad para el pais elegido"));
    }else{
       echo(json_encode("true"));
    }
}


// Funciones de Grilla


function grilla_listar_contar($ciudad_nombre_buscar, $ciudad_pais_buscar){
    $ciudades = Ciudad::listar_filtrado_contar($ciudad_nombre_buscar, $ciudad_pais_buscar);

    $cantidad = $ciudades['registros'];
    If ($cantidad > 50){
        $texto = "<i class='fa fa-exclamation-circle'></i>   Se han encontrado " . $cantidad . " registros. Se muestran sólo los primeros 50 resultados. Por favor refine su búsqueda.";
    } else {
        $texto = "<p> Se han encontrado " . $cantidad . " registros.</p>";
    }
    
    $grilla = "<div class='row'>";
    $grilla .= "<div id='grilla_abm' class='col-sm-12'>";
    $grilla .=  "<div class='card-box table-responsive'>";
    $grilla .=      $texto;
    $grilla .=    "</div>";
    $grilla .=  "</div>";
    $grilla .="</div>";   
    
    echo $grilla;
}


function grilla_listar($ciudad_nombre_buscar, $ciudad_pais_buscar, $permisos){
    
    $ciudades = Ciudad::listar_filtrado($ciudad_nombre_buscar, $ciudad_pais_buscar);

    $grilla = "<div class='row'>";
    $grilla .= "<div id='grilla_abm' class='col-sm-12'>";
    $grilla .=  "<div class='card-box table-responsive'>";
    $grilla .=      "<h4 class='m-t-0 header-title'><b>Tabla de ciudades</b></h4>";
    $grilla .=      "<table id='dt_ciudad' class='table table-hover table-striped m-0 table-responsive'>";
    $grilla .=          "<thead>";
    $grilla .=              "<tr>";
    $grilla .=                  "<th>Pais</th>";
    $grilla .=                  "<th>Ciudad</th>";
    $grilla .=                  "<th>Activo</th>";
    $grilla .=                  "<th>Acciones</th>";
    $grilla .=              "</tr>";
    $grilla .=          "</thead>";
    $grilla .=          "<tbody>";  
    foreach($ciudades as $ciudad){
            $ciudad_id = $ciudad["ciudad_id"];
            $pais_nombre = $ciudad["pais_nombreEspanol"];
            $ciudad_nombre = $ciudad["ciudad_nombre"];
            $ciudad_activa = $ciudad["ciudad_activa"];
    $grilla .=              "<tr>";
    $grilla .=                  "<td>";
    $grilla .=                      $pais_nombre;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $ciudad_nombre;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    if($ciudad_activa == 1){
    $grilla .=                 "<span class='label label-success'>Activo</span>";
    }else{
    $grilla .=                 "<span class='label label-danger'>Inactivo</span>";
    }
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    
    $ciudades_modificar = array_search('ciudades_modificar', array_column($permisos, 'permiso_variable'));
    if (!empty($ciudades_modificar) || ($ciudades_modificar === 0)) {
        $grilla .=                      "<a href='javascript:void(0)'> <i onclick='formulario_lectura($ciudad_id)' class='fa fa-edit'></i></a>";
    }

    $ciudades_baja = array_search('ciudades_baja', array_column($permisos, 'permiso_variable'));
    if (!empty($ciudades_baja) || ($ciudades_baja === 0)) {
        if($ciudad_activa == 1){
        $grilla .=                      "<a href='javascript:void(0)'> <i onclick='modal_baja($ciudad_id)' class='fa fa-user-times'></i></a>";
        }else{
        $grilla .=                      "<a href='javascript:void(0)'> <i onclick='modal_alta($ciudad_id)' class='fa fa-user-plus'></i></a>";
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