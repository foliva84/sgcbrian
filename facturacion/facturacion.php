<?php
require '../seguridad/seguridad.php';

$facturas_ver = array_search('facturas_ver', array_column($permisos, 'permiso_variable'));
if (empty($facturas_ver) && ($facturas_ver !== 0)) {
    header("location:../_errores/401.php");
} else {
?>
    <!-- INICIO - Includes -->
    <?php $pagina = "Facturacion"; ?>
    <?php require '../includes/header_start.php'; ?>
    <?php require '../includes/header_datatables.php'; ?>
    <?php require '../includes/header_end.php'; ?>
    <link href="../assets/css/iconos_propios.css" rel="stylesheet" type="text/css">
    <?php require '../includes/header_pagina.php'; ?>
    <!-- FIN - Includes -->

    <!-- INICIO - Panel Formularios y Grilla  -->
    <div class="panel panel-default users-content">
        <?php
        // Permiso para la carga de una nueva factura
        $facturas_alta = array_search('facturas_alta', array_column($permisos, 'permiso_variable'));
        if (!empty($facturas_alta) || ($facturas_alta === 0)) {
        ?>
            <div class="panel-heading">
                <button id="btn_nueva_factura" onclick="javascript:preparar_formulario_alta();" class="btn btn-primary waves-effect waves-light m-l-5"> Nueva Factura <i class="glyphicon glyphicon-plus"></i></button>
            </div>
        <?php } ?>

        <!-- INICIO - Modal Busqueda de Prestadores  -->
        <div id="modal_busqueda_prestadores" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal_busqueda_prestadores_label" aria-hidden="true" style="display: none;">
            <div class="modal-dialog" style="width:80%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <b>Buscar Prestador</b>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <!-- INICIO - Grilla para buscar Prestador -->
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card-box">
                                    <div class="row">
                                        <h4 class="m-t-0 header-title"><b>Datos generales</b></h4>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Prestador nombre</label>
                                                <input type="text" name="prestador_nombre_buscar" id="prestador_nombre_buscar" class="form-control" maxlength="45">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Prestador tipo</label>
                                                <select name="prestador_tipoPrestador_id_b" class="form-control" id="prestador_tipoPrestador_id_b"></select>
                                            </div>
                                        </div>
                                        <br>
                                        <h4 class="m-t-0 header-title"><b>Territorialidad</b></h4>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Pais</label>
                                                <select name="prestador_pais_id_b" class="form-control" id="prestador_pais_id_b"></select>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Ciudad</label>
                                                <input name="prestador_ciudad_id_b" id="prestador_ciudad_id_b" class="form-control" placeholder="Seleccione el País e ingrese las primeras 3 letras de la ciudad">
                                                <input type="hidden" name="prestador_ciudad_id_b_2" class="form-control" id="prestador_ciudad_id_b_2">
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <label>&nbsp;</label>
                                            <div class="form-group text-right">
                                                <button id="btn_listar_prestadores" class="btn btn-primary waves-effect waves-light m-l-5"> Buscar <i class="glyphicon glyphicon-search"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="grilla_prestador"></div>
                        <!-- FIN - Grilla para buscar Prestador -->
                    </div>
                </div>
            </div>
        </div>
        <!-- FIN - Modal Busqueda de Prestadores  -->

        <!-- INICIO - Modal Busqueda de Servicios  -->
        <div id="modal_busqueda_servicios" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal_busqueda_servicios_label" aria-hidden="true" style="display: none;">
            <div class="modal-dialog" style="width:80%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <b>Buscar Servicios</b>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <!-- INICIO - Grilla para buscar Servicios -->
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card-box">
                                    <div class="row">
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label>Número de Caso</label>
                                                <input type="text" name="caso_numero_buscar" id="caso_numero_buscar" class="form-control" maxlength="6">
                                            </div>
                                        </div>
                                        <div class="col-sm-10">
                                            <label>&nbsp;</label>
                                            <div class="form-group text-right">
                                                <button id="btn_listar_servicios" class="btn btn-primary waves-effect waves-light m-l-5"> Buscar <i class="glyphicon glyphicon-search"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="grilla_servicios"></div>
                        <!-- FIN - Grilla para buscar Servicios -->
                    </div>
                </div>
            </div>
        </div>
        <!-- FIN - Modal Busqueda de Servicios  -->

        <!-- INICIO - Modal Servicios Asignados mas servicios autorizados del caso del mismo prestador sin asociar a un item de la misma u otra factura -->
        <div id="modal_servicios_asignados" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal_servicios_asignados_label" aria-hidden="true" style="display: none;">
            <div class="modal-dialog" style="width:80%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <b>Servicios Asignados</b>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <!-- INICIO - Grilla Servicios -->
                        <div id="grilla_servicios_asignados"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- FIN - Modal Servicios Asignados  mas servicios-->

        <!-- INICIO - Modal Servicios Asociados a un item  -->
        <div id="modal_servicios_fci" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal_servicios_fci_label" aria-hidden="true" style="display: none;">
            <div class="modal-dialog" style="width:80%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <b>Servicios Asociados</b>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <!-- INICIO - Grilla Servicios -->
                        <div id="grilla_servicios_fci"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- FIN - Modal Servicios Asignados  -->


        <!-- INICIO - Panel busqueda de facturas  -->
        <div class="panel-body" id="panel_busqueda_facturas">
            <div class='row'>
                <div id='grilla_abm' class='col-sm-12'>
                    <div class='card-box table-responsive'>
                        <div class="col-md-2">
                            <label class="control-label">Número de factura</label>
                            <input type="text" name="factura_numero_buscar" id="factura_numero_buscar" class="form-control" maxlength="15">
                        </div>
                        <div class="col-md-2">
                            <label class="control-label">Número de factura final</label>
                            <input type="text" name="factura_final_buscar" id="factura_final_buscar" class="form-control" maxlength="15">
                        </div>
                        <div class="col-md-2">
                            <label class="control-label">Número de caso</label>
                            <input type="text" name="fc_caso_numero_buscar" id="fc_caso_numero_buscar" class="form-control" maxlength="15">
                        </div>
                        <div class="col-md-4">
                            <label class="control-label">Prestador</label>
                            <input name="factura_prestador_nombre_buscar" id="factura_prestador_nombre_buscar" placeholder="Ingrese las primeras 3 letras del prestador" type="text" class="form-control">
                            <input name="factura_prestador_buscar" id="factura_prestador_buscar" class="form-control" type="hidden" readonly="readonly">
                        </div>
                        <div class="col-md-1">
                            <label class="control-label"></label>
                            <div class="form-group">
                                <i id="btn_buscar_factura" class='fa fa-search' style="margin-top: 10px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- INICIO - Grillas de resultados  -->
            <div id="grilla_facturas"></div>
            <!-- FIN - Grillas de resultados  -->
        </div>
        <!-- FIN - Panel busqueda de facturas  -->

        <!-- INICIO - Formulario Alta Factura -->
        <div class="panel-body hidden" id="panel_formulario_alta">
            <div class="row">
                <form id="formulario_alta" name="formulario_alta">
                    <!-- Acción del formulario en opcion  -->
                    <input type="hidden" id="opcion" name="opcion" value="formulario_alta">
                    <div class="col-sm-12">
                        <div class="card-box-form">
                            <div class="row">
                                <h4 class="m-t-0 m-b-20 header-title"><b>Datos Generales</b></h4>
                                <div class="col-md-6">
                                    <div class="form-group has-warning">
                                        <label class="control-label">Prestador</label>
                                        <input name="factura_prestador_nombre_n" id="factura_prestador_nombre_n" placeholder="Ingrese las primeras 3 letras del prestador" type="text" class="form-control">
                                        <input name="factura_prestador_id_n" id="factura_prestador_id_n" class="form-control" type="hidden" readonly="readonly">
                                        <!--<span class="input-group-btn">
                                            <button id="btn_buscar_prestadores" type="button" class="btn waves-effect waves-light btn-warning"><i class="ion-search" ></i></button>
                                        </span>-->
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group has-warning">
                                        <label class="control-label">Número de Factura</label>
                                        <input id="factura_numero_n" name="factura_numero_n" class="form-control" type="text" maxlength="15">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Prioridad</label>
                                        <select id="factura_prioridad_id_n" name="factura_prioridad_id_n" class="form-control"></select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group has-warning">
                                        <label class="control-label has-warning">Fecha Emisión</label>
                                    </div>
                                    <div class="input-group col-md-12 has-warning">
                                        <input id="factura_fechaEmision_n" name="factura_fechaEmision_n" class="form-control" type="text" placeholder="dd-mm-aaaa" readonly="readonly">
                                        <i class="ion-calendar form-control-feedback l-h-34"></i>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group has-warning">
                                        <label class="control-label">Fecha Recepción</label>
                                    </div>
                                    <div class="input-group has-warning col-md-12">
                                        <input id="factura_fechaRecepcion_n" name="factura_fechaRecepcion_n" class="form-control" type="text" readonly="readonly">
                                        <i class="ion-calendar form-control-feedback l-h-34"></i>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group has-warning">
                                        <label class="control-label">Fecha Vencimiento</label>
                                    </div>
                                    <div class="input-group col-md-12 has-warning">
                                        <input id="factura_fechaVencimiento_n" name="factura_fechaVencimiento_n" class="form-control" type="text" placeholder="dd-mm-aaaa" readonly="readonly">
                                        <i class="ion-calendar form-control-feedback l-h-34"></i>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">Observaciones</label>
                                        <textarea id="factura_observaciones_n" name="factura_observaciones_n" class="form-control" rows="3" maxlength="400"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="card-box-form">
                            <div class="form-group text-right m-b-0">
                                <button type="reset" id="btn_cancelar_nuevo" class="btn btn-inverse waves-effect waves-light">Cancelar</button>
                                <?php
                                // Permiso para la carga de una nueva factura
                                $facturas_alta = array_search('facturas_alta', array_column($permisos, 'permiso_variable'));
                                if (!empty($facturas_alta) || ($facturas_alta === 0)) {
                                ?>
                                    <button type="submit" class="btn btn-success waves-effect waves-light">Crear Factura</button>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- FIN - Formulario Alta Factura -->

        <!-- INICIO - Tabs Facturas e Items -->
        <!-- INICIO - Menu Tabs -->
        <div class="col-sm-12 hidden" id="menu_tabs">
            <ul class="nav nav-tabs navtab-custom nav-justified">
                <li id="tabFactura" class="active tab">
                    <a href="#factura" data-toggle="tab" aria-expanded="false">
                        <span class="visible-xs"><i class="fa fa-home"></i></span>
                        <span class="hidden-xs">Factura</span>
                    </a>
                </li>
                <li id="tabFacturaItems" class="tab">
                    <a data-toggle="tab" href="#facturaItems" aria-expanded="false">
                        <span class="visible-xs"><i class="fa fa-home"></i></span>
                        <span class="hidden-xs">Items de Factura</span>
                    </a>
                </li>
                <li id="tabComunicacionesF" class="tab">
                    <a href="#comunicacionesF" data-toggle="tab" aria-expanded="false">
                        <span class="visible-xs"><i class="fa fa-user"></i></span>
                        <span class="hidden-xs">Comunicaciones</span>
                    </a>
                </li>
                <li id="tabFacturaArchivos" class="tab">
                    <a data-toggle="tab" href="#facturaArchivos" aria-expanded="false">
                        <span class="visible-xs"><i class="fa fa-home"></i></span>
                        <span class="hidden-xs">Archivos</span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- FIN - Menu Tabs -->

        <!-- INICIO - Tabs (Ver factura + item factura)  -->
        <div class="tab-content">
            <!-- INICIO - Formulario Ver Factura -->
            <div class="tab-pane active" id="factura">
                <div class="panel-body hidden" id="panel_formulario_vista">
                    <div class="row">
                        <form id="formulario_vista" name="formulario_vista">
                            <!-- Acción del formulario en opcion  -->
                            <input id="factura_id_v" name="factura_id_v" class="form-control" type="hidden" readonly="true">
                            <div class="col-sm-12">
                                <div class="card-box-form">
                                    <div class="row">
                                        <h4 class="m-t-0 m-b-20 header-title"><b>Datos Generales</b></h4>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Prestador</label>
                                                <div class="input-group">
                                                    <input name="factura_prestador_nombre_v" id="factura_prestador_nombre_v" type="text" class="form-control" readonly="readonly">
                                                    <input name="factura_prestador_id_v" id="factura_prestador_id_v" type="hidden" class="form-control" readonly="readonly">
                                                    <span class="input-group-btn">
                                                        <button type="button" id="btn_ver_prestador" class="btn waves-effect waves-light btn-primary"><i class="ion-search"></i></button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Número de Factura</label>
                                                <input id="factura_numero_v" name="factura_numero_v" class="form-control" type="text" readonly="readonly">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Estado de Factura</label>
                                                <input id="factura_estado_v" name="factura_estado_v" class="form-control" type="text" readonly="readonly">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Prioridad</label>
                                                <input name="factura_prioridad_id_v" id="factura_prioridad_id_v" type="text" class="form-control" readonly="readonly">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label has-warning">Fecha Emisión</label>
                                                <input name="factura_fechaEmision_v" id="factura_fechaEmision_v" type="text" class="form-control" readonly="readonly">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Fecha Recepción</label>
                                                <input name="factura_fechaRecepcion_v" id="factura_fechaRecepcion_v" type="text" class="form-control" readonly="readonly">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Fecha Vencimiento</label>
                                                <input name="factura_fechaVencimiento_v" id="factura_fechaVencimiento_v" type="text" class="form-control" readonly="readonly">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label">Observaciones</label>
                                                <textarea id="factura_observaciones_v" name="factura_observaciones_v" class="form-control" rows="2" readonly="readonly"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="card-box-form">
                                    <div class="form-group text-right m-b-0">
                                        <button type="reset" id="btn_cerrar_vista" class="btn btn-inverse waves-effect waves-light">Cerrar</button>
                                        <?php
                                        // Permiso para mostrar el boton de 'Factura paga'
                                        $fci_auto_pago = array_search('fci_auto_pago', array_column($permisos, 'permiso_variable'));
                                        if (!empty($fci_auto_pago) || ($fci_auto_pago === 0)) {
                                        ?>
                                            <button type="button" id="btn_pagar_factura" class="btn btn-primary waves-effect waves-light">Factura Paga</button>
                                        <?php } ?>
                                        <?php
                                        // Permiso para la modificacion de una factura
                                        $facturas_modificar = array_search('facturas_modificar', array_column($permisos, 'permiso_variable'));
                                        if (!empty($facturas_modificar) || ($facturas_modificar === 0)) {
                                        ?>
                                            <button type="button" id="btn_modificar_factura" class="btn btn-danger waves-effect waves-light">Modificar Factura</button>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- FIN - Formulario Ver Factura -->

            <!-- INICIO - Formulario Alta ITEMS Factura -->
            <div class="tab-pane" id="facturaItems">
                <!-- INICIO - Formulario Alta ITEMS Facturas -->
                <div class="panel-body" id="panel_formulario_alta_fci">
                    <?php
                    // Permiso para la carga de un Item de Factura
                    $fci_alta = array_search('fci_alta', array_column($permisos, 'permiso_variable'));
                    if (!empty($fci_alta) || ($fci_alta === 0)) {
                    ?>
                        <div class="row">
                            <form id="formulario_alta_fci" name="formulario_alta_fci">
                                <!-- Acción del formulario en opcion  -->
                                <input type="hidden" id="opcion" name="opcion" value="formulario_alta_fci" readonly="true">
                                <div class="col-sm-12">
                                    <div class="card-box">
                                        <div class="row">
                                            <h4 class="m-t-0 m-b-20 header-title"><b>Agregar Items de Factura</b></h4>
                                            <div class="col-md-2">
                                                <div class="form-group has-warning">
                                                    <label class="control-label">Caso (Servicios)</label>
                                                    <div class="input-group">
                                                        <input name="fci_caso_n" id="fci_caso_n" type="text" class="form-control" readonly="true" />
                                                        <input name="fci_caso_id_n" id="fci_caso_id_n" class="form-control" type="hidden" readonly="true" />
                                                        <input name="fci_factura_id_n" id="fci_factura_id_n" class="form-control" type="hidden" readonly="true" />
                                                        <input name="fci_seleccionados" id="fci_seleccionados" class="form-control" type="hidden" readonly="true" />
                                                        <span class="input-group-btn">
                                                            <button id="btn_buscar_servicios" type="button" class="btn waves-effect waves-light btn-warning"><i class="ion-search"></i></button>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group has-warning">
                                                    <label class="control-label">Pagador</label>
                                                    <select id="fci_pagador_id_n" name="fci_pagador_id_n" class="form-control"></select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Número de Factura</label>
                                                    <input id="fci_numeroFactura" class="form-control" type="text" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group has-warning">
                                                    <label class="control-label">Importes Medicos</label>
                                                    <input id="fci_imp_medicoOrigen_n" name="fci_imp_medicoOrigen_n" class="form-control" type="text" maxlength="13">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group has-warning">
                                                    <label class="control-label">FEE</label>
                                                    <input id="fci_imp_feeOrigen_n" name="fci_imp_feeOrigen_n" class="form-control" type="text" maxlength="13">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">Descuento</label>
                                                    <input id="fci_descuento_n" name="fci_descuento_n" class="form-control" type="text" maxlength="13" />
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-group has-warning">
                                                    <label class="control-label has-warning">Moneda</label>
                                                    <select id="fci_moneda_id_n" name="fci_moneda_id_n" class="form-control"></select>
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-group has-warning">
                                                    <label class="control-label">T/C</label>
                                                    <input id="fci_tipoCambio_n" name="fci_tipoCambio_n" class="form-control" type="text">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group has-warning">
                                                    <label class="control-label">Importe Total USD</label>
                                                    <input id="fci_importeUSD_n" name="fci_importeUSD_n" class="form-control" type="text">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <label>&nbsp;</label>
                                                <div class="form-group text-right m-b-0">
                                                    <button type="button" id="btn_cancelar_nuevo_fci" class="btn btn-default waves-effect waves-light m-l-5">Cancelar</button>
                                                    <button id="btn_guardar_nuevo_fci" class="btn btn-primary waves-effect waves-light" type="submit">Guardar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    <?php
                    }
                    ?>
                </div>
                <!-- FIN - Formulario Alta ITEM Facturas -->

                <!-- INICIO - Formulario Modificacion ITEM Facturas-->
                <div class="panel-body" id="panel_formulario_modificacion_fci">
                    <?php
                    // Permiso para la carga de una nueva factura
                    //$fci_alta = array_search('fci_alta', array_column($permisos, 'permiso_variable'));
                    //if (!empty($fci_alta) || ($fci_alta === 0)) { 
                    ?>
                    <div class="row">
                        <form id="formulario_modificacion_fci" name="formulario_modificacion_fci">
                            <!-- Acción del formulario en opcion  -->
                            <input type="hidden" name="fci_id" id="fci_id" class="form-control" readonly="true">
                            <input type="hidden" id="opcion" name="opcion" value="formulario_modificacion_fci" readonly="true">
                            <div class="col-sm-12">
                                <div class="card-box">
                                    <div class="row">
                                        <h4 class="m-t-0 m-b-20 header-title"><b>Modificar Items de Factura</b></h4>
                                        <div class="col-md-2">
                                            <div class="form-group has-warning">
                                                <label class="control-label">Caso (Servicios)</label>
                                                <div class="input-group">
                                                    <input name="fci_caso_m" id="fci_caso_m" type="text" class="form-control" readonly="true" />
                                                    <input name="fci_caso_id_m" id="fci_caso_id_m" class="form-control" type="hidden" readonly="true">
                                                    <input name="fci_prestador_id_m" id="fci_prestador_id_m" class="form-control" type="hidden" readonly="true">
                                                    <input name="fci_seleccionados_m" id="fci_seleccionados_m" class="form-control" type="hidden" readonly="true">
                                                    <input name="fci_seleccionados_b" id="fci_seleccionados_b" class="form-control" type="hidden" readonly="true">
                                                    <span class="input-group-btn">
                                                        <button id="btn_buscar_servicios_m" type="button" class="btn waves-effect waves-light btn-warning"><i class="ion-search"></i></button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group has-warning">
                                                <label class="control-label">Pagador</label>
                                                <select id="fci_pagador_id_m" name="fci_pagador_id_m" class="form-control"></select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Número de Factura</label>
                                                <input id="fci_numeroFactura_m" name="fci_numeroFactura_m" class="form-control" type="text" disabled>
                                                <input id="fci_factura_id_m" name="fci_factura_id_m" type="hidden" class="form-control" type="text">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group has-warning">
                                                <label class="control-label">Importes Medicos</label>
                                                <input id="fci_imp_medicoOrigen_m" name="fci_imp_medicoOrigen_m" class="form-control" type="text" maxlength="13">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group has-warning">
                                                <label class="control-label">FEE</label>
                                                <input id="fci_imp_feeOrigen_m" name="fci_imp_feeOrigen_m" class="form-control" type="text" maxlength="13">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Descuento</label>
                                                <input id="fci_descuento_m" name="fci_descuento_m" class="form-control" type="text" maxlength="13">
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="form-group has-warning">
                                                <label class="control-label has-warning">Moneda</label>
                                                <select id="fci_moneda_id_m" name="fci_moneda_id_m" class="form-control"></select>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="form-group has-warning">
                                                <label class="control-label">T/C</label>
                                                <input id="fci_tipoCambio_m" name="fci_tipoCambio_m" class="form-control" type="text">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group has-warning">
                                                <label class="control-label">Importe Total USD</label>
                                                <input id="fci_importeUSD_m" name="fci_importeUSD_m" class="form-control" type="text">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label>&nbsp;</label>
                                            <div class="form-group text-right m-b-0">
                                                <button type="button" id="btn_cancelar_modificacion_fci" class="btn btn-default waves-effect waves-light m-l-5">Cancelar</button>
                                                <?php
                                                //$servicios_alta = array_search('servicios_alta', array_column($permisos, 'permiso_variable'));
                                                //if (!empty($servicios_alta) || ($servicios_alta === 0)) { 
                                                ?>
                                                <button id="btn_guardar_modificacion_fci" class="btn btn-primary waves-effect waves-light" type="submit">Guardar</button>
                                                <?php //} 
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <?php
                    // }
                    ?>
                </div>
                <!-- FIN - Formulario Modificacion ITEM Facturas -->

                <div class="panel-body" id="panel_grilla_items">
                    <div id="grilla_fci"></div>
                </div>
            </div>
            <!-- FIN - Formulario Alta ITEMS Factura -->

            <!-- INICIO - Tab COMUNICACIONES Reintegro -->
            <div class="tab-pane" id="comunicacionesF">
                <object type="text/html" id="pantalla_comunicacionesF" width="100%" height="2500"></object>
            </div>
            <!-- FIN - Tab COMUNICACIONES Reintegro -->

            <!-- INICIO - Tab ITEM Archivos -->
            <div class="tab-pane" id="facturaArchivos">
                <object type="text/html" id="pantalla_archivos" width="100%" height="3000"></object>
            </div>
            <!-- FIN - Tab ITEM Archivos -->
        </div>
        <!-- FIN - Tabs Edicion Factura -->
        <!-- FIN - Tabs Facturas - Items - Archivos -->

        <!-- INICIO - Formulario Modificacion Factura -->
        <div class="panel-body hidden" id="panel_formulario_modificacion">
            <div class="row">
                <form id="formulario_modificacion" name="formulario_modificacion">
                    <!-- Acción del formulario en opcion  -->
                    <input type="hidden" id="factura_id_m" name="factura_id_m" class="form-control" readonly="true">
                    <input type="hidden" id="opcion" name="opcion" value="formulario_modificacion" readonly="true">
                    <div class="col-sm-12">
                        <div class="card-box-form">
                            <div class="row">
                                <h4 class="m-t-0 m-b-20 header-title"><b>Datos Generales</b></h4>
                                <div class="col-md-6">
                                    <div class="form-group has-warning">
                                        <label class="control-label">Prestador</label>
                                        <input name="factura_prestador_nombre_m" id="factura_prestador_nombre_m" placeholder="Ingrese las primeras 3 letras del prestador" type="text" class="form-control">
                                        <input name="factura_prestador_id_m" id="factura_prestador_id_m" class="form-control" type="hidden" readonly="readonly">
                                        <!--<span class="input-group-btn">
                                            <button id="btn_buscar_prestadores_modificar" type="button" class="btn waves-effect waves-light btn-warning"><i class="ion-search" ></i></button>
                                        </span>-->
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group has-warning">
                                        <label class="control-label">Número de Factura</label>
                                        <input id="factura_numero_m" name="factura_numero_m" class="form-control" type="text" maxlength="15">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Estado de Factura</label>
                                        <input id="factura_estado_m" name="factura_estado_m" class="form-control" type="text" readonly="readonly">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Prioridad</label>
                                        <select id="factura_prioridad_id_m" name="factura_prioridad_id_m" class="form-control"></select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group has-warning">
                                        <label class="control-label has-warning">Fecha Emisión</label>
                                    </div>
                                    <div class="input-group col-md-12 has-warning">
                                        <input id="factura_fechaEmision_m" name="factura_fechaEmision_m" class="form-control" type="text" placeholder="dd-mm-aaaa" readonly="readonly">
                                        <i class="ion-calendar form-control-feedback l-h-34"></i>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group has-warning">
                                        <label class="control-label">Fecha Recepción</label>
                                    </div>
                                    <div class="input-group has-warning col-md-12">
                                        <input id="factura_fechaRecepcion_m" name="factura_fechaRecepcion_m" class="form-control" type="text" placeholder="dd-mm-aaaa" readonly="readonly">
                                        <i class="ion-calendar form-control-feedback l-h-34"></i>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group has-warning">
                                        <label class="control-label">Fecha Vencimiento</label>
                                    </div>
                                    <div class="input-group col-md-12 has-warning">
                                        <input id="factura_fechaVencimiento_m" name="factura_fechaVencimiento_m" class="form-control" type="text" placeholder="dd-mm-aaaa" readonly="readonly">
                                        <i class="ion-calendar form-control-feedback l-h-34"></i>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">Observaciones</label>
                                        <textarea id="factura_observaciones_m" name="factura_observaciones_m" class="form-control" rows="2" maxlength="300"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="card-box-form">
                            <div class="form-group text-right m-b-0">
                                <button type="reset" id="btn_cancelar_modificacion" class="btn btn-inverse waves-effect waves-light">Cancelar</button>
                                <?php
                                // Permiso para la modificacion de una factura
                                $facturas_modificar = array_search('facturas_modificar', array_column($permisos, 'permiso_variable'));
                                if (!empty($facturas_modificar) || ($facturas_modificar === 0)) {
                                ?>
                                    <button type="submit" class="btn btn-success waves-effect waves-light">Guardar Modificaciones</button>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- FIN - Formulario Modificacion Factura -->


        <!-- INICIO - Modal Autorizacion de Facturas  -->
        <div id="modal_auto_facturas" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal_auto_facturas_label" aria-hidden="true" style="display: none;">
            <div class="modal-dialog" style="width:80%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <b>Autorizacion de Items de Facturas</b>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <!-- INICIO - Autorizacion de Facturas  -->
                        <form id="formulario_autorizacion_fci" name="formulario_autorizacion_fci">
                            <!-- Acción del formulario en opcion  -->
                            <input type="hidden" id="opcion" name="opcion" value="formulario_autorizacion_fci">
                            <input type="hidden" id="fci_id_au" name="fci_id_au">
                            <div class="row">
                                <!-- INICIO - Factura Info -->
                                <div class="col-sm-12">
                                    <div class="card-box-form">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Número de Factura</label>
                                                    <input id="fci_numero_au" name="fci_numero_au" class="form-control" type="text" disabled="true">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Estado de la Factura</label>
                                                    <input id="fci_estado_au_id" name="fci_estado_au_id" class="form-control" type="hidden" disabled="true">
                                                    <input id="fci_estado_au" name="fci_estado_au" class="form-control" type="text" disabled="true">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Importe Total USD</label>
                                                    <input id="fci_importeUSD_au" name="fci_importeUSD_au" class="form-control" type="text" disabled="true">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group has-success">
                                                    <label class="control-label">Importe Aprobado USD</label>
                                                    <input id="fci_importeAprobadoUSD_au" name="fci_importeAprobadoUSD_au" class="form-control" type="text" disabled="true">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- FIN - Factura Info -->

                                <!-- INICIO - Autorizacion de la Factura -->
                                <div class="col-sm-12">
                                    <div class="card-box-form">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="form-group autorizacionFactura has-warning">
                                                    <label class="control-label">Autorización</label>
                                                    <select name="fci_mov_auditoria_auto_id" id="fci_mov_auditoria_auto_id" class="form-control"></select>
                                                </div>
                                            </div>
                                            <div class="col-sm-9">
                                                <div class="form-group motivoRechazoFci has-warning">
                                                    <label class="control-label">Motivo Rechazo</label>
                                                    <select name="fci_mov_motivoRechazo_auto_id" id="fci_mov_motivoRechazo_auto_id" class="form-control"></select>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group importeUSDFci">
                                                    <label class="control-label">Importe Total USD</label>
                                                    <input name="fci_importeUSD_auto" id="fci_importeUSD_auto" type="text" class="form-control" disabled="true">
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group importeAprobadoFci has-warning">
                                                    <label class="control-label">Importe Aprobado USD</label>
                                                    <input name="fci_importeAprobadoUSD_auto" id="fci_importeAprobadoUSD_auto" type="text" class="form-control" maxlength="12">
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group importeRechazadoUSDFci">
                                                    <label class="control-label">Importe Rechazado USD</label>
                                                    <input name="fci_importeRechazadoUSD_auto" id="fci_importeRechazadoUSD_auto" type="text" class="form-control" disabled="true">
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group fechaPago has-warning">
                                                    <label class="control-label">Fecha de Pago</label>
                                                    <input name="fci_fechaPago_auto" id="fci_fechaPago_auto" type="text" class="form-control" placeholder="dd-mm-aaaa" readonly="true">
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group formaPago has-warning">
                                                    <label class="control-label">Forma de Pago</label>
                                                    <select name="fci_formaPago_auto" id="fci_formaPago_auto" class="form-control"></select>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label class="control-label">Observaciones</label>
                                                    <textarea name="fci_observaciones_auto" id="fci_observaciones_auto" class="form-control" rows="3" maxlength="400"></textarea>
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
                                <!-- FIN - Autorizacion de la Factura -->
                            </div>
                        </form>
                        <!-- FIN - Autorizacion de Facturas  -->
                    </div>
                </div>
            </div>
        </div>
        <!-- FIN - Modal Autorizacion de Facturas  -->

        <!-- INICIO - Modal Movimientos FCI -->
        <div id="modal_mov_fci" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal_mov_fci_label" aria-hidden="true" style="display: none;">
            <div class="modal-dialog" style="width:80%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <b>Detalle del Item de Factura</b>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>

                    <div class="modal-body">

                        <div class="col-sm-12">
                            <div class="card-box">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label">Nombre del Beneficiario</label>
                                            <input name="fci_caso_beneficiario" id="fci_caso_beneficiario" type="text" class="form-control" readonly="true">
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="control-label">Número de Voucher</label>
                                            <input name="fci_caso_voucher" id="fci_caso_voucher" type="text" class="form-control" readonly="true">
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="control-label">Producto</label>
                                            <input name="fci_caso_producto" id="fci_caso_producto" type="text" class="form-control" readonly="true">
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="control-label">Agencia</label>
                                            <input name="fci_caso_agencia" id="fci_caso_agencia" type="text" class="form-control" readonly="true">
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="control-label">País</label>
                                            <input name="fci_caso_pais" id="fci_caso_pais" type="text" class="form-control" readonly="true">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label">Diagnóstico</label>
                                            <input name="fci_caso_diagnostico" id="fci_caso_diagnostico" type="text" class="form-control" readonly="true">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="grilla_mov_fci"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- FIN - Modal Autorizacion de Facturas  -->

        <!-- INICIO - Modal Servicios Asignados -->
        <div id="modal_mov_fci" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal_mov_fci_label" aria-hidden="true" style="display: none;">
            <div class="modal-dialog" style="width:80%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <b>Movimientos del Item de Factura</b>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <div id="grilla_mov_fci"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- FIN - Modal Servicios Asignados -->
    </div>
    <!-- FIN - Panel Formularios y Grilla  -->


    <!-- INICIO - Includes -->
    <?php require '../includes/footer_start.php' ?>
    <?php require '../includes/footer_notificaciones.php' ?>
    <?php require '../includes/footer_datatable.php' ?>
    <?php require '../includes/footer_validacion.php' ?>
    <script src="facturacion.js?random=<?php echo uniqid(); ?>"></script>
    <script src="../assets/js/dt_idioma.js?random=<?php echo uniqid(); ?>"></script>
    <script src="../assets/js/jquery.number.min.js"></script>
    <?php require '../includes/footer_end.php' ?>
    <!-- FIN - Includes -->


    <!-- Para ubicarse en una factura puntual -->
    <?php
    $vinvoice = isset($_GET["vinvoice"]) ? $_GET["vinvoice"] : ''; // Trae el id de la factura a mostrar

    if ($vinvoice > 0) {
    ?>
        <script>
            let factura_id = '<?php echo $vinvoice; ?>';
            formulario_lectura(factura_id);
        </script>
    <?php } ?>
<?php } ?>