<?php 
    require '../seguridad/seguridad.php';
    
    // Validacion de permisos
    $tipoAsistencia_ver = array_search('tipoAsistencia_ver', array_column($permisos, 'permiso_variable'));
    if (empty($tipoAsistencia_ver) && ($tipoAsistencia_ver !== 0)) {
        header("location:../_errores/401.php");
    } else {
?>
    <?php $pagina = "Tipo de Asistencia"; ?>
    <?php require '../includes/header_start.php'; ?>
    <?php require '../includes/header_datatables.php'; ?>
    <?php require '../includes/header_end.php'; ?>
    <link href="tipoAsistencia.css" rel="stylesheet" type="text/css">
    <?php require '../includes/header_pagina.php'; ?>

    <!-- Inicio - Panel Formularios y Grilla  -->
     <div class="panel panel-default users-content">
        <div class="panel-heading">
            <?php
                $tipoAsistencia_alta = array_search('tipoAsistencia_alta', array_column($permisos, 'permiso_variable'));
                if (!empty($tipoAsistencia_alta) || ($tipoAsistencia_alta === 0)) { 
            ?>
            <button onclick="javascript:agrega_tipoAsistencia_formulario();" class="btn btn-primary waves-effect waves-light m-l-5"> Agregar Tipo de Asistencia <i class="glyphicon glyphicon-plus" ></i></button></div>
            <?php } ?>
            <!-- Inicio - Panel Formulario Alta  -->
            <div class="panel-body hidden" id="panel_formulario_alta">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card-box">
                            <h4 class="m-t-0 header-title"><b>Formulario Alta de Tipo de Asistencia</b></h4>
                            <p class="text-muted font-13 m-b-30">
                                Complete el formulario para dar de alta un tipo de asistencia
                            </p>
                            <form  id="formulario_alta" name="formulario_alta">
                                <!-- Acción del formulario en opcion  -->
                                <input type="hidden" id="opcion" name="opcion" value="formulario_alta">
                                <div class="form-group">
                                    <label for="tipoAsistencia_clasificacion_id_n">Clasificación</label>
                                    <select name="tipoAsistencia_clasificacion_id_n" class="form-control" id="tipoAsistencia_clasificacion_id_n">
                                        <option value="">Seleccione</option>
                                        <option value="1">Médica</option>
                                        <option value="2">Equipaje</option>
                                        <option value="3">Demora del Vuelo</option>
                                        <option value="4">Otro</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="tipoAsistencia_nombre_n">Tipo de Asistencia</label>
                                    <input type="text" name="tipoAsistencia_nombre_n" class="form-control" id="tipoAsistencia_nombre_n">
                                </div>
                                <div class="form-group text-right m-b-0">
                                    <button id="btn_guardar_nuevo" class="btn btn-primary waves-effect waves-light" type="submit">Guardar</button>
                                    <button type="reset" id="btn_cancelar_nuevo" class="btn btn-default waves-effect waves-light m-l-5">Cancelar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>   
            <!-- Fin - Panel Formulario Alta  -->


            <!-- Inicio - Panel Formulario Modificacion  -->
            <div class="panel-body hidden" id="panel_formulario_modificacion">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card-box">
                            <h4 class="m-t-0 header-title"><b>Formulario Modificación de Tipo de Asistencia</b></h4>
                            <p class="text-muted font-13 m-b-30">
                                Modifique el tipo de asistencia
                            </p>
                            <form  id="formulario_modificacion" name="formulario_modificacion">
                                <!-- Acción del formulario en opcion y id del tipo de asistencia a modificar -->
                                <input type="hidden" id="tipoAsistencia_id" name="tipoAsistencia_id" value="0">
                                <input type="hidden" id="opcion" name="opcion" value="formulario_modificacion">
                                <div class="form-group">
                                    <label class="control-label" for="tipoAsistencia_clasificacion_id">Clasificación</label>
                                    <select name="tipoAsistencia_clasificacion_id" class="form-control" id="tipoAsistencia_clasificacion_id">
                                        <option value="1">Médica</option>
                                        <option value="2">Equipaje</option>
                                        <option value="3">Demora del Vuelo</option>
                                        <option value="4">Otro</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="tipoAsistencia_nombre">Tipo de Asistencia</label>
                                    <input type="text" name="tipoAsistencia_nombre" class="form-control" id="tipoAsistencia_nombre">
                                </div>
                                <div class="form-group text-right m-b-0">
                                    <button class="btn btn-primary waves-effect waves-light" type="submit">Guardar</button>
                                    <button type="reset" id="btn_cancelar" class="btn btn-default waves-effect waves-light m-l-5">Cancelar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div> 
            <!-- Fin - Panel Formulario Modificacion  -->

            <div class="panel-body" id="panel_grilla">        
                <div id="grilla_tipoAsistencia"></div>      
            </div>        
     </div>
    <!-- Fin - Panel Formularios y Grilla  -->

    <?php require '../includes/modal_activaDesactiva.php' ?>
    <?php require '../includes/footer_start.php' ?>
    <?php require '../includes/footer_notificaciones.php' ?>
    <?php require '../includes/footer_datatable.php' ?>
    <?php require '../includes/footer_validacion.php' ?>
    <script src="tipoAsistencia.js"></script> 
    <?php require '../includes/footer_end.php' ?>
<?php } ?>