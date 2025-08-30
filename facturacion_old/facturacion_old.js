// Funciones que se van a ejecutar en la primer carga de la página.
$().ready(function(){
     
    // Oculta el menu de los tabs hasta que se active la busqueda
    $('#menu_tabs').hide();
    // Acomoda los paneles
    $('#panel_formulario_alta').slideUp();
    $('#panel_grilla').slideDown();
});




// Formulario
// 
// Formulario de LECTURA de una factura
function formulario_lectura(factura_id) {
    
    // scrollea la pagina hasta arriba al cargar la funcion
    window.scrollTo(0,0);
    
    grilla_listar_log_factura(factura_id);
    
    let parametros = {
        "factura_id": factura_id,
        "opcion": 'formulario_lectura'
    };
    
    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'facturacion_cb_old.php',
        data: parametros,
        success:function(data){

            // Formatea los campos de monedas
            $('#factura_importe_medicoOrigen_v').number( true, 2, ',', '.' );
            $('#factura_importe_feeOrigen_v').number( true, 2, ',', '.' );
            $('#factura_tipoCambio_v').number( true, 2, ',', '.' );
            $('#factura_importeUSD_v').number( true, 2, ',', '.' );
            $('#factura_importeAprobadoUSD_v').number( true, 2, ',', '.' );
            $('#factura_importeRechazadoUSD_v').number( true, 2, ',', '.' );
            
            $('#factura_id_v').val(data.factura_id);
            $('#factura_tipo_id_v').val(data.tipoFactura);
            $('#factura_numero_v').val(data.numeroFactura);
            $('#factura_pagador_id_v').val(data.clienteFactura);
            $('#factura_prioridad_id_v').val(data.prioridadFactura);
            $('#factura_fechaIngresoSistema_v').val(data.fechaIngresoSistemaFactura);
            $('#factura_fechaEmision_v').val(data.fechaEmisionFactura);
            $('#factura_fechaRecepcion_v').val(data.fechaRecepcionFactura);
            $('#factura_fechaVencimiento_v').val(data.fechaVencimientoFactura);
            $('#factura_fechaPago_v').val(data.fechaPagoFactura);
            $('#factura_importe_medicoOrigen_v').val(data.importeMedicoFactura);
            $('#factura_importe_feeOrigen_v').val(data.feeOrigenFactura);
            $('#factura_moneda_id_v').val(data.monedaFactura);
            $('#factura_tipoCambio_v').val(data.tipoCambioFactura);
            $('#factura_importeUSD_v').val(data.importeUSDFactura);
            $('#factura_importeAprobadoUSD_v').val(data.importeAprobadoUSDFactura);
            $('#factura_importeRechazadoUSD_v').val(data.importeRechazadoUSDFactura);
            $('#factura_observaciones_v').val(data.observacionesFactura);
         
            // Deshabilita el boton modificar si la factura avanzo en el proceso de aprobacion
            let estadoFactura = data.estadoFactura;
            
            // Llama a la funcion que preparar el formulario
            preparar_formulario_lectura(estadoFactura);
            
            // Importante para que luego de la carga dinámica vuelvan a funcionar los tooltips
            $('[data-toggle="tooltip"]').tooltip();
        }
    });
};

// Formulario de AUTORIZACION de una factura
$("#formulario_pago").validate({  
    ignore: [],
    rules: {
        factura_fechaPago_auto: {
            required: true
        },
        factura_formaPago_auto: {
            required: true
        }
    },
    messages: {
        factura_fechaPago_auto: {
            required: "Por favor ingrese una fecha de pago"
        },
        factura_formaPago_auto: {
            required: "Por favor seleccione una forma de pago"
        }
    },
    
    submitHandler: function (form) {
        
        $.ajax({
            type: "POST",
            url: "facturacion_cb_old.php",
            data: $(form).serialize(),
            success: function (data) {
                $.Notification.autoHideNotify('success', 'top center', 'La factura se modifico exitosamente...', 'Los cambios han sido guardados.');
                
                // Actualiza la grilla
                let caso_id = $('#caso_id').val();
                grilla_listar_facturas(caso_id);
            
                // Habilita los campos con .prop("disabled")
                $('#caso_numero_buscar').prop("disabled", false);
                $('#btn_lupa').prop("disabled", false);
                
                // Limpia formulario autorizacion
                $("#formulario_pago").clearValidation();
                $('#formulario_pago')[0].reset();
                
                // Oculta modal de autorizacion de facturas
                $('#modalPagoFacturas').modal('toggle');  
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $.Notification.autoHideNotify('error', 'top center', 'Error...', 'Reporte del error. ' + xhr.responseText);
            }
        });
        
        // Previene que el formulario haga POST después de la validación, ya que ejecuto el guardado por Ajax.
        return false;
    }
});




