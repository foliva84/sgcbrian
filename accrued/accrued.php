<?php 
    require '../seguridad/seguridad.php';

    if (Usuario::puede("ver_accrued") == 0) {

        header("location:../_errores/401.php");

    } else {
?>
    <?php $pagina = "Accrued"; ?>
    <?php require '../includes/header_start.php'; ?>
    <?php require '../includes/header_datatables.php'; ?>
    <?php require '../includes/header_end.php'; ?>
    <?php require '../includes/header_pagina.php'; ?>

    <!-- INICIO - Panel Formularios y Grilla  -->
    <div class="panel panel-default users-content">
        <div class="panel-body " id="panel_formulario_vista">
            <div class="row">
                <!-- INICIO - Menu Tabs -->
                <div class="col-sm-12">
                    <ul class="nav nav-tabs navtab-custom nav-justified">
                        <li id="tab_acc_reint" class="active">
                            <a href="#acc_reint" data-toggle="tab" aria-expanded="true">
                                <span class="hidden-xs">Reintegros</span>
                            </a>
                        </li>
                        <li id="tab_acc_fci" class="">
                            <a href="#acc_fci" data-toggle="tab" aria-expanded="false">
                                <span class="hidden-xs">Items de Facturas</span>
                            </a>
                        </li>
                        <li id="tab_acc_preview" class="">
                            <a href="#acc_preview" data-toggle="tab" aria-expanded="false">
                                <span class="hidden-xs">Preview</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- FIN - Menu Tabs -->

                <!-- INICIO - TAB -->
                <div class="tab-content">
                    <!-- INICIO - Tab ACC REINT -->
                    <div class="tab-pane active" id="acc_reint">
                        <?php 
                            // Valida si existe un Lote Borrador
                            if (Accrued::existe_lote_pendiente_informar(1) != true) {
                        ?>
                            <div id="panel_formulario_acc_reint" name="panel_formulario_acc_reint">
                                <div class="col-sm-12">
                                    <div class="card-box-form">
                                        <div class="row">
                                            <div class='card-box-form table-responsive col-md-12'>
                                                <form id="formulario_acc_reint" name="formulario_acc_reint" method="get" action="">
                                                    <!-- Acción del formulario en opcion  -->
                                                    <input type="hidden" id="opcion" name="opcion" value="formulario_acc_reint" readonly="readonly">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <label class="control-label" for="reint_fechaPago">Fecha Pago</label>
                                                            <div class="form-group">
                                                                <div class="form-group has-warning col-md-6">
                                                                    <input type="text" class="form-control" name="reint_fechaPago_desde" id="reint_fechaPago_desde" placeholder="Fecha desde" required>
                                                                </div>
                                                                <div class="form-group has-warning col-md-6">
                                                                    <input type="text" class="form-control" name="reint_fechaPago_hasta" id="reint_fechaPago_hasta" placeholder="Fecha hasta" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label>&nbsp;</label>
                                                            <div class="form-group">
                                                                <button class="btn btn-primary waves-effect waves-light" type="button" id="btn_buscar_reint" onclick="listar_reintegros()">Buscar</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div id="grilla_acc_reint_info"></div>
                                            <div id="grilla_acc_reint"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="col-sm-12">
                                <div class="card-box-form text-center">
                                    <div class="row">
                                        Existe un lote pendiente informar. Para ver los lotes creados, haga <a target='_blank' href='../accrued/accrued_generados.php'><b>click aquí</b></a>.
                                    </div>
                                </div>
                            </div>
                        <?php } ?>            
                    </div>
                    <!-- FIN - Tab ACC REINT -->

                    <!-- INICIO - Tab ACC FCI -->
                    <div class="tab-pane" id="acc_fci">
                        <?php 
                            // Valida si existe un Lote Borrador
                            if (Accrued::existe_lote_pendiente_informar(2) != true) {
                        ?>
                            <div id="panel_formulario_acc_fci" name="panel_formulario_acc_fci">
                                <div class="col-sm-12">
                                    <div class="card-box-form">
                                        <div class="row">
                                            <form id="formulario_acc_fci" name="formulario_acc_fci" method="get" action="">
                                                <div class="col-md-6">
                                                    <div class="card-box-form">
                                                        <div class="row">
                                                            <!-- Acción del formulario en opcion  -->
                                                            <input type="hidden" id="opcion" name="opcion" value="guardar_acc_fci" readonly="readonly">
                                                            <div class="col-md-6">
                                                                <label class="control-label" for="fci_fechaPago">Fecha Pago</label>
                                                                <div class="form-group">
                                                                    <div class="form-group col-md-6">
                                                                        <input type="text" class="form-control" name="fci_fechaPago_desde" id="fci_fechaPago_desde" placeholder="Fecha desde">
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <input type="text" class="form-control" name="fci_fechaPago_hasta" id="fci_fechaPago_hasta" placeholder="Fecha hasta">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label>&nbsp;</label>
                                                                <div class="form-group">
                                                                    <button class="btn btn-primary waves-effect waves-light" type="button" id='btn_buscar_fci' onclick="listar_fci()">Buscar</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="card-box-form">
                                                        <div class="row">
                                                            <div class="col-md-9">
                                                                <div class="col-md-6">
                                                                    <label class="control-label">Referencia > Importe Límite USD</label>
                                                                    <div class="form-group">
                                                                        <input type="text" class="form-control" name="fci_importe_limite_n" id="fci_importe_limite_n" maxlength="12">
                                                                    </div>
                                                                </div>
                                                                <div id="campo_acumulado" class="col-md-6">
                                                                    <label class="control-label">Importe Acumulado USD</label>
                                                                    <div class="form-group">
                                                                        <input type="text" class="form-control" name="fci_importe_acumulado_v" id="fci_importe_acumulado_v" maxlength="12" readonly>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label>&nbsp;</label>
                                                                <div class="form-group">
                                                                    <button class='btn btn-success waves-effect waves-light text-right hidden' type='submit' id='btn_borrador_fci' disabled>Crear Borrador FCI</button>
                                                                    <input name="fci_seleccionados" id="fci_seleccionados" class="form-control" type="hidden" readonly="true"/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                            <div id="grilla_acc_fci_info"></div>
                                            <div id="grilla_acc_fci"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="col-sm-12">
                                <div class="card-box-form text-center">
                                    <div class="row">
                                        Existe un lote pendiente informar. Para ver los lotes creados, haga <a target='_blank' href='../accrued/accrued_generados.php'><b>click aquí</b></a>.
                                    </div>
                                </div>
                            </div>
                        <?php } ?>      
                    </div>
                    <!-- FIN - Tab ACC FCI -->

                    <!-- INICIO - Tab ACC Preview -->
                    <div class="tab-pane" id="acc_preview">
                        <div id="panel_formulario_acc_fci" name="panel_formulario_acc_fci">
                            <div class="col-sm-12">
                                <div class="card-box-form">
                                    <div class="row">
                                        <div id="grilla_acc_reint_preview"></div>
                                        <div id="grilla_acc_fci_preview"></div>
                                    </div>
                                    <div class="text-right">
                                        <a target='_blank' href='../accrued/accrued_generados.php'>
                                            <button class="btn btn-primary waves-effect waves-light" type="button" id="btn_ver_generados">Ver Accrued Generados</button>
                                        </a>
                                        <button class="btn btn-success waves-effect waves-light" type="button" id="btn_procesar_accrued">Procesar Accrued</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- FIN - Tab ACC Preview -->
                </div>
                <!-- FIN - TAB -->
            </div>
        </div>
    </div>
    <!-- FIN - Panel Formularios y Grilla  -->

    <!-- INICIO - Includes -->
    <?php require '../includes/footer_start.php' ?>
    <?php require '../includes/footer_notificaciones.php' ?>
    <?php require '../includes/footer_datatable.php' ?>
    <?php require '../includes/footer_validacion.php' ?>
    <script src="accrued.js?random=<?php echo uniqid(); ?>"></script>
    <script src="../plugins/notifyjs/dist/notify.min.js"></script>
    <script src="../plugins/notifications/notify-metro.js"></script>
    <script src="../assets/js/jquery.number.min.js"></script>
    <?php require '../includes/footer_end.php' ?>
    <!-- FIN - Includes -->
<?php } ?>