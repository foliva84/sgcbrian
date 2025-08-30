//Verifica que se halla cargado la página para ejecutar las funciones.		
$().ready(function(){
      
    // Funciones que se van a ejecutar en la primer carga de la página.

    // Agrega la informacion a los distintos Select en el formulario de ALTA de COMUNICACIONES
        // Select Asunto
        listarAsunto_altaComunicacion();
        // Select Estado del Csso
        listarCasoEstado_altaComunicacion();
        // Select Tipos de Asunto Grilla
        listarTiposAsunto_grillaComunicaciones();


    // Carga los datos en la grilla
    grilla_listar('','');

    // Carga los datos en panel_formulario_vistaDatosCaso
    formulario_vistaDatosCaso('','');

    // Acomoda los paneles para la primer vista.
    $('#panel_formulario_alta').slideDown();
    $('#panel_formulario_modificacion').slideUp();
    
    //Si asunto es confirmar servicios se coloca valor por defecto y se bloquea el campo 
    $('#comunicacion_asunto_id_n').change(function() {
        if($(this).val() == 15 ){
            $('#comunicacion_n').val('Confirmación de servicio').prop('disabled', true);
        }else{
            $('#comunicacion_n').val('').prop('disabled', false);
        }
    });
});


// Validación de formulario de modificacion y procesamiento
$("#formulario_modificacion").validate({
         
    rules:{
        comunicacion_casoEstado_id: {
            required: true    
        },
        comunicacion_asunto_id: {
            required: true    
        },
        comunicacion: {
            required: true
        }
    },
    
    messages:{
        comunicacion_casoEstado_id: {
            required: "Por favor seleccione el estado del caso"
        },
        comunicacion_asunto_id: {
            required: "Por favor seleccione el asunto de la comunicacion"
        },
        comunicacion: {
            required: "Por favor ingrese la comunicacion"
        }
    },
    
    submitHandler: function (form) {
        $.ajax({
            type: "POST",
            url: "comunicacion_cb.php",
            data: $(form).serialize(),
            success: function () {
                $.Notification.autoHideNotify('success', 'top right', 'Grabada exitosamente...','Los cambios han sido guardados.');
                
                listarCasoEstado_altaComunicacion();
                
                grilla_listar('','');
                $('#panel_grilla').slideDown();
                $('#panel_formulario_alta').slideDown();
                $('#panel_formulario_modificacion').slideUp();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $.Notification.autoHideNotify('error', 'top right', 'Error...', 'Reporte del error. ' + xhr.responseText);
            }
        });
        return false; // Previene que el formulario haga POST después de la validación, ya que ejecuto el guardado por Ajax.
    }
});


