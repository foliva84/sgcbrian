<?php
require '../seguridad/seguridad.php';
include_once '../includes/herramientas.php';


// Busqueda
$caso_id = isset($_POST["caso_id"])?$_POST["caso_id"]:'';
$caso_numero_desde_b = isset($_POST["caso_numero_desde_b"])?$_POST["caso_numero_desde_b"]:'';
$caso_numero_hasta_b = isset($_POST["caso_numero_hasta_b"])?$_POST["caso_numero_hasta_b"]:'';
$reintegro_estado_id_b = isset($_POST["reintegro_estado_id_b"])?$_POST["reintegro_estado_id_b"]:'';
$caso_usuarioAsignado_id_b = isset($_POST["caso_usuarioAsignado_id_b"])?$_POST["caso_usuarioAsignado_id_b"]:'';


// Toma las variables del formulario de asignacion
$agenda_reintegro_id = isset($_POST["agenda_reintegro_id"])?$_POST["agenda_reintegro_id"]:'';
$agenda_caso_id = isset($_POST["agenda_caso_id"])?$_POST["agenda_caso_id"]:'';
$agenda_usuarioAsignado_a = isset($_POST["agenda_usuarioAsignado_a"])?$_POST["agenda_usuarioAsignado_a"]:'';

// Toma las variables del formulario de re-asignacion
$agenda_id_r = isset($_POST["agenda_id_r"])?$_POST["agenda_id_r"]:'';
$agenda_usuarioAsignado_r = isset($_POST["agenda_usuarioAsignado_r"])?$_POST["agenda_usuarioAsignado_r"]:'';

$reintegroAgenda_id = isset($_POST["reintegroAgenda_id"])?$_POST["reintegroAgenda_id"]:'';

// Definición del ABML (Alta, Baja, Modificacion, Lectura -- objeto_ABML)
$opcion = isset($_POST["opcion"])?$_POST["opcion"]:'';


switch($opcion){
    
    // Case para completar los select
    case 'listar_reintegroEstados':
        listar_reintegroEstados();
        break;
    
    case 'listar_usuarios':
        listar_usuarios();
        break;
    
    case 'listarUsuarios_reAsignacion':
        listarUsuarios_reAsignacion($reintegroAgenda_id);
        break;
    
    
    // Case de acciones en formularios
    case 'formulario_asignacion':
        formulario_asignacion($agenda_reintegro_id, $agenda_usuarioAsignado_a, $sesion_usuario_id);
        break;
    
    case 'formulario_reasignacion':
        formulario_reasignacion($agenda_id_r, $agenda_usuarioAsignado_r, $sesion_usuario_id);
        break;
    
    case 'formulario_modificar':
        formulario_modificar($reintegroAgenda_id);
        break;
    
    // Case grillas
    case 'grilla_listar':
        grilla_listar($caso_numero_desde_b,
                      $caso_numero_hasta_b,
                      $reintegro_estado_id_b,
                      $caso_usuarioAsignado_id_b,
                      $permisos, 
                      $sesion_usuario_id);
        break;
      
    case 'grilla_listar_contar':
        grilla_listar_contar($caso_numero_desde_b,
                             $caso_numero_hasta_b,
                             $reintegro_estado_id_b,
                             $caso_usuarioAsignado_id_b);
        break;
    
    default:
        echo("Está mal seleccionada la funcion");        
}


// Funciones para llenar los Select
    function listar_reintegroEstados(){

        $estado = EstadoReintegro::listar_reintegroEstados();

        echo json_encode($estado);
    }

    function listar_usuarios(){

        $usuarios = Usuario::listar_usuarios_reint();

        echo json_encode($usuarios);   
    }    
    
    function listarUsuarios_reAsignacion($reintegroAgenda_id){

        $usuarios = Usuario::listarUsuarios_reAsignacionR($reintegroAgenda_id);

        echo json_encode($usuarios);   
    }

    
