<?php
include_once '../includes/herramientas.php';

// Variables de Reintegros
$reint_fechaPago_desde  = isset($_POST["reint_fechaPago_desde"])?$_POST["reint_fechaPago_desde"]:'';
$reint_fechaPago_hasta  = isset($_POST["reint_fechaPago_hasta"])?$_POST["reint_fechaPago_hasta"]:'';

// Variables de FCI
$fci_fechaPago_desde    = isset($_POST["fci_fechaPago_desde"])?$_POST["fci_fechaPago_desde"]:'';
$fci_fechaPago_hasta    = isset($_POST["fci_fechaPago_hasta"])?$_POST["fci_fechaPago_hasta"]:'';
$fci_seleccionados      = isset($_POST["fci_seleccionados"])?$_POST["fci_seleccionados"]:'';

// Variables del Lote
$lote_numero            = isset($_POST["lote_numero"])?$_POST["lote_numero"]:'';

// Variables para exportar el excel
$exp_acc_id             = isset($_POST["exp_acc_id"])?$_POST["exp_acc_id"]:'';

// Definición del ABML (Alta, Baja, Modificacion, Lectura -- objeto_ABML)
$opcion = isset($_POST["opcion"])?$_POST["opcion"]:'';



// Switch
switch($opcion){
       
    // Case Acciones
    case 'guardar_acc_reint':
        guardar_acc_reint($reint_fechaPago_desde, $reint_fechaPago_hasta);
        break;
    
    case 'guardar_acc_fci':
        guardar_acc_fci($fci_seleccionados);
        break;

    case 'procesar_accrued':
        procesar_accrued($lote_numero);
        break;

    case 'informar_accrued':
        informar_accrued($lote_numero);
        break;
    
    case 'exportar_accrued':
        exportar_accrued($exp_acc_id);
        break;

    // Case Grillas
    case 'listar_reintegros':
        listar_reintegros($reint_fechaPago_desde, $reint_fechaPago_hasta);
        break;

    case 'listar_borrador_reintegros':
        listar_borrador_reintegros();
        break;       
        
    case 'reint_procesado':
        reint_procesado($lote_numero);
        break;

    case 'listar_fci':
        listar_fci($fci_fechaPago_desde, $fci_fechaPago_hasta);
        break;

    case 'listar_borrador_fci':
        listar_borrador_fci();
        break;

    case 'fci_procesado':
        fci_procesado($lote_numero);
        break;

    case 'listar_accrued_generados':
        listar_accrued_generados();
        break;

    // Default
    default:
        echo("Está mal seleccionada la funcion");        
}



// Acciones
function guardar_acc_reint($reint_fechaPago_desde, $reint_fechaPago_hasta) {
    
    $resultado = Accrued::guardar_acc_reint($reint_fechaPago_desde, $reint_fechaPago_hasta);

    echo json_encode($resultado);
}

function guardar_acc_fci($fci_seleccionados) {

    $resultado = Accrued::guardar_acc_fci($fci_seleccionados);

    echo json_encode($resultado);
}

function procesar_accrued() {

    // Valida si existen borradores de Reintegros y FCI
    if (Accrued::existe_lote_borrador(1) == true && Accrued::existe_lote_borrador(2) == true) {
        
        // Si existen lotes en estado borrador, procesa el Accrued
        Accrued::procesar();
        echo json_encode(0);

    } else if (Accrued::existe_lote_borrador(1) == true && Accrued::existe_lote_borrador(2) != true) { // Falta FCI
        echo json_encode(1);
    } else if (Accrued::existe_lote_borrador(1) != true && Accrued::existe_lote_borrador(2) == true) { // Falta Reintegros
        echo json_encode(2);
    } else if (Accrued::existe_lote_borrador(1) != true && Accrued::existe_lote_borrador(2) != true) { // Faltan Ambos
        echo json_encode(3);
    }

}

function informar_accrued($lote_numero) {

    // Marca como Informado el Accrued
    $resultado = Accrued::informar($lote_numero);
    
    echo json_encode($resultado);

}

