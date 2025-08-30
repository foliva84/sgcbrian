<?php
require '../seguridad/seguridad.php';
include_once '../includes/herramientas.php';


// Toma las variables del formulario ALTA REINTEGRO
$reintegro_fechaPresentacion_n      = isset($_POST["reintegro_fechaPresentacion_n"])?$_POST["reintegro_fechaPresentacion_n"]:'';
$reintegro_observaciones_n          = isset($_POST["reintegro_observaciones_n"])?$_POST["reintegro_observaciones_n"]:'';
    

// Toma las variables del formulario MODIFICACION REINTEGRO
$reintegro_id_m                     = isset($_POST["reintegro_id_m"])?$_POST["reintegro_id_m"]:'';
$reintegro_fechaPresentacion_m      = isset($_POST["reintegro_fechaPresentacion_m"])?$_POST["reintegro_fechaPresentacion_m"]:'';
$reintegro_observaciones_m          = isset($_POST["reintegro_observaciones_m"])?$_POST["reintegro_observaciones_m"]:'';

// datos bancarios
$reintegro_id_db                    = isset($_POST["reintegro_id_db"])?$_POST["reintegro_id_db"]:'';
$reintegro_CBUcuenta_m              = isset($_POST["reintegro_CBUcuenta_m"])?$_POST["reintegro_CBUcuenta_m"]:'';
$reintegro_CBUalias_m               = isset($_POST["reintegro_CBUalias_m"])?$_POST["reintegro_CBUalias_m"]:'';
$reintegro_denominacion_m           = isset($_POST["reintegro_denominacion_m"])?$_POST["reintegro_denominacion_m"]:'';
$reintegro_documentoTipo_id_m       = isset($_POST["reintegro_documentoTipo_id_m"])?$_POST["reintegro_documentoTipo_id_m"]:'';
$reintegro_beneficiarioDocumento_m  = isset($_POST["reintegro_beneficiarioDocumento_m"])?$_POST["reintegro_beneficiarioDocumento_m"]:'';
$reintegro_referenciaTipo_id_m      = isset($_POST["reintegro_referenciaTipo_id_m"])?$_POST["reintegro_referenciaTipo_id_m"]:'';
$reintegro_referencia_m             = isset($_POST["reintegro_referencia_m"])?$_POST["reintegro_referencia_m"]:'';
$reintegro_avisoTransTipo_id_m      = isset($_POST["reintegro_avisoTransTipo_id_m"])?$_POST["reintegro_avisoTransTipo_id_m"]:'';
$reintegro_emailDestinatario_m      = isset($_POST["reintegro_emailDestinatario_m"])?$_POST["reintegro_emailDestinatario_m"]:'';
$reintegro_emailTexto_m             = isset($_POST["reintegro_emailTexto_m"])?$_POST["reintegro_emailTexto_m"]:'';
$reintegro_compania_m               = isset($_POST["reintegro_compania_m"])?$_POST["reintegro_compania_m"]:'';
$reintegro_codigoArea_m             = isset($_POST["reintegro_codigoArea_m"])?$_POST["reintegro_codigoArea_m"]:'';
$reintegro_telefono_m               = isset($_POST["reintegro_telefono_m"])?$_POST["reintegro_telefono_m"]:'';

//datos bancarios nuevos
$reintegro_banco_m                          = isset($_POST["reintegro_banco_m"])?$_POST["reintegro_banco_m"]:'';
$reintegro_digito_verificacion_titular_m    = isset($_POST["reintegro_digito_verificacion_titular_m"])?$_POST["reintegro_digito_verificacion_titular_m"]:'';
$reintegro_mail_titular_m                   = isset($_POST["reintegro_mail_titular_m"])?$_POST["reintegro_mail_titular_m"]:'';
$reintegro_tipo_cuenta_m                    = isset($_POST["reintegro_tipo_cuenta_m"])?$_POST["reintegro_tipo_cuenta_m"]:'';
$reintegro_direccion_titular_m              = isset($_POST["reintegro_direccion_titular_m"])?$_POST["reintegro_direccion_titular_m"]:'';
$reintegro_ciudad_m                         = isset($_POST["reintegro_ciudad_m"])?$_POST["reintegro_ciudad_m"]:'';

// Toma las variables del formulario ALTA ITEM REINTEGRO
$reintegroItem_reintegro_id_n       = isset($_POST["reintegroItem_reintegro_id_n"])?$_POST["reintegroItem_reintegro_id_n"]:'';
$reintegroItem_concepto_id_n        = isset($_POST["reintegroItem_concepto_id_n"])?$_POST["reintegroItem_concepto_id_n"]:'';
$reintegroItem_importeOrigen_n      = isset($_POST["reintegroItem_importeOrigen_n"])?$_POST["reintegroItem_importeOrigen_n"]:'';
$reintegroItem_moneda_id_n          = isset($_POST["reintegroItem_moneda_id_n"])?$_POST["reintegroItem_moneda_id_n"]:'';
$reintegroItem_monedaTC_n           = isset($_POST["reintegroItem_monedaTC_n"])?$_POST["reintegroItem_monedaTC_n"]:'';
$reintegroItem_importeUSD_n         = isset($_POST["reintegroItem_importeUSD_n"])?$_POST["reintegroItem_importeUSD_n"]:'';
$reintegroItem_observaciones_n      = isset($_POST["reintegroItem_observaciones_n"])?$_POST["reintegroItem_observaciones_n"]:'';

