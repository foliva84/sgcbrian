<?php 
    require '../seguridad/seguridad.php';
    
    // Validacion de permisos
    $ciudades_ver = array_search('ciudades_ver', array_column($permisos, 'permiso_variable'));
    if (empty($ciudades_ver) && ($ciudades_ver !== 0)) {
        header("location:../_errores/401.php");
    } else {
?>
    <?php $pagina = "Ciudad"; ?>
    <?php require '../includes/header_start.php'; ?>
    <?php require '../includes/header_datatables.php'; ?>
    <?php require '../includes/header_end.php'; ?>
    <link href="ciudad.css" rel="stylesheet" type="text/css">
    <?php require '../includes/header_pagina.php'; ?>

    <!-- Inicio - Panel Formularios y Grilla  -->
     <div class="panel panel-default users-content">
        <div class="panel-heading">
            <?php
                $ciudades_alta = array_search('ciudades_alta', array_column($permisos, 'permiso_variable'));
                if (!empty($ciudades_alta) || ($ciudades_alta === 0)) { 
            ?>
            <button onclick="javascript:agrega_ciudad_formulario();" class="btn btn-primary waves-effect waves-light m-l-5"> Agregar Ciudad <i class="glyphicon glyphicon-plus" ></i></button></div>
            <?php } ?>
            <!-- Inicio - Panel Formulario Alta  -->
            <div class="panel-body hidden" id="panel_formulario_alta">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card-box">
                            <h4 class="m-t-0 header-title"><b>Formulario Alta de la Ciudad</b></h4>
                            <p class="text-muted font-13 m-b-30">
                                Complete el formulario para dar de alta la ciudad
                            </p>
                            <form  id="formulario_alta" name="formulario_alta">
                                <!-- Acción del formulario en opcion  -->
                                <input type="hidden" id="opcion" name="opcion" value="formulario_alta">
                                <div class="form-group">
                                    <label class="control-label" for="ciudad_pais_id_n">Pais</label>
                                    <select name="ciudad_pais_id_n" class="form-control" id="ciudad_pais_id_n"></select>
                                </div>
                                <div class="form-group">
                                    <label for="ciudad_nombre_n">Nombre*</label>
                                    <input type="text" name="ciudad_nombre_n" class="form-control" id="ciudad_nombre_n">
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
                            <h4 class="m-t-0 header-title"><b>Formulario Modificación de la Ciudad</b></h4>
                            <p class="text-muted font-13 m-b-30">
                                Modifique la ciudad.
                            </p>
                            <form  id="formulario_modificacion" name="formulario_modificacion">
                                <!-- Acción del formulario en opcion y id de la ciudad a modificar -->
                                <input type="hidden" id="ciudad_id" name="ciudad_id" value="0">
                                <input type="hidden" id="opcion" name="opcion" value="formulario_modificacion">
                                <div class="form-group">
                                    <label class="control-label" for="ciudad_pais_id">Pais</label>
                                    <select name="ciudad_pais_id" class="form-control" id="ciudad_pais_id"></select>
                                </div>
                                <div class="form-group">
                                    <label for="ciudad_nombre">Ciudad*</label>
                                    <input type="text" name="ciudad_nombre" placeholder="" class="form-control" id="ciudad_nombre">
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
                        <select name="ciudad_pais_id_b" class="form-control" id="ciudad_pais_id_b"></select>
                    </div>
                    <div class="form-group col-md-4">
                        <input type="text"  name="ciudad_nombre_buscar" class="form-control"  id="ciudad_nombre_buscar" placeholder="Buscar por ciudad">
                    </div>
                    <div class="form-group col-md-1">
                        <a href='javascript:void(0)'> <i onclick='grilla_listar()' class='fa fa-search' style="margin-top: 10px;"></i></a>
                    </div>
                </div>
                <div id="grilla_info"></div>
                <div id="grilla_ciudad"></div>           
            </div>  
            <!-- FIN - Grilla  -->



     </div>
    <!-- Fin - Panel Formularios y Grilla  -->

    <?php require '../includes/modal_activaDesactiva.php' ?>
    <?php require '../includes/footer_start.php' ?>
    <?php require '../includes/footer_notificaciones.php' ?>
    <?php require '../includes/footer_datatable.php' ?>
    <?php require '../includes/footer_validacion.php' ?>
    <script src="ciudad.js"></script> 
    <?php require '../includes/footer_end.php' ?>
<?php } ?>