function exportar_accrued($exp_acc_id) {

    // Trae el array para poner en el Excel con los resultados
    $registros = Accrued::generar_excel($exp_acc_id);

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
    $objPHPExcel->getActiveSheet()  ->setCellValue('A1', 'Platform_code')
                                    ->setCellValue('B1', 'Case number')
                                    ->setCellValue('C1', 'Claim_Occurrence_date')
                                    ->setCellValue('D1', 'REI_Claim_Settled_amount')
                                    ->setCellValue('E1', 'FCI_Claim_Settled_amount')
                                    ->setCellValue('F1', 'Claim_Country_name')
                                    ->setCellValue('G1', 'Claim_Policy_code')
                                    ->setCellValue('H1', 'Settlement_Nature_assistance_name')
                                    ->setCellValue('I1', 'Policy_Creation_date')
                                    ->setCellValue('J1', 'Beneficiary Name')
                                    ->setCellValue('K1', 'Product Name');
    
    // Recorre las columnas
    foreach (range('A', 'K') as $columnID) {
        //autodimensionar las columnas
        $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
    }
    
    // Se vuelca el array obtenido de la base de datos con los datos
    $objPHPExcel->getActiveSheet()->fromArray($registros, NULL, 'A2');

    // Se da estilo a los títulos
    $objPHPExcel->getActiveSheet()->getStyle('A1:K1')->applyFromArray(
        array(
            'font'  => array(
                'bold' => true
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        )
    );
    
    // Se renombra a la pestaña activa
    $objPHPExcel->getActiveSheet()->setTitle('Resultado reporte');

    // Se establece a la primer pestaña como la activa
    $objPHPExcel->setActiveSheetIndex(0);
    
    ob_clean();

    // Redirecciona el resultado a un archivo (Excel2007)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="CORIS - Accrued Lote ' . $exp_acc_id . '.xlsx"');
    header('Cache-Control: max-age=0');
    // Si se usa IE 9
    header('Cache-Control: max-age=1');

    // Si se usa SSL
    header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Un día en el pasado
    header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); 
    header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header ('Pragma: public'); // HTTP/1.0

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
    exit;

}



// Grillas
function listar_reintegros($reint_fechaPago_desde, $reint_fechaPago_hasta) {

    $reintegros = Accrued::listar_reintegros($reint_fechaPago_desde, $reint_fechaPago_hasta);

    if ($reintegros != NULL) {
        $grilla = "<div class='row'>";
        $grilla .= "<div id='grilla_abm' class='col-sm-12'>";
        $grilla .=  "<div class='card-box-form table-responsive'>";
        $grilla .=      "<h4 class='m-t-0 header-title'><b>Reintegros Pagados</b></h4>";
        $grilla .=      "<table id='dt_listado_reintegros' class='table table-hover m-0 table-responsive'>";
        $grilla .=          "<thead>";
        $grilla .=              "<tr>";
        $grilla .=                  "<th>Caso</th>";
        $grilla .=                  "<th>País Siniestro</th>";
        $grilla .=                  "<th>Fecha Pago</th>";
        $grilla .=                  "<th>Forma de Pago</th>";
        $grilla .=                  "<th>Importe Aprobado USD</th>";
        $grilla .=              "</tr>";
        $grilla .=          "</thead>";
        $grilla .=          "<tbody>";  
        foreach($reintegros as $reintegro) {
                $caso_numero            = $reintegro["caso_numero"];
                $caso_pais              = $reintegro["pais_nombreEspanol"];
                $reintegro_fechaPago    = date('d-m-Y', strtotime($reintegro["reintegro_fechaPago"]));
                $reintegro_formaPago    = $reintegro["formaPago_nombre"];
                $aprobado_usd           = $reintegro["aprobado_usd"];
        $grilla .=              "<tr>";
        $grilla .=                  "<td>";
        $grilla .=                      $caso_numero;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $caso_pais;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $reintegro_fechaPago;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $reintegro_formaPago;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $aprobado_usd;
        $grilla .=                  "</td>";
        $grilla .=              "</tr>";
        }
        $grilla .=          "</tbody>";
        $grilla .=       "</table>";
        $grilla .=    "</div>";
        
        $grilla .=       "<div class='col-md-12'>";
        $grilla .=          "<label>&nbsp;</label>";
        $grilla .=          "<div class='form-group text-right'>";
        $grilla .=              "<button class='btn btn-success waves-effect waves-light text-right' type='button' onclick='guardar_acc_reint()'>Crear Borrador Reintegros</button>";
        $grilla .=          "</div>";
        $grilla .=       "</div>";

        $grilla .=  "</div>";
        $grilla .="</div>";   
    } else {
        $grilla = "<div class='col-sm-12'>";
        $grilla .=  "<div class='card-box-form text-center'>";
        $grilla .=      "<div class='row'>";
        $grilla .=          "Sin resultados";
        $grilla .=      "</div>";
        $grilla .=  "</div>";
        $grilla .= "</div>";
    }
    echo $grilla;
}

