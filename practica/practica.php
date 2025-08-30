<?php 
    require '../seguridad/seguridad.php';
    
    // Validacion de permisos
    $practicas_ver = array_search('practicas_ver', array_column($permisos, 'permiso_variable'));
    if (empty($practicas_ver) && ($practicas_ver !== 0)) {
        header("location:../_errores/401.php");
    } else {
?>
    <?php $pagina = "Practica"; ?>
    <?php require '../includes/header_start.php'; ?>
    <?php require '../includes/header_datatables.php'; ?>
    <?php require '../includes/header_end.php'; ?>
    <link href="practica.css" rel="stylesheet" type="text/css">
    <?php require '../includes/header_pagina.php'; ?>

    <!-- Inicio - Panel Formularios y Grilla  -->
     <div class="panel panel-default users-content">
        <div class="panel-heading">
            <?php
                $practicas_alta = array_search('practicas_alta', array_column($permisos, 'permiso_variable'));
                if (!empty($practicas_alta) || ($practicas_alta === 0)) { 
            ?>
            <button onclick="javascript:agrega_practica_formulario();" class="btn btn-primary waves-effect waves-light m-l-5"> Agregar Practica <i class="glyphicon glyphicon-plus" ></i></button></div>
            <?php } ?>
            <!-- Inicio - Panel Formulario Alta  -->
            <div class="panel-body hidden" id="panel_formulario_alta">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card-box">
                            <h4 class="m-t-0 header-title"><b>Formulario Alta de Practica</b></h4>
                            <p class="text-muted font-13 m-b-30">
                                Complete el formulario para dar de alta la practica
                            </p>
                            <form  id="formulario_alta" name="formulario_alta">
                                <!-- Acción del formulario en opcion  -->
                                <input type="hidden" id="opcion" name="opcion" value="formulario_alta">
                                <div class="form-group">
                                    <label for="practica_nombre_n">Nombre*</label>
                                    <input type="text" name="practica_nombre_n" class="form-control" id="practica_nombre_n" maxlength="60">
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
                            <h4 class="m-t-0 header-title"><b>Formulario Modificación de Practica</b></h4>
                            <p class="text-muted font-13 m-b-30">
                                Modifique la practica.
                            </p>
                            <form  id="formulario_modificacion" name="formulario_modificacion">
                                <!-- Acción del formulario en opcion y id de la practica a modificar -->
                                <input type="hidden" id="practica_id" name="practica_id" value="0">
                                <input type="hidden" id="opcion" name="opcion" value="formulario_modificacion">
                                <div class="form-group">
                                    <label for="practica_nombre">Nombre*</label>
                                    <input type="text" name="practica_nombre" class="form-control" id="practica_nombre" maxlength="60">
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

            <div class="panel-body" id="panel_grilla">        
                <div id="grilla_practica"></div>      
            </div>        
     </div>
    <!-- Fin - Panel Formularios y Grilla  -->

    <?php require '../includes/modal_activaDesactiva.php' ?>
    <?php require '../includes/footer_start.php' ?>
    <?php require '../includes/footer_notificaciones.php' ?>
    <?php require '../includes/footer_datatable.php' ?>
    <?php require '../includes/footer_validacion.php' ?>
    <script src="practica.js"></script>
    <?php require '../includes/footer_end.php' ?>
<?php } ?>