// Toma las variables del formulario MODIFICACION ITEM REINTEGRO
$reintegroItem_id                   = isset($_POST["reintegroItem_id"])?$_POST["reintegroItem_id"]:'';
$reintegroItem_concepto_id          = isset($_POST["reintegroItem_concepto_id"])?$_POST["reintegroItem_concepto_id"]:'';
$reintegroItem_importeOrigen        = isset($_POST["reintegroItem_importeOrigen"])?$_POST["reintegroItem_importeOrigen"]:'';
$reintegroItem_moneda_id            = isset($_POST["reintegroItem_moneda_id"])?$_POST["reintegroItem_moneda_id"]:'';
$reintegroItem_monedaTC             = isset($_POST["reintegroItem_monedaTC"])?$_POST["reintegroItem_monedaTC"]:'';
$reintegroItem_importeUSD           = isset($_POST["reintegroItem_importeUSD"])?$_POST["reintegroItem_importeUSD"]:'';
$reintegroItem_observaciones        = isset($_POST["reintegroItem_observaciones"])?$_POST["reintegroItem_observaciones"]:'';

// Toma la variable para establecer la forma de pago e importe total ARS
$reintegro_formaPago_id             = isset($_POST["reintegro_formaPago_id"])?$_POST["reintegro_formaPago_id"]:'';
$reintegro_importe_usd_v            = isset($_POST["reintegro_importe_usd_v"])?$_POST["reintegro_importe_usd_v"]:'';
$reintegro_tc_ars                   = isset($_POST["reintegro_tc_ars"])?$_POST["reintegro_tc_ars"]:'';
$reintegro_importe_ars              = isset($_POST["reintegro_importe_ars"])?$_POST["reintegro_importe_ars"]:'';
$reintegro_id_fp                    = isset($_POST["reintegro_id_fp"])?$_POST["reintegro_id_fp"]:'';

// Tomas las variables del formulario AUTORIZACION ITEM REINTEGRO
$ri_id_au                           = isset($_POST["ri_id_au"])?$_POST["ri_id_au"]:'';
$ri_estado_id_au                    = isset($_POST["ri_estado_id_au"])?$_POST["ri_estado_id_au"]:'';
$ri_importeAprobadoUSD_au           = isset($_POST["ri_importeAprobadoUSD_au"])?$_POST["ri_importeAprobadoUSD_au"]:'';
/* Campos reintegroLog */
$ri_mov_auditoria_auto_id           = isset($_POST["ri_mov_auditoria_auto_id"])?$_POST["ri_mov_auditoria_auto_id"]:'';
$ri_importeAprobadoUSD_auto         = isset($_POST["ri_importeAprobadoUSD_auto"])?$_POST["ri_importeAprobadoUSD_auto"]:'';
$ri_fechaPago_auto                  = isset($_POST["ri_fechaPago_auto"])?$_POST["ri_fechaPago_auto"]:'';
$ri_observaciones_auto              = isset($_POST["ri_observaciones_auto"])?$_POST["ri_observaciones_auto"]:'';

// Toma variables para el calculo de importes
$importe_origen                     = isset($_POST["importe_origen"])?$_POST["importe_origen"]:'';
$tipo_cambio                        = isset($_POST["tipo_cambio"])?$_POST["tipo_cambio"]:'';

// Toma variables generales
$caso_id                            = isset($_POST["caso_id"])?$_POST["caso_id"]:'';
$reintegro_id                       = isset($_POST["reintegro_id"])?$_POST["reintegro_id"]:'';
$auditoria_tipo                     = isset($_POST["auditoria_tipo"])?$_POST["auditoria_tipo"]:'';

// Definición del ABML (Alta, Baja, Modificacion, Lectura -- objeto_ABML)
$opcion                             = isset($_POST["opcion"])?$_POST["opcion"]:'';