function listar_borrador_reintegros() {

    $reintegros = Accrued::listar_borrador_reintegros();

    if (Accrued::existe_lote_borrador(1) == true) {
        $grilla = "<div class='row'>";
        $grilla .= "<div id='grilla_abm' class='col-sm-12'>";
        $grilla .=  "<div class='card-box-form table-responsive'>";
        $grilla .=      "<h4 class='m-t-0 header-title'><b>Borrador de Reintegros</b></h4>";
        $grilla .=      "<table id='dt_listado_reintegros_borrador' class='table table-hover m-0 table-responsive'>";
        $grilla .=          "<thead>";
        $grilla .=              "<tr>";
        $grilla .=                  "<th>Caso</th>";
        $grilla .=                  "<th>País Siniestro</th>";
        $grilla .=                  "<th>Fecha Pago</th>";
        $grilla .=                  "<th>Forma de Pago</th>";
        $grilla .=                  "<th>Importe Aprobado USD</th>";
        $grilla .=              "</tr>";
        $grilla .=          "</thead>";
        $grilla .=          "<tbody>";  
        foreach($reintegros as $reintegro) {
            $caso_numero            = $reintegro["caso_numero"];
            $caso_pais              = $reintegro["pais_nombreEspanol"];
            $reintegro_fechaPago    = date('d-m-Y', strtotime($reintegro["reintegro_fechaPago"]));
            $reintegro_formaPago    = $reintegro["formaPago_nombre"];
            $aprobado_usd           = $reintegro["aprobado_usd"];
        $grilla .=              "<tr>";
        $grilla .=                  "<td>";
        $grilla .=                      $caso_numero;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $caso_pais;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $reintegro_fechaPago;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $reintegro_formaPago;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $aprobado_usd;
        $grilla .=                  "</td>";
        $grilla .=              "</tr>";
        }
        $grilla .=          "</tbody>";
        $grilla .=       "</table>";
        $grilla .=    "</div>";
        $grilla .=  "</div>";
        $grilla .="</div>";   
    } else {
        $grilla = "<div class='col-sm-12'>";
        $grilla .=  "<div class='card-box-form text-center'>";
        $grilla .=      "<div class='row'>";
        $grilla .=          "Aún no se creó un Lote Borrador para Reintegros";
        $grilla .=      "</div>";
        $grilla .=  "</div>";
        $grilla .= "</div>";        
    }

    echo $grilla;
}

