<?php
require '../seguridad/seguridad.php';
include_once '../includes/herramientas.php';


// Toma las variables del formulario ALTA FACTURA
$factura_prestador_id_n             = isset($_POST["factura_prestador_id_n"]) ? $_POST["factura_prestador_id_n"] : '';
$factura_numero_n                   = isset($_POST["factura_numero_n"]) ? $_POST["factura_numero_n"] : '';
$factura_prioridad_id_n             = isset($_POST["factura_prioridad_id_n"]) ? $_POST["factura_prioridad_id_n"] : '';
$factura_fechaRecepcion_n           = isset($_POST["factura_fechaRecepcion_n"]) ? $_POST["factura_fechaRecepcion_n"] : '';
$factura_fechaEmision_n             = isset($_POST["factura_fechaEmision_n"]) ? $_POST["factura_fechaEmision_n"] : '';
$factura_fechaVencimiento_n         = isset($_POST["factura_fechaVencimiento_n"]) ? $_POST["factura_fechaVencimiento_n"] : '';
$factura_observaciones_n            = isset($_POST["factura_observaciones_n"]) ? $_POST["factura_observaciones_n"] : '';

// Toma las variables del formulario MODIFICACION FACTURA
$factura_id_m                       = isset($_POST["factura_id_m"]) ? $_POST["factura_id_m"] : '';
$factura_prestador_id_m             = isset($_POST["factura_prestador_id_m"]) ? $_POST["factura_prestador_id_m"] : '';
$factura_numero_m                   = isset($_POST["factura_numero_m"]) ? $_POST["factura_numero_m"] : '';
$factura_prioridad_id_m             = isset($_POST["factura_prioridad_id_m"]) ? $_POST["factura_prioridad_id_m"] : '';
$factura_fechaRecepcion_m           = isset($_POST["factura_fechaRecepcion_m"]) ? $_POST["factura_fechaRecepcion_m"] : '';
$factura_fechaEmision_m             = isset($_POST["factura_fechaEmision_m"]) ? $_POST["factura_fechaEmision_m"] : '';
$factura_fechaVencimiento_m         = isset($_POST["factura_fechaVencimiento_m"]) ? $_POST["factura_fechaVencimiento_m"] : '';
$factura_observaciones_m            = isset($_POST["factura_observaciones_m"]) ? $_POST["factura_observaciones_m"] : '';

// Toma las variables del formulario ALTA ITEM FACTURA
$fci_caso_id_n                      = isset($_POST["fci_caso_id_n"]) ? $_POST["fci_caso_id_n"] : '';
$fci_factura_id_n                   = isset($_POST["fci_factura_id_n"]) ? $_POST["fci_factura_id_n"] : '';
$fci_caso_n                         = isset($_POST["fci_caso_n"]) ? $_POST["fci_caso_n"] : '';
$fci_seleccionados                  = isset($_POST["fci_seleccionados"]) ? $_POST["fci_seleccionados"] : '';
$fci_pagador_id_n                   = isset($_POST["fci_pagador_id_n"]) ? $_POST["fci_pagador_id_n"] : '';
$fci_imp_medicoOrigen_n             = isset($_POST["fci_imp_medicoOrigen_n"]) ? $_POST["fci_imp_medicoOrigen_n"] : '';
$fci_imp_feeOrigen_n                = isset($_POST["fci_imp_feeOrigen_n"]) ? $_POST["fci_imp_feeOrigen_n"] : '';
$fci_descuento_n                    = isset($_POST["fci_descuento_n"]) ? $_POST["fci_descuento_n"] : '';
$fci_moneda_id_n                    = isset($_POST["fci_moneda_id_n"]) ? $_POST["fci_moneda_id_n"] : '';
$fci_tipoCambio_n                   = isset($_POST["fci_tipoCambio_n"]) ? $_POST["fci_tipoCambio_n"] : '';

// Toma las variables del formulario MODIFICACION ITEM FACTURA
//$fci_caso_id_m                      = isset($_POST["fci_caso_id_m"])?$_POST["fci_caso_id_m"]:'';
$fci_factura_id_m                   = isset($_POST["fci_factura_id_m"]) ? $_POST["fci_factura_id_m"] : '';
$fci_caso_m                         = isset($_POST["fci_caso_m"]) ? $_POST["fci_caso_m"] : '';
$fci_seleccionados_m                = isset($_POST["fci_seleccionados_m"]) ? $_POST["fci_seleccionados_m"] : '';
$fci_seleccionados_b                = isset($_POST["fci_seleccionados_b"]) ? $_POST["fci_seleccionados_b"] : '';
$fci_pagador_id_m                   = isset($_POST["fci_pagador_id_m"]) ? $_POST["fci_pagador_id_m"] : '';
$fci_imp_medicoOrigen_m             = isset($_POST["fci_imp_medicoOrigen_m"]) ? $_POST["fci_imp_medicoOrigen_m"] : '';
$fci_imp_feeOrigen_m                = isset($_POST["fci_imp_feeOrigen_m"]) ? $_POST["fci_imp_feeOrigen_m"] : '';
$fci_descuento_m                    = isset($_POST["fci_descuento_m"]) ? $_POST["fci_descuento_m"] : '';
$fci_moneda_id_m                    = isset($_POST["fci_moneda_id_m"]) ? $_POST["fci_moneda_id_m"] : '';
$fci_tipoCambio_m                   = isset($_POST["fci_tipoCambio_m"]) ? $_POST["fci_tipoCambio_m"] : '';

// Tomas las variables del formulario AUTORIZACION ITEM FACTURA
$fci_id_au                          = isset($_POST["fci_id_au"]) ? $_POST["fci_id_au"] : '';
$fci_estado_id_au                   = isset($_POST["fci_estado_id_au"]) ? $_POST["fci_estado_id_au"] : '';
$fci_importe_aprobadoUSD_au         = isset($_POST["fci_importe_aprobadoUSD_au"]) ? $_POST["fci_importe_aprobadoUSD_au"] : '';
/* Campos facturaMov */
$fci_mov_auditoria_auto_id          = isset($_POST["fci_mov_auditoria_auto_id"]) ? $_POST["fci_mov_auditoria_auto_id"] : '';
$fci_importeAprobadoUSD_auto        = isset($_POST["fci_importeAprobadoUSD_auto"]) ? $_POST["fci_importeAprobadoUSD_auto"] : '';
$fci_mov_motivoRechazo_auto_id      = isset($_POST["fci_mov_motivoRechazo_auto_id"]) ? $_POST["fci_mov_motivoRechazo_auto_id"] : '';
$fci_fechaPago_auto                 = isset($_POST["fci_fechaPago_auto"]) ? $_POST["fci_fechaPago_auto"] : '';
$fci_formaPago_auto                 = isset($_POST["fci_formaPago_auto"]) ? $_POST["fci_formaPago_auto"] : '';
$fci_observaciones_auto             = isset($_POST["fci_observaciones_auto"]) ? $_POST["fci_observaciones_auto"] : '';

// Toma las variables del BUSCADOR FACTURAS
$factura_numero_buscar              = isset($_POST["factura_numero_buscar"]) ? $_POST["factura_numero_buscar"] : '';
$factura_final_buscar              = isset($_POST["factura_final_buscar"]) ? $_POST["factura_final_buscar"] : '';
$fc_caso_numero_buscar              = isset($_POST["fc_caso_numero_buscar"]) ? $_POST["fc_caso_numero_buscar"] : '';
$factura_prestador_buscar           = isset($_POST["factura_prestador_buscar"]) ? $_POST["factura_prestador_buscar"] : '';

