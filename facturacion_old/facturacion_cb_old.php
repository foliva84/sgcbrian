<?php
require '../seguridad/seguridad.php';
include_once '../includes/herramientas.php';


// Toma las variables del formulario ALTA
$factura_caso_id_n                  = isset($_POST["factura_caso_id_n"])?$_POST["factura_caso_id_n"]:'';
$factura_tipo_id_n                  = isset($_POST["factura_tipo_id_n"])?$_POST["factura_tipo_id_n"]:'';
$factura_numero_n                   = isset($_POST["factura_numero_n"])?$_POST["factura_numero_n"]:'';
$factura_prioridad_id_n             = isset($_POST["factura_prioridad_id_n"])?$_POST["factura_prioridad_id_n"]:'';
$factura_pagador_id_n               = isset($_POST["factura_pagador_id_n"])?$_POST["factura_pagador_id_n"]:'';
$factura_fechaRecepcion_n           = isset($_POST["factura_fechaRecepcion_n"])?$_POST["factura_fechaRecepcion_n"]:'';
$factura_fechaEmision_n             = isset($_POST["factura_fechaEmision_n"])?$_POST["factura_fechaEmision_n"]:'';
$factura_fechaVencimiento_n         = isset($_POST["factura_fechaVencimiento_n"])?$_POST["factura_fechaVencimiento_n"]:'';
$factura_importe_medicoOrigen_n     = isset($_POST["factura_importe_medicoOrigen_n"])?$_POST["factura_importe_medicoOrigen_n"]:'';
$factura_importe_feeOrigen_n        = isset($_POST["factura_importe_feeOrigen_n"])?$_POST["factura_importe_feeOrigen_n"]:'';
$factura_moneda_id_n                = isset($_POST["factura_moneda_id_n"])?$_POST["factura_moneda_id_n"]:'';
$factura_tipoCambio_n               = isset($_POST["factura_tipoCambio_n"])?$_POST["factura_tipoCambio_n"]:'';
$factura_importeUSD_n               = isset($_POST["factura_importeUSD_n"])?$_POST["factura_importeUSD_n"]:'';
$factura_observaciones_n            = isset($_POST["factura_observaciones_n"])?$_POST["factura_observaciones_n"]:'';

// Toma las variables del formulario MODIFICACION
$factura_id_m                       = isset($_POST["factura_id_m"])?$_POST["factura_id_m"]:'';
$factura_tipo_id_m                  = isset($_POST["factura_tipo_id_m"])?$_POST["factura_tipo_id_m"]:'';
$factura_numero_m                   = isset($_POST["factura_numero_m"])?$_POST["factura_numero_m"]:'';
$factura_prioridad_id_m             = isset($_POST["factura_prioridad_id_m"])?$_POST["factura_prioridad_id_m"]:'';
$factura_pagador_id_m               = isset($_POST["factura_pagador_id_m"])?$_POST["factura_pagador_id_m"]:'';
$factura_fechaRecepcion_m           = isset($_POST["factura_fechaRecepcion_m"])?$_POST["factura_fechaRecepcion_m"]:'';
$factura_fechaEmision_m             = isset($_POST["factura_fechaEmision_m"])?$_POST["factura_fechaEmision_m"]:'';
$factura_fechaVencimiento_m         = isset($_POST["factura_fechaVencimiento_m"])?$_POST["factura_fechaVencimiento_m"]:'';
$factura_importe_medicoOrigen_m     = isset($_POST["factura_importe_medicoOrigen_m"])?$_POST["factura_importe_medicoOrigen_m"]:'';
$factura_importe_feeOrigen_m        = isset($_POST["factura_importe_feeOrigen_m"])?$_POST["factura_importe_feeOrigen_m"]:'';
$factura_moneda_id_m                = isset($_POST["factura_moneda_id_m"])?$_POST["factura_moneda_id_m"]:'';
$factura_tipoCambio_m               = isset($_POST["factura_tipoCambio_m"])?$_POST["factura_tipoCambio_m"]:'';
$factura_importeUSD_m               = isset($_POST["factura_importeUSD_m"])?$_POST["factura_importeUSD_m"]:'';
$factura_observaciones_m            = isset($_POST["factura_observaciones_m"])?$_POST["factura_observaciones_m"]:'';