function reint_procesado($lote_numero) {

    $reintegros = Accrued::reint_procesado($lote_numero);

    $grilla = "<div class='row'>";
    $grilla .= "<div id='grilla_abm' class='col-sm-12'>";
    $grilla .=  "<div class='card-box-form table-responsive'>";
    $grilla .=      "<h4 class='m-t-0 header-title'><b>Lote " . $lote_numero . " > Reintegros</b></h4>";
    $grilla .=      "<table id='dt_reint_procesado' class='table table-hover m-0 table-responsive'>";
    $grilla .=          "<thead>";
    $grilla .=              "<tr>";
    $grilla .=                  "<th>Caso</th>";
    $grilla .=                  "<th>País Siniestro</th>";
    $grilla .=                  "<th>Fecha Pago</th>";
    $grilla .=                  "<th>Forma de Pago</th>";
    $grilla .=                  "<th>Importe Aprobado USD</th>";
    $grilla .=              "</tr>";
    $grilla .=          "</thead>";
    $grilla .=          "<tbody>";  
    foreach($reintegros as $reintegro) {
        $caso_numero            = $reintegro["caso_numero"];
        $caso_pais              = $reintegro["pais_nombreEspanol"];
        $reintegro_fechaPago    = date('d-m-Y', strtotime($reintegro["reintegro_fechaPago"]));
        $reintegro_formaPago    = $reintegro["formaPago_nombre"];
        $aprobado_usd           = $reintegro["aprobado_usd"];
    $grilla .=              "<tr>";
    $grilla .=                  "<td>";
    $grilla .=                      $caso_numero;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $caso_pais;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $reintegro_fechaPago;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $reintegro_formaPago;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $aprobado_usd;
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

function listar_fci($fci_fechaPago_desde, $fci_fechaPago_hasta) {

    $fcis = Accrued::listar_fci($fci_fechaPago_desde, $fci_fechaPago_hasta);
    $existe_lote = Accrued::existe_lote_borrador(2);

    if ($existe_lote != true) {
        $grilla = "<div class='row'>";
        $grilla .= "<div id='grilla_abm' class='col-sm-12'>";
        $grilla .=  "<div class='card-box-form table-responsive'>";
        $grilla .=      "<h4 class='m-t-0 header-title'><b>Items de Factura</b></h4>";
        $grilla .=      "<table id='dt_listado_fci' class='table table-hover m-0 table-responsive'>";
        $grilla .=          "<thead>";
        $grilla .=              "<tr>";
        $grilla .=                  "<th>Caso</th>";
        $grilla .=                  "<th>Factura</th>";
        $grilla .=                  "<th>Prestador</th>";
        $grilla .=                  "<th>Estado</th>";
        $grilla .=                  "<th>Importe Aprobado USD</th>";
        $grilla .=                  "<th>Check</th>";
        $grilla .=              "</tr>";
        $grilla .=          "</thead>";
        $grilla .=          "<tbody>";  
        foreach($fcis as $fci) {
                $fci_id             = $fci["fcItem_id"];    
                $caso_numero        = $fci["caso_numero"];
                $factura_numero        = $fci["factura_numero"];
                $prestador_nombre   = $fci["prestador_nombre"];
                $estado             = $fci["estado"];
                $aprobado_usd       = $fci["aprobado_usd"];
                // Faltan otros datos
        $grilla .=              "<tr>";
        $grilla .=                  "<td>";
        $grilla .=                      $caso_numero;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $factura_numero;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $prestador_nombre;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $estado;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $aprobado_usd;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      "<input type='checkbox' onclick='javascript:seleccion_fci();' name='seleccionados[]' class='checkboxFCI' value='" . $fci_id . "'/>";
        $grilla .=                  "</td>";
        $grilla .=              "</tr>";
        }
        $grilla .=          "</tbody>";
        $grilla .=       "</table>";
        $grilla .=    "</div>";
        $grilla .=  "</div>";
        $grilla .="</div>";   
    } else {
        $grilla = "<div class='col-sm-12'>";
        $grilla .=  "<div class='card-box-form text-center'>";
        $grilla .=      "<div class='row'>";
        $grilla .=          "Sin resultados";
        $grilla .=      "</div>";
        $grilla .=  "</div>";
        $grilla .= "</div>";
    }
    echo $grilla;
}

function listar_borrador_fci() {

    $fcis = Accrued::listar_borrador_fci();

    if (Accrued::existe_lote_borrador(2) == true) {
        $grilla = "<div class='row'>";
        $grilla .= "<div id='grilla_abm' class='col-sm-12'>";
        $grilla .=  "<div class='card-box-form table-responsive'>";
        $grilla .=      "<h4 class='m-t-0 header-title'><b>Borrador de Items de Factura</b></h4>";
        $grilla .=      "<table id='dt_listado_fci_borrador' class='table table-hover m-0 table-responsive'>";
        $grilla .=          "<thead>";
        $grilla .=              "<tr>";
        $grilla .=                  "<th>Caso</th>";
        $grilla .=                  "<th>Prestador</th>";
        $grilla .=                  "<th>Estado</th>";
        $grilla .=                  "<th>Importe Aprobado USD</th>";
        $grilla .=              "</tr>";
        $grilla .=          "</thead>";
        $grilla .=          "<tbody>";  
        foreach($fcis as $fci) { 
                $caso_numero        = $fci["caso_numero"];
                $prestador_nombre   = $fci["prestador_nombre"];
                $estado             = $fci["estado"];
                $aprobado_usd       = $fci["aprobado_usd"];
        $grilla .=              "<tr>";
        $grilla .=                  "<td>";
        $grilla .=                      $caso_numero;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $prestador_nombre;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $estado;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $aprobado_usd;
        $grilla .=                  "</td>";
        $grilla .=              "</tr>";
        }
        $grilla .=          "</tbody>";
        $grilla .=       "</table>";
        $grilla .=    "</div>";
        $grilla .=  "</div>";
        $grilla .="</div>";   
    } else {
        $grilla = "<div class='col-sm-12'>";
        $grilla .=  "<div class='card-box-form text-center'>";
        $grilla .=      "<div class='row'>";
        $grilla .=          "Aún no se creó un Lote Borrador para Items de Factura";
        $grilla .=      "</div>";
        $grilla .=  "</div>";
        $grilla .= "</div>";        
    }
    
    echo $grilla;
}

function fci_procesado($lote_numero) {

    $fcis = Accrued::fci_procesado($lote_numero);

    $grilla = "<div class='row'>";
    $grilla .= "<div id='grilla_abm' class='col-sm-12'>";
    $grilla .=  "<div class='card-box-form table-responsive'>";
    $grilla .=      "<h4 class='m-t-0 header-title'><b>Lote " . $lote_numero . " > Items de Factura</b></h4>";
    $grilla .=      "<table id='dt_fci_procesado' class='table table-hover m-0 table-responsive'>";
    $grilla .=          "<thead>";
    $grilla .=              "<tr>";
    $grilla .=                  "<th>Caso</th>";
    $grilla .=                  "<th>Prestador</th>";
    $grilla .=                  "<th>Estado</th>";
    $grilla .=                  "<th>Importe Aprobado USD</th>";
    $grilla .=              "</tr>";
    $grilla .=          "</thead>";
    $grilla .=          "<tbody>";  
    foreach($fcis as $fci) {  
            $caso_numero        = $fci["caso_numero"];
            $prestador_nombre   = $fci["prestador_nombre"];
            $estado             = $fci["estado"];
            $aprobado_usd       = $fci["aprobado_usd"];
    $grilla .=              "<tr>";
    $grilla .=                  "<td>";
    $grilla .=                      $caso_numero;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $prestador_nombre;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $estado;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $aprobado_usd;
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

function listar_accrued_generados() {

    $acc_generados = Accrued::listar_accrued_generados();

    $grilla = "<div class='row'>";
    $grilla .= "<div id='grilla_abm' class='col-sm-12'>";
    $grilla .=  "<div class='card-box-form table-responsive'>";
    $grilla .=      "<h4 class='m-t-0 header-title'><b>Lista de Accrued Generados</b></h4>";
    $grilla .=      "<table id='dt_listado_acc_generados' class='table table-hover m-0 table-responsive'>";
    $grilla .=          "<thead>";
    $grilla .=              "<tr>";
    $grilla .=                  "<th>Lote</th>";
    $grilla .=                  "<th>Estado</th>";
    $grilla .=                  "<th>Informado</th>";
    $grilla .=                  "<th>Fecha Borrador</th>";
    $grilla .=                  "<th>Fecha Procesado</th>";
    $grilla .=                  "<th>Fecha Informado</th>";
    $grilla .=                  "<th>Reintegros Aprobado USD</th>";
    $grilla .=                  "<th>Items de Facturas Aprobado USD</th>";
    $grilla .=                  "<th>Acciones</th>";
    $grilla .=              "</tr>";
    $grilla .=          "</thead>";
    $grilla .=          "<tbody>";  
    foreach($acc_generados as $acc_generado) {
            $lote_numero            = $acc_generado["lote"];
            $estado_id              = $acc_generado["estado_id"];
            $estado                 = $acc_generado["estado"]; 
            // Conversiones de la fecha de ANSI a dd-mm-yyyy hh:mm
            $fecha_borrador_ANSI    = $acc_generado["fecha_borrador"];
            $fecha_borrador         = date("d-m-y / H:m:s", strtotime($fecha_borrador_ANSI));
            $fecha_procesado_ANSI    = $acc_generado["fecha_procesado"];
            $fecha_procesado         = date("d-m-y / H:m:s", strtotime($fecha_procesado_ANSI));
            $fecha_informado_ANSI    = $acc_generado["fecha_informado"];
            $fecha_informado         = date("d-m-y / H:m:s", strtotime($fecha_informado_ANSI));
            $imp_reint              = $acc_generado["reint_aprobado_usd"];
            $imp_fci                = $acc_generado["fci_aprobado_usd"];
    $grilla .=              "<tr>";
    $grilla .=                  "<td>";
    $grilla .=                      "Lote " . $lote_numero;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $estado;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
        if ($estado_id != 3) {
            $grilla .=  "<span class='label label-danger'>Sin Informar</span>";
        } else {
            $grilla .=  "<span class='label label-success'>Informado</span>";
        }
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $fecha_borrador;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $fecha_procesado;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $fecha_informado;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $imp_reint;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $imp_fci;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    if ($estado_id == 2) {
        $grilla .=                      "<a href='javascript:void(0)'><i onclick='informar_accrued($lote_numero)' class='fa fa-check-square-o' data-toggle='tooltip' data-placement='top' title='Marcar como Informado'></i></a>";
    }
    if ($estado_id != 1) {
        $grilla .=                      "<a href='javascript:void(0)'><i onclick='mostrar_lote_procesado($lote_numero)' class='fa fa-search' data-toggle='tooltip' data-placement='top' title='Ver Lote'></i></a>";
    } else {
        $grilla .=                      "<a href='accrued.php'><span class='label label-danger'>Sin Procesar</span></a>";
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