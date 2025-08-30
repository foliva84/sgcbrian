// Funciones que se van a ejecutar en la primer carga de la página.
$().ready(function(){
    
    listarPrestadores_buscarFactura();
    
    // Acomoda los paneles
    $('#panel_formulario_alta').slideUp();
    $('#panel_grilla').slideDown();
    
  
});

// Formularios
// 
// Formulario de ALTA de una FACTURA
$("#formulario_alta").validate({  
    ignore: [],
    rules: {
        factura_prestador_nombre_n: {
            required: true
        },
        factura_prestador_id_n: { // Para cuando el id de prestador no se carga correctamente en 'factura_prestador_id_n'
            required: function(e){
                return $("#factura_prestador_nombre_n").val() !== "";
            }
        },
        factura_numero_n: {
            required: true,
            remote: {
                url: "facturacion_cb.php",
                type: "POST",
                data: {
                    factura_prestador_id_n: function() {
                        return $("#factura_prestador_id_n").val();
                    },
                    opcion: 'factura_existente_alta'
                }
            }
        },
        factura_fechaEmision_n: {
            required: true
        },
        factura_fechaRecepcion_n: {
            required: true
        },
        factura_fechaVencimiento_n: {
            required: true
        }
    },
    messages: {
        factura_prestador_nombre_n: {
            required: "Por favor seleccione un prestador"
        },
        factura_prestador_id_n: {
            required: "El prestador no se cargó correctamente. Vuelva a seleccionarlo."
        },
        factura_numero_n: {
            required: "Por favor ingrese el número de la factura"
        },
        factura_fechaEmision_n: {
            required: "Por favor ingrese la fecha de emisión de la factura"
        },
        factura_fechaRecepcion_n: {
            required: "Por favor ingrese la fecha de recepción de la factura"
        },
        factura_fechaVencimiento_n: {
            required: "Por favor ingrese la fecha de vencimiento de la factura"
        }
    },
    
    submitHandler: function (form) {
        
        $.ajax({
            type: "POST",
            dataType:'JSON',
            url: "facturacion_cb.php",
            data: $(form).serialize(),
            success: function (data) {
                
                if (data != false) {
                    $.Notification.autoHideNotify('success', 'top center', 'La factura se grabó exitosamente...', 'Los cambios han sido guardados.');
                
                    // Limpia formulario alta
                    $("#formulario_alta").clearValidation();
                    $('#formulario_alta')[0].reset();
                    
                    // Llamar a formulario_lectura para mostrar la factura recien cargada
                    factura_id = data.replace(/"/g,"");
                    formulario_lectura(factura_id);
                    
                    // Desbloquea el boton de nueva factura
                    $("#btn_nueva_factura").prop("disabled", false);
                } else {
                    $.Notification.autoHideNotify('error', 'top center', 'La factura que intenta ingresar ya existe...', 'Ingrese otro numero de factura');
                }    

            },
            error: function (xhr, ajaxOptions, thrownError) {
                $.Notification.autoHideNotify('error', 'top center', 'Error...', 'Reporte del error. ' + xhr.responseText);
            }
        });
        
        // Previene que el formulario haga POST después de la validación, ya que ejecuto el guardado por Ajax.
        return false;
    }
});

// Formulario de LECTURA de una FACTURA
function formulario_lectura(factura_id) {
    
    // scrollea la pagina hasta arriba al cargar la funcion
    window.scrollTo(0,0);
    
    // Le saca la propiedad hidden a la clase del menu tabs
    $('#menu_tabs').removeClass('hidden');
    
    // Carga los formularios con info de los distintos tabs para reintegros
    $('#pantalla_comunicacionesF').attr('data','../comunicacionFacturacion/comunicacionF.php?factura_id=' + factura_id);
    $('#pantalla_archivos').attr('data','../archivos_facturas/archivo.php?factura_id_archivo=' + factura_id);
    
    let parametros = {
        "factura_id": factura_id,   
        "opcion": 'formulario_lectura'
    };
    
    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'facturacion_cb.php',
        data: parametros,
        success:function(data){
            
            $('#factura_id_v').val(data.factura_id);
            $('#factura_prestador_id_v').val(data.idPrestador);
            $('#factura_prestador_nombre_v').val(data.prestador);
            $('#factura_numero_v').val(data.numeroFactura);
            $('#factura_estado_v').val(data.estadoFactura);
            $('#fci_numeroFactura').val(data.numeroFactura);
            $('#factura_prioridad_id_v').val(data.prioridadFactura);
            $('#factura_fechaIngresoSistema_v').val(data.fechaIngresoSistemaFactura);
            $('#factura_fechaEmision_v').val(data.fechaEmisionFactura);
            $('#factura_fechaRecepcion_v').val(data.fechaRecepcionFactura);
            $('#factura_fechaVencimiento_v').val(data.fechaVencimientoFactura);
            $('#factura_observaciones_v').val(data.observacionesFactura);
         
            // IMPLEMENTAR: Deshabilita el boton modificar si la factura avanzo en el proceso de aprobacion
            let estado_factura_id = data.estadoFacturaId;
            
            // Llama a la funcion que preparar el formulario
            preparar_formulario_lectura(factura_id, estado_factura_id);
            preparar_formulario_altaFci();
            
            // Importante para que luego de la carga dinámica vuelvan a funcionar los tooltips
            $('[data-toggle="tooltip"]').tooltip();
        }
    });
};

// Formulario de MODIFICACION de una FACTURA
$("#formulario_modificacion").validate({  
    ignore: [],
    rules: {
        factura_prestador_nombre_m: {
            required: true
        },
        factura_prestador_id_m: { // Para cuando el id de prestador no se carga correctamente en 'factura_prestador_id_m'
            required: function(e){
                return $("#factura_prestador_nombre_m").val() !== "";
            }
        },
        factura_numero_m: {
            required: true
        },
        factura_fechaEmision_m: {
            required: true
        },
        factura_fechaRecepcion_m: {
            required: true
        },
        factura_fechaVencimiento_m: {
            required: true
        }
    },
    messages: {
        factura_prestador_nombre_m: {
            required: "Por favor seleccione un prestador"
        },
        factura_prestador_id_m: {
            required: "El prestador no se cargó correctamente. Vuelva a seleccionarlo."
        },
        factura_numero_m: {
            required: "Por favor ingrese el número de la factura"
        },
        factura_fechaEmision_m: {
            required: "Por favor ingrese la fecha de emisión de la factura"
        },
        factura_fechaRecepcion_m: {
            required: "Por favor ingrese la fecha de recepción de la factura"
        },
        factura_fechaVencimiento_m: {
            required: "Por favor ingrese la fecha de vencimiento de la factura"
        }
    },
    
    submitHandler: function (form) {
                
        $.ajax({
            type: "POST",
            dataType:'JSON',
            url: "facturacion_cb.php",
            data: $(form).serialize(),
            success: function(data) {
                
                if (data != false) {

                    $.Notification.autoHideNotify('success', 'top center', 'La factura se modifico exitosamente...', 'Los cambios han sido guardados.');
                
                    // Limpia formulario modificacion
                    $("#formulario_modificacion").clearValidation();
                    $('#formulario_modificacion')[0].reset();
                    
                    // Acomoda los paneles
                    $('#panel_formulario_modificacion').slideUp();
                    
                    // Llamar a formulario_lectura para mostrar la factura recien modificada
                    let factura_id = $('#factura_id_v').val();
                    formulario_lectura(factura_id);

                } else {
                    $.Notification.autoHideNotify('error', 'top center', 'La factura que intenta modificar ya existe...', 'Ingrese otro numero de factura');
                }
                
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $.Notification.autoHideNotify('error', 'top center', 'Error...', 'Reporte del error. ' + xhr.responseText);
            }
        });
        
        // Previene que el formulario haga POST después de la validación, ya que ejecuto el guardado por Ajax.
        return false;
    }
});

