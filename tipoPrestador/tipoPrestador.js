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
        tipoPrestador_nombre: {
            required: true,
            minlength: 4,
            remote: {
                url: "tipoPrestador_cb.php",
                type: "post",
                data: {
                    tipoPrestador_id: function() {
                        return $( "#tipoPrestador_id" ).val();
                    },
                    opcion: 'tipoPrestador_existe_modificacion'
                }
            }            
        }
    },
    messages:{
        tipoPrestador_nombre: {
            required: "Por favor ingrese un nombre",
            minlength: "Por favor ingrese por lo menos 4 caracteres"
        }
    },
    submitHandler: function (form) {
        $.ajax({
            type: "POST",
            url: "tipoPrestador_cb.php",
            data: $(form).serialize(),
            success: function () {
                $.Notification.autoHideNotify('success', 'top right', 'Grabado exitosamente...','Los cambios han sido guardados.');
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
         
    rules:{
            tipoPrestador_nombre_n: {
                    required: true,
                    minlength: 4,
                    remote: {
                            url: "tipoPrestador_cb.php",
                            type: "post",
                            data: {
                                opcion: 'tipoPrestador_existe'
                            }
                    }        
            }              
    },
    messages: {
            tipoPrestador_nombre_n: {
                    required: "Por favor ingrese un nombre",
                    minlength: "Por lo menos 4 caracteres"
            }
    },
                    
    submitHandler: function (form) {
        //deshabilita boton Guardar al hacer el submit
        $("#btn_guardar_nuevo").attr("disabled", true);
        
        $.ajax({
            type: "POST",
            url: "tipoPrestador_cb.php",
            data: $(form).serialize(),
            success: function () {
                $.Notification.autoHideNotify('success', 'top right', 'Grabado exitosamente...','Los cambios han sido guardados.');
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
var agrega_tipoPrestador_formulario = function(){
    $('#panel_formulario_alta').removeClass('hidden');
    $('#panel_formulario_alta').slideDown();
    $('#panel_formulario_modificacion').slideUp();
    $('#panel_grilla').slideUp();
};



var modal_baja = function($tipoPrestador_id){
   $('#ventana_modal_borrado').modal('show');
   $('#id_modalBaja').val($tipoPrestador_id);
   
};
var modal_alta = function($tipoPrestador_id){
   $('#ventana_modal_habilita').modal('show');
   $('#id_modalAlta').val($tipoPrestador_id);
   
};



//Eliminación de la tipoPrestador o borrado lógico
$( "#formulario_baja" ).on( "submit", function(e) {
    
  e.preventDefault();
  
    var tipoPrestador_id_b = $('#id_modalBaja').val();
  
    var parametros = {
        "tipoPrestador_id_b": tipoPrestador_id_b,
        "opcion": 'formulario_baja'
    };
 
  $.ajax({
            type: "POST",
            url: "tipoPrestador_cb.php",
            dataType:'JSON',
            data: parametros,
            success: function (resultado) {
                if(resultado=='ok'){
                    $.Notification.autoHideNotify('success', 'top right', 'Deshabilitado exitosamente...','Se ha deshabilitado la tipoPrestador.');
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


//Para volver a habilitar al tipoPrestador
$( "#formulario_habilita" ).on( "submit", function(e) {
    
  e.preventDefault();
  
    var tipoPrestador_id_a = $('#id_modalAlta').val();
  
    var parametros = {
        "tipoPrestador_id_a": tipoPrestador_id_a,
        "opcion": 'formulario_habilita'
    };
 
  $.ajax({
            type: "POST",
            url: "tipoPrestador_cb.php",
            dataType:'JSON',
            data: parametros,
            success: function (resultado) {
                if(resultado=='ok'){
                    $.Notification.autoHideNotify('success', 'top right', 'Habilitado exitosamente...','Se ha rehabilitado el tipoPrestador.');
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
function formulario_lectura(tipoPrestador_id){
    
     var parametros = {
        "tipoPrestador_id": tipoPrestador_id,
        "opcion": 'formulario_lectura'
    };
    
     $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'tipoPrestador_cb.php',
        data: parametros,
        success:function(data){
            $('#tipoPrestador_id').val(data.tipoPrestador_id);
            $('#tipoPrestador_nombre').val(data.tipoPrestador_nombre);
                              
            $('#panel_formulario_alta').slideUp();
            $('#panel_formulario_modificacion').removeClass('hidden');
            $('#panel_formulario_modificacion').slideDown();
            $('#panel_grilla').slideUp();
            
        }
    });
};



//Va a buscar los datos de la grilla
var grilla_listar = function(tipoPrestador_nombre){
    var parametros = {
        "tipoPrestador_nombre": tipoPrestador_nombre,
        "opcion": 'grilla_listar'
    };
    $.ajax({
            dataType: "html",
            method: "POST",
            url: "tipoPrestador_cb.php",
            data: parametros
    }).done( function( data ){					
            $("#grilla_tipoPrestador").html(data);
            // Ver acá que otras acciones
            listar();
           });
};

//Hace arrancar la lista
var listar = function(){

    var table = $("#dt_tipoPrestador").DataTable({
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