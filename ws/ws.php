<?php $pagina = "Voucher"; ?>
<?php require '../includes/header_start.php'; ?>
<?php require '../includes/header_datatables.php'; ?>
<?php require '../includes/header_end.php'; ?>
<link href="ws.css" rel="stylesheet" type="text/css">
<?php require '../includes/header_pagina.php'; ?>

        
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

<?php require '../includes/modal_activaDesactiva.php' ?>
<?php require '../includes/footer_start.php' ?>
<?php require '../includes/footer_notificaciones.php' ?>
<?php require '../includes/footer_datatable.php' ?>
<?php require '../includes/footer_validacion.php' ?>

<script src="ws.js"></script> 
<?php require '../includes/footer_end.php' ?>