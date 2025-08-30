//Verifica que se halla cargado la página para ejecutar las funciones.		
$().ready(function(){
     
    // Funciones que se van a ejecutar en la primer carga de la página.
    $('#servicio_tipoCambio_n').prop("disabled", true);
    $('#servicio_presuntoUSD_n').prop("disabled", true);
    
    // Agrega la informacion a los distintos Select en el formulario de ALTA de SERVICIOS
    // Select Monedas
    listarMoneda_altaServicio();
    
    // Agrega la informacion a los select en el formulario de BUSQUEDA DE PRESTADOR
    // Select Tipos de prestador    
    listarTipoPrestador_buscarPrestador();
    // Select Paises
    listarPaises_buscarPrestador();
     
    // Formatea los campos monetarios
    $('#servicio_presuntoOrigen_n').number( true, 2, ',', '.' );
    $('#servicio_tipoCambio_n').number( true, 2, ',', '.' );
    $('#servicio_presuntoUSD_n').number( true, 2, ',', '.' );
    $('#servicio_presuntoOrigen').number( true, 2, ',', '.' );
    $('#servicio_tipoCambio').number( true, 2, ',', '.' );
    $('#servicio_presuntoUSD').number( true, 2, ',', '.' );
    
    // Carga los datos en la grilla
    grilla_listar('','');
    
    // Acomoda los paneles para la primer vista.
    $('#panel_formulario_gop').slideUp();
    $('#panel_formulario_alta').slideDown();
    $('#panel_formulario_modificacion').slideUp();
    $('#panel_grilla_busquedaPrestador').slideUp();
});


// Validación de formulario de modificacion y procesamiento
$("#formulario_modificacion").validate({
    ignore: [],     
    rules:{
        servicio_prestador_id: {
            required: true    
        },
        servicio_practica_id: {
            required: true    
        },
        servicio_moneda_id: {
            required: true
        },
        servicio_tipoCambio: {
            required: true,
            min: 0.01
        },
        // servicio_presuntoOrigen: {
        //     required: true,
        //     min: 0.01
        // },
        servicio_presuntoUSD: {
            required: true
        }
    },
    
    messages:{
        servicio_prestador_id: {
            required: "Por favor seleccione un prestador"
        },
        servicio_practica_id: {
            required: "Por favor seleccione una practica"
        },
        servicio_moneda_id: {
            required: "Por favor ingrese la moneda"
        },
        servicio_tipoCambio: {
            required: "Por favor ingrese el tipo de cambio",
            min: "El tipo de cambio no puede ser menor a 0,01"
        },
        // servicio_presuntoOrigen: {
        //     required: "Por favor ingrese el presunto de origen",
        //     min: "El presunto no puede ser nulo"
        // },
        servicio_presuntoUSD: {
            required: "Por favor ingrese el presunto en dolares"
        }
    },
    
    submitHandler: function (form) {
        
        // Quita el disabled para que se guarde al modificar
        $('#servicio_practica_id').prop("disabled", false)
        $('#servicio_moneda_id').prop("disabled", false)
        $('#servicio_tipoCambio').prop("disabled", false);
        $('#servicio_presuntoUSD').prop("disabled", false);
        $('#servicio_presuntoOrigen').prop("disabled", false)

        // Cambia le estado de autorizado si se hizo check en servicio_autorizado
        if ($('#servicio_autorizado').prop("checked")){
            $('#servicio_autorizado').val('1');
        } else {
            $('#servicio_autorizado').val('0');
        }
        
        $.ajax({
            type: "POST",
            url: "servicio_cb.php",
            data: $(form).serialize(),
            beforeSend: function () {
                $.Notification.autoHideNotify('warning', 'top right', 'El Servicio está siendo guardado...', 'Aguarde un instante por favor.');
            },
            success: function () {
                $('.notifyjs-wrapper').trigger('notify-hide');
                $.Notification.autoHideNotify('success', 'top right', 'Grabada exitosamente!...','Los cambios han sido guardados.');
                
                // Agrega el disabled para que no se puedan modificar los campos
                $('#servicio_tipoCambio').prop("disabled", true);
                $('#servicio_presuntoUSD').prop("disabled", true);
                
                grilla_listar('','');
                // Limpia formularios y campos
                $("#formulario_alta").clearValidation();
                $("#servicio_prestador_nombre_n").val('');
                $("#servicio_prestador_id_n").val('');
                $("#servicio_practica_id_n").empty();
                // Acomoda los paneles
                $('#panel_formulario_gop').slideUp();
                $('#panel_formulario_alta').slideDown();
                $('#panel_formulario_modificacion').slideUp();
                $('#panel_grilla_busquedaPrestador').slideUp();
                $('#panel_grilla').slideDown();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $.Notification.autoHideNotify('error', 'top right', 'Error...', 'Reporte del error. ' + xhr.responseText);
            }
        });
        return false; // Previene que el formulario haga POST después de la validación, ya que ejecuto el guardado por Ajax.
    }
});