// Case
switch($opcion){
    
    // Acciones de los formularios
    case 'formulario_alta':
        formulario_alta($reintegro_fechaPresentacion_n,
                        $reintegro_observaciones_n,
                        $caso_id,
                        $sesion_usuario_id);
        break;
    
    case 'formulario_lectura':
        formulario_lectura($reintegro_id);
        break;
    
    case 'formulario_modificacion':
        formulario_modificacion($reintegro_fechaPresentacion_m,
                                $reintegro_observaciones_m,
                                $reintegro_id_m,
                                $sesion_usuario_id);
        break;
    
    case 'formulario_reintegro_formaPago':
        formulario_reintegro_formaPago($reintegro_formaPago_id, 
                                        $reintegro_importe_usd_v,                                
                                        $reintegro_tc_ars,
                                        $reintegro_importe_ars,                               
                                        $reintegro_id_fp);
            break;

    case 'chequeoItemsPendientes':
        chequeoItemsPendientes($reintegro_id);
        break;
    
    case 'formulario_datosBancarios':
        formulario_datosBancarios($reintegro_CBUcuenta_m,
                                    $reintegro_CBUalias_m,
                                    $reintegro_denominacion_m,
                                    $reintegro_documentoTipo_id_m,
                                    $reintegro_beneficiarioDocumento_m,
                                    $reintegro_referenciaTipo_id_m,
                                    $reintegro_referencia_m,
                                    $reintegro_avisoTransTipo_id_m,
                                    $reintegro_emailDestinatario_m,
                                    $reintegro_emailTexto_m,
                                    $reintegro_compania_m,
                                    $reintegro_codigoArea_m,
                                    $reintegro_telefono_m,
                                    $reintegro_id_db,
                                    $reintegro_banco_m,
                                    $reintegro_digito_verificacion_titular_m,
                                    $reintegro_mail_titular_m,
                                    $reintegro_tipo_cuenta_m,
                                    $reintegro_direccion_titular_m,
                                    $reintegro_ciudad_m);
                                    
        break;
    
    case 'formulario_alta_ri':
        formulario_alta_ri($reintegroItem_reintegro_id_n,
                           $reintegroItem_concepto_id_n,
                           $reintegroItem_importeOrigen_n,
                           $reintegroItem_moneda_id_n,
                           $reintegroItem_monedaTC_n,
                           $reintegroItem_importeUSD_n,
                           $reintegroItem_observaciones_n,
                           $sesion_usuario_id);
        break;
    
    case 'formulario_modificacion_ri':
        formulario_modificacion_ri($reintegroItem_id,
                                   $reintegroItem_concepto_id,
                                   $reintegroItem_importeOrigen,
                                   $reintegroItem_moneda_id,
                                   $reintegroItem_monedaTC,
                                   $reintegroItem_importeUSD,
                                   $reintegroItem_observaciones,
                                   $sesion_usuario_id);
        break;
    
    case 'formulario_lectura_ri':
        formulario_lectura_ri($reintegroItem_id);
        break;
    
    case 'formulario_autorizacion_ri':
        formulario_autorizacion_ri($ri_id_au,
                                   $ri_mov_auditoria_auto_id,
                                   $ri_fechaPago_auto,
                                   $ri_formaPago_auto_id,
                                   $ri_observaciones_auto,
                                   $sesion_usuario_id,
                                   $ri_importeAprobadoUSD_au,
                                   $ri_importeAprobadoUSD_auto);
        break;
    
    case 'ri_pendiente_autorizar':
        ri_pendiente_autorizar($reintegroItem_id);
        break;
    
    // Funciones auxiliares de formulario    
    case 'calcular_importe_usd':
        calcular_importe_usd($importe_origen, $tipo_cambio);
        break;

    case 'valida_estado_items':
        valida_estado_items($reintegro_id);
        break;
    
    case 'auditar_reintegro':
        auditar_reintegro($reintegro_id, $auditoria_tipo);
        break;
            
    case 'retener_reintegro':
    retener_reintegro($reintegro_id);
        break;
            
    case 'liberar_reintegro':
        liberar_reintegro($reintegro_id);
        break;
    
    case 'rollback_reintegro':
        rollback_reintegro($reintegro_id);
        break;
    
    case 'rollback_reintegro_pendDoc':
        rollback_reintegro_pendDoc($reintegro_id);
        break;
    
    case 'rollback_reintegro_enProceso':
        rollback_reintegro_enProceso($reintegro_id);
        break;

    // Select - Formulario Alta Reintegros
    case 'listar_estadosReintegro_alta':
        listar_estadosReintegro_alta();
        break;
    
    case 'listar_documentoTipos_alta':
        listar_documentoTipos_alta();
        break;
    
    case 'listar_documentoTipos_modificacion':
        listar_documentoTipos_modificacion($reintegro_id);
        break;
    
    case 'listar_referenciaTipos_alta':
        listar_referenciaTipos_alta();
        break;
    
    case 'listar_referenciaTipos_modificacion':
        listar_referenciaTipos_modificacion($reintegro_id);
        break;
    
    case 'listar_avisoTransTipos_alta':
        listar_avisoTransTipos_alta();
        break;
    
    case 'listar_avisoTransTipos_modificacion':
        listar_avisoTransTipos_modificacion($reintegro_id);
        break;

    case 'listar_riConceptos_alta':
        listar_riConceptos_alta();
        break;
    
    case 'listar_riConceptos_modificacion':
        listar_riConceptos_modificacion($reintegroItem_id);
        break;
    
    case 'listar_riMonedas_alta':
        listar_riMonedas_alta();
        break;
    
    case 'listar_riMonedas_modificacion':
        listar_riMonedas_modificacion($reintegroItem_id);
        break;
    
    case 'listar_movEstados_alta':
        listar_movEstados_alta($ri_estado_id_au);
        break;
    
    case 'listar_formasPagos_reintegro':
        listar_formasPagos_reintegro();
        break;
    
    case 'formulario_vistaDatosCaso':
        formulario_vistaDatosCaso($caso_id);
        break;
    
       
    // Case grillas
    case 'grilla_listar':
        grilla_listar($caso_id);
        break;
    
    case 'grilla_listar_items':
        grilla_listar_items($reintegro_id, $permisos);
        break;
    
    case 'grilla_listar_mov_ri':
        grilla_listar_mov_ri($reintegroItem_id);
        break;
    
    case 'grilla_observaciones_ri':
        grilla_observaciones_ri($reintegroItem_id);
        break;

    
    default:
       echo("Está mal seleccionada la funcion");
}


