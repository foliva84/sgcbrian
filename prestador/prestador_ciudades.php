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



    <div class="col-md-12">
    
    <?php //echo("vista = " . $prestador_vista);?>
    
    <?php if (empty($prestador_vista) || (!($prestador_vista == 1))) { ?>
        <div class="col-md-5">
            <div class="form-group">
                <label class="control-label" for="paises">Países</label>
                <input name="paises" class="form-control" id="paises" placeholder="Coloque primeras 2 letras del Pais...">
                <input type="hidden" name="pais_id" id="pais_id">
            </div>
        </div>
        <div class="col-md-1">
            <div class="form-group">
               <div class="col-md-1">
                    <a href='javascript:void(0)'> <i onclick='paises_prestador_insertar()' class='fa fa-arrow-circle-right ' style="margin-top: 100px;"></i></a>
                    <a href='javascript:void(0)'> <i onclick='paises_prestador_eliminar()' class='fa fa-arrow-circle-left ' style="margin-top: 20px;"></i></a>
                </div>
            </div>
        </div>
    <?php } ?>
        <div class="col-md-5">
            <div class="form-group">
                <input type="hidden" id="prestador_id" value="<?= $prestador_id ?>"

                <label class="control-label" for="paises_prestador">Países Prestador</label>
                <select name="paises_prestador" class="form-control" id="paises_prestador" size="10"></select>
            </div>
        </div>
    </div>

    <div class="col-md-12">
    <?php if (empty($prestador_vista) || (!($prestador_vista == 1))) { ?>
        <div class="col-md-5">
            <div class="form-group">
                <label class="control-label" for="paises_p_ciudades">Pais</label>
                <input name="paises_p_ciudades" class="form-control" id="paises_p_ciudades" size="10" placeholder="Seleccione un Pais para la Ciudad...">
                <input type="hidden" name="paises_p_ciudades_id" id="paises_p_ciudades_id">
            </div>
            <div class="form-group">
                <label class="control-label" for="ciudades">Ciudades</label>
                <input name="ciudades" class="form-control" id="ciudades" size="10">
                <input type="hidden" name="ciudad_id" id="ciudad_id">
            </div>
        </div>
        <div class="col-md-1">
            <div class="form-group">
               <div class="col-md-1">
                    <a href='javascript:void(0)'> <i onclick='ciudades_prestador_insertar()' class='fa fa-arrow-circle-right ' style="margin-top: 100px;"></i></a>
                    <a href='javascript:void(0)'> <i onclick='ciudades_prestador_eliminar()' class='fa fa-arrow-circle-left ' style="margin-top: 20px;"></i></a>
                </div>
            </div>
        </div>
    <?php } ?>
        <div class="col-md-5">
            <div class="form-group">
                <label class="control-label" for="ciudades_prestador">Ciudades Prestador</label>
                <select name="ciudades_prestador" class="form-control" id="ciudades_prestador" size="10"></select>
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
<script src="prestador_ciudades.js"></script> 

</body>
</html>