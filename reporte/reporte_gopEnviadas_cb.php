<?php
include_once '../includes/herramientas.php';


// Busqueda de gop
$caso_numero = isset($_POST["caso_numero"])?$_POST["caso_numero"]:'';
$gop_id = isset($_POST["gop_id"])?$_POST["gop_id"]:'';

// Definición del ABML (Alta, Baja, Modificacion, Lectura -- objeto_ABML)
$opcion = isset($_POST["opcion"])?$_POST["opcion"]:'';


switch($opcion){
       
    // Case grillas
    case 'grilla_listar':
        grilla_listar($caso_numero);
        break;
    
    case 'grilla_listar_contar':
        grilla_listar_contar($caso_numero);
        break;
    
    case 'ver_gop':
        ver_gop($gop_id);
        break;
    
    
    case 'exportar_excel':
        exportar_excel($caso_numero);
        break;
    
       
    default:
        echo("Está mal seleccionada la funcion");        
}


// Funciones de Grilla
    
function grilla_listar_contar($caso_numero){
    
    $gopEnviadas = Reporte::listar_gopEnviadas_contar($caso_numero);

    $cantidad = $gopEnviadas['registros'];
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


function grilla_listar($caso_numero){
    
    $gopEnviadas = Reporte::listar_gopEnviadas($caso_numero);

    $grilla = "<div class='row'>";
    $grilla .= "<div id='grilla_abm' class='col-sm-12'>";
    $grilla .=  "<div class='card-box table-responsive'>";
    $grilla .=      "<h4 class='m-t-0 header-title'><b>Tabla de casos</b></h4>";
    $grilla .=      "<table id='dt_gopEnviadas' class='table table-hover m-0 table-responsive'>";
    $grilla .=          "<thead>";
    $grilla .=              "<tr>";
    $grilla .=                  "<th>Caso Nro</th>";
    $grilla .=                  "<th>Voucher</th>";
    $grilla .=                  "<th>Prestador</th>";
    $grilla .=                  "<th>Beneficiario</th>";
    $grilla .=                  "<th>Fecha</th>";
    $grilla .=                  "<th>Ver</th>";
    $grilla .=              "</tr>";
    $grilla .=          "</thead>";
    $grilla .=          "<tbody>";  
    foreach($gopEnviadas as $gopEnviada){
            $gop_id = $gopEnviada["gop_id"];
            $caso_numero = $gopEnviada["gop_casoNumero"];
            $numeroVoucher = $gopEnviada["gop_voucher"];
            $prestador_nombre = $gopEnviada["prestador_nombre"];
            $beneficiarioNombre = $gopEnviada["gop_nombreBeneficiario"];
            $gop_fecha = $gopEnviada["gop_fecha"];
    $grilla .=              "<tr>";
    $grilla .=                  "<td>";
    $grilla .=                      $caso_numero;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $numeroVoucher;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $prestador_nombre;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $beneficiarioNombre;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $gop_fecha;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      "<a href='javascript:void(0)'> <i onclick='ver_gop($gop_id)' class='fa fa-edit'></i></a>";
    $grilla .=                  "</td>";
    $grilla .=              "</tr>";
    $grilla .=              "</tr>";
    }
    $grilla .=          "</tbody>";
    $grilla .=       "</table>";
    $grilla .=    "</div>";
    $grilla .=  "</div>";
    $grilla .="</div>";   
    
    echo $grilla;
}


function ver_gop($gop_id){
    
    $gop_info = Gop::buscarPorId($gop_id);
    
    echo json_encode($gop_info);
}


// Función para exportar el Excel que debe venir directamente de un POST

function exportar_excel($caso_numero){    
    
    // Trae el array para poner en el Excel con los resultados
    $gopEnviadas = Reporte::listar_gopEnviadas($caso_numero);
    
    
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
    $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Cliente')
                                    ->setCellValue('B1', 'Caso')
                                    ->setCellValue('C1', 'Fecha Siniestro')
                                    ->setCellValue('D1', 'Presunto USD')
                                    ->setCellValue('E1', 'Pais')
                                    ->setCellValue('F1', 'Ciudad')
                                    ->setCellValue('G1', 'Voucher')
                                    ->setCellValue('H1', 'Producto')
                                    ->setCellValue('I1', 'Fecha Emision')
                                    ->setCellValue('J1', 'T.Asistencia')
                                    ->setCellValue('K1', 'Beneficiario');                                    
                                    
    
    //recorrer las columnas
    foreach (range('A', 'K') as $columnID) {
      //autodimensionar las columnas
      $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
    }
    
    // Se vuelca el array obtenido de la base de datos con los datos
    $objPHPExcel->getActiveSheet()->fromArray($gopEnviadas, NULL, 'A2');

    // Se da estilo a los títulos
    $objPHPExcel->getActiveSheet()->getStyle('A1:V1')->getFont()->setBold(true);


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