// Funciones de Formulario
function formulario_alta($reintegro_fechaPresentacion_n,
                         $reintegro_observaciones_n,
                         $caso_id,
                         $sesion_usuario_id) {
    
    $resultado_insert = Reintegro::insertar($reintegro_fechaPresentacion_n,
                                            $reintegro_observaciones_n,
                                            $caso_id,
                                            $sesion_usuario_id);

    echo json_encode($resultado_insert);
}

function formulario_lectura($reintegro_id){
    
    $reintegro = Reintegro::buscarPorId($reintegro_id);
    
    echo json_encode($reintegro);
}

function formulario_modificacion($reintegro_fechaPresentacion_m,
                                 $reintegro_observaciones_m,
                                 $reintegro_id_m,
                                 $sesion_usuario_id) {
    
    $resultado = Reintegro::actualizar($reintegro_fechaPresentacion_m,
                                       $reintegro_observaciones_m,
                                       $reintegro_id_m,
                                       $sesion_usuario_id);
    
    echo json_encode($resultado);
}

function formulario_reintegro_formaPago($reintegro_formaPago_id, 
                                        $reintegro_importe_usd_v,
                                        $reintegro_tc_ars,
                                        $reintegro_importe_ars,
                                        $reintegro_id_fp) {
    
    $resultado = Reintegro::forma_pago($reintegro_formaPago_id, 
                                        $reintegro_importe_usd_v,                                    
                                        $reintegro_tc_ars,
                                        $reintegro_importe_ars,
                                        $reintegro_id_fp);
    
    echo json_encode($resultado);
}

function chequeoItemsPendientes($reintegro_id){
    
    $cantItemsPendientes = Reintegro::chequeoItemsPendientes($reintegro_id);
    
    echo json_encode($cantItemsPendientes);
}

function formulario_datosBancarios($reintegro_CBUcuenta_m,
                                    $reintegro_CBUalias_m,
                                    $reintegro_denominacion_m,
                                    $reintegro_documentoTipo_id_m,
                                    $reintegro_beneficiarioDocumento_m,
                                    $reintegro_referenciaTipo_id_m,
                                    $reintegro_referencia_m,
                                    $reintegro_avisoTransTipo_id_m,
                                    $reintegro_emailDestinatario_m,
                                    $reintegro_emailTexto_m,
                                    $reintegro_compania_m,
                                    $reintegro_codigoArea_m,
                                    $reintegro_telefono_m,
                                    $reintegro_id_db,
                                    $reintegro_banco_m,
                                    $reintegro_digito_verificacion_titular_m,
                                    $reintegro_mail_titular_m,
                                    $reintegro_tipo_cuenta_m,
                                    $reintegro_direccion_titular_m,
                                    $reintegro_ciudad_m) {
    
    $resultado = Reintegro::actualizar_datosBancarios($reintegro_CBUcuenta_m,
                                                      $reintegro_CBUalias_m,
                                                      $reintegro_denominacion_m,
                                                      $reintegro_documentoTipo_id_m,
                                                      $reintegro_beneficiarioDocumento_m,
                                                      $reintegro_referenciaTipo_id_m,
                                                      $reintegro_referencia_m,
                                                      $reintegro_avisoTransTipo_id_m,
                                                      $reintegro_emailDestinatario_m,
                                                      $reintegro_emailTexto_m,
                                                      $reintegro_compania_m,
                                                      $reintegro_codigoArea_m,
                                                      $reintegro_telefono_m,
                                                      $reintegro_id_db,
                                                      $reintegro_banco_m,
                                                      $reintegro_digito_verificacion_titular_m,
                                                      $reintegro_mail_titular_m,
                                                      $reintegro_tipo_cuenta_m,
                                                      $reintegro_direccion_titular_m,
                                                      $reintegro_ciudad_m);
    
    echo json_encode($resultado);
}

function formulario_alta_ri($reintegroItem_reintegro_id_n,
                            $reintegroItem_concepto_id_n,
                            $reintegroItem_importeOrigen_n,
                            $reintegroItem_moneda_id_n,
                            $reintegroItem_monedaTC_n,
                            $reintegroItem_importeUSD_n,
                            $reintegroItem_observaciones_n,
                            $sesion_usuario_id) {
    
    
    $resultado_insert = Reintegro::insertar_ri($reintegroItem_reintegro_id_n,
                                               $reintegroItem_concepto_id_n,
                                               $reintegroItem_importeOrigen_n,
                                               $reintegroItem_moneda_id_n,
                                               $reintegroItem_monedaTC_n,
                                               $reintegroItem_importeUSD_n,
                                               $reintegroItem_observaciones_n,
                                               $sesion_usuario_id);

    echo json_encode($resultado_insert);
}

