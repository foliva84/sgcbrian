<?php
require '../seguridad/seguridad.php';
include_once '../includes/herramientas.php';


// Toma las variables del formulario de alta
$caso_id_archivo = isset($_POST["caso_id_archivo"])?$_POST["caso_id_archivo"]:'';


// Definición del ABML (Alta, Baja, Modificacion, Lectura -- objeto_ABML)
$opcion = isset($_POST["opcion"])?$_POST["opcion"]:'';


switch($opcion){
    
    // Acciones de los formularios
 
    case 'grilla_listar':
        grilla_listar($caso_id_archivo, $permisos);
        break;
     
    
    default:
       echo("Está mal seleccionada la funcion");
        
        
}



// Funciones de Grilla

function grilla_listar($caso_id_archivo, $permisos){
    

    $archivos = Archivo::listar($caso_id_archivo);

    $grilla = "<div class='row'>";
    $grilla .= "<div id='grilla_abm' class='col-sm-12'>";
    $grilla .=  "<div class='card-box table-responsive'>";
    $grilla .=      "<h4 class='m-t-0 header-title'><b>Tabla de archivos</b></h4>";
    $grilla .=      "<table id='dt_archivos' class='table table-hover m-0 table-responsive'>";
    $grilla .=          "<thead>";
    $grilla .=              "<tr>";
    $grilla .=                  "<th>Icono</th>";
    $grilla .=                  "<th>Nombre</th>";
    $grilla .=                  "<th>Fecha</th>";
    $grilla .=                  "<th>Acciones</th>";
    $grilla .=              "</tr>";
    $grilla .=          "</thead>";
    $grilla .=          "<tbody>";  
    foreach($archivos as $archivo){
            $archivo_id = $archivo["archivo_id"];
            $archivo_nombre = $archivo["archivo_nombre"];
            $archivo_extension = $archivo["archivo_extension"];
            $archivo_encriptado  = "'" . $archivo["archivo_encriptado"].".".$archivo_extension . "'";
            $archivo_fecha = $archivo["archivo_fecha"];
    $grilla .=              "<tr>";
    $grilla .=                  "<td>";
    if($archivo_extension == 'pdf'){
        $grilla .=                  "<i class='fa fa-file-pdf-o'></i>";
    } else {
        $grilla .=                  "<i class='fa fa-file-image-o'></i>";
    }
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $archivo_nombre;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $archivo_fecha;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      '<a href="#"> <i onclick="javascript:vista_archivo(' . ($archivo_encriptado) . ')" class="fa fa-download" data-toggle="tooltip" data-placement="top" title="Ver archivo"></i></a>';
    // Validacion de permisos
    $archivos_borrar = array_search('archivos_borrar', array_column($permisos, 'permiso_variable'));
    if (!empty($archivos_borrar) || ($archivos_borrar === 0)) {
    $grilla .=                      '<a href="#"> <i onclick="javascript:modal_borra_archivo(' . ($archivo_id) . ')" class="fa fa-ban" data-toggle="tooltip" data-placement="top" title="Eliminar archivo"></i></a>';
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



