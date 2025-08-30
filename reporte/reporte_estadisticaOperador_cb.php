<?php
include_once '../includes/herramientas.php';

// Busqueda de casos
$caso_fechaAperturaCaso_desde   = isset($_POST["caso_fechaAperturaCaso_desde"])?$_POST["caso_fechaAperturaCaso_desde"]:'';
$caso_fechaAperturaCaso_hasta   = isset($_POST["caso_fechaAperturaCaso_hasta"])?$_POST["caso_fechaAperturaCaso_hasta"]:'';
$incluir_usuariosDeshabilitados = isset($_POST["incluir_usuariosDeshabilitados"])?$_POST["incluir_usuariosDeshabilitados"]:'';
$incluir_usuariosDeshab         = isset($_POST["incluir_usuariosDeshab"])?$_POST["incluir_usuariosDeshab"]:'';

// Definición del ABML (Alta, Baja, Modificacion, Lectura -- objeto_ABML)
$opcion                         = isset($_POST["opcion"])?$_POST["opcion"]:'';

switch($opcion){
       
    // Case grillas
    case 'grilla_listar':
        grilla_listar($caso_fechaAperturaCaso_desde,
                      $caso_fechaAperturaCaso_hasta,
                      $incluir_usuariosDeshabilitados);
        break;    
    
    case 'exportar_excel':
        exportar_excel($caso_fechaAperturaCaso_desde,
                       $caso_fechaAperturaCaso_hasta,
                       $incluir_usuariosDeshab);
        break;
    
    default:
        echo("Está mal seleccionada la funcion");        
}


// Funciones de Grilla
    
function grilla_listar($caso_fechaAperturaCaso_desde,
                       $caso_fechaAperturaCaso_hasta,
                       $incluir_usuariosDeshabilitados){

    $casos_porOperador = Reporte::listar_estadisticaPorOperador($caso_fechaAperturaCaso_desde,
                                                                $caso_fechaAperturaCaso_hasta,
                                                                $incluir_usuariosDeshabilitados);

    $grilla = "<div class='row'>";
    $grilla .= "<div id='grilla_abm' class='col-sm-12'>";
    $grilla .=  "<div class='card-box table-responsive'>";
    $grilla .=      "<h4 class='m-t-0 header-title'><b>Casos abiertos y notas ingresadas por operador</b></h4>";
    $grilla .=      "<table id='dt_estadisticaOperador' class='table table-hover m-0 table-responsive'>";
    $grilla .=          "<thead>";
    $grilla .=              "<tr>";
    $grilla .=                  "<th>Usuario</th>";
    $grilla .=                  "<th>Cantidad de Casos</th>";
    $grilla .=                  "<th>Cantidad de Notas</th>";
    $grilla .=                  "<th>Cantidad de Notas Reintegros</th>";
    $grilla .=                  "<th>Usuario Estado</th>";
    $grilla .=              "</tr>";
    $grilla .=          "</thead>";
    $grilla .=          "<tbody>";  
    foreach($casos_porOperador as $operador){
            $operador_nombre = $operador["usuario_usuario"];
            $operador_cantidadCasos = $operador["cantcasos"];
            $operador_cantidadNotas = $operador["cantcomunic"];
            $operador_cantidadNotasR = $operador["cantcomunicR"];
            $usuarioEstado = $operador["usuarioEstado"];
    $grilla .=              "<tr>";
    $grilla .=                  "<td>";
    $grilla .=                      $operador_nombre;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $operador_cantidadCasos;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $operador_cantidadNotas;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $operador_cantidadNotasR;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $usuarioEstado;
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
function exportar_excel($caso_fechaAperturaCaso_desde,
                        $caso_fechaAperturaCaso_hasta,
                        $incluir_usuariosDeshab) {    
    
    // Trae el array para poner en el Excel con los resultados
    $casos_porOperador = Reporte::listar_estadisticaPorOperador($caso_fechaAperturaCaso_desde,
                                                                $caso_fechaAperturaCaso_hasta,
                                                                $incluir_usuariosDeshab);   
    
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
    $objPHPExcel->getActiveSheet()  ->setCellValue('A1', 'Usuario')
                                    ->setCellValue('B1', 'Cantidad de Casos')
                                    ->setCellValue('C1', 'Cantidad de Notas Casos')
                                    ->setCellValue('D1', 'Cantidad de Notas Reintegros')
                                    ->setCellValue('E1', 'Usuario Estado');
    
    // Recorre las columnas
    foreach (range('A', 'E') as $columnID) {
        //autodimensionar las columnas
        $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
    }
    
    // Se vuelca el array obtenido de la base de datos con los datos
    $objPHPExcel->getActiveSheet()->fromArray($casos_porOperador, NULL, 'A2');

    // Se da estilo a los títulos
    $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->applyFromArray (
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
    header('Content-Disposition: attachment;filename="ReporteSGC - estadisticasOperador.xlsx"');
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