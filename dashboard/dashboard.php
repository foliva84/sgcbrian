<?php $pagina = "Dashboard"; ?>
<?php require '../seguridad/seguridad.php'; ?>
<?php require '../includes/header_start.php'; ?>
<?php require '../includes/header_datatables.php'; ?>
<?php require '../includes/header_end.php'; ?>
<link href="../assets/css/iconos_propios.css" rel="stylesheet" type="text/css">
<?php require '../includes/header_pagina.php'; ?>

<!-- INICIO - Bienvenida -->
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">
                    Bienvenid@
                    <span class="text-success counter"><?php echo $sesion_usuario_nombre . ' ' . $sesion_usuario_apellido ?></span>
                    al Sistema!
                </h4>
            </div>
        </div>
    </div>
</div>
<!-- FIN - Bienvenida -->

<!-- INICIO - Estadisticas -->
<div class="container">
    <div class="row"> 
        <!-- INICIO - CASOS -->
        <?php if (Usuario::puede("casos_ver") == 1) { ?>
            <div class="col-lg-2">
                <div class="widget-simple-chart text-center card-box">
                    <h3 class="text-purple counter" id="c_casos_totales"></h3>
                    <p class="text-muted text-nowrap">Casos Totales</p>
                </div>

                <div class="widget-simple-chart text-center card-box">
                    <h3 class="text-pink counter" id="c_casos_cerrados"></h3>
                    <p class="text-muted text-nowrap">Casos Cerrados</p>
                </div>
            </div>

            <div class="col-lg-2">
                <div class="widget-simple-chart text-center card-box">
                    <h3 class="text-warning counter" id="c_casos_simples_abiertos"></h3>
                    <p class="text-muted text-nowrap">Casos Simples en Curso</p>
                </div>

                <div class="widget-simple-chart text-center card-box">
                    <h3 class="text-warning counter" id="c_casos_complejos_abiertos"></h3>
                    <p class="text-muted text-nowrap">Casos Complejos en Curso</p>
                </div>

            </div>
        
            <div id="grilla_casos"></div>
            
        <?php } ?>
        <!-- FIN - CASOS -->            

        <!-- INICIO - FACTURAS -->
        <?php if (Usuario::puede("facturas_ver_TOFIX") == 1) { ?>
            <div class="col-lg-4">
                <div class="card-box">
                <h4 class="text-dark  header-title m-t-0">Totales Facturas (USD)</h4>
                    <div class="widget-chart text-center">
                        <ul class="list-inline m-t-15 mb-0">
                            <li>
                                <h5 class="text-muted m-t-20">Total Ingresado</h5>
                                <h4 class="m-b-0" id="t_facturas_total_ingresado"></h4>
                            </li>
                            <li>
                                <h5 class="text-muted m-t-20">Pendiente Pagar</h5>
                                <h4 class="m-b-0" id="t_facturas_pendiente_pagar"></h4>
                            </li>
                            <li>
                                <h5 class="text-muted m-t-20">Pagado</h5>
                                <h4 class="m-b-0" id="t_facturas_importe_pagado"></h4>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="widget-simple-chart text-center card-box">
                    <h3 class="text-purple counter" id="c_facturas_total_ingresado"></h3>
                    <p class="text-muted text-nowrap">Facturas Totales</p>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="widget-simple-chart text-center card-box">
                    <h3 class="text-purple counter" id="c_facturas_pendiente_auditar"></h3>
                    <p class="text-muted text-nowrap">Facturas Pendientes Auditar</p>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="widget-simple-chart text-center card-box">
                    <h3 class="text-pink counter" id="c_facturas_pendiente_pagar"></h3>
                    <p class="text-muted text-nowrap">Facturas Pendientes de Pago</p>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="widget-simple-chart text-center card-box">
                    <h3 class="text-danger counter" id="c_facturas_importe_pagado"></h3>
                    <p class="text-muted text-nowrap">Facturas Pagadas</p>
                </div>
            </div>
        <?php } ?>
        <!-- FIN - FACTURAS -->
    </div>
</div>
<!-- FIN - Estadisticas -->

<?php require '../includes/footer_start.php' ?>
<?php require '../includes/footer_notificaciones.php' ?>
<?php require '../includes/footer_datatable.php' ?>
<?php require '../includes/footer_validacion.php' ?>
<script src="dashboard.js?random=<?php echo uniqid(); ?>"></script>
<?php require '../includes/footer_end.php' ?>