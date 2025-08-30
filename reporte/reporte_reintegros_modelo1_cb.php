<?php
include_once '../includes/herramientas.php';


// Busqueda de casos
$caso_numero_desde = isset($_POST["caso_numero_desde"])?$_POST["caso_numero_desde"]:'';
$caso_numero_hasta = isset($_POST["caso_numero_hasta"])?$_POST["caso_numero_hasta"]:'';
$reintegro_fechaPresentacion_desde = isset($_POST["reintegro_fechaPresentacion_desde"])?$_POST["reintegro_fechaPresentacion_desde"]:'';
$reintegro_fechaPresentacion_hasta = isset($_POST["reintegro_fechaPresentacion_hasta"])?$_POST["reintegro_fechaPresentacion_hasta"]:'';
$reintegro_fechaAuditado_desde = isset($_POST["reintegro_fechaAuditado_desde"])?$_POST["reintegro_fechaAuditado_desde"]:'';
$reintegro_fechaAuditado_hasta = isset($_POST["reintegro_fechaAuditado_hasta"])?$_POST["reintegro_fechaAuditado_hasta"]:'';
$reintegro_fechaPago_desde = isset($_POST["reintegro_fechaPago_desde"])?$_POST["reintegro_fechaPago_desde"]:'';
$reintegro_fechaPago_hasta = isset($_POST["reintegro_fechaPago_hasta"])?$_POST["reintegro_fechaPago_hasta"]:'';

// Definición del ABML (Alta, Baja, Modificacion, Lectura -- objeto_ABML)
$opcion = isset($_POST["opcion"])?$_POST["opcion"]:'';


switch($opcion){
       
    // Case grillas
    case 'grilla_listar':
        grilla_listar($caso_numero_desde, 
                      $caso_numero_hasta,
                      $reintegro_fechaPresentacion_desde,
                      $reintegro_fechaPresentacion_hasta,
                      $reintegro_fechaAuditado_desde,
                      $reintegro_fechaAuditado_hasta,
                      $reintegro_fechaPago_desde,
                      $reintegro_fechaPago_hasta);
        break;
    
    case 'grilla_listar_contar':
        grilla_listar_contar($caso_numero_desde, 
                             $caso_numero_hasta,
                             $reintegro_fechaPresentacion_desde,
                             $reintegro_fechaPresentacion_hasta,
                             $reintegro_fechaAuditado_desde,
                             $reintegro_fechaAuditado_hasta,
                             $reintegro_fechaPago_desde,
                             $reintegro_fechaPago_hasta);
        break;
    
    
    case 'exportar_excel':
        exportar_excel($caso_numero_desde, 
                       $caso_numero_hasta,
                       $reintegro_fechaPresentacion_desde,
                       $reintegro_fechaPresentacion_hasta,
                       $reintegro_fechaAuditado_desde,
                       $reintegro_fechaAuditado_hasta,
                       $reintegro_fechaPago_desde,
                       $reintegro_fechaPago_hasta);
        break;
    
    // Acciones auxiliares en el formulario
    case 'listar_reintegro_estados':
        listar_reintegro_estados();
        break;
    
    case 'listar_formas_pagos':
        listar_formas_pagos();
        break;
    
       
    default:
        echo("Está mal seleccionada la funcion");        
}

// Funciones auxiliares de formulario

    // Funciones para llenar los Select  
    function listar_formas_pagos(){

        $formas_pago = Reintegro::listar_formasPago();

        echo json_encode($formas_pago);
    }
    
    function listar_reintegro_estados(){

        $reintegro_estados = Reintegro::listar_estadosReintegro();

        echo json_encode($reintegro_estados);
    }
    
    
// Funciones de Grilla
    
