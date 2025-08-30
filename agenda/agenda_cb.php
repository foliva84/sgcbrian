<?php
require '../seguridad/seguridad.php';
include_once '../includes/herramientas.php';


// Busqueda
$caso_id = isset($_POST["caso_id"])?$_POST["caso_id"]:'';
$caso_numero_desde_b = isset($_POST["caso_numero_desde_b"])?$_POST["caso_numero_desde_b"]:'';
$caso_numero_hasta_b = isset($_POST["caso_numero_hasta_b"])?$_POST["caso_numero_hasta_b"]:'';
$caso_estado_id_b = isset($_POST["caso_estado_id_b"])?$_POST["caso_estado_id_b"]:'';
$caso_usuarioAsignado_id_b = isset($_POST["caso_usuarioAsignado_id_b"])?$_POST["caso_usuarioAsignado_id_b"]:'';


// Toma las variables del formulario de asignacion
$agenda_caso_id = isset($_POST["agenda_caso_id"])?$_POST["agenda_caso_id"]:'';
$agenda_usuarioAsignado_a = isset($_POST["agenda_usuarioAsignado_a"])?$_POST["agenda_usuarioAsignado_a"]:'';

// Toma las variables del formulario de re-asignacion
$agenda_id_r = isset($_POST["agenda_id_r"])?$_POST["agenda_id_r"]:'';
$agenda_usuarioAsignado_r = isset($_POST["agenda_usuarioAsignado_r"])?$_POST["agenda_usuarioAsignado_r"]:'';

$casoAgenda_id = isset($_POST["casoAgenda_id"])?$_POST["casoAgenda_id"]:'';

// Definición del ABML (Alta, Baja, Modificacion, Lectura -- objeto_ABML)
$opcion = isset($_POST["opcion"])?$_POST["opcion"]:'';


switch($opcion){
    
    // Case para completar los select
    case 'listar_casoEstados':
        listar_casoEstados();
        break;
    
    case 'listar_usuarios':
        listar_usuarios();
        break;
    
    case 'listarUsuarios_reAsignacion':
        listarUsuarios_reAsignacion($casoAgenda_id);
        break;
    
    
    // Case de acciones en formularios
    case 'formulario_asignacion':
        formulario_asignacion($agenda_caso_id, $agenda_usuarioAsignado_a, $sesion_usuario_id);
        break;
    
    case 'formulario_reasignacion':
        formulario_reasignacion($agenda_id_r, $agenda_usuarioAsignado_r, $sesion_usuario_id);
        break;
    
    case 'formulario_modificar':
        formulario_modificar($casoAgenda_id);
        break;
    
    // Case grillas
    case 'grilla_listar':
        grilla_listar($caso_numero_desde_b,
                      $caso_numero_hasta_b,
                      $caso_estado_id_b,
                      $caso_usuarioAsignado_id_b,
                      $permisos, 
                      $sesion_usuario_id);
        break;
      
    case 'grilla_listar_contar':
        grilla_listar_contar($caso_numero_desde_b,
                             $caso_numero_hasta_b,
                             $caso_estado_id_b,
                             $caso_usuarioAsignado_id_b);
        break;
    
    default:
        echo("Está mal seleccionada la funcion");        
}


// Funciones para llenar los Select
    function listar_casoEstados(){

        $casoEstados = EstadoCaso::listar_casoEstados();

        echo json_encode($casoEstados);   
    }

    function listar_usuarios(){

        $usuarios = Usuario::listar_usuarios_op();

        echo json_encode($usuarios);   
    }    
    
    function listarUsuarios_reAsignacion($casoAgenda_id){

        $usuarios = Usuario::listarUsuarios_reAsignacion($casoAgenda_id);

        echo json_encode($usuarios);   
    }

    
