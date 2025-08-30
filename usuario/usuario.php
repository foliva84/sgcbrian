<?php require '../seguridad/seguridad.php'; ?> 
<?php $pagina = "Usuario"; ?>
<?php require '../includes/header_start.php'; ?>
<?php require '../includes/header_datatables.php'; ?>
<?php require '../includes/header_end.php'; ?>
<link href="usuario.css" rel="stylesheet" type="text/css">
<?php require '../includes/header_pagina.php'; ?>

<!-- Inicio - Panel Formularios y Grilla  -->
 <div class="panel panel-default users-content">
    <div class="panel-heading">
        <button onclick="javascript:agrega_usuario_formulario();" class="btn btn-primary waves-effect waves-light m-l-5"> Agregar Usuario <i class="glyphicon glyphicon-plus" ></i></button></div>
        <!-- Inicio - Panel Formulario Alta  -->
        <div class="panel-body hidden" id="panel_formulario_alta">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card-box">
                        <h4 class="m-t-0 header-title"><b>Formulario Alta de Usuario</b></h4>
                        <p class="text-muted font-13 m-b-30">
                            Complete el formulario para dar de alta el usuario
                        </p>
                        <form  id="formulario_alta" name="formulario_alta">
                            <!-- Acción del formulario en opcion  -->
                            <input type="hidden" id="opcion" name="opcion" value="formulario_alta">
                            <div class="form-group">
                                <label for="usuario_nombre_n">Nombre*</label>
                                <input type="text" name="usuario_nombre_n" class="form-control" id="usuario_nombre_n">
                            </div>
                            <div class="form-group">
                                <label for="usuario_apellido_n">Apellido*</label>
                                <input type="text" name="usuario_apellido_n" class="form-control" id="usuario_apellido_n">
                            </div>
                            <div class="form-group">
                                <label for="usuario_usuario_n">Usuario*</label>
                                <input type="text" name="usuario_usuario_n" class="form-control" id="usuario_usuario_n">
                            </div>
                            <div class="form-group">
                                <label for="usuario_rol_n">Rol*</label>
                                <select name="usuario_rol_id_n" class="form-control select2" id="usuario_rol_id_n"></select>
                            </div>
                            <div class="form-group">
                                <label for="usuario_password_n">Contraseña*</label>
                                <input  type="password" placeholder="Password" name="usuario_password_n"  class="form-control" id="usuario_password_n">
                            </div>
                            <div class="form-group">
                                <label for="usuario_password_n_c">Confirma Contraseña*</label>
                                <input  type="password" placeholder="Password" name="usuario_password_n_c"  class="form-control" id="usuario_password_n_c">
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
                        <h4 class="m-t-0 header-title"><b>Formulario Modificación de Usuario</b></h4>
                        <p class="text-muted font-13 m-b-30">
                            Modifique el usuario.
                        </p>
                        <form  id="formulario_modificacion" name="formulario_modificacion">
                            <!-- Acción del formulario en opcion y id del usuario a modificar -->
                            <input type="hidden" id="usuario_id" name="usuario_id" value="0">
                            <input type="hidden" id="opcion" name="opcion" value="formulario_modificacion">
                            <div class="form-group">
                                <label for="usuario_nombre">Nombre*</label>
                                <input type="text" name="usuario_nombre" placeholder="" class="form-control" id="usuario_nombre">
                            </div>
                            <div class="form-group">
                                <label for="usuario_apellido">Apellido*</label>
                                <input type="text" name="usuario_apellido"  placeholder="" class="form-control" id="usuario_apellido">
                            </div>
                            <div class="form-group">
                                <label for="usuario_usuario">Usuario*</label>
                                <input id="usuario_usuario" name="usuario_usuario" type="text" placeholder="" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="usuario_rol_id">Rol*</label>
                                <select name="usuario_rol_id"  class="form-control" id="usuario_rol_id"></select>
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
            <div id="grilla_usuario"></div>      
        </div>        
 </div>
<!-- Fin - Panel Formularios y Grilla  -->

<?php require '../includes/modal_activaDesactiva.php' ?>
<?php require '../includes/footer_start.php' ?>
<?php require '../includes/footer_notificaciones.php' ?>
<?php require '../includes/footer_datatable.php' ?>
<?php require '../includes/footer_validacion.php' ?>
<script src="usuario.js?random=<?php echo uniqid(); ?>"></script>
<?php require '../includes/footer_end.php'?>