// Validación de formulario de alta y procesamiento de un SERVICIO
$("#formulario_alta").validate({
    ignore: [],     
    rules:  {
        servicio_prestador_id_n: {
            required: true    
        },
        servicio_practica_id_n: {
            required: true    
        },
        servicio_moneda_id_n: {
            required: true
        },
        servicio_tipoCambio_n: {
            required: true,
            min: 0.01
        },
        // servicio_presuntoOrigen_n: {
        //     required: true,
        //     min: 0.01
        // },
        servicio_presuntoUSD_n: {
            required: true
        }
    },     
    
    messages: {
        servicio_prestador_id_n: {
            required: "Por favor seleccione un prestador"
        },
        servicio_practica_id_n: {
            required: "Por favor seleccione una practica"
        },
        servicio_moneda_id_n: {
            required: "Por favor ingrese la moneda"
        },
        servicio_tipoCambio_n: {
            required: "Por favor ingrese el tipo de cambio",
            min: "El tipo de cambio no puede ser menor a 0,01"
        },
        // servicio_presuntoOrigen_n: {
        //     required: "Por favor ingrese el presunto de origen",
        //     min: "El presunto no puede ser nulo"
        // },
        servicio_presuntoUSD_n: {
            required: "Por favor ingrese el presunto en dolares"
        }
    },
                    
    submitHandler: function (form) {
        
        //valida los presunto contra la opcion "Asistencia sin costo" en la configuracion del caso
        console.log($("#no_medical_cost_v",top.document).prop("checked"));
        // if ( $('#servicio_presuntoUSD_n').val()== 0.00 && !$("#no_medical_cost_v",top.document).prop("checked") ) {
        //     $.Notification.autoHideNotify('error', 'top right', 'Error...', 'Para presunto origen 0, debe marcar "Asistencia sin costo" en el caso.');
        //     return false;
        // }

        if ( $('#servicio_presuntoUSD_n').val() > 0.00 && $("#no_medical_cost_v",top.document).prop("checked") ) {
            $.Notification.autoHideNotify('error', 'top right', 'Error...', 'Para presunto origen mayor a 0, debe desmarcar "Asistencia sin costo" en el caso.');
            return false;
        }

        // Deshabilita boton Guardar al hacer el submit
        $("#btn_guardar_nuevo").attr("disabled", true);
        
        // Quita el disabled para que se guarde al momento del alta
        $('#servicio_tipoCambio_n').prop("disabled", false);
        $('#servicio_presuntoUSD_n').prop("disabled", false);
        
        $.ajax({
            type: "POST",
            url: "servicio_cb.php",
            data: $(form).serialize(),
            beforeSend: function () {
                $.Notification.autoHideNotify('warning', 'top right', 'El Servicio está siendo guardado...', 'Aguarde un instante por favor.');
            },
            success: function (data) {
                
                var form_c = $(form).serializeArray();
                if(form_c[2]['name'] == "modalComunication"){
                    window.parent.closeModal();
                }
                console.log('resultado: ' + data);

                // Resultado comprobacion back-end. 
                // El 200 viene desde la confirmación de envio de mail desde 'alertas_mail'
                if (data == 200) {
                    $('.notifyjs-corner').empty();
                    $.Notification.autoHideNotify('success', 'top right', 'Grabada exitosamente...','Los cambios han sido guardados.');
                
                    // Agrega el disabled para que no se puedan modificar los campos
                    $('#servicio_tipoCambio_n').prop("disabled", true);
                    $('#servicio_presuntoUSD_n').prop("disabled", true);                

                    grilla_listar('','');
                    // Limpia formularios y campos
                    $("#formulario_alta").clearValidation();
                    $("#servicio_prestador_nombre_n").val('');
                    $("#servicio_prestador_id_n").val('');
                    $("#servicio_practica_id_n").empty();
                    $('#formulario_alta')[0].reset();
                    // Acomoda los paneles
                    $('#panel_formulario_gop').slideUp();
                    $('#panel_formulario_alta').slideDown();
                    $('#panel_formulario_modificacion').slideUp();
                    $('#panel_grilla_busquedaPrestador').slideUp();
                    $('#panel_grilla').slideDown();
                    //habilita boton Guardar despues de realizado el insert
                    $("#btn_guardar_nuevo").attr("disabled", false);
                } else {
                    $('.notifyjs-corner').empty();
                    $.Notification.autoHideNotify('error', 'top right', 'Error...', 'Permisos insuficientes para cargar servicios.');
                   
                    // Agrega el disabled para que no se puedan modificar los campos
                    $('#servicio_tipoCambio_n').prop("disabled", true);
                    $('#servicio_presuntoUSD_n').prop("disabled", true);                

                    grilla_listar('','');
                    // Limpia formularios y campos
                    $("#formulario_alta").clearValidation();
                    $("#servicio_prestador_nombre_n").val('');
                    $("#servicio_prestador_id_n").val('');
                    $("#servicio_practica_id_n").empty();
                    $('#formulario_alta')[0].reset();
                    // Acomoda los paneles
                    $('#panel_formulario_gop').slideUp();
                    $('#panel_formulario_alta').slideDown();
                    $('#panel_formulario_modificacion').slideUp();
                    $('#panel_grilla_busquedaPrestador').slideUp();
                    $('#panel_grilla').slideDown();
                    //habilita boton Guardar despues de realizado el insert
                    $("#btn_guardar_nuevo").attr("disabled", false);
                }    
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $.Notification.autoHideNotify('error', 'top right', 'Error...', 'Reporte del error. ' + xhr.responseText);
            }
        });
        return false; // Previene que el formulario haga POST después de la validación, ya que ejecuto el guardado por Ajax.
    }
});