// Funciones auxiliares formulario
// 
// Función para resetear validaciones que quedan arraigadas al formulario al menos que se las saque explícitamente.
$.fn.clearValidation = function(){let v = $(this).validate();$('[name]',this).each(function(){v.successList.push(this);v.showErrors();});v.resetForm();v.reset();};

// Va a buscar los datos del caso y llama a las funciones de las grillas
$("#btn_lupa").on('click', function() {    
    
    let caso_numero_b = $("#caso_numero_buscar").val();
    
    let parametros = {
        "caso_numero_b": caso_numero_b,
        "opcion": 'datos_caso'
    };

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'facturacion_cb_old.php',
        data: parametros,
        success:function(data){
            
            $('#caso_id').val(data.caso_id);
            $('#caso_beneficiario_b').val(data.beneficiario);
            $('#caso_fecha_b').val(data.casoFecha);
            $('#caso_voucher_b').val(data.casoVoucher);
            $('#caso_agencia_b').val(data.casoAgencia);
            $('#caso_producto_b').val(data.producto);
            
            $('#menu_tabs').show(); // muestra el menu de los tabs
            grilla_listar_servicios(data.caso_id);
            grilla_listar_facturas(data.caso_id);
            grilla_listar_facturas_pendientes_autorizar(data.caso_id);
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });
});

// Funcion para cargar el formulario modificacion con la info de la factura seleccionada
$("#btn_modificar_factura").on('click', function() {
    
    let factura_id = $('#factura_id_v').val();
    
    var parametros = {
        "factura_id": factura_id,
        "opcion": 'formulario_lectura'
    };
    
    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'facturacion_cb_old.php',
        data: parametros,
        success:function(data){
            
            $('#factura_id_m').val(data.factura_id);
            listar_tiposFacturas_modificacion(data.factura_id);
            $('#factura_numero_m').val(data.numeroFactura);
            listar_facturasPagador_modificacion(data.factura_id);
            listar_facturasPrioridades_modificacion(data.factura_id);
            $('#factura_fechaIngresoSistema_m').val(data.fechaIngresoSistemaFactura);
            $('#factura_fechaEmision_m').val(data.fechaEmisionFactura);
            $('#factura_fechaRecepcion_m').val(data.fechaRecepcionFactura);
            $('#factura_fechaVencimiento_m').val(data.fechaVencimientoFactura);
            //$('#fechaPagoFactur_m').val(data.fechaPagoFactura);
            $('#factura_importe_medicoOrigen_m').val(data.importeMedicoFactura);
            $('#factura_importe_feeOrigen_m').val(data.feeOrigenFactura);
            listar_facturasMonedas_modificacion(data.factura_id);
            $('#factura_tipoCambio_m').val(data.tipoCambioFactura);
            $('#factura_importeUSD_m').val(data.importeUSDFactura);
            $('#factura_observaciones_m').val(data.observacionesFactura);
            
            preparar_formulario_modificacion();
        }
    });
});


// Funcion para la llamada al formulario de PAGO de facturas
let pagar_facturas = function(factura_id) {
    
    let parametros = {
        "factura_id": factura_id,
        "opcion": 'facturas_pendiente_autorizar'
    };

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'facturacion_cb_old.php',
        data: parametros,
        success:function(data){
            
            // Datos para ingresar en formulario
            $('#factura_id_au').val(data.idFactura);
            $('#factura_numero_au').val(data.numeroFactura);
            $('#factura_estado_au').val(data.estadoFactura);
            $('#factura_importeUSD_au').val(data.importeUSDFactura);
            $('#factura_importeAprobadoUSD_au').val(data.importeAprobadoUSD);

            preparar_formulario_pago(data.estadoFacturaId);
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });
}

// Funcion que valida cuando se debe mostrar el DIV motivoRechazoFactura
let mostrar_motivo_rechazo = function(factura_log_estado) {
    
    if (factura_log_estado === '3' || factura_log_estado === '5' || factura_log_estado === '7') {
        $('.motivoRechazoFactura').show();
    } else {
        $('.motivoRechazoFactura').hide();
    }
}

// Valida si el importe rechazado es mayor a 0 para el formulario AUTORIZACION
$.validator.addMethod('maxStrict', function (value, el, param) {
    return 0 <= param;
});




