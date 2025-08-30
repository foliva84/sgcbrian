<?php
require '../seguridad/seguridad.php';
include_once '../includes/herramientas.php';


// Toma las variables del formulario de alta
$comunicacionR_n = isset($_POST["comunicacionR_n"])?$_POST["comunicacionR_n"]:'';
$reintegro_id_n = isset($_POST["reintegro_id_n"])?$_POST["reintegro_id_n"]:'';

// Toma las variables del formulario de modificación
$comunicacionR_id = isset($_POST["comunicacionR_id"])?$_POST["comunicacionR_id"]:'';
$comunicacionR = isset($_POST["comunicacionR"])?$_POST["comunicacionR"]:'';
$comunicacionR_fechaIngreso = isset($_POST["comunicacionR_fechaIngreso"])?$_POST["comunicacionR_fechaIngreso"]:'';
$reintegro_id = isset($_POST["reintegro_id"])?$_POST["reintegro_id"]:'';

// Toma el comunicacionR_id para deshabilitar
$comunicacionR_id_b = isset($_POST["comunicacionR_id_b"])?$_POST["comunicacionR_id_b"]:'';

// Toma el comunicacionR_id para volver a habilitar
$comunicacionR_id_a = isset($_POST["comunicacionR_id_a"])?$_POST["comunicacionR_id_a"]:'';


// Definición del ABML (Alta, Baja, Modificacion, Lectura -- objeto_ABML)
$opcion = isset($_POST["opcion"])?$_POST["opcion"]:'';


switch($opcion){
    
    // Acciones de los formularios
    case 'formulario_alta':
        formulario_alta($comunicacionR_n, 
                        $reintegro_id_n,
                        $sesion_usuario_id);
        break;
     
    case 'formulario_baja':
        formulario_baja($comunicacionR_id_b);
        break;
    
    case 'formulario_habilita':
        formulario_habilita($comunicacionR_id_a);
        break;
    
    case 'formulario_modificacion':
        formulario_modificacion($comunicacionR, 
                                $comunicacionR_id,
                                $comunicacionR_fechaIngreso,
                                $reintegro_id,
                                $sesion_usuario_id);
        break;
    
    case 'formulario_lectura':
        formulario_lectura($comunicacionR_id);
        break;
    
    case 'grilla_listar':
        grilla_listar($reintegro_id, $permisos, $sesion_usuario_id);
        break;
    
    case 'grilla_listar_historial':
        grilla_listar_historial($reintegro_id, $comunicacionR_id);
        break;   
    
       
    default:
       echo("Está mal seleccionada la funcion");
}


// Funciones de Formulario
function formulario_alta($comunicacionR_n, 
                         $reintegro_id_n,
                         $sesion_usuario_id){
    
    ComunicacionR::insertar($comunicacionR_n, 
                            $reintegro_id_n,
                            $sesion_usuario_id);            
}


function formulario_baja($comunicacionR_id_b){
    
    $resultado = ComunicacionR::borradoLogico($comunicacionR_id_b);
    
    echo json_encode($resultado);    
}


function formulario_habilita($comunicacionR_id_a){
    
    $resultado = ComunicacionR::reActivar($comunicacionR_id_a);
    
    echo json_encode($resultado);    
}


function formulario_modificacion($comunicacionR, 
                                 $comunicacionR_id,
                                 $comunicacionR_fechaIngreso,
                                 $reintegro_id,
                                 $sesion_usuario_id){
    
    ComunicacionR::modificar($comunicacionR, 
                             $comunicacionR_id,
                             $comunicacionR_fechaIngreso,
                             $reintegro_id,
                             $sesion_usuario_id);
}


