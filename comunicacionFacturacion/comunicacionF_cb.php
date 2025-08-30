<?php
require '../seguridad/seguridad.php';
include_once '../includes/herramientas.php';


// Toma las variables del formulario de alta
$comunicacionF_n = isset($_POST["comunicacionF_n"])?$_POST["comunicacionF_n"]:'';
$factura_id_n = isset($_POST["factura_id_n"])?$_POST["factura_id_n"]:'';

// Toma las variables del formulario de modificación
$comunicacionF_id = isset($_POST["comunicacionF_id"])?$_POST["comunicacionF_id"]:'';
$comunicacionF = isset($_POST["comunicacionF"])?$_POST["comunicacionF"]:'';
$comunicacionF_fechaIngreso = isset($_POST["comunicacionF_fechaIngreso"])?$_POST["comunicacionF_fechaIngreso"]:'';
$factura_id = isset($_POST["factura_id"])?$_POST["factura_id"]:'';

// Toma el comunicacionF_id para deshabilitar
$comunicacionF_id_b = isset($_POST["comunicacionF_id_b"])?$_POST["comunicacionF_id_b"]:'';

// Toma el comunicacionF_id para volver a habilitar
$comunicacionF_id_a = isset($_POST["comunicacionF_id_a"])?$_POST["comunicacionF_id_a"]:'';


// Definición del ABML (Alta, Baja, Modificacion, Lectura -- objeto_ABML)
$opcion = isset($_POST["opcion"])?$_POST["opcion"]:'';


switch($opcion){
    
    // Acciones de los formularios
    case 'formulario_alta':
        formulario_alta($comunicacionF_n, 
                        $factura_id_n,
                        $sesion_usuario_id);
        break;
     
    case 'formulario_baja':
        formulario_baja($comunicacionF_id_b);
        break;
    
    case 'formulario_habilita':
        formulario_habilita($comunicacionF_id_a);
        break;
    
    case 'formulario_modificacion':
        formulario_modificacion($comunicacionF, 
                                $comunicacionF_id,
                                $comunicacionF_fechaIngreso,
                                $factura_id,
                                $sesion_usuario_id);
        break;
    
    case 'formulario_lectura':
        formulario_lectura($comunicacionF_id);
        break;
    
    case 'grilla_listar':
        grilla_listar($factura_id, $permisos, $sesion_usuario_id);
        break;
    
    case 'grilla_listar_historial':
        grilla_listar_historial($factura_id, $comunicacionF_id);
        break;   
    
       
    default:
       echo("Está mal seleccionada la funcion");
}


// Funciones de Formulario
function formulario_alta($comunicacionF_n, 
                         $factura_id_n,
                         $sesion_usuario_id){
    
    ComunicacionF::insertar($comunicacionF_n, 
                            $factura_id_n,
                            $sesion_usuario_id);            
}


function formulario_baja($comunicacionF_id_b){
    
    $resultado = ComunicacionF::borradoLogico($comunicacionF_id_b);
    
    echo json_encode($resultado);    
}


function formulario_habilita($comunicacionF_id_a){
    
    $resultado = ComunicacionF::reActivar($comunicacionF_id_a);
    
    echo json_encode($resultado);    
}


function formulario_modificacion($comunicacionF, 
                                 $comunicacionF_id,
                                 $comunicacionF_fechaIngreso,
                                 $factura_id,
                                 $sesion_usuario_id){
    
    ComunicacionF::modificar($comunicacionF, 
                             $comunicacionF_id,
                             $comunicacionF_fechaIngreso,
                             $factura_id,
                             $sesion_usuario_id);
}


