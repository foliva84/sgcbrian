<?php
include_once '../includes/herramientas.php';


// Definición del ABML (Alta, Baja, Modificacion, Lectura -- objeto_ABML)
$opcion = isset($_POST["opcion"])?$_POST["opcion"]:'';


switch($opcion){
       
    // Case grillas
    case 'grilla_listar':
        grilla_listar();
        break;
    
    case 'grilla_listar_contar':
        grilla_listar_contar();
        break;
    
    
    case 'exportar_excel':
        exportar_excel();
        break;
       
    default:
        echo("Está mal seleccionada la funcion");        
}


   
// Funciones de Grilla
    
    function grilla_listar_contar(){
    
    $facturas = Reporte::listar_fci_ingresados_contar();

    $cantidad = $facturas['registros'];
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


    function grilla_listar(){
    
    $facturas = Reporte::listar_fci_ingresados();

    $grilla = "<div class='row'>";
    $grilla .= "<div id='grilla_abm' class='col-sm-12'>";
    $grilla .=  "<div class='card-box table-responsive'>";
    $grilla .=      "<h4 class='m-t-0 header-title'><b>Resultado de la búsqueda</b></h4>";
    $grilla .=      "<table id='dt_facturas' class='table table-hover m-0 table-responsive'>";
    $grilla .=          "<thead>";
    $grilla .=              "<tr>";
    $grilla .=                  "<th>Caso</th>";
    $grilla .=                  "<th>Factura</th>";
    $grilla .=                  "<th>Fecha ingreso</th>";
    $grilla .=                  "<th>Voucher</th>";
    $grilla .=                  "<th>Beneficiario</th>";
    $grilla .=                  "<th>Prestador</th>";
    $grilla .=              "</tr>";
    $grilla .=          "</thead>";
    $grilla .=          "<tbody>";  
    foreach($facturas as $factura) {
            $caso_numero                    = $factura["caso_numero"];
            $factura_numero                 = $factura["factura_numero"];
            $fci_fechaIngresoSistema        = $factura["fcItem_fechaIngresoSistema"];
            $caso_voucher                   = $factura["caso_numeroVoucher"];
            $caso_beneficiario              = $factura["caso_beneficiarioNombre"];
            $prestador_nombre               = $factura["prestador_nombre"];            
    $grilla .=              "<tr>";
    $grilla .=                  "<td>";
    $grilla .=                      $caso_numero;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $factura_numero;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $fci_fechaIngresoSistema;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $caso_voucher;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $caso_beneficiario;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $prestador_nombre;
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

function exportar_excel()
{    
    
    // Trae el array para poner en el Excel con los resultados
    $facturas = Reporte::listar_fci_ingresados();
    
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
    $objPHPExcel->getProperties()   ->setCreator('CORIS SGC')
                                    ->setLastModifiedBy('CORIS SGC')
                                    ->setTitle('Reporte')
                                    ->setSubject('Reporte Excel')
                                    ->setDescription('Descripcion Reporte generado por SGC')
                                    ->setKeywords('Keywords Reporte SGC')
                                    ->setCategory('Categoria Reporte SGC');

    // Se establecen cuales serán los titulos de las filas
    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()  ->setCellValue('A1', 'Caso')
                                    ->setCellValue('B1', 'Factura')
                                    ->setCellValue('C1', 'Fecha Ingreso')
                                    ->setCellValue('D1', 'Voucher')
                                    ->setCellValue('E1', 'Beneficiario')
                                    ->setCellValue('F1', 'Prestador');
    
    //recorrer las columnas
    foreach (range('A', 'F') as $columnID) {
      //autodimensionar las columnas
      $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
    }
    
    // Se vuelca el array obtenido de la base de datos con los datos
    $objPHPExcel->getActiveSheet()->fromArray($facturas, NULL, 'A2');

    // Se da estilo a los títulos
    $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);


    // Se renombra a la pestaña activa
    $objPHPExcel->getActiveSheet()->setTitle('Resultado reporte');

    // Se establece a la primer pestaña como la activa
    $objPHPExcel->setActiveSheetIndex(0);


    // Redirecciona el resultado a un archivo (Excel2007)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="ReporteSGC - Facturas con items ingresados.xlsx"');
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