function grilla_listar_contar($caso_numero_desde, 
                              $caso_numero_hasta,
                              $reintegro_fechaPresentacion_desde,
                              $reintegro_fechaPresentacion_hasta,
                              $reintegro_fechaAuditado_desde,
                              $reintegro_fechaAuditado_hasta,
                              $reintegro_fechaPago_desde,
                              $reintegro_fechaPago_hasta){
    
    $reintegros = Reporte::listar_reintegros_modelo1_contar($caso_numero_desde, 
                                                            $caso_numero_hasta,
                                                            $reintegro_fechaPresentacion_desde,
                                                            $reintegro_fechaPresentacion_hasta,
                                                            $reintegro_fechaAuditado_desde,
                                                            $reintegro_fechaAuditado_hasta,
                                                            $reintegro_fechaPago_desde,
                                                            $reintegro_fechaPago_hasta);

    $cantidad = $reintegros['registros'];
    If ($cantidad > 50){
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
    $grilla .="</div>";   
    
    echo $grilla;
}


function grilla_listar($caso_numero_desde, 
                       $caso_numero_hasta,
                       $reintegro_fechaPresentacion_desde,
                       $reintegro_fechaPresentacion_hasta,
                       $reintegro_fechaAuditado_desde,
                       $reintegro_fechaAuditado_hasta,
                       $reintegro_fechaPago_desde,
                       $reintegro_fechaPago_hasta){
    
    $reintegros = Reporte::listar_reintegros_modelo1($caso_numero_desde, 
                                                     $caso_numero_hasta,
                                                     $reintegro_fechaPresentacion_desde,
                                                     $reintegro_fechaPresentacion_hasta,
                                                     $reintegro_fechaAuditado_desde,
                                                     $reintegro_fechaAuditado_hasta,
                                                     $reintegro_fechaPago_desde,
                                                     $reintegro_fechaPago_hasta);

    $grilla = "<div class='row'>";
    $grilla .= "<div id='grilla_abm' class='col-sm-12'>";
    $grilla .=  "<div class='card-box table-responsive'>";
    $grilla .=      "<h4 class='m-t-0 header-title'><b>Tabla de casos</b></h4>";
    $grilla .=      "<table id='dt_reintegros' class='table table-hover m-0 table-responsive'>";
    $grilla .=          "<thead>";
    $grilla .=              "<tr>";
    $grilla .=                  "<th>Caso Nro</th>";
    $grilla .=                  "<th>Estado</th>";
    $grilla .=                  "<th>Fecha Presentacion</th>";
    $grilla .=                  "<th>Usuario</th>";
    $grilla .=              "</tr>";
    $grilla .=          "</thead>";
    $grilla .=          "<tbody>";  
    foreach($reintegros as $reintegro){
            $caso_numero = $reintegro["caso_numero"];
            $reintegroEstado_nombre = $reintegro["reintegroEstado_nombre"];
            $reintegro_fechaPresentacion = $reintegro["reintegro_fechaPresentacion"];
            $usuario_nombre = $reintegro["usuario_nombre"];
    $grilla .=              "<tr>";
    $grilla .=                  "<td>";
    $grilla .=                      $caso_numero;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $reintegroEstado_nombre;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $reintegro_fechaPresentacion;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $usuario_nombre;
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


// Función para exportar el Excel que debe venir directamente de un POST
function exportar_excel($caso_numero_desde, 
                        $caso_numero_hasta,
                        $reintegro_fechaPresentacion_desde,
                        $reintegro_fechaPresentacion_hasta,
                        $reintegro_fechaAuditado_desde,
                        $reintegro_fechaAuditado_hasta,
                        $reintegro_fechaPago_desde,
                        $reintegro_fechaPago_hasta){    
    
    // Trae el array para poner en el Excel con los resultados
    $reintegros = Reporte::listar_reintegros_modelo1excel($caso_numero_desde, 
                                                          $caso_numero_hasta,
                                                          $reintegro_fechaPresentacion_desde,
                                                          $reintegro_fechaPresentacion_hasta,
                                                          $reintegro_fechaAuditado_desde,
                                                          $reintegro_fechaAuditado_hasta,
                                                          $reintegro_fechaPago_desde,
                                                          $reintegro_fechaPago_hasta);
    
    // Trae el array para poner en el Excel con los resultados
    $totales = Reporte::totales_reintegros_modelo1excel($caso_numero_desde, 
                                                        $caso_numero_hasta,
                                                        $reintegro_fechaPresentacion_desde,
                                                        $reintegro_fechaPresentacion_hasta,
                                                        $reintegro_fechaAuditado_desde,
                                                        $reintegro_fechaAuditado_hasta,
                                                        $reintegro_fechaPago_desde,
                                                        $reintegro_fechaPago_hasta);
    
    
    error_reporting(E_ALL);
    ini_set('display_errors', TRUE);
    ini_set('display_startup_errors', TRUE);

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
    $objPHPExcel->getActiveSheet()  ->setCellValue('A1', 'Reintegro ID')
                                    ->setCellValue('B1', 'CBU')
                                    ->setCellValue('C1', 'ARS')
                                    ->setCellValue('D1', 'Beneficiario')
                                    ->setCellValue('E1', 'CUIT')
                                    ->setCellValue('F1', 'Datos Reembolso')
                                    ->setCellValue('G1', 'USD')
                                    ->setCellValue('H1', 'Tipo Siniestro')
                                    ->setCellValue('I1', 'Coordina')
                                    ->setCellValue('J1', 'Agencia')
                                    ->setCellValue('K1', 'Caso Nro');
    
    
    // Recorre las columnas
    foreach (range('A', 'K') as $columnID) {
        //autodimensionar las columnas
        $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
    }
    
    // Se vuelca el array obtenido de la base de datos con los datos
    $objPHPExcel->getActiveSheet()->fromArray($reintegros, NULL, 'A2');
    $fila = count($reintegros) + 3;
    $colfila = 'B' . $fila;
    $colfilaARS = 'C' . $fila;
    $colfilaUSD = 'G' . $fila;
    // Se vuelcan los totales obtenidos de la base de datos
    $objPHPExcel->getActiveSheet()->fromArray($totales, NULL, $colfila);
    //$objPHPExcel->getActiveSheet()->fromArray($totales['totalImporteARS'], NULL, $colfilaARS);
    //$objPHPExcel->getActiveSheet()->fromArray($totales["totalAprobadoUSD"], NULL, $colfilaUSD);
    
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
    // Se da estilo a los totales
    //$objPHPExcel->getActiveSheet()->getStyle($colfila)->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle($colfilaARS)->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle($colfilaUSD)->getFont()->setBold(true);
    
    // Se renombra a la pestaña activa
    $objPHPExcel->getActiveSheet()->setTitle('Resultado reporte');

    // Se establece a la primer pestaña como la activa
    $objPHPExcel->setActiveSheetIndex(0);

    // Redirecciona el resultado a un archivo (Excel2007)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="ReporteSGC - Reintegros Pendientes Pago.xlsx"');
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