// Tomas las variables del formulario AUTORIZACION
$factura_id_au                      = isset($_POST["factura_id_au"])?$_POST["factura_id_au"]:'';
$factura_estado_id_au               = isset($_POST["factura_estado_id_au"])?$_POST["factura_estado_id_au"]:'';
// Campos facturaLog
$factura_fechaPago_auto             = isset($_POST["factura_fechaPago_auto"])?$_POST["factura_fechaPago_auto"]:'';
$factura_formaPago_auto             = isset($_POST["factura_formaPago_auto"])?$_POST["factura_formaPago_auto"]:'';
$factura_observaciones_auto         = isset($_POST["factura_observaciones_auto"])?$_POST["factura_observaciones_auto"]:'';

// Toma las variables del BUSCADOR
$caso_numero_b                      = isset($_POST["caso_numero_b"])?$_POST["caso_numero_b"]:'';

// Toma variables generales
$caso_id                            = isset($_POST["caso_id"])?$_POST["caso_id"]:'';
$factura_id                         = isset($_POST["factura_id"])?$_POST["factura_id"]:'';

// Definición del ABML (Alta, Baja, Modificacion, Lectura -- objeto_ABML)
$opcion                             = isset($_POST["opcion"])?$_POST["opcion"]:'';


// Case
switch($opcion){
    
    // Acciones de los formularios
    case 'formulario_alta':
        formulario_alta($factura_caso_id_n,
                        $factura_tipo_id_n,
                        $factura_numero_n,
                        $factura_prioridad_id_n,
                        $factura_pagador_id_n,
                        $factura_fechaRecepcion_n,
                        $factura_fechaEmision_n,
                        $factura_fechaVencimiento_n,
                        $factura_importe_medicoOrigen_n,
                        $factura_importe_feeOrigen_n,
                        $factura_moneda_id_n,
                        $factura_tipoCambio_n,
                        $factura_importeUSD_n,
                        $factura_observaciones_n,
                        $sesion_usuario_id);
        break;
        
    case 'formulario_lectura':
        formulario_lectura($factura_id);
        break;
    
    case 'formulario_pago':
        formulario_pago($factura_id_au,
                        $factura_fechaPago_auto,
                        $factura_formaPago_auto,
                        $factura_observaciones_auto);
        break;
    
    // Acciones auxiliares en el formulario
    case 'datos_caso':
        datos_caso($caso_numero_b);
        break;
    
    case 'facturas_pendiente_autorizar':
        facturas_pendiente_autorizar($factura_id);
        break;
    
    // Select - Formulario Alta Casos
    case 'listar_tiposFacturas_alta':
        listar_tiposFacturas_alta();
        break;

    case 'listar_facturasPrioridades_alta':
        listar_facturasPrioridades_alta();
        break;

    case 'listar_facturasPagador_alta':
        listar_facturasPagador_alta($caso_id);
        break;

    case 'listar_facturasMonedas_alta':
        listar_facturasMonedas_alta();
        break;
    
    case 'listar_motivosRechazo_alta':
        listar_motivosRechazo_alta();
        break;
    
    case 'listar_tiposFacturas_modificacion':
        listar_tiposFacturas_modificacion($factura_id);
        break;

    case 'listar_facturasPrioridades_modificacion':
        listar_facturasPrioridades_modificacion($factura_id);
        break;

    case 'listar_facturasPagador_modificacion':
        listar_facturasPagador_modificacion($factura_id);
        break;

    case 'listar_facturasMonedas_modificacion':
        listar_facturasMonedas_modificacion($factura_id);
        break; 

    case 'listar_formasPagos_autorizacion':
        listar_formasPagos_autorizacion();
        break;
    
    // Case grillas
    case 'grilla_listar_servicios':
        grilla_listar_servicios($caso_id, $permisos);
        break; 
    
    case 'grilla_listar_facturas':
        grilla_listar_facturas($caso_id);
        break; 
    
    case 'listar_facturas_pendientes_autorizar':
        listar_facturas_pendientes_autorizar($caso_id, $permisos);
        break;
    
    case 'grilla_listar_log_factura':
        grilla_listar_log_factura($factura_id);
        break;
    
    default:
       echo("Está mal seleccionada la funcion");
}