// Funciones para preparar los distintos formularios
// 
// Prepara el formulario de ALTA de facturas
let preparar_formulario_alta = function(caso_id) {
    
    // scrollea la pagina hasta arriba la cargar la funcion
    window.scrollTo(0,0);
    
    // Completa el id del caso al campo oculto factura_caso_id_n para el formulario alta
    $('#factura_caso_id_n').val(caso_id);
    
    // Deshabilita ciertos campos y los botones de busqueda
    $('#factura_tipoCambio_n').prop("disabled", true);
    $('#factura_importeUSD_n').prop("disabled", true);    
    $('#caso_numero_buscar').prop("disabled", true);
    $('#btn_lupa').prop("disabled", true);
    
    // Limpia los select
    $("#factura_tipo_id_n").empty();
    $("#factura_pagador_id_n").empty();
    $("#factura_prioridad_id_n").empty();
    $("#factura_moneda_id_n").empty();
    
    // Agrega la informacion a los distintos Select en el formulario de ALTA
    listar_tiposFacturas_alta();
    listar_facturasPrioridades_alta();
    listar_facturasPagador_alta();
    listar_facturasMonedas_alta();
    
    // Date picker
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
    
    // Formatea los campos de monedas
    $('#factura_importe_medicoOrigen_n').number( true, 2, ',', '.' );
    $('#factura_importe_feeOrigen_n').number( true, 2, ',', '.' );
    $('#factura_tipoCambio_n').number( true, 2, ',', '.' );
    $('#factura_importeUSD_n').number( true, 2, ',', '.' );
    
    // Acomoda los paneles
    $('#panel_formulario_alta').removeClass('hidden');
    $('#panel_formulario_alta').slideDown();
    $('#panel_grilla').slideUp();
};

// Prepara el formulario de MODIFICACION de facturas
let preparar_formulario_lectura = function(estadoFactura) {
    
    // scrollea la pagina hasta arriba la cargar la funcion
    window.scrollTo(0,0);
    
    // Deshabilita los botones de busqueda
    $('#caso_numero_buscar').prop("disabled", true);
    $('#btn_lupa').prop("disabled", true);

    // Deshabilita el boton modificar si la factura avanzo en el proceso de aprobacion
    if (estadoFactura != 1 && estadoFactura != 2) {
        $('#btn_modificar_factura').prop("disabled", true);
    } else {
        $('#btn_modificar_factura').prop("disabled", false);
    }
    
    // Acomoda los paneles
    $('#panel_formulario_alta').slideUp();
    $('#panel_formulario_vista').removeClass('hidden');
    $('#panel_formulario_vista').slideDown();
    $('#panel_grilla').slideUp(); 
};

// Prepara el formulario de MODIFICACION de facturas
let preparar_formulario_modificacion = function() {
    
    // scrollea la pagina hasta arriba la cargar la funcion
    window.scrollTo(0,0);
    
    // Deshabilita ciertos campos y los botones de busqueda
    $('#factura_tipoCambio_m').prop("disabled", true);
    $('#factura_importeUSD_m').prop("disabled", true);
    $('#caso_numero_buscar').prop("disabled", true);
    $('#btn_lupa').prop("disabled", true);
    
    // Limpia los select
    $("#factura_tipo_id_m").empty();
    $("#factura_pagador_id_m").empty();
    $("#factura_prioridad_id_m").empty();
    $("#factura_moneda_id_m").empty();
    
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
    
    // Formatea los campos de monedas
    $('#factura_importe_medicoOrigen_m').number( true, 2, ',', '.' );
    $('#factura_importe_feeOrigen_m').number( true, 2, ',', '.' );
    $('#factura_tipoCambio_m').number( true, 2, ',', '.' );
    $('#factura_importeUSD_m').number( true, 2, ',', '.' );
    
    // Acomoda los paneles
    $('#panel_formulario_modificacion').removeClass('hidden');
    $('#panel_formulario_modificacion').slideDown();
    $('#panel_formulario_vista').slideUp();
};

// Prepara el formulario de PAGO de facturas
let preparar_formulario_pago = function(estadoFacturaId) {
    
    // Muestra el modal de autorizacion de facturas
    $('#modalPagoFacturas').modal('show'); 
    
    // Date picker
    $('#factura_fechaPago_auto').datepicker({
        maxDate: new Date(),
        yearRange: '-1:+0'
    });
    
    // Limpia los select
    $("#factura_formaPago_auto").empty();
    
    // Agrega la informacion a los distintos Select en el formulario de AUTORIZACION
    listar_formasPagos_autorizacion();

};




