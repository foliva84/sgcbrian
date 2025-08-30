<?php require '../seguridad/seguridad.php'; ?> 
<?php $pagina = "Permisos para el Rol"; 
include_once '../includes/herramientas.php';?>
<?php require '../includes/header_start.php'; ?>
<?php require '../includes/header_datatables.php'; ?>
<?php require '../includes/header_end.php'; ?>
<link href="rol.css" rel="stylesheet" type="text/css">
<?php require '../includes/header_pagina.php'; ?>
<link href="../plugins/switchery/switchery.min.css" rel="stylesheet" />
<!-- Inicio - Panel Formularios y Grilla  -->
 <div class="panel panel-default users-content">
     
    <?php
    
    $rol_id = $_GET["rol_id"];
    
    $rol = Rol::buscarPorid($rol_id);
    
    $rol_nombre = $rol["rol_nombre"]; 
    
    ?>
     
    <div class="panel-heading">
        <div class="form-group">
            <label for="rol_nombre">Permisos para el Rol:</label>
            <input type="text" name="rol_nombre" value="<?=$rol_nombre?>" class="form-control" id="rol_nombre">
        </div>
    </div>
     
    <?php
          
    // Acá tengo que tomar el rol
    $permisos = Permiso::listar_permisos_rol($rol_id);
    
    $grilla = "<div class='row'>";
    $grilla .= "<div id='grilla_abm' class='col-sm-12'>";
    $grilla .=  "<div class='card-box table-responsive'>";
    $grilla .=      "<h4 class='m-t-0 header-title'><b>Tabla de usuarios</b></h4>";
    $grilla .=      "<table id='dt_rolPremisos' class='table table-hover m-0 table-responsive'>";
    $grilla .=          "<thead>";
    $grilla .=              "<tr>";
    $grilla .=                  "<th>Permiso</th>";
    $grilla .=                  "<th>Activo</th>";
    $grilla .=              "</tr>";
    $grilla .=          "</thead>";
    $grilla .=          "<tbody>";  
    foreach($permisos as $permiso){
            $permiso_id = $permiso["permiso_id"];
            $permiso_nombre = $permiso["permiso_nombre"];
            $permiso_tiene_rol = $permiso["rol_id"];
    $grilla .=              "<tr>";
    $grilla .=                  "<td>";
    $grilla .=                      $permiso_nombre;
    $grilla .=                  "</td>";
    $grilla .=                  "<td>";
    if ($permiso_tiene_rol){
         $grilla .=  "<a href='javascript:void(0)'> <i onclick='permiso_modifica($permiso_id, $rol_id)'> <input type='checkbox' id='$permiso_id' checked data-plugin='switchery' data-color='#3bafda' data-size='small'/></a>";
    }else{
         $grilla .=  "<a href='javascript:void(0)'> <i onclick='permiso_modifica($permiso_id, $rol_id)'> <input type='checkbox' id='$permiso_id' data-plugin='switchery' data-color='#3bafda' data-size='small'/></a>";
    }
    $grilla .=                  "</td>";
    $grilla .=              "</tr>";
    }
    $grilla .=          "</tbody>";
    $grilla .=       "</table>";
    $grilla .=    "</div>";
    $grilla .=  "</div>";
    $grilla .="</div>";   
    echo $grilla;
    ?>
        
 </div>
<!-- Fin - Panel Formularios y Grilla  -->






<?php require '../includes/footer_start.php' ?>
<?php require '../includes/footer_notificaciones.php' ?>
<?php require '../includes/footer_datatable.php' ?>
<?php require '../includes/footer_validacion.php' ?>
<script src="rol_permisos.js"></script> 
<?php require '../includes/footer_end.php' ?>