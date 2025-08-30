<?php require '../includes/header_start.php';

    $cliente_id = isset($_GET["cliente_id"])?$_GET["cliente_id"]:'';

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
    
    <link href="cliente.css" rel="stylesheet" type="text/css">

    <!-- INICIO - Panel Formularios y Grilla  -->
    <div class="col-md-12">
        <div class="col-md-5">
            <div class="form-group">
                <input type="hidden" id="cliente_id" value="<?=$cliente_id?>">
                <label class="control-label" for="productos">Productos disponibles</label>
                <select name="productos" class="form-control" id="productos" size="10"></select>
            </div>
        </div>
        <div class="col-md-1">
            <div class="form-group">
               <div class="col-md-1">
                    <a href='javascript:void(0)'> <i onclick='cliente_productos_insertar()' class='fa fa-arrow-circle-right ' style="margin-top: 100px;"></i></a>
                    <a href='javascript:void(0)'> <i onclick='cliente_productos_eliminar()' class='fa fa-arrow-circle-left ' style="margin-top: 20px;"></i></a>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label class="control-label" for="cliente_productos">Productos asignados</label>
                <select name="cliente_productos" class="form-control" id="cliente_productos" size="10"></select>
            </div>
        </div>
    </div>
    <!-- FIN - Panel Formularios y Grilla  -->

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
    <script src="cliente_producto.js"></script> 

</body>
</html>