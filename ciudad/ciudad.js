//Verifica que se halla cargado la página para ejecutar las funciones.		
$().ready(function(){
      
     // Funciones que se van a ejecutar en la primer carga de la página.
     // Select Paises
        formulario_alta_paises();
        
    
     // Acomoda los paneles para la primer vista.
     $('#panel_formulario_alta').slideUp();
     $('#panel_formulario_modificacion').slideUp();

});

// Validación de formulario de modificacion y procesamiento
$("#formulario_modificacion").validate({
         
    rules:{
        ciudad_pais_id: {
            required: true
        },
        ciudad_nombre: {
            required: true,
            minlength: 4,
            remote: {
                url: "ciudad_cb.php",
                type: "post",
                data: {
                    opcion: 'ciudad_existe_modificacion',
                    ciudad_id: function() {
                        return $( "#ciudad_id" ).val();
                    },
                    ciudad_pais_id: function() {
                        return $("#ciudad_pais_id").val();
                    }
                }
            }            
        }
    },
    messages:{
        ciudad_pais_id: {
            required: "Por favor seleccione un pais"
         },
        ciudad_nombre: {
            required: "Por favor ingrese un nombre",
            minlength: "Por favor ingrese por lo menos 4 caracteres"
        }
    },
    submitHandler: function (form) {
        $.ajax({
            type: "POST",
            url: "ciudad_cb.php",
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
        ciudad_pais_id_n: {
            required: true
        },
        ciudad_nombre_n: {
            required: true,
            minlength: 4,
            remote: {
                url: "ciudad_cb.php",
                type: "post",
                data: {
                    opcion: 'ciudad_existe',
                    ciudad_pais_id_n: function() {
                        return $("#ciudad_pais_id_n").val();
                    }
                }
            }      
        }            
    },
    messages: {
        ciudad_pais_id_n: {
            required: "Por favor seleccione un pais"
        },
        ciudad_nombre_n: {
            required: "Por favor ingrese un nombre",
            minlength: "Por lo menos 4 caracteres"
        }
    },
                    
    submitHandler: function (form) {
        //deshabilita boton Guardar al hacer el submit
        $("#btn_guardar_nuevo").attr("disabled", true);
        
        $.ajax({
            type: "POST",
            url: "ciudad_cb.php",
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


// Funciones para completar los SELECT
// 
// Paises para el Formulario de Alta de Ciudades
function formulario_alta_paises(){

    var parametros = {
        "opcion": 'formulario_alta_paises'
    };

    var miselect = $("#ciudad_pais_id_n");
    var miselect2 = $("#ciudad_pais_id_b");

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'ciudad_cb.php',
        data: parametros,
        success:function(data){
                
            miselect.append('<option value="">Seleccione un Pais</option>');  
            miselect2.append('<option value="">Seleccione un Pais</option>');
            
            for (var i=0; i<data.length; i++) {
                miselect.append('<option value="' + data[i].pais_id + '">' + data[i].pais_nombreEspanol + '</option>');
                miselect2.append('<option value="' + data[i].pais_id + '">' + data[i].pais_nombreEspanol + '</option>');
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });
};
// Paises para el Formulario de Modificacion de Ciudades
function formulario_modificacion_paises(ciudad_id){
   
    var parametros = {
        "opcion": 'formulario_modificacion_paises',
        "ciudad_id" : ciudad_id
    };
    
    var miselect = $("#ciudad_pais_id");
    miselect.empty();
    
    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'ciudad_cb.php',
        data: parametros,
        success:function(data){
            for (var i=0; i<data.length; i++) {
                miselect.append('<option value="' + data[i].pais_id + '">' + data[i].pais_nombreEspanol + '</option>');
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
var agrega_ciudad_formulario = function(){
    $('#panel_formulario_alta').removeClass('hidden');
    $('#panel_formulario_alta').slideDown();
    $('#panel_formulario_modificacion').slideUp();
    $('#panel_grilla').slideUp();
};


var modal_baja = function($ciudad_id){
   $('#ventana_modal_borrado').modal('show');
   $('#id_modalBaja').val($ciudad_id);
   
};

var modal_alta = function($ciudad_id){
   $('#ventana_modal_habilita').modal('show');
   $('#id_modalAlta').val($ciudad_id);
   
};


//Eliminación de la ciudad o borrado lógico
$( "#formulario_baja" ).on( "submit", function(e) {
    
  e.preventDefault();
  
    var ciudad_id_b = $('#id_modalBaja').val();
  
    var parametros = {
        "ciudad_id_b": ciudad_id_b,
        "opcion": 'formulario_baja'
    };
 
  $.ajax({
            type: "POST",
            url: "ciudad_cb.php",
            dataType:'JSON',
            data: parametros,
            success: function (resultado) {
                if(resultado=='ok'){
                    $.Notification.autoHideNotify('success', 'top right', 'Deshabilitado exitosamente...','Se ha deshabilitado el ciudad.');
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


//Para volver a habilitar al ciudad
$( "#formulario_habilita" ).on( "submit", function(e) {
    
  e.preventDefault();
  
    var ciudad_id_a = $('#id_modalAlta').val();
  
    var parametros = {
        "ciudad_id_a": ciudad_id_a,
        "opcion": 'formulario_habilita'
    };
 
  $.ajax({
            type: "POST",
            url: "ciudad_cb.php",
            dataType:'JSON',
            data: parametros,
            success: function (resultado) {
                if(resultado=='ok'){
                    $.Notification.autoHideNotify('success', 'top right', 'Habilitado exitosamente...','Se ha rehabilitado el ciudad.');
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
function formulario_lectura(ciudad_id){
    
     var parametros = {
        "ciudad_id": ciudad_id,
        "opcion": 'formulario_lectura'
    };
    
    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'ciudad_cb.php',
        data: parametros,
        success:function(data){
            $('#ciudad_id').val(data.ciudad_id);
            $('#ciudad_pais_id').val(data.ciudad_pais_id);
            $('#ciudad_nombre').val(data.ciudad_nombre);
            
            formulario_modificacion_paises(ciudad_id);
                              
            $('#panel_formulario_alta').slideUp();
            $('#panel_formulario_modificacion').removeClass('hidden');
            $('#panel_formulario_modificacion').slideDown();
            $('#panel_grilla').slideUp();
        }
    });
};



//Va a buscar los datos de la grilla
var grilla_listar = function(){
    
    let ciudad_nombre_buscar = $("#ciudad_nombre_buscar").val();  
    let ciudad_pais_buscar = $("#ciudad_pais_id_b option:selected").val();
        
    
    var parametros = {
        "ciudad_nombre_buscar": ciudad_nombre_buscar,
        "ciudad_pais_buscar": ciudad_pais_buscar,
        "opcion": 'grilla_listar_contar'
    };
    $.ajax({
            dataType: "html",
            method: "POST",
            url: "ciudad_cb.php",
            data: parametros,
           
            success:function(data){
                $("#grilla_info").html(data);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
            }
    });
    
    
    var parametros = {
        "ciudad_nombre_buscar": ciudad_nombre_buscar,
        "ciudad_pais_buscar": ciudad_pais_buscar,
        "opcion": 'grilla_listar'
    };
    $.ajax({
        dataType: "html",
        method: "POST",
        url: "ciudad_cb.php",
        data: parametros,
        success:function(data){
            $("#grilla_ciudad").html(data);
            listar_ciudades();
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    }); 
};


//Hace arrancar el datatable
var listar_ciudades = function(){

    var table = $("#dt_ciudad").DataTable({
        "destroy":true,
        "stateSave": true,
        "bFilter": false,        
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