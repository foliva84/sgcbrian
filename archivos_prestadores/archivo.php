<?php 
    require '../seguridad/seguridad.php';

    $archivos_prestadores_ver = array_search('archivos_prestadores_ver', array_column($permisos, 'permiso_variable'));
    if (empty($archivos_prestadores_ver) && ($archivos_prestadores_ver !== 0)) {
        header("location:../_errores/401.php");
    } else {

    $pagina = "Archivo"; 
    $prestador_id_archivo = isset($_GET["prestador_id"])?$_GET["prestador_id"]:'';
    $prestador_vista = isset($_GET["prestador_vista"])?$_GET["prestador_vista"]:'';
    
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

    
    <!-- INICIO - Panel Formulario Alta -->
    <form id="upload_form" enctype="multipart/form-data" method="post">
        <input type=hidden id="prestador_id_archivo" name="prestador_id_archivo" value="<?=$prestador_id_archivo?>">
        <?php if (empty($prestador_vista) || (!($prestador_vista == 1))) { ?>
            <?php
            $archivos_prestadores_alta = array_search('archivos_prestadores_alta', array_column($permisos, 'permiso_variable'));
            if (!empty($archivos_prestadores_alta) || ($archivos_prestadores_alta === 0)) { 
            ?>
            <div class="col-lg-7 col-sm-6 col-12">
                <div class="input-group">
                    <label class="input-group-btn">
                        <span class="btn btn-primary" style="width: 100px;">
                            Examinar&hellip; <input type="file" name="upfile" id="upfile" style="display: none;">
                        </span>
                    </label>
                    <input type="text" class="form-control" style="width:400px;" readonly>            
                    <button class="btn btn-default" type="button" style="width: 100px;" onclick="uploadFile()">Guardar</button>
                </div>
                <progress id="progressBar" value="0" max="100" style="width:600px;"></progress>
                <p id="loaded_n_total"></p>
                <p id="status"></p>
            </div>
            <?php } ?>
        <?php } ?>
    </form>
    <!-- FIN - Panel Formulario Alta -->
    

    <div id="grilla_archivos"></div>     


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
    
    <?php require '../includes/modal_archivo.php' ?>
    <?php require '../includes/footer_notificaciones.php' ?>
    <?php require '../includes/footer_datatable.php' ?>
    <?php require '../includes/footer_validacion.php' ?>

    <script src="archivo.js"></script> 
    </body>
    </html>
<?php } ?>