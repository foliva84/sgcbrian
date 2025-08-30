<?php
include_once '../includes/herramientas.php';

$cliente_id = isset($_POST["cliente_id"]) ? $_POST["cliente_id"] : '';
$prestador_id = isset($_POST["prestador_id"]) ? $_POST["prestador_id"] : '';
$fci_seleccionados = isset($_POST["fci_seleccionados"]) ? $_POST["fci_seleccionados"] : '';
// Definición del ABML (Alta, Baja, Modificacion, Lectura -- objeto_ABML)
$opcion = isset($_POST["opcion"]) ? $_POST["opcion"] : '';


switch ($opcion) {

    case 'importeAprobadoUSD_total':
        importeAprobadoUSD_total($fci_seleccionados);
        break;

        // Case grillas
    case 'grilla_listar':
        grilla_listar(
            $cliente_id,
            $prestador_id
        );
        break;

    case 'grilla_listar_contar':
        grilla_listar_contar(
            $cliente_id,
            $prestador_id
        );
        break;


    case 'exportar_excel':
        exportar_excel(
            $cliente_id,
            $prestador_id,
            $fci_seleccionados
        );
        break;

    default:
        echo ("Está mal seleccionada la funcion");
}


// Funcion para sumar importesAprobadosUSD de items de factura seleccionados
function importeAprobadoUSD_total($fci_seleccionados)
{

    $importeAprobadoUSD_total = Reporte::importeAprobadoUSD_total($fci_seleccionados);

    echo json_encode($importeAprobadoUSD_total);
}

// Funciones de Grilla

function grilla_listar_contar(
    $cliente_id,
    $prestador_id
) {

    $facturas = Reporte::listar_fci_ordenAuditoria_contar(
        $cliente_id,
        $prestador_id
    );

    $cantidad = $facturas['registros'];

    if ($cantidad > 50) {
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
    $grilla .= "</div>";

    echo $grilla;
}


function grilla_listar(
    $cliente_id,
    $prestador_id
) {

    $facturas = Reporte::listar_fci_ordenAuditoria(
        $cliente_id,
        $prestador_id
    );

    $grilla = "<div class='row'>";
    $grilla .= "<div id='grilla_abm' class='col-sm-12'>";
    $grilla .=  "<div class='card-box table-responsive'>";
    $grilla .=      "<h4 class='m-t-0 header-title'><b>Resultado de la búsqueda</b></h4>";
    $grilla .=      "<table id='dt_facturas' class='table table-hover m-0 table-responsive'>";
    $grilla .=          "<thead>";
    $grilla .=              "<tr>";
    $grilla .=                  "<th>Factura</th>";
    $grilla .=                  "<th>Caso</th>";
    $grilla .=                  "<th>Voucher</th>";
    $grilla .=                  "<th>Beneficiario</th>";
    $grilla .=                  "<th>Documento Beneficiario</th>";
    $grilla .=                  "<th>Importe Aprobado Origen</th>";
    $grilla .=                  "<th>Importe Aprobado USD</th>";
    $grilla .=                  "<th>Check</th>";
    $grilla .=              "</tr>";
    $grilla .=          "</thead>";
    $grilla .=          "<tbody>";
    foreach ($facturas as $factura) {
        $fcItem_id                      = $factura["fcItem_id"];
        $factura_numero                 = $factura["factura_numero"];
        $caso_numero                    = $factura["caso_numero"];
        $caso_voucher                   = $factura["caso_numeroVoucher"];
        $caso_beneficiario              = $factura["caso_beneficiarioNombre"];
        $importeAprobadoOrigen          = number_format((float)$factura['fcItem_importeMedicoOrigen'] + (float)$factura['fcItem_importeFeeOrigen'], 2, ',', '');
        $importeAprobadoUSD             = $factura["fcItem_importeAprobadoUSD"];
        $beneficiarioDocumento             = $factura["beneficiarioDocumento"];
        $grilla .=              "<tr>";
        $grilla .=                  "<td>";
        $grilla .=                      $factura_numero;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $caso_numero;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $caso_voucher;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $caso_beneficiario;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $beneficiarioDocumento;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $importeAprobadoOrigen;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $importeAprobadoUSD;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      "<input type='checkbox' onclick='javascript:seleccion_items();' name='seleccionados[]' class='checkboxServicio' value='" . $fcItem_id . "'/>";
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

function exportar_excel(
    $cliente_id,
    $prestador_id,
    $fci_seleccionados
) {

    // Trae el array para poner en el Excel con los resultados
    $facturas = Reporte::listar_fci_ordenAuditoria_op(
        $cliente_id,
        $prestador_id,
        $fci_seleccionados
    );

    $totales = Reporte::totales_fci_ordenAuditoria_op(
        $cliente_id,
        $prestador_id,
        $fci_seleccionados
    );

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
    $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Factura')
        ->setCellValue('B1', 'Caso')
        ->setCellValue('C1', 'Beneficiario')
        ->setCellValue('D1', 'Documento Beneficiario')
        ->setCellValue('E1', 'Voucher')
        ->setCellValue('F1', 'Pais')
        ->setCellValue('G1', 'Prestador')
    ->setCellValue('H1', 'Fecha de Emision')
    ->setCellValue('I1', 'Fee')
    ->setCellValue('J1', 'Importe Aprobado Origen')
    ->setCellValue('K1', 'Importe Aprobado USD');


    //recorrer las columnas
    foreach (range('A', 'K') as $columnID) {
        //autodimensionar las columnas
        $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
    }

    // Se vuelca el array obtenido de la base de datos con los datos
    $objPHPExcel->getActiveSheet()->fromArray($facturas, NULL, 'A2');
    $fila = count($facturas) + 2;
    $colfila = 'J' . $fila;
    $colfilaUSD = 'K' . $fila;
    // Se vuelcan los totales obtenidos de la base de datos
    $objPHPExcel->getActiveSheet()->fromArray($totales, NULL, $colfila);

    // Se da estilo a los títulos
    $objPHPExcel->getActiveSheet()->getStyle('A1:K1')->getFont()->setBold(true);
    // Se da estilo a los totales
    $objPHPExcel->getActiveSheet()->getStyle($colfila)->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle($colfilaUSD)->getFont()->setBold(true);

    // Se renombra a la pestaña activa
    $objPHPExcel->getActiveSheet()->setTitle('Resultado reporte');

    // Se establece a la primer pestaña como la activa
    $objPHPExcel->setActiveSheetIndex(0);


    // Redirecciona el resultado a un archivo (Excel2007)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="ReporteSGC - Facturas con items auditoria.xlsx"');
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