// Toma texto introducido en prestador para el autocomplete
$prestador                          = isset($_POST["prestador"]) ? $_POST["prestador"] : '';
// Busqueda de PRESTADOR
$prestador_nombre_buscar            = isset($_POST["prestador_nombre_buscar"]) ? $_POST["prestador_nombre_buscar"] : '';
$prestador_tipoPrestador_id_buscar  = isset($_POST["prestador_tipoPrestador_id_buscar"]) ? $_POST["prestador_tipoPrestador_id_buscar"] : '';
$prestador_pais_id_buscar           = isset($_POST["prestador_pais_id_buscar"]) ? $_POST["prestador_pais_id_buscar"] : '';
$prestador_ciudad_id_buscar         = isset($_POST["prestador_ciudad_id_buscar"]) ? $_POST["prestador_ciudad_id_buscar"] : '';
$caso_numero_buscar                 = isset($_POST["caso_numero_buscar"]) ? $_POST["caso_numero_buscar"] : '';

// Toma variables para el calculo de importes
$importe_origen                     = isset($_POST["importe_origen"]) ? $_POST["importe_origen"] : '';
$tipo_cambio                        = isset($_POST["tipo_cambio"]) ? $_POST["tipo_cambio"] : '';

// Toma variables generales
$caso_id                            = isset($_POST["caso_id"]) ? $_POST["caso_id"] : '';
$factura_id                         = isset($_POST["factura_id"]) ? $_POST["factura_id"] : '';
$order                              = isset($_POST["order"]) ? $_POST["order"] : '';
$pagador                            = isset($_POST["pagador"]) ? $_POST["pagador"] : '';
$factura_final                            = isset($_POST["factura_final"]) ? $_POST["factura_final"] : '';
$fci_id                             = isset($_POST["fci_id"]) ? $_POST["fci_id"] : '';
$prestador_id                       = isset($_POST["prestador_id"]) ? $_POST["prestador_id"] : '';
$pagador_id                         = isset($_POST["pagador_id"]) ? $_POST["pagador_id"] : '';
$moneda_id                          = isset($_POST["moneda_id"]) ? $_POST["moneda_id"] : '';

// Definición del ABML (Alta, Baja, Modificacion, Lectura -- objeto_ABML)
$opcion                             = isset($_POST["opcion"]) ? $_POST["opcion"] : '';


// Toma variables para nuevo pagador
$nuevo_pagador                      = isset($_POST["nuevo_pagador"]) ? $_POST["nuevo_pagador"] : '';
$nueva_factura                      = isset($_POST["nueva_factura"]) ? $_POST["nueva_factura"] : '';
$items                              = isset($_POST["items"]) ? $_POST["items"] : '';

// Case
switch ($opcion) {

        // Acciones de los formularios
    case 'nuevo_pagador':
        nuevo_pagador(
            $nuevo_pagador,
            $nueva_factura,
            $items
        );
        break;

        // Acciones de los formularios
    case 'formulario_alta':
        formulario_alta(
            $factura_prestador_id_n,
            $factura_numero_n,
            $factura_prioridad_id_n,
            $factura_fechaRecepcion_n,
            $factura_fechaEmision_n,
            $factura_fechaVencimiento_n,
            $factura_observaciones_n,
            $sesion_usuario_id
        );
        break;

    case 'formulario_lectura':
        formulario_lectura($factura_id);
        break;

    case 'formulario_modificacion':
        formulario_modificacion(
            $factura_prestador_id_m,
            $factura_numero_m,
            $factura_prioridad_id_m,
            $factura_fechaRecepcion_m,
            $factura_fechaEmision_m,
            $factura_fechaVencimiento_m,
            $factura_observaciones_m,
            $factura_id_m
        );
        break;

    case 'formulario_alta_fci':
        formulario_alta_fci(
            $fci_caso_id_n,
            $fci_factura_id_n,
            $fci_caso_n,
            $fci_seleccionados,
            $fci_pagador_id_n,
            $fci_imp_medicoOrigen_n,
            $fci_imp_feeOrigen_n,
            $fci_descuento_n,
            $fci_moneda_id_n,
            $fci_tipoCambio_n,
            $sesion_usuario_id
        );
        break;

    case 'formulario_lectura_fci':
        formulario_lectura_fci($fci_id);
        break;

    case 'formulario_modificacion_fci':
        formulario_modificacion_fci( //$fci_caso_id_m,
            $fci_factura_id_m,
            $fci_caso_m,
            $fci_seleccionados_m,
            $fci_seleccionados_b,
            $fci_pagador_id_m,
            $fci_imp_medicoOrigen_m,
            $fci_imp_feeOrigen_m,
            $fci_descuento_m,
            $fci_moneda_id_m,
            $fci_tipoCambio_m,
            $sesion_usuario_id,
            $fci_id
        );
        break;

    case 'formulario_autorizacion_fci':
        formulario_autorizacion_fci(
            $fci_id_au,
            $fci_mov_auditoria_auto_id,
            $fci_mov_motivoRechazo_auto_id,
            $fci_fechaPago_auto,
            $fci_formaPago_auto,
            $fci_observaciones_auto,
            $sesion_usuario_id,
            $fci_importeAprobadoUSD_auto
        );
        break;

        // Acciones auxiliares en el formulario
    case 'factura_existente_alta':
        factura_existente_alta($factura_numero_n, $factura_prestador_id_n);
        break;

    case 'fci_pendiente_autorizar':
        fci_pendiente_autorizar($fci_id);
        break;

    case 'pagar_factura':
        pagar_factura($factura_id, $sesion_usuario_id);
        break;

    case 'valida_estado_items':
        valida_estado_items($factura_id);
        break;

        // Select - Formulario Alta Facturas
    case 'select_prestadores':
        select_prestadores($prestador);
        break;

    case 'listar_tiposFacturas_alta':
        listar_tiposFacturas_alta();
        break;

    case 'listar_facturasPrioridades_alta':
        listar_facturasPrioridades_alta();
        break;

    case 'listar_fciPagador_alta':
        listar_fciPagador_alta($caso_id);
        break;

    case 'listar_fciPagador_modificacion':
        listar_fciPagador_modificacion($pagador_id);
        break;

    case 'listar_fciMonedas_alta':
        listar_fciMonedas_alta();
        break;

    case 'listar_fciMonedas_modificacion':
        listar_fciMonedas_modificacion($moneda_id);
        break;

    case 'listar_movEstados_alta':
        listar_movEstados_alta($fci_estado_id_au, $fci_importe_aprobadoUSD_au);
        break;

    case 'listar_motivosRechazo_alta':
        listar_motivosRechazo_alta();
        break;

    case 'listar_formasPagos_autorizacion':
        listar_formasPagos_autorizacion();
        break;

    case 'calcular_importe_usd':
        calcular_importe_usd($importe_origen, $tipo_cambio);
        break;


        // Select - Formulario Modificacion Facturas
    case 'listar_tiposFacturas_modificacion':
        listar_tiposFacturas_modificacion($factura_id);
        break;

    case 'listar_facturasPrioridades_modificacion':
        listar_facturasPrioridades_modificacion($factura_id);
        break;

    case 'listar_facturasPagador_modificacion':
        listar_facturasPagador_modificacion($factura_id);
        break;

        // Select - Busqueda Facturas
    case 'listarTipoPrestador_buscarPrestador':
        listarTipoPrestador_buscarPrestador();
        break;

        // Case grillas
    case 'grilla_listar_facturas':
        grilla_listar_facturas(
            $factura_numero_buscar,
            $factura_final_buscar,
            $fc_caso_numero_buscar,
            $factura_prestador_buscar
        );
        break;

    case 'grilla_listar_prestador_contar':
        grilla_listar_prestador_contar(
            $prestador_nombre_buscar,
            $prestador_tipoPrestador_id_buscar,
            $prestador_pais_id_buscar,
            $prestador_ciudad_id_buscar
        );
        break;

    case 'grilla_listar_prestador':
        grilla_listar_prestador(
            $prestador_nombre_buscar,
            $prestador_tipoPrestador_id_buscar,
            $prestador_pais_id_buscar,
            $prestador_ciudad_id_buscar
        );
        break;

    case 'grilla_fcItems':
        grilla_fcItems($factura_id, $permisos, $order, $pagador, $factura_final);

    case 'grilla_listar_servicios':
        grilla_listar_servicios($caso_numero_buscar, $prestador_id);
        break;

    case 'grilla_servicios_asignadosPorItem':
        grilla_servicios_asignadosPorItem(
            $fci_id,
            $caso_id,
            $prestador_id
        );
        break;

    case 'grilla_listar_mov_fci':
        grilla_listar_mov_fci($fci_id);
        break;

    case 'fci_datos_caso':
        fci_datos_caso($fci_id);
        break;

    case 'grilla_listar_servicios_fci':
        grilla_listar_servicios_fci($fci_id);
        break;

    default:
        echo ("Está mal seleccionada la funcion");
}