// Validación de formulario de alta y procesamiento
$("#formulario_alta").validate({

    rules:  {
        comunicacion_casoEstado_id_n: {
            required: true    
        },
        comunicacion_asunto_id_n: {
            required: true    
        },
        comunicacion_n: {
            required: true
        }
    },     
    
    messages: {
        comunicacion_casoEstado_id_n: {
            required: "Por favor seleccione el estado del caso"
        },
        comunicacion_asunto_id_n: {
            required: "Por favor seleccione el asunto de la comunicacion"
        },
        comunicacion_n: {
            required: "Por favor ingrese la comunicacion"
        }
    },
                    
    submitHandler: function (form) {

        var form_c = $(form).serializeArray();

        //No permite cerrar caso si asunto es nuevo servicio o confirmar servicio
        if(
            form_c[3]['name'] == "comunicacion_casoEstado_id_n" && 
            form_c[3]['value'] == "6" && //ESTADO CERRADO
            form_c[2]['name'] == "comunicacion_asunto_id_n" && 
            (form_c[2]['value'] == "14" || form_c[2]['value'] == "15") //ASUNTO NUEVO SERVICIO O CONFIRMAR SERVICIO
        ){
            $.Notification.autoHideNotify('error', 'top right', 'Error...', 'Asunto no valido para cerrar caso');
            return false;
        }

        //Verfica si el caso se va cerrar para validar la cantidad de servicios nuevos contra confirmados
        if(form_c[3]['name'] == "comunicacion_casoEstado_id_n" && form_c[3]['value'] == "6"){

            var parametros = {
                "caso_id_n": $('#caso_id_n').val(),
                "opcion": 'listarComunicacionesPorAsunto'
            };
    
            $.ajax({
                type: 'POST',
                dataType: 'JSON',
                url: 'comunicacion_cb.php',
                data: parametros,
                success: function(data) {
                    // if(data.nuevo > 0 && data.confirmado > 0){
                    // if(data.confirmado < data.nuevo){

                        if(data.confirmado < data.total_servicios){
                            $.Notification.autoHideNotify('error', 'top right', 'Error...', 'Aun tiene servicios sin confirmar.');
                        }else{
                            //verifica si es un nuevo servicio para recargar la solapa servicios
                            if(form_c[2]['name'] == "comunicacion_asunto_id_n" && form_c[2]['value'] == "14"){
                                var caso_id = $('#caso_id_n').val();
                                $('#pantalla_servicios_comuni').attr('data', '../servicio/servicio.php?caso_id=' + caso_id + '&modalComunication=true');
                                $('#servicesModal').modal('show');
                            } else if(form_c[2]['name'] == "comunicacion_asunto_id_n" && form_c[2]['value'] == "15"){//Verfica si es confirma servicio para mostrar modal de servicios
                                grilla_listar_servicios_sin_confirmar();
                                $('#servicesConfirmarModal').modal('show');
                            }else{

                                var insert_form = $(form).serialize();
                                if(data.total_servicios == data.servicios_costo0){//valida si todos los servicios tienen costo 0 para marcar check de caso sin costo de asistencia 
                                    insert_form = insert_form  + "&medical_cost=1";
                                }
                                
                                $.ajax({
                                    type: "POST",
                                    url: "comunicacion_cb.php",
                                    data: insert_form,
                                    success: function () {
                                        $.Notification.autoHideNotify('success', 'top right', 'Grabada exitosamente...','Los cambios han sido guardados.');
                                        grilla_listar('','');
                                        $('#panel_grilla').slideDown();
                                        $("#formulario_alta").clearValidation();
                                        $("#comunicacion_n").val('');
                                        $("#comunicacion_asunto_id_n").val('');
                                        //$('#formulario_alta')[0].reset();
                                        $('#panel_formulario_alta').slideDown();
                                    },
                                    error: function (xhr, ajaxOptions, thrownError) {
                                        $.Notification.autoHideNotify('error', 'top right', 'Error...', 'Reporte del error. ' + xhr.responseText);
                                    }
                                });
                            }
                        }
                    // }
                }
            });

        }else{
            //verifica si es un nuevo servicio para recargar la solapa servicios
            if(form_c[2]['name'] == "comunicacion_asunto_id_n" && form_c[2]['value'] == "14"){
                var caso_id = $('#caso_id_n').val();
                $('#pantalla_servicios_comuni').attr('data', '../servicio/servicio.php?caso_id=' + caso_id + '&modalComunication=true');
                $('#servicesModal').modal('show');
            } else if(form_c[2]['name'] == "comunicacion_asunto_id_n" && form_c[2]['value'] == "15"){//Verfica si es confirma servicio para mostrar modal de servicios
                grilla_listar_servicios_sin_confirmar();
                $('#servicesConfirmarModal').modal('show');
            }else{
                $.ajax({
                    type: "POST",
                    url: "comunicacion_cb.php",
                    data: $(form).serialize(),
                    success: function () {
                        $.Notification.autoHideNotify('success', 'top right', 'Grabada exitosamente...','Los cambios han sido guardados.');
                        grilla_listar('','');
                        $('#panel_grilla').slideDown();
                        $("#formulario_alta").clearValidation();
                        $("#comunicacion_n").val('');
                        $("#comunicacion_asunto_id_n").val('');
                        //$('#formulario_alta')[0].reset();
                        $('#panel_formulario_alta').slideDown();
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        $.Notification.autoHideNotify('error', 'top right', 'Error...', 'Reporte del error. ' + xhr.responseText);
                    }
                });
            }
        }
        return false; // Previene que el formulario haga POST después de la validación, ya que ejecuto el guardado por Ajax.
        
    }
});

//muestra grilla de servicios sin corfirmar
var grilla_listar_servicios_sin_confirmar = function() {
            
    var caso_id = $('#caso_id_n').val();
    var parametros = {
        "opcion": 'grilla_listar_servicios_sin_confirmar',
        "caso_id": caso_id
    };
    $.ajax({
        dataType: "html",
        method: "POST",
        url: "../servicio/servicio_cb.php",
        data: parametros
    }).done( function(data) {
        
        $("#grilla_servicios_sin_confirmar").html(data);
        
        // Para que en la carga dinámica vuelvan a funcionar los tooltips
        $('[data-toggle="tooltip"]').tooltip();
    });
};

