<?php
include_once '../includes/herramientas.php';

$reintegroEstado_id = isset($_POST["reintegroEstado_id"])?$_POST["reintegroEstado_id"]:'';
// Definicion del ABML (Alta, Baja, Modificacion, Lectura -- objeto_ABML)
$opcion = isset($_POST["opcion"])?$_POST["opcion"]:'';


switch($opcion){
       
    // Case grillas
    case 'grilla_listar':
        grilla_listar($reintegroEstado_id);
        break;
    
    case 'grilla_listar_contar':
        grilla_listar_contar($reintegroEstado_id);
        break;
    
    
    case 'exportar_excel':
        exportar_excel($reintegroEstado_id);
        break;
    
    case 'listar_reintegro_estados':
        listar_reintegro_estados();
        break;
    
    default:
        echo("Esta mal seleccionada la funcion");        
}

    
// Funciones auxiliares de formulario

    // Funciones para llenar los Select
    function listar_reintegro_estados(){

        $reintegro_estados = Reintegro::listar_estadosReintegro();

        echo json_encode($reintegro_estados);
    }

// Funciones de Grilla    
function grilla_listar_contar($reintegroEstado_id){
    
    $reintegros = Reporte::listar_reintegros_op300_contar($reintegroEstado_id);

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


function grilla_listar($reintegroEstado_id){
    
    $reintegros = Reporte::listar_reintegros_op300($reintegroEstado_id);

    $grilla = "<div class='row'>";
    $grilla .= "<div id='grilla_abm' class='col-sm-12'>";
    $grilla .=  "<div class='card-box table-responsive'>";
    $grilla .=      "<h4 class='m-t-0 header-title'><b>Tabla de reintegros</b></h4>";
    $grilla .=      "<table id='dt_reintegros' class='table table-hover m-0 table-responsive'>";
    $grilla .=          "<thead>";
    $grilla .=              "<tr>";
    $grilla .=                  "<th>Caso Nro</th>";
    $grilla .=                  "<th>Cliente</th>";
    $grilla .=                  "<th>Producto</th>";
    $grilla .=                  "<th>Voucher</th>";
    $grilla .=                  "<th>Importe Aprobado USD</th>";
    $grilla .=                  "<th>Agencia</th>";
    $grilla .=              "</tr>";
    $grilla .=          "</thead>";
    $grilla .=          "<tbody>";  
    foreach($reintegros as $reintegro){
            $caso_id = $reintegro["caso_id"];
            $caso_nro = $reintegro["caso_numero"];
            $cliente_nombre = $reintegro["cliente_nombre"];
            $producto_nombre = $reintegro["product_name"];
            $voucher_nro = $reintegro["caso_numeroVoucher"];
            $importeAprobadoUSD = $reintegro["importeAprobadoUSD"];
            $caso_agencia = $reintegro["caso_agencia"];
    $grilla .=              "<tr>";
    $grilla .=                  "<td>";
    $grilla .=                      "<a target='_blank' href='../caso/caso.php?vcase=" . $caso_id . "'>" . $caso_nro . "</a>";
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $cliente_nombre;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $producto_nombre;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $voucher_nro;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $importeAprobadoUSD;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $caso_agencia;
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

function exportar_excel($reintegroEstado_id){    
    
    // Trae el array para poner en el Excel con los resultados
    $reintegros = Reporte::listar_reintegros_op300_excel($reintegroEstado_id);
    
    
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
    $objPHPExcel->getActiveSheet()  ->setCellValue('A1', 'Caso Numero')
                                    ->setCellValue('B1', 'Cliente')
                                    ->setCellValue('C1', 'Producto')
                                    ->setCellValue('D1', 'Voucher')
                                    ->setCellValue('E1', 'Importe Aprobado USD')
                                    ->setCellValue('F1', 'Agencia');
    
    // Recorre las columnas
    foreach (range('A', 'F') as $columnID) {
        //autodimensionar las columnas
        $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
    }
    
    // Se vuelca el array obtenido de la base de datos con los datos
    $objPHPExcel->getActiveSheet()->fromArray($reintegros, NULL, 'A2');

    // Se da estilo a los títulos
    $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->applyFromArray(
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
    header('Content-Disposition: attachment;filename="ReporteSGC - Reintegros.xlsx"');
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