// Formulario de ALTA de un ITEM de FACTURA
$("#formulario_alta_fci").validate({  
            
    ignore: [],
    // Agrupa los 4 campos para la seleccion de servicios. 
    // De esta forma sale un solo mensaje de error cuando alguno de estos campos no este completo.
    groups: {
        ServiciosCaso: "fci_caso_n fci_caso_id_n fci_factura_id_n fci_seleccionados"
    },
    rules: {
        fci_caso_n: {
            required: true
        },
        fci_caso_id_n: {
            required: true
        },
        fci_factura_id_n: {
            required: true
        },
        fci_seleccionados: {
            required: true
        },
        fci_imp_medicoOrigen_n: {
            required: true,
            min: 0
        },
        fci_imp_feeOrigen_n: {
            required: true,
            min: 0
        },
        fci_descuento_n: {
            maxStrict: function(e) {
                let importe_medico  = Number($('#fci_imp_medicoOrigen_n').val());
                let importe_fee     = Number($('#fci_imp_feeOrigen_n').val());
                let deducible       = Number($('#fci_descuento_n').val());
                return Number((importe_medico + importe_fee) - deducible);
            },
            min: 0
        },
        fci_moneda_id_n: {
            required: true
        },
        fci_tipoCambio_n: {
            required: true,
            min: 0.01
        },
        fci_importeUSD_n: {
            required: true
        }
    },
    messages: {
        fci_caso_n: {
            required: "Por favor seleccione los servicios asociados"
        },
        fci_caso_id_n: {
            required: "Por favor seleccione los servicios asociados 1"
        },
        fci_factura_id_n: {
            required: "Por favor seleccione los servicios asociados 1"
        },
        fci_seleccionados: {
            required: "Por favor seleccione los servicios asociados 1"
        },
        fci_imp_medicoOrigen_n: {
            required: "Por favor ingrese el importe médico de origen",
            min: "El importe medico no puede ser nulo"
        },
        fci_imp_feeOrigen_n: {
            required: "Por favor ingrese el importe de fee",
            min: "El importe fee no puede ser negativo"
        },
        fci_descuento_n: {
            maxStrict: "El descuento no puede ser mayor al Importe Médico + Fee",
            min: "El descuento no puede ser negativo"
        },
        fci_moneda_id_n: {
            required: "Por favor seleccione la moneda"
        },
        fci_tipoCambio_n: {
            required: "Por favor ingrese el tipo de cambio",
            min: "El tipo de cambio no puede ser nulo"    
        },
        fci_importeUSD_n: {
            required: "Algo falló, el Importe USD no fue calculado"
        }
    },
    
    submitHandler: function (form) {
        
        // Habilita los campos readonly para que se puedan guardar
        $('#fci_tipoCambio_n').prop("disabled", false);
        $('#fci_importeUSD_n').prop("disabled", false);
        
        // Bloquea el boton Guardar item de factura
        $("#btn_guardar_nuevo_fci").prop("disabled", true);

        $.ajax({
            type: "POST",
            url: "facturacion_cb.php",
            data: $(form).serialize(),
            beforeSend: function () {
                $.Notification.autoHideNotify('warning', 'top center', 'El item de Factura está siendo guardado...', 'Aguarde un instante por favor.');
            },
            success: function () {
                $('.notifyjs-wrapper').trigger('notify-hide');
                $.Notification.autoHideNotify('success', 'top center', 'El item de Factura se grabó exitosamente...', 'Los cambios han sido guardados.');

                // Arma la grilla con los ITEMS de Factura
                grilla_fcItems('','');
                
                // Limpia formulario alta
                $("#formulario_alta_fci").clearValidation();
                $('#formulario_alta_fci')[0].reset();
                
                // Deshabilita los campos de importe que no debe modificar el usuario
                $('#fci_tipoCambio_n').prop("disabled", true);
                $('#fci_importeUSD_n').prop("disabled", true);
                
                // Desbloquea el boton Guardar item de factura
                $("#btn_guardar_nuevo_fci").prop("disabled", false);
                
                //refresco de toda las solapas de factura
                let factura_id = $('#factura_id_v').val();
                formulario_lectura(factura_id);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $.Notification.autoHideNotify('error', 'top center', 'Error...', 'Reporte del error. ' + xhr.responseText);
            }
        });
        
        // Previene que el formulario haga POST después de la validación, ya que ejecuto el guardado por Ajax.
        return false;
    }
});

// Formulario de MODIFICACION de un ITEM de FACTURA
$("#formulario_modificacion_fci").validate({  
    ignore: [],
    rules: {
        fci_caso_m: {
            required: true
        },
        fci_imp_medicoOrigen_m: {
            required: true,
            min: 0
        },
        fci_imp_feeOrigen_m: {
            required: true,
            min: 0
        },
        fci_descuento_m: {
            maxStrict: function(e){
                let importe_medico  = Number($('#fci_imp_medicoOrigen_m').val());
                let importe_fee     = Number($('#fci_imp_feeOrigen_m').val());
                let deducible       = Number($('#fci_descuento_m').val());
                return Number((importe_medico + importe_fee) - deducible);
            },
            min: 0
        },
        fci_moneda_id_m: {
            required: true
        },
        fci_tipoCambio_m: {
            required: true,
            min: 0.01
        },
        fci_importeUSD_m: {
            required: true
        }
    },
    messages: {
        fci_caso_m: {
            required: "Por favor seleccione los servicios asociados"
        },
        fci_imp_medicoOrigen_m: {
            required: "Por favor ingrese el importe médico de origen",
            min: "El importe medico no puede ser nulo"
        },
        fci_imp_feeOrigen_m: {
            required: "Por favor ingrese el importe de fee",
            min: "El importe fee no puede ser negativo"
        },
        fci_descuento_m: {
            maxStrict: "El descuento no puede ser mayor al Importe Médico + Fee",
            min: "El descuento no puede ser negativo"
        },
        fci_moneda_id_m: {
            required: "Por favor seleccione la moneda"
        },
        fci_tipoCambio_m: {
            required: "Por favor ingrese el tipo de cambio",
            min: "El tipo de cambio no puede ser nulo"
        },
        fci_importeUSD_m: {
            required: "Algo falló, el Importe USD no fue calculado"
        }
    },
    
    submitHandler: function (form) {
        
        // Habilita los campos readonly para que se puedan guardar
        $('#fci_tipoCambio_m').prop("disabled", false);
        $('#fci_importeUSD_m').prop("disabled", false);

        $.ajax({
            type: "POST",
            url: "facturacion_cb.php",
            data: $(form).serialize(),
            success: function () {
                
                $.Notification.autoHideNotify('success', 'top center', 'El item de la factura se modifico exitosamente...', 'Los cambios han sido guardados.');

                // Arma la grilla con los ITEMS de Factura
                grilla_fcItems('','');
                
                // Limpia formulario modificacion
                $("#formulario_modificacion_fci").clearValidation();
                $('#formulario_modificacion_fci')[0].reset();
                
                // Acomoda paneles
                $('#panel_formulario_alta_fci').slideDown();
                $('#panel_formulario_modificacion_fci').slideUp();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $.Notification.autoHideNotify('error', 'top center', 'Error...', 'Reporte del error. ' + xhr.responseText);
            }
        });
        
        // Previene que el formulario haga POST después de la validación, ya que ejecuto el guardado por Ajax.
        return false;
    }
});

// Formulario de AUTORIZACION de un ITEM de FACTURA
$("#formulario_autorizacion_fci").validate({  
    ignore: [],
    rules: {
        fci_mov_auditoria_auto_id: {
           required: true
        },
        fci_importeAprobadoUSD_auto: {
            required: function(e){
                return $("#fci_estado_au_id").val() === "1";
            },
            maxStrict: function(e){
                return $("#fci_importeRechazadoUSD_auto").val();
            },
            min: 0
        },
        fci_mov_motivoRechazo_auto_id: {
            required: function(e){
                return $("#fci_mov_auditoria_auto_id option:selected").val() === "4" || $("#fci_mov_auditoria_auto_id option:selected").val() === "6" || $("#fci_mov_auditoria_auto_id option:selected").val() === "8";
            }
        },
        fci_observaciones_auto: {
            required: function(e){
                return $("#fci_mov_auditoria_auto_id option:selected").val() === "4" || $("#fci_mov_auditoria_auto_id option:selected").val() === "6" || $("#fci_mov_auditoria_auto_id option:selected").val() === "8";
            }
        },
        fci_fechaPago_auto: {
            required: function(e){
                return $("#fci_mov_auditoria_auto_id option:selected").val() === "10";
            }
        },
        fci_formaPago_auto: {
            required: function(e){
                return $("#fci_mov_auditoria_auto_id option:selected").val() === "10";
            }
        }
    },
    messages: {
        fci_mov_auditoria_auto_id: {
            required: "Por favor seleccione un tipo de autorización"
        },
        fci_importeAprobadoUSD_auto: {
            required: "Por favor ingrese el importe aprobado en USD",
            maxStrict: "El importe aprobado no puede ser superior al importe del ITEM de la Factura",
            min: "El importe aprobado no puede ser negativo"
        },
        fci_mov_motivoRechazo_auto_id: {
            required: "Por favor selecciones un motivo por el cual se rechaza el ITEM de la Factura"
        },
        fci_observaciones_auto: {
            required: "Por favor ingrese una observación"
        },
        fci_fechaPago_auto: {
            required: "Por favor ingrese una fecha de pago"
        },
        fci_formaPago_auto: {
            required: "Por favor seleccione una forma de pago"
        }
    },
    
    submitHandler: function (form) {
        
        $('#fci_mov_auditoria_auto_id').prop("disabled", false);
        
        $.ajax({
            type: "POST",
            url: "facturacion_cb.php",
            data: $(form).serialize(),
            success: function () {
                // Mensaje de confirmacion
                $.Notification.autoHideNotify('success', 'top center', 'El ITEM de la Factura se autorizó exitosamente...', 'Los cambios han sido guardados.');
                
                // Arma la grilla con los ITEMS de Factura
                grilla_fcItems('','');
                
                // Limpia formulario autorizacion
                $("#formulario_autorizacion_fci").clearValidation();
                $('#formulario_autorizacion_fci')[0].reset();
                
                // Oculta modal de autorizacion de facturas
                $('#modal_auto_facturas').modal('toggle');
                
                //refresco de toda las solapas de factura
                let factura_id = $('#factura_id_v').val();
                formulario_lectura(factura_id);
            },
            error: function (xhr) {
                // Mensaje de error
                $.Notification.autoHideNotify('error', 'top center', 'Error...', 'Reporte del error. ' + xhr.responseText);
            }
        });
        
        // Previene que el formulario haga POST después de la validación, ya que ejecuto el guardado por Ajax.
        return false;
    }
});

