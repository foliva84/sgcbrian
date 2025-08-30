<?php require '../seguridad/seguridad.php'; ?> 
<?php $pagina = "Reporte_ItemFactura_ingresado"; ?>
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
            <form  id="formulario_reporte" name="formulario_reporte" action='reporte_fci_ingresado_cb.php' method="post">
                <!-- Acción del formulario en opcion  -->
                <input type="hidden" id="opcion" name="opcion" value="exportar_excel" readonly="readonly">
                <div class="col-md-2">
                    <label>&nbsp;</label>
                    <div class="form-group">  
                        <button class="btn btn-default waves-effect waves-light" type="submit">Exportar Excel</button>
                    </div>
                </div>
            </form>
            <div id="grilla_info"></div>
            <div id="grilla_itemFactura_ingresado"></div> 
        </div>
    </div>
    <!-- FIN - Formulario Buscar -->
</div>
<!-- FIN - Panel Formularios y Grilla  -->

<?php require '../includes/footer_start.php' ?>
<?php require '../includes/footer_notificaciones.php' ?>
<?php require '../includes/footer_datatable.php' ?>
<?php require '../includes/footer_validacion.php' ?>
<script src="reporte_fci_ingresado.js"></script> 
<?php require '../includes/footer_end.php' ?>