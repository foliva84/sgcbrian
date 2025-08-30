<?php $pagina = "Cliente"; ?>
<?php require '../includes/header_start.php'; ?>
<?php require '../includes/header_datatables.php'; ?>
<?php require '../includes/header_end.php'; ?>
<link href="cliente.css" rel="stylesheet" type="text/css">
<?php require '../includes/header_pagina.php'; ?>


<!-- INICIO - Grilla  -->
        <div class="panel-body" id="panel_grilla">     
            <div class="row">
                <div class="form-group col-md-4">
                    <input type="text"  name="cliente_nombre_buscar" class="form-control"  id="cliente_nombre_buscar" placeholder="Buscar por nombre" >
                </div>
                <div class="form-group col-md-4">
                    <select name="cliente_pais_id_b" class="form-control" id="cliente_pais_id_b"></select>
                </div>
                <div class="form-group col-md-1">
                    <a href='javascript:void(0)'> <i onclick='cliente_listar_filtrado()' class='fa fa-search' style="margin-top: 13px;"></i></a>
                </div>
               
            </div>
            <div id="grilla_cliente"></div>      
        </div>  
<!-- FIN - Grilla  -->



<?php require '../includes/modal_activaDesactiva_cliente.php' ?>
<?php require '../includes/footer_start.php' ?>
<?php require '../includes/footer_notificaciones.php' ?>
<?php require '../includes/footer_datatable.php' ?>
<?php require '../includes/footer_validacion.php' ?>



<script>
//Va a buscar los datos de la grilla
var cliente_listar_filtrado = function(){
    
    let cliente_nombre = $("#cliente_nombre_buscar").val();
    let cliente_pais_id = $("#cliente_pais_id_b").val();  // arreglar esto
   
    $.Notification.autoHideNotify('error', 'top center', 'Acá llegamos.', cliente_nombre);
    
    if (cliente_nombre === '' && cliente_pais_id === ''){
        $.Notification.autoHideNotify('error', 'top center', 'Campos incompletos.', 'Debe completar los campos para la búsqueda.');
        return;
    } 
        
    var parametros = {
        "cliente_nombre_buscar": cliente_nombre,
        "cliente_pais_id_buscar": cliente_pais_id,
        "opcion": 'cliente_listar_filtrado'
    };
    $.ajax({
            dataType: "html",
            method: "POST",
            url: "cliente_cb.php",
            data: parametros,
            success: function ( resultado ) {
                $.Notification.autoHideNotify('sucsess', 'top right', 'Estoy refrescando la grilla...', 'Estoy refrescando la grilla... ');
                $("#grilla_cliente").html(resultado);
                listar(); 
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $.Notification.autoHideNotify('error', 'top right', 'Error...', 'Reporte del error. ' + xhr.responseText);
            } 
           
    }); 
           
};

</script>
<?php require '../includes/footer_end.php' ?>