// Formulario de LECTURA de un ITEM de factura
function formulario_lectura_fci(fci_id) {
    
    // scrollea la pagina hasta arriba al cargar la funcion
    window.scrollTo(0,0);
    
    // Le saca la propiedad hidden a la clase del menu tabs
    //$('#menu_tabs').removeClass('hidden');
    
    let parametros = {
        "fci_id": fci_id,
        "opcion": 'formulario_lectura_fci'
    };
    
    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'facturacion_cb.php',
        data: parametros,
        success:function(data){
            
            // Formatea los campos de monedas
            $('#fci_imp_medicoOrigen_m').number( true, 2, ',', '.' );
            $('#fci_imp_feeOrigen_m').number( true, 2, ',', '.' );
            $('#fci_descuento_m').number( true, 2, ',', '.' );
            $('#fci_tipoCambio_m').number( true, 2, ',', '.' );
            $('#fci_importeUSD_m').number( true, 2, ',', '.' );
            
            $('#fci_id').val(data.fcItem_id);
            $('#fci_caso_m').val(data.caso_numero);
            $('#fci_caso_id_m').val(data.fcItem_caso_id);
            $('#fci_prestador_id_m').val(data.factura_prestador_id);
            $('#fci_numeroFactura_m').val(data.factura_numero);
            $('#fci_factura_id_m').val(data.fcItem_factura_id);
            //select cliente pagador
            listar_fciPagador_modificacion(data.pagador_id);            
            $('#fci_imp_medicoOrigen_m').val(data.importeMedico);
            $('#fci_imp_feeOrigen_m').val(data.importeFee);
            $('#fci_descuento_m').val(data.descuento);
            //select moneda
            listar_fciMonedas_modificacion(data.moneda_id);
            $('#fci_tipoCambio_m').val(data.tipoCambio);
            $('#fci_importeUSD_m').val(data.importeUSD);
            
            preparar_formulario_modificacionFci();
            
            // Importante para que luego de la carga dinámica vuelvan a funcionar los tooltips
            $('[data-toggle="tooltip"]').tooltip();
        }
    });
};




// Funciones auxiliares formulario
// 
// Función para resetear validaciones que quedan arraigadas al formulario al menos que se las saque explícitamente.
$.fn.clearValidation = function(){let v = $(this).validate();$('[name]',this).each(function(){v.successList.push(this);v.showErrors();});v.resetForm();v.reset();};

// Abre el modal para buscar prestadores
$("#btn_buscar_prestadores").add('#btn_buscar_prestadores_modificar').on('click', function() {    
    
    // Muestra el modal de autorizacion de facturas
    $('#modal_busqueda_prestadores').modal('show');
    
});

// Autocomplete de prestador en buscador de factura
$('#factura_prestador_nombre_buscar').autocomplete({ 
    source: function( request, response ) {
        $.ajax({
            method: "post",
            url : 'facturacion_cb.php',
            dataType: "json",
                data: {
                    prestador: request.term,
                    opcion: 'select_prestadores'
                },
                success: function( data ) {
                    response( $.map( data, function( item ) {
                        var code = item.split("|");
                        return {
                            label: code[0],
                            value: code[0],
                            data : item
                        }
                    }));
                }
        });
    },
    autoFocus: true,
    minLength: 3,
    select: function( event, ui ) {
            var names = ui.item.data.split("|");						
            $('#factura_prestador_buscar').val(names[1]);
    }		      	
});

// Autocomplete de prestador en alta de factura
$('#factura_prestador_nombre_n').autocomplete({ 
    source: function( request, response ) {
        $.ajax({
            method: "post",
            url : 'facturacion_cb.php',
            dataType: "json",
                data: {
                    prestador: request.term,
                    opcion: 'select_prestadores'
                },
                success: function( data ) {
                    response( $.map( data, function( item ) {
                        var code = item.split("|");
                        return {
                            label: code[0],
                            value: code[0],
                            data : item
                        }
                    }));
                }
        });
    },
    autoFocus: true,
    minLength: 3,
    select: function( event, ui ) {
            var names = ui.item.data.split("|");						
            $('#factura_prestador_id_n').val(names[1]);
    }		      	
});

// Autocomplete de prestador en modificacion de factura
$('#factura_prestador_nombre_m').autocomplete({ 
    source: function( request, response ) {
        $.ajax({
            method: "post",
            url : 'facturacion_cb.php',
            dataType: "json",
                data: {
                    prestador: request.term,
                    opcion: 'select_prestadores'
                },
                success: function( data ) {
                    response( $.map( data, function( item ) {
                        var code = item.split("|");
                        return {
                            label: code[0],
                            value: code[0],
                            data : item
                        }
                    }));
                }
        });
    },
    autoFocus: true,
    minLength: 3,
    select: function( event, ui ) {
            var names = ui.item.data.split("|");						
            $('#factura_prestador_id_m').val(names[1]);
    }		      	
});
    
// Funcion para cargar el formulario MODIFICACION con la info de la factura seleccionada
$("#btn_modificar_factura").on('click', function() {
    
    let factura_id = $('#factura_id_v').val();
    
    var parametros = {
        "factura_id": factura_id,
        "opcion": 'formulario_lectura'
    };
    
    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'facturacion_cb.php',
        data: parametros,
        success:function(data){
            
            $('#factura_id_m').val(data.factura_id);
            $('#factura_prestador_nombre_m').val(data.prestador);
            $('#factura_prestador_id_m').val(data.idPrestador);
            $('#factura_numero_m').val(data.numeroFactura);
            $('#factura_estado_m').val(data.estadoFactura);
            listar_facturasPagador_modificacion(data.factura_id);
            listar_facturasPrioridades_modificacion(data.factura_id);
            $('#factura_fechaIngresoSistema_m').val(data.fechaIngresoSistemaFactura);
            $('#factura_fechaEmision_m').val(data.fechaEmisionFactura);
            $('#factura_fechaRecepcion_m').val(data.fechaRecepcionFactura);
            $('#factura_fechaVencimiento_m').val(data.fechaVencimientoFactura);
            $('#factura_observaciones_m').val(data.observacionesFactura);
            
            preparar_formulario_modificacion();
        }
    });
});

 //Funcion para asignar un prestador en ALTA y MODIFICACION de la FACTURA
function asignar_prestador_factura(prestador_id, prestador_nombre) {

    // Asigna el NOMBRE del prestador
    $('#factura_prestador_nombre_n').val(prestador_nombre);
    $('#factura_prestador_nombre_m').val(prestador_nombre);
    
    // Asigna el ID del prestador
    $('#factura_prestador_id_n').val(prestador_id);
    $('#factura_prestador_id_m').val(prestador_id);

    // Oculta modal de busqueda de prestadores
    $('#modal_busqueda_prestadores').modal('toggle');
    
};

// Abre el modal para buscar servicios
$("#btn_buscar_servicios").add('#btn_buscar_servicios_modificar').on('click', function() {    
    
    let caso_numero = $("#fci_caso_n").val();
    $("#caso_numero_buscar").val(caso_numero);
    grilla_listar_servicios('','');
    // Muestra el modal de autorizacion de facturas
    $('#modal_busqueda_servicios').modal('show');
    
});

// Abre el modal para buscar servicios
$("#btn_buscar_servicios_m").add('#btn_buscar_servicios_modificar').on('click', function() {    

    grilla_servicios_asignadosPorItem('','');

    $("#fci_seleccionados_m").empty();
    $("#fci_seleccionados_b").empty();
    // Muestra el modal de autorizacion de facturas
    $('#modal_servicios_asignados').modal('show');

});

// Funcion para la seleccion de Servicios en la carga de ITEMS de Facturas
function seleccion_servicios(caso_id, caso_cliente_id) {

    let seleccionados = "";

    $('input[name="seleccionados[]"]:checked').each(function() {
        seleccionados += $(this).val() + ",";
    });
    
    // Se elimina la última coma
    seleccionados = seleccionados.substring(0, seleccionados.length-1);

    // Si se selecciona al menos un servicio, muestra el boton de carga de servicios
    if(!seleccionados) {
        $('#box_cargarServicios').addClass('hidden');
    } else {
        $('#box_cargarServicios').removeClass('hidden');
    }
    
    // Captura el evento click de btn_cargarServicios 
    $("#btn_cargarServicios").on('click', function() {
        
        // Toma variables
        let seleccionados   = "";
        let caso_numero     = $('#caso_numero_buscar').val();
        let factura_id      = $('#factura_id_v').val();
        
        $('input[name="seleccionados[]"]:checked').each(function() {
            seleccionados += $(this).val() + ",";
        });

        // Se elimina la última coma
        seleccionados = seleccionados.substring(0, seleccionados.length-1);
        
        // Pasa los datos a los campos ocultos del insert del item
        $('#fci_seleccionados').val(seleccionados);
        $('#fci_caso_id_n').val(caso_id);
        $('#fci_factura_id_n').val(factura_id);
        $('#fci_caso_n').val(caso_numero);
        listar_fciPagador_alta(caso_cliente_id);
        // Oculta modal de busqueda de servicios
        //$('#modal_busqueda_servicios').modal('toggle');
    }); 
};