// Funciones de Formulario
function formulario_alta(
    $factura_prestador_id_n,
    $factura_numero_n,
    $factura_prioridad_id_n,
    $factura_fechaRecepcion_n,
    $factura_fechaEmision_n,
    $factura_fechaVencimiento_n,
    $factura_observaciones_n,
    $sesion_usuario_id
) {


    $factura_id = Facturacion::insertar(
        $factura_prestador_id_n,
        $factura_numero_n,
        $factura_prioridad_id_n,
        $factura_fechaRecepcion_n,
        $factura_fechaEmision_n,
        $factura_fechaVencimiento_n,
        $factura_observaciones_n,
        $sesion_usuario_id
    );

    echo json_encode($factura_id);
}

function formulario_lectura($factura_id)
{

    $factura = Facturacion::buscarPorId($factura_id);

    echo json_encode($factura);
}

function formulario_modificacion(
    $factura_prestador_id_m,
    $factura_numero_m,
    $factura_prioridad_id_m,
    $factura_fechaRecepcion_m,
    $factura_fechaEmision_m,
    $factura_fechaVencimiento_m,
    $factura_observaciones_m,
    $factura_id_m
) {

    $resultado = Facturacion::actualizar(
        $factura_prestador_id_m,
        $factura_numero_m,
        $factura_prioridad_id_m,
        $factura_fechaRecepcion_m,
        $factura_fechaEmision_m,
        $factura_fechaVencimiento_m,
        $factura_observaciones_m,
        $factura_id_m
    );

    echo json_encode($resultado);
}

function nuevo_pagador(
    $nuevo_pagador,
    $nueva_factura,
    $items
) {

    $resultado = Facturacion::actualizar_pagador(
        $nuevo_pagador,
        $nueva_factura,
        $items
    );

    echo json_encode($resultado);
}

function formulario_alta_fci(
    $fci_caso_id_n,
    $fci_factura_id_n,
    $fci_caso_n,
    $fci_seleccionados,
    $fci_pagador_id_n,
    $fci_imp_medicoOrigen_n,
    $fci_imp_feeOrigen_n,
    $fci_descuento_n,
    $fci_moneda_id_n,
    $fci_tipoCambio_n,
    $sesion_usuario_id
) {

    $resultado_insert_fci = Facturacion::insertar_fci(
        $fci_caso_id_n,
        $fci_factura_id_n,
        $fci_caso_n,
        $fci_seleccionados,
        $fci_pagador_id_n,
        $fci_imp_medicoOrigen_n,
        $fci_imp_feeOrigen_n,
        $fci_descuento_n,
        $fci_moneda_id_n,
        $fci_tipoCambio_n,
        $sesion_usuario_id
    );

    echo json_encode($resultado_insert_fci);
}

function formulario_lectura_fci($fci_id)
{

    $fci = Facturacion::buscarItemPorId($fci_id);

    echo json_encode($fci);
}

function formulario_modificacion_fci( //$fci_caso_id_m,
    $fci_factura_id_m,
    $fci_caso_m,
    $fci_seleccionados_m,
    $fci_seleccionados_b,
    $fci_pagador_id_m,
    $fci_imp_medicoOrigen_m,
    $fci_imp_feeOrigen_m,
    $fci_descuento_m,
    $fci_moneda_id_m,
    $fci_tipoCambio_m,
    $sesion_usuario_id,
    $fci_id
) {

    $resultado_update_fci = Facturacion::actualizar_fci( //$fci_caso_id_m,
        $fci_factura_id_m,
        $fci_caso_m,
        $fci_seleccionados_m,
        $fci_seleccionados_b,
        $fci_pagador_id_m,
        $fci_imp_medicoOrigen_m,
        $fci_imp_feeOrigen_m,
        $fci_descuento_m,
        $fci_moneda_id_m,
        $fci_tipoCambio_m,
        $sesion_usuario_id,
        $fci_id
    );

    echo json_encode($resultado_update_fci);
}

function formulario_autorizacion_fci(
    $fci_id_au,
    $fci_mov_auditoria_auto_id,
    $fci_mov_motivoRechazo_auto_id,
    $fci_fechaPago_auto,
    $fci_formaPago_auto,
    $fci_observaciones_auto,
    $sesion_usuario_id,
    $fci_importeAprobadoUSD_auto
) {

    // Busca el tipo de cambio
    $tipoCambio = Facturacion::buscar_tipoCambio($fci_id_au);

    // Insert del movimiento
    $fciMov_fci_id                  = $fci_id_au;
    $fciMov_usuario_id              = $sesion_usuario_id;
    $fciMov_movEstado_id            = $fci_mov_auditoria_auto_id;
    $fciMov_motivoRechazo_id        = $fci_mov_motivoRechazo_auto_id;
    $fciMov_importeAprobadoUSD      = $fci_importeAprobadoUSD_auto;
    $fciMov_importeAprobadoOrigen   = $fci_importeAprobadoUSD_auto * $tipoCambio;
    $fciMov_fechaPago               = $fci_fechaPago_auto;
    $fciMov_formaPago               = $fci_formaPago_auto;
    $fciMov_observaciones           = $fci_observaciones_auto;

    $resultado = Facturacion::insertar_mov_facturacion(
        $fciMov_fci_id,
        $fciMov_usuario_id,
        $fciMov_movEstado_id,
        $fciMov_motivoRechazo_id,
        $fciMov_importeAprobadoUSD,
        $fciMov_importeAprobadoOrigen,
        $fciMov_fechaPago,
        $fciMov_formaPago,
        $fciMov_observaciones
    );

    echo json_encode($resultado);
}


// Funcion para buscar datos del caso que seran utilizados al momento de la auditoria de items de factura
function fci_datos_caso($fci_id)
{

    $resultado = Facturacion::fci_datos_caso($fci_id);

    echo json_encode($resultado);
}


