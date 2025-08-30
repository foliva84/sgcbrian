//Verifica que se halla cargado la página para ejecutar las funciones.		
$().ready(function(){
      
     // Funciones que se van a ejecutar en la primer carga de la página.
     
     // Carga los datos en la grilla
     grilla_listar('','');
     // Acomoda los paneles para la primer vista.
     $('#panel_formulario_alta').slideUp();
     $('#panel_formulario_modificacion').slideUp();

});


// Validación de formulario de modificacion y procesamiento
$("#formulario_modificacion").validate({
         
    rules:{
        tipoAsistencia_nombre: {
            required: true,
            minlength: 4,
            remote: {
                url: "tipoAsistencia_cb.php",
                type: "post",
                data: {
                    tipoAsistencia_id: function() {
                        return $( "#tipoAsistencia_id" ).val();
                    },
                    opcion: 'tipoAsistencia_existe_modificacion'
                }
            }
        }
    },

    messages:{
        tipoAsistencia_nombre: {
            required: "Por favor ingrese un tipo de asistencia",
            minlength: "Ingrese por lo menos 4 caracteres"
        }
    },
    
    submitHandler: function (form) {
        $.ajax({
            type: "POST",
            url: "tipoAsistencia_cb.php",
            data: $(form).serialize(),
            success: function () {
                $.Notification.autoHideNotify('success', 'top right', 'Grabada exitosamente...','Los cambios han sido guardados.');
               grilla_listar('','');
               $('#panel_grilla').slideDown();
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
        tipoAsistencia_clasificacion_id_n: {
            required: true    
        },
        tipoAsistencia_nombre_n: {
            required: true,
            minlength: 4,
            remote: {
                url: "tipoAsistencia_cb.php",
                type: "post",
                data: {
                    opcion: 'tipoAsistencia_existe'
                }
            }        
        }
    },     
    
    messages: {
        tipoAsistencia_clasificacion_id_n: {
            required: "Por favor seleccione la clasificación del tipo de asistencia"
        },
        tipoAsistencia_nombre_n: {
            required: "Por favor ingrese un tipo de asistencia",
            minlength: "Ingrese por lo menos 4 caracteres"
        }
    },
                    
    submitHandler: function (form) {
        //deshabilita boton Guardar al hacer el submit
        $("#btn_guardar_nuevo").attr("disabled", true);
        
        $.ajax({
            type: "POST",
            url: "tipoAsistencia_cb.php",
            data: $(form).serialize(),
            success: function () {
                $.Notification.autoHideNotify('success', 'top right', 'Grabada exitosamente...','Los cambios han sido guardados.');
               grilla_listar('','');
               $('#panel_grilla').slideDown();
               $("#formulario_alta").clearValidation();
               $('#formulario_alta')[0].reset();
               $('#panel_formulario_alta').slideUp();
               //habilita boton Guardar despues de realizado el insert
               $("#btn_guardar_nuevo").attr("disabled", false);
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
    $('#panel_formulario_alta').slideUp();
    $('#panel_formulario_modificacion').slideUp();
    $('#panel_grilla').slideDown();
});


// Resetear el formulario modificacion cuando se pulsa en cancelar
$("#btn_cancelar").click(function(){
    $("#formulario_modificacion").clearValidation();
    $('#formulario_modificacion')[0].reset();
    $('#panel_formulario_modificacion').slideUp();
    $('#panel_grilla').slideDown();
});


// Agrega los datos al formulario
var agrega_tipoAsistencia_formulario = function(){
    $('#panel_formulario_alta').removeClass('hidden');
    $('#panel_formulario_alta').slideDown();
    $('#panel_formulario_modificacion').slideUp();
    $('#panel_grilla').slideUp();
};


var modal_baja = function($tipoAsistencia_id){
   $('#ventana_modal_borrado').modal('show');
   $('#id_modalBaja').val($tipoAsistencia_id);
   
};


var modal_alta = function($tipoAsistencia_id){
   $('#ventana_modal_habilita').modal('show');
   $('#id_modalAlta').val($tipoAsistencia_id);
   
};


//Eliminación del tipo de asistencia o borrado lógico
$( "#formulario_baja" ).on( "submit", function(e) {
    
  e.preventDefault();
  
    var tipoAsistencia_id_b = $('#id_modalBaja').val();
  
    var parametros = {
        "tipoAsistencia_id_b": tipoAsistencia_id_b,
        "opcion": 'formulario_baja'
    };
 
  $.ajax({
            type: "POST",
            url: "tipoAsistencia_cb.php",
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


//Para volver a habilitar al tipo de asistencia
$( "#formulario_habilita" ).on( "submit", function(e) {
    
  e.preventDefault();
  
    var tipoAsistencia_id_a = $('#id_modalAlta').val();
  
    var parametros = {
        "tipoAsistencia_id_a": tipoAsistencia_id_a,
        "opcion": 'formulario_habilita'
    };
 
  $.ajax({
            type: "POST",
            url: "tipoAsistencia_cb.php",
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
function formulario_lectura(tipoAsistencia_id){
    
     var parametros = {
        "tipoAsistencia_id": tipoAsistencia_id,
        "opcion": 'formulario_lectura'
    };
    
     $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'tipoAsistencia_cb.php',
        data: parametros,
        success:function(data){
            $('#tipoAsistencia_id').val(data.tipoAsistencia_id);
            $('#tipoAsistencia_clasificacion_id').val(data.tipoAsistencia_clasificacion_id);
            $('#tipoAsistencia_nombre').val(data.tipoAsistencia_nombre);
            
            $('#panel_formulario_alta').slideUp();
            $('#panel_formulario_modificacion').removeClass('hidden');
            $('#panel_formulario_modificacion').slideDown();
            $('#panel_grilla').slideUp();
        }
    });
};


//Va a buscar los datos de la grilla
var grilla_listar = function(tipoAsistencia_nombre){
    var parametros = {
        "tipoAsistencia_nombre": tipoAsistencia_nombre,
        "opcion": 'grilla_listar'
    };
    $.ajax({
            dataType: "html",
            method: "POST",
            url: "tipoAsistencia_cb.php",
            data: parametros
    }).done( function( data ){					
            $("#grilla_tipoAsistencia").html(data);
            // Ver acá que otras acciones
            listar_tiposAsistencia();
           });
};


//Hace arrancar la lista
var listar_tiposAsistencia = function(){

    console.log('entro en listar');
    var table = $("#dt_tipoAsistencia").DataTable({
        "destroy":true,
        "stateSave": true,
        "language": idioma_espanol
    });
};


//Idioma de la grilla
var idioma_espanol = {
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
        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
    }
};