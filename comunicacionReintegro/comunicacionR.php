<?php 
    require '../seguridad/seguridad.php';

    $comunicacionesR_ver = array_search('comunicacionesR_ver', array_column($permisos, 'permiso_variable'));
    if (empty($comunicacionesR_ver) && ($comunicacionesR_ver !== 0)) {
        header("location:../_errores/401.php");
    } else {

    $pagina = "ComunicacionR";
    $reintegro_id = isset($_GET["reintegro_id"])?$_GET["reintegro_id"]:'';    
?>
    <!-- CSS -->    
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="../assets/js/jquery-ui.min.css" rel="stylesheet" type="text/css"/>
    <link href="../assets/css/core.css" rel="stylesheet" type="text/css">
    <link href="../assets/css/icons.css" rel="stylesheet" type="text/css">
    <link href="../assets/css/components.css" rel="stylesheet" type="text/css">
    <link href="../assets/css/pages.css" rel="stylesheet" type="text/css">
    <link href="../assets/css/menu.css" rel="stylesheet" type="text/css">
    <link href="../assets/css/responsive.css" rel="stylesheet" type="text/css">
    <?php require '../includes/header_datatables.php'; ?>
    
    <link href="comunicacionR.css" rel="stylesheet" type="text/css">
    
    
    <!-- Inicio - Panel Formularios y Grilla  -->
     <div class="panel panel-default users-content">
         
            <input type="hidden" id="reintegro_id_grilla" name="reintegro_id_grilla" value="<?php echo $reintegro_id?>" readonly="readonly">
            <?php
                $comunicacionesR_alta = array_search('comunicacionesR_alta', array_column($permisos, 'permiso_variable'));
                if (!empty($comunicacionesR_alta) || ($comunicacionesR_alta === 0)) { 
            ?>
            <!-- INICIO - Panel Formulario Alta  -->
            <div class="panel-body" id="panel_formulario_alta">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-box-form">
                            <form id="formulario_alta" name="formulario_alta">
                                <!-- Acción del formulario en opcion  -->
                                <input type="hidden" id="opcion" name="opcion" value="formulario_alta">
                                <input type="hidden" id="reintegro_id_n" name="reintegro_id_n" value="<?php echo $reintegro_id?>" readonly="readonly">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="comunicacionR_n">Comunicacion</label>
                                        <textarea name="comunicacionR_n" class="form-control" id="comunicacionR_n" rows="5"></textarea>
                                    </div>
                                </div>
                                <div class="form-group text-right m-b-0">
                                    <button type="reset" id="btn_cancelar_nuevo" class="btn btn-default waves-effect waves-light m-l-5">Cancelar</button>
                                    <button class="btn btn-primary waves-effect waves-light" type="submit">Guardar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>   
            <!-- Fin - Panel Formulario Alta  -->
            <?php } ?>
            
            <!-- INICIO - Panel Formulario Modificacion  -->
            <div class="panel-body" id="panel_formulario_modificacion">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-box-form">
                            <h4 class="m-t-0 header-title"><b>Formulario Modificación de Comunicacion</b></h4>
                            <p class="text-muted font-13 m-b-30">
                                Modifique la comunicacion
                            </p>
                            <form  id="formulario_modificacion" name="formulario_modificacion">
                                <!-- Acción del formulario en opcion y id de la comunicacionR a modificar -->
                                <input type="hidden" id="comunicacionR_id" name="comunicacionR_id" value="0">
                                <input type="hidden" id="reintegro_id" name="reintegro_id" value="<?php echo $reintegro_id?>">
                                <input type="hidden" id="comunicacionR_fechaIngreso" name="comunicacionR_fechaIngreso">
                                <input type="hidden" id="opcion" name="opcion" value="formulario_modificacion">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="comunicacionR">Comunicacion</label>
                                        <textarea name="comunicacionR" class="form-control" id="comunicacionR" rows="5"></textarea>
                                    </div>
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

            <!-- INICIO - Grilla de Comunicaciones -->    
            <div class="panel-body" id="panel_grilla">        
                <div id="grilla_comunicacionR"></div>      
            </div>
            <!-- FIN - Grilla de Comunicaciones --> 

            <!-- INICIO - Grilla de Historial de una comunicacionR -->
            <div class="panel-body" id="panel_grilla_historial">   
                <div id="grilla_comunicacionR_historial"></div>      
            </div>
            <!-- FIN - Grilla de Historial de una comunicacionR -->

    </div>
    <!-- Fin - Panel Formularios y Grilla  -->


    <!-- jQuery  -->
    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/jquery-ui.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/js/detect.js"></script>
    <script src="../assets/js/fastclick.js"></script>
    <script src="../assets/js/jquery.slimscroll.js"></script>
    <script src="../assets/js/jquery.blockUI.js"></script>
    <script src="../assets/js/waves.js"></script>
    <script src="../assets/js/wow.min.js"></script>
    <script src="../assets/js/jquery.nicescroll.js"></script>
    <script src="../assets/js/jquery.scrollTo.min.js"></script>

    <!-- Plugin para Switch -->
    <script src="../plugins/switchery/switchery.min.js"></script>

    <!-- peity charts -->
    <script src="../plugins/peity/jquery.peity.min.js"></script>
    <script src="../assets/js/jquery.core.js"></script>
    <script src="../assets/js/jquery.app.js"></script>

    <!-- Plugin para Fechas -->
    <script src="../assets/js/datePickerES.js"></script>

    <?php require '../includes/footer_notificaciones.php' ?>
    <?php require '../includes/footer_datatable.php' ?>
    <?php require '../includes/footer_validacion.php' ?>
    
    <script src="comunicacionR.js?random=<?php echo uniqid(); ?>"></script>
    </body>
    </html>
<?php } ?>