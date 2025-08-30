<?php 
    require '../seguridad/seguridad.php';
    
    // Validacion de permisos
    $clientes_ver = array_search('clientes_ver', array_column($permisos, 'permiso_variable'));
    if (empty($clientes_ver) && ($clientes_ver !== 0)) {
        header("location:../_errores/401.php");
    } else {
?>
    <?php $pagina = "Cliente"; ?>
    <?php require '../includes/header_start.php'; ?>
    <?php require '../includes/header_datatables.php'; ?>
    <?php require '../includes/header_end.php'; ?>
    <link href="cliente.css" rel="stylesheet" type="text/css">
    <?php require '../includes/header_pagina.php'; ?>
    <link href="../assets/js/jquery-ui.min.css" rel="stylesheet" type="text/css"/>

    <!-- INICIO - Panel Formularios y Grilla  -->
    <div class="panel panel-default users-content">
        <div class="panel-heading">
            <?php
                $clientes_alta = array_search('clientes_alta', array_column($permisos, 'permiso_variable'));
                if (!empty($clientes_alta) || ($clientes_alta === 0)) { 
            ?>            
            <button onclick="javascript:agrega_cliente_formulario();" class="btn btn-primary waves-effect waves-light m-l-5"> Agregar cliente <i class="glyphicon glyphicon-plus" ></i></button>
            <?php } ?>
        </div>
            <!-- INICIO - Formulario Alta -->
            <div class="panel-body hidden" id="panel_formulario_alta">
                <div class="row">
                    <form  id="formulario_alta" name="formulario_alta">
                        <!-- Acción del formulario en opcion  -->
                        <input type="hidden" id="opcion" name="opcion" value="formulario_alta">
                        <div class="col-sm-12">
                            <h4 class="m-t-0 header-title"><b>Formulario Alta de Cliente</b></h4>
                            <p class="text-muted font-13 m-b-30"></p>   

                            <div class="col-sm-6">                         
                                <div class="card-box">
                                    <h4 class="m-t-0 m-b-20 header-title"><b>Datos del Cliente</b></h4>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group has-warning">
                                                <label class="control-label" for="cliente_nombre_n">Nombre</label>
                                                <input type="text" name="cliente_nombre_n" class="form-control" id="cliente_nombre_n" maxlength="60">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group has-warning">
                                                <label class="control-label" for="cliente_razonSocial_n">Razón social</label>
                                                <input type="text" name="cliente_razonSocial_n" class="form-control" id="cliente_razonSocial_n" maxlength="60">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group has-warning">
                                                <label class="control-label" for="cliente_abreviatura_n">Abreviatura</label>
                                                <input type="text" name="cliente_abreviatura_n" class="form-control" id="cliente_abreviatura_n" maxlength="10">
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="form-group has-warning">
                                                <label class="control-label" for="cliente_tipoCliente_id_n">Tipo de cliente</label>
                                                <select name="cliente_tipoCliente_id_n" class="form-control" id="cliente_tipoCliente_id_n"></select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">                         
                                <div class="card-box">
                                    <h4 class="m-t-0 m-b-20 header-title"><b>Datos de Ubicacion</b></h4>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group has-warning">
                                                <label class="control-label" for="cliente_pais_id_n">País</label>
                                                <select name="cliente_pais_id_n" class="form-control" id="cliente_pais_id_n"></select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group has-warning">
                                                <label class="control-label" for="cliente_ciudad_id_n">Ciudad</label>

                                                <input name="cliente_ciudad_id_n" class="form-control" id="cliente_ciudad_id_n" placeholder="Seleccione el País e ingrese las primeras 3 letras de la ciudad"></input>
                                                <input type="hidden" name="cliente_ciudad_id_n_2" class="form-control" id="cliente_ciudad_id_n_2"></input>
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <label class="control-label" for="cliente_direccion_n">Dirección</label>
                                                <input type="text" name="cliente_direccion_n" class="form-control" id="cliente_direccion_n" maxlength="60">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label" for="cliente_codigoPostal_n">Código Postal</label>
                                                <input type="text" name="cliente_codigoPostal_n" class="form-control" id="cliente_codigoPostal_n" maxlength="10">
                                            </div>
                                        </div>                         
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12">                         
                                <div class="card-box">
                                    <h4 class="m-t-0 m-b-20 header-title"><b>Datos de Contacto</b></h4>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="control-label" for="grilla_telefonos">Teléfono</label>
                                            <div class="row">
                                                <div class="form-group col-md-3">
                                                    <select class="form-control" name="telefonoTipo_id_n" id="telefonoTipo_id_n"></select>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <input type="text" name="telefono_numero_n" class="form-control"  id="telefono_numero_n" placeholder="Ingrese el número" maxlength="22">
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <div class="checkbox checkbox-primary">
                                                        <input name="telefono_principal_n" id="telefono_principal_n" type="checkbox">
                                                        <label for="telefono_principal_n">Principal</label>
                                                    </div>
                                                </div>
                                            </div>      
                                        </div>
                                        <div class="col-md-6">
                                            <label class="control-label" for="grilla_emails">E-mail</label>
                                            <div class="row">
                                                <div class="form-group col-md-3">
                                                    <select class="form-control" name="emailTipo_id_n" id="emailTipo_id_n"></select>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <input type="email" name="email_email_n" class="form-control"  id="email_email_n" placeholder="Ingrese el email" maxlength="40">
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="email_principal_n" name="email_principal_n" type="checkbox">
                                                        <label for="email_principal_n">Principal</label>
                                                    </div>
                                                </div>
                                            </div> 
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label" for="cliente_paginaWeb_n">Página WEB</label>
                                                <input type="text" name="cliente_paginaWeb_n" class="form-control" id="cliente_paginaWeb_n" maxlength="40">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12"> 
                                <div class="card-box">
                                    <h4 class="m-t-0 m-b-20 header-title"><b>Observaciones</b></h4>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label" for="cliente_observaciones_n">Observaciones</label>
                                                <textarea name="cliente_observaciones_n" class="form-control" id="cliente_observaciones_n" rows="3" maxlength="200"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12"> 
                                <div class="card-box">                        
                                    <div class="form-group text-right m-b-0">
                                        <button type="reset" id="btn_cancelar_nuevo" class="btn btn-default waves-effect waves-light m-l-5">Cancelar</button>
                                        <button id="btn_guardar_nuevo" class="btn btn-primary waves-effect waves-light" type="submit">Guardar</button>
                                    </div>
                                </div>
                            </div>    
                        </div>                            
                    </form>
                </div>
            </div>       
            <!-- FIN - Formulario Alta -->

             <!-- INICIO - Formulario Vista -->
             <div class="panel-body hidden" id="panel_formulario_ver">
                <div class="row">
                        <!-- Acción del formulario en opcion y id del cliente a modificar -->
                        <input type="hidden" id="cliente_id_v" name="cliente_id_v" value="0">
                        <input type="hidden" id="opcion" name="opcion" value="formulario_ver">
                        <div class="col-sm-12">
                            <h4 class="m-t-0 header-title"><b>Formulario Vista del Cliente</b></h4>
                            <p class="text-muted font-13 m-b-30"></p>   

                            <div class="col-sm-6">      
                                <div class="card-box">
                                    <h4 class="m-t-0 m-b-20 header-title"><b>Datos del Cliente</b></h4>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label" for="cliente_nombre_v">Nombre</label>
                                                <input type="text" name="cliente_nombre_v" class="form-control" id="cliente_nombre_v" maxlength="60" readonly="true">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label" for="cliente_razonSocial_v">Razón social</label>
                                                <input type="text" name="cliente_razonSocial_v" class="form-control" id="cliente_razonSocial_v" maxlength="60" readonly="true">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label" for="cliente_abreviatura_v">Abreviatura</label>
                                                <input type="text" name="cliente_abreviatura_v" class="form-control" id="cliente_abreviatura_v" maxlength="10" readonly="true">
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <label class="control-label" for="cliente_tipoCliente_nombre_v">Tipo de cliente</label>
                                                <input type="text" name="cliente_tipoCliente_nombre_v" class="form-control" id="cliente_tipoCliente_nombre_v" readonly="true"> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">  
                                <div class="card-box">
                                    <h4 class="m-t-0 m-b-20 header-title"><b>Datos de Ubicacion</b></h4>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label" for="cliente_pais_id_v">País</label>
                                                <input type="text" name="cliente_pais_nombre_v" class="form-control" id="cliente_pais_nombre_v" readonly="readonly">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label" for="cliente_ciudad_id_v">Ciudad</label>
                                                <input name="cliente_ciudad_nombre_v" class="form-control" id="cliente_ciudad_nombre_v" readonly="readonly">
                                                <input type="hidden" name="cliente_ciudad_id_2_v" class="form-control" id="cliente_ciudad_id_2_v">
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <label class="control-label" for="cliente_direccion_v">Dirección</label>
                                                <input type="text" name="cliente_direccion_v" class="form-control" id="cliente_direccion_v" maxlength="60" readonly="true">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label" for="cliente_codigoPostal_v">Codigo Postal</label>
                                                <input type="text" name="cliente_codigoPostal_v" class="form-control" id="cliente_codigoPostal_v" maxlength="10" readonly="true">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="panel-group" id="accordion-cliente">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a data-toggle="collapse" data-parent="#accordion-test-2" href="#datosdecontacto_v" aria-expanded="false" class="collapsed">
                                                        <h4 class="m-t-0 m-b-0 header-title"><b>Datos de Contacto</b></h4>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="datosdecontacto_v" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <div class="col-sm-12"> 
                                                        <div class="card-box">
                                                            <div class="row">                                 
                                                                <div class="col-md-6">
                                                                    <label class="control-label" for="grilla_telefonos_v">Teléfonos</label>
                                                                    <div id="grilla_telefonos_v"></div>      
                                                                </div>  
                                                                <div class="col-md-6">
                                                                    <label class="control-label" for="grilla_emails_v">E-mails</label>
                                                                    <div id="grilla_emails_v"></div>      
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="col-md-6">
                                                                       <div class="form-group">
                                                                           <label class="control-label" for="cliente_paginaWeb_v">Página WEB</label>
                                                                           <input type="text" name="cliente_paginaWeb_v" class="form-control" id="cliente_paginaWeb_v" maxlength="40">
                                                                       </div>
                                                                    </div>
                                                                </div>                                        
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a data-toggle="collapse" data-parent="#accordion-test-2" href="#datosproductos_v" aria-expanded="false" class="collapsed">
                                                        <h4 class="m-t-0 m-b-0 header-title"><b>Productos</b></h4>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="datosproductos_v" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <object type="text/html" id="pantalla_producto_v" width="100%" height="320"></object> 
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a data-toggle="collapse" data-parent="#accordion-test-2" href="#datosobservaciones_v" aria-expanded="false" class="collapsed">
                                                        <h4 class="m-t-0 m-b-0 header-title"><b>Observaciones</b></h4>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="datosobservaciones_v" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="control-label" for="cliente_observaciones_v">Observaciones</label>
                                                                <textarea name="cliente_observaciones_v" class="form-control" id="cliente_observaciones_v" rows="3" maxlength="200" readonly="true"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12">  
                                <div class="card-box">
                                    <div class="form-group text-right m-b-0">
                                        <button type="reset" id="btn_cancelar_vista" class="btn btn-inverse waves-effect waves-light m-l-5">Cerrar</button>
                                        <?php
                                            $clientes_modificar = array_search('clientes_modificar', array_column($permisos, 'permiso_variable'));
                                            if (!empty($clientes_modificar) || ($clientes_modificar === 0)) { 
                                        ?>
                                            <button type="button" id="btn_modificar_cliente" class="btn btn-danger waves-effect waves-light" >Modificar Cliente</button>
                                        <?php } ?>
                                    </div>   
                                </div>
                            </div>        
                        </div>
                </div>
            </div>
            <!-- FIN - Formulario Vista -->  



            <!-- INICIO - Formulario Modificar -->
            <div class="panel-body hidden" id="panel_formulario_modificacion">
                <div class="row">
                    <form  id="formulario_modificacion" name="formulario_modificacion">
                        <!-- Acción del formulario en opcion y id del cliente a modificar -->
                        <input type="hidden" id="cliente_id" name="cliente_id" value="0">
                        <input type="hidden" id="opcion" name="opcion" value="formulario_modificacion">

                        <div class="col-sm-12">
                            <h4 class="m-t-0 header-title"><b>Formulario Modificación del Cliente</b></h4>
                            <p class="text-muted font-13 m-b-30"></p>   

                            <div class="col-sm-6">      
                                <div class="card-box">
                                    <h4 class="m-t-0 m-b-20 header-title"><b>Datos del Cliente</b></h4>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group has-warning">
                                                <label class="control-label" for="cliente_nombre">Nombre</label>
                                                <input type="text" name="cliente_nombre" class="form-control" id="cliente_nombre" maxlength="60">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group has-warning">
                                                <label class="control-label" for="cliente_razonSocial">Razón social</label>
                                                <input type="text" name="cliente_razonSocial" class="form-control" id="cliente_razonSocial" maxlength="60">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group has-warning">
                                                <label class="control-label" for="cliente_abreviatura">Abreviatura</label>
                                                <input type="text" name="cliente_abreviatura" class="form-control" id="cliente_abreviatura" maxlength="10">
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="form-group has-warning">
                                                <label class="control-label" for="cliente_tipoCliente_id">Tipo de cliente</label>
                                                <select name="cliente_tipoCliente_id" class="form-control" id="cliente_tipoCliente_id"></select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">  
                                <div class="card-box">
                                    <h4 class="m-t-0 m-b-20 header-title"><b>Datos de Ubicacion</b></h4>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group has-warning">
                                                <label class="control-label" for="cliente_pais_id">País</label>
                                                <select name="cliente_pais_id" class="form-control" id="cliente_pais_id"></select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group has-warning">
                                                <label class="control-label" for="cliente_ciudad_id">Ciudad</label>

                                                <input name="cliente_ciudad_id" class="form-control" id="cliente_ciudad_id" placeholder="Seleccione el País e ingrese las primeras 3 letras de la ciudad"></input>
                                                <input type="hidden" name="cliente_ciudad_id_2" class="form-control" id="cliente_ciudad_id_2"></input>
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="form-group">

                                                <label class="control-label" for="cliente_direccion">Dirección</label>
                                                <input type="text" name="cliente_direccion" class="form-control" id="cliente_direccion" maxlength="60">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label" for="cliente_codigoPostal">Codigo Postal</label>
                                                <input type="text" name="cliente_codigoPostal" class="form-control" id="cliente_codigoPostal" maxlength="10">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="panel-group" id="accordion-cliente">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a data-toggle="collapse" data-parent="#accordion-test-2" href="#datosdecontacto" aria-expanded="false" class="collapsed">
                                                        <h4 class="m-t-0 m-b-0 header-title"><b>Datos de Contacto</b></h4>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="datosdecontacto" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <div class="col-sm-12"> 
                                                        <div class="card-box">
                                                            <div class="row">                                 
                                                                <div class="col-md-6">
                                                                    <label class="control-label" for="grilla_telefonos">Teléfonos</label>
                                                                    <div class="row">
                                                                        <div class="form-group col-md-3">
                                                                            <select class="form-control" name="telefonoTipo_id" id="telefonoTipo_id"></select>
                                                                        </div>
                                                                        <div class="form-group col-md-5">
                                                                            <input type="text" name="telefono_numero" class="form-control"  id="telefono_numero" placeholder="Ingrese el número" maxlength="20">
                                                                        </div>
                                                                        <div class="form-group col-md-2">
                                                                            <div class="checkbox checkbox-primary">
                                                                                <input id="telefono_principal" type="checkbox">
                                                                                <label for="telefono_principal">Principal</label>
                                                                            </div>
                                                                            <input type="hidden" name="telefono_id_m" class="form-control"  id="telefono_id_m">
                                                                        </div>
                                                                        <div class="form-group col-md-2">
                                                                            <a href='javascript:void(0)'> <i onclick='telefono_guardar()' class='fa fa-save' style="margin-top: 13px;"></i></a>
                                                                            <a href='javascript:void(0)'> <i onclick='telefono_limpiar()' class='fa fa-ban' style="margin-top: 13px;"></i></a>
                                                                        </div>
                                                                    </div> 
                                                                    <div id="grilla_telefonos"></div>      
                                                                </div>  
                                                                <div class="col-md-6">
                                                                    <label class="control-label" for="grilla_emails">E-mails</label>
                                                                    <div class="row">
                                                                        <div class="form-group col-md-3">
                                                                            <select class="form-control" name="emailTipo_id" id="emailTipo_id"></select>
                                                                        </div>
                                                                        <div class="form-group col-md-5">
                                                                            <input type="email" name="email_email" class="form-control"  id="email_email" placeholder="Ingrese el email" maxlength="40">
                                                                        </div>
                                                                        <div class="form-group col-md-2">
                                                                            <div class="checkbox checkbox-primary">
                                                                                <input id="email_principal" type="checkbox">
                                                                                <label for="email_principal">Principal</label>
                                                                            </div>
                                                                            <input type="hidden" name="email_id_m" class="form-control"  id="email_id_m">
                                                                        </div>
                                                                        <div class="form-group col-md-2">
                                                                            <a href='javascript:void(0)'> <i onclick='email_guardar()' class='fa fa-save' style="margin-top: 13px;"></i></a>
                                                                            <a href='javascript:void(0)'> <i onclick='email_limpiar()' class='fa fa-ban' style="margin-top: 13px;"></i></a>
                                                                        </div>
                                                                    </div> 
                                                                    <div id="grilla_emails"></div>      
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="col-md-6">
                                                                       <div class="form-group">
                                                                           <label class="control-label" for="cliente_paginaWeb">Página WEB</label>
                                                                           <input type="text" name="cliente_paginaWeb" class="form-control" id="cliente_paginaWeb" maxlength="40">
                                                                       </div>
                                                                    </div>
                                                                </div>                                        
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a data-toggle="collapse" data-parent="#accordion-test-2" href="#datosproductos" aria-expanded="false" class="collapsed">
                                                        <h4 class="m-t-0 m-b-0 header-title"><b>Productos</b></h4>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="datosproductos" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <object type="text/html" id="pantalla_producto" width="100%" height="320"></object> 
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a data-toggle="collapse" data-parent="#accordion-test-2" href="#datosobservaciones" aria-expanded="false" class="collapsed">
                                                        <h4 class="m-t-0 m-b-0 header-title"><b>Observaciones</b></h4>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="datosobservaciones" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="control-label" for="cliente_observaciones">Observaciones</label>
                                                                <textarea name="cliente_observaciones" class="form-control" id="cliente_observaciones" rows="3" maxlength="200"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12">  
                                <div class="card-box">
                                    <div class="form-group text-right m-b-0">
                                        <button type="reset" id="btn_cancelar" class="btn btn-default waves-effect waves-light m-l-5">Cancelar</button>
                                        <button class="btn btn-primary waves-effect waves-light" type="submit">Guardar</button>
                                    </div>   
                                </div>
                            </div>        
                        </div>
                    </form>    
                </div>
            </div>
            <!-- FIN - Formulario Modificar -->  


            <!-- INICIO - Grilla  -->
            <div class="panel-body" id="panel_grilla">     
                <div class="row">
                    <div class="form-group col-md-4">
                        <input type="text"  name="cliente_nombre_buscar" class="form-control"  id="cliente_nombre_buscar" placeholder="Buscar por nombre">
                    </div>
                    <div class="form-group col-md-3">
                        <select name="cliente_tipoCliente_id_b" class="form-control" id="cliente_tipoCliente_id_b"></select>
                    </div>                
                    <div class="form-group col-md-4">
                        <select name="cliente_pais_id_b" class="form-control" id="cliente_pais_id_b"></select>
                    </div>
                    <div class="form-group col-md-1">
                        <a href='javascript:void(0)'> <i onclick='grilla_listar()' class='fa fa-search' style="margin-top: 10px;"></i></a>
                    </div>
                </div>
                <div id="grilla_info"></div>
                <div id="grilla_cliente"></div>      
            </div>  
            <!-- FIN - Grilla  -->
    </div>
    <!-- FIN - Panel Formularios y Grilla  -->

    <?php require '../includes/modal_activaDesactiva_cliente.php' ?>
    <?php require '../includes/modal_eliminacion.php' ?>
    <?php require '../includes/footer_start.php' ?>
    <?php require '../includes/footer_notificaciones.php' ?>
    <?php require '../includes/footer_datatable.php' ?>
    <?php require '../includes/footer_validacion.php' ?>
    <script src="../assets/js/jquery-ui.min.js" type="text/javascript"></script>
    <script src="cliente.js?random=<?php echo uniqid(); ?>"></script> 
    <?php require '../includes/footer_end.php' ?>
<?php } ?>