function formulario_lectura_ri($reintegroItem_id){
    
    $reintegroItem = Reintegro::buscarItemPorId($reintegroItem_id);
    
    echo json_encode($reintegroItem);
}

function formulario_modificacion_ri($reintegroItem_id,
                                    $reintegroItem_concepto_id,
                                    $reintegroItem_importeOrigen,
                                    $reintegroItem_moneda_id,
                                    $reintegroItem_monedaTC,
                                    $reintegroItem_importeUSD,
                                    $reintegroItem_observaciones,
                                    $sesion_usuario_id) {
    
    
    $resultado_update = Reintegro::actualizar_ri($reintegroItem_id,
                                                 $reintegroItem_concepto_id,
                                                 $reintegroItem_importeOrigen,
                                                 $reintegroItem_moneda_id,
                                                 $reintegroItem_monedaTC,
                                                 $reintegroItem_importeUSD,
                                                 $reintegroItem_observaciones,
                                                 $sesion_usuario_id);

    echo json_encode($resultado_update);
}

function formulario_autorizacion_ri($ri_id_au,
                                    $ri_mov_auditoria_auto_id,
                                    $ri_fechaPago_auto,
                                    $ri_formaPago_auto_id,
                                    $ri_observaciones_auto,
                                    $sesion_usuario_id,
                                    $ri_importeAprobadoUSD_au,
                                    $ri_importeAprobadoUSD_auto) {
    
    $riMov_ri_id                   = $ri_id_au;
    $riMov_riEstado_id             = $ri_mov_auditoria_auto_id;
    $riMov_fechaPago               = $ri_fechaPago_auto;
    $riMov_formaPago_id            = $ri_formaPago_auto_id;
    $riMov_observaciones           = $ri_observaciones_auto;
    $riMov_usuario_id              = $sesion_usuario_id;
    
    if($riMov_riEstado_id == 5 || $riMov_riEstado_id == 6 || $riMov_riEstado_id == 7 ) {
       $riMov_importeAprobadoUSD   = $ri_importeAprobadoUSD_au;
    }else{    
       $riMov_importeAprobadoUSD   = $ri_importeAprobadoUSD_auto;
    }
            
    $resultado = Reintegro::insertar_riMovimiento($riMov_ri_id,
                                                  $riMov_riEstado_id,
                                                  $riMov_fechaPago,
                                                  $riMov_formaPago_id,
                                                  $riMov_observaciones,
                                                  $riMov_usuario_id,
                                                  $riMov_importeAprobadoUSD);

    echo json_encode($resultado);
}

function ri_pendiente_autorizar($reintegroItem_id) {
    
    $resultado = Reintegro::info_ri_pendiente_autorizar($reintegroItem_id);
    
    echo json_encode($resultado);
}


// Funciones auxiliares de formularios
function calcular_importe_usd($importe_origen, $tipo_cambio){

    $calculo = ($importe_origen / $tipo_cambio);

    echo json_encode($calculo);
}

function valida_estado_items($reintegro_id) {
    
    $resultado_1 = Reintegro::valida_estado_items($reintegro_id);

    $resultado_2 = Reintegro::tiene_rei($reintegro_id);

    echo json_encode(array($resultado_1, $resultado_2));

}

function auditar_reintegro($reintegro_id, $auditoria_tipo) {

    $resultado = Reintegro::auditar_reintegro($reintegro_id, $auditoria_tipo);
    
    echo json_encode($resultado);
}

function retener_reintegro($reintegro_id) {

    $resultado = Reintegro::retener_reintegro($reintegro_id);
    
    echo json_encode($resultado);
}

function liberar_reintegro($reintegro_id) {

    $resultado = Reintegro::liberar_reintegro($reintegro_id);
    
    echo json_encode($resultado);
}

function rollback_reintegro($reintegro_id) {

    $resultado = Reintegro::rollback_reintegro($reintegro_id);
    
    echo json_encode($resultado);
}

function rollback_reintegro_pendDoc($reintegro_id) {

    $resultado = Reintegro::rollback_reintegro_pendDoc($reintegro_id);
    
    echo json_encode($resultado);
}

function rollback_reintegro_enProceso($reintegro_id) {

    $resultado = Reintegro::rollback_reintegro_enProceso($reintegro_id);
    
    echo json_encode($resultado);
}


// Select - Formularios Alta Reintegros
function listar_estadosReintegro_alta(){

    $estados_reintegros = Reintegro::listar_estadosReintegro_alta();

    echo json_encode($estados_reintegros);   
}

function listar_documentoTipos_alta(){

    $tipos_documento = Reintegro::listar_documentoTipos_alta();

    echo json_encode($tipos_documento);   
}
function listar_documentoTipos_modificacion($reintegro_id){

    $tipos_documento = Reintegro::listar_documentoTipos_modificacion($reintegro_id);

    echo json_encode($tipos_documento);   
}