// Funciones de acciones en formularios
    function formulario_asignacion($agenda_reintegro_id,
                                   $agenda_usuarioAsignado_a,
                                   $sesion_usuario_id) {

        Agenda::asignar_reintegro($agenda_reintegro_id,
                                  $agenda_usuarioAsignado_a,
                                  $sesion_usuario_id);
    }
    
    function formulario_reasignacion($agenda_id_r,
                                     $agenda_usuarioAsignado_r,
                                     $sesion_usuario_id) {

        Agenda::reasignar_reintegro($agenda_id_r, 
                                    $agenda_usuarioAsignado_r,
                                    $sesion_usuario_id);
    }

    function formulario_modificar($reintegroAgenda_id) {

        $agenda = Agenda::buscarRPorId($reintegroAgenda_id);
        
        echo json_encode($agenda);
    }
    
    
    // Funciones de Grilla 
    function grilla_listar($caso_numero_desde_b,
                           $caso_numero_hasta_b,
                           $reintegro_estado_id_b,
                           $caso_usuarioAsignado_id_b, 
                           $permisos, 
                           $sesion_usuario_id){

        $reintegros = Reintegro::listar_filtrado_agenda($caso_numero_desde_b,
                                                        $caso_numero_hasta_b,
                                                        $reintegro_estado_id_b,
                                                        $caso_usuarioAsignado_id_b);

        $grilla = "<div class='row'>";
        $grilla .= "<div id='grilla_abm' class='col-sm-12'>";
        $grilla .=  "<div class='card-box table-responsive'>";
        $grilla .=      "<h4 class='m-t-0 header-title'><b>Reintegros</b></h4>";
        $grilla .=      "<table class='table table-hover table-striped m-0 table-responsive'>";
        $grilla .=          "<thead>";
        $grilla .=              "<tr>";
        $grilla .=                  "<th>Caso</th>";
        $grilla .=                  "<th>Fecha de Ingreso</th>";
        $grilla .=                  "<th>Beneficiario</th>";
        $grilla .=                  "<th>Estado</th>";
        $grilla .=                  "<th>Usuario Asignado</th>";
        $grilla .=                  "<th>Acciones</th>";
        $grilla .=              "</tr>";
        $grilla .=          "</thead>";
        $grilla .=          "<tbody>";  
        foreach($reintegros as $reintegro){
                $reintegro_id           = $reintegro["reintegro_id"];
                $caso_id                = $reintegro["caso_id"];
                $caso_numero            = $reintegro["caso_numero"];
                $fecha_ingreso          = date('d-m-Y', strtotime($reintegro["reintegro_fechaIngresoSistema"]));
                $beneficiario_nombre    = $reintegro["caso_beneficiarioNombre"];
                $estado                 = $reintegro["reintegroEstado_nombre"];
                $asignado_nombre        = $reintegro["asignado_nombre"];
                $asignado_apellido      = $reintegro["asignado_apellido"];
                $reintegro_agenda_id    = $reintegro["reintegroAgenda_id"];
        $grilla .=              "<tr>";
        $grilla .=                  "<td>";
        $grilla .=                      "<a target='_blank' href='../caso/caso.php?vcase=" . $caso_id . "'>" . $caso_numero . "</a>";
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $fecha_ingreso;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $beneficiario_nombre;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $estado;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        if($reintegro_agenda_id != ''){
        $grilla .=                      "<span class='label label-success'>" . $asignado_nombre . ' ' . $asignado_apellido . "</span>";
        }else{
        $grilla .=                      "<span class='label label-danger'>Sin Asignar</span>";
        }
        $grilla .=                  "</td>";    
        $grilla .=                  "<td>";
        // Validacion de permisos
        $agenda_asignar_casos = array_search('agenda_asignar_casos', array_column($permisos, 'permiso_variable'));
        if (!empty($agenda_asignar_casos) || ($agenda_asignar_casos === 0)) { 
            if($reintegro_agenda_id != ''){
                $grilla .=                      "<a href='../caso/caso.php?vcase=" . $caso_id . "' target='_blank'> <i class='fa fa-search'></i></a>";
                $grilla .=                      "<a href='javascript:void(0)'> <i onclick='formulario_modificar($reintegro_agenda_id)' class='fa fa-history'></i></a>";
            }else{
                $grilla .=                      "<a href='../caso/caso.php?vcase=" . $caso_id . "' target='_blank'> <i class='fa fa-search'></i></a>";
                $grilla .=                      "<a href='javascript:void(0)'> <i onclick='formulario_asigna($reintegro_id,$caso_id,$caso_numero)' class='fa fa-mail-forward'></i></a>";
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
    
  
    function grilla_listar_contar($caso_numero_desde_b,
                                  $caso_numero_hasta_b,
                                  $reintegro_estado_id_b,
                                  $caso_usuarioAsignado_id_b){

        $reintegros = Reintegro::listar_filtrado_contar_agenda($caso_numero_desde_b,
                                                          $caso_numero_hasta_b,
                                                          $reintegro_estado_id_b,
                                                          $caso_usuarioAsignado_id_b);

        $cantidad = $reintegros['registros'];
        
        If ($cantidad > 100){
            $texto = "<i class='fa fa-exclamation-circle'></i>   Se han encontrado " . $cantidad . " registros. Se muestran sólo los primeros 600 resultados. Por favor refine su búsqueda.";
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