$('#btn_service_confirmation').click(function() {
    
    var formData = $('#FormServiceConfirmation').serializeArray();
    var formDataC = $('#formulario_alta').serializeArray();
    var txtarea = 'Servicios confirmados:' + "\n";
    var info = '';
    $.each(formData, function(i, field){
        if(field.name == 'services_confirmation[]'){
            data = $('#'+field.value).val().split(",");
            info = 
                'Fecha Ingreso: ' + data[0] + "\n" + 
                'Usuario: ' + data[1] + "\n" +
                'Prestador: ' + data[2] + "\n" +
                'Practica: ' + data[3] + "\n" +
                'Presunto Origen: ' + data[4] + "\n" +
                'Moneda: ' + data[5] + "\n" +
                'T/C: ' + data[6] + "\n" +
                'Presunto USD: ' + data[7] + "\n" ;
            txtarea = (txtarea=='') ? info : txtarea + "\n ------------------------------\n"  + info;
        }
    });
    $('#comunicacion_n').val(txtarea);
    formDataC.push({name: "comunicacion_n", value:txtarea});
    $.ajax({
        dataType: "html",
        method: "POST",
        url: "../servicio/servicio_cb.php",
        data: formData,
    }).done( function(data) {
        $('#servicesConfirmarModal').modal('hide');
        $.ajax({
            type: "POST",
            url: "comunicacion_cb.php",
            data: formDataC,
            success: function () {
               $.Notification.autoHideNotify('success', 'top right', 'Grabada exitosamente...','Los cambios han sido guardados.');
               grilla_listar('','');
               $('#panel_grilla').slideDown();
               $("#formulario_alta").clearValidation();
               $("#comunicacion_n").val('');
               $("#comunicacion_asunto_id_n").val('');
               //$('#formulario_alta')[0].reset();
               $('#panel_formulario_alta').slideDown();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $.Notification.autoHideNotify('error', 'top right', 'Error...', 'Reporte del error. ' + xhr.responseText);
            }
        });
    });

  });


window.closeModal = function(){
    $('#servicesModal').modal('hide');
    $.ajax({
        type: "POST",
        url: "comunicacion_cb.php",
        data: $('#formulario_alta').serialize(),
        success: function () {
           $.Notification.autoHideNotify('success', 'top right', 'Grabada exitosamente...','Los cambios han sido guardados.');
           grilla_listar('','');
           $('#panel_grilla').slideDown();
           $("#formulario_alta").clearValidation();
           $("#comunicacion_n").val('');
           $("#comunicacion_asunto_id_n").val('');
           //$('#formulario_alta')[0].reset();
           $('#panel_formulario_alta').slideDown();
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $.Notification.autoHideNotify('error', 'top right', 'Error...', 'Reporte del error. ' + xhr.responseText);
        }
    });
};

// Funciones para completar los SELECT
// Tipos de Asuntos para el Formulario de Alta de Comunicacion
function listarAsunto_altaComunicacion(){

    var parametros = {
        "opcion": 'listarAsunto_altaComunicacion'
    };

    var miselect = $("#comunicacion_asunto_id_n");

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'comunicacion_cb.php',
        data: parametros,
        success:function(data){
            
            miselect.append('<option value="">Seleccione un Asunto</option>');
            
            for (var i=0; i<data.length; i++) {
                miselect.append('<option value="' + data[i].comunicacionAsunto_id + '">' + data[i].comunicacionAsunto_nombre + '</option>');
                
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });
};

// Estados de casos para el Formulario de Alta de Comunicacion
function listarCasoEstado_altaComunicacion(){
    
    var caso_id = $('#caso_id_n').val();
    var parametros = {
        "opcion": 'listarCasoEstado_altaComunicacion',
        "caso_id" : caso_id
    };

    var miselect = $("#comunicacion_casoEstado_id_n");
    miselect.empty();

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'comunicacion_cb.php',
        data: parametros,
        success:function(data){
            
            //miselect.append('<option value="">Seleccione Estado del Caso</option>');
            
            for (var i=0; i<data.length; i++) {
                miselect.append('<option value="' + data[i].casoEstado_id + '">' + data[i].casoEstado_nombre + '</option>');
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });
};