function listar_referenciaTipos_alta(){

    $tipos_ref = Reintegro::listar_referenciaTipos_alta();

    echo json_encode($tipos_ref);   
}
function listar_referenciaTipos_modificacion($reintegro_id){

    $tipos_ref = Reintegro::listar_referenciaTipos_modificacion($reintegro_id);

    echo json_encode($tipos_ref);   
}

function listar_avisoTransTipos_alta(){

    $tipos_avisoTrans = Reintegro::listar_avisoTransTipos_alta();

    echo json_encode($tipos_avisoTrans);   
}
function listar_avisoTransTipos_modificacion($reintegro_id){

    $tipos_avisoTrans = Reintegro::listar_avisoTransTipos_modificacion($reintegro_id);

    echo json_encode($tipos_avisoTrans);   
}

function listar_riConceptos_alta(){

    $riConceptos = Reintegro::listar_riConceptos_alta();

    echo json_encode($riConceptos);   
}

function listar_riConceptos_modificacion($reintegroItem_id){

    $riConceptos = Reintegro::listar_riConceptos_modificacion($reintegroItem_id);

    echo json_encode($riConceptos);   
}

function listar_riMonedas_alta(){

        $monedas = Reintegro::listar_riMonedas_alta();

        echo json_encode($monedas);   
    }
    
function listar_riMonedas_modificacion($reintegroItem_id){

        $monedas = Reintegro::listar_riMonedas_modificacion($reintegroItem_id);

        echo json_encode($monedas);   
    }
    
function listar_movEstados_alta($ri_estado_id_au){

    $movEstados = Reintegro::listar_movEstados_alta($ri_estado_id_au);

    echo json_encode($movEstados);
}

function listar_formasPagos_reintegro(){

    $formas_pagos = FormaPago::listar_reint();

    echo json_encode($formas_pagos);
}

function formulario_vistaDatosCaso($caso_id){
    
    $resultado = Reintegro::buscarDatosGeneralesCaso($caso_id);
    
    echo json_encode($resultado);
}



