<?php require '../includes/header_start.php';

    $prestador_id = isset($_GET["prestador_id"])?$_GET["prestador_id"]:'';
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

    <link href="prestador.css" rel="stylesheet" type="text/css">

    <!-- INICIO - Panel Formularios y Grilla  -->
    <div class="col-md-12">
    <?php if (empty($prestador_vista) || (!($prestador_vista == 1))) { ?>
        <div class="col-md-5">
            <div class="form-group">
                <label class="control-label" for="practicas">Prácticas disponibles</label>
                <select name="practicas" class="form-control" id="practicas" size="10"></select>
            </div>
        </div>
        <div class="col-md-1">
            <div class="form-group">
               <div class="col-md-1">
                    <a href='javascript:void(0)'> <i onclick='ShowModalPractices()' class='fa fa-arrow-circle-right ' style="margin-top: 100px;"></i></a>
                    <a href='javascript:void(0)'> <i onclick='practicas_prestador_eliminar()' class='fa fa-arrow-circle-left ' style="margin-top: 20px;"></i></a>
                </div>
            </div>
        </div>
    <?php } ?>
        <div class="col-md-5">
            <div class="form-group">
                <input type="hidden" id="prestador_id" value="<?= $prestador_id ?>">
                       
                <label class="control-label" for="practicas_prestador">Prácticas asignadas</label>
                <select name="practicas_prestador" class="form-control" id="practicas_prestador" size="10"></select>
            </div>
        </div>
    </div>
    <!-- FIN - Panel Formularios y Grilla  -->


    <!-- Button trigger modal -->


    <!-- Modal practices presunto origen-->
    <div class="modal fade" id="practicesModal" tabindex="-1" role="dialog" aria-labelledby="practicesModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="practicesModalLabel"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <label for="presunto_origen_practica_prestador">Presunto Origen</label>
            <input type="text" id="presunto_origen_practica_prestador" value="">
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary" onclick='setPresuntoOrigen()'>Insertar Práctica</button>
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
    <!-- Plugin para Fechas -->


    <!-- peity charts -->
    <script src="../plugins/peity/jquery.peity.min.js"></script>
    <script src="../assets/js/jquery.core.js"></script>
    <script src="../assets/js/jquery.app.js"></script>

    <script src="../assets/js/datePickerES.js"></script>
    <?php require '../includes/footer_notificaciones.php' ?>

    <?php require '../includes/footer_validacion.php' ?>
    <script src="prestador_practica.js"></script>
    
</body>
</html>