// Lista los asuntos de una una comunicacion en modificacion
function listarAsunto_modificarComunicacion(comunicacion_id){
   
    var parametros = {
        "opcion": 'listarAsunto_modificarComunicacion',
        "comunicacion_id" : comunicacion_id
    };
    
    var miselect = $("#comunicacion_asunto_id");
    miselect.empty();
    
    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'comunicacion_cb.php',
        data: parametros,
        success:function(data){
            
            for (var i=0; i<data.length; i++) {
                miselect.append('<option value="' + data[i].comunicacionAsunto_id + '">' + data[i].comunicacionAsunto_nombre + '</option>');
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });
    
};


// Lista los estados de un caso mientras se modifica una comunicacion
function listarCasoEstado_modificarComunicacion(comunicacion_id){
   
    var parametros = {
        "opcion": 'listarCasoEstado_modificarComunicacion',
        "comunicacion_id" : comunicacion_id
    };
    
    var miselect = $("#comunicacion_casoEstado_id");
    miselect.empty();
    
    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'comunicacion_cb.php',
        data: parametros,
        success:function(data){
            
            for (var i=0; i<data.length; i++) {
                miselect.append('<option value="' + data[i].casoEstado_id + '">' + data[i].casoEstado_nombre + '</option>');
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });
    
};


// Función para resetear validaciones que quedan arraigadas al formulario al menos que se las saque explícitamente.
$.fn.clearValidation = function(){var v = $(this).validate();$('[name]',this).each(function(){v.successList.push(this);v.showErrors();});v.resetForm();v.reset();};


// Resetear el formulario alta cuando se pulsa en cancelar
$("#btn_cancelar_nuevo").click(function(){
    $("#formulario_alta").clearValidation();
    $('#formulario_alta')[0].reset();
    $('#panel_formulario_alta').slideDown();
    $('#panel_formulario_modificacion').slideUp();
    $('#panel_grilla').slideDown();
});

// Resetear el formulario modificacion cuando se pulsa en cancelar
$("#btn_cancelar").click(function(){
    $("#formulario_modificacion").clearValidation();
    $('#formulario_modificacion')[0].reset();
    $('#panel_formulario_alta').slideDown();
    $('#panel_formulario_modificacion').slideUp();
    $('#panel_grilla').slideDown();
});

// Cerrar la grilla_listar_historial
var btn_cerrar_logComunicaciones = function(){
    $('#panel_grilla').slideDown();
    $('#panel_grilla_historial').slideUp();    
};


// Agrega los datos al formulario
var agrega_comunicacion_formulario = function(){
    $('#panel_formulario_alta').slideDown();
    $('#panel_formulario_modificacion').slideUp();
    $('#panel_grilla').slideDown();
};


var modal_baja = function($comunicacion_id){
   $('#ventana_modal_borrado').modal('show');
   $('#id_modalBaja').val($comunicacion_id);
};


var modal_alta = function($comunicacion_id){
   $('#ventana_modal_habilita').modal('show');
   $('#id_modalAlta').val($comunicacion_id);
};