// Funcion para la seleccion de Servicios en la modificacion de ITEMS de Facturas
function seleccion_servicios_m() {

    let seleccionados_m = "";

    $('input[name="seleccionados_m[]"]:checked').each(function() {
        seleccionados_m += $(this).val() + ",";
    });

    // Se elimina la última coma
    seleccionados_m = seleccionados_m.substring(0, seleccionados_m.length-1);
    $('#fci_seleccionados_m').val(seleccionados_m);   
};
// Funcion para seleccionar y borrar Servicios asociados en la modificacion de ITEMS de Facturas
function seleccion_servicios_b() {

    let seleccionados_b = "";

    $('input[name="seleccionados_b[]"]:not(:checked)').each(function() {
        seleccionados_b += $(this).val() + ",";
    });

    // Se elimina la última coma
    seleccionados_b = seleccionados_b.substring(0, seleccionados_b.length-1);
    $('#fci_seleccionados_b').val(seleccionados_b);    
};

// Completa los datos a mostrar del ITEM que se va a autorizar 
// y llama a la funcion que prepara el formulario de autorizacion
let autorizar_fci = function(fci_id) {
    
    // Limpia los select
    $("#fci_mov_auditoria_auto_id").empty();
    $("#fci_mov_motivoRechazo_auto_id").empty();
    
    let parametros = {
        "fci_id": fci_id,
        "opcion": 'fci_pendiente_autorizar'
    };

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'facturacion_cb.php',
        data: parametros,
        success:function(data) {
            
            // Formatea los campos de monedas
            $('#fci_importeUSD_au').number( true, 2, ',', '.' );
            $('#fci_importeAprobadoUSD_au').number( true, 2, ',', '.' );
            $('#fci_importeUSD_auto').number( true, 2, ',', '.' );
            
            // Datos para ingresar en formulario
            $('#fci_id_au').val(data.idFci);
            $('#fci_numero_au').val(data.numeroFactura);
            $('#fci_estado_au_id').val(data.estadoFciId);
            $('#fci_estado_au').val(data.estadoFciNombre);
            $('#fci_importeUSD_au').val(data.importeUSDFci);
            $('#fci_importeAprobadoUSD_au').val(data.importeAprobadoUSD);
            $('#fci_importeUSD_auto').val(data.importeUSDFci);
            
            listar_movEstados_alta(data.estadoFciId, data.importeAprobadoUSD);
            listar_motivosRechazo_alta();
            
            preparar_formulario_autorizacion_fci(data.estadoFciId);
        },
        error: function (xhr) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });
}

// Funcion para mostrar u ocultar el div motivoRechazoFactura, cuando se selecciona un logEstado
$("#fci_importeAprobadoUSD_auto").on('keyup', function() {
    
    let importe_real_USD    = $('#fci_importeUSD_auto').val();
    let importe_aprobado    = $('#fci_importeAprobadoUSD_auto').val();
    let importe_rechazado   = (importe_real_USD - importe_aprobado);
    
    $('#fci_importeRechazadoUSD_auto').val(importe_rechazado);
    
    if (importe_rechazado == 0)  {
        $('#fci_mov_auditoria_auto_id').val(2); // 2 - Item Aprobado por Facturación
    } else if (importe_real_USD == importe_rechazado) {
        $('#fci_mov_auditoria_auto_id').val(4); // 4 - Item Rechazado por Facturación
    } else if (importe_rechazado < 0) {
        $('#fci_mov_auditoria_auto_id').val(10); // 10 - Seleccione (En caso que el 'Valor Aprobado' sea superior al 'Valor Real USD')
    } else {
        $('#fci_mov_auditoria_auto_id').val(3); // 3 - Item Aprobado Parcial por Facturación 
    }
    
    factura_log_estado = $("#fci_mov_auditoria_auto_id option:selected").val();
    mostrar_motivo_rechazo(factura_log_estado);
});

// Valida si el importe rechazado es mayor a 0 para el formulario AUTORIZACION
$.validator.addMethod('maxStrict', function (value, el, param) {
    return 0 <= param;
});

// Al modificarse el select fci_mov_auditoria_auto_id llama a la funcion mostrar_motivo_rechazo que comprueba comprobar si debe mostrar el DIV motivoRechazoFactura
$("#fci_mov_auditoria_auto_id").on('change', function() {
    factura_log_estado = $("#fci_mov_auditoria_auto_id option:selected").val();
    mostrar_motivo_rechazo(factura_log_estado);
    
});

// Funcion que valida cuando se debe mostrar el DIV motivoRechazoFactura
let mostrar_motivo_rechazo = function(factura_log_estado) {
    if (factura_log_estado === '4' || factura_log_estado === '6' || factura_log_estado === '8') {
        $('.motivoRechazoFci').show();
    } else {
        $('.motivoRechazoFci').hide();
    }
}

// Busca los datos necesarios del caso para la auditoria de items de factura
// Se llama desde la funcion grilla_movimientos_fci
function fci_datos_caso(fci_id) {
    
    let parametros = {
        "fci_id": fci_id,
        "opcion": 'fci_datos_caso'
    };
    
    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'facturacion_cb.php',
        data: parametros,
        success:function(data){
            
            // Completa los input con la informacion del caso necesaria al momento de auditar items de factura
            $('#fci_caso_beneficiario').val(data.beneficiario);
            $('#fci_caso_voucher').val(data.voucher);
            $('#fci_caso_producto').val(data.producto);
            $('#fci_caso_agencia').val(data.agencia);
            $('#fci_caso_pais').val(data.pais);
            $('#fci_caso_diagnostico').val(data.diagnostico);
        }
    });
};

// Función para marcar una factura como pagada
$("#btn_pagar_factura").on('click', function() {
    
    factura_id = $("#factura_id_v").val();
    
    let parametros = {
        "factura_id": factura_id,   
        "opcion": 'pagar_factura'
    };
    
    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'facturacion_cb.php',
        data: parametros,
        success: function () {
            formulario_lectura(factura_id);
            $.Notification.autoHideNotify('success', 'top center', 'La factura se pagó exitosamente...', 'Los cambios han sido guardados.');
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $.Notification.autoHideNotify('error', 'top center', 'Error...', 'Reporte del error. ' + xhr.responseText);
        }
    });

});

// Función para ver la información completa de un Prestador
$("#btn_ver_prestador").on('click', function() {
    
    prestador_id = $("#factura_prestador_id_v").val();
    
    window.open(
        '../prestador/prestador.php?vprovider=' + prestador_id,
        '_blank' // <- This is what makes it open in a new window.
    );
});




// Funciones para preparar los distintos formularios
// 
// Prepara el formulario de ALTA de facturas
let preparar_formulario_alta = function(caso_id) {
    
    // scrollea la pagina hasta arriba la cargar la funcion
    window.scrollTo(0,0);
    
    // Completa el id del caso al campo oculto factura_caso_id_n para el formulario alta
    $('#factura_caso_id_n').val(caso_id);
    
    // Limpia los select
    $("#factura_prioridad_id_n").empty();
    
    // Agrega la informacion a los distintos Select en el formulario de ALTA
    listar_facturasPrioridades_alta();
    
    // Date picker
    $("#factura_fechaRecepcion_n").val($.datepicker.formatDate('dd-mm-yy', new Date()));
    $('#factura_fechaRecepcion_n').datepicker({
        maxDate: new Date(),
        yearRange: '-1:+0'
    });
    
    $('#factura_fechaEmision_n').datepicker({
        maxDate: new Date(),
        yearRange: '-1:+0'
    });
    
    $('#factura_fechaVencimiento_n').datepicker({
        yearRange: '-0:+1'
    });
    
    // Bloquea el boton de nueva factura
    $("#btn_nueva_factura").prop("disabled", true);
    
    // Acomoda los paneles
    $('#panel_formulario_alta').removeClass('hidden');
    $('#panel_formulario_alta').slideDown();
    $('#panel_busqueda_facturas').slideUp();
    $('#panel_grilla').slideUp();
};

