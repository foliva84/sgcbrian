<?php
include_once '../includes/herramientas.php';


// Toma de Variables
$rim_seleccionados = isset($_POST["rim_seleccionados"]) ? $_POST["rim_seleccionados"] : '';

// Definición del ABML (Alta, Baja, Modificacion, Lectura -- objeto_ABML)
$opcion = isset($_POST["opcion"]) ? $_POST["opcion"] : '';


switch ($opcion) {

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


    case 'exportar_excel':
        exportar_excel($rim_seleccionados);
        break;

    default:
        echo ("Está mal seleccionada la funcion");
}


// Funcion para sumar importeAprobadoARS de Reintegros seleccionados
function importeAprobadoARS_total($rim_seleccionados)
{

    $importeAprobadoARS_total = Reporte::reintegros_importeAprobadoARS_total($rim_seleccionados);

    echo json_encode($importeAprobadoARS_total);
}


// Funciones de Grilla
function grilla_listar_contar()
{

    $reintegros = Reporte::listar_reintegro_ordenPago_contar();

    $cantidad = $reintegros['registros'];

    if ($cantidad > 50) {
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
    $grilla .= "</div>";

    echo $grilla;
}


function grilla_listar()
{

    $reintegros = Reporte::listar_reintegro_ordenPago();

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
    $grilla .=                  "<th>Cliente</th>";
    $grilla .=                  "<th>Agencia</th>";
    $grilla .=                  "<th>Importe aprobado (USD)</th>";
    $grilla .=                  "<th>Check</th>";
    $grilla .=              "</tr>";
    $grilla .=          "</thead>";
    $grilla .=          "<tbody>";

    foreach ($reintegros as $reintegro) {

        $reintegro_id                   = $reintegro["reintegro_id"];
        $caso_numero                    = $reintegro["caso_numero"];
        $reintegro_fechaIngresoSistema  = $reintegro["reintegro_fechaIngresoSistema"];
        $reintegro_fechaPresentacion    = $reintegro["reintegro_fechaPresentacion"];
        $total                          = $reintegro["total"];
        $cbu_cuenta                     = $reintegro["cbu_cuenta"];
        $cliente                        = $reintegro["cliente"];
        $agencia                        = $reintegro["agencia"];
        $importe_aprobado_usd           = $reintegro["importe_aprobado_usd"];

        $grilla .=              "<tr>";
        $grilla .=                  "<td>";
        $grilla .=                      $caso_numero;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $reintegro_fechaIngresoSistema;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $reintegro_fechaPresentacion;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $total;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $cliente;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $agencia;
        $grilla .=                  "</td>";

        $grilla .=                  "<td>";
        $grilla .=                      $importe_aprobado_usd;
        $grilla .=                  "</td>";


        $grilla .=                  "<td>";
        if ($cbu_cuenta != 0) {
            $grilla .=                      "<input type='checkbox' onclick='javascript:seleccion_items();' name='seleccionados[]' class='checkboxServicio' value='" . $reintegro_id . "'/>";
        } else {
            $grilla .=                      "<span class='label label-danger'>Sin Datos Bancarios</span>";
        }
        $grilla .=                  "</td>";
        $grilla .=              "</tr>";
    }

    $grilla .=          "</tbody>";
    $grilla .=       "</table>";
    $grilla .=    "</div>";
    $grilla .=  "</div>";
    $grilla .= "</div>";

    echo $grilla;
}


// Función para exportar el Excel que debe venir directamente de un POST
function exportar_excel($rim_seleccionados)
{

    // Trae el array para poner en el Excel con los resultados
    $reintegros = Reporte::listar_reintegro_ordenPago_op($rim_seleccionados);

    $totales = Reporte::totales_reintegro_ordenPago_op($rim_seleccionados);

    Reporte::update_reintegros_pendientePago($rim_seleccionados);

    error_reporting(E_ALL);
    ini_set('display_errors', TRUE);
    ini_set('display_startup_errors', TRUE);
    // date_default_timezone_set('Europe/London');

    if (PHP_SAPI == 'cli')
        die('This example should only be run from a Web Browser');

    // Incluye la clase y librería de PHPExcel para poder generarlo.
    require_once dirname(__FILE__) . './../clases/PHPExcel.php';

    // Se instancia la clase
    $objPHPExcel = new PHPExcel();

    // Se establecen las propiedades del documento a exportar
    $objPHPExcel->getProperties()->setCreator('CORIS SGC')
        ->setLastModifiedBy('CORIS SGC')
        ->setTitle('Reporte')
        ->setSubject('Reporte Excel')
        ->setDescription('Descripcion Reporte generado por SGC')
        ->setKeywords('Keywords Reporte SGC')
        ->setCategory('Categoria Reporte SGC');

    // Se establecen cuales serán los titulos de las filas
    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Cuenta CBU')
        ->setCellValue('B1', 'Alias CBU')
        ->setCellValue('C1', 'Importe')
        ->setCellValue('D1', 'Denominacion')
        ->setCellValue('E1', 'Tipo de documento')
        ->setCellValue('F1', 'Nro de documento')
        ->setCellValue('G1', 'Tipo de referencia')
        ->setCellValue('H1', 'Referencia')
        ->setCellValue('I1', 'Tipo de aviso de transferencia al beneficiario')
        ->setCellValue('J1', 'Email destinatario')
        ->setCellValue('K1', 'Mensaje Email al destinatario')
        ->setCellValue('L1', 'Nombre de compañia')
        ->setCellValue('M1', 'Codigo de area')
        ->setCellValue('N1', 'Nro de telefono')
        ->setCellValue('O1', 'Cliente')
        ->setCellValue('P1', 'Agencia')
        ->setCellValue('Q1', 'Importe aprobado USD')
        ->setCellValue('R1', 'Pais')
        ->setCellValue('S1', 'Banco')
        ->setCellValue('T1', 'Digito verificación')
        ->setCellValue('U1', 'Email titular')
        ->setCellValue('V1', 'Tipo de cuenta')
        ->setCellValue('W1', 'Dirección titular')
        ->setCellValue('X1', 'Ciudad');

    //recorrer las columnas
    foreach (range('A', 'V') as $columnID) {
        //autodimensionar las columnas
        $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
    }

    // Se vuelca el array obtenido de la base de datos con los datos
    $objPHPExcel->getActiveSheet()->fromArray($reintegros, NULL, 'A2');
    $fila = count($reintegros) + 2;
    $colfilaUSD = 'C' . $fila;
    // Se vuelcan los totales obtenidos de la base de datos
    $objPHPExcel->getActiveSheet()->fromArray($totales, NULL, $colfilaUSD);

    // Se da estilo a los títulos
    $objPHPExcel->getActiveSheet()->getStyle('A1:X1')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('A1:X1')->getFill()->getStartColor()->setRGB('FFFF0000');
    // Se da estilo a los totales
    $objPHPExcel->getActiveSheet()->getStyle($colfilaUSD)->getFont()->setBold(true);

    // Se renombra a la pestaña activa
    $objPHPExcel->getActiveSheet()->setTitle('Resultado reporte');

    // Se establece a la primer pestaña como la activa
    $objPHPExcel->setActiveSheetIndex(0);


    // Redirecciona el resultado a un archivo (Excel2007)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="ReporteSGC - Reintegros_ordenPago.xlsx"');
    header('Cache-Control: max-age=0');
    // Si se usa IE 9
    header('Cache-Control: max-age=1');

    // Si se usa SSL
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Un día en el pasado
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header('Pragma: public'); // HTTP/1.0

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
    exit;
}