// Funciones de Formulario
function formulario_alta($factura_caso_id_n,
                        $factura_tipo_id_n,
                        $factura_numero_n,
                        $factura_prioridad_id_n,
                        $factura_pagador_id_n,
                        $factura_fechaRecepcion_n,
                        $factura_fechaEmision_n,
                        $factura_fechaVencimiento_n,
                        $factura_importe_medicoOrigen_n,
                        $factura_importe_feeOrigen_n,
                        $factura_moneda_id_n,
                        $factura_tipoCambio_n,
                        $factura_importeUSD_n,
                        $factura_observaciones_n,
                        $sesion_usuario_id) {
    
    
    $resultado_insert = Facturacion_old::insertar($factura_caso_id_n,
                                    $factura_tipo_id_n,
                                    $factura_numero_n,
                                    $factura_prioridad_id_n,
                                    $factura_pagador_id_n,
                                    $factura_fechaRecepcion_n,
                                    $factura_fechaEmision_n,
                                    $factura_fechaVencimiento_n,
                                    $factura_importe_medicoOrigen_n,
                                    $factura_importe_feeOrigen_n,
                                    $factura_moneda_id_n,
                                    $factura_tipoCambio_n,
                                    $factura_importeUSD_n,
                                    $factura_observaciones_n,
                                    $sesion_usuario_id);
    
    $factura_id = $resultado_insert;
    
    // LOG Facturacion
    $facturaLog_factura_id          = $factura_id;
    $facturaLog_usuario_id          = $sesion_usuario_id;
    $facturaLog_fechaEvento         = date("Y-m-d H:i:s");
    $facturaLog_facturaLogEstado_id = 1;
    
    $resultado = Facturacion_old::insertar_log_facturacion($facturaLog_factura_id,
                                                        $facturaLog_usuario_id,
                                                        $facturaLog_fechaEvento,
                                                        $facturaLog_facturaLogEstado_id);
    echo json_encode($resultado);
}


function formulario_lectura($factura_id){
    
    $factura = Facturacion_old::buscarPorId($factura_id);
    
    echo json_encode($factura);
}


function formulario_pago($factura_id_au,
                         $factura_fechaPago_auto,
                         $factura_formaPago_auto,
                         $factura_observaciones_auto) {
    
    
    $resultado = Facturacion_old::pagar($factura_id_au,
                                        $factura_fechaPago_auto,
                                        $factura_formaPago_auto,
                                        $factura_observaciones_auto);
    
    echo json_encode($resultado);
}


function datos_caso($caso_numero_b) {
    
    $resultado = Caso::mostrar_info_para_facturacion($caso_numero_b);
    
    echo json_encode($resultado);
}


function facturas_pendiente_autorizar($factura_id) {
    
    $resultado = Facturacion_old::info_pendientes_autorizar($factura_id);
    
    echo json_encode($resultado);
}


// Select - Formularios Alta Casos
function listar_tiposFacturas_alta(){

    $tipos_facturas = Facturacion_old::listar_tiposFacturas_alta();

    echo json_encode($tipos_facturas);   
}

function listar_facturasPrioridades_alta(){

    $facturas_prioridades = Facturacion_old::listar_facturasPrioridades_alta();

    echo json_encode($facturas_prioridades);   
}

function listar_facturasPagador_alta($caso_id){

    $facturas_pagadores = Cliente::listar_modificacion_casos($caso_id); // Se usa ese metodo en Cliente ya que cumple la misma funcion

    echo json_encode($facturas_pagadores);   
}

function listar_facturasMonedas_alta(){

    $monedas = Facturacion_old::listar_facturasMonedas_alta();

    echo json_encode($monedas);
}


function listar_motivosRechazo_alta(){

    $motivosRechazo = Facturacion_old::listar_motivosRechazo_alta();

    echo json_encode($motivosRechazo);
}

function listar_tiposFacturas_modificacion($factura_id){

    $tipos_facturas = Facturacion_old::listar_tiposFacturas_modificacion($factura_id);

    echo json_encode($tipos_facturas);   
}

