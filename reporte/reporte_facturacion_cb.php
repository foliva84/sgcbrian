<?php
include_once '../includes/herramientas.php';


// Busqueda de facturas con items ingresados
$factura_fechaIngresoSistema_desde = isset($_POST["factura_fechaIngresoSistema_desde"])?$_POST["factura_fechaIngresoSistema_desde"]:'';
$factura_fechaIngresoSistema_hasta = isset($_POST["factura_fechaIngresoSistema_hasta"])?$_POST["factura_fechaIngresoSistema_hasta"]:'';
$caso_numero_desde = isset($_POST["caso_numero_desde"])?$_POST["caso_numero_desde"]:'';
$caso_numero_hasta = isset($_POST["caso_numero_hasta"])?$_POST["caso_numero_hasta"]:'';
$caso_cliente_id = isset($_POST["caso_cliente_id"])?$_POST["caso_cliente_id"]:'';
$caso_prestador_id = isset($_POST["caso_prestador_id"])?$_POST["caso_prestador_id"]:'';
$facturaEstado_id = isset($_POST["facturaEstado_id"])?$_POST["facturaEstado_id"]:'';
$factura_numero = isset($_POST["factura_numero"])?$_POST["factura_numero"]:'';


// Definición del ABML (Alta, Baja, Modificacion, Lectura -- objeto_ABML)
$opcion = isset($_POST["opcion"])?$_POST["opcion"]:'';


switch($opcion){
       
    // Case grillas
    case 'grilla_listar':
        grilla_listar($factura_fechaIngresoSistema_desde,
                      $factura_fechaIngresoSistema_hasta,
                      $caso_numero_desde, 
                      $caso_numero_hasta, 
                      $caso_cliente_id,
                      $caso_prestador_id,
                      $facturaEstado_id,
                      $factura_numero);
        break;
    
    case 'grilla_listar_contar':
        grilla_listar_contar($factura_fechaIngresoSistema_desde,
                             $factura_fechaIngresoSistema_hasta,
                             $caso_numero_desde, 
                             $caso_numero_hasta, 
                             $caso_cliente_id,
                             $caso_prestador_id,
                             $facturaEstado_id,
                             $factura_numero);
        break;
    
    
    case 'exportar_excel':
        exportar_excel($factura_fechaIngresoSistema_desde,
                       $factura_fechaIngresoSistema_hasta,
                       $caso_numero_desde, 
                       $caso_numero_hasta, 
                       $caso_cliente_id,
                       $caso_prestador_id,
                       $facturaEstado_id,
                       $factura_numero);
        break;
    
    
    // Acciones auxiliares en el formulario
       
    case 'listar_clientes':
        listar_clientes();
        break;
    
    case 'listar_fci_estados':
        listar_fci_estados();
        break;
        
    case 'listar_prestadores':
        listar_prestadores();
        break;
        
       
    default:
        echo("Está mal seleccionada la funcion");        
}


// Funciones auxiliares de formulario

    // Funciones para llenar los Select
    function listar_clientes(){

        $clientes = Cliente::formulario_alta_clientes();

            echo json_encode($clientes);   
    }
    
    function listar_fci_estados(){

        $fci_estados = Facturacion::listar_fci_estados();

        echo json_encode($fci_estados);
    }
        
    function listar_prestadores(){

        $prestadores = Prestador::listar_prestadores();

        echo json_encode($prestadores);   
    }

    