// Funciones para completar los SELECT
function listarPractica_modificarServicio(servicio_id, prestador_id, practica_id){
   
    var parametros = {
        "opcion": 'listarPractica_modificarServicio',
        "servicio_id" : servicio_id,
        "prestador_id" : prestador_id
    };
    
    var miselect = $("#servicio_practica_id");
    miselect.empty();
    
    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'servicio_cb.php',
        data: parametros,
        success:function(data){

            var selected = "";
            for (var i=0; i<data.length; i++) {
                
                selected = (practica_id ==data[i].practica_id) ? "selected" : "";
                
                miselect.append('<option data-presunto="' + data[i].presuntoOrigen + '" value="' + data[i].practica_id + '" ' +  selected + '>' + data[i].practica_nombre + '</option>');
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });
    
};


// Monedas para el Formulario de Alta de Servicio
function listarMoneda_altaServicio(){

    var parametros = {
        "opcion": 'listarMoneda_altaServicio'
    };

    var miselect = $("#servicio_moneda_id_n");

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'servicio_cb.php',
        data: parametros,
        success:function(data){
            
            miselect.append('<option value="">Seleccione moneda</option>');
            
            for (var i=0; i<data.length; i++) {
                miselect.append('<option value="' + data[i].moneda_id + '">' + data[i].moneda_nombre + '</option>');
                
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });
};


// Funcion para listar las monedas en el formulario de modificar el servicio
function listarMoneda_modificarServicio(servicio_id){
   
    var parametros = {
        "opcion": 'listarMoneda_modificarServicio',
        "servicio_id" : servicio_id
    };
    
    var miselect = $("#servicio_moneda_id");
    miselect.empty();
    
    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'servicio_cb.php',
        data: parametros,
        success:function(data){
            
            for (var i=0; i<data.length; i++) {
                miselect.append('<option value="' + data[i].moneda_id + '">' + data[i].moneda_nombre + '</option>');
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });
    
};


// Función para resetear validaciones que quedan arraigadas al formulario al menos que se las saque explícitamente.
$.fn.clearValidation = function(){var v = $(this).validate();$('[name]',this).each(function(){v.successList.push(this);v.showErrors();});v.resetForm();v.reset();};


//Eliminación de la servicio o borrado lógico
$( "#formulario_baja" ).on( "submit", function(e) {
    
  e.preventDefault();
  
    var servicio_id_b = $('#id_modalBaja').val();
  
    var parametros = {
        "servicio_id_b": servicio_id_b,
        "opcion": 'formulario_baja'
    };
 
  $.ajax({
            type: "POST",
            url: "servicio_cb.php",
            dataType:'JSON',
            data: parametros,
            success: function (resultado) {
                if(resultado=='ok'){
                    $.Notification.autoHideNotify('success', 'top right', 'Deshabilitada exitosamente...','Se ha deshabilitado el tipo de asistencia.');
                    grilla_listar('','');
                    
                }else{
                    $.Notification.autoHideNotify('error', 'top right', 'Error...', 'Descripción:' + resultado);
                }
               
             
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $.Notification.autoHideNotify('error', 'top right', 'Error...', 'Reporte del error. ' + xhr.responseText);
            }
  });
    
  $('#ventana_modal_borrado').modal('hide');
});