function listar_facturasPrioridades_modificacion($factura_id){

    $facturas_prioridades = Facturacion_old::listar_facturasPrioridades_modificacion($factura_id);

    echo json_encode($facturas_prioridades);   
}

function listar_facturasPagador_modificacion($factura_id){

    $facturas_pagadores = Cliente::listar_facturasPagador_modificacion($factura_id);

    echo json_encode($facturas_pagadores);   
}

function listar_facturasMonedas_modificacion($factura_id){

    $monedas = Facturacion_old::listar_facturasMonedas_modificacion($factura_id);

    echo json_encode($monedas);
}

function listar_formasPagos_autorizacion(){

    $formas_pagos = Facturacion_old::listar_formasPagos_autorizacion();

    echo json_encode($formas_pagos);
}


// Funciones de Grilla
//
// Grilla Servicios
function grilla_listar_servicios($caso_id, $permisos){

    $servicios  = Servicio::listar_para_facturacion($caso_id);

    $grilla = "<div class='row'>";
    $grilla .= "<div id='grilla_abm' class='col-sm-12'>";
    $grilla .=  "<div class='card-box table-responsive'>";
    $grilla .=      "<h4 class='m-t-0 header-title'><b>Servicios</b></h4>";
    $grilla .=      "<table id='dt_facturacion_servicios' class='table table-hover table-striped m-0 table-responsive'>";
    $grilla .=          "<thead>";
    $grilla .=              "<tr>";
    $grilla .=                  "<th>Fecha del Servicio</th>";
    $grilla .=                  "<th>Prestador</th>";
    $grilla .=                  "<th>Tipo</th>";
    $grilla .=                  "<th>Práctica</th>";
    $grilla .=                  "<th>Presunto USD</th>";
    $grilla .=                  "<th>Facturar</th>";
    $grilla .=              "</tr>";
    $grilla .=          "</thead>";
    $grilla .=          "<tbody>";  
    foreach($servicios as $servicio) {
            $fechaServicio = $servicio["fechaServicio"];
            $prestador = $servicio["prestador"];
            $tipoPrestador = $servicio["tipoPrestador"];
            $practica = $servicio["practica"];
            $presuntoUSD = $servicio["presuntoUSD"]; 
    $grilla .=              "<tr>";
    $grilla .=                  "<td>";
    $grilla .=                      $fechaServicio;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $prestador;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $tipoPrestador;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $practica;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $presuntoUSD;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $presuntoUSD;
    $grilla .=                  "</td>";    
    $grilla .=              "</tr>";
    }
    $grilla .=          "</tbody>";
    $grilla .=       "</table>";
    $grilla .=       "<div class='form-group text-right m-b-0'>";
    $grilla .=       "</div>";            
    $grilla .=    "</div>";
    $grilla .=  "</div>";        
    $grilla .="</div>";   

    echo $grilla;
}

