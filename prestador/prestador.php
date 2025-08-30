<?php 
    require '../seguridad/seguridad.php';
    
    // Validacion de permisos
    $prestadores_ver = array_search('prestadores_ver', array_column($permisos, 'permiso_variable'));
    if (empty($prestadores_ver) && ($prestadores_ver !== 0)) {
        header("location:../_errores/401.php");
    } else {
?>
    <?php $pagina = "Prestador"; ?>
    <?php require '../includes/header_start.php'; ?>
    <?php require '../includes/header_datatables.php'; ?>
    <?php require '../includes/header_end.php'; ?>
    <link href="prestador.css" rel="stylesheet" type="text/css">
    <?php require '../includes/header_pagina.php'; ?>


    <!-- INICIO - Panel Formularios y Grilla  -->
    <div class="panel panel-default users-content">
        <div class="panel-heading">
            <?php
                $prestadores_alta = array_search('prestadores_alta', array_column($permisos, 'permiso_variable'));
                if (!empty($prestadores_alta) || ($prestadores_alta === 0)) { 
            ?>            
            <button onclick="javascript:agrega_prestador_formulario();" class="btn btn-primary waves-effect waves-light m-l-5"> Agregar prestador <i class="glyphicon glyphicon-plus" ></i></button></div>
            <?php } ?>
            <!-- INICIO - Formulario Alta -->
            <div class="panel-body hidden" id="panel_formulario_alta">
                <div class="row">
                    <form  id="formulario_alta" name="formulario_alta">
                        <!-- Acción del formulario en opcion  -->
                        <input type="hidden" id="opcion" name="opcion" value="formulario_alta">
                        <div class="col-sm-12">
                            <h4 class="m-t-0 header-title"><b>Formulario Alta de Prestador</b></h4>
                            <p class="text-muted font-13 m-b-30"></p>   

                            <div class="col-sm-6">
                                <div class="card-box">
                                    <h4 class="m-t-0 m-b-20 header-title"><b>Datos del Prestador</b></h4>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group has-warning">
                                                <label class="control-label" for="prestador_nombre_n">Nombre</label>
                                                <input type="text" name="prestador_nombre_n" class="form-control" id="prestador_nombre_n" maxlength="75">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group has-warning">
                                                <label class="control-label" for="prestador_razonSocial_n">Razón social</label>
                                                <input type="text" name="prestador_razonSocial_n" class="form-control" id="prestador_razonSocial_n" maxlength="75">
                                            </div>
                                        </div>
                                            <div class="col-md-6">
                                                <div class="form-group has-warning">
                                                    <label class="control-label" for="prestador_tipoPrestador_id_n">Tipo de prestador</label>
                                                    <select name="prestador_tipoPrestador_id_n" class="form-control" id="prestador_tipoPrestador_id_n"></select>
                                                </div>
                                            </div>
                                        <div class="col-md-6">
                                            <div class="form-group has-warning">
                                                <label class="control-label" for="prestador_prestadorPrioridad_id_n">Prioridad</label>
                                                <select name="prestador_prestadorPrioridad_id_n" class="form-control" id="prestador_prestadorPrioridad_id_n"></select>
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
                                               <label class="control-label" for="prestador_pais_id_n">País</label>
                                               <select name="prestador_pais_id_n" class="form-control" id="prestador_pais_id_n" placeholder="Seleccione un pais"></select>
                                           </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group has-warning">
                                                <label class="control-label" for="prestador_ciudad_id_n">Ciudad</label>

                                                <input name="prestador_ciudad_id_n" class="form-control" id="prestador_ciudad_id_n" placeholder="Seleccione el País e ingrese las primeras 3 letras de la ciudad"></input>
                                                <input type="hidden" name="prestador_ciudad_id_n_2" class="form-control" id="prestador_ciudad_id_n_2"></input>
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="form-group has-warning">
                                                <label class="control-label" for="prestador_direccion_n">Dirección</label>
                                                <input type="text" name="prestador_direccion_n" class="form-control" id="prestador_direccion_n" maxlength="60">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group has-warning">
                                                <label class="control-label" for="prestador_codigoPostal_n">Código Postal</label>
                                                <input type="text" name="prestador_codigoPostal_n" class="form-control" id="prestador_codigoPostal_n" maxlength="10">
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
                                                    <input type="email" name="email_email_n" class="form-control"  id="email_email_n" placeholder="Ingrese el e-mail" maxlength="100">
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
                                                <label class="control-label" for="prestador_paginaWeb_n">Página WEB</label>
                                                <input type="text" name="prestador_paginaWeb_n" class="form-control" id="prestador_paginaWeb_n" maxlength="40">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>   

                            <div class="col-sm-12">
                                <div class="card-box">
                                    <h4 class="m-t-0 m-b-20 header-title"><b>Datos de Contrato</b></h4>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label" for="prestador_contrato_id_n">Contrato</label>
                                                <select name="prestador_contrato_id_n" class="form-control" id="prestador_contrato_id_n"></select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label" for="prestador_contratoObservaciones_n">Observaciones</label>
                                                <textarea name="prestador_contratoObservaciones_n" class="form-control" id="prestador_contratoObservaciones_n" rows="3" maxlength="170"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>    
                            <div class="col-sm-12">
                                <div class="card-box">
                                    <h4 class="m-t-0 m-b-20 header-title"><b>Datos Administrativos</b></h4>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label" for="prestador_bancoBeneficiario_n">Banco beneficiario</label>
                                                <textarea name="prestador_bancoBeneficiario_n" class="form-control" id="prestador_bancoBeneficiario_n" rows="6" maxlength="500"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label" for="prestador_bancoIntermediario_n">Banco intermediario</label>
                                                <textarea name="prestador_bancoIntermediario_n" class="form-control" id="prestador_bancoIntermediario_n" rows="6" maxlength="500"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label" for="prestador_taxID_n">Tax ID</label>
                                                <input type="text" name="prestador_taxID_n" class="form-control" id="prestador_taxID_n" maxlength="20">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="control-label" for="prestador_inicioActividades_n">Inicio de actividades</label>
                                            </div>
                                            <div class="input-group col-md-12">
                                                <input type="text" name="prestador_inicioActividades_n" class="form-control" id="prestador_inicioActividades_n" placeholder="dd-mm-aaaa">
                                                <i class="ion-calendar form-control-feedback l-h-34"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>                            
                            <div class="col-sm-12">
                                <div class="card-box">
                                    <h4 class="m-t-0 m-b-20 header-title"><b>Observaciones generales</b></h4>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <textarea name="prestador_observaciones_n" class="form-control" id="prestador_observaciones_n" rows="6" maxlength="1000"></textarea>
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
            <div class="panel-body hidden" id="panel_formulario_vista">
                <div class="row">
                        <!-- Acción del formulario en opcion y id del prestador a ver -->
                        <input type="hidden" id="prestador_id_v" name="prestador_id_v" value="0">
                        <input type="hidden" id="opcion" name="opcion" value="formulario_vista">
                        <div class="col-sm-12">
                            <h4 class="m-t-0 header-title"><b>Vista del Prestador</b></h4>
                            <p class="text-muted font-13 m-b-30"></p>   

                            <div class="col-sm-6">
                                <div class="card-box">
                                    <h4 class="m-t-0 m-b-20 header-title"><b>Datos del Prestador</b></h4>   
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label" for="prestador_nombre_v">Nombre</label>
                                                <input type="text" name="prestador_nombre_v" class="form-control" id="prestador_nombre_v" readonly="readonly">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label" for="prestador_razonSocial_v">Razón social</label>
                                                <input type="text" name="prestador_razonSocial_v" class="form-control" id="prestador_razonSocial_v" readonly="readonly">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label" for="prestador_tipoPrestador_nombre_v">Tipo de prestador</label>
                                                <input type="text" name="prestador_tipoPrestador_nombre_v" class="form-control" id="prestador_tipoPrestador_nombre_v" readonly="readonly">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label" for="prestador_prestadorPrioridad_nombre_v">Prioridad</label>
                                                <input type="text" name="prestador_prestadorPrioridad_nombre_v" class="form-control" id="prestador_prestadorPrioridad_nombre_v" readonly="readonly">
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
                                                <label class="control-label" for="prestador_pais_id_v">País</label>
                                                <input type="text" name="prestador_pais_nombre_v" class="form-control" id="prestador_pais_nombre_v" readonly="readonly">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label" for="prestador_ciudad_id_v">Ciudad</label>
                                                <input name="prestador_ciudad_nombre_v" class="form-control" id="prestador_ciudad_nombre_v" readonly="readonly"></input>
                                                <input type="hidden" name="prestador_ciudad_id_2_v" class="form-control" id="prestador_ciudad_id_2_v"></input>
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <label class="control-label" for="prestador_direccion_v">Dirección</label>
                                                <input type="text" name="prestador_direccion_v" class="form-control" id="prestador_direccion_v" readonly="readonly">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label" for="prestador_codigoPostal_v">Código Postal</label>
                                                <input type="text" name="prestador_codigoPostal_v" class="form-control" id="prestador_codigoPostal_v" readonly="readonly">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="panel-group" id="accordion-prestador">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a data-toggle="collapse" data-parent="#accordion-test-2" href="#datosdecontacto_v" aria-expanded="false" class="collapsed">
                                                        <h4 class="m-t-0 m-b-0 header-title"><b>Datos de contacto</b></h4>
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
                                                                    <div class="col-sm-6">
                                                                        <div class="form-group">
                                                                            <label class="control-label" for="prestador_paginaWeb_v">Página WEB</label>
                                                                            <input type="text" name="prestador_paginaWeb_v" placeholder="" class="form-control" id="prestador_paginaWeb_v" readonly="readonly">
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
                                                    <a data-toggle="collapse" data-parent="#accordion-test-2" href="#datosdecontrato_v" aria-expanded="false" class="collapsed">
                                                        <h4 class="m-t-0 m-b-0 header-title"><b>Datos de contrato</b></h4>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="datosdecontrato_v" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <div class="col-sm-12"> 
                                                        <div class="card-box">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="control-label" for="prestador_contrato_id_v">Contrato</label>
                                                                        <input type="text" name="prestador_contrato_nombre_v" class="form-control" id="prestador_contrato_nombre_v" readonly="readonly">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-8">
                                                                    <div class="form-group">
                                                                        <label class="control-label" for="prestador_contratoObservaciones_v">Observaciones</label>
                                                                        <textarea name="prestador_contratoObservaciones_v" class="form-control" id="prestador_contratoObservaciones_v" rows="3" readonly="readonly"></textarea>
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
                                                    <a data-toggle="collapse" data-parent="#accordion-test-2" href="#datosadministrativos_v" aria-expanded="false" class="collapsed">
                                                        <h4 class="m-t-0 m-b-0 header-title"><b>Datos Administrativos</b></h4>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="datosadministrativos_v" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <div class="col-sm-12"> 
                                                        <div class="card-box">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="control-label" for="prestador_bancoBeneficiario_v">Banco beneficiario</label>
                                                                        <textarea name="prestador_bancoBeneficiario_v" class="form-control" id="prestador_bancoBeneficiario_v" rows="6" readonly="readonly"></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="control-label" for="prestador_bancoIntermediario_v">Banco intermediario</label>
                                                                        <textarea name="prestador_bancoIntermediario_v" class="form-control" id="prestador_bancoIntermediario_v" rows="6" readonly="readonly"></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="control-label" for="prestador_taxID_v">Tax ID</label>
                                                                        <input type="text" name="prestador_taxID_v" class="form-control" id="prestador_taxID_v" readonly="readonly">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="input-group">
                                                                        <label class="control-label" for="prestador_inicioActividades_v">Inicio de actividades</label>
                                                                    </div>
                                                                    <div class="input-group col-md-12">
                                                                        <input type="text" name="prestador_inicioActividades_v" class="form-control" id="prestador_inicioActividades_v" readonly="readonly">
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
                                                    <a data-toggle="collapse" data-parent="#accordion-test-2" href="#observacionesGenerales_v" aria-expanded="false" class="collapsed">
                                                        <h4 class="m-t-0 m-b-0 header-title"><b>Observaciones generales</b></h4>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="observacionesGenerales_v" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <div class="col-sm-12"> 
                                                        <div class="card-box">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <textarea name="prestador_observaciones_v" class="form-control" id="prestador_observaciones_v" rows="6" readonly="readonly"></textarea>
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
                                                    <a data-toggle="collapse" data-parent="#accordion-test-2" href="#datosterritorialidad_v" aria-expanded="false" class="collapsed">
                                                        <h4 class="m-t-0 m-b-0 header-title"><b>Territorialidad</b></h4>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="datosterritorialidad_v" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <object type="text/html" id="pantalla_territorialidad_v" width="100%" height="600"></object> 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a data-toggle="collapse" data-parent="#accordion-test-2" href="#datospractica_v" aria-expanded="false" class="collapsed">
                                                        <h4 class="m-t-0 m-b-0 header-title"><b>Prácticas</b></h4>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="datospractica_v" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <object type="text/html" id="pantalla_practica_v" width="100%" height="300"></object> 
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a data-toggle="collapse" data-parent="#accordion-test-2" href="#archivos_v" aria-expanded="false" class="collapsed">
                                                        <h4 class="m-t-0 m-b-0 header-title"><b>Archivos</b></h4>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="archivos_v" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <object type="text/html" id="pantalla_archivos_v" width="100%" height="400"></object> 
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
                                            $prestadores_modificar = array_search('prestadores_modificar', array_column($permisos, 'permiso_variable'));
                                            if (!empty($prestadores_modificar) || ($prestadores_modificar === 0)) { 
                                        ?>
                                        <button type="button" id="btn_modificar_prestador_footer" class="btn btn-danger waves-effect waves-light" >Modificar Prestador</button>
                                        <?php } ?>
                                    </div>   
                                </div>
                            </div>                            
                        </div>
                    </form>    
                </div>
            </div>
            <!-- FIN - Formulario Vista -->


            <!-- INICIO - Formulario Modificar -->
            <div class="panel-body hidden" id="panel_formulario_modificacion">
                <div class="row">
                    <form  id="formulario_modificacion" name="formulario_modificacion">
                        <!-- Acción del formulario en opcion y id del prestador a modificar -->
                        <input type="hidden" id="prestador_id" name="prestador_id" value="0">
                        <input type="hidden" id="opcion" name="opcion" value="formulario_modificacion">
                        <div class="col-sm-12">
                            <h4 class="m-t-0 header-title"><b>Formulario Modificación del Prestador</b></h4>
                            <p class="text-muted font-13 m-b-30"></p>   

                            <div class="col-sm-6">
                                <div class="card-box">
                                    <h4 class="m-t-0 m-b-20 header-title"><b>Datos del Prestador</b></h4>   
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group has-warning">
                                                <label class="control-label" for="prestador_nombre">Nombre</label>
                                                <input type="text" name="prestador_nombre" placeholder="" class="form-control" id="prestador_nombre" maxlength="75">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group has-warning">
                                                <label class="control-label" for="prestador_razonSocial">Razón social</label>
                                                <input type="text" name="prestador_razonSocial" placeholder="" class="form-control" id="prestador_razonSocial" maxlength="75">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group has-warning">
                                                <label class="control-label" for="prestador_tipoPrestador_id">Tipo de prestador</label>
                                                <select name="prestador_tipoPrestador_id" class="form-control" id="prestador_tipoPrestador_id"></select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group has-warning">
                                                <label class="control-label" for="prestador_prestadorPrioridad_id">Prioridad</label>
                                                <select name="prestador_prestadorPrioridad_id" class="form-control" id="prestador_prestadorPrioridad_id"></select>
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
                                                <label class="control-label" for="prestador_pais_id">País</label>
                                                <select name="prestador_pais_id" class="form-control" id="prestador_pais_id"></select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group has-warning">
                                                <label class="control-label" for="prestador_ciudad_id">Ciudad</label>

                                                <input name="prestador_ciudad_id" class="form-control" id="prestador_ciudad_id" placeholder="Seleccione el País e ingrese las primeras 3 letras de la ciudad"></input>
                                                <input type="hidden" name="prestador_ciudad_id_2" class="form-control" id="prestador_ciudad_id_2"></input>
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="form-group has-warning">
                                                <label class="control-label" for="prestador_direccion">Dirección</label>
                                                <input type="text" name="prestador_direccion" class="form-control" id="prestador_direccion" maxlength="60">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group has-warning">
                                                <label class="control-label" for="prestador_codigoPostal">Código Postal</label>
                                                <input type="text" name="prestador_codigoPostal" class="form-control" id="prestador_codigoPostal" maxlength="10">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="panel-group" id="accordion-prestador">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a data-toggle="collapse" data-parent="#accordion-test-2" href="#datosdecontacto" aria-expanded="false" class="collapsed">
                                                        <h4 class="m-t-0 m-b-0 header-title"><b>Datos de contacto</b></h4>
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
                                                                            <input type="text" name="telefono_numero" class="form-control"  id="telefono_numero" placeholder="Ingrese el número" maxlength="22">
                                                                        </div>
                                                                        <div class="form-group col-md-2">
                                                                            <div class="checkbox checkbox-primary">
                                                                                <input name="telefono_principal" id="telefono_principal" type="checkbox">
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
                                                                            <input type="text" name="email_email" class="form-control"  id="email_email" placeholder="Ingrese el e-mail" maxlength="100">
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
                                                                    <div class="col-sm-6">
                                                                        <div class="form-group">
                                                                            <label class="control-label" for="prestador_paginaWeb">Página WEB</label>
                                                                            <input type="text" name="prestador_paginaWeb" placeholder="" class="form-control" id="prestador_paginaWeb" maxlength="40">
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
                                                    <a data-toggle="collapse" data-parent="#accordion-test-2" href="#datosdecontrato" aria-expanded="false" class="collapsed">
                                                        <h4 class="m-t-0 m-b-0 header-title"><b>Datos de contrato</b></h4>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="datosdecontrato" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <div class="col-sm-12"> 
                                                        <div class="card-box">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="control-label" for="prestador_contrato_id">Contrato</label>
                                                                        <select name="prestador_contrato_id" class="form-control" id="prestador_contrato_id"></select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-8">
                                                                    <div class="form-group">
                                                                        <label class="control-label" for="prestador_contratoObservaciones">Observaciones</label>
                                                                        <textarea name="prestador_contratoObservaciones" class="form-control" id="prestador_contratoObservaciones" rows="3" maxlength="170"></textarea>
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
                                                    <a data-toggle="collapse" data-parent="#accordion-test-2" href="#datosadministrativos" aria-expanded="false" class="collapsed">
                                                        <h4 class="m-t-0 m-b-0 header-title"><b>Datos Administrativos</b></h4>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="datosadministrativos" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <div class="col-sm-12"> 
                                                        <div class="card-box">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="control-label" for="prestador_bancoBeneficiario">Banco beneficiario</label>
                                                                        <textarea name="prestador_bancoBeneficiario" class="form-control" id="prestador_bancoBeneficiario" rows="6" maxlength="500"></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="control-label" for="prestador_bancoIntermediario">Banco intermediario</label>
                                                                        <textarea name="prestador_bancoIntermediario" class="form-control" id="prestador_bancoIntermediario" rows="6" maxlength="500"></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="control-label" for="prestador_taxID">Tax ID</label>
                                                                        <input type="text" name="prestador_taxID" class="form-control" id="prestador_taxID" maxlength="20">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="input-group">
                                                                        <label class="control-label" for="prestador_inicioActividades">Inicio de actividades</label>
                                                                    </div>
                                                                    <div class="input-group col-md-12">
                                                                        <input type="text" name="prestador_inicioActividades" class="form-control" id="prestador_inicioActividades" placeholder="dd/mm/aaaa">
                                                                        <i class="ion-calendar form-control-feedback l-h-34"></i>
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
                                                    <a data-toggle="collapse" data-parent="#accordion-test-2" href="#observacionesGenerales" aria-expanded="false" class="collapsed">
                                                        <h4 class="m-t-0 m-b-0 header-title"><b>Observaciones generales</b></h4>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="observacionesGenerales" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <div class="col-sm-12"> 
                                                        <div class="card-box">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <textarea name="prestador_observaciones" class="form-control" id="prestador_observaciones" rows="6" maxlength="1000"></textarea>
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
                                                    <a data-toggle="collapse" data-parent="#accordion-test-2" href="#datosterritorialidad" aria-expanded="false" class="collapsed">
                                                        <h4 class="m-t-0 m-b-0 header-title"><b>Territorialidad</b></h4>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="datosterritorialidad" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <object type="text/html" id="pantalla_territorialidad" width="100%" height="600"></object> 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a data-toggle="collapse" data-parent="#accordion-test-2" href="#datospractica" aria-expanded="false" class="collapsed">
                                                        <h4 class="m-t-0 m-b-0 header-title"><b>Prácticas</b></h4>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="datospractica" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <object type="text/html" id="pantalla_practica" width="100%" height="300"></object> 
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a data-toggle="collapse" data-parent="#accordion-test-2" href="#archivos" aria-expanded="false" class="collapsed">
                                                        <h4 class="m-t-0 m-b-0 header-title"><b>Archivos</b></h4>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="archivos" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <object type="text/html" id="pantalla_archivos" width="100%" height="400"></object> 
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
                    <div class="form-group col-md-5">
                        <input type="text"  name="prestador_nombre_buscar" class="form-control"  id="prestador_nombre_buscar" placeholder="Buscar por nombre" >
                    </div>
                    <div class="form-group col-md-5">
                        <select name="prestador_tipoPrestador_id_b" class="form-control" id="prestador_tipoPrestador_id_b"></select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-5">
                        <select name="prestador_pais_id_b" class="form-control" id="prestador_pais_id_b"></select>
                    </div>
                    <div class="form-group col-md-5">                    
                        <input name="prestador_ciudad_id_b" class="form-control" id="prestador_ciudad_id_b" placeholder="Seleccione el País e ingrese las primeras 3 letras de la ciudad"></input>
                        <input type="hidden" name="prestador_ciudad_id_b_2" class="form-control" id="prestador_ciudad_id_b_2"></input>
                    </div>
                    <div class="form-group col-md-1">
                        <a href='javascript:void(0)'> <i onclick='grilla_listar()' class='fa fa-search' style="margin-top: 10px;"></i></a>
                    </div>
                </div>
                <div id="grilla_info"></div>
                <div id="grilla_prestador"></div>      
            </div>  
            <!-- FIN - Grilla  -->
        </div>
    </div>
    <!-- FIN - Panel Formularios y Grilla  -->

    <?php require '../includes/modal_activaDesactiva_prestador.php' ?>
    <?php require '../includes/modal_eliminacion.php' ?>
    <?php require '../includes/footer_start.php' ?>
    <?php require '../includes/footer_notificaciones.php' ?>
    <?php require '../includes/footer_datatable.php' ?>
    <?php require '../includes/footer_validacion.php' ?>
    <script src="prestador.js?random=<?php echo uniqid(); ?>"></script>
    <?php require '../includes/footer_end.php' ?>
    
    
    <!-- Para ubicarse en un prestador puntual -->
    <?php 
        $vprovider = isset($_GET["vprovider"])?$_GET["vprovider"]:''; // Trae el id del provedor para mostrarlo
        
        if ($vprovider > 0) { 
    ?>
        <script>
            let prestador_id = '<?php echo $vprovider; ?>';
            formulario_lectura(prestador_id);
        </script>
    <?php } ?>
<?php } ?>