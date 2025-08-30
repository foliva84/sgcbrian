<?php require '../seguridad/seguridad.php'; ?> 
<?php $pagina = "Reporte_Reintegros_ordenPago"; ?>
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
            <form  id="formulario_reporte" name="formulario_reporte" action='reporte_reintegros_ordenPago_cb.php' method="post">
                <!-- Acción del formulario en opcion  -->
                <input type="hidden" id="opcion" name="opcion" value="exportar_excel" readonly="readonly">
                <div class="col-md-12">
                    <div class="card-box-form">
                        <div class="row">
                            <h4 class="m-t-0 m-b-20 header-title"><b>Generar Orden de Pago</b></h4>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <button class="btn btn-primary waves-effect waves-light" type="button" onclick='grilla_listar()'>Listar Resultado</button>
                                    <button id="btn_generar_orden_pago" class="btn btn-default waves-effect waves-light" type="submit">Generar Orden de Pago</button>
                                    <input name="rim_seleccionados" id="rim_seleccionados" class="form-control" type="hidden" readonly="true"/>
                                    <input name="rim_contador" id="rim_contador" class="form-control" type="hidden" readonly="true"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div id="grilla_info"></div>
            <div id="reporte_reintegros_ordenPago"></div>
            <div>
                <label class="control-label" for="importeAprobadoARS_total">&nbsp;&nbsp;&nbsp;&nbsp; TOTAL Importe Aprobado en ARS</label>
                <input name="importeAprobadoARS_total" id="importeAprobadoARS_total" readonly="true"/>
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
<script src="reporte_reintegros_ordenPago.js?random=<?php echo uniqid(); ?>"></script>
<?php require '../includes/footer_end.php' ?>