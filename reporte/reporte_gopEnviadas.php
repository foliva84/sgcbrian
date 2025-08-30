<?php require '../seguridad/seguridad.php'; ?> 
<?php $pagina = "Reporte_Gop_Enviadas"; ?>
<?php require '../includes/header_start.php'; ?>
<?php require '../includes/header_datatables.php'; ?>
<?php require '../includes/header_end.php'; ?>
<link href="reporte.css" rel="stylesheet" type="text/css">
<?php require '../includes/header_pagina.php'; ?>




<!-- INICIO - Panel Formularios y Grilla  -->
<div class="panel panel-default users-content">
    <!-- INICIO - Formulario Buscar -->
    <div class="panel-body" id="panel_buscador">
        <div class="row">
            <form  id="formulario_reporte" name="formulario_reporte" action='reporte_gopEnviadas_cb.php' method="post">
                <!-- Acción del formulario en opcion  -->
                <input type="hidden" id="opcion" name="opcion" value="exportar_excel" readonly="readonly">
                <div class="col-sm-12">
                    <div class="card-box-form">
                        <div class="row">
                            <h4 class="m-t-0 m-b-20 header-title"><b>Buscador de Gop Enviadas</b></h4>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="caso_numero">Caso</label>
                                    <input type="text"  name="caso_numero" class="form-control"  id="caso_numero" placeholder="Caso numero" >
                                </div>
                            </div>

                            <div class="col-md-8"></div>
                            
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <div class="form-group">  
                                    <button class="btn btn-primary waves-effect waves-light" type="button" onclick='grilla_listar()'>Buscar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form> 
        </div>
    </div>
    <div class="panel-body" id="panel_grilla">
        <div class="row">
            <div class="col-sm-12">
                <div id="grilla_info"></div>
                <div id="grilla_gop_enviadas"></div>
            </div>
        </div>
    </div>
    <!-- FIN - Formulario Buscar -->
    
    <!-- INICIO - Formulario GOP -->
    <div class="panel-body hidden" id="panel_formulario_gop">
        <div class="row">
            <!-- Acción del formulario en opcion  -->
            <input type="hidden" id="opcion" name="opcion" value="ver_gop">
            <div class="col-sm-12">
                <div class="col-sm-12">
                    <div class="card-box">
                        <h4 class="m-t-0 m-b-20 header-title"><b>Datos de la GOP</b></h4>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group has-success">
                                    <label class="control-label" for="numero_caso_g">Operador</label>
                                    <input type="text" name="usuario_nombre_g" class="form-control" id="usuario_nombre_g" readonly="readonly">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group has-success">
                                    <label class="control-label" for="numero_caso_g">Fecha de la GOP</label>
                                    <input type="text" name="fecha_g" class="form-control" id="fecha_g" readonly="readonly">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label>&nbsp;</label>
                                <div class="form-group has-success">
                                    <button type="reset" id="btn_volver" class="btn btn-primary waves-effect waves-light">Volver</button>
                                </div>
                            </div>
                        </div>    
                    </div>
                </div>
                
                <div class="col-sm-6">
                    <div class="card-box">
                        <h4 class="m-t-0 m-b-20 header-title"><b>Datos del Caso</b></h4>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group has-success">
                                    <label class="control-label" for="numero_caso_g">Número de Caso</label>
                                    <input type="text" name="numero_caso_g" class="form-control" id="numero_caso_g" readonly="readonly">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label class="control-label" for="paciente_g">Paciente</label>
                                    <input type="text" name="paciente_g" class="form-control" id="paciente_g" readonly="readonly">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="nVoucher_g">Número de Voucher</label>
                                    <input type="text" name="nVoucher_g" class="form-control" id="nVoucher_g" readonly="readonly">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label" for="fecha_nacimiento_g">Fecha de Nacimiento</label>
                                    <input type="text" name="fecha_nacimiento_g" class="form-control" id="fecha_nacimiento_g" readonly="readonly">
                                    <input type="hidden" name="fecha_nacimiento_ansi_g" class="form-control" id="fecha_nacimiento_ansi_g">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label" for="edad_g">Edad</label>
                                    <input type="text" name="edad_g" class="form-control" id="edad_g" readonly="readonly">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label" for="sintomas_g">Síntomas</label>
                                    <textarea name="sintomas_g" class="form-control" id="sintomas_g" rows="3" readonly="readonly"></textarea>
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="card-box">
                        <h4 class="m-t-0 m-b-20 header-title"><b>Datos de Contacto</b></h4>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="pais_g">País</label>
                                    <input type="text" name="pais_g" class="form-control" id="pais_g" readonly="readonly">
                                    <input type="hidden" name="pais_id_g" class="form-control" id="pais_id_g">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="ciudad_g">Ciudad</label>
                                    <input type="text" name="ciudad_g" class="form-control" id="ciudad_g" readonly="readonly">
                                    <input type="hidden" name="ciudad_id_g" class="form-control" id="ciudad_id_g">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label" for="telefono_g">Teléfono</label>
                                    <input type="text" name="telefono_g" class="form-control" id="telefono_g" readonly="readonly">
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="control-label" for="direccion_g">Dirección</label>
                                    <input type="text" name="direccion_g" class="form-control" id="direccion_g" readonly="readonly">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="cp_g">Código Postal</label>
                                    <input type="text" name="cp_g" class="form-control" id="cp_g" readonly="readonly">
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label class="control-label" for="hotel_g">Hotel</label>
                                    <input type="text" name="hotel_g" class="form-control" id="hotel_g" readonly="readonly">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="habitacion_g">Habitacion</label>
                                    <input type="text" name="habitacion_g" class="form-control" id="habitacion_g" readonly="readonly">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label" for="">&nbsp;</label>
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="card-box">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="col-md-12">
                                    <div class="form-group has-success">
                                        <label class="control-label" for="prestador_g">Prestador</label>
                                        <input type="text" name="prestador_g" class="form-control" id="prestador_g" readonly="readonly">
                                        <input type="hidden" name="prestador_id_g" class="form-control" id="prestador_id_g">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group has-success">
                                        <label class="control-label" for="email_g">E-mail Prestador</label>
                                        <textarea type="text" name="email_g" class="form-control" id="email_g" rows="2" maxlength="150" readonly="readonly"></textarea>
                                    </div>
                                </div>
                            </div>   
                            <div class="col-sm-8">
                                <div class="col-md-12">
                                    <div class="form-group has-warning">
                                        <label class="control-label" for="observaciones_g">Observaciones</label>
                                        <textarea name="observaciones_g" class="form-control" id="observaciones_g" rows="6" readonly="readonly"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>                         
            </div>
        </div>
    </div>
    <!-- FIN - Formulario GOP -->
            
    
</div>
<!-- FIN - Panel Formularios y Grilla  -->

<?php require '../includes/footer_start.php' ?>
<?php require '../includes/footer_notificaciones.php' ?>
<?php require '../includes/footer_datatable.php' ?>
<?php require '../includes/footer_validacion.php' ?>
<script src="reporte_gopEnviadas.js"></script> 
<?php require '../includes/footer_end.php' ?>