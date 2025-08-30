<?php
require '../seguridad/seguridad.php';
include_once '../includes/herramientas.php';


//print_r($_POST);
//exit();


// Toma las variables del formulario de alta
$diagnostico_id = isset($_POST["diagnostico_id"])?$_POST["diagnostico_id"]:'';
$diagnostico_codigoICD_n = isset($_POST["diagnostico_codigoICD_n"])?$_POST["diagnostico_codigoICD_n"]:'';
$diagnostico_nombre_n = isset($_POST["diagnostico_nombre_n"])?$_POST["diagnostico_nombre_n"]:'';

// Toma las variables del formulario de modificación
$diagnostico_id = isset($_POST["diagnostico_id"])?$_POST["diagnostico_id"]:'';
$diagnostico_codigoICD = isset($_POST["diagnostico_codigoICD"])?$_POST["diagnostico_codigoICD"]:'';
$diagnostico_nombre = isset($_POST["diagnostico_nombre"])?$_POST["diagnostico_nombre"]:'';
$diagnostico_habilitado = isset($_POST["diagnostico_habilitado"])?$_POST["diagnostico_habilitado"]:'';


// Toma el diagnostico_id para una baja
$diagnostico_id_b = isset($_POST["diagnostico_id_b"])?$_POST["diagnostico_id_b"]:'';


// Toma el diagnostico_id para volver a habilitarlo
$diagnostico_id_a = isset($_POST["diagnostico_id_a"])?$_POST["diagnostico_id_a"]:'';

//Toma variables del buscador
$diagnostico_nombre_buscar = isset($_POST["diagnostico_nombre_buscar"])?$_POST["diagnostico_nombre_buscar"]:'';
$diagnostico_codigoICD_buscar = isset($_POST["diagnostico_codigoICD_buscar"])?$_POST["diagnostico_codigoICD_buscar"]:'';

// Definición del ABML (Alta, Baja, Modificacion, Lectura -- objeto_ABML)
$opcion = isset($_POST["opcion"])?$_POST["opcion"]:'';


switch($opcion){
    
    // Acciones de los formularios
    case 'formulario_alta':
        formulario_alta($diagnostico_codigoICD_n, $diagnostico_nombre_n);
        break;
     
    case 'formulario_baja':
        formulario_baja($diagnostico_id_b);
        break;
    
    case 'formulario_habilita':
        formulario_habilita($diagnostico_id_a);
        break;
    
    case 'formulario_modificacion':
        formulario_modificacion($diagnostico_codigoICD, $diagnostico_nombre, $diagnostico_id);
        break;
    
    case 'formulario_lectura':
        formulario_lectura($diagnostico_id);
        break;
    
    case 'grilla_listar':
        grilla_listar($diagnostico_nombre_buscar, $diagnostico_codigoICD_buscar, $permisos);
        break;     
    
    case 'grilla_listar_contar':
        grilla_listar_contar($diagnostico_nombre_buscar, $diagnostico_codigoICD_buscar);
        break;
     
// Acciones auxiliares en el formulario

    case 'codigoICD_existe':
        codigoICD_existe($diagnostico_codigoICD_n);
        break;
    
    case 'diagnostico_existe':
        diagnostico_existe($diagnostico_nombre_n);
        break;
    
    case 'codigoICD_existe_modificacion':
        codigoICD_existe_modificacion($diagnostico_codigoICD, $diagnostico_id);
        break;

    case 'diagnostico_existe_modificacion':
        diagnostico_existe_modificacion($diagnostico_nombre, $diagnostico_id);
        break;
        
 
    
    
    default:
       echo("Está mal seleccionada la funcion");
        
        
}


// Funciones de Formulario

function formulario_alta($diagnostico_codigoICD, $diagnostico_nombre){
    
    Diagnostico::insertar($diagnostico_codigoICD, $diagnostico_nombre);
            
}

function formulario_baja($diagnostico_id_b){
    
    $resultado = Diagnostico::borradoLogico($diagnostico_id_b);
    
    echo json_encode($resultado);    
}

function formulario_habilita($diagnostico_id_a){
    
    $resultado = Diagnostico::reActivar($diagnostico_id_a);
    
    echo json_encode($resultado);    
}

function formulario_modificacion($diagnostico_codigoICD, $diagnostico_nombre, $diagnostico_id){
    
    Diagnostico::actualizar($diagnostico_codigoICD, $diagnostico_nombre, $diagnostico_id);

}