//Para volver a habilitar el servicio
$( "#formulario_habilita" ).on( "submit", function(e) {
    
  e.preventDefault();
  
    var servicio_id_a = $('#id_modalAlta').val();
  
    var parametros = {
        "servicio_id_a": servicio_id_a,
        "opcion": 'formulario_habilita'
    };
 
  $.ajax({
            type: "POST",
            url: "servicio_cb.php",
            dataType:'JSON',
            data: parametros,
            success: function (resultado) {
                if(resultado=='ok'){
                    $.Notification.autoHideNotify('success', 'top right', 'Habilitada exitosamente...','Se ha rehabilitado el tipo de asistencia.');
                    grilla_listar('','');
                    
                }else{
                    $.Notification.autoHideNotify('error', 'top right', 'Error...', 'Descripción:' + resultado);
                }
             
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $.Notification.autoHideNotify('error', 'top right', 'Error...', 'Reporte del error. ' + xhr.responseText);
            }
  });
    
  $('#ventana_modal_habilita').modal('hide');
});


// Carga los datos a editar en el formulario
function formulario_lectura(servicio_id){
    
    var parametros = {
        "servicio_id": servicio_id,
        "opcion": 'formulario_lectura'
    };
    
    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'servicio_cb.php',
        data: parametros,
        success:function(data){
            
            $('#servicio_id').val(data.servicio_id);
            $('#servicio_prestador_id').val(data.servicio_prestador_id);
            $('#servicio_prestador_nombre').val(data.prestador_nombre);
            var prestador_id = data.servicio_prestador_id 
            listarPractica_modificarServicio(servicio_id, prestador_id, data.servicio_practica_id);
            listarMoneda_modificarServicio(servicio_id);
            $('#servicio_tipoCambio').val(data.servicio_tipoCambio);
            $('#servicio_presuntoOrigen').val(data.servicio_presuntoOrigen);
            $('#servicio_presuntoUSD').val(data.servicio_presuntoUSD);
            $('#servicio_justificacion').val(data.servicio_justificacion);
            // Checkbox Servicio Autorizado
            var servicio_autorizado = data.servicio_autorizado;
                // Valida si el servicio esta autorizado. En caso de estarlo pone en disabled a los distintos elementos.
                if (servicio_autorizado == '1') {
                    // Campos disabled = true
                    $('#btn_buscar_prestador_formularioModificar').prop("disabled", true)
                    $('#servicio_practica_id').prop("disabled", true)
                    $('#servicio_moneda_id').prop("disabled", true)
                    $('#servicio_tipoCambio').prop("disabled", true)
                    $('#servicio_presuntoOrigen').prop("disabled", true)
                    $('#servicio_presuntoUSD').prop("disabled", true)
                    // Autorizado checked = true
                    $('#servicio_autorizado').prop('checked', true);
                } else if (servicio_autorizado == '0') {
                    // Campos disabled = false
                    $('#btn_buscar_prestador_formularioModificar').prop("disabled", false)
                    $('#servicio_practica_id').prop("disabled", false)
                    $('#servicio_moneda_id').prop("disabled", false)
                    $('#servicio_tipoCambio').prop("disabled", true)
                    $('#servicio_presuntoOrigen').prop("disabled", false)
                    $('#servicio_presuntoUSD').prop("disabled", true)
                    // Autorizado checked = false
                    $('#servicio_autorizado').prop('checked', false);
                }
            
            // Acomoda los paneles
            $('#panel_formulario_gop').slideUp();
            $('#panel_formulario_alta').slideUp();
            $('#panel_formulario_modificacion').removeClass('hidden');
            $('#panel_formulario_modificacion').slideDown();
            $('#panel_grilla_busquedaPrestador').slideUp();
            $('#panel_grilla').slideDown();
        }
    });
};


