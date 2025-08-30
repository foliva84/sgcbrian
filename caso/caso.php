<?php 
    require '../seguridad/seguridad.php';

    if (Usuario::puede("casos_ver") == 0) {

        header("location:../_errores/401.php");

    } else {
?>
    <?php $pagina = "Caso"; ?>
    <?php require '../includes/header_start.php'; ?>
    <?php require '../includes/header_datatables.php'; ?>
    <?php require '../includes/header_end.php'; ?>
    <link href="../assets/css/iconos_propios.css" rel="stylesheet" type="text/css">
    <?php require '../includes/header_pagina.php'; ?>
    
    <?php include ('ws.php'); ?>
    
    <!-- INICIO - Modal  Casos Repetidos  -->
    <div id="modal_casosRepetidos" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-casosRepetidos" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" style="width:70%;">
            <div class="modal-content">
                <div class="modal-header">
                    <b>Casos Repetidos</b>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div id="grilla_casos_repetidos"></div>    
                </div>
            </div>
        </div>
    </div>
    <!-- FIN - Modal Casos Repetidos  -->

    <!-- INICIO - Panel Formularios y Grilla  -->
    <div class="panel panel-default users-content">
            <?php if (Usuario::puede("casos_alta")) { ?>
                <div class="panel-heading">
                    <button id="btn_nuevo_caso" onclick="javascript:preparar_alta_caso();" class="btn btn-primary waves-effect waves-light m-l-5"> Nuevo Caso <i class="glyphicon glyphicon-plus" ></i></button>
                    <?php if (Usuario::puede("carga_caso_manual")) { ?>
                        <button id="btn_caso_manual" onclick="javascript:habilita_alta_manual_caso();" class="btn btn-warning waves-effect waves-light m-l-5"> Entrada Manual <i class="glyphicon glyphicon-console" ></i></button>
                    <?php } ?>
                </div>
            <?php } ?>
            <!-- INICIO - Formulario Alta -->
            <div class="panel-body hidden" id="panel_formulario_alta">
                <div class="row">
                    <form  id="formulario_alta" name="formulario_alta">
                        <!-- Acción del formulario en opcion  -->
                        <input type="hidden" id="opcion" name="opcion" value="formulario_alta">
                        <input type="hidden" id="caso_info_ws_n" name="caso_info_ws_n" readonly="readonly">
                        <div class="col-sm-6">
                            <div class="card-box-form">
                                <div class="row">
                                    <h4 class="m-t-0 m-b-20 header-title"><b>Datos principales</b></h4>
                                    <div class="col-md-5">
                                        <div class="form-group has-warning">
                                            <label class="control-label" for="caso_numeroVoucher_n">Número de Voucher</label>
                                            <div class="input-group">
                                                <input type="text" name="caso_numeroVoucher_n" class="form-control" id="caso_numeroVoucher_n" maxlength="15" readonly>
                                                <span class="input-group-btn">
                                                    <button type="button" onclick="form_consulta_ws(1);" data-toggle="modal" data-target="#modal-ws" class="btn waves-effect waves-light btn-warning"><i class="ion-search"></i></button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="form-group has-warning">
                                            <label class="control-label" for="caso_cliente_id_n">Cliente</label>
                                            <select name="caso_cliente_id_n" class="form-control" id="caso_cliente_id_n"></select>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-group has-warning">
                                            <label class="control-label" for="caso_beneficiarioNombre_n">Nombre del Beneficiario</label>
                                            <input type="text" name="caso_beneficiarioNombre_n" class="form-control" id="caso_beneficiarioNombre_n" maxlength="100">
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <div class="checkbox checkbox-primary">
                                                <p class="text-muted m-b-30 font-13"></p>
                                                <input name="caso_paxVIP_n" id="caso_paxVIP_n" type="checkbox">
                                                <label for="caso_paxVIP_n">VIP</label>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Permiso para el marcar el caso como legal -->
                                    <?php
                                        $caso_legal = array_search('caso_legal', array_column($permisos, 'permiso_variable'));
                                        if (!empty($caso_legal) || ($caso_legal === 0)) { 
                                    ?>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <div class="checkbox checkbox-primary">
                                                <p class="text-muted m-b-30 font-13"></p>
                                                <input name="caso_legal_n" id="caso_legal_n" type="checkbox">
                                                <label for="caso_legal_n">Legal</label>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <div class="col-md-4">
                                        <div class="input-group has-warning">
                                            <label class="control-label" for="caso_fechaSiniestro_n">Fecha del Siniestro</label>
                                        </div>
                                        <div class="input-group has-warning col-md-12">
                                            <input type="text" name="caso_fechaSiniestro_n" class="form-control" id="caso_fechaSiniestro_n" placeholder="dd-mm-aaaa" readonly="readonly">
                                            <i class="ion-calendar form-control-feedback l-h-34"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group has-warning">
                                            <label class="control-label" for="caso_tipoAsistencia_id_n">Tipo de Asistencia</label>
                                            <select name="caso_tipoAsistencia_id_n" class="form-control" id="caso_tipoAsistencia_id_n"></select>
                                            <input type="hidden" name="caso_tipoAsistencia_clasificacion_id_n" class="form-control" id="caso_tipoAsistencia_clasificacion_id_n" >
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group has-warning">
                                            <label class="control-label" for="caso_fee_id_n">Fee</label>
                                            <select name="caso_fee_id_n" class="form-control" id="caso_fee_id_n"></select>
                                        </div>
                                    </div>
                                    <div class="col-md-5" id="no_medical_cost_container">
                                        <div class="form-group">
                                            <div class="checkbox checkbox-primary">
                                                <p class="text-muted m-b-30 font-13"></p>
                                                <input name="no_medical_cost_n" id="no_medical_cost_n" type="checkbox">
                                                <label for="no_medical_cost_n">Asistencia sin costo</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="card-box-form">
                                <div class="row">
                                    <h4 class="m-t-0 m-b-20 header-title"><b>Datos Territoriales</b></h4>
                                    <div class="col-md-6">
                                        <div class="form-group has-warning">
                                            <label class="control-label" for="caso_pais_id_n">Pais</label>
                                            <select name="caso_pais_id_n" class="form-control" id="caso_pais_id_n"></select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group has-warning">
                                            <label class="control-label" for="caso_ciudad_id_n">Ciudad</label>
                                            <input name="caso_ciudad_id_n" class="form-control" id="caso_ciudad_id_n" placeholder="Seleccione el País e ingrese las primeras 3 letras de la ciudad">
                                            <input type="hidden" name="caso_ciudad_id_n_2" class="form-control" id="caso_ciudad_id_n_2" readonly="readonly">
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <label class="control-label" for="caso_direccion_n">Dirección</label>
                                            <input name="caso_direccion_n" class="form-control" id="caso_direccion_n" type="text" maxlength="60">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label" for="caso_codigoPostal_n">Código Postal</label>
                                            <input name="caso_codigoPostal_n" class="form-control" id="caso_codigoPostal_n" type="text" maxlength="10">
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <label class="control-label" for="caso_hotel_n">Hotel</label>
                                            <input name="caso_hotel_n" class="form-control" id="caso_hotel_n" type="text" maxlength="40">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label" for="caso_habitacion_n">Habitación</label>
                                            <input name="caso_habitacion_n" class="form-control" id="caso_habitacion_n" type="text" maxlength="6">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="card-box-form">
                                <div class="row">
                                    <h4 class="m-t-0 m-b-20 header-title"><b>Datos de Contacto</b></h4>
                                    <div class="col-md-6">
                                        <label class="control-label">Teléfonos</label>
                                        <div class="row">
                                            <div class="form-group col-md-4">
                                                <select class="form-control" name="caso_telefonoTipo_id_n" id="caso_telefonoTipo_id_n"></select>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <span class="input-group-btn">
                                                            <button type="button" id="btn_prefijo_pais_n" class="btn btn-inverse"><i class="ion-ios7-telephone"></i></button>   
                                                        </span>
                                                        <input type="text" name="telefono_numero_n" class="form-control"  id="telefono_numero_n" placeholder="Ingrese el número principal" maxlength="22">
                                                    </div>  
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-4">
                                                <select class="form-control" name="caso_telefonoTipo_id_n_2" id="caso_telefonoTipo_id_n_2"></select>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <span class="input-group-btn">
                                                        <button type="button" id="btn_prefijo_pais_n_2" class="btn btn-inverse" ><i class="ion-ios7-telephone"></i></button>
                                                        </span>
                                                        <input type="text" name="telefono_numero_n_2" class="form-control"  id="telefono_numero_n_2" placeholder="Ingrese el número" maxlength="22">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="control-label">E-mail</label>
                                        <div class="row">
                                            <div class="form-group col-md-8">
                                                <input type="email" name="email_email_n" class="form-control"  id="email_email_n" placeholder="Ingrese el email" maxlength="40">
                                            </div>
                                        </div> 
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="card-box-form">
                                <div class="row">
                                    <h4 class="m-t-0 m-b-20 header-title"><b>Datos del Beneficiario</b></h4>
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <label class="control-label" for="caso_beneficiarioNacimiento_n">Fecha de Nacimiento</label>
                                        </div>
                                        <div class="input-group col-md-12">
                                            <input type="text" name="caso_beneficiarioNacimiento_n" class="form-control" id="caso_beneficiarioNacimiento_n" placeholder="dd-mm-aaaa" readonly="readonly">
                                            <i class="ion-calendar form-control-feedback l-h-34"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label class="control-label" for="caso_beneficiarioEdad_n">Edad</label>
                                            <input class="form-control" name="caso_beneficiarioEdad_n" id="caso_beneficiarioEdad_n" type="text" readonly="readonly">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label" for="caso_beneficiarioGenero_id_n">Genero</label>
                                            <select name="caso_beneficiarioGenero_id_n" class="form-control" id="caso_beneficiarioGenero_id_n">
                                                <option value="">Seleccione</option>
                                                <option value="1">Masculino</option>
                                                <option value="2">Femenino</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label" for="caso_beneficiarioDocumento_n">Número de Documento</label>
                                            <input name="caso_beneficiarioDocumento_n" class="form-control" id="caso_beneficiarioDocumento_n" type="text" maxlength="25">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="card-box-form">
                                <div class="row">
                                    <h4 class="m-t-0 m-b-20 header-title"><b>Datos del Producto</b></h4>
                                    <div class="col-md-3">
                                        <div class="form-group has-warning">
                                            <label class="control-label" for="caso_producto_id_n">Producto</label>
                                            <select name="caso_producto_id_n" class="form-control" id="caso_producto_id_n"></select>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group has-warning">
                                            <label class="control-label" for="caso_deducible_n">Deducible</label>
                                            <input name="caso_deducible_n" class="form-control" id="caso_deducible_n" type="text" maxlength="50">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label" for="caso_agencia_n">Agencia</label>
                                            <input name="caso_agencia_n" class="form-control" id="caso_agencia_n" type="text" maxlength="60">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label" for="caso_quienEmitioVoucher_n">Quien emitió el Voucher</label>
                                            <input name="caso_quienEmitioVoucher_n" class="form-control" id="caso_quienEmitioVoucher_n" type="text" maxlength="45">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label" for="caso_fechaSalida_n">Fecha de salida</label>
                                            <div class="input-group col-md-12">
                                                <input type="text" name="caso_fechaSalida_n" class="form-control" id="caso_fechaSalida_n" placeholder="dd-mm-aaaa" readonly="readonly">
                                                <i class="ion-calendar form-control-feedback l-h-34"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label" for="caso_fechaEmisionVoucher_n">Fecha emisión del Voucher</label>
                                            <div class="input-group col-md-12">
                                                <input type="text" name="caso_fechaEmisionVoucher_n" class="form-control" id="caso_fechaEmisionVoucher_n" placeholder="dd-mm-aaaa" readonly="readonly">
                                                <i class="ion-calendar form-control-feedback l-h-34"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label" for="vigenciasVoucher">Vigencia del Voucher</label>
                                            <div class="input-group input-daterange" id="date-range">
                                                <input type="text" name="caso_vigenciaVoucherDesde_n" class="form-control" placeholder="dd-mm-aaaa" id="caso_vigenciaVoucherDesde_n" readonly="readonly">
                                                <span class="input-group-addon bg-primary b-0 text-white">hasta</span>
                                                <input type="text" name="caso_vigenciaVoucherHasta_n" class="form-control" placeholder="dd-mm-aaaa" id="caso_vigenciaVoucherHasta_n" readonly="readonly">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12" id="porEnfermedad_n">
                            <div class="card-box-form">
                                <div class="row">
                                    <h4 class="m-t-0 m-b-20 header-title"><b>Datos por Enfermedad</b></h4>
                                    <div class="col-md-2 has-warning">
                                        <label class="control-label" for="caso_fechaInicioSintomas_n">Incio de Síntomas</label>
                                        <div class="input-group">
                                            <input type="text" name="caso_fechaInicioSintomas_n" class="form-control" placeholder="dd-mm-aaaa" id="caso_fechaInicioSintomas_n" readonly="readonly">
                                            <i class="ion-calendar form-control-feedback l-h-34"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group has-warning">
                                            <label class="control-label" for="caso_sintomas_n">Síntomas</label>
                                            <textarea name="caso_sintomas_n" class="form-control" id="caso_sintomas_n" rows="3" maxlength="170"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="control-label" for="caso_antecedentes_n">Antecedentes</label>
                                            <textarea name="caso_antecedentes_n" class="form-control" id="caso_antecedentes_n" rows="3" maxlength="170"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label" for="caso_diagnostico_id_n">Diagnóstico</label>
                                            <input name="caso_diagnostico_id_n" class="form-control" id="caso_diagnostico_id_n" placeholder="Ingrese las primeras 3 letras del diagnóstico">
                                            <input type="hidden" name="caso_diagnostico_id_n_2" class="form-control" id="caso_diagnostico_id_n_2" readonly="readonly">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12" id="porDemoraVuelo_n">
                            <div class="card-box-form">
                                <div class="row">
                                    <h4 class="m-t-0 m-b-20 header-title"><b>Datos por Demora del Vuelo</b></h4>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label" for="caso_motivoVueloDemorado_n">Motivo</label>
                                            <textarea name="caso_motivoVueloDemorado_n" class="form-control" id="caso_motivoVueloDemorado_n" rows="3" maxlength="150"></textarea>
                                        </div>
                                    </div>             
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12" id="porPerdidaEquipaje_n">
                            <div class="card-box-form">
                                <div class="row">
                                    <h4 class="m-t-0 m-b-20 header-title"><b>Datos por Pérdida de Equipaje</b></h4>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label" for="caso_companiaAerea_n">Nombre de la Compañía Aérea</label>
                                            <input name="caso_companiaAerea_n" class="form-control" id="caso_companiaAerea_n" type="text" maxlength="45">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label" for="caso_numeroVuelo_n">Número de Vuelo</label>
                                            <input name="caso_numeroVuelo_n" class="form-control" id="caso_numeroVuelo_n" type="text" maxlength="10">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label" for="caso_fechaPerdidaEquipaje_n">Fecha de la pérdida</label>
                                            <div class="input-group col-md-12">
                                                <input type="text" name="caso_fechaPerdidaEquipaje_n" class="form-control" placeholder="dd-mm-aaaa" id="caso_fechaPerdidaEquipaje_n" readonly="readonly">
                                                <i class="ion-calendar form-control-feedback l-h-34"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label" for="caso_fechaRecuperacionEquipaje_n">Fecha de recuperación</label>
                                            <div class="input-group col-md-12">
                                                <input type="text" name="caso_fechaRecuperacionEquipaje_n" class="form-control" placeholder="dd-mm-aaaa" id="caso_fechaRecuperacionEquipaje_n" readonly="readonly">
                                                <i class="ion-calendar form-control-feedback l-h-34"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label" for="caso_titularPIR_n">Titular de PIR</label>
                                            <input name="caso_titularPIR_n" class="form-control" id="caso_titularPIR_n" type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label" for="caso_numeroPIR_n">Número de PIR</label>
                                            <input name="caso_numeroPIR_n" class="form-control" id="caso_numeroPIR_n" type="text" maxlength="10">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label" for="caso_numeroEquipaje_n">Número de Equipaje</label>
                                            <input name="caso_numeroEquipaje_n" class="form-control" id="caso_numeroEquipaje_n" type="text" maxlength="10">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="card-box-form">
                                <div class="row">
                                    <h4 class="m-t-0 m-b-20 header-title"><b>Otros Datos</b></h4>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label" for="caso_observaciones_n">Observaciones</label>
                                            <textarea name="caso_observaciones_n" class="form-control" id="caso_observaciones_n" rows="4" maxlength="300"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <?php
                                                $campo_supervisor = array_search('campo_supervisor', array_column($permisos, 'permiso_variable'));
                                                if (!empty($campo_supervisor) || ($campo_supervisor === 0)) { 
                                            ?>
                                            <label class="control-label" for="caso_campoSupervisor_n">Campo Supervisor</label>
                                            <textarea name="caso_campoSupervisor_n" class="form-control" id="caso_campoSupervisor_n" rows="4" maxlength="300"></textarea>
                                            <?php } ?>
                                        </div>
                                    </div>     
                                </div>
                            </div>
                            <div class="card-box-form">
                                <div class="form-group text-right m-b-0">
                                    <button type="reset" id="btn_cancelar_nuevo" class="btn btn-inverse waves-effect waves-light">Cancelar</button>
                                    <?php
                                        $casos_alta = array_search('casos_alta', array_column($permisos, 'permiso_variable'));
                                        if (!empty($casos_alta) || ($casos_alta === 0)) { 
                                    ?>
                                        <button type="submit" id="btn_crear_nuevo" class="btn btn-success waves-effect waves-light">Crear Caso</button> 
                                    <?php } ?>    
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
                    <!-- INICIO - Menu Tabs -->
                    <div class="col-sm-12">
                        <ul class="nav nav-tabs navtab-custom nav-justified">
                            <?php
                                if (Usuario::puede("casos_ver") == 1) {
                            ?>
                            <li id="tabCasos" class="active">
                                <a href="#caso" data-toggle="tab" aria-expanded="true">
                                    <span class="visible-xs"><i class="fa fa-home"></i></span>
                                    <span class="hidden-xs">Caso</span>
                                </a>
                            </li>
                            <?php } ?>
                            <?php
                                if (Usuario::puede("comunicaciones_ver") == 1) {
                            ?>
                            <li id="tabComunicaciones" class="">
                                <a href="#comunicaciones" data-toggle="tab" aria-expanded="false">
                                    <span class="visible-xs"><i class="fa fa-user"></i></span>
                                    <span class="hidden-xs">Comunicaciones</span>
                                </a>
                            </li>
                            <?php } ?>
                            <?php
                                if (Usuario::puede("servicios_ver") == 1) {
                            ?>
                            <li id="tabServicios" class="">
                                <a href="#servicios" data-toggle="tab" aria-expanded="false">
                                    <span class="visible-xs"><i class="fa fa-user"></i></span>
                                    <span class="hidden-xs">Servicios</span>
                                </a>
                            </li>
                            <?php } ?>
                            <?php
                                if (Usuario::puede("reintegros_ver") == 1) {
                            ?>
                            <li id="tabReintegros" class="">
                                <a href="#reintegros" data-toggle="tab" aria-expanded="false">
                                    <span class="visible-xs"><i class="fa fa-user"></i></span>
                                    <span class="hidden-xs">Reintegros</span>
                                </a>
                            </li>
                            <?php } ?>
                            <?php
                                if (Usuario::puede("archivos_ver") == 1) {
                            ?>
                             <li id="tabArchivos" class="">
                                <a href="#archivos" data-toggle="tab" aria-expanded="false">
                                    <span class="visible-xs"><i class="fa fa-user"></i></span>
                                    <span class="hidden-xs">Archivos</span>
                                </a>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <!-- FIN - Menu Tabs -->

                    <!-- INICIO - TAB -->
                    <div class="tab-content">
                        <!-- INICIO - Tabs Casos -->
                        <div class="tab-pane active" id="caso">
                            <div  id="formulario_vista" name="formulario_vista">
                                <!-- Acción del formulario en opcion  -->
                                <input type="hidden" id="caso_id_v" name="caso_id_v" value="0">
                                <input type="hidden" id="opcion" name="opcion" value="formulario_vista">
                                <div class="col-sm-12">
                                    <div class="card-box-form">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group has-success">
                                                    <label class="control-label" for="caso_numero_v">Número de Caso</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="caso_numero_v" name="caso_numero_v" maxlength="5">
                                                        <span class="input-group-btn">
                                                            <button onclick="javascript:buscarCaso();" class="btn btn-success waves-effect waves-light"><i class="ion-search" ></i></button>
                                                        </span>
                                                    </div>
                                                </div>  
                                            </div>
                                            <div class="col-md-4">
                                                <label>&nbsp;</label>
                                                <div class="form-group"> 
                                                    <button id="btnCasoAnterior" onclick="javascript:navegaCasos(-1);" class="btn btn-info waves-effect waves-light"><i class="ion-arrow-left-a" ></i></button>
                                                    <button id="btnCasoSiguiente" onclick="javascript:navegaCasos(1);" class="btn btn-info waves-effect waves-light"><i class="ion-arrow-right-a" ></i></button>
                                                    <?php
                                                        $casos_clonar = array_search('casos_clonar', array_column($permisos, 'permiso_variable'));
                                                        if (!empty($casos_clonar) || ($casos_clonar === 0)) { 
                                                    ?>
                                                        <button id="btn_clonar_caso" class="btn btn-primary waves-effect waves-light">Clonar</button>    
                                                    <?php } ?>
                                                    <?php
                                                        $casos_modificar = array_search('casos_modificar', array_column($permisos, 'permiso_variable'));
                                                        if (!empty($casos_modificar) || ($casos_modificar === 0)) { 
                                                    ?>
                                                        <button id="btn_modificar_caso" class="btn btn-danger waves-effect waves-light">Modificar</button>
                                                    <?php } ?>    
                                                    <?php
                                                        $casos_anular = array_search('casos_anular', array_column($permisos, 'permiso_variable'));
                                                        if (!empty($casos_anular) || ($casos_anular === 0)) { 
                                                    ?>
                                                        <button id="btn_anular_caso" class="btn btn-warning waves-effect waves-light">Anular Caso</button>
                                                        <button id="btn_rehabilitar_caso" class="btn btn-success waves-effect waves-light">Rehabilitar Caso</button>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="caso_casoEstado_nombre_v">Estado del Caso</label>
                                                    <input class="form-control" id="caso_casoEstado_nombre_v" name="caso_casoEstado_nombre_v" type="text" readonly="readonly">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="input-group">
                                                    <label class="control-label" for="caso_fechaAperturaCaso_v">Fecha de apertura</label>
                                                    <input class="form-control" id="caso_fechaAperturaCaso_v" name="caso_fechaAperturaCaso_v" type="text" readonly="readonly">
                                                </div>
                                            </div>  
                                            <div class="col-md-2">
                                                <div class="input-group">
                                                    <label class="control-label" for="caso_ultimaModificacion_v">Última modificación</label>
                                                    <input class="form-control" id="caso_ultimaModificacion_v" name="caso_ultimaModificacion_v" type="text" readonly="readonly">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label" for="caso_abiertoPor_id_v">Caso abierto por</label>
                                                    <input class="form-control" id="caso_abiertoPor_nombre_v" name="caso_abiertoPor_nombre_v" type="text" readonly="readonly">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label" for="caso_asignadoA_id_v">Caso asignado a</label>
                                                    <input class="form-control" id="caso_asignadoA_nombre_v" name="caso_asignadoA_nombre_v" type="text" readonly="readonly">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card-box-form">
                                        <div class="row">
                                            <h4 class="m-t-0 m-b-20 header-title"><b>Datos principales para crear el Caso</b></h4>
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label for="caso_numeroVoucher_v">Número de Voucher</label>
                                                    <input type="text" name="caso_numeroVoucher_v" class="form-control" id="caso_numeroVoucher_v" readonly="readonly">
                                                </div>
                                            </div>
                                            <div class="col-md-7">
                                                <div class="form-group">
                                                    <label class="control-label" for="caso_cliente_nombre_v">Cliente</label>
                                                    <input type="text" name="caso_cliente_nombre_v" class="form-control" id="caso_cliente_nombre_v" readonly="readonly">
                                                </div>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="form-group has-success">
                                                    <label class="control-label" for="caso_beneficiarioNombre_v">Nombre del Beneficiario</label>
                                                    <input type="text" name="caso_beneficiarioNombre_v" class="form-control" id="caso_beneficiarioNombre_v" readonly="readonly">
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="checkbox checkbox-primary">
                                                    <p class="text-muted m-b-30 font-13"></p>
                                                    <input name="caso_paxVIP_v"_v id="caso_paxVIP_v" type="checkbox" disabled>
                                                    <label for="caso_paxVIP_v">VIP</label>
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="checkbox checkbox-primary">
                                                    <p class="text-muted m-b-30 font-13"></p>
                                                    <input name="caso_legal_v"_v id="caso_legal_v" type="checkbox" disabled>
                                                    <label for="caso_legal_v">Legal</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label class="control-label" for="caso_fechaSiniestro_v">Fecha del Siniestro</label>
                                                    <input class="form-control" id="caso_fechaSiniestro_v" name="caso_fechaSiniestro_v" type="text" readonly="readonly">
                                                </div>
                                            </div>  
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label class="control-label" for="caso_tipoAsistencia_nombre_v">Tipo de Asistencia</label>
                                                    <input type="text" name="caso_tipoAsistencia_nombre_v" class="form-control" id="caso_tipoAsistencia_nombre_v" readonly="readonly">
                                                    <input type="hidden" name="caso_tipoAsistencia_clasificacion_id_v" class="form-control" id="caso_tipoAsistencia_clasificacion_id_v" readonly="readonly">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label" for="caso_fee_nombre_v">Fee</label>
                                                    <input type="text" name="caso_fee_nombre_v" class="form-control" id="caso_fee_nombre_v" readonly="readonly">
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <div class="checkbox checkbox-primary">
                                                        <p class="text-muted m-b-30 font-13"></p>
                                                        <input name="no_medical_cost_v" id="no_medical_cost_v" type="checkbox" disabled>
                                                        <label for="no_medical_cost_v">Asistencia sin costo</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="card-box-form">
                                        <div class="row">
                                            <h4 class="m-t-0 m-b-20 header-title"><b>Datos Territoriales</b></h4>
                                            <div class="col-md-6">
                                                <div class="form-group has-success">
                                                    <label class="control-label" for="caso_pais_nombre_v">Pais</label>
                                                    <input type="text" name="caso_pais_nombre_v" class="form-control" id="caso_pais_nombre_v" readonly="readonly">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group has-success">
                                                    <label class="control-label" for="caso_ciudad_id_v">Ciudad</label>
                                                    <input name="caso_ciudad_id_v" class="form-control" id="caso_ciudad_id_v"  readonly="readonly">
                                                    <input type="hidden" name="caso_ciudad_id_2_v" class="form-control" id="caso_ciudad_id_2_v" readonly="readonly">
                                                </div>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="form-group has-success">
                                                    <label class="control-label" for="caso_direccion_v">Dirección</label>
                                                    <input name="caso_direccion_v" class="form-control" id="caso_direccion_v" type="text" readonly="readonly">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group has-success">
                                                    <label class="control-label" for="caso_codigoPostal_v">Código Postal</label>
                                                    <input name="caso_codigoPostal_v" class="form-control" id="caso_codigoPostal_v" type="text" readonly="readonly">
                                                </div>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="form-group">
                                                    <label class="control-label" for="caso_hotel_v">Hotel</label>
                                                    <input name="caso_hotel_v" class="form-control" id="caso_hotel_v" type="text" readonly="readonly">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label" for="caso_habitacion_v">Habitación</label>
                                                    <input name="caso_habitacion_v" class="form-control" id="caso_habitacion_v" type="text" readonly="readonly">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="card-box-form">
                                        <div class="row">                    
                                            <h4 class="m-t-0 m-b-30 header-title"><b>Datos de Contacto</b></h4>                                
                                            <div class="col-md-6">
                                                <label class="control-label" for="grilla_telefonos_v">Teléfonos</label> 
                                                <div id="grilla_telefonos_v"></div>      
                                            </div>
                                            <div class="col-md-6">
                                                <label class="control-label">E-mail</label>
                                                <div class="row">
                                                    <div class="form-group col-md-8">
                                                        <input type="email" name="email_email_v" class="form-control"  id="email_email_v" placeholder="Ingrese el email" readonly="readonly">
                                                        <input type="hidden" name="email_id_v" class="form-control"  id="email_id_v" readonly="readonly">
                                                    </div>
                                                </div> 
                                            </div>
                                        </div> 
                                    </div>
                                </div>                                

                                <div class="col-sm-12">
                                    <div class="card-box-form">
                                        <div class="row">
                                            <h4 class="m-t-0 m-b-20 header-title"><b>Datos del Beneficiario</b></h4>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <label class="control-label" for="caso_beneficiarioNacimiento_v">Fecha de Nacimiento</label>
                                                    <input class="form-control" id="caso_beneficiarioNacimiento_v" name="caso_beneficiarioNacimiento_v" type="text" placeholder="dd-mm-aaaa" readonly="readonly">
                                                </div>
                                            </div>  
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <label class="control-label" for="caso_beneficiarioEdad_v">Edad</label>
                                                    <input class="form-control" name="caso_beneficiarioEdad_v" id="caso_beneficiarioEdad_v" type="text" readonly="readonly">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label" for="caso_beneficiarioGenero_nombre_v">Genero</label>
                                                    <input class="form-control" name="caso_beneficiarioGenero_nombre_v" id="caso_beneficiarioGenero_nombre_v" type="text" readonly="readonly">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label" for="caso_beneficiarioDocumento_v">Número de Documento</label>
                                                    <input name="caso_beneficiarioDocumento_v" class="form-control" id="caso_beneficiarioDocumento_v" type="text" readonly="readonly">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="card-box-form">
                                        <div class="row">
                                            <h4 class="m-t-0 m-b-20 header-title"><b>Datos del Producto</b></h4>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label" for="caso_producto_nombre_v">Producto</label>
                                                    <input name="caso_producto_nombre_v" class="form-control" id="caso_producto_nombre_v" type="text" readonly="readonly">
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <label class="control-label" for="caso_deducible_v">Deducible</label>
                                                    <input name="caso_deducible_v" class="form-control" id="caso_deducible_v" type="text" readonly="readonly">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label" for="caso_agencia_v">Agencia</label>
                                                    <input name="caso_agencia_v" class="form-control" id="caso_agencia_v" type="text" readonly="readonly">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label" for="caso_quienEmitioVoucher_v">Quien emitió el Voucher</label>
                                                    <input name="caso_quienEmitioVoucher_v" class="form-control" id="caso_quienEmitioVoucher_v" type="text" readonly="readonly">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <label class="control-label" for="caso_fechaSalida_v">Fecha de Salida</label>
                                                    <input class="form-control" id="caso_fechaSalida_v" name="caso_fechaSalida_v" type="text" placeholder="dd-mm-aaaa" readonly="readonly">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <label class="control-label" for="caso_fechaEmisionVoucher_v">Fecha emisión del Voucher</label>
                                                    <input class="form-control" id="caso_fechaEmisionVoucher_v" name="caso_fechaEmisionVoucher_v" type="text" placeholder="dd-mm-aaaa" readonly="readonly">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label" for="vigenciasVoucher_v">Vigencia del Voucher</label>
                                                    <div class="input-group input-daterange" id="date-range">
                                                        <input class="form-control" id="caso_vigenciaVoucherDesde_v" name="caso_vigenciaVoucherDesde_v" type="text" placeholder="dd-mm-aaaa" readonly="readonly">
                                                        <span class="input-group-addon bg-primary b-0 text-white">hasta</span>
                                                        <input class="form-control" id="caso_vigenciaVoucherHasta_v" name="caso_vigenciaVoucherHasta_v" type="text" placeholder="dd-mm-aaaa" readonly="readonly">
                                                    </div>
                                                </div>    
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12" id="porEnfermedad_v">
                                    <div class="card-box-form">
                                        <div class="row">
                                            <h4 class="m-t-0 m-b-20 header-title"><b>Datos por Enfermedad</b></h4>
                                            <div class="col-md-2">
                                                <div class="input-group">
                                                    <label class="control-label" for="caso_fechaInicioSintomas_v">Incio de Síntomas</label>
                                                    <input class="form-control" id="caso_fechaInicioSintomas_v" name="caso_fechaInicioSintomas_v" type="text" placeholder="dd-mm-aaaa" readonly="readonly">
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label class="control-label" for="caso_sintomas_v">Síntomas</label>
                                                    <textarea name="caso_sintomas_v" class="form-control" id="caso_sintomas_v" rows="3" readonly="readonly"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label class="control-label" for="caso_antecedentes_v">Antecedentes</label>
                                                    <textarea name="caso_antecedentes_v" class="form-control" id="caso_antecedentes_v" rows="3" readonly="readonly"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label" for="caso_diagnostico_id_v">Diagnóstico</label>
                                                    <input name="caso_diagnostico_id_v" class="form-control" id="caso_diagnostico_id_v" readonly="readonly">
                                                    <input type="hidden" name="caso_diagnostico_id_2_v" class="form-control" id="caso_diagnostico_id_2_v" readonly="readonly">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12" id="porDemoraVuelo_v">
                                    <div class="card-box-form">
                                        <div class="row">
                                            <h4 class="m-t-0 m-b-20 header-title"><b>Datos por Demora del Vuelo</b></h4>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label" for="caso_motivoVueloDemorado_v">Motivo</label>
                                                    <textarea name="caso_motivoVueloDemorado_v" class="form-control" id="caso_motivoVueloDemorado_v" readonly="readonly"></textarea>
                                                </div>
                                            </div>             
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12" id="porPerdidaEquipaje_v">
                                    <div class="card-box-form">
                                        <div class="row">
                                            <h4 class="m-t-0 m-b-20 header-title"><b>Datos por Pérdida de Equipaje</b></h4>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label" for="caso_companiaAerea_v">Nombre de la Compañía Aérea</label>
                                                    <input name="caso_companiaAerea_v" class="form-control" id="caso_companiaAerea_v" type="text" readonly="readonly">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label" for="caso_numeroVuelo_v">Número de Vuelo</label>
                                                    <input name="caso_numeroVuelo_v" class="form-control" id="caso_numeroVuelo_v" type="text" readonly="readonly">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label" for="caso_fechaPerdidaEquipaje_v">Fecha de la pérdida</label>
                                                    <input class="form-control" id="caso_fechaPerdidaEquipaje_v" name="caso_fechaPerdidaEquipaje_v" type="text" placeholder="dd-mm-aaaa" readonly="readonly">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label" for="caso_fechaRecuperacionEquipaje_v">Fecha de recuperación</label>
                                                    <input class="form-control" id="caso_fechaRecuperacionEquipaje_v" name="caso_fechaRecuperacionEquipaje_v" type="text" placeholder="dd-mm-aaaa" readonly="readonly">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label" for="caso_titularPIR_v">Titular de PIR</label>
                                                    <input name="caso_titularPIR_v" class="form-control" id="caso_titularPIR_v" type="text" readonly="readonly">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label" for="caso_numeroPIR_v">Número de PIR</label>
                                                    <input name="caso_numeroPIR_v" class="form-control" id="caso_numeroPIR_v" type="text" readonly="readonly">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label" for="caso_numeroEquipaje_v">Número de Equipaje</label>
                                                    <input name="caso_numeroEquipaje_v" class="form-control" id="caso_numeroEquipaje_v" type="text" readonly="readonly">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-sm-12">
                                    <div class="card-box-form">
                                        <div class="row">
                                            <h4 class="m-t-0 m-b-20 header-title"><b>Otros Datos</b></h4>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label" for="caso_observaciones_v">Observaciones</label>
                                                    <textarea name="caso_observaciones_v" class="form-control" id="caso_observaciones_v" rows="4" readonly="readonly"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label" for="caso_campoSupervisor_v">Campo Supervisor</label>
                                                    <textarea name="caso_campoSupervisor_v" class="form-control" id="caso_campoSupervisor_v" rows="4" readonly="readonly"></textarea>
                                                </div>
                                            </div>     
                                        </div>
                                    </div>
                                    <div class="card-box-form">
                                        <div class="form-group text-right m-b-0">
                                            <button id="btn_cancelar_vista" type="reset" class="btn btn-inverse waves-effect waves-light">Cerrar</button>
                                            <?php
                                                $casos_modificar = array_search('casos_modificar', array_column($permisos, 'permiso_variable'));
                                                if (!empty($casos_modificar) || ($casos_modificar === 0)) { 
                                            ?>
                                                <button type="button" id="btn_modificar_caso_footer" class="btn btn-danger waves-effect waves-light">Modificar Caso</button>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>  
                            </div>
                        </div>
                        <!-- FIN - Tab Casos -->

                        <!-- INICIO - Tab Comunicaciones -->
                        <?php
                            if (Usuario::puede("comunicaciones_ver") == 1) {
                        ?>
                            <div class="tab-pane" id="comunicaciones">   
                                <object type="text/html" id="pantalla_comunicaciones" width="100%" height="3000"></object>  
                            </div>
                        <?php } ?>
                        <!-- FIN - Tab Comunicaciones -->

                        <!-- INICIO - Tab Servicios -->
                        <?php
                            if (Usuario::puede("servicios_ver") == 1) {
                        ?>
                            <div class="tab-pane" id="servicios">   
                                <object type="text/html" id="pantalla_servicios" width="100%" height="3000"></object>  
                            </div>
                        <?php } ?>
                        <!-- FIN - Tab Servicios -->
                        
                        <!-- INICIO - Tab Reintegros -->
                        <?php
                            if (Usuario::puede("reintegros_ver") == 1) {
                        ?>
                            <div class="tab-pane" id="reintegros">
                                <object type="text/html" id="pantalla_reintegros" width="100%" height="3000"></object>  
                            </div>
                        <?php } ?>
                        <!-- FIN - Tab Reintegros -->

                        <!-- INICIO - Tab Archivos -->
                        <?php
                            if (Usuario::puede("archivos_ver") == 1) {
                        ?>
                            <div class="tab-pane" id="archivos">   
                                <object type="text/html" id="pantalla_archivos" width="100%" height="3000"></object>  
                            </div>
                        <?php } ?>
                        <!-- FIN - Tab Archivos -->
                    </div>
                    <!-- FIN - TAB -->
                </div>
            </div>
            <!-- FIN - Formulario Vista -->  


            <!-- INICIO - Formulario Modificar -->
            <div class="panel-body hidden" id="panel_formulario_modificacion">
                <div class="row">
                    <form  id="formulario_modificacion" name="formulario_modificacion">
                        <!-- Acción del formulario en opcion  -->
                        <input type="hidden" id="caso_id" name="caso_id" value="0">
                        <input type="hidden" id="caso_info_ws" name="caso_info_ws" readonly="readonly">
                        <input type="hidden" id="opcion" name="opcion" value="formulario_modificacion">
                        <div class="col-sm-12">
                            <div class="card-box-form">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group has-success">
                                            <label for="caso_numero">Número de Caso</label>
                                            <input type="text" name="caso_numero" class="form-control" id="caso_numero" readonly="readonly">
                                        </div>  
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="caso_casoEstado_nombre">Estado del Caso</label>
                                            <input class="form-control" id="caso_casoEstado_nombre" name="caso_casoEstado_nombre" type="text" readonly="readonly">
                                        </div>
                                    </div> 
                                    <div class="col-md-2">
                                        <div class="input-group">
                                            <label class="control-label" for="caso_ultimaModificacion">Última modificación</label>
                                            <input class="form-control" id="caso_ultimaModificacion" name="caso_ultimaModificacion" type="text" readonly="readonly">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label" for="caso_abiertoPor_nombre">Caso abierto por</label>
                                            <input class="form-control" id="caso_abiertoPor_nombre" name="caso_abiertoPor_nombre" type="text" readonly="readonly">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label" for="caso_asignadoA_nombre">Caso asignado a</label>
                                            <input class="form-control" id="caso_asignadoA_nombre" name="caso_asignadoA_nombre" type="text" readonly="readonly">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card-box-form">
                                <div class="row">
                                    <h4 class="m-t-0 m-b-20 header-title"><b>Datos principales para crear el Caso</b></h4>
                                    <div class="col-md-5">
                                        <div class="form-group has-warning">
                                            <label class="control-label" for="caso_numeroVoucher">Número de Voucher</label>
                                            <div class="input-group">
                                                <input type="text" name="caso_numeroVoucher" class="form-control" id="caso_numeroVoucher" maxlength="15">
                                                <span class="input-group-btn">
                                                    <button id="btn_ws" type="button" onclick="form_consulta_ws(2);" data-toggle="modal" data-target="#modal-ws" class="btn waves-effect waves-light btn-warning"><i class="ion-search"></i></button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="form-group has-warning">
                                            <label class="control-label" for="caso_cliente_id">Cliente</label>
                                            <input type="hidden" class="form-control" name="caso_cliente_id_input" id="caso_cliente_id_input" readonly="readonly">
                                            <select name="caso_cliente_id" class="form-control" id="caso_cliente_id"></select>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-group has-warning">
                                            <label class="control-label" for="caso_beneficiarioNombre">Nombre del Beneficiario</label>
                                            <input type="text" name="caso_beneficiarioNombre" class="form-control" id="caso_beneficiarioNombre" maxlength="100">
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="checkbox checkbox-primary">
                                            <p class="text-muted m-b-30 font-13"></p>
                                            <input name="caso_paxVIP" id="caso_paxVIP" type="checkbox">
                                            <label for="caso_paxVIP">VIP</label>
                                        </div>
                                    </div>
                                    <!-- Permiso para el marcar el caso como legal -->
                                    <?php
                                        $caso_legal = array_search('caso_legal', array_column($permisos, 'permiso_variable'));
                                        if (!empty($caso_legal) || ($caso_legal === 0)) { 
                                    ?>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <div class="checkbox checkbox-primary">
                                                <p class="text-muted m-b-30 font-13"></p>
                                                <input name="caso_legal" id="caso_legal" type="checkbox">
                                                <label for="caso_legal">Legal</label>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <div class="col-md-4">
                                        <div class="input-group has-warning">
                                            <label class="control-label" for="caso_fechaSiniestro">Fecha del Siniestro</label>
                                        </div>
                                        <div class="input-group has-warning col-md-12">
                                            <input type="text" name="caso_fechaSiniestro" class="form-control" id="caso_fechaSiniestro" placeholder="dd-mm-aaaa" readonly="readonly">
                                            <i class="ion-calendar form-control-feedback l-h-34"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group has-warning">
                                            <label class="control-label" for="caso_tipoAsistencia_id">Tipo de Asistencia</label>
                                            <select name="caso_tipoAsistencia_id" class="form-control" id="caso_tipoAsistencia_id"></select>
                                            <input type="hidden" name="caso_tipoAsistencia_clasificacion_id" class="form-control" id="caso_tipoAsistencia_clasificacion_id" readonly="readonly">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group has-warning">
                                            <label class="control-label" for="caso_fee_id">Fee</label>
                                            <select name="caso_fee_id" class="form-control" id="caso_fee_id"></select>
                                        </div>
                                    </div>
                                    <div class="col-md-5" id="no_medical_cost_container">
                                        <div class="form-group">
                                            <div class="checkbox checkbox-primary">
                                                <p class="text-muted m-b-30 font-13"></p>
                                                <input name="no_medical_cost" id="no_medical_cost" type="checkbox">
                                                <label for="no_medical_cost">Asistencia sin costo</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="card-box-form">
                                <div class="row">
                                    <h4 class="m-t-0 m-b-20 header-title"><b>Datos Territoriales</b></h4>
                                    <div class="col-md-6">
                                        <div class="form-group has-warning">
                                            <label class="control-label" for="caso_pais_id">Pais</label>
                                            <select name="caso_pais_id" class="form-control select2-active" id="caso_pais_id"></select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group has-warning">
                                            <label class="control-label" for="caso_ciudad_id">Ciudad</label>
                                            <input name="caso_ciudad_id" class="form-control" id="caso_ciudad_id" placeholder="Seleccione el País e ingrese las primeras 3 letras de la ciudad">
                                            <input type="hidden" name="caso_ciudad_id_2" class="form-control" id="caso_ciudad_id_2" readonly="readonly">
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <label class="control-label" for="caso_direccion">Dirección</label>
                                            <input name="caso_direccion" class="form-control" id="caso_direccion" type="text" maxlength="60">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label" for="caso_codigoPostal">Código Postal</label>
                                            <input name="caso_codigoPostal" class="form-control" id="caso_codigoPostal" type="text" maxlength="10">
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <label class="control-label" for="caso_hotel">Hotel</label>
                                            <input name="caso_hotel" class="form-control" id="caso_hotel" type="text" maxlength="40">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label" for="caso_habitacion">Habitación</label>
                                            <input name="caso_habitacion" class="form-control" id="caso_habitacion" type="text" maxlength="6">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="card-box-form">
                                <div class="row">                    
                                    <h4 class="m-t-0 m-b-30 header-title"><b>Datos de Contacto</b></h4>                                
                                    <div class="col-md-6">
                                        <label class="control-label" for="grilla_telefonos">Teléfonos</label>
                                        <div class="row">
                                            <div class="form-group col-md-3">
                                                <select class="form-control" name="caso_telefonoTipo_id" id="caso_telefonoTipo_id"></select>
                                            </div>
                                            <div class="form-group col-md-5">
                                                <input type="text" name="telefono_numero" class="form-control"  id="telefono_numero" placeholder="Ingrese el número" maxlength="22">
                                            </div>
                                            <div class="form-group col-md-2">
                                                <div class="checkbox checkbox-primary">
                                                    <input id="telefono_principal" type="checkbox">
                                                    <label for="telefono_principal">Principal</label>
                                                </div>
                                                <input type="hidden" name="telefono_id_m" class="form-control"  id="telefono_id_m" readonly="readonly">
                                            </div>
                                            <div class="form-group col-md-2">
                                                <a href='javascript:void(0)'> <i onclick='telefono_guardar()' class='fa fa-save' style="margin-top: 13px;"></i></a>
                                                <a href='javascript:void(0)'> <i onclick='telefono_limpiar()' class='fa fa-ban' style="margin-top: 13px;"></i></a>
                                            </div>
                                        </div> 
                                        <div id="grilla_telefonos"></div>      
                                    </div>
                                    <div class="col-md-6">
                                        <label class="control-label">E-mail</label>
                                        <div class="row">
                                            <div class="form-group col-md-8">
                                                <input type="email" name="email_email" class="form-control"  id="email_email" placeholder="Ingrese el email" maxlength="40">
                                                <input type="hidden" name="email_id" class="form-control"  id="email_id" placeholder="Ingrese el email" maxlength="40" readonly="readonly">
                                            </div>
                                        </div> 
                                    </div>
                                </div> 
                            </div>
                        </div>                                

                        <div class="col-sm-12">
                            <div class="card-box-form">
                                <div class="row">
                                    <h4 class="m-t-0 m-b-20 header-title"><b>Datos del Beneficiario</b></h4>                                
                                    <div class="col-md-3">
                                        <div class="input-group">
                                            <label class="control-label" for="caso_beneficiarioNacimiento">Fecha de Nacimiento</label>
                                        </div>
                                        <div class="input-group col-md-12">
                                            <input type="text" name="caso_beneficiarioNacimiento" class="form-control" id="caso_beneficiarioNacimiento" placeholder="dd-mm-aaaa" readonly="readonly">
                                            <i class="ion-calendar form-control-feedback l-h-34"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label class="control-label" for="caso_beneficiarioEdad">Edad</label>
                                            <input class="form-control" name="caso_beneficiarioEdad" id="caso_beneficiarioEdad" type="text" readonly="readonly">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label" for="caso_beneficiarioGenero_id">Genero</label>
                                            <select name="caso_beneficiarioGenero_id" class="form-control" id="caso_beneficiarioGenero_id">
                                                <option value="">Seleccione</option>
                                                <option value="1">Masculino</option>
                                                <option value="2">Femenino</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label" for="caso_beneficiarioDocumento">Número de Documento</label>
                                            <input name="caso_beneficiarioDocumento" class="form-control" id="caso_beneficiarioDocumento" type="text" maxlength="15">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="card-box-form">
                                <div class="row">
                                    <h4 class="m-t-0 m-b-20 header-title"><b>Datos del Producto</b></h4>
                                    <div class="col-md-3">
                                        <div class="form-group has-warning">
                                            <label class="control-label" for="caso_producto_id">Producto</label>
                                            <select name="caso_producto_id" class="form-control" id="caso_producto_id"></select>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group has-warning">
                                            <label class="control-label" for="caso_deducible">Deducible</label>
                                            <input name="caso_deducible" class="form-control" id="caso_deducible" type="text" maxlength="50">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label" for="caso_agencia">Agencia</label>
                                            <input name="caso_agencia" class="form-control" id="caso_agencia" type="text" maxlength="60">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label" for="caso_quienEmitioVoucher">Quien emitió el Voucher</label>
                                            <input name="caso_quienEmitioVoucher" class="form-control" id="caso_quienEmitioVoucher" type="text" maxlength="45">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label" for="caso_fechaSalida">Fecha de Salida</label>
                                            <div class="input-group col-md-12">
                                                <input class="form-control" id="caso_fechaSalida" name="caso_fechaSalida" type="text" placeholder="dd-mm-aaaa" readonly="readonly">
                                                <i class="ion-calendar form-control-feedback l-h-34"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label" for="caso_fechaEmisionVoucher">Fecha emisión del Voucher</label>
                                            <div class="input-group col-md-12">
                                                <input type="text" name="caso_fechaEmisionVoucher" class="form-control" placeholder="dd-mm-aaaa" id="caso_fechaEmisionVoucher" readonly="readonly">
                                                <i class="ion-calendar form-control-feedback l-h-34"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label" for="vigenciasVoucher">Vigencia del Voucher</label>
                                            <div class="input-group input-daterange" id="date-range">
                                                <input type="text" name="caso_vigenciaVoucherDesde" class="form-control" placeholder="dd-mm-aaaa" id="caso_vigenciaVoucherDesde" readonly="readonly">
                                                <span class="input-group-addon bg-primary b-0 text-white">hasta</span>
                                                <input type="text" name="caso_vigenciaVoucherHasta" class="form-control" placeholder="dd-mm-aaaa" id="caso_vigenciaVoucherHasta" readonly="readonly">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12" id="porEnfermedad">
                            <div class="card-box-form">
                                <div class="row">
                                    <h4 class="m-t-0 m-b-20 header-title"><b>Datos por Enfermedad</b></h4>
                                    <div class="col-md-2 has-warning">
                                        <label class="control-label" for="caso_fechaInicioSintomas">Incio de Síntomas</label>
                                        <div class="input-group">
                                            <input type="text" name="caso_fechaInicioSintomas" class="form-control" placeholder="dd-mm-aaaa" id="caso_fechaInicioSintomas" readonly="readonly">
                                            <i class="ion-calendar form-control-feedback l-h-34"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group has-warning">
                                            <label class="control-label" for="caso_sintomas">Síntomas</label>
                                            <textarea name="caso_sintomas" class="form-control" id="caso_sintomas" rows="3" maxlength="170"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="control-label" for="caso_antecedentes">Antecedentes</label>
                                            <textarea name="caso_antecedentes" class="form-control" id="caso_antecedentes" rows="3" maxlength="170"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label" for="caso_diagnostico_id">Diagnóstico</label>
                                            <input name="caso_diagnostico_id" class="form-control" id="caso_diagnostico_id" placeholder="Ingrese las primeras 3 letras del diagnóstico">
                                            <input type="hidden" name="caso_diagnostico_id_2" class="form-control" id="caso_diagnostico_id_2" readonly="readonly">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12" id="porDemoraVuelo">
                            <div class="card-box-form">
                                <div class="row">
                                    <h4 class="m-t-0 m-b-20 header-title"><b>Datos por Demora del Vuelo</b></h4>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label" for="caso_motivoVueloDemorado">Motivo</label>
                                            <textarea name="caso_motivoVueloDemorado" class="form-control" id="caso_motivoVueloDemorado" rows="3" maxlength="150"></textarea>
                                        </div>
                                    </div>             
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12" id="porPerdidaEquipaje">
                            <div class="card-box-form">
                                <div class="row">
                                    <h4 class="m-t-0 m-b-20 header-title"><b>Datos por Pérdida de Equipaje</b></h4>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label" for="caso_companiaAerea">Nombre de la Compañía Aérea</label>
                                            <input name="caso_companiaAerea" class="form-control" id="caso_companiaAerea" type="text" maxlength="45">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label" for="caso_numeroVuelo">Número de Vuelo</label>
                                            <input name="caso_numeroVuelo" class="form-control" id="caso_numeroVuelo" type="text" maxlength="10">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label" for="caso_fechaPerdidaEquipaje">Fecha de la pérdida</label>
                                            <div class="input-group col-md-12">
                                                <input type="text" name="caso_fechaPerdidaEquipaje" class="form-control" placeholder="dd-mm-aaaa" id="caso_fechaPerdidaEquipaje" readonly="readonly">
                                                <i class="ion-calendar form-control-feedback l-h-34"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label" for="caso_fechaRecuperacionEquipaje">Fecha de recuperación</label>
                                            <div class="input-group col-md-12">
                                                <input type="text" name="caso_fechaRecuperacionEquipaje" class="form-control" placeholder="dd-mm-aaaa" id="caso_fechaRecuperacionEquipaje" readonly="readonly">
                                                <i class="ion-calendar form-control-feedback l-h-34"></i>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label" for="caso_titularPIR">Titular de PIR</label>
                                            <input name="caso_titularPIR" class="form-control" id="caso_titularPIR" type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label" for="caso_numeroPIR">Número de PIR</label>
                                            <input name="caso_numeroPIR" class="form-control" id="caso_numeroPIR" type="text" maxlength="10">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label" for="caso_numeroEquipaje">Número de Equipaje</label>
                                            <input name="caso_numeroEquipaje" class="form-control" id="caso_numeroEquipaje" type="text" maxlength="10">
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="card-box-form">
                                <div class="row">
                                    <h4 class="m-t-0 m-b-20 header-title"><b>Otros Datos</b></h4>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label" for="caso_observaciones">Observaciones</label>
                                            <textarea name="caso_observaciones" class="form-control" id="caso_observaciones" rows="4" maxlength="300"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <?php
                                                $campo_supervisor = array_search('campo_supervisor', array_column($permisos, 'permiso_variable'));
                                                if (!empty($campo_supervisor) || ($campo_supervisor === 0)) { 
                                            ?>
                                            <label class="control-label" for="caso_campoSupervisor">Campo Supervisor</label>
                                            <textarea name="caso_campoSupervisor" class="form-control" id="caso_campoSupervisor" rows="4" maxlength="300"></textarea>
                                            <?php } ?>
                                        </div>
                                    </div>     
                                </div>
                            </div>
                            <div class="card-box-form">
                                <div class="form-group text-right m-b-0">
                                    <button type="reset" id="btn_cancelar_modificacion" class="btn btn-inverse waves-effect waves-light">Cancelar</button>
                                    <button type="submit" class="btn btn-success waves-effect waves-light">Guardar Modificaciones</button>                
                                </div>
                            </div>
                        </div>
                    </form>    
                </div>
            </div>
            <!-- FIN - Formulario Modificar -->    


            <!-- INICIO - Grilla  -->
            <div class="panel-body" id="panel_grilla">
                <!-- INICIO - Buscador  -->
                <div class='row'>
                    <div id='grilla_abm' class='col-sm-12'>
                        <div class='card-box table-responsive'>
                            <div class="col-md-2">
                                <label class="control-label" for="caso_numero">Caso numero</label>
                                <div class="form-group">
                                    <div class="form-group col-md-6">
                                        <input type="text" name="caso_numero_desde_b" class="form-control"  id="caso_numero_desde_b" placeholder="Desde" maxlength="6">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <input type="text" name="caso_numero_hasta_b" class="form-control"  id="caso_numero_hasta_b" placeholder="Hasta" maxlength="6">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="control-label" for="caso_fechaSiniestro">Fecha del siniestro</label>
                                <div class="form-group">
                                    <div class="form-group col-md-6">
                                        <input type="text" name="caso_fechaSiniestro_desde_b" class="form-control"  id="caso_fechaSiniestro_desde_b" placeholder="Desde">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <input type="text" name="caso_fechaSiniestro_hasta_b" class="form-control"  id="caso_fechaSiniestro_hasta_b" placeholder="Hasta">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="control-label" for="caso_estado_id_b">Estado del caso</label>
                                <div class="form-group">
                                    <select name="caso_estado_id_b" class="form-control" id="caso_estado_id_b"></select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="control-label" for="caso_tipoAsistencia_id_b">Tipo de asistencia</label>
                                <div class="form-group">    
                                    <select name="caso_tipoAsistencia_id_b" class="form-control" id="caso_tipoAsistencia_id_b"></select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label" for="caso_estado_id_b">Número de Voucher</label>
                                <div class="form-group">
                                    <input type="text" name="caso_voucher_b" id="caso_voucher_b" class="form-control" maxlength="20">
                                </div>
                            </div>
                            <div class="col-md-5">
                                <label class="control-label" for="caso_beneficiario_b">Beneficiario</label>
                                <div class="form-group">
                                   <input type="text"  name="caso_beneficiario_b" class="form-control"  id="caso_beneficiario_b" maxlength="70">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="control-label" for="caso_estado_id_b">Agencia</label>
                                <div class="form-group">
                                    <input type="text" name="caso_agencia_b" id="caso_agencia_b" class="form-control" maxlength="60">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <div class="form-group">  
                                    <button class="btn btn-primary waves-effect waves-light" type="button" onclick='grilla_listar()'>Buscar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- INICIO - Grillas de resultados  -->
                <div id="grilla_info"></div>
                <div id="grilla_caso"></div>   
                <!-- FIN - Grillas de resultados  -->
            </div>
            <!-- FIN - Buscador  -->
    </div>
    <!-- FIN - Panel Formularios y Grilla  -->

    <?php require '../includes/modal_eliminacion.php' ?>
    <?php require '../includes/modal_anularCaso.php' ?>
    <?php require '../includes/footer_start.php' ?>
    <?php require '../includes/footer_notificaciones.php' ?>
    <?php require '../includes/footer_datatable.php' ?>
    <?php require '../includes/footer_validacion.php' ?>
    <script src="caso.js?random=<?php echo uniqid(); ?>"></script>
    <script src="ws.js?random=<?php echo uniqid(); ?>"></script>
    <script src="../plugins/notifyjs/dist/notify.min.js"></script>
    <script src="../plugins/notifications/notify-metro.js"></script>
    <?php require '../includes/footer_end.php' ?>

    <!-- Para mostrar el formulario alta o ubicarse en un caso puntual -->
    <?php 
        $ncase = isset($_GET["ncase"])?$_GET["ncase"]:''; // Trae siempre 1 para indicar que es un nuevo caso
        $vcase = isset($_GET["vcase"])?$_GET["vcase"]:''; // Trae el id de caso para mostrarlo
        
        if ($ncase == 1) { 
    ?>
        <script>preparar_alta_caso();</script>
    <?php } else if ($vcase > 0) { ?>
        <script>
            let caso_id = '<?php echo $vcase; ?>';
            formulario_lectura(caso_id);
        </script>
    <?php } ?>
<?php } ?>