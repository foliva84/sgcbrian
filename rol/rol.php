<?php require '../seguridad/seguridad.php'; ?> 
<?php $pagina = "Rol"; ?>
<?php require '../includes/header_start.php'; ?>
<?php require '../includes/header_datatables.php'; ?>
<?php require '../includes/header_end.php'; ?>
<link href="rol.css" rel="stylesheet" type="text/css">
<?php require '../includes/header_pagina.php'; ?>

<!-- Inicio - Panel Formularios y Grilla  -->
 <div class="panel panel-default users-content">
    <div class="panel-heading">
        <button onclick="javascript:agrega_rol_formulario();" class="btn btn-primary waves-effect waves-light m-l-5"> Agregar Rol <i class="glyphicon glyphicon-plus" ></i></button></div>
        
        <!-- Inicio - Panel Formulario Alta  -->
        <div class="panel-body hidden" id="panel_formulario_alta">

            <div class="row">
                <div class="col-lg-6">
                    <div class="card-box">
                        <h4 class="m-t-0 header-title"><b>Formulario Alta de Rol</b></h4>
                        <p class="text-muted font-13 m-b-30">Complete el formulario para dar de alta el rol.</p>
                        <form  id="formulario_alta" name="formulario_alta">
                            <!-- Acción del formulario en opcion  -->
                            <input type="hidden" id="opcion" name="opcion" value="formulario_alta">
                            <div class="form-group">
                                <label for="rol_nombre_n">Nombre del rol*</label>
                                <input type="text" name="rol_nombre_n" placeholder="" class="form-control" id="rol_nombre_n">
                            </div>
                            <div class="form-group">
                                <label for="rol_orden_n">Orden Listados*</label><span class="text-muted font-13 m-b-30"> (orden que tendrán en los listados de selección)</span>
                                <input type="text" name="rol_orden_n"  placeholder="" class="form-control" style="width: 50px;" id="rol_orden_n">
                            </div>
                            <div class="form-group">
                                <label for="rol_jerarquia_n">Jerarquía del Rol*</label><span class="text-muted font-13 m-b-30"> (que valor de jerarquía tiene respecto de otros roles)</span>
                                <input name="rol_jerarquia_n" type="text" placeholder="" class="form-control" style="width: 50px;" id="rol_jerarquia_n">
                            </div>
                            <div class="form-group text-right m-b-0">
                                <button class="btn btn-primary waves-effect waves-light" type="submit">
                                    Guardar
                                </button>
                                <button type="reset" id="btn_cancelar_nuevo" class="btn btn-default waves-effect waves-light m-l-5">
                                    Cancelar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>   
        <!-- Fin - Panel Formulario Alta  -->
    
        <!-- Inicio - Panel Formulario Modificacion  -->
        <div class="panel-body hidden" id="panel_formulario_modificacion">

            <div class="row">
                <div class="col-lg-6">
                    <div class="card-box">
                        <h4 class="m-t-0 header-title"><b>Formulario Modificación de Rol</b></h4>
                        <p class="text-muted font-13 m-b-30">
                            Modifique el rol.
                        </p>
                        <form  id="formulario_modificacion" name="formulario_modificacion">
                            <!-- Acción del formulario en opcion y id del usuario a modificar -->
                            <input type="hidden" id="rol_id" name="rol_id" value="0">
                            <input type="hidden" id="opcion" name="opcion" value="formulario_modificacion">
                            <div class="form-group">
                                <label for="rol_nombre">Nombre del rol*</label>
                                <input type="text" name="rol_nombre" placeholder="" class="form-control" id="rol_nombre">
                            </div>
                            <div class="form-group">
                                <label for="rol_orden">Orden Listados*</label>
                                <input type="text" name="rol_orden"  placeholder="" class="form-control" style="width: 50px;" id="rol_orden">
                            </div>
                            <div class="form-group">
                                <label for="rol_jerarquia">Jerarquía del Rol*</label>
                                <input  name="rol_jerarquia" type="text" placeholder="" class="form-control" style="width: 50px;" id="rol_jerarquia">
                            </div>
                            <div class="form-group text-right m-b-0">
                                <button class="btn btn-primary waves-effect waves-light" type="submit">
                                    Guardar
                                </button>
                                <button type="reset" id="btn_cancelar" class="btn btn-default waves-effect waves-light m-l-5">
                                    Cancelar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div> 
        <!-- Fin - Panel Formulario Modificacion  -->
    
    
        <div class="panel-body" id="panel_grilla">        
            <div id="grilla_rol"></div>      
        </div>        
 </div>
<!-- Fin - Panel Formularios y Grilla  -->

<!-- Inicio - Ventana modal para borrado.-->
<form id="formulario_baja" method="POST" action="">
    <div id="ventana_modal_borrado" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
        <div class="modal-dialog">
            <div class="modal-content">    
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel"><i class='fa fa-trash fa-lg'></i>&nbsp;&nbsp;Deshabilita usuario</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="usuario_id_b" name="usuario_id_b" value="0" />
                    <h4>¿Está seguro que desea deshabilitar a este usuario?</h4>
                    <hr>
                    <h4>Nota:</h4>
                    <p>Los registros nunca son eliminados completamente, los mismos son desactivados.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">No</button>
                    <button type="submit" class="btn btn-primary waves-effect waves-light" >Sí eliminar</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</form> 
<!-- Fin - Ventana modal para borrado.-->

<!-- Inicio - Ventana modal para borrado.-->
<form id="formulario_habilita" method="POST" action="">
    <div id="ventana_modal_habilita" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
        <div class="modal-dialog">
            <div class="modal-content">    
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel"><i class='fa fa-trash fa-lg'></i>&nbsp;&nbsp;Volver a habilitar al usuario</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="usuario_id_a" name="usuario_id_a" value="0" />
                    <h4>¿Está seguro que desea volver a habilitar a este usuario?</h4>
                    <hr>
                    <h4>Nota:</h4>
                    <p>Bla.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">No</button>
                    <button type="submit" class="btn btn-primary waves-effect waves-light" >Sí habilitar</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</form> 
<!-- Fin - Ventana modal para borrado.-->

<?php require '../includes/footer_start.php' ?>
<?php require '../includes/footer_notificaciones.php' ?>
<?php require '../includes/footer_datatable.php' ?>
<?php require '../includes/footer_validacion.php' ?>
<script src="rol.js"></script> 
<?php require '../includes/footer_end.php' ?>