// Funcion para buscar la informacion del caso y mostrarla en pantalla para el formulario de envio GOP
function crear_gop(servicio_id) {
    
    let parametros = {
        "servicio_id": servicio_id,
        "opcion": 'crear_gop'
    };
    
    
    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'servicio_cb.php',
        data: parametros,
        success:function(data) { 
            
            $('#numero_caso_g').val(data.casoNumero);
            $('#paciente_g').val(data.nombreBeneficiario);
            $('#nVoucher_g').val(data.voucher);
            $('#fecha_nacimiento_g').val(data.nacimientoBeneficiario);
            $('#fecha_nacimiento_ansi_g').val(data.nacimientoBeneficiario_ansi);
            $('#edad_g').val(data.edad);
            $('#sintomas_g').val(data.sintomas);
            //$('#telefono_g').val(data.telefono);
            $('#telefono_g').val(data.telefonoPrincipal);
            $('#telefonosSec_g').val(data.telefonosSecundarios);
            $('#pais_g').val(data.pais);
            $('#pais_id_g').val(data.pais_id);
            $('#ciudad_g').val(data.ciudad);
            $('#ciudad_id_g').val(data.ciudad_id);
            $('#direccion_g').val(data.direccion);
            $('#cp_g').val(data.cp);
            $('#hotel_g').val(data.hotel);
            $('#habitacion_g').val(data.habitacion);
            $('#prestador_g').val(data.prestador);
            $('#prestador_id_g').val(data.prestador_id);
            $('#email_g').val(data.email);
            
            // Acomoda los paneles
            $('#panel_formulario_gop').removeClass('hidden');
            $('#panel_formulario_gop').slideDown();
            $('#panel_formulario_alta').slideUp();
            $('#panel_formulario_modificacion').slideUp();
            $('#panel_grilla_busquedaPrestador').slideUp();
            $('#panel_grilla').slideUp();
        }
    });
};


// Funcion para enviar la GOP por mail
function enviar_gop(idioma) {
    
    //deshabilita boton Guardar al hacer el submit
    $("#btn_enviar_gop_esp").attr("disabled", true);
    $("#btn_enviar_gop_eng").attr("disabled", true);
        
    // Idioma
    let idioma1                 = idioma;
    
    // Datos del sistema
    let casoNumero              = $("#numero_caso_g").val();
    let nombreBeneficiario      = $("#paciente_g").val();
    let voucher                 = $("#nVoucher_g").val();
    let nacimientoBeneficiario  = $("#fecha_nacimiento_g").val();
    let nacimientoBeneficiario_ansi  = $("#fecha_nacimiento_ansi_g").val();
    let edad                    = $("#edad_g").val();
    let sintomas                = $("#sintomas_g").val();
    let telefono                = $("#telefono_g").val();
    let telefonosSec            = $("#telefonosSec_g").val();
    let pais                    = $("#pais_g").val();
    let pais_id                 = $("#pais_id_g").val();
    let ciudad                  = $("#ciudad_g").val();
    let ciudad_id               = $("#ciudad_id_g").val();
    let direccion               = $("#direccion_g").val();
    let cp                      = $("#cp_g").val();
    let hotel                   = $("#hotel_g").val();
    let habitacion              = $("#habitacion_g").val();
    let prestador               = $("#prestador_g").val();
    let prestador_id            = $("#prestador_id_g").val();
    let email                   = $("#email_g").val();
    let observaciones           = $("#observaciones_g").val();
    
    var parametros = {
        // Idioma
        "idioma1": idioma1,
        
        // Datos del sistema
        "casoNumero": casoNumero,
        "nombreBeneficiario": nombreBeneficiario,
        "voucher": voucher,        
        "nacimientoBeneficiario": nacimientoBeneficiario,
        "nacimientoBeneficiario_ansi": nacimientoBeneficiario_ansi,
        "edad": edad,
        "sintomas": sintomas,
        "telefono": telefono,
        "telefonosSec": telefonosSec,
        "pais": pais,
        "pais_id": pais_id,
        "ciudad": ciudad,
        "ciudad_id": ciudad_id,
        "direccion": direccion,
        "cp": cp,
        "hotel": hotel,
        "habitacion": habitacion,
        "prestador": prestador,
        "prestador_id": prestador_id,
        "email": email,
        "observaciones": observaciones
    };
        
    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: '../mail/gop.php',
        data: parametros,
        beforeSend: function () {
            $.Notification.autoHideNotify('warning', 'top center', 'La GOP está siendo enviada...', 'Aguarde un instante por favor.');
        },
        success: function () {
            
            // scrollea la pagina hasta arriba la cargar la funcion
            window.scrollTo(0,0);
    
            // Resetea el formulario GOP
            $("#formulario_gop").clearValidation();
            $('#formulario_gop')[0].reset();
            
            // Mensaje de exito
            $.Notification.autoHideNotify('success', 'top center', 'GOP enviada exitosamente...','');
            
            // Arma la grilla
            grilla_listar('','');

            // Acomoda los paneles
            $('#panel_formulario_gop').slideUp();
            $('#panel_formulario_alta').slideDown();
            $('#panel_grilla').slideDown();
            //deshabilita boton Guardar al hacer el submit
            $("#btn_enviar_gop_esp").attr("disabled", false);
            $("#btn_enviar_gop_eng").attr("disabled", false);
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $.Notification.autoHideNotify('error', 'top right', 'Error...', 'Reporte del error. ' + xhr.responseText);
        }
    });
}