function formulario_lectura($comunicacionF_id){
    
    $comunicacionF = ComunicacionF::buscarPorId($comunicacionF_id);
    
    echo json_encode($comunicacionF);
}

   
// Muestra la grilla con las comunicacionFes
function grilla_listar($factura_id, $permisos, $sesion_usuario_id){
    
    $comunicacionesF = ComunicacionF::listar($factura_id);
    
    $comunicacionesF_cantidad = ComunicacionF::listar_cantidad($factura_id);
    $comunicacionF_numero = $comunicacionesF_cantidad["comunicacionesF_cantidad"] + 1 ;
    

    $grilla = "<div class='row'>";
    $grilla .= "<div id='grilla_abm' class='col-sm-12'>";
    $grilla .=  "<div class='card-box-form table-responsive'>";
    $grilla .=      "<table id='dt_comunicaciones' class='table table-hover m-0'>";
    $grilla .=          "<thead>";
    $grilla .=              "<tr>";
    $grilla .=                  "<th class='col-sm-1'></th>";
    $grilla .=                  "<th class='col-sm-3'>Datos</th>";
    $grilla .=                  "<th class='col-sm-7'>Comunicacion</th>";
    $grilla .=                  "<th class='col-sm-1'>Editar</th>";
    $grilla .=              "</tr>";
    $grilla .=          "</thead>";
    $grilla .=          "<tbody>";  
    foreach($comunicacionesF as $comunicacionF) {
            $comunicacionF_numero            = $comunicacionF_numero - 1;
            $comunicacionF_id                = $comunicacionF["comunicacionF_id"];
            $fecha_ingreso                   = $comunicacionF["comunicacionF_fechaIngreso"];
            $comunicacionF_fechaIngreso      = date("d-m-Y H:i:s", strtotime($comunicacionF["comunicacionF_fechaIngreso"]));
            $comunicacionF_historial_id      = $comunicacionF["comunicacionF_historial_id"];
            $comunicacionF_usuario_id        = $comunicacionF["comunicacionF_usuario_id"];
            $operador                        = $comunicacionF["usuario_nombre"] . ' ' . $comunicacionF["usuario_apellido"];
            //$comunicacionF_asunto_color      = $comunicacionF["comunicacionFAsunto_color"];
            //$comunicacionF_asunto_nombre     = $comunicacionF["comunicacionFAsunto_nombre"];
            $comunicacionF                   = $comunicacionF["comunicacionF"];        
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
            
            // Valida si la comunicacionF tiene historial
            if($comunicacionF_historial_id <> 0){
                $historial = 1;
            } else {
                $historial = 0;
            }
            
    $grilla .=              "<tr style='border-left: 4px'>";        
    $grilla .=                  "<td>";
    $grilla .=                          $comunicacionF_numero;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      "<strong>Fecha Ing&nbsp;&nbsp;</strong>";
    $grilla .=                          $comunicacionF_fechaIngreso; 
    $grilla .=                      "<br/>";
    $grilla .=                      "<strong>Operador &nbsp;&nbsp;</strong>";
    $grilla .=                          $operador;
    $grilla .=                      "<br/>";
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      "<p style='white-space:pre-wrap;'>" . $comunicacionF . "</p>";
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    // Validacion de permisos
    $comunicacionesF_modificar = array_search('comunicacionesF_modificar', array_column($permisos, 'permiso_variable'));
    if (!empty($comunicacionesF_modificar) || ($comunicacionesF_modificar === 0)) {   
        // Valida (De cumplirse no deja modificar):
        // 1- Si pasaron 30 minutos
        // 2- Si el usuario que ingreso la comunicacionF es el mismo que esta logueado
        // 3- Si el asunto es Caso Anulado o Caso Rehabilitado
        if ($modifica_com == 1 && $sesion_usuario_id === $comunicacionF_usuario_id) {
            $grilla .=              "<a href='javascript:void(0)'> <i onclick='formulario_lectura($comunicacionF_id)' class='fa fa-edit'></i></a>";
        }
    }    
    if ($historial == 1){
        $grilla .=                  "<a href='javascript:void(0)'> <i onclick='grilla_listar_historial($factura_id, $comunicacionF_id)' class='fa fa-list'></i></a>";
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


// Muestra la grilla del historial de ediciones de una comunicacionF
function grilla_listar_historial($factura_id, $comunicacionF_id){
    $comunicacionesF = ComunicacionF::listar_historial($factura_id, $comunicacionF_id);
    
    $grilla  = "<div class='row'>";
    $grilla .= "<div id='grilla_abm' class='col-sm-12'>";
    $grilla .=  "<div class='card-box-form table-responsive'>";
    $grilla .=      "<a href='javascript:void(0)'> <i onclick='btn_cerrar_logComunicacionesF()' class='btn btn-inverse waves-effect waves-light col-sm-1'>Cerrar</i></a>";
    $grilla .=      "<table id='dt_comunicaciones' class='table table-hover m-0'>";
    $grilla .=          "<thead>";
    $grilla .=              "<tr>";
    $grilla .=                  "<th class='col-sm-3'>Datos</th>";
    $grilla .=                  "<th class='col-sm-9'>Comunicacion</th>";
    $grilla .=              "</tr>";
    $grilla .=          "</thead>";
    $grilla .=          "<tbody>";  
    foreach ($comunicacionesF as $comunicacionF) {
            $comunicacionF_id                = $comunicacionF["comunicacionF_id"];
            $fecha_ingreso                   = date("d-m-Y H:i:s", strtotime($comunicacionF["comunicacionF_fechaIngreso"]));
            $fecha_modificacion              = date("d-m-Y H:i:s", strtotime($comunicacionF["comunicacionF_fechaModificacion"]));
            $operador                        = $comunicacionF["usuario_nombre"] . ' ' . $comunicacionF["usuario_apellido"];
            //$comunicacionF_asunto_color      = $comunicacionF["comunicacionFAsunto_color"];
            //$comunicacionF_asunto_nombre     = $comunicacionF["comunicacionFAsunto_nombre"];
            $comunicacionF                   = $comunicacionF["comunicacionF"];        
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
    $grilla .=                      "<p style='white-space:pre-wrap;'>" . $comunicacionF . "</p>";
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
