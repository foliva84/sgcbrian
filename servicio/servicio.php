    <?php 
        require '../seguridad/seguridad.php';
            
        $servicios_ver = array_search('servicios_ver', array_column($permisos, 'permiso_variable'));
        if (empty($servicios_ver) && ($servicios_ver !== 0)) {
            header("location:../_errores/401.php");
        } else {

        $pagina = "Servicio"; 
        $caso_id = isset($_GET["caso_id"])?$_GET["caso_id"]:'';
        $modalComunication = isset($_GET["modalComunication"])?$_GET["modalComunication"]:'';
        
        /*
            Consulta si cuando se creo el caso, el voucher tenia cobertura:
            1- Si el resultado es 0, el caso se creo estando EN VIGENCIA
            2- Si el resultado es 1, el caso se creo estando FUERA DE VIGENCIA
        */
        $caso_cobertura = Caso::consultar_caso_enVigencia($caso_id);

        /* 
            Consulta la vigencia del voucher, respecto a la fecha actual
            1- Si el resultado es 0, el caso sigue EN VIGENCIA
            2- Si el resultado es 1, el caso esta FUERA DE VIGENCIA
        */
        $vigencia_actual = Caso::consultar_vigencia_actual($caso_id);

        /* 
            Consulta el estado del caso respecto a si tiene info MANUAL o del WS
            1- Si el resultado es 0, el caso se cargo MANUAL
            2- Si el resultado es 1, el caso se cargo con info del WS
        */
        $info_ws = Caso::caso_info_ws($caso_id);
    ?>


    <?php require '../includes/header_start.php'; ?>
    <?php require '../includes/header_end_sin_menu.php'; ?>
    <link href="servicio.css" rel="stylesheet" type="text/css">
    <?php require '../includes/header_pagina.php'; ?>

    
    <!-- Inicio - Panel Formularios y Grilla  -->
     <div class="panel panel-default users-content">
            <!-- INICIO - Formulario GOP -->
            <div class="panel-body hidden" id="panel_formulario_gop">
                <div class="row">
                    <form id="formulario_gop" name="formulario_gop">
                        <!-- Acción del formulario en opcion  -->
                        <input type="hidden" id="opcion" name="opcion" value="formulario_gop">
                        <div class="col-sm-12"> 
                            <div class="col-sm-6">
                                <div class="card-box">
                                    <h4 class="m-t-0 m-b-20 header-title"><b>Datos del Caso</b></h4>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group has-success">
                                                <label class="control-label" for="numero_caso_g">Número de Caso</label>
                                                <input type="text" name="numero_caso_g" class="form-control" id="numero_caso_g" readonly="readonly">
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label class="control-label" for="paciente_g">Paciente</label>
                                                <input type="text" name="paciente_g" class="form-control" id="paciente_g" readonly="readonly">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label" for="nVoucher_g">Número de Voucher</label>
                                                <input type="text" name="nVoucher_g" class="form-control" id="nVoucher_g" readonly="readonly">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label" for="fecha_nacimiento_g">Fecha de Nacimiento</label>
                                                <input type="text" name="fecha_nacimiento_g" class="form-control" id="fecha_nacimiento_g" readonly="readonly">
                                                <input type="hidden" name="fecha_nacimiento_ansi_g" class="form-control" id="fecha_nacimiento_ansi_g">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label" for="edad_g">Edad</label>
                                                <input type="text" name="edad_g" class="form-control" id="edad_g" readonly="readonly">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label" for="sintomas_g">Síntomas</label>
                                                <textarea name="sintomas_g" class="form-control" id="sintomas_g" rows="3" readonly="readonly"></textarea>
                                            </div>
                                        </div>
                                    </div> 
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="card-box">
                                    <h4 class="m-t-0 m-b-20 header-title"><b>Datos de Contacto</b></h4>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label" for="pais_g">País</label>
                                                <input type="text" name="pais_g" class="form-control" id="pais_g" readonly="readonly">
                                                <input type="hidden" name="pais_id_g" class="form-control" id="pais_id_g">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label" for="ciudad_g">Ciudad</label>
                                                <input type="text" name="ciudad_g" class="form-control" id="ciudad_g" readonly="readonly">
                                                <input type="hidden" name="ciudad_id_g" class="form-control" id="ciudad_id_g">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label" for="telefono_g">Teléfono principal</label>
                                                <input type="text" name="telefono_g" class="form-control" id="telefono_g" readonly="readonly">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label class="control-label" for="direccion_g">Dirección</label>
                                                <input type="text" name="direccion_g" class="form-control" id="direccion_g" readonly="readonly">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label" for="cp_g">Código Postal</label>
                                                <input type="text" name="cp_g" class="form-control" id="cp_g" readonly="readonly">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label" for="telefonosSec_g">Teléfonos secundarios</label>
                                                <input type="text" name="telefonosSec_g" class="form-control" id="telefonosSec_g" readonly="readonly">
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <label class="control-label" for="hotel_g">Hotel</label>
                                                <input type="text" name="hotel_g" class="form-control" id="hotel_g" readonly="readonly">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label" for="habitacion_g">Habitacion</label>
                                                <input type="text" name="habitacion_g" class="form-control" id="habitacion_g" readonly="readonly">
                                            </div>
                                        </div>
                                    </div> 
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="card-box">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="col-md-12">
                                                <div class="form-group has-success">
                                                    <label class="control-label" for="prestador_g">Prestador</label>
                                                    <input type="text" name="prestador_g" class="form-control" id="prestador_g" readonly="readonly">
                                                    <input type="hidden" name="prestador_id_g" class="form-control" id="prestador_id_g">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group has-warning">
                                                    <label class="control-label" for="email_g">E-mail Prestador</label>
                                                    <i class='fa fa-info-circle' data-toggle='tooltip' data-placement='top' title='Para enviar a mas de un destinatario, debe separar los correos con COMA ","'></i>
                                                    <textarea type="text" name="email_g" class="form-control" id="email_g" rows="2" maxlength="150"></textarea>
                                                </div>
                                            </div>
                                        </div>   
                                        <div class="col-sm-8">
                                            <div class="col-md-12">
                                                <div class="form-group has-warning">
                                                    <label class="control-label" for="observaciones_g">Observaciones</label>
                                                    <textarea type="text" name="observaciones_g" class="form-control" id="observaciones_g" rows="6" maxlength="1800"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div> 
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="card-box">                        
                                    <div class="form-group text-right m-b-0">
                                        <button type="reset" id="btn_cancelar_gop" class="btn btn-default waves-effect waves-light m-l-5">Cancelar</button>
                                        <button id="btn_enviar_gop_esp" onclick="javascript:enviar_gop(1);" class="btn btn-primary waves-effect waves-light">Enviar GOP (ESP)</button>
                                        <button id="btn_enviar_gop_eng" onclick="javascript:enviar_gop(2);" class="btn btn-primary waves-effect waves-light">Enviar GOP (ENG)</button>
                                    </div>
                                </div>
                            </div>                            
                        </div>
                    </form>   
                </div>
            </div>
            <!-- FIN - Formulario GOP -->

            <!-- INICIO - Modal  ITEMS de Factura Asociados a Servicios -->
            <div id="modal_ServiciosConItemsFactura" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-modal_ServiciosConItemsFactura" aria-hidden="true" style="display: none;">
                <div class="modal-dialog" style="width:70%;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <b>ITEM de Factura asociado al Servicio</b>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body">
                            <div id="grilla_servicios_itemsFactura"></div>    
                        </div>
                    </div>
                </div>
            </div>
            <!-- FIN - Modal  ITEMS de Factura Asociados a Servicios -->

            <!-- Inicio - Panel Formulario Alta  -->
            <div class="panel-body" id="panel_formulario_alta">
                <div class="row">
                    <form  id="formulario_alta" name="formulario_alta">
                        <!-- Acción del formulario en opcion  -->
                        <input type="hidden" id="opcion" name="opcion" value="formulario_alta">
                        <input type="hidden" id="caso_id_n" name="caso_id_n" value="<?php echo $caso_id?>">
                        <?php if($modalComunication!=''){?>
                            <input class="form-control" id="modalComunication" name="modalComunication" type="hidden" value="<?php echo $modalComunication;?>">
                        <?php }?>
                        <?php
                            // Permiso para el Alta de Servicios
                            $servicios_alta = array_search('servicios_alta', array_column($permisos, 'permiso_variable'));
                            if (!empty($servicios_alta) || ($servicios_alta === 0)) { 
                        ?>
                            <?php
                                /* 
                                    Para la creacion de un Servicio valida:

                                    1 - Si el caso fue creado fuera de cobertura.
                                    2 - Si la fecha actual este fuera de los rangos de cobertura
                                    3 - Si el caso se cargo de forma MANUAL y aún no se pusieron los datos del WS 
                                    4 - En caso que estas condiciones se cumplan, el usuario debera tener los permisos correspondientes para crear el servicio
                                */
                                if  (
                                        ($info_ws == 1 || (Usuario::puede("carga_servicio_casoManual"))) &&
                                        (($caso_cobertura == 0 && $vigencia_actual == 0) || (($caso_cobertura == 1 || $vigencia_actual == 1) && (Usuario::puede("carga_servicio_casoFueraCobertura"))))
                                    ) {
                            ?>
                                <div class="col-lg-12">
                                    <div class="card-box"> 
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group has-warning">
                                                    <label class="control-label" for="servicio_prestador_id_n">Prestador</label>
                                                    <div class="input-group">
                                                        <input type="text" name="servicio_prestador_nombre_n" class="form-control" id="servicio_prestador_nombre_n" placeholder="Seleccione un prestador" readonly="readonly">
                                                        <input type="hidden" name="servicio_prestador_id_n" class="form-control" id="servicio_prestador_id_n" readonly="readonly">
                                                        <span class="input-group-btn">
                                                            <button onclick="javascript:buscar_prestador_formulario(1)" type="button" class="btn waves-effect waves-light btn-warning"><i class="ion-search" ></i></button>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2 has-warning">
                                                <div class="form-group">
                                                    <label for="servicio_practica_id_n">Practica</label>
                                                    <select name="servicio_practica_id_n" class="form-control" id="servicio_practica_id_n"></select>
                                                </div>
                                            </div>
                                            <div class="col-md-2 has-warning">                                     
                                                <div class="form-group">
                                                    <label for="servicio_presuntoOrigen_n">Presunto Origen</label>
                                                    <input class="form-control" id="servicio_presuntoOrigen_n" name="servicio_presuntoOrigen_n" type="text" maxlength="13">
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-group has-warning">
                                                    <label for="servicio_moneda_id_n">Moneda</label>
                                                    <select id="servicio_moneda_id_n" name="servicio_moneda_id_n" class="form-control"></select>
                                                </div>
                                            </div>
                                            <div class="col-md-1 has-warning">                                         
                                                <div class="form-group">
                                                    <label for="servicio_tipoCambio_n">T/C</label>
                                                    <input class="form-control" id="servicio_tipoCambio_n" name="servicio_tipoCambio_n" type="text">
                                                </div>
                                            </div>   
                                            <div class="col-md-2 has-warning">                                         
                                                <div class="form-group">
                                                    <label for="servicio_presuntoUSD_n">Presunto USD</label>
                                                    <input class="form-control" id="servicio_presuntoUSD_n" name="servicio_presuntoUSD_n" type="text">
                                                </div>
                                            </div>
                                            <!-- 
                                                Para mostrar o no el campo 'servicio_justificacion_n', valida:
                                                1 - Si el caso fue creado fuera de cobertura
                                                2 - Si la fecha actual este fuera de los rangos de cobertura
                                            -->
                                            <?php if ($caso_cobertura == 1 || $vigencia_actual == 1) { ?>
                                                <div class="col-md-10">
                                                    <div class="form-group">
                                                        <input class="form-control" id="servicio_justificacion_n" name="servicio_justificacion_n" type="text" maxlength="120" placeholder="Ingrese el motivo de la carga del servicio">
                                                    </div>
                                                </div>
                                            <?php } else { ?>
                                                <div class="col-md-10"></div>
                                            <?php } ?>
                                            <div class="col-md-2">
                                                <div class="form-group text-right m-b-0">
                                                    <button type="reset" id="btn_cancelar_nuevo" class="btn btn-default waves-effect waves-light m-l-5">Cancelar</button>
                                                    <button id="btn_guardar_nuevo" class="btn btn-primary waves-effect waves-light" type="submit">Guardar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                            <?php } else { ?>
                                    <div class="col-lg-12">
                                        <div class="card-box">
                                            <?php if ($caso_cobertura == 1 && Usuario::puede("carga_servicio_casoFueraCobertura") == 0) { ?>
                                                <p class="text-center">El Caso fue creado con un Voucher fuera de cobertura, por lo que <b>solo usuarios autorizados pueden cargar servicios</b>.</p>
                                            <?php } else if ($vigencia_actual == 1 && Usuario::puede("carga_servicio_casoFueraCobertura") == 0) { ?>
                                                <p class="text-center">El Voucher esta Fuera de Cobertura, por lo que <b>solo usuarios autorizados pueden cargar servicios</b>.</p>
                                            <?php } else if ($info_ws == 0) { ?>
                                                <p class="text-center">El Caso se cargo de forma Manual, por lo que <b>solo usuarios autorizados pueden cargar servicios</b>.</p>
                                            <?php } ?>    
                                        </div>
                                    </div>    
                            <?php } ?>
                        <?php } ?>
                    </form>
                </div>
            </div>
            <!-- Fin - Panel Formulario Alta  -->

            <!-- Inicio - Panel Formulario Modificacion  -->
            <?php //if($modalComunication==''){?>
                <div class="panel-body hidden" id="panel_formulario_modificacion">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card-box">
                                <h4 class="m-t-0 header-title"><b>Formulario Modificación de Servicio</b></h4>
                                <p></p>
                                <form  id="formulario_modificacion" name="formulario_modificacion">
                                    <!-- Acción del formulario en opcion y id de la servicio a modificar -->
                                    <input type="hidden" id="servicio_id" name="servicio_id" value="0" readonly="readonly">
                                    <input type="hidden" id="caso_id" name="caso_id" value="<?php echo $caso_id?>" readonly="readonly">
                                    <input type="hidden" id="opcion" name="opcion" value="formulario_modificacion" readonly="readonly">
                                    <div class="col-md-4">
                                        <div class="form-group has-warning">
                                            <label class="control-label" for="servicio_prestador_nombre">Prestador</label>
                                            <div class="input-group">
                                                <input name="servicio_prestador_nombre" class="form-control" id="servicio_prestador_nombre" placeholder="Seleccione un prestador" readonly="readonly">
                                                <input type="hidden" name="servicio_prestador_id" class="form-control" id="servicio_prestador_id" readonly="readonly">
                                                <span class="input-group-btn">
                                                    <button id="btn_buscar_prestador_formularioModificar" onclick="javascript:buscar_prestador_formulario(2)" type="button" class="btn waves-effect waves-light btn-warning"><i class="ion-search" ></i></button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group has-warning">
                                            <label for="servicio_practica_id">Practica</label>
                                            <select id="servicio_practica_id" name="servicio_practica_id" class="form-control"></select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group has-warning">
                                            <label for="servicio_presuntoOrigen">Presunto Origen</label>
                                            <input class="form-control" id="servicio_presuntoOrigen" name="servicio_presuntoOrigen" maxlength="13">
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group has-warning">
                                            <label for="servicio_moneda_id">Moneda</label>
                                            <select id="servicio_moneda_id" name="servicio_moneda_id" class="form-control"></select>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group has-warning">
                                            <label for="servicio_tipoCambio">T/C</label>
                                            <input class="form-control" id="servicio_tipoCambio" name="servicio_tipoCambio">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group has-warning">
                                            <label for="servicio_presuntoUSD">Presunto USD</label>
                                            <input class="form-control" id="servicio_presuntoUSD" name="servicio_presuntoUSD" type="text">
                                        </div>
                                    </div>
                                    <!-- 
                                        Para mostrar o no el campo 'servicio_justificacion', valida:
                                        1 - Si el caso fue creado fuera de cobertura
                                        2 - Si la fecha actual este fuera de los rangos de cobertura
                                    -->
                                    <?php if ($caso_cobertura == 1 || $vigencia_actual == 1) { ?>
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <?php
                                                    // Si el usuario no puede cargar servicios en un caso fuera de cobertura, el campo justificacion estara en modo disabled
                                                    if (Usuario::puede("carga_servicio_casoFueraCobertura") == 1) {
                                                ?>
                                                    <input class="form-control" id="servicio_justificacion" name="servicio_justificacion" type="text" maxlength="120" placeholder="Ingrese el motivo de la carga del servicio">
                                                <?php } else {?>
                                                    <input class="form-control" id="servicio_justificacion" name="servicio_justificacion" type="text" readonly="true">
                                                <?php } ?>
                                            </div>
                                        </div>
                                    <?php } else { ?>
                                        <div class="col-md-9"></div>
                                    <?php } ?>
                                    <div class="form-group text-right m-b-0">
                                        <?php
                                            // Valida si el usuario tiene permisos para autorizar servicios
                                            if (Usuario::puede("servicios_autorizar") == 1) {
                                        ?>
                                            <input name="servicio_autorizado" id="servicio_autorizado" type="checkbox">
                                            <label for="servicio_autorizado">Autorizar</label>
                                        <?php } ?>  
                                        <button type="reset" id="btn_cancelar" class="btn btn-default waves-effect waves-light m-l-5">Cancelar</button>
                                        <button class="btn btn-primary waves-effect waves-light" type="submit">Guardar</button>  
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div> 
            <?php //}?>
            <!-- Fin - Panel Formulario Modificacion  -->

            <!-- INICIO - Grilla Busqueda Prestador -->
            <div class="panel-body hidden" id="panel_grilla_busquedaPrestador">   
                <div class="card-box">
                    <div class="row">
                        <div class="col-md-12">
                            <label class="control-label" for="caso_pais_id">Datos generales</label>
                            <div class="form-group">
                                <div class="form-group col-md-5">
                                    <input type="text" name="prestador_nombre_buscar" class="form-control" id="prestador_nombre_buscar" placeholder="Buscar por nombre" >
                                </div>
                                <div class="form-group col-md-5">
                                    <select name="prestador_tipoPrestador_id_b" class="form-control" id="prestador_tipoPrestador_id_b"></select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label class="control-label" for="caso_pais_id">Territorialidad</label>
                            <div class="form-group">
                                <div class="form-group col-md-5">
                                    <select name="prestador_pais_id_b" class="form-control" id="prestador_pais_id_b"></select>
                                </div>
                                <div class="form-group col-md-5">
                                    <input name="prestador_ciudad_id_b" class="form-control" id="prestador_ciudad_id_b" placeholder="Seleccione el País e ingrese las primeras 3 letras de la ciudad">
                                    <input type="hidden" name="prestador_ciudad_id_b_2" class="form-control" id="prestador_ciudad_id_b_2">
                                </div>
                                <div class="form-group col-md-1">
                                    <a href='javascript:void(0)' id='btn_cancelar_busqueda_prestador_alta'> <i onclick='btn_cancelar_busqueda_prestador(1)' class='md md-cancel' style="margin-top: 8px;" data-toggle="tooltip" data-placement="top" title="Cancelar"></i></a>
                                    <a href='javascript:void(0)' id='btn_cancelar_busqueda_prestador_modificacion'> <i onclick='btn_cancelar_busqueda_prestador(2)' class='md md-cancel' style="margin-top: 8px;" data-toggle="tooltip" data-placement="top" title="Cancelar"></i></a>
                                    <a href='javascript:void(0)' id='btn_listar_prestador_alta'> <i onclick='grilla_listar_prestador(1)' class='fa fa-search' style="margin-top: 8px;" data-toggle="tooltip" data-placement="top" title="Buscar"></i></a>
                                    <a href='javascript:void(0)' id='btn_listar_prestador_modificacion'> <i onclick='grilla_listar_prestador(2)' class='fa fa-search' style="margin-top: 8px;" data-toggle="tooltip" data-placement="top" title="Buscar"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="grilla_prestador"></div>      
            </div>
            <!-- FIN - Grilla Busqueda Prestador -->

            <!-- INICIO - Grilla de Servicios -->
            <?php //if($modalComunication==''){?>
                <div class="panel-body" id="panel_grilla">        
                    <div id="grilla_servicio"></div>      
                </div>
            <?php //}?>
            <!-- FIN - Grilla de Servicios -->
            
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
    <script src="../assets/js/jquery.number.min.js"></script>

    <!-- Plugin para Switch -->
    <script src="../plugins/switchery/switchery.min.js"></script>
    <!-- Plugin para Select -->
    <script src="../plugins/select2/select2.min.js" type="text/javascript"></script>

    <!-- Peity charts -->
    <script src="../plugins/peity/jquery.peity.min.js"></script>
    <script src="../assets/js/jquery.core.js"></script>
    <script src="../assets/js/jquery.app.js"></script>

    <!-- Plugin para Fechas -->
    <script src="../assets/js/datePickerES.js"></script>

    <!-- Plugin para el formateo de importes -->
    <script src="../assets/js/jquery.mask.min.js" type="text/javascript"></script>
    
    <?php require '../includes/footer_notificaciones.php' ?>
    <?php require '../includes/footer_validacion.php' ?>

    <script src="servicio.js?random=<?php echo uniqid(); ?>"></script>
    </body>
    </html>
<?php } ?>