// Prepara el formulario de MODIFICACION de facturas
let preparar_formulario_lectura = function(factura_id, estado_factura_id) {
    
    // scrollea la pagina hasta arriba la cargar la funcion
    window.scrollTo(0,0);
    
    // Deshabilita el boton de nueva factura
    $('#btn_nueva_factura').prop("disabled", true);
    
    // Muestra el menu de los tabs hasta que se active la busqueda
    $('#menu_tabs').show();
    
    // #303 - TODAVIA NO FUNCIONA: Valida estado de los items de factura
    let parametros = {
        "factura_id": factura_id,   
        "opcion": 'valida_estado_items'
    };
    
    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'facturacion_cb.php',
        data: parametros,
        success: function (data) {
            
            // Validaciones para habilitar los botones de 'Factura Paga', 'Modificar Factura' y el 'Formulario Alta FCI'
            // 
            // Estado 'Ingresada' (Id. 1)
                // 1- No se puede 'pagar' la factura.
                // 2- Se puede modificar la factura.
                // 3- Se pueden agregar nuevo items de factura.
                if (estado_factura_id == 1) {
                    $('#btn_pagar_factura').prop("disabled", true); 
                    $('#btn_modificar_factura').prop("disabled", false);
                    $('#panel_formulario_alta_fci').removeClass('hidden');
            // Estado 'En proceso' (Id. 2)
                // 1- Se puede 'pagar' la factura.
                // 2- No se puede modificar la factura.
                // 3- Se pueden agregar nuevo items de factura.
                } else if (estado_factura_id == 2 && data != false) {
                    $('#btn_pagar_factura').prop("disabled", false); 
                    $('#btn_modificar_factura').prop("disabled", true);
                    $('#panel_formulario_alta_fci').removeClass('hidden');
            // Estado 'Pagada' (Id. 7)
                // 1- No se puede 'pagar' la factura.
                // 2- No se puede modificar la factura.
                // 3- No se pueden agregar nuevo items de factura.
                } else if (estado_factura_id == 7) {
                    $('#btn_pagar_factura').prop("disabled", true); 
                    $('#btn_modificar_factura').prop("disabled", true);
                    $('#panel_formulario_alta_fci').addClass('hidden');
                } else {
                    $('#btn_pagar_factura').prop("disabled", true); 
                    $('#btn_modificar_factura').prop("disabled", true);
                    $('#panel_formulario_alta_fci').removeClass('hidden');
                }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $.Notification.autoHideNotify('error', 'top center', 'Error...', 'Reporte del error. ' + xhr.responseText);
        }
    });
    
    // Acomoda los paneles
    $('#panel_formulario_alta').slideUp();
    $('#panel_formulario_vista').removeClass('hidden');
    $('#panel_formulario_vista').slideDown();
    $('#panel_busqueda_facturas').slideUp(); 
};

// Prepara el formulario de MODIFICACION de facturas
let preparar_formulario_modificacion = function() {
    
    // scrollea la pagina hasta arriba la cargar la funcion
    window.scrollTo(0,0);
    
    // Oculta el menu de los tabs
    $('#menu_tabs').hide();
    
    // Limpia los select
    $("#factura_prioridad_id_m").empty();
    
    // Date picker
    $('#factura_fechaRecepcion_m').datepicker({
        maxDate: new Date(),
        yearRange: '-1:+0'
    });
    
    $('#factura_fechaEmision_m').datepicker({
        maxDate: new Date(),
        yearRange: '-1:+0'
    });
    
    $('#factura_fechaVencimiento_m').datepicker({
        yearRange: '-0:+1'
    });
    
    // Acomoda los paneles
    $('#panel_formulario_modificacion').removeClass('hidden');
    $('#panel_formulario_modificacion').slideDown();
    $('#panel_formulario_vista').slideUp();
};

// Prepara el formulario de ALTA de ITEMS de Facturas
let preparar_formulario_altaFci = function() {
    
    // scrollea la pagina hasta arriba la cargar la funcion
    window.scrollTo(0,0);
    
    // Formatea los campos de monedas
    $('#fci_imp_medicoOrigen_n').number( true, 2, ',', '.' );
    $('#fci_imp_feeOrigen_n').number( true, 2, ',', '.' );
    $('#fci_descuento_n').number( true, 2, ',', '.' );
    $('#fci_tipoCambio_n').number( true, 2, ',', '.' );
    $('#fci_importeUSD_n').number( true, 2, ',', '.' );
    
    // Limpia los select
    $("#fci_pagador_id_n").empty();
    $("#fci_moneda_id_n").empty();
    
    // Agrega la informacion a los distintos Select en el formulario de ALTA ITEM FACTURA
    listar_fciPagador_alta();
    listar_fciMonedas_alta();
    
    // Deshabilita los campos de importe que no debe modificar el usuario
    $('#fci_tipoCambio_n').prop("disabled", true);
    $('#fci_importeUSD_n').prop("disabled", true);
    
    // Acomoda los paneles
    $('#panel_formulario_alta_fci').slideDown();
    $('#panel_formulario_modificacion_fci').slideUp();
    // Llama a la funcion de la GRILLA de ITEMS de FACTURA
    grilla_fcItems();
};

// Prepara el formulario de MODIFICACION de ITEMS de Facturas
let preparar_formulario_modificacionFci = function() {
    
    // scrollea la pagina hasta arriba la cargar la funcion
    window.scrollTo(0,0);
    
    // Deshabilita los campos readonly
    $('#fci_tipoCambio_m').prop("disabled", true);
    $('#fci_importeUSD_m').prop("disabled", true);
    
    // Acomoda los paneles
    $('#panel_formulario_alta_fci').slideUp();
    $('#panel_formulario_modificacion_fci').slideDown();    
    // Llama a la funcion de la GRILLA de ITEMS de FACTURA
    grilla_fcItems();
};

// Prepara el formulario de AUTORIZACION de ITEMS de Facturas
let preparar_formulario_autorizacion_fci = function(estado_fci) {
    
    // Muestra el modal de autorizacion de ITEMS de Facturas
    $('#modal_auto_facturas').modal('show'); 
    
    // Date picker
    $('#fci_fechaPago_auto').datepicker({
        maxDate: new Date(),
        yearRange: '-1:+0'
    });
    
    // Limpia los select
    $("#fci_formaPago_auto").empty();
    
    // Formatea los campos de monedas
    $('#fci_importeAprobadoUSD_auto').number( true, 2, ',', '.' );
    $('#fci_importeRechazadoUSD_auto').number( true, 2, ',', '.' );
    
    // Agrega la informacion a los distintos Select en el formulario de AUTORIZACION
    listar_formasPagos_autorizacion();
    
    // Oculta el div motivoRechazoFci, luego se muestra dependiendo el movEstado que se elija
    $('.motivoRechazoFci').hide();
    
    // Dependiendo del estado de ITEMS de Facturas son los campos que muestra
    if (estado_fci == 1) {  //Estado: Ingresado
        $('#fci_mov_auditoria_auto_id').prop("disabled", true);
        $('.importeAprobadoFci').show();
        $('.importeUSDFci').show();
        $('.importeRechazadoUSDFci').show();
        $('.estadoAutorizacionFci').show();
        $('.fechaPago').hide();
        $('.formaPago').hide();
    } else if (estado_fci == 9) { // Estado: Pend. Pago
        $('#fci_mov_auditoria_auto_id').prop("disabled", false);
        $('.importeAprobadoFci').hide();
        $('.importeUSDFci').hide();
        $('.importeRechazadoUSDFci').hide();
        $('.estadoAutorizacionFci').hide();
        $('.fechaPago').show();
        $('.formaPago').show();
    } else { // Todo el resto de los estados
        $('#fci_mov_auditoria_auto_id').prop("disabled", false);
        $('.importeAprobadoFci').hide();
        $('.importeUSDFci').hide();
        $('.importeRechazadoUSDFci').hide();
        $('.estadoAutorizacionFci').hide();
        $('.fechaPago').hide();
        $('.formaPago').hide();
    }
};




// Funciones para completar los SELECT
// 
// Select Prioridades para el Formulario de Alta de Facturas
let listar_facturasPrioridades_alta = function(){

    let parametros = {
        "opcion": 'listar_facturasPrioridades_alta'
    };

    let miselect = $("#factura_prioridad_id_n");

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'facturacion_cb.php',
        data: parametros,
        success:function(data){

            miselect.append('<option value="">Seleccione</option>');

            for (let i=0; i<data.length; i++) {
                miselect.append('<option value="' + data[i].facturaPrioridad_id + '">' + data[i].facturaPrioridad_nombre + '</option>');
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });  
};

// Select Pagador para el Formulario de Alta de Items
let listar_fciPagador_alta = function (selected = 0) {
    //let caso_id = 387;
    
    let parametros = {
        //"caso_id": caso_id,
        "opcion": 'listar_fciPagador_alta'
    };

    let miselect = $("#fci_pagador_id_n");

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'facturacion_cb.php',
        data: parametros,
        success:function(data) {
            miselect.empty();
            for (let i=0; i<data.length; i++) {
                if (data[i].cliente_id == selected) {
                    selected_ = 'selected';
                } else {
                    selected_ = '';
                }
                miselect.append('<option ' + selected_ + ' value="' + data[i].cliente_id + '">' + data[i].cliente_nombre + '</option>');
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });  
};

// Select Pagador para el Formulario de Modificacion de Items
let listar_fciPagador_modificacion = function(pagador_id){
    
    let parametros = {
        "pagador_id": pagador_id,
        "opcion": 'listar_fciPagador_modificacion'
    };

    let miselect = $("#fci_pagador_id_m");
    miselect.empty();

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'facturacion_cb.php',
        data: parametros,
        success:function(data) {
            
            for (let i=0; i<data.length; i++) {
                miselect.append('<option value="' + data[i].cliente_id + '">' + data[i].cliente_nombre + '</option>');
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });  
};

// Select Moneda para el Formulario de Alta de Items
let listar_fciMonedas_alta = function(){
    
    let parametros = {
        "opcion": 'listar_fciMonedas_alta'
    };

    let miselect = $("#fci_moneda_id_n");

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'facturacion_cb.php',
        data: parametros,
        success:function(data){
            
            miselect.append('<option value="">Seleccione una moneda</option>');
            
            for (let i=0; i<data.length; i++) {
                miselect.append('<option value="' + data[i].moneda_id + '">' + data[i].moneda_nombre + '</option>');
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });  
};

