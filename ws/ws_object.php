<?php 
    $pagina = "Comunicacion"; 
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
<link href="ws.css" rel="stylesheet" type="text/css">

<!-- Inicio - Panel Formularios y Grilla  -->   
<div class="row">
    <div class="col-lg-6">
        <div class="card-box">
            <h4 class="m-t-0 header-title"><b>Buscar Voucher</b></h4>
                <div class="form-group">
                    <label for="buscar_voucher">Número de voucher</label>
                    <input type="text" name="buscar_voucher" class="form-control" id="buscar_voucher">
                </div> 
                <div class="form-group">
                    <label for="sistema_emision">Sistema de emisión</label>
                    <select name="sistema_emision" class="form-control" id="sistema_emision">
                        <option value="1">SEC</option>
                        <option value="2">Assist 1</option>
                    </select>
                </div>
                <div class="form-group text-right m-b-0">
                    <button onclick="javascript:grilla_listar();" class="btn btn-primary waves-effect waves-light m-l-5"> Buscar <i class="glyphicon glyphicon-search" ></i></button>
                </div>        
        </div>
    </div>
</div>    
        
 <div class="panel panel-default users-content">
    <div class="panel-body" id="panel_formulario_voucher">        
        <div id="formulario_voucher"></div>      
    </div> 

    <div class="panel-body" id="panel_grilla_producto">        
        <div id="grilla_producto"></div>      
    </div> 

    <div class="panel-body" id="panel_grilla">        
        <div id="grilla_voucher"></div>      
    </div>        
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


<script src="ws.js"></script> 
</body>
</html>