// Funciones de Grilla
    
    function grilla_listar_contar($factura_fechaIngresoSistema_desde,
                                  $factura_fechaIngresoSistema_hasta,
                                  $caso_numero_desde, 
                                  $caso_numero_hasta, 
                                  $caso_cliente_id,
                                  $caso_prestador_id,
                                  $facturaEstado_id,
                                  $factura_numero){
    
    $facturas = Reporte::listar_facturacion_contar($factura_fechaIngresoSistema_desde,
                                                    $factura_fechaIngresoSistema_hasta,
                                                    $caso_numero_desde, 
                                                    $caso_numero_hasta, 
                                                    $caso_cliente_id,
                                                    $caso_prestador_id,
                                                    $facturaEstado_id,
                                                    $factura_numero);

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


    function grilla_listar($factura_fechaIngresoSistema_desde,
                           $factura_fechaIngresoSistema_hasta,
                           $caso_numero_desde, 
                           $caso_numero_hasta, 
                           $caso_cliente_id,
                           $caso_prestador_id,
                           $facturaEstado_id,
                           $factura_numero){
    
    $facturas = Reporte::listar_facturacion($factura_fechaIngresoSistema_desde,
                                            $factura_fechaIngresoSistema_hasta,
                                            $caso_numero_desde, 
                                            $caso_numero_hasta, 
                                            $caso_cliente_id,
                                            $caso_prestador_id,
                                            $facturaEstado_id,
                                            $factura_numero);

    $grilla = "<div class='row'>";
    $grilla .= "<div id='grilla_abm' class='col-sm-12'>";
    $grilla .=  "<div class='card-box table-responsive'>";
    $grilla .=      "<h4 class='m-t-0 header-title'><b>Resultado de la búsqueda</b></h4>";
    $grilla .=      "<table id='dt_facturas' class='table table-hover m-0 table-responsive'>";
    $grilla .=          "<thead>";
    $grilla .=              "<tr>";
    $grilla .=                  "<th>Caso</th>";
    $grilla .=                  "<th>FC item número</th>";
    $grilla .=                  "<th>Estado</th>";
    $grilla .=                  "<th>Fecha ingreso</th>";
    $grilla .=                  "<th>Pagador</th>";
    $grilla .=                  "<th>Importe USD</th>";
    $grilla .=              "</tr>";
    $grilla .=          "</thead>";
    $grilla .=          "<tbody>";  
    foreach($facturas as $factura) {
            $factura_numero                 = $factura["factura_numero"];
            $caso_numero                    = $factura["caso_numero"];
            $estado                         = $factura["estado"];
            //$estado_sector                  = $factura["estadoSector"];
            $factura_fechaIngresoSistema    = $factura["factura_fechaIngresoSistema"];
            $cliente_nombre                 = $factura["cliente_nombre"];    
            $factura_importeUSD             = $factura["fcItem_importeUSD"];
    $grilla .=              "<tr>";
    $grilla .=                  "<td>";
    $grilla .=                      $caso_numero;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $factura_numero;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $estado;
    //$grilla .=                      $estado . ': ' . $estado_sector;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $factura_fechaIngresoSistema;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $cliente_nombre;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $factura_importeUSD;
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

function exportar_excel($factura_fechaIngresoSistema_desde,
                        $factura_fechaIngresoSistema_hasta,
                        $caso_numero_desde, 
                        $caso_numero_hasta, 
                        $caso_cliente_id,
                        $caso_prestador_id,
                        $facturaEstado_id,
                        $factura_numero)
{
    
    
    // Trae el array para poner en el Excel con los resultados
    $facturas = Reporte::listar_facturacion($factura_fechaIngresoSistema_desde,
                                             $factura_fechaIngresoSistema_hasta,
                                             $caso_numero_desde, 
                                             $caso_numero_hasta, 
                                             $caso_cliente_id,
                                             $caso_prestador_id,
                                             $facturaEstado_id,
                                             $factura_numero);
    
    
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
    $objPHPExcel->getActiveSheet()
        ->setCellValue('A1', 'Caso')
        ->setCellValue('B1', 'Beneficiario')
        ->setCellValue('C1', 'Fecha Siniestro')
        ->setCellValue('D1', 'Pais Siniestro')
        ->setCellValue('E1', 'Voucher')
        ->setCellValue('F1', 'Fecha Emision del Voucher')
        ->setCellValue('G1', 'Producto')
        ->setCellValue('H1', 'Agencia')
        ->setCellValue('I1', 'Factura')
        ->setCellValue('J1', 'Fecha Emision')
        ->setCellValue('K1', 'Fecha Ingreso')
        ->setCellValue('L1', 'Estado')
        ->setCellValue('M1', 'Prestador')
        ->setCellValue('N1', 'Facturador')
        ->setCellValue('O1', 'Cliente')
        ->setCellValue('P1', 'Pagador')
    ->setCellValue('Q1', 'Pagador Final')
    ->setCellValue('R1', 'Factura Final')
    ->setCellValue('S1', 'Costo Medico')
    ->setCellValue('T1', 'Fee')
    ->setCellValue('U1', 'Descuento')
    ->setCellValue('V1', 'Moneda')
    ->setCellValue('W1', 'TC')
    ->setCellValue('X1', 'Importe USD')
    ->setCellValue('Y1', 'Valor deducible')
    ->setCellValue('Z1', 'Importe Aprobado USD')
    ->setCellValue('AA1', 'Importe Aprobado Origen');

    //recorrer las columnas
    foreach (range('A', 'AA') as $columnID) {
      //autodimensionar las columnas
      $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
    }
    
    // Se vuelca el array obtenido de la base de datos con los datos
    $objPHPExcel->getActiveSheet()->fromArray($facturas, NULL, 'A2');

    // Se da estilo a los títulos
    $objPHPExcel->getActiveSheet()->getStyle('A1:AA1')->getFont()->setBold(true);


    // Se renombra a la pestaña activa
    $objPHPExcel->getActiveSheet()->setTitle('Resultado reporte');

    // Se establece a la primer pestaña como la activa
    $objPHPExcel->setActiveSheetIndex(0);


    // Redirecciona el resultado a un archivo (Excel2007)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="ReporteSGC - Facturacion.xlsx"');
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