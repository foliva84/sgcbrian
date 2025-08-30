<?php
include_once '../includes/herramientas.php';


// Toma el id de país para los select dependientes
$pais_id = isset($_POST["pais_id"])?$_POST["pais_id"]:'';
$ciudad = isset($_POST["ciudad"])?$_POST["ciudad"]:'';

// Busqueda de casos
$caso_numero_desde = isset($_POST["caso_numero_desde"])?$_POST["caso_numero_desde"]:'';
$caso_numero_hasta = isset($_POST["caso_numero_hasta"])?$_POST["caso_numero_hasta"]:'';
$caso_fechaSiniestro_desde = isset($_POST["caso_fechaSiniestro_desde"])?$_POST["caso_fechaSiniestro_desde"]:'';
$caso_fechaSiniestro_hasta = isset($_POST["caso_fechaSiniestro_hasta"])?$_POST["caso_fechaSiniestro_hasta"]:'';
$caso_cliente_id = isset($_POST["caso_cliente_id"])?$_POST["caso_cliente_id"]:'';
$caso_producto_id = isset($_POST["caso_producto_id"])?$_POST["caso_producto_id"]:'';
$caso_pais_id = isset($_POST["caso_pais_id"])?$_POST["caso_pais_id"]:'';
$caso_ciudad_id = isset($_POST["caso_ciudad_id"])?$_POST["caso_ciudad_id"]:'';
$caso_ciudad_id_2 = isset($_POST["caso_ciudad_id_2"])?$_POST["caso_ciudad_id_2"]:'';
$caso_tipoAsistencia_id = isset($_POST["caso_tipoAsistencia_id"])?$_POST["caso_tipoAsistencia_id"]:'';
$caso_abiertoPor_id = isset($_POST["caso_abiertoPor_id"])?$_POST["caso_abiertoPor_id"]:'';
$caso_agencia = isset($_POST["caso_agencia"])?$_POST["caso_agencia"]:'';
$caso_prestador_id = isset($_POST["caso_prestador_id"])?$_POST["caso_prestador_id"]:'';
$caso_estado_id = isset($_POST["caso_estado_id"])?$_POST["caso_estado_id"]:'';
$caso_fee_id = isset($_POST["caso_fee_id"])?$_POST["caso_fee_id"]:'';
$caso_anulado = isset($_POST["caso_anulado"])?$_POST["caso_anulado"]:'';


// Definición del ABML (Alta, Baja, Modificacion, Lectura -- objeto_ABML)
$opcion = isset($_POST["opcion"])?$_POST["opcion"]:'';