function formulario_lectura($diagnostico_id){
    $diagnostico = Diagnostico::buscarPorId($diagnostico_id);
    echo json_encode($diagnostico);
}

// Funciones auxiliares de formulario

function codigoICD_existe($diagnostico_codigoICD_n){
 
    $codigoICD_existente = Diagnostico::existe_codigoICD($diagnostico_codigoICD_n);
    
    if($codigoICD_existente == 1) {
       echo(json_encode("El codigo ICD ingresado ya existe"));
    }else{    
       echo(json_encode("true"));
    }
}

function diagnostico_existe($diagnostico_nombre_n){
 
    $diagnostico_existente = Diagnostico::existe($diagnostico_nombre_n);
    
    if($diagnostico_existente == 1) {
       echo(json_encode("El diagnostico ingresado ya existe"));
    }else{    
       echo(json_encode("true"));
    }
}

function codigoICD_existe_modificacion($diagnostico_codigoICD, $diagnostico_id){
 
    
    $codigoICD_existente = Diagnostico::existeUpdate_codigoICD($diagnostico_codigoICD, $diagnostico_id);
    
    if($codigoICD_existente == 1) {
        
       echo(json_encode("El codigo ICD ingresado ya existe"));
        
    }else{
        
       echo(json_encode("true"));

    }
}

function diagnostico_existe_modificacion($diagnostico_nombre, $diagnostico_id){
 
    
    $diagnostico_existente = Diagnostico::existeUpdate($diagnostico_nombre, $diagnostico_id);
    
    if($diagnostico_existente == 1) {
        
       echo(json_encode("El diagnostico ingresado ya existe"));
        
    }else{
        
       echo(json_encode("true"));

    }
}


// Funciones de Grilla

function grilla_listar_contar($diagnostico_nombre_buscar, $diagnostico_codigoICD_buscar){
    
    $diagnosticos = Diagnostico::listar_filtrado_contar($diagnostico_nombre_buscar, $diagnostico_codigoICD_buscar);

    $cantidad = $diagnosticos['registros'];
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


function grilla_listar($diagnostico_nombre_buscar, $diagnostico_codigoICD_buscar, $permisos){
    
    $diagnosticos = Diagnostico::listar_filtrado($diagnostico_nombre_buscar, $diagnostico_codigoICD_buscar);

    $grilla = "<div class='row'>";
    $grilla .= "<div id='grilla_abm' class='col-sm-12'>";
    $grilla .=  "<div class='card-box table-responsive'>";
    $grilla .=      "<h4 class='m-t-0 header-title'><b>Tabla de diagnosticos</b></h4>";
    $grilla .=      "<table id='dt_diagnostico' class='table table-hover table-striped m-0 table-responsive'>";
    $grilla .=          "<thead>";
    $grilla .=              "<tr>";
    $grilla .=                  "<th>Codigo ICD</th>";
    $grilla .=                  "<th>Diagnostico</th>";
    $grilla .=                  "<th>Activo</th>";
    $grilla .=                  "<th>Acciones</th>";
    $grilla .=              "</tr>";
    $grilla .=          "</thead>";
    $grilla .=          "<tbody>";  
    foreach($diagnosticos as $diagnostico){
            $diagnostico_id = $diagnostico["diagnostico_id"];
            $diagnostico_codigoICD = $diagnostico["diagnostico_codigoICD"];
            $diagnostico_nombre = $diagnostico["diagnostico_nombre"];
            $diagnostico_activo = $diagnostico["diagnostico_activo"];
    $grilla .=              "<tr>";
    $grilla .=                  "<td>";
    $grilla .=                      $diagnostico_codigoICD;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $diagnostico_nombre;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    if($diagnostico_activo == 1){
    $grilla .=                 "<span class='label label-success'>Activo</span>";
    }else{
    $grilla .=                 "<span class='label label-danger'>Inactivo</span>";
    }
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      "<a href='javascript:void(0)'> <i onclick='formulario_lectura($diagnostico_id)' class='fa fa-edit'></i></a>";
    
    $diagnosticos_baja = array_search('diagnosticos_baja', array_column($permisos, 'permiso_variable'));
    if (!empty($diagnosticos_baja) || ($diagnosticos_baja === 0)) {
        if($diagnostico_activo == 1){
        $grilla .=                      "<a href='javascript:void(0)'> <i onclick='modal_baja($diagnostico_id)' class='fa fa-user-times'></i></a>";
        }else{
        $grilla .=                      "<a href='javascript:void(0)'> <i onclick='modal_alta($diagnostico_id)' class='fa fa-user-plus'></i></a>";
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