//Va a buscar los datos de la grilla
var grilla_listar = function() {
    
    var caso_id = $('#caso_id_n').val();
    var parametros = {
        "opcion": 'grilla_listar',
        "caso_id": caso_id
    };
    $.ajax({
        dataType: "html",
        method: "POST",
        url: "servicio_cb.php",
        data: parametros
    }).done( function(data) {
        
        $("#grilla_servicio").html(data);
        
        // Para que en la carga dinámica vuelvan a funcionar los tooltips
        $('[data-toggle="tooltip"]').tooltip();
    });
};


//Funciones para BUSCAR PRESTADOR
//Va a buscar los datos de la grilla
var grilla_listar_prestador = function(formulario_busqueda) {
    
    let prestador_nombre = $("#prestador_nombre_buscar").val();  
    let prestador_tipoPrestador_id = $("#prestador_tipoPrestador_id_b option:selected").val();
    let prestador_pais_id = $("#prestador_pais_id_b option:selected").val();
    let prestador_ciudad_id = $("#prestador_ciudad_id_b_2").val();
                 
    var parametros = {
        "prestador_nombre_buscar": prestador_nombre,
        "prestador_tipoPrestador_id_buscar": prestador_tipoPrestador_id,        
        "prestador_pais_id_buscar": prestador_pais_id,
        "prestador_ciudad_id_buscar": prestador_ciudad_id,
        "formulario_busqueda": formulario_busqueda,
        "opcion": 'grilla_listar_prestador'
    };
    $.ajax({
            dataType: "html",
            method: "POST",
            url: "servicio_cb.php",
            data: parametros,
           
            success:function(data){
                $("#grilla_prestador").html(data);
                $("#grilla_prestador_modificacion").html(data);
                
                // Para que en la carga dinámica vuelvan a funcionar los tooltips
                $('[data-toggle="tooltip"]').tooltip();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
            }
    });
};
 

