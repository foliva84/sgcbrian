<?php
include_once '../includes/herramientas.php';


// Busqueda de casos
$caso_numero_desde = isset($_POST["caso_numero_desde"]) ? $_POST["caso_numero_desde"] : '';
$caso_numero_hasta = isset($_POST["caso_numero_hasta"]) ? $_POST["caso_numero_hasta"] : '';
$caso_fechaSiniestro_desde = isset($_POST["caso_fechaSiniestro_desde"]) ? $_POST["caso_fechaSiniestro_desde"] : '';
$caso_fechaSiniestro_hasta = isset($_POST["caso_fechaSiniestro_hasta"]) ? $_POST["caso_fechaSiniestro_hasta"] : '';
$caso_agencia = isset($_POST["caso_agencia"]) ? $_POST["caso_agencia"] : '';
$caso_pais_id = isset($_POST["caso_pais_id"]) ? $_POST["caso_pais_id"] : '';
$caso_producto_id = isset($_POST["caso_producto_id"]) ? $_POST["caso_producto_id"] : '';
$reintegroEstado_id = isset($_POST["reintegroEstado_id"]) ? $_POST["reintegroEstado_id"] : '';
$reintegro_fechaIngresoSistema_desde = isset($_POST["reintegro_fechaIngresoSistema_desde"]) ? $_POST["reintegro_fechaIngresoSistema_desde"] : '';
$reintegro_fechaIngresoSistema_hasta = isset($_POST["reintegro_fechaIngresoSistema_hasta"]) ? $_POST["reintegro_fechaIngresoSistema_hasta"] : '';



// Definición del ABML (Alta, Baja, Modificacion, Lectura -- objeto_ABML)
$opcion = isset($_POST["opcion"]) ? $_POST["opcion"] : '';


switch ($opcion) {

        // Case grillas
    case 'grilla_listar':
        grilla_listar(
            $caso_numero_desde,
            $caso_numero_hasta,
            $caso_fechaSiniestro_desde,
            $caso_fechaSiniestro_hasta,
            $caso_agencia,
            $caso_pais_id,
            $caso_producto_id,
            $reintegroEstado_id,
            $reintegro_fechaIngresoSistema_desde,
            $reintegro_fechaIngresoSistema_hasta
        );
        break;

    case 'grilla_listar_contar':
        grilla_listar_contar(
            $caso_numero_desde,
            $caso_numero_hasta,
            $caso_fechaSiniestro_desde,
            $caso_fechaSiniestro_hasta,
            $caso_agencia,
            $caso_pais_id,
            $caso_producto_id,
            $reintegroEstado_id,
            $reintegro_fechaIngresoSistema_desde,
            $reintegro_fechaIngresoSistema_hasta
        );
        break;


    case 'exportar_excel':
        exportar_excel(
            $caso_numero_desde,
            $caso_numero_hasta,
            $caso_fechaSiniestro_desde,
            $caso_fechaSiniestro_hasta,
            $caso_agencia,
            $caso_pais_id,
            $caso_producto_id,
            $reintegroEstado_id,
            $reintegro_fechaIngresoSistema_desde,
            $reintegro_fechaIngresoSistema_hasta
        );
        break;

        // Acciones auxiliares en el formulario
    case 'listar_paises':
        listar_paises();
        break;

    case 'listar_productos':
        listar_productos();
        break;

    case 'listar_reintegro_estados':
        listar_reintegro_estados();
        break;


    default:
        echo ("Está mal seleccionada la funcion");
}

// Funciones auxiliares de formulario

// Funciones para llenar los Select
function listar_paises()
{

    $paises = Pais::formulario_alta_paises();

    echo json_encode($paises);
}

function listar_productos()
{

    $productos = Product::listar_activos();

    echo json_encode($productos);
}

function listar_reintegro_estados()
{

    $reintegro_estados = Reintegro::listar_estadosReintegro();

    echo json_encode($reintegro_estados);
}


// Funciones de Grilla