// Funciones de Grilla
//
// Grilla Reintegros
function grilla_listar($caso_id){
    
    $reintegros  = Reintegro::listarPorCaso($caso_id);
    
    $grilla = "<div class='row'>";
    $grilla .= "<div id='grilla_abm' class='col-sm-12'>";
    $grilla .=  "<div class='card-box table-responsive'>";
    $grilla .=      "<h4 class='m-t-0 header-title'><b>Reintegros</b></h4>";
    $grilla .=      "<table id='dt_reintegros' class='table table-hover table-striped m-0 table-responsive'>";
    $grilla .=          "<thead>";
    $grilla .=              "<tr>";
    $grilla .=                  "<th>Estado</th>";
    $grilla .=                  "<th>Presentacion</th>";
    $grilla .=                  "<th>Auditado</th>";
    $grilla .=                  "<th>Pago</th>";
    $grilla .=                  "<th>Usuario Asignado</th>";
    $grilla .=                  "<th>Acciones</th>";
    $grilla .=              "</tr>";
    $grilla .=          "</thead>";
    $grilla .=          "<tbody>";  
    foreach($reintegros as $reintegro) {
            $reintegro_id               = $reintegro["reintegro_id"];
            $estadoNombre               = $reintegro["reintegroEstado_nombre"];
            $fechaPresentacionANSI      = $reintegro["reintegro_fechaPresentacion"];
            $fechaPresentacion          = date("d-m-Y", strtotime($fechaPresentacionANSI));
            $fechaAuditadoANSI          = $reintegro["reintegro_fechaAuditado"];
            $fechaAuditado              = date("d-m-Y", strtotime($fechaAuditadoANSI));
            $fechaPagoANSI              = $reintegro["reintegro_fechaPago"];
            $fechaPago                  = date("d-m-Y", strtotime($fechaPagoANSI));
            $reintegroAgenda_id         = $reintegro["reintegroAgenda_id"];
            $asignadoNombre             = $reintegro["usuario_nombre"];
            $asignadoApellido           = $reintegro["usuario_apellido"];
    $grilla .=              "<tr>";
    $grilla .=                  "<td>";
    $grilla .=                      $estadoNombre;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $fechaPresentacion;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    if ($fechaAuditado == '31-12-1969') {
        $grilla .=                      ''; 
    } else {
        $grilla .=                      $fechaAuditado;    
    }
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    if ($fechaPago == '31-12-1969') {
        $grilla .=                      ''; 
    } else {
        $grilla .=                      $fechaPago;    
    }
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    if($reintegroAgenda_id != ''){
    $grilla .=                      "<span class='label label-success'>" . $asignadoNombre . ' ' . $asignadoApellido . "</span>";
    }else{
    $grilla .=                      "<span class='label label-danger'>Sin Asignar</span>";
    }
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      "<a href='javascript:void(0)'> <i onclick='formulario_lectura($reintegro_id)' class='fa fa-search' data-toggle='tooltip' data-placement='top' title='Ver reintegro'></i></a>";
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

// Grilla Reintegros
function grilla_listar_items($reintegro_id, $permisos){
    
    $reintegroItems  = Reintegro::listarItemsPorReintegro($reintegro_id);
    
    $grilla = "<div class='row'>";
    $grilla .= "<div id='grilla_abm' class='col-sm-12'>";
    $grilla .=  "<div class='card-box table-responsive'>";
    $grilla .=      "<h4 class='m-t-0 header-title'><b>Items del reintegro</b></h4>";
    $grilla .=      "<table id='dt_reintegros' class='table table-hover table-striped m-0 table-responsive'>";
    $grilla .=          "<thead>";
    $grilla .=              "<tr>";
    $grilla .=                  "<th>Estado</th>";
    $grilla .=                  "<th>Fecha</th>";
    $grilla .=                  "<th>Concepto</th>";
    $grilla .=                  "<th>Importe Origen</th>";
    $grilla .=                  "<th>Moneda</th>";
    $grilla .=                  "<th>TC</th>";
    $grilla .=                  "<th>Importe USD</th>";
    $grilla .=                  "<th>Importe Aprobado</th>";
    $grilla .=                  "<th>Usuario</th>";
    $grilla .=                  "<th>Acciones</th>";
    $grilla .=              "</tr>";
    $grilla .=          "</thead>";
    $grilla .=          "<tbody>";  
    foreach($reintegroItems as $reintegroItem) {
            $reintegroItem_id = $reintegroItem["reintegroItem_id"];
            $reintegroItem_estado_id = $reintegroItem["riMov_riEstado_id"];
            $riEstado_nombre = $reintegroItem["riEstado_nombre"];
            $riEstado_sector = $reintegroItem["riEstado_sector"];
            $reintegroItem_fechaANSI = $reintegroItem["reintegroItem_fecha"];
            $reintegroItem_fecha     = date("d-m-Y H:i:s", strtotime($reintegroItem_fechaANSI));
            $riConcepto_nombre = $reintegroItem["riConcepto_nombre"];
            $reintegroItem_importeOrigen = $reintegroItem["reintegroItem_importeOrigen"];
            $reintegroItem_importeOrigen = number_format($reintegroItem_importeOrigen, 2, ',', '.');
            $reintegroItem_moneda_nombre = $reintegroItem["moneda_nombre"];
            $reintegroItem_monedaTC = $reintegroItem["reintegroItem_monedaTC"];
            $reintegroItem_importeUSD = $reintegroItem["reintegroItem_importeUSD"];
            $reintegroItem_importeUSD = number_format($reintegroItem_importeUSD, 2, ',', '.');
            $reintegroItem_importeAprobadoUSD = $reintegroItem["riMov_importeAprobadoUSD"];
            $reintegroItem_importeAprobadoUSD = number_format($reintegroItem_importeAprobadoUSD, 2, ',', '.');
            $usuario = $reintegroItem["usuario_usuario"];            
    $grilla .=              "<tr>";
    $grilla .=                  "<td>";
            if ($reintegroItem_estado_id == 1) { // Estado = Item Ingresado
                $grilla .=  "<span class='label label-default'>" . $riEstado_nombre . "</span>";
            } else if ($reintegroItem_estado_id == 4 || $reintegroItem_estado_id == 6) { // Estados = Item Rechazado
                $grilla .=  "<span class='label label-danger'>" . $riEstado_nombre . ": " . $riEstado_sector . "</span>";
            } else { // Estados = Item Aprobado o Aprovado parcial
                $grilla .=  "<span class='label label-success'>" . $riEstado_nombre . ": " . $riEstado_sector . "</span>";
            }
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $reintegroItem_fecha;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $riConcepto_nombre;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $reintegroItem_importeOrigen;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $reintegroItem_moneda_nombre;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $reintegroItem_monedaTC;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $reintegroItem_importeUSD;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $reintegroItem_importeAprobadoUSD;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    $grilla .=                      $usuario;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
                                    // Permiso Modificar ITEM de Reintegro
                                    if (Usuario::puede("ri_modificacion") == 1) {    
                                    // Estados de item - Ingresado
                                        if ($reintegroItem_estado_id == 1) {
    $grilla .=                              "<a href='javascript:void(0)'> <i onclick='formulario_lectura_ri($reintegroItem_id)' class='fa fa-edit' data-toggle='tooltip' data-placement='top' title='Editar Item'></i></a>";
                                        }
                                    }
                                    // Permisos Auditoria y Pago ITEM de Reintegro
                                    // Estados de item - Ingresado 
                                    if ($reintegroItem_estado_id == 1) {
                                        // Permiso Auditoria N1
                                        if (Usuario::puede("ri_auto_auditoriaN1") == 1) {
    $grilla .=                              "<a href='javascript:void(0)'> <i onclick='autorizar_ri($reintegroItem_id)' class='fa fa-check-square-o' data-toggle='tooltip' data-placement='top' title='Autorizar Item'></i></a>";                                        
                                        }
                                    }
    $grilla .=                      "<a href='javascript:void(0)'> <i onclick='grilla_observaciones_ri($reintegroItem_id)' class='fa fa-search' data-toggle='tooltip' data-placement='top' title='Ver observaciones'></i></a>";
    $grilla .=                      "<a href='javascript:void(0)'> <i onclick='grilla_movimientos_ri($reintegroItem_id)' class='fa fa-search' data-toggle='tooltip' data-placement='top' title='Ver movimientos'></i></a>";
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

// Grilla movimientos de item de reintegro
function grilla_listar_mov_ri($reintegroItem_id){
    
    $movimientos_ri             = Reintegro::listar_mov_ri($reintegroItem_id);
    $movimientos_ri_contador    = Reintegro::contar_mov_ri($reintegroItem_id);
    $movimiento_ri_numero       = $movimientos_ri_contador["riMov_cantidad"] + 1;
    
    $grilla = '<div class="row">';
    $grilla .=   '<div class="col-sm-12">';
    $grilla .=       '<div class="timeline">';
    $grilla .=           '<article class="timeline-item alt">';
    $grilla .=               '<div class="text-right">';
    $grilla .=                   '<div class="time-show first">';
    $grilla .=                       '<a href="#" class="btn btn-primary w-lg">Item de Reintegro</a>';
    $grilla .=                   '</div>';
    $grilla .=               '</div>';
    $grilla .=           '</article> ';  
    
    foreach($movimientos_ri as $movimiento_ri) {
            $movimiento_ri_numero   = $movimiento_ri_numero - 1;
            
            $fechaEvento            = $movimiento_ri["fechaEvento"];
            $usuarioMov             = $movimiento_ri["usuarioNombreMov"] . ' ' . $movimiento_ri["usuarioApellidoMov"];
            $estadoMov              = $movimiento_ri["estadoMovId"];
            $estadoMovDesc          = $movimiento_ri["estadoMovDesc"];
            $observacionesMov       = $movimiento_ri["observacionesMov"];
            $importeAprobadoUSD     = $movimiento_ri["importeAprobadoUSD"];
            $fechaPagoMov           = $movimiento_ri["fechaPago"];
            $formaPagoMov           = $movimiento_ri["formaPago"];
            
        // Setea la posicion del cuadro dependiendo si es par o impar (aplica solo para la presentacion grafica)
        if ($movimiento_ri_numero % 2 == 0) {
            $article_pos = '<article class="timeline-item alt">';
            $span_post = '<span class="arrow-alt panel-logFacturas"></span>';
        } else {
            $article_pos = '<article class="timeline-item">';
            $span_post = '<span class="arrow"></span>';
        }

        // Segun el estado de movimiento, es el color del texto
        if ($estadoMov == 1) { // Item ingresado
            $text_color = 'text-default';
        } else if ($estadoMov == 2 || $estadoMov == 3 || $estadoMov == 5) { // Item Aprobado
            $text_color = 'text-success';
        } else if ($estadoMov == 4 || $estadoMov == 6) { // Item Rechazado
            $text_color = 'text-danger';
        } else if ($estadoMov == 7) { // Item pagado
            $text_color = 'text-primary';
        }

        // Armar texto del log
        $texto = '<b class="' . $text_color . '">' . $estadoMovDesc . '</b><br>';
        if ($importeAprobadoUSD > 0)   {
        $texto .= ' <b>Importe Aprobado USD:</b> ' . $importeAprobadoUSD . '<br>'; 
        }
        if ($observacionesMov != NULL) {
        $texto .= ' <b>Observaciones:</b> ' . $observacionesMov . '<br>';    
        }
        if ($fechaPagoMov != NULL && $formaPagoMov != NULL) {
        $texto .= ' <b>Fecha de pago:</b> ' . $fechaPagoMov . ' <b>Forma de pago:</b> ' . $formaPagoMov;    
        }
        
        $fecha = date('d M Y - h:i', strtotime($fechaEvento));
        
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
    $grilla .="</div>";

    echo $grilla;
}

// Muestra las observaciones de un item de reintegro
function grilla_observaciones_ri($reintegroItem_id){
    
    $reintegroItem             = Reintegro::buscarItemPorId($reintegroItem_id);
    
    $grilla  = "<div class='row'>";
    $grilla .= "<div id='grilla_abm' class='col-sm-12'>";
    $grilla .=  "<div class='card-box-form table-responsive'>";
    $grilla .=      "<table id='dt_reintegros' class='table table-hover m-0'>";
    $grilla .=          "<thead>";
    $grilla .=              "<tr>";
    $grilla .=                  "<th class='col-sm-9'>Observaciones</th>";
    $grilla .=              "</tr>";
    $grilla .=          "</thead>";
    $grilla .=          "<tbody>";  
            $observaciones                  = $reintegroItem["reintegroItem_observaciones"];
    $grilla .=              "<tr>";        
    $grilla .=                  "<td>";
    $grilla .=                      "<p style='white-space:pre-wrap;'>" . $observaciones . "</p>";
    $grilla .=                  "</td>";
    $grilla .=              "</tr>";
    $grilla .=          "</tbody>";
    $grilla .=       "</table>";
    $grilla .=    "</div>";
    $grilla .=  "</div>";
    $grilla .="</div>";   
    
    echo $grilla;
}
