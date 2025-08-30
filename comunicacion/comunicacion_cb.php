<?php
require '../seguridad/seguridad.php';
include_once '../includes/herramientas.php';


// Toma las variables del formulario de alta
$comunicacion_asunto_id_n = isset($_POST["comunicacion_asunto_id_n"])?$_POST["comunicacion_asunto_id_n"]:'';
$comunicacion_casoEstado_id_n = isset($_POST["comunicacion_casoEstado_id_n"])?$_POST["comunicacion_casoEstado_id_n"]:'';
$comunicacion_n = isset($_POST["comunicacion_n"])?$_POST["comunicacion_n"]:'';
$caso_id_n = isset($_POST["caso_id_n"])?$_POST["caso_id_n"]:'';
$medical_cost = isset($_POST["medical_cost"])?$_POST["medical_cost"]:'';

// Toma las variables del formulario de modificación
$comunicacion_id = isset($_POST["comunicacion_id"])?$_POST["comunicacion_id"]:'';
$comunicacion_asunto_id = isset($_POST["comunicacion_asunto_id"])?$_POST["comunicacion_asunto_id"]:'';
$comunicacion_casoEstado_id = isset($_POST["comunicacion_casoEstado_id"])?$_POST["comunicacion_casoEstado_id"]:'';
$comunicacion = isset($_POST["comunicacion"])?$_POST["comunicacion"]:'';
$comunicacion_fechaIngreso = isset($_POST["comunicacion_fechaIngreso"])?$_POST["comunicacion_fechaIngreso"]:'';

$caso_id = isset($_POST["caso_id"])?$_POST["caso_id"]:'';
$asunto_tipo_id = isset($_POST["asunto_tipo_id"])?$_POST["asunto_tipo_id"]:'';

// Toma el comunicacion_id para deshabilitar
$comunicacion_id_b = isset($_POST["comunicacion_id_b"])?$_POST["comunicacion_id_b"]:'';


// Toma el comunicacion_id para volver a habilitar
$comunicacion_id_a = isset($_POST["comunicacion_id_a"])?$_POST["comunicacion_id_a"]:'';


// Definición del ABML (Alta, Baja, Modificacion, Lectura -- objeto_ABML)
$opcion = isset($_POST["opcion"])?$_POST["opcion"]:'';


switch($opcion){
    
    // Acciones de los formularios
    case 'formulario_alta':
        formulario_alta($comunicacion_asunto_id_n, 
                        $comunicacion_casoEstado_id_n, 
                        $comunicacion_n, 
                        $caso_id_n,
                        $sesion_usuario_id,
                        $medical_cost);
        break;
     
    case 'formulario_baja':
        formulario_baja($comunicacion_id_b);
        break;
    
    case 'formulario_habilita':
        formulario_habilita($comunicacion_id_a);
        break;
    
    case 'formulario_modificacion':
        formulario_modificacion($comunicacion_asunto_id, 
                                $comunicacion_casoEstado_id, 
                                $comunicacion, 
                                $comunicacion_id,
                                $comunicacion_fechaIngreso,
                                $caso_id,
                                $sesion_usuario_id);
        break;

    case 'formulario_vistaDatosCaso':
        formulario_vistaDatosCaso($caso_id);
        break;
    
    case 'formulario_lectura':
        formulario_lectura($comunicacion_id);
        break;
    
    case 'grilla_listar':
        grilla_listar($caso_id, $asunto_tipo_id, $permisos, $sesion_usuario_id);
        break;
    
    case 'grilla_listar_historial':
        grilla_listar_historial($caso_id, $comunicacion_id);
        break;
    
    case 'grilla_producto':
        grilla_producto($caso_id);
        break;
 
    // Acciones auxiliares en el formulario
    case 'listarAsunto_altaComunicacion':
        listarAsunto_altaComunicacion();
        break;
    
    case 'listarCasoEstado_altaComunicacion':
        listarCasoEstado_altaComunicacion($caso_id, $permisos);
        break;
    
    case 'listarAsunto_modificarComunicacion':
        listarAsunto_modificarComunicacion($comunicacion_id);
        break;
    
    case 'listarCasoEstado_modificarComunicacion':
        listarCasoEstado_modificarComunicacion($comunicacion_id);
        break;
    
    case 'listarTiposAsunto_grillaComunicaciones':
        listarTiposAsunto_grillaComunicaciones();
        break;    
    
    case 'listarComunicacionesPorAsunto':
        listarComunicacionesPorAsunto($caso_id_n);
        break;   
    
    default:
       echo("Está mal seleccionada la funcion");
}


