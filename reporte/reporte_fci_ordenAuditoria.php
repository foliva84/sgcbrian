<?php require '../seguridad/seguridad.php'; ?> 
<?php $pagina = "Reporte_ItemFactura_ordenAuditoria"; ?>
<?php require '../includes/header_start.php'; ?>
<?php require '../includes/header_datatables.php'; ?>
<?php require '../includes/header_end.php'; ?>
<link href="reporte.css" rel="stylesheet" type="text/css">
<?php require '../includes/header_pagina.php'; ?>




<!-- INICIO - Panel Grilla  -->
<div class="panel panel-default users-content">
    <!-- INICIO - Formulario Buscar -->
    <div class="panel-body" id="panel_grilla">
        <div class="row">
            <form  id="formulario_reporte" name="formulario_reporte" action='reporte_fci_ordenAuditoria_cb.php' method="post">
                <!-- Acción del formulario en opcion  -->
                <input type="hidden" id="opcion" name="opcion" value="exportar_excel" readonly="readonly">
                <div class="col-md-12">
                    <div class="card-box-form">
                        <div class="row">
                            <h4 class="m-t-0 m-b-20 header-title"><b>Buscador de facturas con orden de auditoria</b></h4>
                            
                            <div class="col-md-4">
                                <div class="form-group has-warning">
                                    <label class="control-label">Prestador</label>
                                    <input name="factura_prestador_nombre_buscar" id="factura_prestador_nombre_buscar" placeholder="Ingrese las primeras 3 letras del prestador" type="text" class="form-control">
                                    <input name="factura_prestador_id_buscar" id="factura_prestador_id_buscar" class="form-control" type="hidden" readonly="readonly">
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group has-warning">
                                    <label class="control-label" for="factura_cliente_id">Pagador</label>
                                    <select name="factura_cliente_id" class="form-control" id="factura_cliente_id"></select>
                                </div>
                            </div>
                    
                            <div class="col-md-4">
                                <label>&nbsp;</label>
                                <div class="form-group">
                                    <button class="btn btn-primary waves-effect waves-light" type="button" onclick='grilla_listar()'>Buscar</button>
                                    <button class="btn btn-default waves-effect waves-light" type="submit">Generar Orden de Pago</button>
                                    <input name="fci_seleccionados" id="fci_seleccionados" class="form-control" type="hidden" readonly="true"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div id="grilla_info"></div>
            <div id="grilla_itemFactura_ordenAuditoria"></div>
            <div>
                <label class="control-label" for="importeAprobadoUSD_total">&nbsp;&nbsp;&nbsp;&nbsp; TOTAL Importe Aprobado en USD</label>
                <input name="importeAprobadoUSD_total" id="importeAprobadoUSD_total" readonly="true"/>
            </div>
        </div>
    </div>
    <!-- FIN - Formulario Buscar -->
</div>
<!-- FIN - Panel Formularios y Grilla  -->

<?php require '../includes/footer_start.php' ?>
<?php require '../includes/footer_notificaciones.php' ?>
<?php require '../includes/footer_datatable.php' ?>
<?php require '../includes/footer_validacion.php' ?>
<script src="reporte_fci_ordenAuditoria.js?random=<?php echo uniqid(); ?>"></script>
<?php require '../includes/footer_end.php' ?>