//
function listarTipoPrestador_buscarPrestador(){

    var parametros = {
        "opcion": 'listarTipoPrestador_buscarPrestador'
    };

    var miselect_b = $("#prestador_tipoPrestador_id_b");

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'servicio_cb.php',
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


// Paises para el Formulario de Alta de Prestador
function listarPaises_buscarPrestador(){

    var parametros = {
        "opcion": 'listarPaises_buscarPrestador'
    };

    var miselect_b = $("#prestador_pais_id_b");

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'servicio_cb.php',
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
            url : 'servicio_cb.php',
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


//Funcion para asignar un prestador en ALTA y MODIFICACION de SERVICIO
function asignarPrestador_servicio(prestador_id, formulario_busqueda) {

    //asigna el id del prestador en alta y modificacion de servicio
    $('#servicio_prestador_id_n').val(prestador_id);
    $('#servicio_prestador_id').val(prestador_id);
        
    //lista las practicas correspondientes al prestador en alta y modificacion de servicio
    var parametros = {
        "prestador_id": prestador_id,
        "opcion": 'listarPractica_altaServicio'
    };

    var miselect = $("#servicio_practica_id_n");
    miselect.empty();
    var miselect2 = $("#servicio_practica_id");
    miselect2.empty();
    
    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'servicio_cb.php',
        data: parametros,
        success:function(data){

            miselect.append('<option value="">Seleccione</option>');
            
            for (var i=0; i<data.length; i++) {
                miselect.append('<option data-presunto="' + data[i].presuntoOrigen + '" value="' + data[i].practica_id + '">' + data[i].practica_nombre + '</option>');
                miselect2.append('<option data-presunto="' + data[i].presuntoOrigen + '" value="' + data[i].practica_id + '">' + data[i].practica_nombre + '</option>');
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });
    
    //muestra el nombre del prestador asignado en alta y modificacion de servicio
    var parametros = {
        "prestador_id": prestador_id,
        "opcion": 'prestador_altaServicio'
    };
    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'servicio_cb.php',
        data: parametros,
        success:function(data){
            $('#servicio_prestador_nombre_n').val(data.prestador_nombre);
            $('#servicio_prestador_nombre').val(data.prestador_nombre);   
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });

    // Vacia la grilla resultado de prestadores
    $("#grilla_prestador").empty();
    
    // Acomoda los paneles
    $('#panel_formulario_gop').slideUp();
    if (formulario_busqueda == 1) {
        $('#panel_formulario_alta').slideDown(); 
    } else if (formulario_busqueda == 2) {
        $('#panel_formulario_modificacion').slideDown();  
    }
    $('#panel_grilla_busquedaPrestador').slideUp();
    $('#panel_grilla').slideDown();
};


// Formulario ALTA > Al cambiar el select de moneda, llama a la funcion 'consultar_tipo_cambio'
$("#servicio_moneda_id_n").change(function() { 
    
    // Selecciona elementos del formulario ALTA
    let currency    = $("#servicio_moneda_id_n option:selected").text();
    let amount      = $("#servicio_presuntoOrigen_n").val();
    
    consultar_tipo_cambio('alta',currency,amount);
});

//obtiene el presunto origen de la practica seleccionada para cargarlo en el campo presunto 
$("#servicio_practica_id_n").change(function() { 
    $("#servicio_presuntoOrigen_n").val($(this).find(':selected').attr('data-presunto'));
});

//obtiene el presunto origen de la practica seleccionada para cargarlo en el campo presunto 
$("#servicio_practica_id").change(function() { 
    $("#servicio_presuntoOrigen").val($(this).find(':selected').attr('data-presunto'));
});

// Formulario MODIFICACION > Al cambiar el select de moneda, llama a la funcion 'consultar_tipo_cambio'
$("#servicio_moneda_id").change(function() { 
    
    // Selecciona elementos del formulario ALTA
    let currency    = $("#servicio_moneda_id option:selected").text();
    let amount      = $("#servicio_presuntoOrigen").val();
    
    consultar_tipo_cambio('modificacion',currency,amount);
});


// Formulario ALTA > Al modificarse el campo de presunto origen, llama a la funcion 'calcular_importe_usd' desde el formulario ALTA
$("#servicio_presuntoOrigen_n").add('#servicio_tipoCambio_n').keyup(function () {
    
    let importe_origen  = $('#servicio_presuntoOrigen_n').val();
    let tipo_cambio     = $('#servicio_tipoCambio_n').val();
    let resultado       = calcular_importe_usd('alta',importe_origen,tipo_cambio);
    
    $('#servicio_presuntoUSD_n').val(resultado);
});


// Formulario MODIFICACION > Al modificarse el campo de presunto origen, llama a la funcion 'calcular_importe_usd' desde el formulario MODIFICACION
$("#servicio_presuntoOrigen").add('#servicio_tipoCambio').keyup(function () {
    
    let importe_origen  = $('#servicio_presuntoOrigen').val();
    let tipo_cambio     = $('#servicio_tipoCambio').val();
    let resultado       = calcular_importe_usd('modificacion',importe_origen,tipo_cambio);
    
    $('#servicio_presuntoUSD').val(resultado);
});


// Funcion para consultar el tipo de cambio (T/C) - Consulta API
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
            
            let tipoCambio;
            let tipo_cambio;
            let importeUSD;
            
            if(form == 'alta') {
                tipoCambio = $('#servicio_tipoCambio_n');
                importeUSD = $('#servicio_presuntoUSD_n');
            } else if (form == 'modificacion') {
                tipoCambio = $('#servicio_tipoCambio');
                importeUSD = $('#servicio_presuntoUSD');
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
        error: function (){
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
function calcular_importe_usd(form,importe_origen,tipo_cambio) {
    
    let parametros = {
        "importe_origen": importe_origen,
        "tipo_cambio": tipo_cambio,
        "opcion": 'calcular_importe_usd'
    };
    
    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'servicio_cb.php',
        data: parametros,
        success:function(data) {
            // Pone los resultados en el formulario de ALTA o MODIFICACION
            // Dependiendo de lo que llegue en la variable 'form'
            if (form === 'alta') {
                $("#servicio_tipoCambio_n").val(tipo_cambio);
                $('#servicio_presuntoUSD_n').val(data);
            } else if (form === 'modificacion') {
                $("#servicio_tipoCambio").val(tipo_cambio);
                $('#servicio_presuntoUSD').val(data);
            } else {
                $.Notification.autoHideNotify('error', 'top right', 'Error al convertir la moneda');
            }
        }
    });
}


// Resetear el formulario ALTA cuando se pulsa en cancelar
$("#btn_cancelar_nuevo").click(function(){
    $("#formulario_alta").clearValidation();
    $("#servicio_prestador_nombre_n").val('');
    $("#servicio_prestador_id_n").val('');
    $("#servicio_practica_id_n").empty();
    //$('#formulario_alta')[0].reset();
    
    // Acomoda los paneles
    $('#panel_formulario_gop').slideUp();
    $('#panel_formulario_alta').slideDown();
    $('#panel_formulario_modificacion').slideUp();
    $('#panel_grilla_busquedaPrestador').slideUp();
    $('#panel_grilla').slideDown();
});


// Resetear el formulario MODIFICACION cuando se pulsa en cancelar
$("#btn_cancelar").click(function(){
    $("#formulario_modificacion").clearValidation();
    $("#servicio_prestador_nombre_n").val('');
    $("#servicio_prestador_id_n").val('');
    $("#servicio_practica_id_n").empty();
    $('#formulario_modificacion')[0].reset();
   
    // Acomoda los paneles
    $('#panel_formulario_gop').slideUp();
    $('#panel_formulario_alta').slideDown();
    $('#panel_formulario_modificacion').slideUp();
    $('#panel_grilla_busquedaPrestador').slideUp();
    $('#panel_grilla').slideDown();
});


// Resetear el formulario de GOP cuando se pulsa en cancelar
$("#btn_cancelar_gop").click(function(){
    // Resetea el formulario GOP
    $("#formulario_gop").clearValidation();
    $('#formulario_gop')[0].reset();
    
    // Acomoda los paneles
    $('#panel_formulario_gop').slideUp();
    $('#panel_formulario_alta').slideDown();
    $('#panel_formulario_modificacion').slideUp();
    $('#panel_grilla_busquedaPrestador').slideUp();
    $('#panel_grilla').slideDown();
});


// Muestra panel de ALTA de servicio
var agrega_servicio_formulario = function(){
    // Acomoda los paneles
    $('#panel_formulario_gop').slideUp();
    $('#panel_formulario_alta').slideDown();
    $('#panel_formulario_modificacion').slideUp();
    $('#panel_grilla_busquedaPrestador').slideUp();
    $('#panel_grilla').slideDown();
};


// Muestra panel de busqueda de PRESTADOR en alta servicio
var buscar_prestador_formulario = function(formulario){
    
    // Comprueba desde que formulario se esta llamando al buscador 
    // para determinar en adelante como se devolvera el resultado al formulario de servicios
    // 1 = Formulario Alta - 2 = Formulario Modificacion
    if (formulario == 1) {
        // Botones buscar
        $('#btn_listar_prestador_alta').removeClass('hidden');
        $('#btn_listar_prestador_modificacion').addClass('hidden');
        // Botones cancelar
        $('#btn_cancelar_busqueda_prestador_alta').removeClass('hidden');
        $('#btn_cancelar_busqueda_prestador_modificacion').addClass('hidden');        
        
    } else if (formulario == 2) {
        // Botones buscar
        $('#btn_listar_prestador_alta').addClass('hidden');
        $('#btn_listar_prestador_modificacion').removeClass('hidden');
        // Botones cancelar
        $('#btn_cancelar_busqueda_prestador_alta').addClass('hidden');
        $('#btn_cancelar_busqueda_prestador_modificacion').removeClass('hidden'); 
    }
    
    // Acomoda los paneles
    $('#panel_formulario_gop').slideUp();
    $('#panel_formulario_alta').slideUp();
    $('#panel_formulario_modificacion').slideUp();
    $('#panel_grilla_busquedaPrestador').slideDown();
    $('#panel_grilla_busquedaPrestador').removeClass('hidden');
    $('#panel_grilla').slideUp();
};


// Esconde el panel_grilla_busquedaPrestador cuando se cancela la busqueda de prestador
var btn_cancelar_busqueda_prestador = function (formulario) { 
    
    // Vacia la grilla resultado de prestadores
    $("#grilla_prestador").empty();
    
    // Acomoda los paneles
    $('#panel_formulario_gop').slideUp();
    // Comprueba desde que formulario se esta llamando al buscador 
    // para determinar que formulario mostrara cuando se cancele la busqueda del prestador
    // 1 = Formulario Alta - 2 = Formulario Modificacion
    if (formulario == 1) {
        $('#panel_formulario_alta').slideDown();
        $('#panel_formulario_modificacion').slideUp();
    } else if (formulario == 2) {
        $('#panel_formulario_modificacion').slideDown();
        $('#panel_formulario_alta').slideUp();
    }
    $('#panel_grilla_busquedaPrestador').slideUp();
    $('#panel_grilla').slideDown();
}


// Funcioón para mostrar el ITEM de Factura asociado al Servicio
var mostrar_fci_asignados = function (servicio_id) {

    let parametros = {
        "servicio_id": servicio_id,
        "opcion": 'mostrar_fci_asignados'
    };

    $.ajax({
        type: 'POST',
        dataType: 'HTML',
        url: 'servicio_cb.php',
        data: parametros,
        success: function(data) {
            // Muestra el modal de autorizacion de facturas
            $('#modal_ServiciosConItemsFactura').modal('show');

            // Acomoda la grilla dentro del modal
            $("#grilla_servicios_itemsFactura").html(data);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= ' + xhr.responseText + '</div>');
        }
    });
}