<?php 
    require '../seguridad/seguridad.php';
    
    // Validacion de permisos
    $diagnosticos_ver = array_search('diagnosticos_ver', array_column($permisos, 'permiso_variable'));
    if (empty($diagnosticos_ver) && ($diagnosticos_ver !== 0)) {
        header("location:../_errores/401.php");
    } else {
?>
    <?php $pagina = "Diagnostico"; ?>
    <?php require '../includes/header_start.php'; ?>
    <?php require '../includes/header_datatables.php'; ?>
    <?php require '../includes/header_end.php'; ?>
    <link href="diagnostico.css" rel="stylesheet" type="text/css">
    <?php require '../includes/header_pagina.php'; ?>

    <!-- Inicio - Panel Formularios y Grilla  -->
     <div class="panel panel-default users-content">
            <div class="panel-heading">
                <?php
                $diagnosticos_alta = array_search('diagnosticos_alta', array_column($permisos, 'permiso_variable'));
                if (!empty($diagnosticos_alta) || ($diagnosticos_alta === 0)) { 
                ?>
                <button onclick="javascript:agrega_diagnostico_formulario();" class="btn btn-primary waves-effect waves-light m-l-5"> Agregar Diagnostico <i class="glyphicon glyphicon-plus" ></i></button>
                <?php } ?>
            </div>
            <!-- Inicio - Panel Formulario Alta  -->
            <div class="panel-body hidden" id="panel_formulario_alta">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card-box">
                            <h4 class="m-t-0 header-title"><b>Formulario Alta de Diagnostico</b></h4>
                            <p class="text-muted font-13 m-b-30">
                                Complete el formulario para dar de alta el diagnostico
                            </p>
                            <form  id="formulario_alta" name="formulario_alta">
                                <!-- Acción del formulario en opcion  -->
                                <input type="hidden" id="opcion" name="opcion" value="formulario_alta">
                                <div class="form-group">
                                    <label for="diagnostico_codigoICD_n">Codigo ICD*</label>
                                    <input type="text" name="diagnostico_codigoICD_n" class="form-control" id="diagnostico_codigoICD_n" maxlength="10">
                                </div>
                                <div class="form-group">
                                    <label for="diagnostico_nombre_n">Nombre*</label>
                                    <input type="text" name="diagnostico_nombre_n" class="form-control" id="diagnostico_nombre_n" maxlength="120">
                                </div>

                                <div class="form-group text-right m-b-0">
                                    <button id="btn_guardar_nuevo" class="btn btn-primary waves-effect waves-light" type="submit">
                                        Guardar
                                    </button>
                                    <button type="reset" id="btn_cancelar_nuevo" class="btn btn-default waves-effect waves-light m-l-5">
                                        Cancelar
                                    </button>
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
                            <h4 class="m-t-0 header-title"><b>Formulario Modificación de Diagnostico</b></h4>
                            <p class="text-muted font-13 m-b-30">
                                Modifique el diagnostico.
                            </p>
                            <form  id="formulario_modificacion" name="formulario_modificacion">
                                <!-- Acción del formulario en opcion y id de la diagnostico a modificar -->
                                <input type="hidden" id="diagnostico_id" name="diagnostico_id" value="0">
                                <input type="hidden" id="opcion" name="opcion" value="formulario_modificacion">
                                <div class="form-group">
                                    <label for="diagnostico_nombre">Codigo ICD*</label>
                                    <input type="text" name="diagnostico_codigoICD" placeholder="" class="form-control" id="diagnostico_codigoICD" maxlength="10">
                                </div>
                                <div class="form-group">
                                    <label for="diagnostico_nombre">Nombre*</label>
                                    <input type="text" name="diagnostico_nombre" placeholder="" class="form-control" id="diagnostico_nombre" maxlength="120">
                                </div>

                                <div class="form-group text-right m-b-0">
                                    <button class="btn btn-primary waves-effect waves-light" type="submit">
                                        Guardar
                                    </button>
                                    <button type="reset" id="btn_cancelar" class="btn btn-default waves-effect waves-light m-l-5">
                                        Cancelar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div> 
            <!-- Fin - Panel Formulario Modificacion  -->

            <!-- INICIO - Grilla  -->
            <div class="panel-body" id="panel_grilla">     
                <div class="row">
                    <div class="form-group col-md-4">
                        <input type="text"  name="diagnostico_codigoICD_buscar" class="form-control"  id="diagnostico_codigoICD_buscar" placeholder="Buscar por codigo ICD">
                    </div>
                    <div class="form-group col-md-4">
                        <input type="text"  name="diagnostico_nombre_buscar" class="form-control"  id="diagnostico_nombre_buscar" placeholder="Buscar por nombre">
                    </div>
                    <div class="form-group col-md-1">
                        <a href='javascript:void(0)'> <i onclick='grilla_listar()' class='fa fa-search' style="margin-top: 10px;"></i></a>
                    </div>
                </div>
                <div id="grilla_info"></div>
                <div id="grilla_diagnostico"></div>           
            </div>  
            <!-- FIN - Grilla  -->       
     </div>
    <!-- Fin - Panel Formularios y Grilla  -->

    <?php require '../includes/modal_activaDesactiva.php' ?>
    <?php require '../includes/footer_start.php' ?>
    <?php require '../includes/footer_notificaciones.php' ?>
    <?php require '../includes/footer_datatable.php' ?>
    <?php require '../includes/footer_validacion.php' ?>
    <script src="diagnostico.js"></script> 
    <?php require '../includes/footer_end.php' ?>
<?php } ?>