// Select Moneda para el Formulario de Modificacion de Items
let listar_fciMonedas_modificacion = function(moneda_id){
    
    let parametros = {
        "moneda_id": moneda_id,
        "opcion": 'listar_fciMonedas_modificacion'
    };

    let miselect = $("#fci_moneda_id_m");
    miselect.empty();

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'facturacion_cb.php',
        data: parametros,
        success:function(data){
            
            for (let i=0; i<data.length; i++) {
                miselect.append('<option value="' + data[i].moneda_id + '">' + data[i].moneda_nombre + '</option>');
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });  
};
        
// Select Estados para el Formulario de Autorizacion de Items
let listar_movEstados_alta = function(fci_estado_id_au, fci_importe_aprobadoUSD_au){
    
    let parametros = {
        "fci_estado_id_au": fci_estado_id_au,
        "fci_importe_aprobadoUSD_au": fci_importe_aprobadoUSD_au,
        "opcion": 'listar_movEstados_alta'
    };

    let miselect = $("#fci_mov_auditoria_auto_id");

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'facturacion_cb.php',
        data: parametros,
        success:function(data){
            
            miselect.append('<option value="">Seleccione</option>');
            
            for (let i=0; i<data.length; i++) {
                miselect.append('<option value="' + data[i].fciMovEstado_id + '">' + data[i].fciMovEstado_nombre + '</option>');
            }
        },
        error: function (xhr) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });  
};

// Select Motivos de Rechazo el Formulario de Autorizacion de Items
let listar_motivosRechazo_alta = function(){
    
    let parametros = {
        "opcion": 'listar_motivosRechazo_alta'
    };

    let miselect = $("#fci_mov_motivoRechazo_auto_id");

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'facturacion_cb.php',
        data: parametros,
        success:function(data){
            
            miselect.append('<option value="">Seleccione</option>');
            
            for (let i=0; i<data.length; i++) {
                miselect.append('<option value="' + data[i].fciMotivoRechazo_id + '">' + data[i].fciMotivoRechazo_descripcion + '</option>');
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });  
};

// Select Prioridades para el Formulario de Modificacion de Facturas
let listar_facturasPrioridades_modificacion = function(factura_id){

    let parametros = {
        "opcion": 'listar_facturasPrioridades_modificacion',
        "factura_id": factura_id
    };

    let miselect = $("#factura_prioridad_id_m");

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'facturacion_cb.php',
        data: parametros,
        success:function(data){

            for (let i=0; i<data.length; i++) {
                miselect.append('<option value="' + data[i].facturaPrioridad_id + '">' + data[i].facturaPrioridad_nombre + '</option>');
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });  
};

// Select Pagador para el Formulario de Modificacion
let listar_facturasPagador_modificacion = function(factura_id){

    let parametros = {
        "opcion": 'listar_facturasPagador_modificacion',
        "factura_id": factura_id
    };

    let miselect = $("#factura_pagador_id_m");

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'facturacion_cb.php',
        data: parametros,
        success:function(data){
            for (let i=0; i<data.length; i++) {
                miselect.append('<option value="' + data[i].cliente_id + '">' + data[i].cliente_nombre + '</option>');
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });  
};

// Select Prestadores para la Busqueda de Facturas
let listarPrestadores_buscarFactura = function(){
    
    let parametros = {
        "opcion": 'listar_prestadores_select'
    };

    let miselect = $("#factura_prestador_buscar");

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: '../prestador/prestador_cb.php',
        data: parametros,
        success:function(data) {
            
            miselect.append('<option value="">Seleccione un Prestador</option>');
            
            for (let i=0; i<data.length; i++) {
                miselect.append('<option value="' + data[i].prestador_id + '">' + data[i].prestador_nombre + '</option>');
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });  
};

// Select Tipos de Prestadores en el Formulario de Busqueda de Prestadores
function listarTipoPrestador_buscarPrestador(){

    var parametros = {
        "opcion": 'listarTipoPrestador_buscarPrestador'
    };

    var miselect_b = $("#prestador_tipoPrestador_id_b");

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: '../servicio/servicio_cb.php',
        data: parametros,
        success:function(data){
            
            miselect_b.append('<option value="">Seleccione un tipo de Prestador</option>');

            for (var i=0; i<data.length; i++) {
                miselect_b.append('<option value="' + data[i].tipoPrestador_id + '">' + data[i].tipoPrestador_nombre + '</option>');
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });
};

// Select Paises en el Formulario de Busqueda de Prestadores
function listarPaises_buscarPrestador(){

    var parametros = {
        "opcion": 'listarPaises_buscarPrestador'
    };

    var miselect_b = $("#prestador_pais_id_b");

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: '../servicio/servicio_cb.php',
        data: parametros,
        success:function(data){
            
            miselect_b.append('<option value="">Seleccione un Pais</option>');
            
            for (var i=0; i<data.length; i++) {
                miselect_b.append('<option value="' + data[i].pais_id + '">' + data[i].pais_nombreEspanol + '</option>');
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });
};

// Select Dependiente de Paises > Ciudades para el BUSCADOR de Prestadores
// Esto sirve para que cuando se modifica el select de país se borre lo que está en el input de ciudad
$("#prestador_pais_id_b").change(function () {    
       $("#prestador_ciudad_id_b").val("");
       $("#prestador_ciudad_id_b_2").val("");
});

//
$('#prestador_ciudad_id_b').autocomplete({  
    source: function( request, response ) {
        $.ajax({
            method: "post",
            url : '../servicio/servicio_cb.php',
            dataType: "json",
                data: {
                   ciudad: request.term,
                   opcion: 'select_ciudades',
                   pais_id: $("#prestador_pais_id_b option:selected").val()
                },
                success: function( data ) {
                    response( $.map( data, function( item ) {
                        var code = item.split("|");
                        return {
                            label: code[0],
                            value: code[0],
                            data : item
                        }
                    }));
                }
        });
    },
    autoFocus: true,
    minLength: 3,
    select: function( event, ui ) {
        var names = ui.item.data.split("|");						
        $('#prestador_ciudad_id_b_2').val(names[1]);

    }		      	
});

// Tipos Facturas para el Formulario de Alta
let listar_formasPagos_autorizacion = function() {

    let parametros = {
        "opcion": 'listar_formasPagos_autorizacion'
    };

    let miselect = $("#fci_formaPago_auto");

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'facturacion_cb.php',
        data: parametros,
        success:function(data){

            miselect.append('<option value="">Seleccione</option>');

            for (let i=0; i<data.length; i++) {
                miselect.append('<option value="' + data[i].formaPago_id + '">' + data[i].formaPago_nombre + '</option>');
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });  
};

//
function formatDate(date) {
    var d = new Date(date),
        day = '' + d.getDate(),
        month = '' + (d.getMonth() + 1),
        year = d.getFullYear();

    if (day.length < 2) day = '0' + day;
    if (month.length < 2) month = '0' + month;
    
    return [year, month, day].join('-');
}




// Funciones para los importes, cambio de monedas, etc
// 
// Formulario ALTA de un ITEM
// Al cambiar el select de moneda. Llama a la funcion 'consultar_tipo_cambio'
$("#fci_moneda_id_n").on('change', function() { 
    
    // Selecciona elementos del formulario ALTA de un ITEM
    let currency        = $('#fci_moneda_id_n option:selected').text();
    let fecha_emision   = $('#factura_fechaEmision_v').val();
    let fecha_emision_f = moment(fecha_emision, 'DD.MM.YYYY').format('YYYY-MM-DD');
    let importe_medico  = Number($('#fci_imp_medicoOrigen_n').val());
    let importe_fee     = Number($('#fci_imp_feeOrigen_n').val());
    let descuento       = Number($('#fci_descuento_n').val());
    
    // Calculo del importe real
    let amount          = (importe_medico + importe_fee) - descuento;
    
    // form, moneda, fecha_emision, importe_origen
    consultar_tipo_cambio('alta',currency,fecha_emision_f,amount);
});

// Formulario MODIFICACION de un ITEM
// Al cambiar el select de moneda. Llama a la funcion 'consultar_tipo_cambio'
$("#fci_moneda_id_m").on('change', function() {
    
    // Selecciona elementos del formulario MODIFICACION de un ITEM
    let currency        = $('#fci_moneda_id_m option:selected').text();
    let fecha_emision   = $('#factura_fechaEmision_v').val();
    let fecha_emision_f = moment(fecha_emision, 'DD.MM.YYYY').format('YYYY-MM-DD');
    let importe_medico  = Number($('#fci_imp_medicoOrigen_m').val());
    let importe_fee     = Number($('#fci_imp_feeOrigen_m').val());
    let descuento       = Number($('#fci_descuento_m').val());
    
    // Calculo del importe real
    let amount          = (importe_medico + importe_fee) - descuento;
    
    consultar_tipo_cambio('modificacion',currency,fecha_emision_f,amount);
});

// Formulario ALTA de un ITEM
// Al modificarse el campo importe medico, importe fee o descuento. Llama a la funcion 'calcular_importe_usd'
$('#fci_imp_medicoOrigen_n').add('#fci_imp_feeOrigen_n').add('#fci_descuento_n').add('#fci_tipoCambio_n').on('keyup', function() {
    
    let importe_medico  = Number($('#fci_imp_medicoOrigen_n').val());
    let importe_fee     = Number($('#fci_imp_feeOrigen_n').val());
    let descuento       = Number($('#fci_descuento_n').val());
    // Calcula el importe de origen
    let importe_origen  = (importe_medico + importe_fee) - descuento;
    let tipo_cambio     = $('#fci_tipoCambio_n').val();
    
    let resultado       = calcular_importe_usd('alta',importe_origen,tipo_cambio);
    
    $('#fci_importeUSD_n').val(resultado);
});