// Funciones auxiliares de formulario
function select_prestadores($prestador)
{

    $prestadores = Facturacion::buscar_selectPrestador($prestador);

    $data = array();
    foreach ($prestadores as $prestador) {
        $name = $prestador['prestador_nombre'] . '|' . $prestador['prestador_id'];
        array_push($data, $name);
    }

    echo json_encode($data);
}


function factura_existente_alta($factura_numero_n, $factura_prestador_id_n)
{

    $factura_existente = Facturacion::existe_alta($factura_numero_n, $factura_prestador_id_n);

    if ($factura_existente == 1) {
        echo (json_encode("La factura que intenta ingresar ya existe"));
    } else {
        echo (json_encode("true"));
    }
}

function fci_pendiente_autorizar($fci_id)
{

    $resultado = Facturacion::info_fci_pendientes_autorizar($fci_id);

    echo json_encode($resultado);
}

function pagar_factura($factura_id, $sesion_usuario_id)
{

    $resultado = Facturacion::imputar_pago_factura($factura_id, $sesion_usuario_id);

    echo json_encode($resultado);
}

function valida_estado_items($factura_id)
{

    $array = Facturacion::valida_estado_items($factura_id);

    echo json_encode($array);
}

// Select - Formularios Alta Casos
function listar_tiposFacturas_alta()
{

    $tipos_facturas = Facturacion::listar_tiposFacturas_alta();

    echo json_encode($tipos_facturas);
}

function listar_facturasPrioridades_alta()
{

    $facturas_prioridades = Facturacion::listar_facturasPrioridades_alta();

    echo json_encode($facturas_prioridades);
}

function listar_fciPagador_alta()
{

    $facturas_pagadores = Cliente::formulario_alta_clientes();

    echo json_encode($facturas_pagadores);
}
function listar_fciPagador_modificacion($pagador_id)
{

    $facturas_pagadores = Cliente::listar_clientes_modificacionItemFactura($pagador_id); // Se usa ese metodo en Cliente ya que cumple la misma funcion

    echo json_encode($facturas_pagadores);
}

function listar_fciMonedas_alta()
{

    $monedas = Moneda::listar_form_alta();

    echo json_encode($monedas);
}

function listar_fciMonedas_modificacion($moneda_id)
{

    $monedas = Moneda::fci_listar_form_modificacion($moneda_id);

    echo json_encode($monedas);
}

function listar_movEstados_alta($fci_estado_id_au, $fci_importe_aprobadoUSD_au)
{

    $movEstados = Facturacion::listar_movEstados_alta($fci_estado_id_au, $fci_importe_aprobadoUSD_au);

    echo json_encode($movEstados);
}

function listar_motivosRechazo_alta()
{

    $motivosRechazo = Facturacion::listar_motivosRechazo_alta();

    echo json_encode($motivosRechazo);
}

function listar_tiposFacturas_modificacion($factura_id)
{

    $tipos_facturas = Facturacion::listar_tiposFacturas_modificacion($factura_id);

    echo json_encode($tipos_facturas);
}

function listar_facturasPrioridades_modificacion($factura_id)
{

    $facturas_prioridades = Facturacion::listar_facturasPrioridades_modificacion($factura_id);

    echo json_encode($facturas_prioridades);
}

function listar_facturasPagador_modificacion($factura_id)
{

    $facturas_pagadores = Cliente::listar_facturasPagador_modificacion($factura_id);

    echo json_encode($facturas_pagadores);
}

function listar_formasPagos_autorizacion()
{

    $formas_pagos = Facturacion::listar_formasPagos_autorizacion();

    echo json_encode($formas_pagos);
}

function calcular_importe_usd($importe_origen, $tipo_cambio)
{

    $calculo = $importe_origen / $tipo_cambio;

    echo json_encode($calculo);
}