function formulario_lectura($comunicacionR_id){
    
    $comunicacionR = ComunicacionR::buscarPorId($comunicacionR_id);
    
    echo json_encode($comunicacionR);
}

   
// Muestra la grilla con las comunicacionRes
function grilla_listar($reintegro_id, $permisos, $sesion_usuario_id){
    
    $comunicacionesR = ComunicacionR::listar($reintegro_id);
    
    $comunicacionesR_cantidad = ComunicacionR::listar_cantidad($reintegro_id);
    $comunicacionR_numero = $comunicacionesR_cantidad["comunicacionesR_cantidad"] + 1 ;
    

    $grilla = "<div class='row'>";
    $grilla .= "<div id='grilla_abm' class='col-sm-12'>";
    $grilla .=  "<div class='card-box-form table-responsive'>";
    $grilla .=      "<table id='dt_comunicacionRes' class='table table-hover m-0'>";
    $grilla .=          "<thead>";
    $grilla .=              "<tr>";
    $grilla .=                  "<th class='col-sm-1'></th>";
    $grilla .=                  "<th class='col-sm-3'>Datos</th>";
    $grilla .=                  "<th class='col-sm-7'>Comunicacion</th>";
    $grilla .=                  "<th class='col-sm-1'>Editar</th>";
    $grilla .=              "</tr>";
    $grilla .=          "</thead>";
    $grilla .=          "<tbody>";  
    foreach($comunicacionesR as $comunicacionR) {
            $comunicacionR_numero            = $comunicacionR_numero - 1;
            $comunicacionR_id                = $comunicacionR["comunicacionR_id"];
            $fecha_ingreso                   = $comunicacionR["comunicacionR_fechaIngreso"];
            $comunicacionR_fechaIngreso      = date("d-m-Y H:i:s", strtotime($comunicacionR["comunicacionR_fechaIngreso"]));
            $comunicacionR_historial_id      = $comunicacionR["comunicacionR_historial_id"];
            $comunicacionR_usuario_id        = $comunicacionR["comunicacionR_usuario_id"];
            $operador                        = $comunicacionR["usuario_nombre"] . ' ' . $comunicacionR["usuario_apellido"];
            //$comunicacionR_asunto_color      = $comunicacionR["comunicacionRAsunto_color"];
            //$comunicacionR_asunto_nombre     = $comunicacionR["comunicacionRAsunto_nombre"];
            $comunicacionR                   = $comunicacionR["comunicacionR"];        
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
            
            // Valida si la comunicacionR tiene historial
            if($comunicacionR_historial_id <> 0){
                $historial = 1;
            } else {
                $historial = 0;
            }
            
    $grilla .=              "<tr style='border-left: 4px'>";        
    $grilla .=                  "<td>";
    $grilla .=                          $comunicacionR_numero;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      "<strong>Fecha Ing&nbsp;&nbsp;</strong>";
    $grilla .=                          $comunicacionR_fechaIngreso; 
    $grilla .=                      "<br/>";
    $grilla .=                      "<strong>Operador &nbsp;&nbsp;</strong>";
    $grilla .=                          $operador;
    $grilla .=                      "<br/>";
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      "<p style='white-space:pre-wrap;'>" . $comunicacionR . "</p>";
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    // Validacion de permisos
    $comunicacionesR_modificar = array_search('comunicacionesR_modificar', array_column($permisos, 'permiso_variable'));
    if (!empty($comunicacionesR_modificar) || ($comunicacionesR_modificar === 0)) {   
        // Valida (De cumplirse no deja modificar):
        // 1- Si pasaron 30 minutos
        // 2- Si el usuario que ingreso la comunicacionR es el mismo que esta logueado
        // 3- Si el asunto es Caso Anulado o Caso Rehabilitado
        if ($modifica_com == 1 && $sesion_usuario_id === $comunicacionR_usuario_id) {
            $grilla .=              "<a href='javascript:void(0)'> <i onclick='formulario_lectura($comunicacionR_id)' class='fa fa-edit'></i></a>";
        }
    }    
    if ($historial == 1){
        $grilla .=                  "<a href='javascript:void(0)'> <i onclick='grilla_listar_historial($reintegro_id, $comunicacionR_id)' class='fa fa-list'></i></a>";
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


// Muestra la grilla del historial de ediciones de una comunicacionR
function grilla_listar_historial($reintegro_id, $comunicacionR_id){
    $comunicacionesR = ComunicacionR::listar_historial($reintegro_id, $comunicacionR_id);
    
    $grilla  = "<div class='row'>";
    $grilla .= "<div id='grilla_abm' class='col-sm-12'>";
    $grilla .=  "<div class='card-box-form table-responsive'>";
    $grilla .=      "<a href='javascript:void(0)'> <i onclick='btn_cerrar_logComunicacionesR()' class='btn btn-inverse waves-effect waves-light col-sm-1'>Cerrar</i></a>";
    $grilla .=      "<table id='dt_comunicaciones' class='table table-hover m-0'>";
    $grilla .=          "<thead>";
    $grilla .=              "<tr>";
    $grilla .=                  "<th class='col-sm-3'>Datos</th>";
    $grilla .=                  "<th class='col-sm-9'>Comunicacion</th>";
    $grilla .=              "</tr>";
    $grilla .=          "</thead>";
    $grilla .=          "<tbody>";  
    foreach ($comunicacionesR as $comunicacionR) {
            $comunicacionR_id                = $comunicacionR["comunicacionR_id"];
            $fecha_ingreso                   = date("d-m-Y H:i:s", strtotime($comunicacionR["comunicacionR_fechaIngreso"]));
            $fecha_modificacion              = date("d-m-Y H:i:s", strtotime($comunicacionR["comunicacionR_fechaModificacion"]));
            $operador                        = $comunicacionR["usuario_nombre"] . ' ' . $comunicacionR["usuario_apellido"];
            //$comunicacionR_asunto_color      = $comunicacionR["comunicacionRAsunto_color"];
            //$comunicacionR_asunto_nombre     = $comunicacionR["comunicacionRAsunto_nombre"];
            $comunicacionR                   = $comunicacionR["comunicacionR"];        
    $grilla .=              "<tr style='border-left: 4px solid'>";        
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
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      "<p style='white-space:pre-wrap;'>" . $comunicacionR . "</p>";
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
