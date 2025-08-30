<?php require '../seguridad/seguridad.php'; ?> 
<?php $pagina = "Reporte_Casos_avanzado"; ?>
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
            <form  id="formulario_reporte" name="formulario_reporte" action='reporte_casosAvanzados_cb.php' method="post">
                <!-- Acción del formulario en opcion  -->
                <input type="hidden" id="opcion" name="opcion" value="exportar_excel" readonly="readonly">
                <div class="col-sm-12">
                    <div class="card-box-form">
                        <div class="row">
                            <h4 class="m-t-0 m-b-20 header-title"><b>Buscador avanzado de Casos</b></h4>
                            <div class="col-md-3">
                                <label class="control-label" for="caso_numero">Caso</label>
                                <div class="form-group">
                                    <div class="form-group col-md-6">
                                        <input type="text"  name="caso_numero_desde" class="form-control"  id="caso_numero_desde" placeholder="Caso desde" >
                                    </div>
                                    <div class="form-group col-md-6">
                                        <input type="text"  name="caso_numero_hasta" class="form-control"  id="caso_numero_hasta" placeholder="Caso hasta" >
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <label class="control-label" for="caso_fechaSiniestro">Fecha de siniestro</label>
                                <div class="form-group">
                                    <div class="form-group col-md-6">
                                        <input type="text"  name="caso_fechaSiniestro_desde" class="form-control"  id="caso_fechaSiniestro_desde" placeholder="Fecha desde" >
                                    </div>
                                    <div class="form-group col-md-6">
                                        <input type="text"  name="caso_fechaSiniestro_hasta" class="form-control"  id="caso_fechaSiniestro_hasta" placeholder="Fecha hasta" >
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <label class="control-label" for="caso_tipoAsistencia_id">Tipo de asistencia</label>
                                <div class="form-group">
                                    <div class="form-group col-md-12">
                                        <select name="caso_tipoAsistencia_id" class="form-control" id="caso_tipoAsistencia_id"></select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <label class="control-label" for="caso_abiertoPor_id">Abierto por</label>
                                <div class="form-group">
                                    <div class="form-group col-md-12">
                                        <select name="caso_abiertoPor_id" class="form-control" id="caso_abiertoPor_id"></select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <label class="control-label" for="caso_cliente_id">Cliente</label>
                                <div class="form-group">
                                    <div class="form-group col-md-12">
                                        <select name="caso_cliente_id" class="form-control" id="caso_cliente_id"></select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <label class="control-label" for="caso_producto_id">Producto</label>
                                <div class="form-group">
                                    <div class="form-group col-md-12">
                                        <select name="caso_producto_id" class="form-control" id="caso_producto_id"></select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <label class="control-label" for="caso_agencia">Agencia</label>
                                <div class="form-group">
                                    <div class="form-group col-md-12">
                                        <input type="text"  name="caso_agencia" class="form-control"  id="caso_agencia">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <label class="control-label" for="caso_prestador_id">Prestador</label>
                                <div class="form-group">
                                    <div class="form-group col-md-12">
                                        <select name="caso_prestador_id" class="form-control" id="caso_prestador_id"></select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-7">
                                <label class="control-label" for="caso_pais_id">Pais / Ciudad</label>
                                <div class="form-group">
                                    <div class="form-group col-md-5">
                                        <select name="caso_pais_id" class="form-control" id="caso_pais_id"></select>
                                    </div>
                                    <div class="form-group col-md-7">
                                        <input name="caso_ciudad_id" class="form-control" id="caso_ciudad_id" placeholder="Seleccione el País e ingrese las primeras 3 letras de la ciudad">
                                        <input type="hidden" name="caso_ciudad_id_2" class="form-control" id="caso_ciudad_id_2">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <div class="form-group">
                                    <div class="checkbox checkbox-primary">
                                        <p class="text-muted m-b-30 font-13"></p>
                                        <input name="caso_anulado" id="caso_anulado" type="checkbox">
                                        <label for="caso_anulado">Casos anulados</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-10">
                                <label>&nbsp;</label>
                                <div class="form-group">
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <label>&nbsp;</label>
                                <div class="form-group">  
                                    <button class="btn btn-default waves-effect waves-light" type="submit">Exportar Excel</button>
                                    <button class="btn btn-primary waves-effect waves-light" type="button" onclick='grilla_listar()'>Buscar</button>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </form> 
            <div id="grilla_info"></div>
            <div id="grilla_casos_avanzado"></div> 
        </div>
    </div>
    <!-- FIN - Formulario Buscar -->
</div>
<!-- FIN - Panel Formularios y Grilla  -->

<?php require '../includes/footer_start.php' ?>
<?php require '../includes/footer_notificaciones.php' ?>
<?php require '../includes/footer_datatable.php' ?>
<?php require '../includes/footer_validacion.php' ?>
<script src="reporte_casosAvanzados.js"></script> 
<?php require '../includes/footer_end.php' ?>