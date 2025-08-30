<?php
include_once '../includes/herramientas.php';


// Toma de Variables
$reintegro_fechaPago = isset($_POST["reintegro_fechaPago"])?$_POST["reintegro_fechaPago"]:'';
$rim_seleccionados = isset($_POST["rim_seleccionados"])?$_POST["rim_seleccionados"]:'';

// Definición del ABML (Alta, Baja, Modificacion, Lectura -- objeto_ABML)
$opcion = isset($_POST["opcion"])?$_POST["opcion"]:'';


switch($opcion){
    
    case 'importeAprobadoARS_total':
        importeAprobadoARS_total($rim_seleccionados);
        break;
    
    // Case grillas
    case 'grilla_listar':
        grilla_listar();
        break;
    
    case 'grilla_listar_contar':
        grilla_listar_contar();
        break;
    
    
    case 'informar_pago':
        informar_pago($rim_seleccionados, $reintegro_fechaPago);
        break;
       
    default:
        echo("Está mal seleccionada la funcion");        
}


// Funcion para sumar importeAprobadoARS de Reintegros seleccionados
    function importeAprobadoARS_total($rim_seleccionados) {

        $importeAprobadoARS_total = Reporte::reintegro_pago_importeAprobadoARS_total($rim_seleccionados);

        echo json_encode($importeAprobadoARS_total);   
    }


// Funciones de Grilla
    function grilla_listar_contar() {
    
        $reintegros = Reporte::listar_reintegro_pago_contar();

        $cantidad = $reintegros['registros'];

        If ($cantidad > 50) {
            $texto = "<i class='fa fa-exclamation-circle'></i>   Se han encontrado " . $cantidad . " registros. Se muestran sólo los primeros 50 resultados. Por favor refine su búsqueda.";
        } else {
            $texto = "<p> Se han encontrado " . $cantidad . " registros.</p>";
        }
        
        $grilla = "<div class='row'>";
        $grilla .= "<div id='grilla_abm' class='col-sm-12'>";
        $grilla .=  "<div class='card-box table-responsive'>";
        $grilla .=   $texto;
        $grilla .=  "</div>";
        $grilla .=  "</div>";
        $grilla .="</div>";
        
        echo $grilla;
    }


    function grilla_listar() {
    
        $reintegros = Reporte::listar_reintegro_pago();

        $grilla = "<div class='row'>";
        $grilla .= "<div id='grilla_abm' class='col-sm-12'>";
        $grilla .=  "<div class='card-box table-responsive'>";
        $grilla .=      "<h4 class='m-t-0 header-title'><b>Resultado de la búsqueda</b></h4>";
        $grilla .=      "<table id='dt_reintegros' class='table table-hover m-0 table-responsive'>";
        $grilla .=          "<thead>";
        $grilla .=              "<tr>";
        $grilla .=                  "<th>Caso</th>";
        $grilla .=                  "<th>Fecha Ingreso Sistema</th>";
        $grilla .=                  "<th>Fecha Presentación</th>";
        $grilla .=                  "<th>Importe Total ARS</th>";
        $grilla .=                  "<th>Check</th>";
        $grilla .=              "</tr>";
        $grilla .=          "</thead>";
        $grilla .=          "<tbody>";

        foreach($reintegros as $reintegro) {

            $reintegro_id                   = $reintegro["reintegro_id"];
            $caso_numero                    = $reintegro["caso_numero"];                        
            $reintegro_fechaIngresoSistema  = $reintegro["reintegro_fechaIngresoSistema"];
            $reintegro_fechaPresentacion    = $reintegro["reintegro_fechaPresentacion"];
            $total                          = $reintegro["total"];

            $grilla .=              "<tr>";
            $grilla .=                  "<td>";
            $grilla .=                      $caso_numero;
            $grilla .=                  "</td>";
            $grilla .=                  "<td>";
            $grilla .=                      $reintegro_fechaPresentacion;
            $grilla .=                  "</td>";
            $grilla .=                  "<td>";
            $grilla .=                      $reintegro_fechaPresentacion;
            $grilla .=                  "</td>";
            $grilla .=                  "<td>";
            $grilla .=                      $total;
            $grilla .=                  "</td>";
            $grilla .=                  "<td>";
            $grilla .=                      "<input type='checkbox' onclick='javascript:seleccion_items();' name='seleccionados[]' class='checkboxServicio' value='" . $reintegro_id . "'/>";
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


// Función para informar el pago de reintegros
    function informar_pago($rim_seleccionados, $reintegro_fechaPago){
        
         Reporte::update_reintegros_abonados($rim_seleccionados, $reintegro_fechaPago);
        
    }