<?php 
    require '../seguridad/seguridad.php';
    
    // Validacion de permisos
    $agenda_ver = array_search('agenda_ver', array_column($permisos, 'permiso_variable'));
    if (empty($agenda_ver) && ($agenda_ver !== 0)) {
        header("location:../_errores/401.php");
    } else {
?>
    <?php $pagina = "Agenda"; ?>
    <?php require '../includes/header_start.php'; ?>
    <?php require '../includes/header_datatables.php'; ?>
    <?php require '../includes/header_end.php'; ?>
    <link href="agenda.css" rel="stylesheet" type="text/css">
    <?php require '../includes/header_pagina.php'; ?>


    <!-- INICIO - Panel Formularios y Grilla  -->
    <div class="panel panel-default users-content">
        <!-- INICIO - Formulario Asignacion -->
        <div class="panel-body hidden" id="panel_formulario_asignacion">
            <div class="row">
                <form  id="formulario_asignacion" name="formulario_asignacion">
                    <input type="hidden" id="agenda_caso_id" name="agenda_caso_id" value="0">
                    <input type="hidden" id="opcion" name="opcion" value="formulario_asignacion">
                    <div class="col-sm-12">
                        <div class="card-box-form">
                            <div class="row">
                                <h4 class="m-t-0 m-b-20 header-title"><b>Asignar Caso</b></h4>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="agenda_caso_numero">Número de Caso</label>
                                        <input type="text" name="agenda_caso_numero" class="form-control" id="agenda_caso_numero" readonly="readonly">
                                    </div>  
                                </div>
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label class="control-label" for="agenda_usuarioAsignado_a">Asignar a</label>
                                        <select name="agenda_usuarioAsignado_a" class="form-control" id="agenda_usuarioAsignado_a"></select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label>&nbsp;</label>
                                    <div class="form-group">
                                        <button type="reset" id="btn_cancelar_asignacion" class="btn btn-inverse waves-effect waves-light">Cancelar</button>
                                        <button type="submit" id="btn_asignar_caso" class="btn btn-success waves-effect waves-light"><span>Asignar Caso</span> </button>
                                    </div>
                                </div>       
                            </div>
                        </div>
                    </div>
                </form>
            </div>            
        </div>            
        <!-- FIN - Formulario Asignacion -->

        <!-- INICIO - Formulario Re Asignacion -->
        <div class="panel-body hidden" id="panel_formulario_reasignacion">
            <div class="row">
                <form  id="formulario_reasignacion" name="formulario_reasignacion">
                    <input type="hidden" id="agenda_id_r" name="agenda_id_r" value="0" readonly="readonly">
                    <input type="hidden" id="agenda_caso_id_r" name="agenda_caso_id_r" value="0" readonly="readonly">
                    <input type="hidden" id="opcion" name="opcion" value="formulario_reasignacion" readonly="readonly">
                    <div class="col-sm-12">
                        <div class="card-box-form">
                            <div class="row">
                                <h4 class="m-t-0 m-b-20 header-title"><b>Re-Asignar Caso</b></h4>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="agenda_caso_numero_r">Número de Caso</label>
                                        <input type="text" name="agenda_caso_numero_r" class="form-control" id="agenda_caso_numero_r" readonly="readonly">
                                    </div>  
                                </div>
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label class="control-label" for="agenda_usuarioAsignado_r">Asignar a</label>
                                        <select name="agenda_usuarioAsignado_r" class="form-control" id="agenda_usuarioAsignado_r"></select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label>&nbsp;</label>
                                    <div class="form-group">
                                        <button type="reset" id="btn_cancelar_reasignacion" class="btn btn-inverse waves-effect waves-light">Cancelar</button>
                                        <button type="submit" id="btn_reAsignar_caso" class="btn btn-success waves-effect waves-light"><span>Re-Asignar Caso</span> </button>
                                    </div>
                                </div>       
                            </div>
                        </div>
                    </div>
                </form>
            </div>            
        </div>            
        <!-- FIN - Formulario Re Asignacion -->

        <!-- INICIO - Grilla  -->
        <div class="panel-body" id="panel_grilla">   
            <div class="row">
                <div class="form-group col-md-2">
                    <input type="text"  name="caso_numero_desde_b" class="form-control"  id="caso_numero_desde_b" placeholder="Caso desde" >
                </div>
                <div class="form-group col-md-2">
                    <input type="text"  name="caso_numero_hasta_b" class="form-control"  id="caso_numero_hasta_b" placeholder="Caso hasta" >
                </div>
                <div class="form-group col-md-3">
                    <select name="caso_estado_id_b" class="form-control" id="caso_estado_id_b"></select>
                </div>
                <div class="form-group col-md-3">
                    <select name="caso_usuarioAsignado_id_b" class="form-control" id="caso_usuarioAsignado_id_b"></select>
                </div>
                <div class="form-group col-md-1">
                    <a href='javascript:void(0)'> <i onclick='grilla_listar()' class='fa fa-search' style="margin-top: 10px;"></i></a>
                </div>
            </div>
            <div id="grilla_info"></div>
            <div id="grilla_agenda"></div>      
        </div>  
        <!-- FIN - Grilla  -->
    </div>
    <!-- FIN - Panel Formularios y Grilla  -->

    <?php require '../includes/footer_start.php' ?>
    <?php require '../includes/footer_notificaciones.php' ?>
    <?php require '../includes/footer_datatable.php' ?>
    <?php require '../includes/footer_validacion.php' ?>
    <script src="agenda.js"></script> 
    <?php require '../includes/footer_end.php' ?>
<?php } ?>