switch($opcion){
       
    // Case grillas
    case 'grilla_listar':
        grilla_listar($caso_numero_desde, 
                      $caso_numero_hasta, 
                      $caso_fechaSiniestro_desde,
                      $caso_fechaSiniestro_hasta,
                      $caso_cliente_id,
                      $caso_producto_id,
                      $caso_pais_id,
                      $caso_ciudad_id,
                      $caso_tipoAsistencia_id,
                      $caso_abiertoPor_id,
                      $caso_agencia,
                      $caso_prestador_id,
                      $caso_estado_id,
                      $caso_fee_id,
                      $caso_anulado);
        break;
    
    case 'grilla_listar_contar':
        grilla_listar_contar($caso_numero_desde, 
                             $caso_numero_hasta, 
                             $caso_fechaSiniestro_desde,
                             $caso_fechaSiniestro_hasta,
                             $caso_cliente_id,
                             $caso_producto_id,
                             $caso_pais_id,
                             $caso_ciudad_id,
                             $caso_tipoAsistencia_id,
                             $caso_abiertoPor_id,
                             $caso_agencia,
                             $caso_prestador_id,
                             $caso_estado_id,
                             $caso_fee_id,
                             $caso_anulado);
        break;
    
    
    case 'exportar_excel':
        exportar_excel($caso_numero_desde, 
                        $caso_numero_hasta, 
                        $caso_fechaSiniestro_desde,
                        $caso_fechaSiniestro_hasta,
                        $caso_cliente_id,
                        $caso_producto_id,
                        $caso_pais_id,
                        $caso_ciudad_id,
                        $caso_tipoAsistencia_id,
                        $caso_abiertoPor_id,
                        $caso_agencia,
                        $caso_prestador_id,
                        $caso_estado_id,
                        $caso_fee_id,
                        $caso_anulado);
        break;
    
    
    // Acciones auxiliares en el formulario
       
    case 'listar_clientes':
        listar_clientes();
        break;
    
    case 'listar_productos':
        listar_productos();
        break;
        
    case 'listar_tiposAsistencia':
        listar_tiposAsistencia();
        break;
    
    case 'listar_usuarios':
        listar_usuarios();
        break;
    
    case 'listar_prestadores':
        listar_prestadores();
        break;
        
    case 'listar_paises':
        listar_paises();
        break;

    case 'select_ciudades':
        select_ciudades($ciudad, $pais_id);
        break;
    
    case 'listar_estadosCasos':
        listar_estadosCasos();
        break;
    
    case 'listar_fees':
        listar_fees();
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
    
    function listar_productos(){

        $productos = Product::listar_activos();

            echo json_encode($productos);   
    }
        
    function listar_tiposAsistencia(){

        $tiposAsistencias = TipoAsistencia::listar();

            echo json_encode($tiposAsistencias);   
    }
    
    function listar_usuarios(){

        $usuario = Usuario::listar_usuarios();

            echo json_encode($usuario);   
    }
    
    function listar_prestadores(){

        $prestadores = Prestador::listar_prestadores();

        echo json_encode($prestadores);   
    }

    function listar_paises(){

        $paises = Pais::formulario_alta_paises();

        echo json_encode($paises);   
    }
    
    function select_ciudades($ciudad, $pais_id){

        $ciudades = Ciudad::buscar_select($ciudad, $pais_id);

        $data = array();
        foreach ($ciudades as $ciudad) {
            $name = $ciudad['ciudad_nombre'] . '|' . $ciudad['ciudad_id'];
            array_push($data, $name);	
        }	

        echo json_encode($data);

    }
    
    function listar_estadosCasos(){

        $casoEstados = EstadoCaso::listar_casoEstados();

        echo json_encode($casoEstados);      
    }
    
    function listar_fees(){

            $fees = Fee::listar();

            echo json_encode($fees);   
        }
    


// Funciones de Grilla
    
function grilla_listar_contar($caso_numero_desde, 
                              $caso_numero_hasta, 
                              $caso_fechaSiniestro_desde,
                              $caso_fechaSiniestro_hasta,
                              $caso_cliente_id,
                              $caso_producto_id,
                              $caso_pais_id,
                              $caso_ciudad_id,
                              $caso_tipoAsistencia_id,
                              $caso_abiertoPor_id,
                              $caso_agencia,
                              $caso_prestador_id,
                              $caso_estado_id,
                              $caso_fee_id,
                              $caso_anulado){
    
    $casos = Reporte::listar_casosAvanzado_contar($caso_numero_desde, 
                                                  $caso_numero_hasta, 
                                                  $caso_fechaSiniestro_desde,
                                                  $caso_fechaSiniestro_hasta,
                                                  $caso_cliente_id,
                                                  $caso_producto_id,
                                                  $caso_pais_id,
                                                  $caso_ciudad_id,
                                                  $caso_tipoAsistencia_id,
                                                  $caso_abiertoPor_id,
                                                  $caso_agencia,
                                                  $caso_prestador_id,
                                                  $caso_estado_id,
                                                  $caso_fee_id,
                                                  $caso_anulado);

    $cantidad = $casos['registros'];
    If ($cantidad > 36000){
        $texto = "<i class='fa fa-exclamation-circle'></i>   Se han encontrado " . $cantidad . " registros. Se muestran sólo los primeros 36000 resultados. Por favor refine su búsqueda.";
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
                       $caso_fechaSiniestro_hasta,
                       $caso_cliente_id,
                       $caso_producto_id,
                       $caso_pais_id,
                       $caso_ciudad_id,
                       $caso_tipoAsistencia_id,
                       $caso_abiertoPor_id,
                       $caso_agencia,
                       $caso_prestador_id,
                       $caso_estado_id,
                       $caso_fee_id,
                       $caso_anulado){
    
    $casos = Reporte::listar_casosAvanzado($caso_numero_desde, 
                                           $caso_numero_hasta, 
                                           $caso_fechaSiniestro_desde,
                                           $caso_fechaSiniestro_hasta,
                                           $caso_cliente_id,
                                           $caso_producto_id,
                                           $caso_pais_id,
                                           $caso_ciudad_id,
                                           $caso_tipoAsistencia_id,
                                           $caso_abiertoPor_id,
                                           $caso_agencia,
                                           $caso_prestador_id,
                                           $caso_estado_id,
                                           $caso_fee_id,
                                           $caso_anulado);

    $grilla = "<div class='row'>";
    $grilla .= "<div id='grilla_abm' class='col-sm-12'>";
    $grilla .=  "<div class='card-box table-responsive'>";
    $grilla .=      "<h4 class='m-t-0 header-title'><b>Resultado de la búsqueda</b></h4>";
    $grilla .=      "<table id='dt_prestador' class='table table-hover m-0 table-responsive'>";
    $grilla .=          "<thead>";
    $grilla .=              "<tr>";
    $grilla .=                  "<th>Caso Nro</th>";
    $grilla .=                  "<th>Fecha Siniestro</th>";
    $grilla .=                  "<th>Tipo de Asistencia</th>";
    $grilla .=                  "<th>Asistencia sin costo</th>";
    $grilla .=                  "<th>Estado</th>";
    $grilla .=                  "<th>Cliente</th>";
    $grilla .=                  "<th>Voucher</th>";
    $grilla .=                  "<th>Beneficiario</th>";
    $grilla .=              "</tr>";
    $grilla .=          "</thead>";
    $grilla .=          "<tbody>";  
    foreach($casos as $caso) {
        $caso_numero             = $caso["caso_numero"];
        $caso_fechaSiniestro     = $caso["caso_fechaSiniestro"];
        $tipoAsistencia          = $caso["tipoAsistencia"];
        $no_medical_cost         = $caso["no_medical_cost"];
        $casoEstado_nombre       = $caso["casoEstado_nombre"];
        $cliente_nombre          = $caso["cliente_nombre"];    
        $caso_numeroVoucher      = $caso["caso_numeroVoucher"];
        $caso_beneficiarioNombre = $caso["caso_beneficiarioNombre"];
    $grilla .=              "<tr>";
    $grilla .=                  "<td>";
    $grilla .=                      $caso_numero;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $caso_fechaSiniestro;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $tipoAsistencia;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $no_medical_cost;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $casoEstado_nombre;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $cliente_nombre;
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
                       $caso_fechaSiniestro_hasta,
                       $caso_cliente_id,
                       $caso_producto_id,
                       $caso_pais_id,
                       $caso_ciudad_id,
                       $caso_tipoAsistencia_id,
                       $caso_abiertoPor_id,
                       $caso_agencia,
                       $caso_prestador_id,
                       $caso_estado_id,
                       $caso_fee_id,
                       $caso_anulado)
{
    
    
    // Trae el array para poner en el Excel con los resultados
    $casos = Reporte::listar_casosAvanzado($caso_numero_desde, 
                                           $caso_numero_hasta, 
                                           $caso_fechaSiniestro_desde,
                                           $caso_fechaSiniestro_hasta,
                                           $caso_cliente_id,
                                           $caso_producto_id,
                                           $caso_pais_id,
                                           $caso_ciudad_id,
                                           $caso_tipoAsistencia_id,
                                           $caso_abiertoPor_id,
                                           $caso_agencia,
                                           $caso_prestador_id,
                                           $caso_estado_id,
                                           $caso_fee_id,
                                           $caso_anulado);
    
    
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
                                ->setTitle('Reporte Avanzado de Casos')
                                ->setSubject('Reporte Excel')
                                ->setDescription('Descripcion Reporte generado por SGC')
                                ->setKeywords('Keywords Reporte SGC')
                                ->setCategory('Categoria Reporte SGC');

    // Se establecen cuales serán los titulos de las filas
    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Caso')
                                    ->setCellValue('B1', 'Fecha Siniestro')
                                    ->setCellValue('C1', 'Tipo de Asistencia')
                                    ->setCellValue('D1', 'Asistencia sin costo')
                                    ->setCellValue('E1', 'Estado')
                                    ->setCellValue('F1', 'País')
                                    ->setCellValue('G1', 'Ciudad')
                                    ->setCellValue('H1', 'Fecha Apertura')
                                    ->setCellValue('I1', 'Cliente')
                                    ->setCellValue('J1', 'Voucher')
                                    ->setCellValue('K1', 'Producto')
                                    ->setCellValue('L1', 'Fecha Emision')
                                    ->setCellValue('M1', 'Vig. Desde')
                                    ->setCellValue('N1', 'Vig. Hasta')
                                    ->setCellValue('O1', 'Beneficiario')
                                    ->setCellValue('P1', 'Edad')
                                    ->setCellValue('Q1', 'Síntoma')
                                    ->setCellValue('R1', 'Diagnóstico')
                                    ->setCellValue('S1', 'Fee')
                                    ->setCellValue('T1', 'Agencia')
                                    ->setCellValue('U1', 'Usuario')
                                    ->setCellValue('V1', 'Presunto USD')
                                    ->setCellValue('W1', 'Observaciones')
                                    ->setCellValue('X1', 'Prestador');
                                    
    
    //recorrer las columnas
    foreach (range('A', 'V') as $columnID) {
      //autodimensionar las columnas
      $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
    }
    
    // Se vuelca el array obtenido de la base de datos con los datos
    $objPHPExcel->getActiveSheet()->fromArray($casos, NULL, 'A2');

    // Se da estilo a los títulos
    $objPHPExcel->getActiveSheet()->getStyle('A1:V1')->getFont()->setBold(true);


    // Se renombra a la pestaña activa
    $objPHPExcel->getActiveSheet()->setTitle('Resultado reporte');

    // Se establece a la primer pestaña como la activa
    $objPHPExcel->setActiveSheetIndex(0);


    // Redirecciona el resultado a un archivo (Excel2007)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="ReporteSGC - Casos Avanzados.xlsx"');
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