// Funciones de Grilla
//
// Grilla Facturas
function grilla_listar_facturas(
    $factura_numero_buscar,
    $factura_final_buscar,
    $fc_caso_numero_buscar,
    $factura_prestador_buscar
) {

    $facturas = Facturacion::listar(
        $factura_numero_buscar,
        $factura_final_buscar,
        $fc_caso_numero_buscar,
        $factura_prestador_buscar
    );

    $grilla = "<div class='row'>";
    $grilla .= "<div id='grilla_abm' class='col-sm-12'>";
    $grilla .=  "<div class='card-box table-responsive'>";
    $grilla .=      "<h4 class='m-t-0 header-title'><b>Facturas</b></h4>";
    $grilla .=      "<table id='dt_facturacion_facturas' class='table table-hover table-striped m-0 table-responsive'>";
    $grilla .=          "<thead>";
    $grilla .=              "<tr>";
    $grilla .=                  "<th>Prestador</th>";
    $grilla .=                  "<th>Estado</th>";
    $grilla .=                  "<th>Numero</th>";
    $grilla .=                  "<th>Prioridad</th>";
    $grilla .=                  "<th>Acciones</th>";
    $grilla .=              "</tr>";
    $grilla .=          "</thead>";
    $grilla .=          "<tbody>";
    foreach ($facturas as $factura) {
        $factura_id = $factura["idFactura"];
        $prestador = $factura["prestador"];
        $estadoId = $factura["estadoId"];
        $estadoNombre = $factura["estadoNombre"];
        $numeroFactura = $factura["numeroFactura"];
        $prioridadFactura = $factura["prioridadFactura"];
        $grilla .=              "<tr>";
        $grilla .=                  "<td>";
        $grilla .=                      $prestador;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        if ($estadoId == 7) {
            $grilla .=                 "<span class='label label-primary'>$estadoNombre</span>";
        } else {
            $grilla .=                 "<span class='label label-default'>$estadoNombre</span>";
        }
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $numeroFactura;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $prioridadFactura;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      "<a href='javascript:void(0)'> <i onclick='formulario_lectura($factura_id)' class='fa fa-search' data-toggle='tooltip' data-placement='top' title='Ver factura'></i></a>";
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

// Funciones de Grilla de Busqueda de prestador
function grilla_listar_prestador_contar(
    $prestador_nombre_buscar,
    $prestador_tipoPrestador_id_buscar,
    $prestador_pais_id_buscar,
    $prestador_ciudad_id_buscar
) {

    $prestadores = Prestador::listar_filtrado_contar_enServicios(
        $prestador_nombre_buscar,
        $prestador_tipoPrestador_id_buscar,
        $prestador_pais_id_buscar,
        $prestador_ciudad_id_buscar
    );

    $cantidad = $prestadores['registros'];

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


function grilla_listar_prestador(
    $prestador_nombre_buscar,
    $prestador_tipoPrestador_id_buscar,
    $prestador_pais_id_buscar,
    $prestador_ciudad_id_buscar
) {

    $prestadores = Prestador::listar_filtrado_enServicios(
        $prestador_nombre_buscar,
        $prestador_tipoPrestador_id_buscar,
        $prestador_pais_id_buscar,
        $prestador_ciudad_id_buscar
    );

    $grilla = "<div class='row'>";
    $grilla .= "<div id='grilla_abm' class='col-sm-12'>";
    $grilla .=  "<div class='card-box table-responsive'>";
    $grilla .=      "<table class='table table-hover table-striped m-0 table-responsive'>";
    $grilla .=          "<thead>";
    $grilla .=              "<tr>";
    $grilla .=                  "<th>Nombre</th>";
    $grilla .=                  "<th>Prioridad</th>";
    $grilla .=                  "<th>Prestador tipo</th>";
    $grilla .=                  "<th>Direccion</th>";
    $grilla .=                  "<th>Pais</th>";
    $grilla .=                  "<th>Ciudad</th>";
    $grilla .=                  "<th>Acciones</th>";
    $grilla .=              "</tr>";
    $grilla .=          "</thead>";
    $grilla .=          "<tbody>";
    foreach ($prestadores as $prestador) {
        $prestador_id = $prestador["prestador_id"];
        $prestador_nombre = $prestador["prestador_nombre"];
        $prestadorPrioridad_id = $prestador["prestadorPrioridad_id"];
        $prestadorPrioridad_nombre = $prestador["prestadorPrioridad_nombre"];
        $prestadorTipo_nombre = $prestador["tipoPrestador_nombre"];
        $prestador_direccion = $prestador["prestador_direccion"];
        $prestadorPais_nombre = $prestador["pais_nombreEspanol"];
        $prestadorCiudad_nombre = $prestador["ciudad_nombre"];
        $prestador_activo = $prestador["prestador_activo"];
        $grilla .=              "<tr>";
        $grilla .=                  "<td>";
        $grilla .=                      $prestador_nombre;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        if ($prestadorPrioridad_id == 1) {
            $grilla .=                 "<span class='label label-primary'>$prestadorPrioridad_nombre</span>";
        } else {
            $grilla .=                 "<span class='label label-default'>$prestadorPrioridad_nombre</span>";
        }
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $prestadorTipo_nombre;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $prestador_direccion;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $prestadorPais_nombre;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $prestadorCiudad_nombre;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      "<a href='../prestador/prestador.php?vprovider=" . $prestador_id . "' target='_blank'> <i class='fa fa-search'data-toggle='tooltip' data-placement='top' title='Ver prestador'></i></a>";
        $grilla .=                      "<a href='javascript:void(0)'> <i onclick='asignar_prestador_factura($prestador_id,  \"" . $prestador_nombre . "\")' class='fa fa-edit' data-toggle='tooltip' data-placement='top' title='Asignar prestador'></i></a>";
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


function grilla_listar_servicios($caso_numero_buscar, $prestador_id)
{

    $servicios = Servicio::listar_servicios_autorizados($caso_numero_buscar, $prestador_id);

    $grilla = "<div id='div_grilla_servicios' class='row hidden'>";
    $grilla .= "<div class='col-sm-12'>";
    $grilla .=  "<div class='card-box table-responsive'>";
    $grilla .=      "<h4 class='m-t-0 header-title'><b>Servicios Autorizados</b></h4>";
    $grilla .=      "<table class='table table-hover table-striped m-0 table-responsive'>";
    $grilla .=          "<thead>";
    $grilla .=              "<tr>";
    $grilla .=                  "<th>Caso</th>";
    $grilla .=                  "<th>Fecha Servicio</th>";
    $grilla .=                  "<th>Prestador</th>";
    $grilla .=                  "<th>Practica</th>";
    $grilla .=                  "<th>Presunto Origen</th>";
    $grilla .=                  "<th>Moneda</th>";
    $grilla .=                  "<th>T/C</th>";
    $grilla .=                  "<th>Presunto USD</th>";
    $grilla .=                  "<th>Seleccionar</th>";
    $grilla .=              "</tr>";
    $grilla .=          "</thead>";
    $grilla .=          "<tbody>";
    foreach ($servicios as $servicio) {
        $servicio_id        = $servicio["servicioId"];
        $fecha_servicio     = $servicio["servicioFecha"];
        $prestador_id       = $servicio["prestadorId"];
        $prestador          = $servicio["prestador"];
        $practica           = $servicio["practica"];
        $presunto_origen    = $servicio["presuntoOrigen"];
        $moneda             = $servicio["moneda"];
        $tipo_cambio        = $servicio["tipoCambio"];
        $presunto_usd       = $servicio["presuntoUSD"];
        $caso_numero        = $servicio["casoNumero"];
        $caso_id            = $servicio["casoId"];
        $asignado           = $servicio["asignado"];
        $caso_cliente_id           = $servicio["caso_cliente_id"];
        $grilla .=              "<tr>";
        $grilla .=                  "<td>";
        $grilla .=                      $caso_numero;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $fecha_servicio;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $prestador;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $practica;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $presunto_origen;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $moneda;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $tipo_cambio;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $presunto_usd;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        if ($asignado == 0) {
            $grilla .=                  "<input type='checkbox' onclick='javascript:seleccion_servicios(" . $caso_id . ", " . $caso_cliente_id . ");' name='seleccionados[]' class='checkboxServicio' value='" . $servicio_id . "'/>";
        } else {
            $grilla .=                  "<span class='label label-danger'>Asignado</span>";
        }
        $grilla .=                  "</td>";
        $grilla .=              "</tr>";
    }
    $grilla .=          "</tbody>";
    $grilla .=       "</table>";
    $grilla .=    "</div>";
    $grilla .=  "</div>";
    $grilla .= "</div>";
    $grilla .= "<div class='row'>";
    $grilla .=  "<div class='col-sm-12'>";
    $grilla .=      "<div id='box_cargarServicios' class='card-box table-responsive text-right hidden'>";
    $grilla .=          "<button id='btn_cargarServicios' class='btn btn-success waves-effect waves-light m-l-5'> Cargar Servicios <i class='glyphicon glyphicon-ok' ></i></button>";
    $grilla .=      "</div>";
    $grilla .=  "</div>";
    $grilla .= "</div>";

    echo $grilla;
}

function grilla_servicios_asignadosPorItem(
    $fci_id,
    $caso_id,
    $prestador_id
) {

    $servicios = Servicio::listar_servicios_asignadosPorItem(
        $fci_id,
        $caso_id,
        $prestador_id
    );

    $grilla = "<div id='div_grilla_servicios_asignados' class='row hidden'>";
    $grilla .= "<div class='col-sm-12'>";
    $grilla .=  "<div class='card-box table-responsive'>";
    $grilla .=      "<h4 class='m-t-0 header-title'><b>Servicios Autorizados</b></h4>";
    $grilla .=      "<table class='table table-hover table-striped m-0 table-responsive'>";
    $grilla .=          "<thead>";
    $grilla .=              "<tr>";
    $grilla .=                  "<th>Caso</th>";
    $grilla .=                  "<th>Fecha Servicio</th>";
    $grilla .=                  "<th>Prestador</th>";
    $grilla .=                  "<th>Practica</th>";
    $grilla .=                  "<th>Presunto Origen</th>";
    $grilla .=                  "<th>Moneda</th>";
    $grilla .=                  "<th>T/C</th>";
    $grilla .=                  "<th>Presunto USD</th>";
    $grilla .=                  "<th></th>";
    $grilla .=              "</tr>";
    $grilla .=          "</thead>";
    $grilla .=          "<tbody>";
    foreach ($servicios as $servicio) {
        $servicio_id        = $servicio["servicioId"];
        $fecha_servicio     = $servicio["servicioFecha"];
        //$prestador_id       = $servicio["prestadorId"];
        $prestador          = $servicio["prestador"];
        $practica           = $servicio["practica"];
        $presunto_origen    = $servicio["presuntoOrigen"];
        $moneda             = $servicio["moneda"];
        $tipo_cambio        = $servicio["tipoCambio"];
        $presunto_usd       = $servicio["presuntoUSD"];
        $caso_numero        = $servicio["casoNumero"];
        //$caso_id            = $servicio["casoId"];
        $asignado           = $servicio["asignado"];
        $grilla .=              "<tr>";
        $grilla .=                  "<td>";
        $grilla .=                      $caso_numero;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $fecha_servicio;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $prestador;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $practica;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $presunto_origen;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $moneda;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $tipo_cambio;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $presunto_usd;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        if ($asignado == 0) {
            $grilla .=                  "<input type='checkbox' onclick='javascript:seleccion_servicios_m();' name='seleccionados_m[]' class='checkboxServicio' value='" . $servicio_id . "'/>";
        } else {
            $grilla .=                  "<span class='label label-danger'>Asignado</span>";
            $grilla .=                  "<input type='checkbox' onclick='javascript:seleccion_servicios_b();' name='seleccionados_b[]' class='checkboxServicio' value='" . $servicio_id . "' checked   />";
        }
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


function grilla_fcItems($factura_id, $permisos, $order, $pagador, $factura_final)
{
    $fc_items = Facturacion::listar_items_facturas($factura_id, $order, $pagador, $factura_final);

    $importeMedicoTotal = 0;
    $importeFeeTotal = 0;
    $descuentoTotal = 0;
    $moneda = "";
    $tipoCambio = "";
    $importeUSDTotal = 0;
    $importeAprobadoTotal = 0;
    $importeAprobadoOrigenTotal = 0;
    $select_pagadores = '<option value="0">Todos</option>';

    //buscador por cliente 
    $pagadores = Cliente::formulario_alta_clientes();
    foreach ($pagadores as $pagador_select) {
        $selected = '';
        if ($pagador_select['cliente_id'] == $pagador) {
            $selected = 'selected';
        }
        $select_pagadores .= '<option value="' . $pagador_select['cliente_id'] . '" ' . $selected . '>' . $pagador_select['cliente_nombre'] . '</option>';
    }


    $grilla = "<div class='row'>";
    $grilla .= "<div class='col-sm-12'>";
    $grilla .=  "<div class='card-box table-responsive'>";
    $grilla .=      "<h4 class='m-t-0 header-title'><b>Items de Factura</b></h4><br>";
    $grilla .=      "<div class='col-md-4'><div class='form-group'>";
    $grilla .=          "<label for='nuevo_pagador'>Filtro por pagador:</label>";
    $grilla .=          "<select id='fci_pagador_id_m' name='fci_pagador_id_m' class='form-control' onchange='grilla_fcItems(1, this.value)' >" . $select_pagadores . "</select>";
    $grilla .=      "</div></div>";
    $grilla .=      "<div class='col-md-4'><div class='form-group'>";
    $grilla .=          "<label for='nuevo_pagador'>Filtro por factura final:</label>";
    $grilla .=          "<input id='factura_final_id_m' name='factura_final_id_m' class='form-control' type='text' maxlength='13' value='" . $factura_final . "'>";
    $grilla .=      "</div></div>";
    $grilla .=      "<div class='col-md-4'><div class='form-group'>";
    $grilla .=      "<i id='btn_buscar_factura_' class='fa fa-search' style='margin-top: 34px;'  onclick=\"grilla_fcItems(1, 0, \$('#factura_final_id_m').val())\"></i>";
    $grilla .=      "</div></div>";
    $grilla .=      "<table class='table table-hover table-striped m-0 table-responsive'>";
    $grilla .=          "<thead>";
    $grilla .=              "<tr>";
    $grilla .=                  "<th>Ingreso</th>";
    $grilla .=                  "<th>Caso <a href='javascript:void(0)'> <i onclick='grilla_fcItems(1, " . $pagador . ")' class='fa fa-solid fa-chevron-down' data-toggle='tooltip' data-placement='top' title='Ordenarmiento ascendente'></i></a><a href='javascript:void(0)'> <i onclick='grilla_fcItems(2, " . $pagador . ")' class='fa fa-solid fa-chevron-up' data-toggle='tooltip' data-placement='top' title='Ordenarmiento descendente'></i></a></th>";
    $grilla .=                  "<th>Pagador</th>";
    $grilla .=                  "<th>Pagador Final</th>";
    $grilla .=                  "<th>Factura Final</th>";
    $grilla .=                  "<th>Estado</th>";
    $grilla .=                  "<th>Imp. Medico</th>";
    $grilla .=                  "<th>Fee</th>";
    $grilla .=                  "<th>Descuento</th>";
    $grilla .=                  "<th>Moneda</th>";
    $grilla .=                  "<th>T/C</th>";
    $grilla .=                  "<th>Imp. USD</th>";
    $grilla .=                  "<th>Aprobado (USD)</th>";
    $grilla .=                  "<th>Aprobado (Origen)</th>";
    $grilla .=                  "<th>Acciones</th>";
    $grilla .=              "</tr>";
    $grilla .=          "</thead>";
    $grilla .=          "<tbody>";
    foreach ($fc_items as $fc_item) {
        $fci_id                             = $fc_item["fci_id"];
        $estadoId                           = $fc_item["estadoId"];
        $estado                             = $fc_item["estado"];
        $estadoSector                       = $fc_item["estadoSector"];
        $caso_id                            = $fc_item["casoId"];
        $caso_numero                        = $fc_item["casoNumero"];
        $pagadorAbreviado                   = $fc_item["pagador"];
        $pagadorFinal                       = $fc_item["pagador_final"];
        $facturafinal                       = $fc_item["factura_final"];
        $cantServicios                      = $fc_item["cantServ"];
        $fechaIngresoSistemaANSI            = $fc_item["fechaIngresoSistema"];
        //Conversiones de la fecha de ANSI a dd-mm-yyyy
        $fechaIngresoSistema                = date("d-m-Y", strtotime($fechaIngresoSistemaANSI));
        $moneda                             = $fc_item["moneda"];
        $importeMedico                      = $fc_item["importeMedico"];
        $importeMedicoTotal                 = $importeMedicoTotal + $importeMedico;
        $importeMedico                      = number_format($importeMedico, 2, ',', '.');
        $importeFee                         = $fc_item["importeFee"];
        $importeFeeTotal                    = $importeFeeTotal + $importeFee;
        $importeFee                         = number_format($importeFee, 2, ',', '.');
        $descuento                          = $fc_item["descuento"];
        $descuentoTotal                     = $descuentoTotal + $descuento;
        $descuento                          = number_format($descuento, 2, ',', '.');
        $tipoCambio                         = $fc_item["tipoCambio"];
        $tipoCambio                         = number_format($tipoCambio, 2, ',', '.');
        $importeUSD                         = $fc_item["importeUSD"];
        $importeUSDTotal                    = $importeUSDTotal + $importeUSD;
        $importeUSD                         = number_format($importeUSD, 2, ',', '.');
        $importeAprobadoUSD_sinFormato      = $fc_item['impAprobadoUSD'];
        $importeAprobadoUSD                 = $fc_item['impAprobadoUSD'];
        $importeAprobadoTotal               = $importeAprobadoTotal + $importeAprobadoUSD;
        $importeAprobadoUSD                 = number_format($importeAprobadoUSD, 2, ',', '.');
        $importeAprobadoOrigen              = $fc_item["impAprobadoOrigen"];

        if ($estadoId == 2 || $estadoId == 3 || $estadoId == 5 || $estadoId == 7) {
            $importeAprobadoOrigenTotal         = $importeAprobadoOrigenTotal + (float)$fc_item["importeMedico"] + (float)$fc_item["importeFee"];
            $importeAprobadoOrigen              = number_format((float)$fc_item["importeMedico"] + (float)$fc_item["importeFee"], 2, ',', '.');
        } else {
            $importeAprobadoOrigenTotal         = $importeAprobadoOrigenTotal + $importeAprobadoOrigen;
            $importeAprobadoOrigen              = number_format($importeAprobadoOrigen, 2, ',', '.');
        }

        $grilla .=              "<tr>";
        $grilla .=                  "<td>";
        $grilla .=                      $fechaIngresoSistema;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      "<a target='_blank' href='../caso/caso.php?vcase=" . $caso_id . "'>" . $caso_numero . "</a>";
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $pagadorAbreviado;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $pagadorFinal;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $facturafinal;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        if ($estadoId == 1) { // Estado = Item Ingresado
            $grilla .=  "<span class='label label-default'>" . $estado . "</span>";
        } else if ($estadoId == 4 || $estadoId == 6 || $estadoId == 8) { // Estados = Item Rechazado
            $grilla .=  "<span class='label label-danger'>" . $estado . ": " . $estadoSector . "</span>";
        } else if ($estadoId == 9) { // Estados = Item Validado
            $grilla .=  "<span class='label label-success'>" . $estado . "</span>";
        } else if ($estadoId == 10) { // Estados = Item Pagado
            $grilla .=  "<span class='label label-primary'>" . $estado . "</span>";
        } else { // Estados = Item Aprobado
            $grilla .=  "<span class='label label-success'>" . $estado . ": " . $estadoSector . "</span>";
        }
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $importeMedico;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $importeFee;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $descuento;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $moneda;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $tipoCambio;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $importeUSD;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $importeAprobadoUSD;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $importeAprobadoOrigen;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";

        /* APLICAR SOLO CUANDO SE RESUELVA LA TARJETA:
         * 
         * Facturas > Modificar items de factura
         * 
         * Valida el estado del item, si se avanzo en el proceso de autorizacion el item no se puede modificar
         
        if ($estadoId == 1) {
            // Permiso para editar el ITEM de Factura
            $fci_modificacion = array_search('fci_modificacion', array_column($permisos, 'permiso_variable'));
            if (!empty($fci_modificacion) || ($fci_modificacion === 0)) {
                $grilla .= "<a href='javascript:void(0)'> <i onclick='formulario_lectura_fci($fci_id)' class='fa fa-edit' data-toggle='tooltip' data-placement='top' title='Editar item'></i></a>";
            }
        }
        */

        // Permisos para autorizacion de ITEM de Factura
        // Botones de AUTORIZACION y PAGO
        if ($estadoId == 1) { // 1 - Item Ingresado

            if (Usuario::puede("fci_auto_admin") == 1) {

                $grilla .= "<a href='javascript:void(0)'> <i onclick='autorizar_fci($fci_id)' class='fa fa-check-square-o' data-toggle='tooltip' data-placement='top' title='Autorización Administración'></i></a>";
            }
        } else if ($estadoId == 2 || $estadoId == 3) { // 2 - Item Aprobado por Facturación OR 3- Item Aprobado Parcial por Facturación

            if ($importeAprobadoUSD_sinFormato >= 5000 && Usuario::puede("fci_auto_medica") == 1) { // Si supera los USD5.000 y tiene permisos, debe autorizar Dirección Médica

                $grilla .= "<a href='javascript:void(0)'> <i onclick='autorizar_fci($fci_id)' class='fa fa-check-square-o' data-toggle='tooltip' data-placement='top' title='Autorización Dir. Médica'></i></a>";
            } else if ($importeAprobadoUSD_sinFormato < 5000 && Usuario::puede("fci_auto_finanzas") == 1) {

                $grilla .= "<a href='javascript:void(0)'> <i onclick='autorizar_fci($fci_id)' class='fa fa-check-square-o' data-toggle='tooltip' data-placement='top' title='Autorización Finanzas'></i></a>";
            }
        } else if ($estadoId == 5) { // 5- Item Aprobado por Dir. Médica

            if (Usuario::puede("fci_auto_finanzas") == 1) {
                $grilla .= "<a href='javascript:void(0)'> <i onclick='autorizar_fci($fci_id)' class='fa fa-check-square-o' data-toggle='tooltip' data-placement='top' title='Autorización Finanzas'></i></a>";
            }
        } else if ($estadoId == 7) { // 7- Item Aprobado por Finanzas

            if (Usuario::puede("fci_auto_validacion") == 1) {

                $grilla .= "<a href='javascript:void(0)'> <i onclick='autorizar_fci($fci_id)' class='fa fa-check-square-o' data-toggle='tooltip' data-placement='top' title='Validar Item'></i></a>";
            }
        } else if ($estadoId == 9) { // 9- Item Validado

            if (Usuario::puede("fci_auto_pago") == 1) {

                $grilla .= "<a href='javascript:void(0)'> <i onclick='autorizar_fci($fci_id)' class='fa fa-check-square-o' data-toggle='tooltip' data-placement='top' title='Pagar Item'></i></a>";
            }
        }
        if (
            $estadoId == 2 ||
            $estadoId == 7
        ) {
            $grilla .= "<input onchange='verifyCheckboxes()' name='item_f_check[]' id='item_f_check[]' class='item_f_check' type='checkbox' value='" . $fci_id . "' data-toggle='tooltip' data-placement='top' title='Seleccionar Item' > ";
        } else {
            $grilla .= "<input name='none' id='none' class='none' type='checkbox' value='' disabled> ";
        }
        $grilla .=                      "<a href='javascript:void(0)'> <i onclick='grilla_movimientos_fci($fci_id)' class='fa fa-search' data-toggle='tooltip' data-placement='top' title='Ver movimientos'></i></a>";
        $grilla .=                      "<a href='javascript:void(0)'> <i onclick='grilla_servicios_fci($fci_id)' class='fa fa-sticky-note-o' data-toggle='tooltip' data-placement='top' title='Ver servicios'></i></a>";
        $grilla .=                  "</td>";
        $grilla .=              "</tr>";
    }
    $grilla .=              "<tr style='font-weight:bold'>";
    $grilla .=                  "<td colspan='5'>";
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      "TOTALES";
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $importeMedicoTotal = number_format($importeMedicoTotal, 2, ',', '.');
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $importeFeeTotal = number_format($importeFeeTotal, 2, ',', '.');
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $descuentoTotal = number_format($descuentoTotal, 2, ',', '.');
    $grilla .=                  "</td>";
    $grilla .=                  "<td colspan='2'>";
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $importeUSDTotal = number_format($importeUSDTotal, 2, ',', '.');
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $importeAprobadoTotal = number_format($importeAprobadoTotal, 2, ',', '.');
    $grilla .=                  "</td>";
    $grilla .=                  "<td colspan='2'>";
    $grilla .=                      $importeAprobadoOrigenTotal = number_format($importeAprobadoOrigenTotal, 2, ',', '.');
    $grilla .=                  "</td>";
    $grilla .=              "</tr>";
    $grilla .=          "</tbody>";
    $grilla .=       "</table>";
    $grilla .=      "<div class='container_nueva_factura_pagador col-md-12' id='container_nueva_factura_pagador' style='display:none'><br><hr>";
    $grilla .=          "<h3>Crear nueva factura</h3>";
    $grilla .=          "<p>Por favor seleccione los items para los cuales desea crear una nueva factura e ingrese los siguientes datos:</p><br>";
    $grilla .=          "<div class='col-md-4'><div class='form-group'>";
    $grilla .=              "<label for='nuevo_pagador'>Seleccione pagador final:</label>";
    $grilla .=              "<select class='form-control' id='nuevo_pagador' name='nuevo_pagador'>" . $select_pagadores . "</select>";
    $grilla .=          "</div></div>";
    $grilla .=          "<div class='col-md-4'><div class='form-group'>";
    $grilla .=              "<label for='nueva_factura'>Ingrese número de factura final:</label>";
    $grilla .=              "<input type='text' class='form-control' name='nueva_factura' id='nueva_factura'>";
    $grilla .=          "</div></div>";
    $grilla .=          "<div class='col-md-4'><br><button type='button' id='guardar_nuevo_pagador' onclick='SaveNewPayer()' class='btn btn-primary'>Crear factura</button></div>";
    $grilla .=      "</div>";
    $grilla .=    "</div>";
    $grilla .=  "</div>";
    $grilla .= "</div>";

    echo $grilla;
}


// Grilla Movimientos Facturas
function grilla_listar_mov_fci($fci_id)
{

    $movFcis            = Facturacion::listar_mov_fci($fci_id);
    $movFci_contador    = Facturacion::contar_mov_fci($fci_id);
    $movFci_numero      = $movFci_contador["fciMov_cantidad"] + 1;


    $grilla = '<div class="col-sm-12">';
    $grilla = '<div class="card-box">';

    $grilla = '<div class="row">';
    $grilla .=   '<div class="col-sm-12">';
    $grilla .=       '<div class="timeline">';
    $grilla .=           '<article class="timeline-item alt">';
    $grilla .=               '<div class="text-right">';
    $grilla .=                   '<div class="time-show first">';
    $grilla .=                       '<a href="#" class="btn btn-primary w-lg">Movimientos del Item</a>';
    $grilla .=                   '</div>';
    $grilla .=               '</div>';
    $grilla .=           '</article> ';

    foreach ($movFcis as $movFci) {
        $movFci_numero          = $movFci_numero - 1;
        $fechaEvento            = $movFci["fechaEvento"];
        $usuarioMov             = $movFci["usuarioNombreMov"] . ' ' . $movFci["usuarioApellidoMov"];
        $estadoMov              = $movFci["estadoMovId"];
        $estadoMovDesc          = $movFci["estadoMovDesc"];
        $motivoRechazo          = $movFci["motivoRechazo"];
        $observacionesMov       = $movFci["observacionesMov"];
        $importeAprobadoUSD     = $movFci["importeAprobadoUSD"];
        $fechaPagoMov           = $movFci["fechaPago"];
        $formaPagoMov           = $movFci["formaPago"];

        // Setea la posicion del cuadro dependiendo si es par o impar (aplica solo para la presentacion grafica)
        if ($movFci_numero % 2 == 0) {
            $article_pos = '<article class="timeline-item alt">';
            $span_post = '<span class="arrow-alt panel-logFacturas"></span>';
        } else {
            $article_pos = '<article class="timeline-item">';
            $span_post = '<span class="arrow"></span>';
        }

        // Segun el estado de movimiento, es el color del texto
        if ($estadoMov == 1) { // Item ingresado
            $text_color = 'text-default';
        } else if ($estadoMov == 2 || $estadoMov == 3 || $estadoMov == 5 || $estadoMov == 7 || $estadoMov == 9) { // Item Aprobado o Item Validado
            $text_color = 'text-success';
        } else if ($estadoMov == 4 || $estadoMov == 6 || $estadoMov == 8) { // Item Rechazado
            $text_color = 'text-danger';
        } else if ($estadoMov == 10) { // Item pagado
            $text_color = 'text-primary';
        }

        // Armar texto de los movimientos de factura
        $texto = '<b class="' . $text_color . '">' . $estadoMovDesc . '</b><br>';
        if ($importeAprobadoUSD > 0) {
            $texto .= ' <b>Importe Aprobado USD:</b> ' . $importeAprobadoUSD . '<br>';
        }
        if ($motivoRechazo != NULL) {
            $texto .= ' <b>Motivo rechazo:</b> ' . $motivoRechazo . '<br>';
        }
        if ($observacionesMov != NULL) {
            $texto .= ' <b>Observaciones:</b> ' . $observacionesMov . '<br>';
        }
        if ($fechaPagoMov != NULL && $formaPagoMov != NULL) {
            $texto .= ' <b>Fecha de pago:</b> ' . $fechaPagoMov . ' <b>Forma de pago:</b> ' . $formaPagoMov;
        }

        $fecha = date('d M Y - H:i', strtotime($fechaEvento));

        // Inicio de timeline
        $grilla .= $article_pos;
        $grilla .=  '<div class="timeline-desk">';
        $grilla .=      '<div class="panel">';
        $grilla .=          '<div class="panel-body">';
        $grilla .=              $span_post;
        $grilla .=              '<span class="timeline-icon"></span>';
        $grilla .=              '<h4 class="text-default">' . $usuarioMov . '</h4>';
        $grilla .=              '<p class="timeline-date text-muted"><small>' . $fecha . '</small></p>';
        $grilla .=              '<p>' . $texto . '</p>';
        $grilla .=          '</div>';
        $grilla .=      '</div>';
        $grilla .=  '</div>';
        $grilla .= '</article>';
    }
    $grilla .=      "</div>";
    $grilla .=  "</div>";
    $grilla .= "</div>";

    $grilla .= "</div>";
    $grilla .= "</div>";

    echo $grilla;
}

function grilla_listar_servicios_fci($fci_id)
{

    $servicios = Facturacion::listar_servicios_fci($fci_id);

    $grilla = "<div id='div_grilla_servicios_fci' class='row hidden'>";
    $grilla .= "<div class='col-sm-12'>";
    $grilla .=  "<div class='card-box table-responsive'>";
    $grilla .=      "<table class='table table-hover table-striped m-0 table-responsive'>";
    $grilla .=          "<thead>";
    $grilla .=              "<tr>";
    $grilla .=                  "<th>Caso</th>";
    $grilla .=                  "<th>Fecha Servicio</th>";
    $grilla .=                  "<th>Prestador</th>";
    $grilla .=                  "<th>Practica</th>";
    $grilla .=                  "<th>Presunto Origen</th>";
    $grilla .=                  "<th>Moneda</th>";
    $grilla .=                  "<th>T/C</th>";
    $grilla .=                  "<th>Presunto USD</th>";
    $grilla .=                  "<th></th>";
    $grilla .=              "</tr>";
    $grilla .=          "</thead>";
    $grilla .=          "<tbody>";
    foreach ($servicios as $servicio) {
        $fecha_servicio     = $servicio["servicioFecha"];
        $prestador          = $servicio["prestador"];
        $practica           = $servicio["practica"];
        $presunto_origen    = $servicio["presuntoOrigen"];
        $moneda             = $servicio["moneda"];
        $tipo_cambio        = $servicio["tipoCambio"];
        $presunto_usd       = $servicio["presuntoUSD"];
        $caso_numero        = $servicio["casoNumero"];
        $grilla .=              "<tr>";
        $grilla .=                  "<td>";
        $grilla .=                      $caso_numero;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $fecha_servicio;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $prestador;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $practica;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $presunto_origen;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $moneda;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $tipo_cambio;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      $presunto_usd;
        $grilla .=                  "</td>";
        $grilla .=                  "<td>";
        $grilla .=                      "<span class='label label-danger'>Asociado</span>";
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