// Funciones de acciones en formularios
    function formulario_asignacion($agenda_caso_id,
                                   $agenda_usuarioAsignado_a,
                                   $sesion_usuario_id) {

        Agenda::asignar_caso($agenda_caso_id, 
                             $agenda_usuarioAsignado_a,
                             $sesion_usuario_id);
    }
    
    function formulario_reasignacion($agenda_id_r,
                                     $agenda_usuarioAsignado_r,
                                     $sesion_usuario_id) {

        Agenda::reasignar_caso($agenda_id_r, 
                               $agenda_usuarioAsignado_r,
                               $sesion_usuario_id);
    }

    function formulario_modificar($casoAgenda_id) {

        $agenda = Agenda::buscarPorId($casoAgenda_id);
        
        echo json_encode($agenda);
    }
    
    
    // Funciones de Grilla 
    function grilla_listar($caso_numero_desde_b,
                           $caso_numero_hasta_b,
                           $caso_estado_id_b,
                           $caso_usuarioAsignado_id_b, 
                           $permisos, 
                           $sesion_usuario_id){

        $casos = Caso::listar_filtrado_agenda($caso_numero_desde_b,
                                              $caso_numero_hasta_b,
                                              $caso_estado_id_b,
                                              $caso_usuarioAsignado_id_b);

        $grilla = "<div class='row'>";
        $grilla .= "<div id='grilla_abm' class='col-sm-12'>";
        $grilla .=  "<div class='card-box table-responsive'>";
        $grilla .=      "<h4 class='m-t-0 header-title'><b>Casos</b></h4>";
        $grilla .=      "<table id='dt_agenda' class='table table-hover table-striped m-0 table-responsive'>";
        $grilla .=          "<thead>";
        $grilla .=              "<tr>";
        $grilla .=                  "<th>Caso</th>";
        $grilla .=                  "<th>Beneficiario</th>";
        $grilla .=                  "<th>Estado</th>";
        $grilla .=                  "<th>Pais</th>";
        $grilla .=                  "<th>Tipo Asistencia</th>";
        $grilla .=                  "<th>Usuario Asignado</th>";
        $grilla .=                  "<th>Acciones</th>";
        $grilla .=              "</tr>";
        $grilla .=          "</thead>";
        $grilla .=          "<tbody>";  
        foreach($casos as $caso){
                $caso_id = $caso["caso_id"];
                $casoEstado_nombre = $caso["casoEstado_nombre"];
                $caso_numero = $caso["caso_numero"];
                $pais_nombreEspanol = $caso["pais_nombreEspanol"];    
                $tipoAsistencia_nombre = $caso["tipoAsistencia_nombre"];
                $usuarioAsignado_nombre = $caso["usuario_nombre"];
                $usuarioAsignado_apellido = $caso["usuario_apellido"];
                $casoAgenda_id = $caso["casoAgenda_id"];
                $beneficiarioNombre = $caso["caso_beneficiarioNombre"];
                
        $grilla .=              "<tr>";
        $grilla .=                  "<td>";
        $grilla .=                      "<a target='_blank' href='../caso/caso.php?vcase=" . $caso_id . "'>" . $caso_numero . "</a>";
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $beneficiarioNombre;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $casoEstado_nombre;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $pais_nombreEspanol;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $tipoAsistencia_nombre;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        if($casoAgenda_id != ''){
        $grilla .=                      "<span class='label label-success'>" . $usuarioAsignado_nombre . ' ' . $usuarioAsignado_apellido . "</span>";
        }else{
        $grilla .=                      "<span class='label label-danger'>Sin Asignar</span>";
        }
        $grilla .=                  "</td>";    
        $grilla .=                  "<td>";
        // Validacion de permisos
        $agenda_asignar_casos = array_search('agenda_asignar_casos', array_column($permisos, 'permiso_variable'));
        if (!empty($agenda_asignar_casos) || ($agenda_asignar_casos === 0)) { 
            if($casoAgenda_id != ''){
                $grilla .=                      "<a href='../caso/caso.php?vcase=" . $caso_id . "' target='_blank'> <i class='fa fa-search'></i></a>";
                $grilla .=                      "<a href='javascript:void(0)'> <i onclick='formulario_modificar($casoAgenda_id)' class='fa fa-history'></i></a>";
            }else{
                $grilla .=                      "<a href='../caso/caso.php?vcase=" . $caso_id . "' target='_blank'> <i class='fa fa-search'></i></a>";
                $grilla .=                      "<a href='javascript:void(0)'> <i onclick='formulario_asigna($caso_id,$caso_numero)' class='fa fa-mail-forward'></i></a>";
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
                                  $caso_estado_id_b,
                                  $caso_usuarioAsignado_id_b){

        $casos = Caso::listar_filtrado_contar_agenda($caso_numero_desde_b,
                                                     $caso_numero_hasta_b,
                                                     $caso_estado_id_b,
                                                     $caso_usuarioAsignado_id_b);

        $cantidad = $casos['registros'];
        
        If ($cantidad > 100){
            $texto = "<i class='fa fa-exclamation-circle'></i>   Se han encontrado " . $cantidad . " registros. Se muestran sólo los primeros 100 resultados. Por favor refine su búsqueda.";
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