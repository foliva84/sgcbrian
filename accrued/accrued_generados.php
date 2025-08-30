<?php 
    require '../seguridad/seguridad.php';

    if (Usuario::puede("ver_accrued") == 0) {

        header("location:../_errores/401.php");

    } else {
?>
    <?php $pagina = "Accrued Generados"; ?>
    <?php require '../includes/header_start.php'; ?>
    <?php require '../includes/header_datatables.php'; ?>
    <?php require '../includes/header_end.php'; ?>
    <link href="../assets/css/iconos_propios.css" rel="stylesheet" type="text/css">
    <?php require '../includes/header_pagina.php'; ?>


    <!-- INICIO - Panel Formularios y Grilla  -->
    <div class="panel panel-default users-content">
        <div class="panel-body " id="panel_formulario_vista">
            <div class="row">
                <!-- INICIO - Listado Accrued -->
                <div class="col-sm-12" id="acc_generados">
                    <div id="panel_formulario_acc_generados" name="panel_formulario_acc_generados">
                        <div class="col-sm-12">
                            <div class="card-box-form">
                                <div class="row">
                                    <div id="grilla_accrued_generados"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- FIN - Listado Accrued -->

                <!-- INICIO - Accrued -->
                <div class="col-sm-12" id="acc_procesado">
                    <div class="panel-body hidden" id="panel_formulario_acc_procesado">
                        <div class="col-sm-12">
                            <div class="card-box-form">
                                <div class="row">
                                    <div class="text-right">
                                        <form id="form_procesa_accrued" name="form_procesa_accrued" action='accrued_cb.php' method="post">
                                            <input type="hidden" id="opcion" name="opcion" value="exportar_accrued" readonly="readonly">
                                            <input type="hidden" id="exp_acc_id" name="exp_acc_id" value="" readonly="readonly">
                                            <div class="text-right">
                                                <button class="btn btn-default waves-effect waves-light" type="button" id="btn_volver_lista_acc">Volver</button>
                                                <button class="btn btn-primary waves-effect waves-light" type="submit" id="btn_exportar_accrued">Descargar Accrued</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="card-box-form">
                                <div class="row">
                                    <div id="grilla_reint_procesado"></div>
                                    <div id="grilla_fci_procesado"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- FIN - Accrued -->
            </div>
        </div>
    </div>
    <!-- FIN - Panel Formularios y Grilla  -->


    <?php require '../includes/footer_start.php' ?>
    <?php require '../includes/footer_notificaciones.php' ?>
    <?php require '../includes/footer_datatable.php' ?>
    <?php require '../includes/footer_validacion.php' ?>
    <script src="accrued.js?random=<?php echo uniqid(); ?>"></script>
    <script src="../plugins/notifyjs/dist/notify.min.js"></script>
    <script src="../plugins/notifications/notify-metro.js"></script>
    <script src="../assets/js/jquery.number.min.js"></script>
    <?php require '../includes/footer_end.php' ?>

<?php } ?>