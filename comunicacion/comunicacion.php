<?php 
    require '../seguridad/seguridad.php';

    $comunicaciones_ver = array_search('comunicaciones_ver', array_column($permisos, 'permiso_variable'));
    if (empty($comunicaciones_ver) && ($comunicaciones_ver !== 0)) {
        header("location:../_errores/401.php");
    } else {

    $pagina = "Comunicacion"; 
    $caso_id = isset($_GET["caso_id"])?$_GET["caso_id"]:'';
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
    <link href="comunicacion.css" rel="stylesheet" type="text/css">


    <!-- INICIO - Modal Cobertura  -->
    <div id="modal-cobertura" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-wsLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" style="width:70%;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="panel panel-default users-content">
                    <div id="grilla_producto_cobertura"></div>      
                </div>
            </div>
        </div>
    </div>
    <!-- FIN - Modal Cobertura  -->   
    
    
    <!-- Inicio - Panel Formularios y Grilla  -->
     <div class="panel panel-default users-content">
            <!-- INICIO - Panel Formulario Datos Generales Caso  -->
            <div class="panel-body" id="panel_formulario_vistaDatosCaso">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-box-form">
                            <!-- Acción del formulario en opcion  -->
                            <input type="hidden" id="opcion" name="opcion" value="formulario_vistaDatosCaso">
                            <input type="hidden" id="caso_id_dGeneral" name="caso_id_dGeneral" value="<?php echo $caso_id?>">
                            <h4 class="m-t-0 m-b-20 header-title"><b>Datos Generales del Caso</b></h4>
                            <div class="row">
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label class="control-label" for="caso_numero_dGeneral">Caso</label>
                                        <input class="form-control" id="caso_numero_dGeneral" name="caso_numero_dGeneral" type="text" readonly="readonly">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label" for="caso_beneficiarioNombre_dGeneral">Nombre del Beneficiario</label>
                                        <input class="form-control" id="caso_beneficiarioNombre_dGeneral" name="caso_beneficiarioNombre_dGeneral" type="text" readonly="readonly">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label" for="caso_telefono_dGeneral">Teléfono</label>
                                        <input class="form-control" id="caso_telefono_dGeneral" name="caso_telefono_dGeneral" type="text" readonly="readonly">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label" for="caso_pais_nombre__dGeneral">Pais</label>
                                        <input class="form-control" id="caso_pais_nombre__dGeneral" name="caso_pais_nombre__dGeneral" type="text" readonly="readonly">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label" for="caso_direccion__dGeneral">Dirección</label>
                                        <input class="form-control" id="caso_direccion__dGeneral" name="caso_direccion__dGeneral" type="text" readonly="readonly">
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <label class="control-label" for="producto_cobertura">Cobertura</label>
                                    <span class="input-group-btn">
                                        <button type="button" onclick="grilla_producto();" data-toggle="modal" data-target="#modal-cobertura" class="btn waves-effect waves-light btn-warning"><i class="ion-search"></i></button>
                                    </span>
                                </div>
                            </div>  
                        </div>
                    </div>
                </div>
            </div>   
            <!-- FIN - Panel Formulario Datos Generales Caso  -->

            <?php
                $comunicaciones_alta = array_search('comunicaciones_alta', array_column($permisos, 'permiso_variable'));
                if (!empty($comunicaciones_alta) || ($comunicaciones_alta === 0)) { 
            ?>
            <!-- INICIO - Panel Formulario Alta  -->
            <div class="panel-body" id="panel_formulario_alta">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-box-form">
                            <form id="formulario_alta" name="formulario_alta">
                                <!-- Acción del formulario en opcion  -->
                                <input type="hidden" id="opcion" name="opcion" value="formulario_alta">
                                <input type="hidden" id="caso_id_n" name="caso_id_n" value="<?php echo $caso_id?>">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="comunicacion_asunto_id_n">Asunto</label>
                                        <select name="comunicacion_asunto_id_n" class="form-control" id="comunicacion_asunto_id_n"></select>
                                    </div>                           
                                    <div class="form-group">
                                        <label for="comunicacion_casoEstado_id_n">Estado del Caso</label>
										<i class='fa fa-info-circle' data-toggle='tooltip' data-placement='top' title='Si el caso es de Tipo Médico, debe ingresar el diagnóstico para poder cerrar el caso.'></i>          
                                        <select name="comunicacion_casoEstado_id_n" class="form-control" id="comunicacion_casoEstado_id_n"></select>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label for="comunicacion_n">Comunicacion</label>
                                        <textarea name="comunicacion_n" class="form-control" id="comunicacion_n" rows="5"></textarea>
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
                                <!-- Acción del formulario en opcion y id de la comunicacion a modificar -->
                                <input type="hidden" id="comunicacion_id" name="comunicacion_id" value="0">
                                <input type="hidden" id="caso_id" name="caso_id" value="<?php echo $caso_id?>">
                                <input type="hidden" id="comunicacion_fechaIngreso" name="comunicacion_fechaIngreso">
                                <input type="hidden" id="opcion" name="opcion" value="formulario_modificacion">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label" for="comunicacion_asunto_id">Asunto</label>
                                        <select name="comunicacion_asunto_id" class="form-control" id="comunicacion_asunto_id"></select>
                                    </div>
                                    <div class="form-group">
                                        <label for="comunicacion_casoEstado_id">Estado del Caso</label>
                                        <select name="comunicacion_casoEstado_id" class="form-control" id="comunicacion_casoEstado_id"></select>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label for="comunicacion">Comunicacion</label>
                                        <textarea name="comunicacion" class="form-control" id="comunicacion" rows="5"></textarea>
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
                <div class="col-md-1">
                    <label class="control-label" for="asunto_tipo_id">Filtrar por</label>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <select name="asunto_tipo_id" class="form-control" id="asunto_tipo_id"></select>
                    </div>
                </div>
                
                <div id="grilla_comunicacion"></div>      
            </div>
            <!-- FIN - Grilla de Comunicaciones --> 


            <!-- INICIO - Grilla de Historial de una comunicacion -->
            <div class="panel-body" id="panel_grilla_historial">   
                <div id="grilla_comunicacion_historial"></div>      
            </div>
            <!-- FIN - Grilla de Historial de una comunicacion -->

    </div>
    <!-- Fin - Panel Formularios y Grilla  -->

    <!-- Modal services-->
    <div class="modal fade bd-example-modal-lg" id="servicesModal" tabindex="-1" role="dialog" aria-labelledby="servicesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="servicesModalLabel">Creación de servicio </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                        <div class="tab-pane" id="servicios">   
                            <object type="text/html" id="pantalla_servicios_comuni" width="100%" height="1000" ></object>  
                        </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal confirmation services -->
    <div class="modal fade bd-example-modal-lg" id="servicesConfirmarModal" tabindex="-1" role="dialog" aria-labelledby="servicesConfirmarModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="servicesConfirmarModalLabel">Confirmación de servicios </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="panel-body" id="panel_grilla">      
                        <form method="post" id="FormServiceConfirmation">
                            <button class="btn btn-primary waves-effect waves-light" id="btn_service_confirmation" type="button">Confirmar</button>
                            <div id="grilla_servicios_sin_confirmar"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
    <!-- Plugin para Select -->
    <script src="../plugins/select2/select2.min.js" type="text/javascript"></script>

    <!-- peity charts -->
    <script src="../plugins/peity/jquery.peity.min.js"></script>
    <script src="../assets/js/jquery.core.js"></script>
    <script src="../assets/js/jquery.app.js"></script>

    <!-- Plugin para Fechas -->
    <script src="../assets/js/datePickerES.js"></script>

    <?php require '../includes/footer_notificaciones.php' ?>
    <?php require '../includes/footer_validacion.php' ?>
    
    <script src="comunicacion.js"></script> 
    </body>
    </html>
<?php } ?>