// Funciones para completar los SELECT (En formularios ALTA)
// 
// Tipos Facturas para el Formulario de Alta
let listar_tiposFacturas_alta = function() {

    let parametros = {
        "opcion": 'listar_tiposFacturas_alta'
    };

    let miselect = $("#factura_tipo_id_n");

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'facturacion_cb_old.php',
        data: parametros,
        success:function(data){

            miselect.append('<option value="">Seleccione</option>');

            for (let i=0; i<data.length; i++) {
                miselect.append('<option value="' + data[i].tipoFactura_id + '">' + data[i].tipoFactura_nombre + '</option>');
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });  
};

// Facturas Prioridades para el Formulario de Alta
let listar_facturasPrioridades_alta = function(){

    let parametros = {
        "opcion": 'listar_facturasPrioridades_alta'
    };

    let miselect = $("#factura_prioridad_id_n");

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'facturacion_cb_old.php',
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

// Facturas Prioridades para el Formulario de Alta
let listar_facturasPagador_alta = function(){
    
    let caso_id = $("#caso_id").val();
    
    let parametros = {
        "caso_id": caso_id,
        "opcion": 'listar_facturasPagador_alta'
    };

    let miselect = $("#factura_pagador_id_n");

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'facturacion_cb_old.php',
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

// Facturas Prioridades para el Formulario de Alta
let listar_facturasMonedas_alta = function(){
    
    let parametros = {
        "opcion": 'listar_facturasMonedas_alta'
    };

    let miselect = $("#factura_moneda_id_n");

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'facturacion_cb_old.php',
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


// Tipos Facturas para el Formulario de Modificacion
let listar_tiposFacturas_modificacion = function(factura_id){

    let parametros = {
        "opcion": 'listar_tiposFacturas_modificacion',
        "factura_id": factura_id
    };

    let miselect = $("#factura_tipo_id_m");

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'facturacion_cb_old.php',
        data: parametros,
        success:function(data){
            for (let i=0; i<data.length; i++) {
                miselect.append('<option value="' + data[i].tipoFactura_id + '">' + data[i].tipoFactura_nombre + '</option>');
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });  
};

// Facturas Prioridades para el Formulario de Modificacion
let listar_facturasPrioridades_modificacion = function(factura_id){

    let parametros = {
        "opcion": 'listar_facturasPrioridades_modificacion',
        "factura_id": factura_id
    };

    let miselect = $("#factura_prioridad_id_m");

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'facturacion_cb_old.php',
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

// Facturas Prioridades para el Formulario de Modificacion
let listar_facturasPagador_modificacion = function(factura_id){

    let parametros = {
        "opcion": 'listar_facturasPagador_modificacion',
        "factura_id": factura_id
    };

    let miselect = $("#factura_pagador_id_m");

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'facturacion_cb_old.php',
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

// Facturas Prioridades para el Formulario de Modificacion
let listar_facturasMonedas_modificacion = function(factura_id){

    let parametros = {
        "opcion": 'listar_facturasMonedas_modificacion',
        "factura_id": factura_id
    };

    let miselect = $("#factura_moneda_id_m");

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'facturacion_cb_old.php',
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

// Tipos Facturas para el Formulario de Alta
let listar_formasPagos_autorizacion = function() {

    let parametros = {
        "opcion": 'listar_formasPagos_autorizacion'
    };

    let miselect = $("#factura_formaPago_auto");

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'facturacion_cb_old.php',
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




// Funciones para los importes, cambio de monedas, etc
// 
// Formulario ALTA - Al cambiar el select de moneda, llama a la funcion 'consultar_tipo_cambio' (consulta API)
$("#factura_moneda_id_n").on('change', function() {    
    // Selecciona elementos del formulario ALTA
    let currency        = $("#factura_moneda_id_n option:selected").text();
    let importe_medico  = $("#factura_importe_medicoOrigen_n").val();
    let importe_fee     = $("#factura_importe_feeOrigen_n").val();
    let amount          = Number(importe_medico + importe_fee);
    
    consultar_tipo_cambio('alta',currency,amount);
});

// Formulario ALTA - Al modificarse el campo importe medico o importe fee, llama a la funcion 'calcular_importe_usd'
$('#factura_importe_medicoOrigen_n').add('#factura_importe_feeOrigen_n').on('keyup', function() {
    
    let importe_medico  = $('#factura_importe_medicoOrigen_n').val();
    let importe_fee     = $("#factura_importe_feeOrigen_n").val();
    let importe_origen  = parseInt(importe_medico + importe_fee);
    let tipo_cambio     = $('#factura_tipoCambio_n').val();
    let resultado       = calcular_importe_usd(importe_origen,tipo_cambio);
    
    $('#factura_importeUSD_n').val(resultado);
});

// Formulario MODIFICACION - Al cambiar el select de moneda, llama a la funcion 'consultar_tipo_cambio' (consulta API)
$("#factura_moneda_id_m").on('change', function() {    
    // Selecciona elementos del formulario ALTA
    let currency        = $("#factura_moneda_id_m option:selected").text();
    let importe_medico  = $("#factura_importe_medicoOrigen_m").val();
    let importe_fee     = $("#factura_importe_feeOrigen_m").val();
    let amount          = Number(importe_medico + importe_fee);
    
    consultar_tipo_cambio('modificacion',currency,amount);
});

// Formulario MODIFICACION - Al modificarse el campo importe medico o importe fee, llama a la funcion 'calcular_importe_usd'
$('#factura_importe_medicoOrigen_m').add('#factura_importe_feeOrigen_m').on('keyup', function() {
    
    let importe_medico  = $('#factura_importe_medicoOrigen_m').val();
    let importe_fee     = $("#factura_importe_feeOrigen_m").val();
    let importe_origen  = parseInt(importe_medico + importe_fee);
    let tipo_cambio     = $('#factura_tipoCambio_m').val();
    let resultado       = calcular_importe_usd(importe_origen,tipo_cambio);
    
    $('#factura_importeUSD_m').val(resultado);
});

// Funcion para consultar el tipo de cambio - Consulta API
function consultar_tipo_cambio(form, moneda, importe_origen) {
    
    // Setea la conexion con la API
    endpoint = 'live';
    access_key = '2NgMGSgT3Ea2UNJZZEHVL7vEYgniu1oq';
    
    $.ajax({
        url: 'https://api.apilayer.com/currency_data/' + endpoint + '?source=&currencies=' + moneda,
        dataType: 'JSON',
        headers: {
            'apikey': access_key
        },
        success: function(data) {
            
            let tipo_cambio;

            // Busca en la primer posicion del objeto data.quotes y lo guarda en 'tipo_cambio'
            if (moneda === 'USD') {
                tipo_cambio = '1';
            } else {
                tipo_cambio = data.quotes[Object.keys(data.quotes)[0]];
            }
            
            // Llama a la funcion 'calcular_importe_usd'
            let resultado = calcular_importe_usd(importe_origen,tipo_cambio);    
            
            // Resultados
            if (form === 'alta') {
                $("#factura_tipoCambio_n").val(tipo_cambio);
                $('#factura_importeUSD_n').val(resultado);
            } else if (form === 'modificacion') {
                $("#factura_tipoCambio_m").val(tipo_cambio);
                $('#factura_importeUSD_m').val(resultado);
            } else {
                $.Notification.autoHideNotify('error', 'top right', 'Error al convertir la moneda');
            }
        },
        error: function (){
            
            //$('#servicio_tipoCambio_n').prop("disabled", false);
            
        }
    });
}

// Funcion para calcular la conversion a USD
function calcular_importe_usd(importe_origen,tipo_cambio) {
    
    // Calcula: importe_origen / tipo_cambio
    let calculo = importe_origen / tipo_cambio;
    
    return calculo;
}




// Funciones de grilla
// 
// Va a buscar los datos para la grilla de servicios
let grilla_listar_servicios = function(caso_id) {
    
    let parametros = {
        "caso_id": caso_id,
        "opcion": 'grilla_listar_servicios'
    };
    $.ajax({        
        method: "POST",
        dataType: "HTML",
        url: "facturacion_cb_old.php",
        data: parametros
    }).done(function(data){	
        $("#grilla_servicios").html(data);
        listar_servicios();
    });
};

// Hace arrancar el dataTable de servicios
let listar_servicios = function() {
    $("#dt_facturacion_servicios").DataTable({   
        "destroy":true,
        "stateSave": true,
        "bFilter": false,
        "language": idioma_espanol
    });
};

// Va a buscar los datos para la grilla de facturas
let grilla_listar_facturas = function(caso_id) {
    
    let parametros = {
        "caso_id": caso_id,
        "opcion": 'grilla_listar_facturas'
    };
    $.ajax({        
        method: "POST",
        dataType: "HTML",
        url: "facturacion_cb_old.php",
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

// Va a buscar los datos para la grilla de log de factura
let grilla_listar_facturas_pendientes_autorizar = function(caso_id) {
    
    let parametros = {
        "caso_id": caso_id,
        "opcion": 'listar_facturas_pendientes_autorizar'
    };
    $.ajax({        
        method: "POST",
        dataType: "HTML",
        url: "facturacion_cb_old.php",
        data: parametros
    }).done(function(data){	
        $("#grilla_autorizacion_facturas").html(data);
        listar_facturas_pendientes_autorizar();
    });
};

//Hace arrancar el dataTable de autorizacion de facturas
let listar_facturas_pendientes_autorizar = function() {
    
    // Importante para que luego de la carga dinámica vuelvan a funcionar los tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    $("#dt_facturas_pendientes_autorizar").DataTable({   
        "destroy":true,
        "stateSave": true,
        "bFilter": false,
        "language": idioma_espanol
    });
};

// Va a buscar los datos para la grilla de log de factura
let grilla_listar_log_factura = function(factura_id) {
    
    let parametros = {
        "factura_id": factura_id,
        "opcion": 'grilla_listar_log_factura'
    };
    $.ajax({        
        method: "POST",
        dataType: "HTML",
        url: "facturacion_cb_old.php",
        data: parametros
    }).done(function(data){	
        $("#grilla_log_factura").html(data);
        listar_log_factura();
    });
};

//Hace arrancar el dataTable del log de facturas
let listar_log_factura = function() {
    $("#dt_facturacion_log_factura").DataTable({   
        "destroy":true,
        "stateSave": true,
        "bFilter": false,
        "language": idioma_espanol
    });
};

//Idioma de las grillas
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
    // Limpia formulario
    $("#formulario_alta").clearValidation();
    $('#formulario_alta')[0].reset();
    // Habilita los botones de busqueda
    $('#caso_numero_buscar').prop("disabled", false);
    $('#btn_lupa').prop("disabled", false);
    // Acomoda paneles
    $('#panel_formulario_alta').slideUp();
    $('#panel_grilla').slideDown();
});

// Cierra y Limpia el formulario VISTA cuando se pulsa en cancelar
$("#btn_cancelar_vista").click(function() {
    // scrollea la pagina hasta arriba al cargar la funcion
    window.scrollTo(0,0);    
    // Limpia formulario
    $("#formulario_vista").clearValidation();
    $('#formulario_vista')[0].reset();
    // Habilita los botones de busqueda
    $('#caso_numero_buscar').prop("disabled", false);
    $('#btn_lupa').prop("disabled", false);
    // Acomoda paneles
    $('#panel_formulario_vista').slideUp();
    $('#panel_grilla').slideDown();
});

// Cierra y Limpia el formulario MODIFICACION cuando se pulsa en cancelar
$("#btn_cancelar_modificacion").click(function() {
    // scrollea la pagina hasta arriba al cargar la funcion
    window.scrollTo(0,0);    
    // Limpia formulario
    $("#formulario_modificacion").clearValidation();
    $('#formulario_modificacion')[0].reset();
    // Habilita los botones de busqueda
    $('#caso_numero_buscar').prop("disabled", false);
    $('#btn_lupa').prop("disabled", false);
    // Acomoda paneles
    $('#panel_formulario_modificacion').slideUp();
    $('#panel_formulario_vista').slideDown();
});

// Cierra y Limpia el formulario AUTORIZACION cuando se pulsa en cancelar
$("#btn_cancelar_autorizacion_factura").click(function() {
    // scrollea la pagina hasta arriba al cargar la funcion
    window.scrollTo(0,0);    
    // Limpia formulario
    $("#formulario_pago").clearValidation();
    $('#formulario_pago')[0].reset();
});

// Cierra y Limpia formulario LOG FACTURA cuando se pulsa en cerrar
let btn_cerrar_log_factura = function() {
    // scrollea la pagina hasta arriba al cargar la funcion
    window.scrollTo(0,0);    
    // Limpia formulario
    $("#formulario_vista").clearValidation();
    $('#formulario_vista')[0].reset();
    // Habilita los botones de busqueda
    $('#caso_numero_buscar').prop("disabled", false);
    $('#btn_lupa').prop("disabled", false);
    // Acomoda paneles
    $('#panel_formulario_vista').slideUp();
    $('#panel_grilla').slideDown();
}