// Formulario MODIFICACION de un ITEM
// Al modificarse el campo importe medico, importe fee o descuento. Llama a la funcion 'calcular_importe_usd'
$('#fci_imp_medicoOrigen_m').add('#fci_imp_feeOrigen_m').add('#fci_descuento_m').add('#fci_tipoCambio_m').on('keyup', function() {
    
    let importe_medico  = Number($('#fci_imp_medicoOrigen_m').val());
    let importe_fee     = Number($('#fci_imp_feeOrigen_m').val());
    let descuento       = Number($('#fci_descuento_m').val());
    // Calcula el importe de origen
    let importe_origen  = (importe_medico + importe_fee) - descuento;
    let tipo_cambio     = $('#fci_tipoCambio_m').val();
    
    let resultado       = calcular_importe_usd('modificacion',importe_origen,tipo_cambio);
    
    $('#fci_importeUSD_m').val(resultado);
});

// Funcion para consultar el tipo de cambio (T/C) - Consulta API
function consultar_tipo_cambio(form, moneda, fecha_emision, importe_origen) {
    
    // Setea la conexion con la API
    endpoint = 'historical'; // Busca registro historico para tomar el 'tipo de cambio'
    access_key = '2NgMGSgT3Ea2UNJZZEHVL7vEYgniu1oq';
    
    $.ajax({
        url: 'https://api.apilayer.com/currency_data/' + endpoint + '?date=' + fecha_emision,
        dataType: 'JSON',
        headers: {
            'apikey': access_key
        },
        success: function(data) {
            
            let tipoCambio;
            let tipo_cambio;
            let importeUSD;
            
            if(form == 'alta') {
                tipoCambio = $('#fci_tipoCambio_n');
                importeUSD = $('#fci_importeUSD_n');
            } else if (form == 'modificacion') {
                tipoCambio = $('#fci_tipoCambio_m');
                importeUSD = $('#fci_importeUSD_m');
            }

            // Valida si encuentra la moneda en el conversor
            if (data.success === true) {
                // Deshabilita el boton fci_tipoCambio_n
                tipoCambio.prop("disabled", true);
                // Busca en la primer posicion del objeto data.quotes y lo guarda en 'tipo_cambio'
                if (moneda === 'USD') {
                    tipo_cambio = '1';
                } else {
                    tipo_cambio = data.quotes[Object.keys(data.quotes)[0]];
                }
                // Llama a la funcion 'calcular_importe_usd'
                calcular_importe_usd(form, importe_origen,tipo_cambio);
            } else {
                $.Notification.autoHideNotify('error', 'top center', `La moneda ${moneda} no fue encontrada`, 'Ingrese el Tipo de Cambio en forma manual.');
                // Acciones sobre los campos tipoCambio y importeUSD
                    // Limpia el campo
                    tipoCambio.val('');
                    importeUSD.val('');
                    // Lo habilita
                    tipoCambio.prop("disabled", false);
                    // Hace foco en el
                    tipoCambio.focus();
            }
        },
        error: function() {
            $.Notification.autoHideNotify('error', 'top center', `Hubo un error en la conversión de moneda`, 'Genere un ticket a soporte técnico copiando el codigo de error. Código: #CC239');
            // Acciones sobre el boton tipoCambio
                // Limpia el campo
                tipoCambio.val('');
                // Lo habilita
                tipoCambio.prop("disabled", false);
                // Hace foco en el
                tipoCambio.focus();
        }
    });
}

// Funcion para calcular la conversion a USD
function calcular_importe_usd(form, importe_origen,tipo_cambio) {
    
    let parametros = {
        "importe_origen": importe_origen,
        "tipo_cambio": tipo_cambio,
        "opcion": 'calcular_importe_usd'
    };
    
    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'facturacion_cb.php',
        data: parametros,
        success:function(data) {
            // Pone los resultados en el formulario de ALTA o MODIFICACION
            // Dependiendo de lo que llegue en la variable 'form'
            if (form === 'alta') {
                $("#fci_tipoCambio_n").val(tipo_cambio);
                $('#fci_importeUSD_n').val(data);
            } else if (form === 'modificacion') {
                $("#fci_tipoCambio_m").val(tipo_cambio);
                $('#fci_importeUSD_m').val(data);
            } else {
                $.Notification.autoHideNotify('error', 'top right', 'Error al convertir la moneda');
            }
        }
    });
}




// Funciones de grilla
//
// Funcion para buscar la factura y armar la grilla con tecla ENTER
$(document).keypress(function (e) {
    if(e.keyCode === 13){
        grilla_listar_facturas('','');
    }
});

// Funcion para buscar la factura y armar la grilla con la lupa
$("#btn_buscar_factura").on('click', function(){
    grilla_listar_facturas('','');
});
    
let grilla_listar_facturas = function(){ 

    let factura_numero_buscar       = $('#factura_numero_buscar').val();
    let factura_final_buscar = $('#factura_final_buscar').val();
    let fc_caso_numero_buscar       = $('#fc_caso_numero_buscar').val();
    let factura_prestador_buscar    = $("#factura_prestador_buscar").val();
    
    let parametros = {
        "factura_numero_buscar": factura_numero_buscar,
        "factura_final_buscar": factura_final_buscar,
        "fc_caso_numero_buscar": fc_caso_numero_buscar,
        "factura_prestador_buscar": factura_prestador_buscar,
        "opcion": 'grilla_listar_facturas'
    };
    $.ajax({        
        method: "POST",
        dataType: "HTML",
        url: "facturacion_cb.php",
        data: parametros
    }).done(function(data){	
        $("#grilla_facturas").html(data);
        listar_facturas();
    });
};

// Hace arrancar el dataTable de facturas
let listar_facturas = function() {
    
    // Importante para que luego de la carga dinámica vuelvan a funcionar los tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    $("#dt_facturacion_facturas").DataTable({   
        "destroy":true,
        "stateSave": true,
        "bFilter": false,
        "language": idioma_espanol
    });
};

// Funcion para buscar el prestador. Va a buscar los datos de la grilla
$("#btn_listar_prestadores").on('click', function() {
    
    let prestador_nombre            = $("#prestador_nombre_buscar").val();  
    let prestador_tipoPrestador_id  = $("#prestador_tipoPrestador_id_b option:selected").val();
    let prestador_pais_id           = $("#prestador_pais_id_b option:selected").val();
    let prestador_ciudad_id         = $("#prestador_ciudad_id_b_2").val();
                 
    let parametros_contar = {
        "prestador_nombre_buscar": prestador_nombre,
        "prestador_tipoPrestador_id_buscar": prestador_tipoPrestador_id,        
        "prestador_pais_id_buscar": prestador_pais_id,
        "prestador_ciudad_id_buscar": prestador_ciudad_id,
        "opcion": 'grilla_listar_prestador_contar'
    };
    $.ajax({
            dataType: "html",
            method: "POST",
            url: "facturacion_cb.php",
            data: parametros_contar,
           
            success:function(data) {
                $("#grilla_info").html(data);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
            }
    });       
    
    let parametros_listar = {
        "prestador_nombre_buscar": prestador_nombre,
        "prestador_tipoPrestador_id_buscar": prestador_tipoPrestador_id,        
        "prestador_pais_id_buscar": prestador_pais_id,
        "prestador_ciudad_id_buscar": prestador_ciudad_id,
        "opcion": 'grilla_listar_prestador'
    };
    $.ajax({
            dataType: "html",
            method: "POST",
            url: "facturacion_cb.php",
            data: parametros_listar,
           
            success:function(data) {
                $("#grilla_prestador").html(data);
                
                // Importante para que luego de la carga dinámica vuelvan a funcionar los tooltips
                $('[data-toggle="tooltip"]').tooltip();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
            }
    });
});
 
// Funcion para buscar el servicio. Va a buscar los datos de la grilla
$("#btn_listar_servicios").on('click', function() {
    grilla_listar_servicios('','');
});

let grilla_listar_servicios = function(){
    
    let caso_numero_buscar = $("#caso_numero_buscar").val();
    let prestador_id       = $("#factura_prestador_id_v").val();
                 
    let parametros = {
        "caso_numero_buscar": caso_numero_buscar,
        "prestador_id": prestador_id,
        "opcion": 'grilla_listar_servicios'
    };
    $.ajax({
            dataType: "html",
            method: "POST",
            url: "facturacion_cb.php",
            data: parametros,
           
            success:function(data){
                
                $("#grilla_servicios").html(data);
                
                // Le saca la propiedad hidden a la clase del menu tabs
                $('#div_grilla_servicios').removeClass('hidden');
                
                // Importante para que luego de la carga dinámica vuelvan a funcionar los tooltips
                $('[data-toggle="tooltip"]').tooltip();
            },
            error: function (xhr) {
                $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
            }
    });
};

