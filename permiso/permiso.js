//Verifica que se halla cargado la página para ejecutar las funciones.		
$().ready(function(){
      
     // Funciones que se van a ejecutar en la primer carga de la página.
     
     // Carga los datos en la grilla
     grilla_listar();
         
     // Acomoda los paneles para la primer vista.
     $('#panel_formulario_alta').slideUp();
     $('#panel_formulario_modificacion').slideUp();

});



// Validación de formulario de modificacion y procesamiento
$("#formulario_modificacion").validate({
         
    rules:{
        
        permiso_nombre: {
                        required: true,
                        minlength: 6,                                 
                        remote: {
                            url: "permiso_cb.php",
                            type: "post",
                            data: {
                                permiso_id: function() {
                                    return $( "#permiso_id" ).val();
                                },
                                opcion: 'nombre_existe_modificacion'
                            }
                        }                        
        },
        
        permiso_variable: {
                        required: true,
                        minlength: 6,                                 
                        remote: {
                            url: "permiso_cb.php",
                            type: "post",
                            data: {
                                permiso_id: function() {
                                    return $( "#permiso_id" ).val();
                                },
                                opcion: 'variable_existe_modificacion'
                            }
                        }          

        },
    messages:{
        permiso_nombre: "Por favor intruduzca un nombre para el permiso",
        permiso_variable: "Por favor intruduzca un valor para la variable a verificar"
        }
    },
    submitHandler: function (form) {
        $.ajax({
            type: "POST",
            url: "permiso_cb.php",
            data: $(form).serialize(),
            success: function (data) {
               $.Notification.autoHideNotify('success', 'top right', 'Grabado exitosamente...', data);
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
            permiso_nombre_n: {
                    required: true,
                    minlength: 6,
                    remote: {
                            url: "permiso_cb.php",
                            type: "post",
                            data: {
                                opcion: 'nombre_existe'
                            }
                    }        
            },
            permiso_variable_n: {
                    required: true,
                    minlength: 6,
                     remote: {
                            url: "permiso_cb.php",
                            type: "post",
                            data: {
                                opcion: 'variable_existe'
                            }
                    }        
            }    
    },
    
    messages: {

            permiso_nombre_n: {
                    required: "Por favor ingrese un nombre",
                    minlength: "Por lo menos 6 caracteres"
            },
            permiso_variable_n: {
                    required: "Por favor ingrese un nombre para la variable",
                    minlength: "Por lo menos 6 caracteres"
            }
    },
                    
    submitHandler: function(form) {
        $.ajax({
            type: "POST",
            url: "permiso_cb.php",
            data: $(form).serialize(),
            success: function (data) {
               $('#error_nuevo').html(data);
               $.Notification.autoHideNotify('success', 'top right', 'Grabado exitosamente...', data);
               grilla_listar('','');
               $('#panel_grilla').slideDown();
               $("#formulario_alta").clearValidation();
               $('#formulario_alta')[0].reset();
               $('#panel_formulario_alta').slideUp();
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
var agrega_permiso_formulario = function(){
    $('#panel_formulario_alta').removeClass('hidden');
    $('#panel_formulario_alta').slideDown();
    $('#panel_formulario_modificacion').slideUp();
    $('#panel_grilla').slideUp();
};



var modal_baja = function($usuario_id){
   $('#ventana_modal_borrado').modal('show');
   $('#usuario_id_b').val($usuario_id);
   
};
var modal_alta = function($usuario_id){
   $('#ventana_modal_habilita').modal('show');
   $('#usuario_id_a').val($usuario_id);
};


// carga los datos a editar en el formulario
function formulario_lectura(permiso_id){
    
     var parametros = {
        "permiso_id": permiso_id,
        "opcion": 'formulario_lectura'
    };
    
     $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'permiso_cb.php',
        data: parametros,
        success:function(data){
            $('#permiso_id').val(data.permiso_id);
            $('#permiso_nombre').val(data.permiso_nombre);
            $('#permiso_variable').val(data.permiso_variable);
                       
            $('#panel_formulario_alta').slideUp();
            $('#panel_formulario_modificacion').removeClass('hidden');
            $('#panel_formulario_modificacion').slideDown();
            $('#panel_grilla').slideUp();
            
        }
    });
};



//Va a buscar los datos de la grilla
var grilla_listar = function(){
    var parametros = {
        "opcion": 'grilla_listar'
    };
    $.ajax({
        dataType: "html",
        method: "POST",
        url: "permiso_cb.php",
        data: parametros
    }).done( function( data ){					
        $("#grilla_permiso").html(data);
        // Ver acá que otras acciones
        listar_permisos();
    });
};

//Hace arrancar la lista
var listar_permisos = function(){

    var table = $("#dt_permiso").DataTable({
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