function grilla_listar_contar(
    $caso_numero_desde,
    $caso_numero_hasta,
    $caso_fechaSiniestro_desde,
    $caso_fechaSiniestro_hasta,
    $caso_agencia,
    $caso_pais_id,
    $caso_producto_id,
    $reintegroEstado_id,
    $reintegro_fechaIngresoSistema_desde,
    $reintegro_fechaIngresoSistema_hasta
) {

    $reintegros = Reporte::listar_reintegros_contar(
        $caso_numero_desde,
        $caso_numero_hasta,
        $caso_fechaSiniestro_desde,
        $caso_fechaSiniestro_hasta,
        $caso_agencia,
        $caso_pais_id,
        $caso_producto_id,
        $reintegroEstado_id,
        $reintegro_fechaIngresoSistema_desde,
        $reintegro_fechaIngresoSistema_hasta
    );

    $cantidad = $reintegros['registros'];
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
    $caso_numero_desde,
    $caso_numero_hasta,
    $caso_fechaSiniestro_desde,
    $caso_fechaSiniestro_hasta,
    $caso_agencia,
    $caso_pais_id,
    $caso_producto_id,
    $reintegroEstado_id,
    $reintegro_fechaIngresoSistema_desde,
    $reintegro_fechaIngresoSistema_hasta
) {

    $reintegros = Reporte::listar_reintegros(
        $caso_numero_desde,
        $caso_numero_hasta,
        $caso_fechaSiniestro_desde,
        $caso_fechaSiniestro_hasta,
        $caso_agencia,
        $caso_pais_id,
        $caso_producto_id,
        $reintegroEstado_id,
        $reintegro_fechaIngresoSistema_desde,
        $reintegro_fechaIngresoSistema_hasta
    );

    $grilla = "<div class='row'>";
    $grilla .= "<div id='grilla_abm' class='col-sm-12'>";
    $grilla .=  "<div class='card-box table-responsive'>";
    $grilla .=      "<h4 class='m-t-0 header-title'><b>Tabla de reintegros</b></h4>";
    $grilla .=      "<table id='dt_reintegros' class='table table-hover m-0 table-responsive'>";
    $grilla .=          "<thead>";
    $grilla .=              "<tr>";
    $grilla .=                  "<th>Caso Nro</th>";
    $grilla .=                  "<th>Estado</th>";
    $grilla .=                  "<th>Fecha de Ingreso</th>";
    $grilla .=                  "<th>Fecha de Aprobacion</th>";
    $grilla .=                  "<th>Fecha de Pago</th>";
    $grilla .=                  "<th>Importe Aprobado USD</th>";
    $grilla .=                  "<th>Importe Rechazado USD</th>";
    $grilla .=                  "<th>Medio de Pago</th>";
    $grilla .=              "</tr>";
    $grilla .=          "</thead>";
    $grilla .=          "<tbody>";
    foreach ($reintegros as $reintegro) {
        $caso_id = $reintegro["caso_id"];
        $caso_numero = $reintegro["caso_numero"];
        $reintegroEstado_nombre = $reintegro["reintegroEstado_nombre"];
        $reintegro_fechaIngreso = $reintegro["reintegro_fechaIngreso"];
        $reintegro_fechaAuditado = $reintegro["reintegro_fechaPresentacion"];
        $reintegro_fechaPago = $reintegro["reintegro_fechaPago"];
        $reintegro_formaPago = $reintegro["formaPago_nombre"];

        $importeUSD = $reintegro["importeUSD"];
        $importeAprobadoUSD = $reintegro["importeAprobadoUSD"];
        $importeRechazadoUSD = ($importeUSD - $importeAprobadoUSD);
        $grilla .=              "<tr>";
        $grilla .=                  "<td>";
        $grilla .=                      "<a target='_blank' href='../caso/caso.php?vcase=" . $caso_id . "'>" . $caso_numero . "</a>";
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $reintegroEstado_nombre;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $reintegro_fechaIngreso;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $reintegro_fechaAuditado;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $reintegro_fechaPago;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $importeAprobadoUSD;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $importeRechazadoUSD;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $reintegro_formaPago;
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
    $caso_numero_desde,
    $caso_numero_hasta,
    $caso_fechaSiniestro_desde,
    $caso_fechaSiniestro_hasta,
    $caso_agencia,
    $caso_pais_id,
    $caso_producto_id,
    $reintegroEstado_id,
    $reintegro_fechaIngresoSistema_desde,
    $reintegro_fechaIngresoSistema_hasta
) {

    // Trae el array para poner en el Excel con los resultados
    $reintegros = Reporte::listar_reintegros_excel(
        $caso_numero_desde,
        $caso_numero_hasta,
        $caso_fechaSiniestro_desde,
        $caso_fechaSiniestro_hasta,
        $caso_agencia,
        $caso_pais_id,
        $caso_producto_id,
        $reintegroEstado_id,
        $reintegro_fechaIngresoSistema_desde,
        $reintegro_fechaIngresoSistema_hasta
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
    $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Caso Numero')
        ->setCellValue('B1', 'Fecha del siniestro')
        ->setCellValue('C1', 'Cliente')
        ->setCellValue('D1', 'Asistencia tipo')
        ->setCellValue('E1', 'Usuario')
        ->setCellValue('F1', 'Estado del reintegro')
        ->setCellValue('G1', 'Fecha de ingreso')
        ->setCellValue('H1', 'Fecha de Aprobacion')
        ->setCellValue('I1', 'Fecha de Pago')
        ->setCellValue('J1', 'Pais del siniestro')
        ->setCellValue('K1', 'Producto')
        ->setCellValue('L1', 'Agencia')
        ->setCellValue('M1', 'Importe Aprobado Origen')
        ->setCellValue('N1', 'Importe Aprobado USD')
        ->setCellValue('O1', 'Importe Rechazado USD')
        ->setCellValue('P1', 'Forma de pago')
        ->setCellValue('Q1', 'Voucher')
        ->setCellValue('R1', 'Fecha emisión del vaucher')
        ->setCellValue('S1', 'Cliente');

    // Recorre las columnas
    foreach (range('A', 'P') as $columnID) {
        //autodimensionar las columnas
        $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
    }

    // Se vuelca el array obtenido de la base de datos con los datos
    $objPHPExcel->getActiveSheet()->fromArray($reintegros, NULL, 'A2');

    // Se da estilo a los títulos
    $objPHPExcel->getActiveSheet()->getStyle('A1:S1')->applyFromArray(
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
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Un día en el pasado
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header('Pragma: public'); // HTTP/1.0

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
    exit;
}
