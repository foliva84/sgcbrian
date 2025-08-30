<?php 
    require '../seguridad/seguridad.php';
    
    $reintegros_ver = array_search('reintegros_ver', array_column($permisos, 'permiso_variable'));
    if (empty($reintegros_ver) && ($reintegros_ver !== 0)) {
        header("location:../_errores/401.php");
    } else {
    
    $caso_id = isset($_GET["caso_id"])?$_GET["caso_id"]:'';
?>
    <!-- INICIO - Includes -->
    <?php $pagina = "Reintegro"; ?>
    <?php require '../includes/header_start.php'; ?>
    <?php require '../includes/header_datatables.php'; ?>
    <?php require '../includes/header_end_sin_menu.php'; ?>
    <link href="../assets/css/iconos_propios.css" rel="stylesheet" type="text/css">
    <?php require '../includes/header_pagina.php'; ?>
    <!-- FIN - Includes -->  
    
    <!-- INICIO - Panel Formularios y Grilla  -->
    <div class="panel panel-default users-content">
        <!-- INICIO - Panel Formulario Datos Generales Caso  -->
        <div class="panel-body" id="panel_formulario_vistaDatosCaso">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card-box-form">
                        <!-- Acción del formulario en opcion  -->
                        <input type="hidden" id="opcion" name="opcion" value="formulario_vistaDatosCaso">
                        <input type="hidden" id="caso_id_dGeneral" name="caso_id_dGeneral" value="<?php echo $caso_id?>">
                        <h4 class="m-t-0 m-b-20 header-title"><b>Datos Generales del Caso</b></h4>
                        <div class="row">
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label class="control-label" for="caso_numero_dGeneral">Caso</label>
                                    <input class="form-control" id="caso_numero_dGeneral" name="caso_numero_dGeneral" type="text" readonly="readonly">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label class="control-label" for="caso_fechaSiniestro_dGeneral">Fecha Siniestro</label>
                                    <input class="form-control" id="caso_fechaSiniestro_dGeneral" name="caso_fechaSiniestro_dGeneral" type="text" readonly="readonly">
                                </div>
                            </div>    
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label" for="caso_beneficiarioNombre_dGeneral">Nombre del Beneficiario</label>
                                    <input class="form-control" id="caso_beneficiarioNombre_dGeneral" name="caso_beneficiarioNombre_dGeneral" type="text" readonly="readonly">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label" for="caso_voucher_dGeneral">Voucher</label>
                                    <input class="form-control" id="caso_voucher_dGeneral" name="caso_voucher_dGeneral" type="text" readonly="readonly">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label" for="caso_producto_dGeneral">Producto</label>
                                    <input class="form-control" id="caso_producto_dGeneral" name="caso_producto_dGeneral" type="text" readonly="readonly">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label" for="caso_agencia_dGeneral">Agencia</label>
                                    <input class="form-control" id="caso_agencia_dGeneral" name="caso_agencia_dGeneral" type="text" readonly="readonly">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label" for="caso_pais_nombre_dGeneral">Pais</label>
                                    <input class="form-control" id="caso_pais_nombre_dGeneral" name="caso_pais_nombre_dGeneral" type="text" readonly="readonly">
                                </div>
                            </div>
                        </div>  
                    </div>
                </div>
            </div>
        </div>   
        <!-- FIN - Panel Formulario Datos Generales Caso  -->
            
        <?php
            $reintegros_alta = array_search('reintegros_alta', array_column($permisos, 'permiso_variable'));
            if (!empty($reintegros_alta) || ($reintegros_alta === 0)) { 
        ?>
            <div class="panel-heading">
                <button id="btn_nuevo_reintegro" onclick="javascript:preparar_formulario_alta();" class="btn btn-primary waves-effect waves-light m-l-5"> Nuevo Reintegro <i class="glyphicon glyphicon-plus" ></i></button>
            </div>   
        <?php } ?>
        <!-- INICIO - Formulario Alta Reintegro -->
        <div class="panel-body hidden" id="panel_formulario_alta">
            <div class="row">
                <form id="formulario_alta" name="formulario_alta">
                    <!-- Acción del formulario en opcion  -->
                    <input type="hidden" id="opcion" name="opcion" value="formulario_alta">
                    <input type="hidden" id="caso_id" name="caso_id" value="<?php echo $caso_id?>">
                    <div class="col-sm-12">
                        <div class="card-box-form">
                            <div class="row">
                                <h4 class="m-t-0 m-b-20 header-title"><b>Datos Reintegro</b></h4>
                                <div class="col-md-2">
                                    <div class="input-group has-warning">
                                        <label class="control-label has-warning">Fecha Presentacion</label>
                                    </div>
                                    <div class="input-group col-md-12 has-warning">
                                        <input id="reintegro_fechaPresentacion_n" name="reintegro_fechaPresentacion_n" class="form-control" type="text" placeholder="dd-mm-aaaa">
                                        <i class="ion-calendar form-control-feedback l-h-34"></i>
                                    </div>
                                </div>
                                                               
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <label class="control-label">Observaciones</label>
                                        <textarea id="reintegro_observaciones_n" name="reintegro_observaciones_n" class="form-control" rows="2" maxlength="300"></textarea>
                                    </div>
                                </div>
                                
                                <div class="col-sm-12">
                                    <div class="form-group text-right m-b-0">
                                        <button type="reset" id="btn_cancelar_nuevo" class="btn btn-inverse waves-effect waves-light">Cancelar</button>
                                        <?php
                                            // Permiso para la carga de un nuevo reintegro
                                            $reintegros_alta = array_search('reintegros_alta', array_column($permisos, 'permiso_variable'));
                                            if (!empty($reintegros_alta) || ($reintegros_alta === 0)) { 
                                        ?>    
                                            <button type="submit" class="btn btn-success waves-effect waves-light">Crear Reintegro</button>     
                                        <?php } ?>
                                    </div>
                                </div>                                
                            </div>
                        </div>
                    </div>
                </form>    
            </div>
        </div>
        <!-- FIN - Formulario Alta Reintegro -->
        
        <!-- INICIO - Panel grilla de reintegros  -->
        <div class="panel-body" id="panel_grilla">
            <div id="grilla_reintegros"></div>
        </div>
        
        <!-- INICIO - Menu Tabs -->
        <div class="col-sm-12 hidden" id="menu_tabs">
            <ul class="nav nav-tabs navtab-custom nav-justified">
                <li id="tabReintegro" class="active tab">
                    <a href="#reintegro" data-toggle="tab" aria-expanded="false">
                        <span class="visible-xs"><i class="fa fa-home"></i></span>
                        <span class="hidden-xs">Reintegro</span>
                    </a>
                </li>
                <li id="tabReintegroItems" class="tab">
                    <a href="#reintegroItems" data-toggle="tab" aria-expanded="false">
                        <span class="visible-xs"><i class="fa fa-home"></i></span>
                        <span class="hidden-xs">Items del Reintegro</span>
                    </a>
                </li>
                <li id="tabComunicacionesR" class="">
                    <a href="#comunicacionesR" data-toggle="tab" aria-expanded="false">
                        <span class="visible-xs"><i class="fa fa-user"></i></span>
                        <span class="hidden-xs">Comunicaciones</span>
                    </a>
                </li>                 
                <li id="tabReintegroArchivos" class="tab">
                    <a href="#reintegroArchivos" data-toggle="tab" aria-expanded="false">
                        <span class="visible-xs"><i class="fa fa-home"></i></span>
                        <span class="hidden-xs">Archivos</span>
                    </a>
                </li>
            </ul>   
        </div>
        <!-- FIN - Menu Tabs -->

        <!-- INICIO - Modal Autorizacion de Item de Reintegro  -->
        <div id="modal_pago_reintegro" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal_pago_reintegro_label" aria-hidden="true" style="display: none;">
            <div class="modal-dialog" style="width:80%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <b>Forma de Pago del Reintegro</b>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <!-- INICIO - Forma de Pago del Reintegros  -->
                        <form id="formulario_reintegro_formaPago" name="formulario_reintegro_formaPago">
                            <input type="hidden" id="opcion" name="opcion" value="formulario_reintegro_formaPago">
                            <input type="hidden" id="reintegro_id_fp" name="reintegro_id_fp">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="card-box-form">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="form-group has-warning">
                                                    <label class="control-label">Forma de Pago</label>
                                                    <select name="reintegro_formaPago_id" id="reintegro_formaPago_id" class="form-control"></select>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">Importe Total (USD)</label>
                                                    <input name="reintegro_importe_usd_v" id="reintegro_importe_usd_v" type="text" class="form-control" readonly="readonly">
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <label class="control-label">T/C</label>
                                                    <input name="reintegro_tc_ars" id="reintegro_tc_ars" type="text" class="form-control" maxlength="6">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group has-warning">
                                                    <label class="control-label">Importe Total (ARS)</label>
                                                    <input name="reintegro_importe_ars" id="reintegro_importe_ars" type="text" class="form-control" maxlength="13">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="card-box-form">
                                        <div class="form-group text-right m-b-0">
                                            <button type="reset" id="btn_cancelarReintegro_formPago" data-dismiss="modal" class="btn btn-inverse waves-effect waves-light">Cancelar</button>
                                            <button type="submit" id="btn_generar_pago" class="btn btn-success waves-effect waves-light">Guardar</button>     
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <!-- FIN - Forma de Pago del Reintegros  -->
                    </div>
                </div>
            </div>
        </div>
        <!-- FIN - Modal Autorizacion de Reintegros  -->
        
        <!-- INICIO - Tabs Edicion Reintegro -->
        <div class="tab-content">
            <!-- INICIO - Tab Reintegro -->
            <div class="tab-pane active" id="reintegro">
                <!-- INICIO - Formulario Ver Reintegro -->
                <div class="panel-body hidden" id="panel_formulario_vista">
                    <div class="row">
                        <form id="formulario_vista" name="formulario_vista">
                            <!-- Acción del formulario en opcion  -->
                            <input type="hidden" id="reintegro_id_v" name="reintegro_id_v" class="form-control" readonly="true">
                            <div class="col-sm-12">
                                <div class="card-box-form">
                                    <div class="row">
                                        <h4 class="m-t-0 m-b-20 header-title"><b>Datos Generales</b></h4>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Estado</label>
                                                <input name="reintegro_estado_nombre_v" id="reintegro_estado_nombre_v" type="text" class="form-control" readonly="readonly">
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <label class="control-label">Presentacion</label>
                                                <input name="reintegro_fechaPresentacion_v" id="reintegro_fechaPresentacion_v" type="text" class="form-control" readonly="readonly">
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <label class="control-label">Auditado</label>
                                                <input name="reintegro_fechaAuditado_v" id="reintegro_fechaAuditado_v" type="text" class="form-control" readonly="readonly">
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <label class="control-label">Pago</label>
                                                <input name="reintegro_fechaPago_v" id="reintegro_fechaPago_v" type="text" class="form-control" readonly="readonly">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Forma Pago</label>
                                                <input name="reintegro_formaPago_v" id="reintegro_formaPago_v" type="text" class="form-control" readonly="readonly">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Importe Total (USD)</label>
                                                <input name="reintegro_valorTotal_usd_v" id="reintegro_valorTotal_usd_v" type="text" class="form-control" readonly="readonly">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Importe Total (ARS)</label>
                                                <input name="reintegro_valorTotal_ars_v" id="reintegro_valorTotal_ars_v" type="text" class="form-control" readonly="readonly">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label">Observaciones</label>
                                                <textarea id="reintegro_observaciones_v" name="reintegro_observaciones_v" class="form-control" rows="2" readonly="readonly"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group text-right m-b-0">

                                                <!-- INICIO - Botones de ACCIONES -->
                                                <div class="btn-group dropup" id="grupo_btn_acciones">
                                                    <button type="button" class="btn btn-primary dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="false">Acciones <span class="caret"></span></button>
                                                    <ul class="dropdown-menu" role="menu">
                                                        <!-- CARGAR Datos Bancarios -->
                                                        <?php if (Usuario::puede("reintegros_modificar")) { ?>
                                                            <li><a href="#" id="btn_modificar_datosbancarios" >Cargar Datos Bancarios</a></li>
                                                        <!-- MODIFICAR Reintegro -->
                                                            <li><a href="#" id="btn_modificar_reintegro" >Modificar Reintegro</a></li>
                                                        <?php } ?>
                                                        <!-- RETENER/LIBERAR Reintegro -->
                                                        <?php if (Usuario::puede("reintegros_retener")) { ?>
                                                            <li><a href="#" id="btn_reintegro_retener" >Retener Reintegro</a></li>
                                                            <li><a href="#" id="btn_reintegro_liberar" >Liberar Reintegro</a></li>
                                                        <?php } ?>
                                                        <!-- ROLLBACK Reintegro -->
                                                        <?php if (Usuario::puede("reintegro_rollback_pendDocumentacion")) { ?>
                                                            <li><a href="#" id="btn_reintegro_rollbackPendDoc" >Cambiar a... 'Pend. Doc.'</a></li>
                                                        <?php } ?>
                                                        <?php if (Usuario::puede("reintegro_rollback_pendDocumentacion")) { ?>    
                                                            <li><a href="#" id="btn_reintegro_rollbackEnProceso" >Cambiar a... 'En Proceso'</a></li>
                                                        <?php } ?>
                                                        <?php if (Usuario::puede("reintegros_retener")) { ?>
                                                            <li><a href="#" id="btn_reintegro_rollbackAuditado" >Cambiar a... 'Auditado'</a></li>
                                                        <?php } ?>
                                                        <!-- FORMA DE PAGO del Reintegro -->
                                                        <li><a href="#" id="btn_reintegro_formaPago" >Forma de Pago</a></li>
                                                    </ul>
                                                </div>
                                                <!-- FIN - Botones de ACCIONES -->

                                                <!-- INICIO - Botones de AUDITORIA -->
                                                <?php if (Usuario::puede("reintegro_auditoria") == 1 || Usuario::puede("reintegro_superior_auditoria") == 1) { ?>
                                                    <div class="btn-group dropup" id="grupo_btn_auditoria">
                                                        <button type="button" class="btn btn-warning dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="false">Auditoria <span class="caret"></span></button>
                                                        <ul class="dropdown-menu" role="menu">
                                                            <li><a href="#" onclick="javascript:proceso_auditoria(1);">Aprobar</a></li>
                                                            <li><a href="#" onclick="javascript:proceso_auditoria(2);">Rechazar</a></li>
                                                        </ul>
                                                    </div>
                                                <?php } ?>
                                                <!-- FIN - Botones de AUDITORIA -->

                                                <button type="reset" id="btn_cerrar_vista" class="btn btn-inverse waves-effect waves-light">Cerrar</button>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>    
                    </div>
                </div>
                <!-- FIN - Formulario Ver Reintegro -->
                
                <!-- INICIO - Formulario Modificacion Reintegro -->
                <div class="panel-body hidden" id="panel_formulario_modificacion">
                    <div class="row">
                        <form id="formulario_modificacion" name="formulario_modificacion">
                            <!-- Acción del formulario en opcion  -->
                            <input type="hidden" id="reintegro_id_m" name="reintegro_id_m" class="form-control reintegro_id_m" readonly="true">
                            <input type="hidden" id="opcion" name="opcion" value="formulario_modificacion" readonly="true">
                            <div class="col-sm-12">
                                <div class="card-box-form">
                                    <div class="row">
                                        <h4 class="m-t-0 m-b-20 header-title"><b>Datos Generales</b></h4>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Estado</label>
                                                <input name="reintegro_estado_nombre_v2" id="reintegro_estado_nombre_v2" type="text" class="form-control" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="input-group has-warning">
                                                <label class="control-label has-warning">Fecha Presentacion</label>
                                            </div>
                                            <div class="input-group col-md-12 has-warning">
                                                <input id="reintegro_fechaPresentacion_m" name="reintegro_fechaPresentacion_m" class="form-control" type="text" placeholder="dd-mm-aaaa">
                                                <i class="ion-calendar form-control-feedback l-h-34"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label">Observaciones</label>
                                                <textarea id="reintegro_observaciones_m" name="reintegro_observaciones_m" class="form-control" rows="2" maxlength="300"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group text-right m-b-0">
                                                <button type="reset" id="btn_cancelar_modificacion" class="btn btn-inverse waves-effect waves-light">Cancelar</button>
                                                <button type="submit" class="btn btn-success waves-effect waves-light">Guardar Modificaciones</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>    
                    </div>
                </div>
                <!-- FIN - Formulario Modificacion Reintegro -->                             
                            
                <!-- INICIO - Formulario Datos Bancarios ARGENTINA -->
                <div class="panel-body hidden" id="panel_formulario_datosBancarios">
                    <div class="row">            
                        <form id="formulario_datosBancarios" name="formulario_datosBancarios">
                            <!-- Acción del formulario en opcion  -->
                            <input type="hidden" id="reintegro_pais" name="reintegro_pais" class="form-control reintegro_pais" readonly="true">
                            <input type="hidden" id="reintegro_id_db" name="reintegro_id_db" class="form-control reintegro_id_db" readonly="true">
                            <input type="hidden" id="opcion" name="opcion" value="formulario_datosBancarios" readonly="true">
                            <div class="col-sm-12">
                                <div class="card-box-form">
                                    <div class="row">
                                        <h4 class="m-t-0 m-b-20 header-title"><b>Datos Bancarios</b></h4>
                                        <div class="col-md-3">
                                            <div class="form-group has-warning">
                                                <label class="control-label">CBU Cuenta</label>
                                                <input id="reintegro_CBUcuenta_m" name="reintegro_CBUcuenta_m" class="form-control reintegro_CBUcuenta_m" type="text" maxlength="22">
                                            </div>
                                        </div> 
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Importe Total (ARS)</label>
                                                <input id="reintegro_importe_m" name="reintegro_importe_m" class="form-control reintegro_importe_m" type="text" maxlength="11" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group has-warning">
                                                <label class="control-label">Denominacion</label>
                                                <input id="reintegro_denominacion_m" name="reintegro_denominacion_m" class="form-control reintegro_denominacion_m" type="text" maxlength="60">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Tipo de Documento</label>
                                                <input id="reintegro_documentoTipo_id_m" name="reintegro_documentoTipo_id_m" class="form-control reintegro_documentoTipo_id_m" type="text" placeholder="CUIT" readonly="readonly">
                                            </div>
                                        </div>                                   
                                        <div class="col-md-3">
                                            <div class="form-group has-warning">
                                                <label class="control-label">Nro de Documento</label>
                                                <input id="reintegro_beneficiarioDocumento_m" name="reintegro_beneficiarioDocumento_m" class="form-control reintegro_beneficiarioDocumento_m" type="text" maxlength="11">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Tipo de Referencia</label>
                                                <input id="reintegro_referenciaTipo_id_m" name="reintegro_referenciaTipo_id_m" class="form-control reintegro_referenciaTipo_id_m" type="text" placeholder="VARIOS" readonly="readonly">
                                            </div>
                                        </div>                                   
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Referencia</label>
                                                <input id="reintegro_referencia_m" name="reintegro_referencia_m" class="form-control reintegro_referencia_m" type="text" placeholder="REEMBOLSOS" readonly="readonly">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Aviso de transferencia</label>
                                                <input id="reintegro_avisoTransTipo_id_m" name="reintegro_avisoTransTipo_id_m" class="form-control reintegro_avisoTransTipo_id_m" type="text" placeholder="EMAIL" readonly="readonly">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Email destinatario</label>
                                                <textarea id="reintegro_emailDestinatario_m" name="reintegro_emailDestinatario_m" class="form-control reintegro_emailDestinatario_m" type="text" rows="2" placeholder="pilar.lozano@coris.com.ar; angeles.sosa@coris.com.ar" readonly="readonly"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <label class="control-label">Email texto</label>
                                                <textarea id="reintegro_emailTexto_m" name="reintegro_emailTexto_m" class="form-control reintegro_emailTexto_m" rows="3" maxlength="255"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="card-box-form">
                                    <div class="form-group text-right m-b-0">
                                        <button type="reset" id="btn_cancelar_datosBancarios" class="btn btn-inverse waves-effect waves-light">Cerrar</button>
                                        <button type="submit" class="btn btn-success waves-effect waves-light">Guardar Datos Bancarios</button>
                                    </div>
                                </div>
                            </div>
                        </form>    
                    </div>
                </div>
                <!-- FIN - Formulario Datos Bancarios -->

                <!-- INICIO - Formulario Datos Bancarios CHILE -->
                <div class="panel-body hidden" id="panel_formulario_datosBancarios_cl">
                    <div class="row">            
                        <form id="formulario_datosBancarios_cl" name="formulario_datosBancarios_cl">
                            <!-- Acción del formulario en opcion  -->
                            <input type="hidden" id="reintegro_pais" name="reintegro_pais" class="form-control reintegro_pais" readonly="true">
                            <input type="hidden" id="reintegro_id_db" name="reintegro_id_db" class="form-control reintegro_id_db" readonly="true">
                            <input type="hidden" id="opcion" name="opcion" value="formulario_datosBancarios" readonly="true">
                            <div class="col-sm-12">
                                <div class="card-box-form">
                                    <div class="row">
                                        <h4 class="m-t-0 m-b-20 header-title"><b>Datos Bancarios Chile</b></h4>
                                        <div class="col-md-3">
                                            <div class="form-group has-warning">
                                                <label class="control-label">Nro de Cuenta</label>
                                                <input id="reintegro_CBUcuenta_m" name="reintegro_CBUcuenta_m" class="form-control reintegro_CBUcuenta_m" type="text"  maxlength="22">
                                            </div>
                                        </div> 
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Importe Total (ARS)</label>
                                                <input id="reintegro_importe_m" name="reintegro_importe_m" class="form-control reintegro_importe_m" type="text" maxlength="11" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group has-warning">
                                                <label class="control-label">Nombre Titular</label>
                                                <input id="reintegro_denominacion_m" name="reintegro_denominacion_m" class="form-control reintegro_denominacion_m" type="text" maxlength="60">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group has-warning">
                                                <label class="control-label">Banco Destino</label>
                                                <input id="reintegro_banco_m" name="reintegro_banco_m" class="form-control reintegro_banco_m" type="text">
                                            </div>
                                        </div>                                   
                                        <div class="col-md-3">
                                            <div class="form-group has-warning">
                                                <label class="control-label">Rut Titular de la cuenta</label>
                                                <input id="reintegro_beneficiarioDocumento_m" name="reintegro_beneficiarioDocumento_m" class="form-control reintegro_beneficiarioDocumento_m" type="text">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Tipo de Referencia</label>
                                                <input id="reintegro_referenciaTipo_id_m" name="reintegro_referenciaTipo_id_m" class="form-control reintegro_referenciaTipo_id_m" type="text" placeholder="VARIOS" readonly="readonly">
                                            </div>
                                        </div>                                   
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Referencia</label>
                                                <input id="reintegro_referencia_m" name="reintegro_referencia_m" class="form-control reintegro_referencia_m" type="text" placeholder="REEMBOLSOS" readonly="readonly">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Aviso de transferencia</label>
                                                <input id="reintegro_avisoTransTipo_id_m" name="reintegro_avisoTransTipo_id_m" class="form-control reintegro_avisoTransTipo_id_m" type="text" placeholder="EMAIL" readonly="readonly">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group has-warning">
                                                <label class="control-label">Digito de Verificacion Titular</label>
                                                <input id="reintegro_digito_verificacion_titular_m" name="reintegro_digito_verificacion_titular_m" class="form-control reintegro_digito_verificacion_titular_m" type="text" maxlength="1">
                                            </div>
                                        </div> 
                                        <div class="col-md-6">
                                            <div class="form-group has-warning">
                                                <label class="control-label">Mail </label>
                                                <input id="reintegro_mail_titular_m" name="reintegro_mail_titular_m" class="form-control reintegro_mail_titular_m" type="text">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Email destinatario</label>
                                                <textarea id="reintegro_emailDestinatario_m" name="reintegro_emailDestinatario_m" class="form-control reintegro_emailDestinatario_m" type="text" rows="2" placeholder="pilar.lozano@coris.com.ar; angeles.sosa@coris.com.ar" readonly="readonly"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label">Email texto</label>
                                                <textarea id="reintegro_emailTexto_m" name="reintegro_emailTexto_m" class="form-control reintegro_emailTexto_m" rows="3" maxlength="255"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="card-box-form">
                                    <div class="form-group text-right m-b-0">
                                        <button type="reset" id="btn_cancelar_datosBancarios_cl" class="btn btn-inverse waves-effect waves-light">Cerrar</button>
                                        <button type="submit" class="btn btn-success waves-effect waves-light">Guardar Datos Bancarios</button>
                                    </div>
                                </div>
                            </div>
                        </form>    
                    </div>
                </div>
                <!-- FIN - Formulario Datos Bancarios -->

                <!-- INICIO - Formulario Datos Bancarios COLOMBIA-->
                <div class="panel-body hidden" id="panel_formulario_datosBancarios_co">
                    <div class="row">            
                        <form id="formulario_datosBancarios_co" name="formulario_datosBancarios_co">
                            <!-- Acción del formulario en opcion  -->
                            <input type="hidden" id="reintegro_pais" name="reintegro_pais" class="form-control reintegro_pais" readonly="true">
                            <input type="hidden" id="reintegro_id_db" name="reintegro_id_db" class="form-control reintegro_id_db" readonly="true">
                            <input type="hidden" id="opcion" name="opcion" value="formulario_datosBancarios" readonly="true">
                            <div class="col-sm-12">
                                <div class="card-box-form">
                                    <div class="row">
                                        <h4 class="m-t-0 m-b-20 header-title"><b>Datos Bancarios Colombia</b></h4>
                                        <div class="col-md-3">
                                            <div class="form-group has-warning">
                                                <label class="control-label">Nro Cuenta</label>
                                                <input id="reintegro_CBUcuenta_m" name="reintegro_CBUcuenta_m" class="form-control reintegro_CBUcuenta_m" type="text"  maxlength="22">
                                            </div>
                                        </div> 
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Importe Total (ARS)</label>
                                                <input id="reintegro_importe_m" name="reintegro_importe_m" class="form-control reintegro_importe_m" type="text" maxlength="11" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group has-warning">
                                                <label class="control-label">Denominacion</label>
                                                <input id="reintegro_denominacion_m" name="reintegro_denominacion_m" class="form-control reintegro_denominacion_m" type="text" maxlength="60">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group has-warning">
                                                <label class="control-label">Nombre del Banco</label>
                                                <input id="reintegro_banco_m" name="reintegro_banco_m" class="form-control reintegro_banco_m" type="text">
                                            </div>
                                        </div>                                   
                                        <div class="col-md-3">
                                            <div class="form-group has-warning">
                                                <label class="control-label">Cédula Titular</label>
                                                <input id="reintegro_beneficiarioDocumento_m" name="reintegro_beneficiarioDocumento_m" class="form-control reintegro_beneficiarioDocumento_m" type="text">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Tipo de Referencia</label>
                                                <input id="reintegro_referenciaTipo_id_m" name="reintegro_referenciaTipo_id_m" class="form-control reintegro_referenciaTipo_id_m" type="text" placeholder="VARIOS" readonly="readonly">
                                            </div>
                                        </div>                                   
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Referencia</label>
                                                <input id="reintegro_referencia_m" name="reintegro_referencia_m" class="form-control reintegro_referencia_m" type="text" placeholder="REEMBOLSOS" readonly="readonly">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Aviso de transferencia</label>
                                                <input id="reintegro_avisoTransTipo_id_m" name="reintegro_avisoTransTipo_id_m" class="form-control reintegro_avisoTransTipo_id_m" type="text" placeholder="EMAIL" readonly="readonly">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group has-warning">
                                                <label class="control-label">Tipo de Cuenta</label>
                                                <select name="reintegro_tipo_cuenta_m" class="form-control reintegro_tipo_cuenta_m" id="reintegro_tipo_cuenta_m">
                                                    <option value="">Seleccione</option>
                                                    <option value="Ahorros">Ahorros</option>
                                                    <option value="Corriente">Corriente</option>
                                                </select>
                                            </div>
                                        </div> 
                                        <div class="col-md-6">
                                            <div class="form-group has-warning">
                                                <label class="control-label">Dirección Titular</label>
                                                <input id="reintegro_direccion_titular_m" name="reintegro_direccion_titular_m" class="form-control reintegro_direccion_titular_m" type="text">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group has-warning">
                                                <label class="control-label">Ciudad</label>
                                                <input id="reintegro_ciudad_m" name="reintegro_ciudad_m" class="form-control reintegro_ciudad_m" type="text">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group has-warning">
                                                <label class="control-label">Teléfono Titular</label>
                                                <input id="reintegro_telefono_m" name="reintegro_telefono_m" class="form-control reintegro_telefono_m" type="text">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group has-warning">
                                                <label class="control-label">Correo electrónico Titular</label>
                                                <input id="reintegro_mail_titular_m" name="reintegro_mail_titular_m" class="form-control reintegro_mail_titular_m" type="email">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Email destinatario</label>
                                                <textarea id="reintegro_emailDestinatario_m" name="reintegro_emailDestinatario_m" class="form-control reintegro_emailDestinatario_m" type="text" rows="2" placeholder="pilar.lozano@coris.com.ar; angeles.sosa@coris.com.ar" readonly="readonly"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <label class="control-label">Email texto</label>
                                                <textarea id="reintegro_emailTexto_m" name="reintegro_emailTexto_m" class="form-control reintegro_emailTexto_m" rows="3" maxlength="255"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="card-box-form">
                                    <div class="form-group text-right m-b-0">
                                        <button type="reset" id="btn_cancelar_datosBancarios_co" class="btn btn-inverse waves-effect waves-light">Cerrar</button>
                                        <button type="submit" class="btn btn-success waves-effect waves-light">Guardar Datos Bancarios</button>
                                    </div>
                                </div>
                            </div>
                        </form>    
                    </div>
                </div>
                <!-- FIN - Formulario Datos Bancarios -->

                <!-- INICIO - Formulario Datos Bancarios URUGUAY -->
                <div class="panel-body hidden" id="panel_formulario_datosBancarios_uy">
                    <div class="row">            
                        <form id="formulario_datosBancarios_uy" name="formulario_datosBancarios_uy">
                            <!-- Acción del formulario en opcion  -->
                            <input type="hidden" id="reintegro_pais" name="reintegro_pais" class="form-control reintegro_pais" readonly="true">
                            <input type="hidden" id="reintegro_id_db" name="reintegro_id_db" class="form-control reintegro_id_db" readonly="true">
                            <input type="hidden" id="opcion" name="opcion" value="formulario_datosBancarios" readonly="true">
                            <div class="col-sm-12">
                                <div class="card-box-form">
                                    <div class="row">
                                        <h4 class="m-t-0 m-b-20 header-title"><b>Datos Bancarios Uruguay</b></h4>
                                        <div class="col-md-3">
                                            <div class="form-group has-warning">
                                                <label class="control-label">Nro de Cuenta</label>
                                                <input id="reintegro_CBUcuenta_m" name="reintegro_CBUcuenta_m" class="form-control reintegro_CBUcuenta_m" type="text"  maxlength="22">
                                            </div>
                                        </div> 
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Importe Total (ARS)</label>
                                                <input id="reintegro_importe_m" name="reintegro_importe_m" class="form-control reintegro_importe_m" type="text" maxlength="11" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group has-warning">
                                                <label class="control-label">Nombre Titular</label>
                                                <input id="reintegro_denominacion_m" name="reintegro_denominacion_m" class="form-control reintegro_denominacion_m" type="text" maxlength="60">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group has-warning">
                                                <label class="control-label">Banco Destino</label>
                                                <input id="reintegro_banco_m" name="reintegro_banco_m" class="form-control reintegro_banco_m" type="text">
                                            </div>
                                        </div>                                   
                                        <div class="col-md-3">
                                            <div class="form-group has-warning">
                                                <label class="control-label">Nro de Documento</label>
                                                <input id="reintegro_beneficiarioDocumento_m" name="reintegro_beneficiarioDocumento_m" class="form-control reintegro_beneficiarioDocumento_m" type="text">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Tipo de Referencia</label>
                                                <input id="reintegro_referenciaTipo_id_m" name="reintegro_referenciaTipo_id_m" class="form-control reintegro_referenciaTipo_id_m" type="text" placeholder="VARIOS" readonly="readonly">
                                            </div>
                                        </div>                                   
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Referencia</label>
                                                <input id="reintegro_referencia_m" name="reintegro_referencia_m" class="form-control reintegro_referencia_m" type="text" placeholder="REEMBOLSOS" readonly="readonly">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Aviso de transferencia</label>
                                                <input id="reintegro_avisoTransTipo_id_m" name="reintegro_avisoTransTipo_id_m" class="form-control reintegro_avisoTransTipo_id_m" type="text" placeholder="EMAIL" readonly="readonly">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Email destinatario</label>
                                                <textarea id="reintegro_emailDestinatario_m" name="reintegro_emailDestinatario_m" class="form-control reintegro_emailDestinatario_m" type="text" rows="2" placeholder="pilar.lozano@coris.com.ar; angeles.sosa@coris.com.ar" readonly="readonly"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <label class="control-label">Email texto</label>
                                                <textarea id="reintegro_emailTexto_m" name="reintegro_emailTexto_m" class="form-control reintegro_emailTexto_m" rows="3" maxlength="255"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="card-box-form">
                                    <div class="form-group text-right m-b-0">
                                        <button type="reset" id="btn_cancelar_datosBancarios_uy" class="btn btn-inverse waves-effect waves-light">Cerrar</button>
                                        <button type="submit" class="btn btn-success waves-effect waves-light">Guardar Datos Bancarios</button>
                                    </div>
                                </div>
                            </div>
                        </form>    
                    </div>
                </div>
                <!-- FIN - Formulario Datos Bancarios -->

            </div>
            <!-- FIN - Tab Reintegro --> 
            
            <!-- INICIO - Tab ITEM Reintegro -->
            <div class="tab-pane" id="reintegroItems">
                <!-- INICIO - Formulario Alta ITEMS Reintegro -->
                <div class="panel-body" id="panel_formulario_alta_ri">
                    <div class="row">
                        <form id="formulario_alta_ri" name="formulario_alta_ri">
                            <!-- Acción del formulario en opcion  -->
                            <input type="hidden" id="reintegroItem_reintegro_id_n" name="reintegroItem_reintegro_id_n" class="form-control" readonly="true">
                            <?php
                                // Permiso para la carga de una nueva factura
                                $ri_alta = array_search('ri_alta', array_column($permisos, 'permiso_variable'));
                                if (!empty($ri_alta) || ($ri_alta === 0)) { 
                            ?>
                            <input type="hidden" id="opcion" name="opcion" value="formulario_alta_ri" readonly="true">
                            <div class="col-sm-12">
                                <div class="card-box-form">
                                    <div class="row">
                                        <h4 class="m-t-0 m-b-20 header-title"><b>Nuevo Item</b></h4>

                                        <div class="col-md-2">
                                            <div class="form-group has-warning">
                                                <label class="control-label">Concepto</label>
                                                <select id="reintegroItem_concepto_id_n" name="reintegroItem_concepto_id_n" class="form-control"></select>
                                            </div>
                                        </div>

                                        <div class="col-md-2 has-warning">
                                            <div class="form-group">
                                                <label for="reintegroItem_importeOrigen_n">Importe Origen</label>
                                                <input class="form-control" id="reintegroItem_importeOrigen_n" name="reintegroItem_importeOrigen_n"  type="text" maxlength="13">
                                            </div>
                                        </div>

                                        <div class="col-md-1">
                                            <div class="form-group has-warning">
                                                <label class="control-label has-warning">Moneda</label>
                                                <select id="reintegroItem_moneda_id_n" name="reintegroItem_moneda_id_n" class="form-control"></select>
                                            </div>
                                        </div>

                                        <div class="col-md-1">
                                            <div class="form-group has-warning">
                                                <label class="control-label">T/C</label>
                                                <input id="reintegroItem_monedaTC_n" name="reintegroItem_monedaTC_n" class="form-control" type="text">
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group has-warning">
                                                <label class="control-label">Importe USD</label>
                                                <input id="reintegroItem_importeUSD_n" name="reintegroItem_importeUSD_n" class="form-control" type="text">
                                            </div>
                                        </div>

                                        <div class="col-md-10">
                                            <div class="form-group">
                                                <label class="control-label">Observaciones</label>
                                                <textarea id="reintegroItem_observaciones_n" name="reintegroItem_observaciones_n" class="form-control" rows="2" maxlength="300"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <label>&nbsp;</label>
                                            <div class="form-group text-right m-b-0">
                                                <button type="reset" id="btn_cancelar_nuevo_ri" class="btn btn-default waves-effect waves-light m-l-5">Cancelar</button>
                                                <button id="btn_guardar_nuevo_ri" class="btn btn-primary waves-effect waves-light" type="submit">Guardar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                                }
                            ?>
                        </form>
                    </div>
                </div>
                <!-- FIN - Formulario Alta ITEM Reintegro -->
                
                <!-- INICIO - Formulario Modificacion ITEM Reintegro-->        
                <div class="panel-body hidden" id="panel_formulario_modificacion_ri">
                    <div class="row">
                        <form id="formulario_modificacion_ri" name="formulario_modificacion_ri">
                            <!-- Acción del formulario en opcion  -->
                            <input type="hidden" id="reintegroItem_id" name="reintegroItem_id" class="form-control" readonly="true">
                            <input type="hidden" id="opcion" name="opcion" value="formulario_modificacion_ri" readonly="true">
                            <div class="col-sm-12">
                                <div class="card-box-form">
                                    <div class="row">
                                        <h4 class="m-t-0 m-b-20 header-title"><b>Modificar Item</b></h4>

                                        <div class="col-md-2">
                                            <div class="form-group has-warning">
                                                <label class="control-label">Concepto</label>
                                                <select id="reintegroItem_concepto_id" name="reintegroItem_concepto_id" class="form-control"></select>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group has-warning">
                                                <label class="control-label">Importe Origen</label>
                                                <input id="reintegroItem_importeOrigen" name="reintegroItem_importeOrigen" class="form-control" type="text" maxlength="13">
                                            </div>
                                        </div>

                                        <div class="col-md-1">
                                            <div class="form-group has-warning">
                                                <label class="control-label has-warning">Moneda</label>
                                                <select id="reintegroItem_moneda_id" name="reintegroItem_moneda_id" class="form-control"></select>
                                            </div>
                                        </div>

                                        <div class="col-md-1">
                                            <div class="form-group has-warning">
                                                <label class="control-label">T/C</label>
                                                <input id="reintegroItem_monedaTC" name="reintegroItem_monedaTC" class="form-control" type="text">
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group has-warning">
                                                <label class="control-label">Importe USD</label>
                                                <input id="reintegroItem_importeUSD" name="reintegroItem_importeUSD" class="form-control" type="text">
                                            </div>
                                        </div>

                                        <div class="col-md-10">
                                            <div class="form-group">
                                                <label class="control-label">Observaciones</label>
                                                <textarea id="reintegroItem_observaciones" name="reintegroItem_observaciones" class="form-control" rows="2" maxlength="300"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <label>&nbsp;</label>
                                            <div class="form-group text-right m-b-0">
                                                <button type="reset" id="btn_cancelar_modificacion_ri" class="btn btn-default waves-effect waves-light m-l-5">Cancelar</button>
                                                <?php
                                                    //$servicios_alta = array_search('servicios_alta', array_column($permisos, 'permiso_variable'));
                                                    //if (!empty($servicios_alta) || ($servicios_alta === 0)) { 
                                                ?>
                                                    <button id="btn_guardar_modificacion_ri" class="btn btn-primary waves-effect waves-light" type="submit">Guardar</button>
                                                <?php //} ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>    
                <!-- FIN - Formulario Modificacion ITEM Reintegro -->
                <div class="panel-body" id="panel_grilla_items">
                    <div id="grilla_reintegroItems"></div>
                </div>
            </div>
            <!-- FIN - Tab ITEM Reintegro -->
            
            <!-- INICIO - Tab COMUNICACIONES Reintegro -->
            <div class="tab-pane" id="comunicacionesR">
                <object type="text/html" id="pantalla_comunicacionesR" width="100%" height="2500"></object>  
            </div>
            <!-- FIN - Tab COMUNICACIONES Reintegro -->
            
            <!-- INICIO - Tab Archivos -->
            <div class="tab-pane" id="reintegroArchivos">
                <object type="text/html" id="pantalla_archivos" width="100%" height="3000"></object>  
            </div>
            <!-- FIN - Tab Archivos -->
        </div>
        <!-- FIN - Tabs Edicion Reintegro -->
        
        <!-- INICIO - Modal Autorizacion de Item de Reintegro  -->
        <div id="modal_auto_reintegros" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal_auto_reintegros_label" aria-hidden="true" style="display: none;">
            <div class="modal-dialog" style="width:80%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <b>Autorizacion de Items de Reintegros</b>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <!-- INICIO - Autorizacion de Reintegros  -->
                        <form id="formulario_autorizacion_ri" name="formulario_autorizacion_ri">
                            <!-- Acción del formulario en opcion  -->
                            <input type="hidden" id="opcion" name="opcion" value="formulario_autorizacion_ri">
                            <input type="hidden" id="ri_id_au" name="ri_id_au">
                            <div class="row">
                                <!-- INICIO - Reintegro Info -->
                                <div class="col-sm-12">
                                    <div class="card-box-form">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Estado del Reintegro / Item</label>
                                                    <input id="ri_estado_au_id" name="ri_estado_au_id" class="form-control" type="hidden" disabled="true">
                                                    <input id="ri_estado_au" name="ri_estado_au" class="form-control" type="text" disabled="true">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Importe USD</label>
                                                    <input id="ri_importeUSD_au" name="ri_importeUSD_au" class="form-control" type="text" disabled="true">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group has-success">
                                                    <label class="control-label">Importe Aprobado USD</label>
                                                    <input id="ri_importeAprobadoUSD_au" name="ri_importeAprobadoUSD_au" class="form-control" type="text" disabled="true">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- FIN - Reintegro Info -->
                                
                                <!-- INICIO - Autorizacion del Reintegro -->
                                <div class="col-sm-12">
                                    <div class="card-box-form">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="form-group autorizacionReintegro has-warning">
                                                    <label class="control-label">Autorizacion</label>
                                                    <select name="ri_mov_auditoria_auto_id" id="ri_mov_auditoria_auto_id" class="form-control"></select>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group importeUSDri">
                                                    <label class="control-label">Importe USD</label>
                                                    <input name="ri_importeUSD_auto" id="ri_importeUSD_auto" type="text" class="form-control" disabled="true">
                                                </div>
                                            </div>                                            
                                            <div class="col-sm-3">
                                                <div class="form-group importeAprobadoRi has-warning">
                                                    <label class="control-label">Importe Aprobado USD</label>
                                                    <input name="ri_importeAprobadoUSD_auto" id="ri_importeAprobadoUSD_auto" type="text" class="form-control" maxlength="12">
                                                </div>
                                            </div>                                            
                                            <div class="col-sm-3">
                                                <div class="form-group importeRechazadoUSDri">
                                                    <label class="control-label">Importe Rechazado USD</label>
                                                    <input name="ri_importeRechazadoUSD_auto" id="ri_importeRechazadoUSD_auto" type="text" class="form-control" disabled="true">
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group fechaPago has-warning">
                                                    <label class="control-label">Fecha de Pago</label>
                                                    <input name="ri_fechaPago_auto" id="ri_fechaPago_auto" type="text" class="form-control" placeholder="dd-mm-aaaa" readonly="true">
                                                </div>
                                            </div> 
                                            <div class="col-sm-3">
                                                <div class="form-group formaPago has-warning">
                                                    <label class="control-label">Forma de Pago</label>
                                                    <select name="ri_formaPago_auto_id" id="ri_formaPago_auto_id" class="form-control"></select>
                                                </div>
                                            </div>                                           
                                            <div class="col-sm-12">        
                                                <div class="form-group">
                                                    <label class="control-label">Observaciones</label>
                                                    <textarea name="ri_observaciones_auto" id="ri_observaciones_auto" class="form-control" rows="2" maxlength="170"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="card-box-form">
                                        <div class="form-group text-right m-b-0">
                                            <button type="reset" id="btn_cancelar_autorizacion_reintegro" class="btn btn-inverse waves-effect waves-light">Cancelar</button>
                                            <button type="submit" class="btn btn-success waves-effect waves-light">Guardar</button>     
                                        </div>
                                    </div>
                                </div>
                                <!-- FIN - Autorizacion del reintegro -->
                            </div>
                        </form>
                        <!-- FIN - Autorizacion de Reintegros  -->
                    </div>
                </div>
            </div>
        </div>
        <!-- FIN - Modal Autorizacion de Reintegros  -->
        
        <!-- INICIO - Modal Movimientos Item de Reintegro -->
        <div id="modal_mov_ri" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal_mov_ri_label" aria-hidden="true" style="display: none;">
            <div class="modal-dialog" style="width:80%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <b>Movimientos del Item de Reintegro</b>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <div id="grilla_mov_ri"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- FIN - Modal Movimientos Item de Reintegro  -->
        
        <!-- INICIO - Modal Observaciones Item de Reintegro -->
        <div id="modal_observaciones_ri" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal_observaciones_ri_label" aria-hidden="true" style="display: none;">
            <div class="modal-dialog" style="width:80%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <b>Observaciones del Item de Reintegro</b>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <div id="grilla_observaciones_ri"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- FIN - Modal Observaciones Item de Reintegro  -->
        
   </div>     
   <!-- FIN - Panel Formularios y Grilla  -->
    
    <!-- INICIO - Includes -->
    <?php require '../includes/footer_start.php' ?>
    <?php require '../includes/footer_notificaciones.php' ?>
    <?php require '../includes/footer_datatable.php' ?>
    <?php require '../includes/footer_validacion.php' ?>
    <script src="../assets/js/jquery.number.min.js"></script>
    <script src="reintegro.js?random=<?php echo uniqid(); ?>"></script>
    <?php require '../includes/footer_end.php' ?>
    <!-- FIN - Includes -->
<?php } ?>