// GRILLA - ITEMS de Facturas asociados a Facturas
let grilla_fcItems = function (order = 1, pagador = 0, factura_final = '') {
    console.log('final ' + factura_final);
    let factura_id = $("#factura_id_v").val();
    order = (order==1) ? 'ASC' : 'DESC';
    
    let parametros = {
        "factura_id": factura_id,
        "order": order,
        "pagador": pagador,
        "factura_final": factura_final,
        "opcion": 'grilla_fcItems'
    };
    
    $.ajax({
        dataType: "HTML",
        method: "POST",
        url: "facturacion_cb.php",
        data: parametros,

        success:function(data) {
            $("#grilla_fci").html(data);
            
            // Importante para que luego de la carga dinámica vuelvan a funcionar los tooltips
            $('[data-toggle="tooltip"]').tooltip();
        },
        error: function (xhr) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });
};

// Servicios asiganods a un item de factura y servicios autorizados del caso sin asignar a un item de la misma u otra factura
let grilla_servicios_asignadosPorItem = function(){
       
    let fci_id = $("#fci_id").val();
    let caso_id = $("#fci_caso_id_m").val();
    let prestador_id = $("#fci_prestador_id_m").val();
    
    let parametros = {
        "fci_id": fci_id,
        "caso_id": caso_id,
        "prestador_id": prestador_id,
        "opcion": 'grilla_servicios_asignadosPorItem'
    };
    
    $.ajax({
        dataType: "HTML",
        method: "POST",
        url: "facturacion_cb.php",
        data: parametros,

        success:function(data) {
            
            $("#grilla_servicios_asignados").html(data);
            
            // Le saca la propiedad hidden a la clase del menu tabs
            $('#div_grilla_servicios_asignados').removeClass('hidden');
            
            // Importante para que luego de la carga dinámica vuelvan a funcionar los tooltips
            $('[data-toggle="tooltip"]').tooltip();
        },
        error: function (xhr) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });
};

// Va a buscar los datos para la grilla de log de factura
let grilla_movimientos_fci = function(fci_id) {
    
    let parametros = {
        "fci_id": fci_id,
        "opcion": 'grilla_listar_mov_fci'
    };
    $.ajax({        
        method: "POST",
        dataType: "HTML",
        url: "facturacion_cb.php",
        data: parametros
    }).done(function(data){
        
        // Llama a la funcion para mostrar los datos del caso
        fci_datos_caso(fci_id);
        
        // Muestra el modal con los movimientos del ITEM de Factura
        $('#modal_mov_fci').modal('show'); 
        // Arma la grilla con los movimientos del ITEM de Factura
        $("#grilla_mov_fci").html(data);
    });
};

// Va a buscar los datos para la grilla de log de factura
let grilla_servicios_fci = function(fci_id) {
    
    let parametros = {
        "fci_id": fci_id,
        "opcion": 'grilla_listar_servicios_fci'
    };
    $.ajax({        
        method: "POST",
        dataType: "HTML",
        url: "facturacion_cb.php",
        data: parametros,
    
        success:function(data) {
    
        // Muestra el modal con los servicios asociados al ITEM de Factura
        $('#modal_servicios_fci').modal('show'); 
        // Arma la grilla con los servicios del ITEM de Factura
        $("#grilla_servicios_fci").html(data);
        // Le saca la propiedad hidden a la clase del menu tabs
        $('#div_grilla_servicios_fci').removeClass('hidden');
        },
        error: function (xhr) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });
};




// Funciones Auxiliares de grilla
//
// Idioma de las grillas
let idioma_espanol = {
    "sProcessing":     "Procesando...",
    "sLengthMenu":     "Mostrar _MENU_ registros",
    "sZeroRecords":    "No se encontraron resultados",
    "sEmptyTable":     "Ningún dato disponible en esta tabla",
    "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
    "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
    "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
    "sInfoPostFix":    "",
    "sSearch":         "Buscar:",
    "sUrl":            "",
    "sInfoThousands":  ",",
    "sLoadingRecords": "Cargando...",
    "oPaginate": {
        "sFirst":    "Primero",
        "sLast":     "Último",
        "sNext":     "Siguiente",
        "sPrevious": "Anterior"
    },
    "oAria": {
        "sSortAscending":  ": Actilet para ordenar la columna de manera ascendente",
        "sSortDescending": ": Actilet para ordenar la columna de manera descendente"
    }
};




// Acciones botones Cancelar
// 
// Cierra y Limpia el formulario ALTA cuando se pulsa en cancelar
$("#btn_cancelar_nuevo").click(function() {
    // scrollea la pagina hasta arriba al cargar la funcion
    window.scrollTo(0,0);
    // Desbloquea el boton de nueva factura
    $("#btn_nueva_factura").prop("disabled", false);
    // Limpia formulario
    $("#formulario_alta").clearValidation();
    $('#formulario_alta')[0].reset();
    // Acomoda paneles
    $('#panel_formulario_alta').slideUp();
    $('#panel_busqueda_facturas').slideDown();
});

// Cierra y Limpia el formulario VISTA cuando se pulsa en cancelar
$("#btn_cerrar_vista").click(function() {
    // scrollea la pagina hasta arriba al cargar la funcion
    window.scrollTo(0,0); 
    // Le agrega la propiedad hidden a la clase del menu tabs
    $('#menu_tabs').addClass('hidden');
    // Habilita el boton de nueva factura
    $('#btn_nueva_factura').prop("disabled", false);
    // Limpia formulario
    $("#formulario_vista").clearValidation();
    $('#formulario_vista')[0].reset();
    // refresca la grilla de facturas
    grilla_listar_facturas('','');
    // Acomoda paneles
    $('#panel_formulario_vista').slideUp();
    $('#panel_busqueda_facturas').slideDown();
});

// Cierra y Limpia el formulario MODIFICACION cuando se pulsa en cancelar
$("#btn_cancelar_modificacion").click(function() {
    // scrollea la pagina hasta arriba al cargar la funcion
    window.scrollTo(0,0);  
    // Muestra el menu de los tabs
    $('#menu_tabs').show();
    // Limpia formulario
    $("#formulario_modificacion").clearValidation();
    $('#formulario_modificacion')[0].reset();
    // Acomoda paneles
    $('#panel_formulario_modificacion').slideUp();
    $('#panel_formulario_vista').slideDown();
});

// Limpia el formulario ALTA FCI cuando se pulsa en cancelar. No limpia el campo readonly
$("#btn_cancelar_nuevo_fci").click(function() {
    $('input,textarea').not('[readonly],[disabled],:button,select').val(''); 
});

// Cierra y Limpia el formulario MODIFICACION FCI cuando se pulsa en cancelar
$("#btn_cancelar_modificacion_fci").click(function() {
    // scrollea la pagina hasta arriba al cargar la funcion
    window.scrollTo(0,0);  
       // Limpia formulario
    $("#formulario_modificacion_fci").clearValidation();
    $('#formulario_modificacion_fci')[0].reset();
    // Acomoda paneles
    $('#panel_formulario_alta_fci').slideDown();
    $('#panel_formulario_modificacion_fci').slideUp();
});

// Cierra y Limpia el formulario MODIFICACION cuando se pulsa en cancelar
$("#btn_cancelar_autorizacion_factura").click(function() {
    // scrollea la pagina hasta arriba al cargar la funcion
    window.scrollTo(0,0);    
    // Limpia formulario
    $("#formulario_autorizacion_fci").clearValidation();
    $('#formulario_autorizacion_fci')[0].reset();
});



let verifyCheckboxes = function () {

    var alMenosUnoSeleccionado = $('input[class="item_f_check"]:checked').length > 0;
    if (alMenosUnoSeleccionado) {
        $('#container_nueva_factura_pagador').show();
    } else {
        $('#container_nueva_factura_pagador').hide();
    }
};

let SaveNewPayer = function () {
    let error = false;
    let seleccionados = "";

    if ($("#nuevo_pagador").val() == 0) {
        $.Notification.autoHideNotify('error', 'top center', 'Error...', 'Debe seleccionar un pagador');
        error = true;
    }
    if ($("#nueva_factura").val() == '') {
        $.Notification.autoHideNotify('error', 'top center', 'Error...', 'Debe ingresar el nuevo número de factura');
        error = true;
    }

    if (!error) {
        $('input[name="item_f_check[]"]:checked').each(function () {
            seleccionados += $(this).val() + ",";
        });
        seleccionados = seleccionados.substring(0, seleccionados.length - 1);
        $('#fci_seleccionados').val(seleccionados);

        let parametros = {
            "opcion": 'nuevo_pagador',
            "nuevo_pagador": $("#nuevo_pagador").val(),
            "nueva_factura": $("#nueva_factura").val(),
            "items": seleccionados
        };
        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: "facturacion_cb.php",
            data: parametros,
            success: function (data) {

                if (data != false) {

                    $.Notification.autoHideNotify('success', 'top center', 'Datos guardados correctamente...', 'Datos guardados correctamente.');

                    $("#nuevo_pagador").val(0);
                    $("#nueva_factura").val("");
                    $('input[name="item_f_check[]"]').prop("checked", false);
                    grilla_fcItems(1);

                } else {
                    $.Notification.autoHideNotify('error', 'top center', 'Ocurrio un erro...', 'Ocurrio un erro');
                }

            },
            error: function (xhr, ajaxOptions, thrownError) {
                $.Notification.autoHideNotify('error', 'top center', 'Error...', 'Reporte del error. ' + xhr.responseText);
            }
        });

    }
};