// Eliminación de la comunicacion o borrado lógico
$( "#formulario_baja" ).on( "submit", function(e) {
    
  e.preventDefault();
  
    var comunicacion_id_b = $('#id_modalBaja').val();
  
    var parametros = {
        "comunicacion_id_b": comunicacion_id_b,
        "opcion": 'formulario_baja'
    };
 
  $.ajax({
            type: "POST",
            url: "comunicacion_cb.php",
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


// Para volver a habilitar al tipo de asistencia
$( "#formulario_habilita" ).on( "submit", function(e) {
    
  e.preventDefault();
  
    var comunicacion_id_a = $('#id_modalAlta').val();
  
    var parametros = {
        "comunicacion_id_a": comunicacion_id_a,
        "opcion": 'formulario_habilita'
    };
 
  $.ajax({
            type: "POST",
            url: "comunicacion_cb.php",
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


// carga los datos a editar en el formulario
function formulario_vistaDatosCaso(){
    
    var caso_id = $("#caso_id_dGeneral").val();
    
    var parametros = {
        "caso_id": caso_id,
        "opcion": 'formulario_vistaDatosCaso'
    };
    
    $.ajax({
       type: 'POST',
       dataType:'JSON',
       url: 'comunicacion_cb.php',
       data: parametros,
       success:function(data){
           
           $('#caso_numero_dGeneral').val(data.casoNumero);
           $('#caso_beneficiarioNombre_dGeneral').val(data.beneficiarioNombre);
           $('#caso_telefono_dGeneral').val(data.telefonoPrincipal);
           $('#caso_pais_nombre__dGeneral').val(data.paisSiniestro);
           $('#caso_direccion__dGeneral').val(data.direccion);
       }
   });
};


// Carga los datos a editar en el formulario
function formulario_lectura(comunicacion_id){
    
    var parametros = {
        "comunicacion_id": comunicacion_id,
        "opcion": 'formulario_lectura'
    };
    
    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'comunicacion_cb.php',
        data: parametros,
        success:function(data){
            $('#comunicacion_id').val(data.comunicacion_id);
            $('#comunicacion_asunto_id').val(data.comunicacion_asunto_id);
            //$('#comunicacion_casoEstado_id').val(data.comunicacion_casoEstado_id);
            //$('#comunicacion_casoEstado_nombre').val(data.casoEstado_nombre);
            if(data.comunicacion_asunto_id == 14 || data.comunicacion_asunto_id == 15){
                $('#comunicacion_asunto_id').prop('disabled', true);
                $('#comunicacion').val(data.comunicacion).prop('disabled', true);
            }else{
                $('#comunicacion_asunto_id').prop('disabled', false);
                $('#comunicacion').val(data.comunicacion).prop('disabled', false);
            }
            $('#comunicacion').val(data.comunicacion);
            $('#comunicacion_fechaIngreso').val(data.comunicacion_fechaIngreso);
            
            listarAsunto_modificarComunicacion(comunicacion_id);
            listarCasoEstado_modificarComunicacion(comunicacion_id);
                        
            $('#panel_formulario_alta').slideUp();
            $('#panel_formulario_modificacion').slideDown();
            $('#panel_grilla').slideDown();
        }
    });
};


// Tipos de Asuntos para filtrar grilla comunicaciones
function listarTiposAsunto_grillaComunicaciones(){

    var parametros = {
        "opcion": 'listarTiposAsunto_grillaComunicaciones'
    };

    var miselect = $("#asunto_tipo_id");

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'comunicacion_cb.php',
        data: parametros,
        success:function(data){
            
            miselect.append('<option value="0">Todas</option>');
            
            for (var i=0; i<data.length; i++) {
                miselect.append('<option value="' + data[i].asunto_tipo_id + '">' + data[i].asunto_tipo_nombre + '</option>');
                
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });
};
// filtra comunicaciones por tipo de asunto
$("#asunto_tipo_id").change(function () {    
       grilla_listar('','');
});
// Va a buscar los datos de la grilla comunicacion
var grilla_listar = function(){
    
    var caso_id = $('#caso_id_dGeneral').val();
    var asunto_tipo_id = $('#asunto_tipo_id').val();
    
    var parametros = {
        "opcion": 'grilla_listar',
        "caso_id" : caso_id,
        "asunto_tipo_id" : asunto_tipo_id
        
    };
    
    $.ajax({
        dataType: "html",
        method: "POST",
        url: "comunicacion_cb.php",
        data: parametros
    }).done( function( data ){					
        $("#grilla_comunicacion").html(data);
    });
};


// Lista la grilla con el log de ediciones de una comunicacion
function grilla_listar_historial(caso_id, comunicacion_id){
        
    var parametros = {
        "caso_id" : caso_id,
        "comunicacion_id": comunicacion_id,        
        "opcion": 'grilla_listar_historial'
    };
    
    $.ajax({
            dataType: "html",
            method: "POST",
            url: "comunicacion_cb.php",
            data: parametros,
            }).done( function( data ){
                $("#grilla_comunicacion_historial").html(data);
                
                $('#panel_grilla').slideUp();
                $('#panel_grilla_historial').slideDown();
                                
    });
};


 // Grilla para mostrar la cobertura del producto correspondiente al voucher del caso
function grilla_producto(){
    
    var caso_id = $('#caso_id_dGeneral').val();
    
    var parametros = {
        "opcion": 'grilla_producto',
        "caso_id" : caso_id
    };
    
    $.ajax({
        dataType: "html",
        method: "POST",
        url: "comunicacion_cb.php",
        data: parametros
    }).done( function( data ){					
        $("#grilla_producto_cobertura").html(data);
    });
};
