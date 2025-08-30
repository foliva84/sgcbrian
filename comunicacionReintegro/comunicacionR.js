//Verifica que se halla cargado la página para ejecutar las funciones.		
$().ready(function(){
      
    // Funciones que se van a ejecutar en la primer carga de la página.
    // Carga los datos en la grilla
    grilla_listar('','');

    // Acomoda los paneles para la primer vista.
    $('#panel_formulario_alta').slideDown();
    $('#panel_formulario_modificacion').slideUp();

});


// Validación de formulario de modificacion y procesamiento
$("#formulario_modificacion").validate({
         
    rules:{
        comunicacionR: {
            required: true
        }
    },
    messages:{
        comunicacionR: {
            required: "Por favor ingrese la comunicacionR"
        }
    },    
    submitHandler: function (form) {
        $.ajax({
            type: "POST",
            url: "comunicacionR_cb.php",
            data: $(form).serialize(),
            success: function () {
                $.Notification.autoHideNotify('success', 'top right', 'Grabada exitosamente...','Los cambios han sido guardados.');
                
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
        comunicacionR_n: {
            required: true
        }
    },  
    messages: {
        comunicacionR_n: {
            required: "Por favor ingrese la comunicacionR"
        }
    },
    submitHandler: function (form) {
        $.ajax({
            type: "POST",
            url: "comunicacionR_cb.php",
            data: $(form).serialize(),
            success: function () {
               $.Notification.autoHideNotify('success', 'top right', 'Grabada exitosamente...','Los cambios han sido guardados.');
               
               grilla_listar('','');
               $('#panel_grilla').slideDown();
               $("#formulario_alta").clearValidation();
               //$("#comunicacionR_n").val('');
               $('#formulario_alta')[0].reset();
               $('#panel_formulario_alta').slideDown();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $.Notification.autoHideNotify('error', 'top right', 'Error...', 'Reporte del error. ' + xhr.responseText);
            }
        });
        return false; // Previene que el formulario haga POST después de la validación, ya que ejecuto el guardado por Ajax.
    }
});


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
var btn_cerrar_logComunicacionesR = function(){
    $('#panel_grilla').slideDown();
    $('#panel_grilla_historial').slideUp();    
};


// Agrega los datos al formulario
var agrega_comunicacionR_formulario = function(){
    $('#panel_formulario_alta').slideDown();
    $('#panel_formulario_modificacion').slideUp();
    $('#panel_grilla').slideDown();
};


var modal_baja = function($comunicacionR_id){
   $('#ventana_modal_borrado').modal('show');
   $('#id_modalBaja').val($comunicacionR_id);
};


var modal_alta = function($comunicacionR_id){
   $('#ventana_modal_habilita').modal('show');
   $('#id_modalAlta').val($comunicacionR_id);
};


// Eliminación de la comunicacionR o borrado lógico
$( "#formulario_baja" ).on( "submit", function(e) {
    
  e.preventDefault();
  
    var comunicacionR_id_b = $('#id_modalBaja').val();
  
    var parametros = {
        "comunicacionR_id_b": comunicacionR_id_b,
        "opcion": 'formulario_baja'
    };
 
  $.ajax({
            type: "POST",
            url: "comunicacionR_cb.php",
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
  
    var comunicacionR_id_a = $('#id_modalAlta').val();
  
    var parametros = {
        "comunicacionR_id_a": comunicacionR_id_a,
        "opcion": 'formulario_habilita'
    };
 
  $.ajax({
            type: "POST",
            url: "comunicacionR_cb.php",
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
function formulario_lectura(comunicacionR_id){
    
    var parametros = {
        "comunicacionR_id": comunicacionR_id,
        "opcion": 'formulario_lectura'
    };
    
    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'comunicacionR_cb.php',
        data: parametros,
        success:function(data){
            $('#comunicacionR_id').val(data.comunicacionR_id);
            $('#comunicacionR').val(data.comunicacionR);
            $('#comunicacionR_fechaIngreso').val(data.comunicacionR_fechaIngreso);
                        
            $('#panel_formulario_alta').slideUp();
            $('#panel_formulario_modificacion').slideDown();
            $('#panel_grilla').slideDown();
        }
    });
};


// Va a buscar los datos de la grilla comunicacionR
var grilla_listar = function(){
    
    var reintegro_id = $('#reintegro_id_grilla').val();
    
    var parametros = {
        "opcion": 'grilla_listar',
        "reintegro_id" : reintegro_id
    };
    
    $.ajax({
        dataType: "html",
        method: "POST",
        url: "comunicacionR_cb.php",
        data: parametros
    }).done( function( data ){					
        $("#grilla_comunicacionR").html(data);
    });
};


// Lista la grilla con el log de ediciones de una comunicacionR
function grilla_listar_historial(reintegro_id, comunicacionR_id){
        
    var parametros = {
        "reintegro_id" : reintegro_id,
        "comunicacionR_id": comunicacionR_id,        
        "opcion": 'grilla_listar_historial'
    };
    
    $.ajax({
            dataType: "html",
            method: "POST",
            url: "comunicacionR_cb.php",
            data: parametros,
            }).done( function( data ){
                $("#grilla_comunicacionR_historial").html(data);
                
                $('#panel_grilla').slideUp();
                $('#panel_grilla_historial').slideDown();
                                
    });
};

