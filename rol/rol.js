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
        rol_nombre: {
            required: true,
            minlength: 4,
            remote: {
                url: "rol_cb.php",
                type: "post",
                data: {
                    opcion: 'rol_existe_modificacion'
                }
            }
        },
        rol_orden: {
            required: true,
            number: true
        },
        rol_jerarquia: {
            required: true,
            number: true
        }           
    },
    
    messages: {
        rol_nombre: {
            required: "Por favor ingrese un nombre al rol",
            minlength: "Por lo menos 4 caracteres"
        },
        rol_orden: {
            required: "Ingrese con un número el orden de este rol",
            number: "Debe colocar sólo números"
        },
        rol_jerarquia: {
            required: "Por favor coloque un usuario",
            number: "Debe colocar sólo números"
        }
    },
    submitHandler: function (form) {
        $.ajax({
            type: "POST",
            url: "rol_cb.php",
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
        rol_nombre_n: {
            required: true,
            minlength: 4,
            remote: {
                url: "rol_cb.php",
                type: "post",
                data: {
                    opcion: 'rol_existe'
                }
            }       
        },
        rol_orden_n: {
            required: true,
            number: true
        },
        rol_jerarquia_n: {
            required: true,
            number: true
        }            
    },
    messages: {
        rol_nombre_n: {
            required: "Por favor ingrese un nombre al rol",
            minlength: "Por lo menos 4 caracteres"
        },
        rol_orden_n: {
            required: "Ingrese con un número el orden de este rol",
            number: "Debe colocar sólo números"
        },
        rol_jerarquia_n: {
            required: "Por favor coloque un usuario",
            number: "Debe colocar sólo números"
        }
    },                
    submitHandler: function (form) {
        $.ajax({
            type: "POST",
            url: "rol_cb.php",
            data: $(form).serialize(),
            success: function () {
                $.Notification.autoHideNotify('success', 'top right', 'Grabado exitosamente...','Los cambios han sido guardados.');
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
var agrega_rol_formulario = function(){
    $('#panel_formulario_alta').removeClass('hidden');
    $('#panel_formulario_alta').slideDown();
    $('#panel_formulario_modificacion').slideUp();
    $('#panel_grilla').slideUp();
};

var permisos = function($rol_id){
    window.location.href = "rol_permisos.php?rol_id=" + $rol_id;
};

var modal_baja = function($usuario_id){
   $('#ventana_modal_borrado').modal('show');
   $('#usuario_id_b').val($usuario_id);
   
};

var modal_alta = function($usuario_id){
   $('#ventana_modal_habilita').modal('show');
   $('#usuario_id_a').val($usuario_id);
   
};


//Eliminación del usuario o borrado lógico
$( "#formulario_baja" ).on( "submit", function(e) {
    
  e.preventDefault();
  
    var usuario_id_b = $('#usuario_id_b').val();
  
    var parametros = {
        "usuario_id_b": usuario_id_b,
        "opcion": 'formulario_baja'
    };
 
  $.ajax({
            type: "POST",
            url: "usuario_cb.php",
            dataType:'JSON',
            data: parametros,
            success: function (resultado) {
                if(resultado=='ok'){
                    $.Notification.autoHideNotify('success', 'top right', 'Eliminado exitosamente...','Se ha eliminado el usuario.');
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


//Para volver a habilitar al usuario
$( "#formulario_habilita" ).on( "submit", function(e) {
    
  e.preventDefault();
  
    var usuario_id_a = $('#usuario_id_a').val();
  
    var parametros = {
        "usuario_id_a": usuario_id_a,
        "opcion": 'formulario_habilita'
    };
 
  $.ajax({
            type: "POST",
            url: "usuario_cb.php",
            dataType:'JSON',
            data: parametros,
            success: function (resultado) {
                if(resultado=='ok'){
                    $.Notification.autoHideNotify('success', 'top right', 'Habilitado exitosamente...','Se ha rehabilitado el usuario.');
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
function formulario_lectura(rol_id){
    
     var parametros = {
        "rol_id": rol_id,
        "opcion": 'formulario_lectura'
    };
    
     $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'rol_cb.php',
        data: parametros,
        success:function(data){
            $('#rol_id').val(data.rol_id);
            $('#rol_nombre').val(data.rol_nombre);
            $('#rol_orden').val(data.rol_orden);
            $('#rol_jerarquia').val(data.rol_jerarquia);
            
       
            
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
            url: "rol_cb.php",
            data: parametros
    }).done( function( data ){					
            $("#grilla_rol").html(data);
            // Ver acá que otras acciones
            listar_roles();
           });
};

//Hace arrancar la lista
var listar_roles = function(){

    var table = $("#dt_rol").DataTable({
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