// Grilla Facturas
function grilla_listar_facturas($caso_id){
    
    $facturas  = Facturacion_old::listar($caso_id);

    $grilla = "<div class='row'>";
    $grilla .= "<div id='grilla_abm' class='col-sm-12'>";
    $grilla .=  "<div class='card-box table-responsive'>";
    $grilla .=      "<h4 class='m-t-0 header-title'><b>Facturas</b></h4>";
    $grilla .=      "<table id='dt_facturacion_facturas' class='table table-hover table-striped m-0 table-responsive'>";
    $grilla .=          "<thead>";
    $grilla .=              "<tr>";
    $grilla .=                  "<th>Estado</th>";
    $grilla .=                  "<th>Tipo</th>";
    $grilla .=                  "<th>Numero</th>";
    $grilla .=                  "<th>Prioridad</th>";
    $grilla .=                  "<th>Pagador</th>";
    $grilla .=                  "<th>Costo Médico</th>";
    $grilla .=                  "<th>Fee</th>";
    $grilla .=                  "<th>Importe USD</th>";
    $grilla .=                  "<th>Importe Aprobado USD</th>";

    $grilla .=                  "<th>Acciones</th>";
    $grilla .=              "</tr>";
    $grilla .=          "</thead>";
    $grilla .=          "<tbody>";  
    foreach($facturas as $factura) {
            $factura_id         = $factura["idFactura"];
            $tipoFactura        = $factura["tipoFactura"];
            $numeroFactura      = $factura["numeroFactura"];
            $estadoFacturaId    = $factura["estadoFacturaId"];
            $estadoFactura      = $factura["estadoFactura"];
            $prioridadFactura   = $factura["prioridadFactura"];
            $cliente            = $factura["cliente"];
            $costoMedico        = $factura["costoMedico"];
            $fee                = $factura["fee"];
            $importeUSD         = $factura["importeUSD"];
            $importeAprobadoUSD = $factura["importeAprobadoUSD"]; 

    $grilla .=              "<tr>";
    $grilla .=                  "<td>";
    if ($estadoFacturaId == 1) { // Estados Pendiente autorizar
        $grilla .=                 "<span class='label label-default'>" . $estadoFactura . "</span>";
    } else if ($estadoFacturaId == 2) { // Estados Autorizada
        $grilla .=                 "<span class='label label-success'>" . $estadoFactura . "</span>";
    } else if ($estadoFacturaId == 3) { // Estado Rechazada
        $grilla .=                 "<span class='label label-danger'>" . $estadoFactura . "</span>";
    } else if ($estadoFacturaId == 4) { // Estado Pagada
        $grilla .=                 "<span class='label label-primary'>" . $estadoFactura . "</span>";
    }
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $tipoFactura;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $numeroFactura;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $prioridadFactura;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $cliente;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $costoMedico;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $fee;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $importeUSD;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $importeAprobadoUSD;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      "<a href='javascript:void(0)'> <i onclick='formulario_lectura($factura_id)' class='fa fa-search' data-toggle='tooltip' data-placement='top' title='Ver factura'></i></a>";
	if (Usuario::puede("fci_auto_pago") == 1 && $estadoFacturaId == 2) {
        /* 
            $grilla .=                      "<a href='javascript:void(0)'> <i onclick='pagar_facturas($factura_id)' class='fa fa-check-square-o' data-toggle='tooltip' data-placement='top' title='Pagar factura'></i></a>";
        */
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


// Grilla Facturas pendientes autorizar
function listar_facturas_pendientes_autorizar($caso_id, $permisos){
    
    $facturas  = Facturacion_old::listar_pendientes_autorizar($caso_id);

    $grilla = "<div class='row'>";
    $grilla .= "<div id='grilla_abm' class='col-sm-12'>";
    $grilla .=  "<div class='card-box table-responsive'>";
    $grilla .=      "<h4 class='m-t-0 header-title'><b>Facturas Pendientes Autorizar</b></h4>";
    $grilla .=      "<table id='dt_facturas_pendientes_autorizar' class='table table-hover table-striped m-0 table-responsive'>";
    $grilla .=          "<thead>";
    $grilla .=              "<tr>";
    $grilla .=                  "<th>Estado</th>";
    $grilla .=                  "<th>Tipo</th>";
    $grilla .=                  "<th>Numero</th>";
    $grilla .=                  "<th>Prioridad</th>";
    $grilla .=                  "<th>Pagador</th>";
    $grilla .=                  "<th>Presunto USD</th>";
    $grilla .=                  "<th>Acciones</th>";
    $grilla .=              "</tr>";
    $grilla .=          "</thead>";
    $grilla .=          "<tbody>";  
    foreach($facturas as $factura) {
            $factura_id = $factura["idFactura"];
            $tipoFactura = $factura["tipoFactura"];
            $numeroFactura = $factura["numeroFactura"];
            $estadoFacturaId = $factura["estadoFacturaId"];
            $estadoFactura = $factura["estadoFactura"];
            $prioridadFactura = $factura["prioridadFactura"];
            $cliente = $factura["cliente"];
            $presuntoUSD = $factura["presuntoUSD"]; 
    $grilla .=              "<tr>";
    $grilla .=                  "<td>";
    if ($estadoFacturaId == 1 || $estadoFacturaId == 2 || $estadoFacturaId == 3 || $estadoFacturaId == 4) { // Estados Pendiente
        $grilla .=                 "<span class='label label-warning'>" . $estadoFactura . "</span>";
    } else if ($estadoFacturaId == 5) { // Estado Rechazada
        $grilla .=                 "<span class='label label-danger'>" . $estadoFactura . "</span>";
    } else if ($estadoFacturaId == 6) { // Estado Cerrada
        $grilla .=                 "<span class='label label-success'>" . $estadoFactura . "</span>";
    }
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $tipoFactura;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $numeroFactura;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $prioridadFactura;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $cliente;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $presuntoUSD;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      "<a href='javascript:void(0)'> <i onclick='formulario_lectura($factura_id)' class='fa fa-search' data-toggle='tooltip' data-placement='top' title='Ver factura'></i></a>";
    // Botones de AUTORIZACION y PAGO
    if ($estadoFacturaId == 1) { // Si el estado de la factura es: Pend. Auditoria Facturación (id. 1)
        // Muestra si: Tiene permisos de AUTORIZACION ADMINISTRACION 
        $facturas_autorizacion_administracion = array_search('facturas_autorizacion_administracion', array_column($permisos, 'permiso_variable'));
        if (!empty($facturas_autorizacion_administracion) || ($facturas_autorizacion_administracion === 0)) {
            $grilla .=  "<a href='javascript:void(0)'> <i onclick='autorizar_facturas($factura_id)' class='fa fa-edit' data-toggle='tooltip' data-placement='top' title='Autorizar Factura'></i></a>";
        }      
    } else if ($estadoFacturaId == 2) { // Si el estado de la factura es: Pend. Auditoría Médica (id. 2)
        // Muestra si: Tiene permisos de AUTORIZACION MEDICA
        $facturas_autorizacion_medica = array_search('facturas_autorizacion_medica', array_column($permisos, 'permiso_variable')); 
        if (!empty($facturas_autorizacion_medica) || ($facturas_autorizacion_medica === 0)) {
            $grilla .=  "<a href='javascript:void(0)'> <i onclick='autorizar_facturas($factura_id)' class='fa fa-edit' data-toggle='tooltip' data-placement='top' title='Autorizar Factura'></i></a>";
        }
    } else if ($estadoFacturaId == 3) { // Si el estado de la factura es: Pend. Auditoría Finanzas (id. 3)
        // Muestra si: Tiene permisos de AUTORIZACION FINANZAS
        $facturas_autorizacion_finanzas = array_search('facturas_autorizacion_finanzas', array_column($permisos, 'permiso_variable')); 
        if (!empty($facturas_autorizacion_finanzas) || ($facturas_autorizacion_finanzas === 0)) {
            $grilla .=  "<a href='javascript:void(0)'> <i onclick='autorizar_facturas($factura_id)' class='fa fa-edit' data-toggle='tooltip' data-placement='top' title='Autorizar Factura'></i></a>";
        }
    } else if ($estadoFacturaId == 4) { // Si el estado de la factura es: Pend. Pago (id. 4)
        // Muestra si: Tiene permisos de PAGO
        $facturas_pago = array_search('facturas_pago', array_column($permisos, 'permiso_variable')); 
        if (!empty($facturas_pago) || ($facturas_pago === 0)) {
            $grilla .=  "<a href='javascript:void(0)'> <i onclick='autorizar_facturas($factura_id)' class='fa fa-edit' data-toggle='tooltip' data-placement='top' title='Autorizar Factura'></i></a>";
        } 
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


// Grilla Log Facturas
function grilla_listar_log_factura($factura_id){
    
    $logFacturas            = Facturacion_old::listar_log_factura($factura_id);
    
    $logFacturas_contador   = Facturacion_old::contar_log_factura($factura_id);
    $logFactura_numero      = $logFacturas_contador["facturaLog_cantidad"] + 1;
    
    
    $grilla = '<div class="row">';
    $grilla .=   '<div class="col-sm-12">';
    $grilla .=       '<div class="timeline">';
    $grilla .=           '<article class="timeline-item alt">';
    $grilla .=               '<div class="text-right">';
    $grilla .=                   '<div class="time-show first">';
    $grilla .=                       '<a href="#" class="btn btn-primary w-lg">Factura</a>';
    $grilla .=                   '</div>';
    $grilla .=               '</div>';
    $grilla .=           '</article> ';  
    
    foreach($logFacturas as $logFactura) {
            $logFactura_numero = $logFactura_numero - 1;
            $fechaEventoLogFactura = $logFactura["fechaEventoLogFactura"];
            $usuarioLogFactura = $logFactura["usuarioNombreLogFactura"] . ' ' . $logFactura["usuarioApellidoLogFactura"];
            $estadoLogFacturaId = $logFactura["estadoLogFacturaId"];
            $estadoLogFacturaDescripcion = $logFactura["estadoLogFacturaDescripcion"];
            $motivoRechazoLogFactura = $logFactura["motivoRechazoLogFactura"];
            $observacionesLogFactura = $logFactura["observacionesLogFactura"]; 
            $fechaPagoLogFactura = $logFactura["fechaPagoLogFactura"];
            $formaPagoLogFactura = $logFactura["formaPagoLogFactura"];
            $importeAprobadoUSDFactura = $logFactura["importeAprobadoUSDFactura"];
            
        // Setea la posicion del cuadro dependiendo si es par o impar (aplica solo para la presentacion grafica)
        if ($logFactura_numero % 2 == 0) {
            $article_pos = '<article class="timeline-item alt">';
            $span_post = '<span class="arrow-alt panel-logFacturas"></span>';
        } else {
            $article_pos = '<article class="timeline-item">';
            $span_post = '<span class="arrow"></span>';
        }

        // Segun el estado de log es el color que se define para el texto
        if ($estadoLogFacturaId == 1) { // Ingresada
            $text_color = 'text-primary';
        } else if ($estadoLogFacturaId == 2 || $estadoLogFacturaId == 4 || $estadoLogFacturaId == 6) { // Aprobada
            $text_color = 'text-success';
        } else if ($estadoLogFacturaId == 3 || $estadoLogFacturaId == 5 || $estadoLogFacturaId == 7) { // Rechazada
            $text_color = 'text-danger';
        } else if ($estadoLogFacturaId == 8) { // Pagada
            $text_color = 'text-primary';
        }

        // Armar texto del log
        $texto = '<b class="' . $text_color . '">' . $estadoLogFacturaDescripcion . '</b><br>';
        if ($importeAprobadoUSDFactura > 0)   {
        $texto .= ' <b>Importe Aprobado USD:</b> ' . $importeAprobadoUSDFactura . '<br>'; 
        }
        if ($motivoRechazoLogFactura != NULL)   {
        $texto .= ' <b>Motivo rechazo:</b> ' . $motivoRechazoLogFactura . '<br>'; 
        }
        if ($observacionesLogFactura != NULL) {
        $texto .= ' <b>Observaciones:</b> ' . $observacionesLogFactura . '<br>';    
        }
        if ($fechaPagoLogFactura != NULL && $formaPagoLogFactura != NULL) {
        $texto .= ' <b>Fecha de pago:</b> ' . $fechaPagoLogFactura . ' <b>Forma de pago:</b> ' . $formaPagoLogFactura;    
        }
        
        $fecha = date('d M Y - h:m', strtotime($fechaEventoLogFactura));
        
        // Inicio de timeline
        $grilla .= $article_pos;        
        $grilla .=  '<div class="timeline-desk">';
        $grilla .=      '<div class="panel">';
        $grilla .=          '<div class="panel-body">';
        $grilla .=              $span_post;
        $grilla .=              '<span class="timeline-icon"></span>';
        $grilla .=              '<h4 class="text-default">' . $usuarioLogFactura . '</h4>';
        $grilla .=              '<p class="timeline-date text-muted"><small>' . $fecha . '</small></p>';
        $grilla .=              '<p>' . $texto . '</p>';
        $grilla .=          '</div>';
        $grilla .=      '</div>';
        $grilla .=  '</div>';
        $grilla .= '</article>';
    }               
    $grilla .=      "</div>";
    $grilla .=  "</div>";
    $grilla .=  "<div class='col-sm-12'>";
    $grilla .=      "<div class='card-box text-right'>";
    $grilla .=          "<button type='button' onclick='btn_cerrar_log_factura()' class='btn btn-inverse waves-effect waves-light'>Cerrar</button>";
    $grilla .=      "</div>";
    $grilla .=  "</div>";   
    $grilla .="</div>";

    echo $grilla;
}