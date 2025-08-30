<?php 
    require '../seguridad/seguridad.php';
    
    $facturas_ver = array_search('facturas_ver', array_column($permisos, 'permiso_variable'));
    if (empty($facturas_ver) && ($facturas_ver !== 0)) {
        header("location:../seguridad/error401.php");
    } else {
?>
    <!-- INICIO - Includes -->
    <?php $pagina = "Facturacion"; ?>
    <?php require '../includes/header_start.php'; ?>
    <?php require '../includes/header_datatables.php'; ?>
    <?php require '../includes/header_end.php'; ?>
    <link href="facturacion_old.css" rel="stylesheet" type="text/css">
    <?php require '../includes/header_pagina.php'; ?>
    <!-- FIN - Includes -->  
    
    <!-- INICIO - Panel Formularios y Grilla  -->
    <div class="panel panel-default users-content">  
        
        <!-- INICIO - Modal Pago de Facturas  -->
        <div id="modalPagoFacturas" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalPagoFacturasLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog" style="width:70%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <b>Pago de Factura</b>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <!-- INICIO - Pago de Facturas  -->
                        <form id="formulario_pago" name="formulario_pago">
                            <!-- Acción del formulario en opcion  -->
                            <input type="hidden" id="opcion" name="opcion" value="formulario_pago">
                            <input type="hidden" id="factura_id_au" name="factura_id_au">
                            <div class="row">
                                <!-- INICIO - Factura Info -->
                                <div class="col-sm-12">
                                    <div class="card-box-form">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Número de Factura</label>
                                                    <input id="factura_numero_au" name="factura_numero_au" class="form-control" type="text" disabled="true">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Estado de la Factura</label>
                                                    <input id="factura_estado_au" name="factura_estado_au" class="form-control" type="text" disabled="true">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Importe USD</label>
                                                    <input id="factura_importeUSD_au" name="factura_importeUSD_au" class="form-control" type="text" disabled="true">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group has-success">
                                                    <label class="control-label">Importe Aprobado USD</label>
                                                    <input id="factura_importeAprobadoUSD_au" name="factura_importeAprobadoUSD_au" class="form-control" type="text" disabled="true">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- FIN - Factura Info -->
                                
                                <!-- INICIO - Pago de la Factura -->
                                <div class="col-sm-12">
                                    <div class="card-box-form">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="form-group fechaPago has-warning">
                                                    <label class="control-label">Fecha de Pago</label>
                                                    <input name="factura_fechaPago_auto" id="factura_fechaPago_auto" type="text" class="form-control" placeholder="dd-mm-aaaa" readonly="true">
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group formaPago has-warning">
                                                    <label class="control-label">Forma de Pago</label>
                                                    <select name="factura_formaPago_auto" id="factura_formaPago_auto" class="form-control"></select>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">        
                                                <div class="form-group">
                                                    <label class="control-label">Observaciones</label>
                                                    <textarea name="factura_observaciones_auto" id="factura_observaciones_auto" class="form-control" rows="2" maxlength="170"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="card-box-form">
                                        <div class="form-group text-right m-b-0">
                                            <button type="reset" id="btn_cancelar_autorizacion_factura" data-dismiss="modal" class="btn btn-inverse waves-effect waves-light">Cancelar</button>
                                            <button type="submit" class="btn btn-success waves-effect waves-light">Guardar</button>     
                                        </div>
                                    </div>
                                </div>
                                <!-- FIN - Pago de la Factura -->
                            </div>
                        </form>
                        <!-- FIN - Pago de Facturas  -->
                    </div>
                </div>
            </div>
        </div>
        <!-- FIN - Modal Pago de Facturas  -->

        <!-- INICIO - Panel Info Caso  -->
        <div class="panel-body" id="panel_info_casos">
            <input type="hidden" class="form-control" id="caso_id" readonly="true">
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group has-success">
                        <label class="control-label">Número de Caso</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="caso_numero_buscar" maxlength="6">
                            <span class="input-group-btn">
                                <button id="btn_lupa" class="btn btn-success waves-effect waves-light"><i class="ion-search" ></i></button>
                            </span>
                        </div>
                    </div>  
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">Nombre Beneficiario</label>
                        <input type="text" class="form-control" id="caso_beneficiario_b" name="caso_beneficiario_b" readonly="readonly">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">Fecha del Caso</label>
                        <input type="text" class="form-control" id="caso_fecha_b" readonly="readonly">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">Voucher</label>
                        <input type="text" class="form-control" id="caso_voucher_b" readonly="readonly">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">Agencia</label>
                        <input type="text" class="form-control" id="caso_agencia_b" readonly="readonly">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">Producto</label>
                        <input type="text" class="form-control" id="caso_producto_b" readonly="readonly">
                    </div>
                </div>
            </div>
        </div>
        <!-- FIN - Panel Info Caso  -->
        
        <!-- INICIO - Formulario Vista -->
        <div class="panel-body hidden" id="panel_formulario_vista">
            <div class="row">
                <!-- INICIO - Tab Factura -->
                <div class="tab-pane active" id="factura">
                    <form id="formulario_vista" name="formulario_vista">
                        <!-- Acción del formulario en opcion  -->
                        <input type="hidden" id="factura_id_v" name="factura_id_v" class="form-control" readonly="true">
                        <div class="col-sm-6">
                            <div class="card-box-form">
                                <div class="row">
                                    <h4 class="m-t-0 m-b-20 header-title"><b>Datos Generales</b></h4>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label class="control-label">Tipo</label>
                                            <input id="factura_tipo_id_v" name="factura_tipo_id_v" class="form-control" type="text" readonly="true">
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="control-label">Número de Factura</label>
                                            <input id="factura_numero_v" name="factura_numero_v" class="form-control" type="text" readonly="true">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">Pagador</label>
                                            <input id="factura_pagador_id_v" name="factura_pagador_id_v" class="form-control" type="text" readonly="true">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">Prioridad</label>
                                            <input id="factura_prioridad_id_v" name="factura_prioridad_id_v" class="form-control" type="text" readonly="true">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">Ingreso Sistema</label>
                                            <input id="factura_fechaIngresoSistema_v" name="factura_fechaIngresoSistema_v" class="form-control" type="text" readonly="readonly">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Emisión</label>
                                            <input id="factura_fechaEmision_v" name="factura_fechaEmision_v" class="form-control" type="text" readonly="readonly">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Recepción</label>
                                            <input id="factura_fechaRecepcion_v" name="factura_fechaRecepcion_v" class="form-control" type="text" readonly="readonly">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">Vencimiento</label>
                                            <input id="factura_fechaVencimiento_v" name="factura_fechaVencimiento_v" class="form-control" type="text" readonly="readonly">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Pago</label>
                                            <input id="factura_fechaPago_v" name="factura_fechaPago_v" class="form-control" type="text" readonly="readonly">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="card-box-form">
                                <div class="row">
                                    <h4 class="m-t-0 m-b-20 header-title"><b>Importes</b></h4>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Costos Médicos</label>
                                            <input id="factura_importe_medicoOrigen_v" name="factura_importe_medicoOrigen_v" class="form-control" type="text" readonly="readonly">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Importe FEE</label>
                                            <input id="factura_importe_feeOrigen_v" name="factura_importe_feeOrigen_v" class="form-control" type="text" readonly="readonly">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label has-warning">Moneda</label>
                                            <input id="factura_moneda_id_v" name="factura_moneda_id_v" class="form-control" type="text" readonly="readonly">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">T/C</label>
                                            <input id="factura_tipoCambio_v" name="factura_tipoCambio_v" class="form-control" type="text" disabled="true">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Importe USD</label>
                                            <input id="factura_importeUSD_v" name="factura_importeUSD_v" class="form-control" type="text" disabled="true">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Importe Aprobado USD</label>
                                            <input id="factura_importeAprobadoUSD_v" name="factura_importeAprobadoUSD_v" class="form-control" type="text" disabled="true">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Importe Rechazado USD</label>
                                            <input id="factura_importeRechazadoUSD_v" name="factura_importeRechazadoUSD_v" class="form-control" type="text" disabled="true">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="card-box-form">
                                <div class="row">
                                    <h4 class="m-t-0 m-b-20 header-title"><b>Otros Datos</b></h4>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label">Observaciones</label>
                                            <textarea id="factura_observaciones_v" name="factura_observaciones_v" class="form-control" rows="2" readonly="true"></textarea>
                                        </div>
                                    </div>     
                                </div>
                            </div>
                            <div class="card-box-form">
                                <div class="form-group text-right m-b-0">
                                    <button type="reset" id="btn_cancelar_vista" class="btn btn-inverse waves-effect waves-light">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </form> 
                </div>
                <!-- FIN - Tab Factura -->                    
            </div>
        </div>
        <!-- FIN - Formulario Vista --> 
                
        <!-- INICIO - Grillas  -->
        <div class="panel-body" id="panel_grilla">
            <div id="grilla_facturas"></div>
            <div id="grilla_servicios"></div>
        </div>
        <!-- FIN - Grillas  --> 

    </div>
    <!-- FIN - Panel Formularios y Grilla  -->
    
    <!-- INICIO - Includes -->
    <?php require '../includes/footer_start.php' ?>
    <?php require '../includes/footer_notificaciones.php' ?>
    <?php require '../includes/footer_datatable.php' ?>
    <?php require '../includes/footer_validacion.php' ?>
    <script src="facturacion_old.js?random=<?php echo uniqid(); ?>"></script>
    <script src="..includes/grilla_idioma.js"></script>
    <script src="../assets/js/jquery.number.min.js"></script>
    <?php require '../includes/footer_end.php' ?>
    <!-- FIN - Includes -->
<?php } ?>