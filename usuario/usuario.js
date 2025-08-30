//Verifica que se halla cargado la página para ejecutar las funciones.		
$().ready(function(){
      
     // Funciones que se van a ejecutar en la primer carga de la página.
     
     // Carga los datos en la grilla
     grilla_listar('','');
     // Agrega los Roles del usuario en el formulario de alta
     formulario_alta_roles();
     // Acomoda los paneles para la primer vista.

   
});

// Validación de formulario de modificacion y procesamiento
$("#formulario_modificacion").validate({
         
    rules:{
        usuario_nombre: {
            required: true,
            minlength: 4
        },
        usuario_apellido: {
            required: true,
            minlength: 4
        },
        usuario_usuario: {
            required: true,
            minlength: 6,                                 
            remote: {
                url: "usuario_cb.php",
                type: "post",
                data: {
                    usuario_id: function() {
                        return $( "#usuario_id" ).val();
                    },
                    opcion: 'usuario_existe_modificacion'
                }
            }                        
        },
        usuario_rol_id: {
            required: true
        }      
    },
    messages:{
        usuario_nombre: {
            required: "Por favor ingrese un nombre",
            minlength: "Por lo menos 4 caracteres"
        },
        usuario_apellido: {
            required: "Por favor ingrese un apellido",
            minlength: "Por lo menos 4 caracteres"
        },
        usuario_usuario: {
            required: "Por favor coloque un usuario",
            minlength: "Su usuario debe contener 6 caracteres minimo"
        },
        usuario_rol_id: {
            required: "Por favor seleccione un rol"
        }        
    },
    submitHandler: function (form) {
        $.ajax({
            type: "POST",
            url: "usuario_cb.php",
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
            usuario_nombre_n: {
                required: true,
                minlength: 4
            },
            usuario_apellido_n: {
                required: true,
                minlength: 4
            },
            usuario_usuario_n: {
                required: true,
                minlength: 6,
                remote: {
                    url: "usuario_cb.php",
                    type: "post",
                    data: {
                        opcion: 'usuario_existe'
                    }
                }        
            },
            usuario_rol_id_n: {
                required: true
            },
            usuario_password_n: {
                required: true,
                minlength: 6
            },
            usuario_password_n_c: {
                required: true,
                minlength: 6,
                equalTo: "#usuario_password_n"
            }
                
    },
    
    messages: {
            usuario_nombre_n: {
                required: "Por favor ingrese un nombre",
                minlength: "Por lo menos 4 caracteres"
            },
            usuario_apellido_n: {
                required: "Por favor ingrese un apellido",
                minlength: "Por lo menos 4 caracteres"
            },
            usuario_usuario_n: {
                required: "Por favor coloque un usuario",
                minlength: "Su usuario debe contener 6 caracteres minimo"
            },
            usuario_rol_id_n: {
                required: "Por favor seleccione un rol"
            },
            usuario_password_n: {
                required: "Por favor coloque un password",
                minlength: "Su password debe contener 6 caracteres minimo"
            },
            usuario_password_n_c: {
                required: "Por favor coloque nuevamente el password",
                minlength: "Su password debe contener 6 caracteres minimo",
                equalTo: "Su password debe coincidir"
            }
    },
                    
    submitHandler: function (form) {
        $.ajax({
            type: "POST",
            url: "usuario_cb.php",
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
var agrega_usuario_formulario = function(){
  
    $('#panel_formulario_alta').removeClass('hidden');
    $('#panel_formulario_alta').slideDown();
    $('#panel_formulario_modificacion').slideUp();
    $('#panel_grilla').slideUp();
};


var modal_baja = function($usuario_id){
   $('#ventana_modal_borrado').modal('show');
   $('#id_modalBaja').val($usuario_id);
   
};
var modal_alta = function($usuario_id){
   $('#ventana_modal_habilita').modal('show');
   $('#id_modalAlta').val($usuario_id);

};


//Eliminación del usuario o borrado lógico
$( "#formulario_baja" ).on( "submit", function(e) {
    
  e.preventDefault();
  
    var usuario_id_b = $('#id_modalBaja').val();
  
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
                    $.Notification.autoHideNotify('success', 'top right', 'Deshabilitado exitosamente...','Se ha deshabilitado el usuario.');
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
  
    var usuario_id_a = $('#id_modalAlta').val();
  
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





// Roles para el Formulario de Alta
function formulario_alta_roles(){
   
    var parametros = {
        "opcion": 'formulario_alta_roles'
    };
    
    var miselect = $("#usuario_rol_id_n");
     
    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'usuario_cb.php',
        data: parametros,
        success:function(data){
            
            miselect.append('<option value="">Seleccione un Rol</option>');

            for (var i=0; i<data.length; i++) {
                miselect.append('<option value="' + data[i].rol_id + '">' + data[i].rol_nombre + '</option>');
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });
    
};


// Roles para el Formulario de Modificación
function formulario_modificacion_roles(usuario_id){
   
    var parametros = {
        "opcion": 'formulario_modificacion_roles',
        "usuario_id" : usuario_id
    };
    
    var miselect = $("#usuario_rol_id");
    miselect.empty();
    
    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'usuario_cb.php',
        data: parametros,
        success:function(data){
            for (var i=0; i<data.length; i++) {
                miselect.append('<option value="' + data[i].rol_id + '">' + data[i].rol_nombre + '</option>');
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });
    
};



// carga los datos a editar en el formulario
function formulario_lectura(usuario_id){
    
     var parametros = {
        "usuario_id": usuario_id,
        "opcion": 'formulario_lectura'
    };
    
     $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'usuario_cb.php',
        data: parametros,
        success:function(data){
            $('#usuario_id').val(data.usuario_id);
            $('#usuario_nombre').val(data.usuario_nombre);
            $('#usuario_apellido').val(data.usuario_apellido);
            $('#usuario_usuario').val(data.usuario_usuario);
            
            formulario_modificacion_roles(usuario_id);
            
            $('#panel_formulario_alta').slideUp();
            $('#panel_formulario_modificacion').removeClass('hidden');
            $('#panel_formulario_modificacion').slideDown();
            $('#panel_grilla').slideUp();
            
        }
    });
};



//Va a buscar los datos de la grilla
var grilla_listar = function(usuario_nombre, usuario_apellido){
    var parametros = {
        "usuario_nombre": usuario_nombre,
        "usuario_apellido": usuario_apellido,
        "opcion": 'grilla_listar'
    };
    $.ajax({
            dataType: "html",
            method: "POST",
            url: "usuario_cb.php",
            data: parametros,
            beforeSend: function () {
                    $("#grilla_usuario").html('<div style="text-align:center; font-size:16px; margin-top:50px;"><img src="../assets/images/ajax_loader.gif"/></div>');
            },
            success:  function (data) {
                    $("#grilla_usuario").html(data);  
                    listar_usuarios();
            },
            error: function () {
                    $("#grilla_usuario").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error interno en el servidor.</div>');
            }
    });
};

//Hace arrancar la lista
var listar_usuarios = function(){

    var table = $("#dt_usuario").DataTable({
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