// Funciones de Formulario
function formulario_alta($comunicacion_asunto_id_n, 
                         $comunicacion_casoEstado_id_n, 
                         $comunicacion_n, 
                         $caso_id_n,
                         $sesion_usuario_id,
                         $medical_cost){
    
    Comunicacion::insertar($comunicacion_asunto_id_n,
                           $comunicacion_casoEstado_id_n, 
                           $comunicacion_n, 
                           $caso_id_n,
                           $sesion_usuario_id,
                           $medical_cost);            
}

function listarComunicacionesPorAsunto($caso_id_n){
    
    $nuevo = count(Comunicacion::listarComunicacionesPorAsunto($caso_id_n, 14));
    $confirmado = count(Servicio::listar_confirmados($caso_id_n));
    $total_servicios = count(Servicio::contar($caso_id_n));
    $servicios_costo0 = count(Servicio::listar_asistencia_costo($caso_id_n, 0));

    echo json_encode(['nuevo'=>$nuevo, 'confirmado'=>$confirmado, 'total_servicios'=>$total_servicios, 'servicios_costo0'=>$servicios_costo0]);   
}

function formulario_baja($comunicacion_id_b){
    
    $resultado = Comunicacion::borradoLogico($comunicacion_id_b);
    
    echo json_encode($resultado);    
}


function formulario_habilita($comunicacion_id_a){
    
    $resultado = Comunicacion::reActivar($comunicacion_id_a);
    
    echo json_encode($resultado);    
}


function formulario_modificacion($comunicacion_asunto_id, 
                                 $comunicacion_casoEstado_id, 
                                 $comunicacion, 
                                 $comunicacion_id,
                                 $comunicacion_fechaIngreso,
                                 $caso_id,
                                 $sesion_usuario_id){
    
    Comunicacion::modificar($comunicacion_asunto_id, 
                             $comunicacion_casoEstado_id, 
                             $comunicacion, 
                             $comunicacion_id,
                             $comunicacion_fechaIngreso,
                             $caso_id,
                             $sesion_usuario_id);
}


function formulario_vistaDatosCaso($caso_id){
    
    $resultado = Caso::buscarDatosGeneralesCaso($caso_id);
    
    echo json_encode($resultado);
}


function formulario_lectura($comunicacion_id){
    
    $comunicacion = Comunicacion::buscarPorId($comunicacion_id);
    
    echo json_encode($comunicacion);
}

// Funciones auxiliares de formulario
// Funciones para llenar los Select

function listarAsunto_altaComunicacion(){

    $asuntos = Comunicacion::listarAsunto_altaComunicacion();

    echo json_encode($asuntos);   
}

function listarCasoEstado_altaComunicacion($caso_id, $permisos) {
    
    $validacion = Caso::validaciones_caso($caso_id);

    $casoEstados = Comunicacion::listarCasoEstado_altaComunicacion($caso_id, $permisos, $validacion);

    echo json_encode($casoEstados);  
}

function listarAsunto_modificarComunicacion($comunicacion_id){

    $asuntos = Comunicacion::listarAsunto_modificarComunicacion($comunicacion_id);

    echo json_encode($asuntos);   
}

function listarCasoEstado_modificarComunicacion($comunicacion_id){

    $casoEstados = Comunicacion::listarCasoEstado_modificarComunicacion($comunicacion_id);

    echo json_encode($casoEstados);   
}

