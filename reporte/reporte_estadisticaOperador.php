<?php require '../seguridad/seguridad.php'; ?> 
<?php $pagina = "Reporte_estadisticaOperador"; ?>
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
            <form  id="formulario_reporte" name="formulario_reporte" action='reporte_estadisticaOperador_cb.php' method="post">
                <!-- Acción del formulario en opcion  -->
                <input type="hidden" id="opcion" name="opcion" value="exportar_excel" readonly="readonly">
                <div class="col-sm-12">
                    <div class="card-box-form">
                        <div class="row">
                            <h4 class="m-t-0 m-b-20 header-title"><b>Buscador de estadisticas por operador</b></h4>
                            <div class="col-md-4">
                                <label class="control-label" for="caso_fechaAperturaCaso">Fecha de apertura de Casos e ingreso de Notas</label>
                                <div class="form-group">
                                    <div class="form-group col-md-6">
                                        <input type="text"  name="caso_fechaAperturaCaso_desde" class="form-control"  id="caso_fechaAperturaCaso_desde" placeholder="Fecha / Fecha desde" >
                                    </div>
                                    <div class="form-group col-md-6">
                                        <input type="text"  name="caso_fechaAperturaCaso_hasta" class="form-control"  id="caso_fechaAperturaCaso_hasta" placeholder="Fecha hasta" >
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <div class="checkbox checkbox-primary">
                                        <p class="text-muted m-b-30 font-13"></p>
                                        <input name="incluir_usuariosDeshab" id="incluir_usuariosDeshab" type="checkbox">
                                        <label for="incluir_usuariosDeshab">Incluir Usuarios Deshabilitados</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label>&nbsp;</label>
                                <div class="form-group">  
                                    <button class="btn btn-default waves-effect waves-light" type="submit">Exportar Excel</button>
                                    <button class="btn btn-primary waves-effect waves-light" type="button" onclick='grilla_listar()'>Buscar</button>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label>&nbsp;</label>
                                <div class="form-group">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div id="grilla_estadisticaOperador"></div> 
        </div>
    </div>
    <!-- FIN - Formulario Buscar -->
</div>
<!-- FIN - Panel Formularios y Grilla  -->

<?php require '../includes/footer_start.php' ?>
<?php require '../includes/footer_notificaciones.php' ?>
<?php require '../includes/footer_datatable.php' ?>
<?php require '../includes/footer_validacion.php' ?>
<script src="reporte_estadisticaOperador.js?random=<?php echo uniqid(); ?>"></script>
<?php require '../includes/footer_end.php' ?>