<?php
include_once '../includes/herramientas.php';


// Busqueda de casos
$caso_numero_desde = isset($_POST["caso_numero_desde"])?$_POST["caso_numero_desde"]:'';
$caso_numero_hasta = isset($_POST["caso_numero_hasta"])?$_POST["caso_numero_hasta"]:'';
$caso_fechaSiniestro_desde = isset($_POST["caso_fechaSiniestro_desde"])?$_POST["caso_fechaSiniestro_desde"]:'';
$caso_fechaSiniestro_hasta = isset($_POST["caso_fechaSiniestro_hasta"])?$_POST["caso_fechaSiniestro_hasta"]:'';


// Definición del ABML (Alta, Baja, Modificacion, Lectura -- objeto_ABML)
$opcion = isset($_POST["opcion"])?$_POST["opcion"]:'';


switch($opcion){
       
    // Case grillas
    case 'grilla_listar':
        grilla_listar($caso_numero_desde, 
                      $caso_numero_hasta, 
                      $caso_fechaSiniestro_desde,
                      $caso_fechaSiniestro_hasta
                      );
        break;
    
    case 'grilla_listar_contar':
        grilla_listar_contar($caso_numero_desde, 
                             $caso_numero_hasta, 
                             $caso_fechaSiniestro_desde,
                             $caso_fechaSiniestro_hasta
                             );
        break;
    
    
    case 'exportar_excel':
        exportar_excel($caso_numero_desde, 
                       $caso_numero_hasta, 
                       $caso_fechaSiniestro_desde,
                       $caso_fechaSiniestro_hasta
                       );
        break;
    
       
    default:
        echo("Está mal seleccionada la funcion");        
}


// Funciones de Grilla
    
function grilla_listar_contar($caso_numero_desde, 
                              $caso_numero_hasta, 
                              $caso_fechaSiniestro_desde,
                              $caso_fechaSiniestro_hasta
                              ){
    
    $casos = Reporte::listar_casosReAsegurador_contar($caso_numero_desde, 
                                                      $caso_numero_hasta, 
                                                      $caso_fechaSiniestro_desde,
                                                      $caso_fechaSiniestro_hasta
                                                      );

    $cantidad = $casos['registros'];
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
                       $caso_fechaSiniestro_desde,
                       $caso_fechaSiniestro_hasta
                       ){
    
    $casos = Reporte::listar_casosReAsegurador($caso_numero_desde, 
                                               $caso_numero_hasta, 
                                               $caso_fechaSiniestro_desde,
                                               $caso_fechaSiniestro_hasta
                                               );

    $grilla = "<div class='row'>";
    $grilla .= "<div id='grilla_abm' class='col-sm-12'>";
    $grilla .=  "<div class='card-box table-responsive'>";
    $grilla .=      "<h4 class='m-t-0 header-title'><b>Tabla de casos</b></h4>";
    $grilla .=      "<table id='dt_casos_reAsegurador' class='table table-hover m-0 table-responsive'>";
    $grilla .=          "<thead>";
    $grilla .=              "<tr>";
    $grilla .=                  "<th>Caso Nro</th>";
    $grilla .=                  "<th>Cliente</th>";
    $grilla .=                  "<th>Fecha Siniestro</th>";
    $grilla .=                  "<th>Producto</th>";
    $grilla .=                  "<th>Voucher</th>";
    $grilla .=                  "<th>Beneficiario</th>";
    $grilla .=              "</tr>";
    $grilla .=          "</thead>";
    $grilla .=          "<tbody>";  
    foreach($casos as $caso){
            $caso_numero = $caso["caso_numero"];
            $cliente_nombre = $caso["cliente_nombre"];
            $caso_fechaSiniestro = $caso["caso_fechaSiniestro"];
            $product_name = $caso["product_name"];    
            $caso_numeroVoucher = $caso["caso_numeroVoucher"];
            $caso_beneficiarioNombre = $caso["caso_beneficiarioNombre"];
    $grilla .=              "<tr>";
    $grilla .=                  "<td>";
    $grilla .=                      $caso_numero;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $cliente_nombre;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $caso_fechaSiniestro;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $product_name;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $caso_numeroVoucher;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $caso_beneficiarioNombre;
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
                        $caso_fechaSiniestro_desde,
                        $caso_fechaSiniestro_hasta
                       ){    
    
    // Trae el array para poner en el Excel con los resultados
    $casos = Reporte::listar_casosReAsegurador($caso_numero_desde, 
                                               $caso_numero_hasta, 
                                               $caso_fechaSiniestro_desde,
                                               $caso_fechaSiniestro_hasta
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
    $objPHPExcel->getActiveSheet()  ->setCellValue('A1', 'Platform_code')
                                    ->setCellValue('B1', 'Case number')
                                    ->setCellValue('C1', 'Claim_Occurrence_date')
                                    ->setCellValue('D1', 'Claim_Settled_amount (Reintegro)')
                                    ->setCellValue('E1', 'Claim_Settled_amount (Factura)')
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
    $objPHPExcel->getActiveSheet()->fromArray($casos, NULL, 'A2');

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

    // Redirecciona el resultado a un archivo (Excel2007)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="ReporteSGC - Re Asegurador.xlsx"');
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