function listarTiposAsunto_grillaComunicaciones(){

    $tiposAsunto = Comunicacion::listarTiposAsunto_grillaComunicaciones();

    echo json_encode($tiposAsunto);   
}

    
// Muestra la grilla con las comunicaciones
function grilla_listar($caso_id, $asunto_tipo_id, $permisos, $sesion_usuario_id){
    $comunicaciones = Comunicacion::listar($caso_id, $asunto_tipo_id);
    
    $comunicaciones_cantidad = Comunicacion::listar_cantidad($caso_id, $asunto_tipo_id);
    $comunicacion_numero = $comunicaciones_cantidad["comunicaciones_cantidad"] + 1 ;
    

    $grilla = "<div class='row'>";
    $grilla .= "<div id='grilla_abm' class='col-sm-12'>";
    $grilla .=  "<div class='card-box-form table-responsive'>";
    $grilla .=      "<table id='dt_comunicaciones' class='table table-hover m-0'>";
    $grilla .=          "<thead>";
    $grilla .=              "<tr>";
    $grilla .=                  "<th class='col-sm-1'></th>";
    $grilla .=                  "<th class='col-sm-3'>Datos</th>";
    $grilla .=                  "<th class='col-sm-9'>Comunicacion</th>";
    $grilla .=                  "<th class='col-sm-1'>Editar</th>";
    $grilla .=              "</tr>";
    $grilla .=          "</thead>";
    $grilla .=          "<tbody>";  
    foreach($comunicaciones as $comunicacion) {
            $comunicacion_numero            = $comunicacion_numero - 1;
            $comunicacion_id                = $comunicacion["comunicacion_id"];
            $fecha_ingreso                  = $comunicacion["comunicacion_fechaIngreso"];
            $comunicacion_fechaIngreso      = date("d-m-Y H:i:s", strtotime($comunicacion["comunicacion_fechaIngreso"]));
            $comunicacion_asunto_id         = $comunicacion["comunicacion_asunto_id"];
            $comunicacion_casoEstado_nombre = $comunicacion["casoEstado_nombre"];
            $comunicacion_historial_id      = $comunicacion["comunicacion_historial_id"];
            $comunicacion_usuario_id        = $comunicacion["comunicacion_usuario_id"];
            $operador                       = $comunicacion["usuario_nombre"] . ' ' . $comunicacion["usuario_apellido"];
            $comunicacionAsunto_color       = $comunicacion["comunicacionAsunto_color"];
            $comunicacion_asunto_nombre     = $comunicacion["comunicacionAsunto_nombre"];
            $comunicacion                   = $comunicacion["comunicacion"];        
            // Formatea la hora
            $datetime1 = new DateTime($fecha_ingreso);
            $datetime2 = new DateTime();
            $interval = date_diff($datetime1, $datetime2);
            $H = $interval->format("%H");
            $m = $interval->format("%i");
            
            // Valida si pasaron 30 minutos
            if($H < 1 && $m < 30) {
                $modifica_com = 1; 
            } else {
                $modifica_com = 0;
            }
            
            // Valida si la comunicacion tiene historial
            if($comunicacion_historial_id <> 0){
                $historial = 1;
            } else {
                $historial = 0;
            }
            
    $grilla .=              "<tr style='border-left: 4px solid $comunicacionAsunto_color;'>";        
    $grilla .=                  "<td>";
    $grilla .=                          $comunicacion_numero;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      "<strong>Fecha Ing&nbsp;&nbsp;</strong>";
    $grilla .=                          $comunicacion_fechaIngreso; 
    $grilla .=                      "<br/>";
    $grilla .=                      "<strong>Operador &nbsp;&nbsp;</strong>";
    $grilla .=                          $operador;
    $grilla .=                      "<br/>";
    $grilla .=                      "<strong>Asunto &nbsp;&nbsp;</strong>";
    $grilla .=                          $comunicacion_asunto_nombre;
    $grilla .=                      "<br/>";
    $grilla .=                      "<strong>Estado del Caso &nbsp;&nbsp;</strong>";
    $grilla .=                          $comunicacion_casoEstado_nombre;
    $grilla .=                      "<br/>";
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      "<p style='white-space:pre-wrap;'>" . $comunicacion . "</p>";
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    // Validacion de permisos
    $comunicaciones_modificar = array_search('comunicaciones_modificar', array_column($permisos, 'permiso_variable'));
    if (!empty($comunicaciones_modificar) || ($comunicaciones_modificar === 0)) {   
        // Valida (De cumplirse no deja modificar):
        // 1- Si pasaron 30 minutos
        // 2- Si el usuario que ingreso la comunicacion es el mismo que esta logueado
        // 3- Si el asunto es Caso Anulado o Caso Rehabilitado
        if ($modifica_com == 1 && $sesion_usuario_id === $comunicacion_usuario_id && ($comunicacion_asunto_id != 7 && $comunicacion_asunto_id != 8)) {
            $grilla .=                  "<a href='javascript:void(0)'> <i onclick='formulario_lectura($comunicacion_id)' class='fa fa-edit'></i></a>";
        }
    }    
    if ($historial == 1){
        $grilla .=                      "<a href='javascript:void(0)'> <i onclick='grilla_listar_historial($caso_id, $comunicacion_id)' class='fa fa-list'></i></a>";
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


// Muestra la grilla del historial de ediciones de una comunicacion
function grilla_listar_historial($caso_id, $comunicacion_id){
    $comunicaciones = Comunicacion::listar_historial($caso_id, $comunicacion_id);
    
    $grilla  = "<div class='row'>";
    $grilla .= "<div id='grilla_abm' class='col-sm-12'>";
    $grilla .=  "<div class='card-box-form table-responsive'>";
    $grilla .=      "<a href='javascript:void(0)'> <i onclick='btn_cerrar_logComunicaciones()' class='btn btn-inverse waves-effect waves-light col-sm-1'>Cerrar</i></a>";
    $grilla .=      "<table id='dt_comunicaciones' class='table table-hover m-0'>";
    $grilla .=          "<thead>";
    $grilla .=              "<tr>";
    $grilla .=                  "<th class='col-sm-3'>Datos</th>";
    $grilla .=                  "<th class='col-sm-9'>Comunicacion</th>";
    $grilla .=              "</tr>";
    $grilla .=          "</thead>";
    $grilla .=          "<tbody>";  
    foreach ($comunicaciones as $comunicacion) {
            $comunicacion_id                = $comunicacion["comunicacion_id"];
            $fecha_ingreso                  = date("d-m-Y H:i:s", strtotime($comunicacion["comunicacion_fechaIngreso"]));
            $fecha_modificacion             = date("d-m-Y H:i:s", strtotime($comunicacion["comunicacion_fechaModificacion"]));
            $comunicacion_casoEstado_nombre = $comunicacion["casoEstado_nombre"];
            $operador                       = $comunicacion["usuario_nombre"] . ' ' . $comunicacion["usuario_apellido"];
            $comunicacionAsunto_color       = $comunicacion["comunicacionAsunto_color"];
            $comunicacion_asunto_nombre     = $comunicacion["comunicacionAsunto_nombre"];
            $comunicacion                   = $comunicacion["comunicacion"];        
    $grilla .=              "<tr style='border-left: 4px solid $comunicacionAsunto_color;'>";        
    $grilla .=                  "<td>";
    $grilla .=                      "<strong>Fecha Ing &nbsp;&nbsp;</strong>";
    $grilla .=                          $fecha_ingreso; 
    $grilla .=                      "<br/>";
    $grilla .=                      "<strong>Fecha Mod &nbsp;&nbsp;</strong>";
    $grilla .=                          $fecha_modificacion; 
    $grilla .=                      "<br/>";
    $grilla .=                      "<strong>Operador &nbsp;&nbsp;</strong>";
    $grilla .=                          $operador;
    $grilla .=                      "<br/>";
    $grilla .=                      "<strong>Asunto &nbsp;&nbsp;</strong>";
    $grilla .=                          $comunicacion_asunto_nombre;
    $grilla .=                      "<br/>";
    $grilla .=                      "<strong>Estado del Caso &nbsp;&nbsp;</strong>";
    $grilla .=                          $comunicacion_casoEstado_nombre;
    $grilla .=                      "<br/>";
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      "<p style='white-space:pre-wrap;'>" . $comunicacion . "</p>";
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


// Grilla para generar la vista del Producto
function grilla_producto($caso_id){
    
    
    $coberturas = Product::buscar_por_caso($caso_id);     
    
    $grilla = "<div class='row'>";
    $grilla .= "<div id='grilla_abm' class='col-sm-12'>";
    $grilla .=  "<div class='card-box-form table-responsive'>";
    $grilla .=      "<table id='dt_producto' class='table table-hover m-0 table-responsive'>";
    $grilla .=          "<thead>";
    $grilla .=              "<tr>";
    $grilla .=                  "<th>Nombre</th>";
    $grilla .=                  "<th>Valor</th>";
    $grilla .=          "</thead>";
    $grilla .=          "<tbody>";  

    foreach ($coberturas as $cobertura){

        $nombre = $cobertura["coverage_name"];
        $valor = $cobertura["coverage_val"];

        $grilla .=              "<tr>";
        $grilla .=                  "<td>";
        $grilla .=                      $nombre;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $valor;
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