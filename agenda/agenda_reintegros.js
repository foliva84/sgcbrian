// Verifica que se halla cargado la página para ejecutar las funciones.		
$().ready(function(){
    
    // Carga los datos en la grilla
    grilla_listar('','');
    
    $('#panel_formulario_asignacion').slideUp();
    $('#panel_formulario_reasignacion').slideUp();
    
    // Agrega datos a los distintos Select
        // Select Estados de Casos
        listar_reintegroEstados();
        // Select Usuario
        listar_usuarios();
});


// Estado del Reintegro para el buscador
function listar_reintegroEstados(){

    var parametros = {
        "opcion": 'listar_reintegroEstados'
    };

    var miselect = $("#reintegro_estado_id_b");

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'agenda_reintegros_cb.php',
        data: parametros,
        success:function(data){
            
            miselect.append('<option value="">Seleccione un Estado</option>');
            
            for (var i=0; i<data.length; i++) {
                miselect.append('<option value="' + data[i].reintegroEstado_id + '">' + data[i].reintegroEstado_nombre + '</option>');
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });
};


// Lista de usuarios para la asignacion
function listar_usuarios(){

    var parametros = {
        "opcion": 'listar_usuarios'
    };

    var miselect = $("#agenda_usuarioAsignado_a");
    var miselect2 = $("#caso_usuarioAsignado_id_b");

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'agenda_reintegros_cb.php',
        data: parametros,
        success:function(data){
            
            miselect.append('<option value="">Seleccione un usuario</option>');
            miselect2.append('<option value="">Seleccione un usuario asignado</option>');
            
            for (var i=0; i<data.length; i++) {
                miselect.append('<option value="' + data[i].usuario_id + '">' + data[i].usuario_nombre + ' ' + data[i].usuario_apellido + '</option>');
                miselect2.append('<option value="' + data[i].usuario_id + '">' + data[i].usuario_nombre + ' ' + data[i].usuario_apellido + '</option>');
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });
};


// Lista los usuarios para el formulario de re-asignacion
function listarUsuarios_reAsignacion(reintegroAgenda_id){

    var parametros = {
        "opcion": 'listarUsuarios_reAsignacion',
        "reintegroAgenda_id": reintegroAgenda_id
    };

    var miselect = $("#agenda_usuarioAsignado_r");
    miselect.empty();

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'agenda_reintegros_cb.php',
        data: parametros,
        success:function(data){
            for (var i=0; i<data.length; i++) {
                miselect.append('<option value="' + data[i].usuario_id + '">' + data[i].usuario_nombre + ' ' + data[i].usuario_apellido + '</option>');
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });
};


// Muestra el panel para el formulario de ASIGNACION de usuario a reintegro
function formulario_asigna(reintegro_id,caso_id,caso_numero) {
    
    $('#agenda_reintegro_id').val(reintegro_id);
    $('#agenda_caso_id').val(caso_id);
    $('#agenda_caso_numero').val(caso_numero); 
    
    $('#panel_formulario_reasignacion').slideUp();
    $('#panel_grilla').slideUp();         
    $('#panel_formulario_asignacion').removeClass('hidden');
    $('#panel_formulario_asignacion').slideDown();
};


// Funcion para ASIGNAR el caso al usuario elegido
// Validaciones y Form
$("#formulario_asignacion").validate({  
    ignore: [],
    rules: {
        agenda_usuarioAsignado_a: {
            required: true
        }
    },   
    messages: {
        agenda_usuarioAsignado_a: {
            required: "Por favor seleccione un usuario"
        }
    },
    submitHandler: function (form) {
        //deshabilita boton de asignar reintegro al hacer el submit
        $("#btn_asignar_reintegro").attr("disabled", true);
        
        $.ajax({
            type: "POST",
            url: "agenda_reintegros_cb.php",
            data: $(form).serialize(),
            success: function () {
                $.Notification.autoHideNotify('success', 'top right', 'El Reintegro se asignó exitosamente...','Los cambios han sido guardados.');
                grilla_listar('','');
                $('#panel_grilla').slideDown();
                $("#formulario_asignacion").clearValidation();
                $('#formulario_asignacion')[0].reset();
                $('#panel_formulario_asignacion').slideUp();
                
                // Habilita boton asignar reintegro despues de realizada la asignacion
                $("#btn_asignar_reintegro").attr("disabled", false);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $.Notification.autoHideNotify('error', 'top right', 'Error...', 'Reporte del error. ' + xhr.responseText);
            }
        });
        
        // Previene que el formulario haga POST después de la validación, ya que ejecuto el guardado por Ajax.
        return false;
    }
});


// Muestra el panel para el formulario de REASIGNACION de usuario a caso
function formulario_modificar(reintegroAgenda_id){
    
    var parametros = {
        "reintegroAgenda_id": reintegroAgenda_id,
        "opcion": 'formulario_modificar'
    };
    
    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'agenda_reintegros_cb.php',
        data: parametros,
        success:function(data){
            $('#agenda_id_r').val(data.reintegroAgenda_id);
            $('#agenda_reintegro_id_r').val(data.reintegroAgenda_reintegro_id);
            $('#agenda_caso_numero_r').val(data.caso_numero);
            
            listarUsuarios_reAsignacion(reintegroAgenda_id);
            
            $('#panel_formulario_asignacion').slideUp();
            $('#panel_grilla').slideUp();         
            $('#panel_formulario_reasignacion').removeClass('hidden');
            $('#panel_formulario_reasignacion').slideDown();
        }
    });
};


// Funcion para RE-ASIGNAR el caso al usuario elegido
// Validaciones y Form
$("#formulario_reasignacion").validate({  
    
    ignore: [],
    rules: {
    },   
    messages: {
    },
    
    submitHandler: function (form) {
        //deshabilita boton de reAsignar caso al hacer el submit
        $("#btn_reAsignar_reintegro").attr("disabled", true);
        
        $.ajax({
            type: "POST",
            url: "agenda_reintegros_cb.php",
            data: $(form).serialize(),
            success: function () {
                $.Notification.autoHideNotify('success', 'top right', 'El Caso se re-asigno exitosamente...','Los cambios han sido guardados.');
                grilla_listar('','');
                $('#panel_grilla').slideDown();
                $("#formulario_reasignacion").clearValidation();
                $('#formulario_reasignacion')[0].reset();
                $('#panel_formulario_reasignacion').slideUp();
                //habilita boton reAsignar caso despues de realizada la reAsignacion
                $("#btn_reAsignar_reintegro").attr("disabled", false);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $.Notification.autoHideNotify('error', 'top right', 'Error...', 'Reporte del error. ' + xhr.responseText);
            }
        });
        
        // Previene que el formulario haga POST después de la validación, ya que ejecuto el guardado por Ajax.
        return false;
    }
});


//Habilita la tecla ENTER para el buscador
$(document).keypress(function (e) {
    if(e.keyCode === 13){
        // Carga los datos en la grilla
        grilla_listar('','');
    }
});


//Va a buscar los datos de la grilla
var grilla_listar = function() {
    
    let caso_numero_desde = $("#caso_numero_desde_b").val();
    let caso_numero_hasta = $("#caso_numero_hasta_b").val();    
    let reintegro_estado_id = $("#reintegro_estado_id_b option:selected").val();
    let caso_usuarioAsignado_id = $("#caso_usuarioAsignado_id_b option:selected").val();

    var parametros = {
        "caso_numero_desde_b": caso_numero_desde,
        "caso_numero_hasta_b": caso_numero_hasta,
        "reintegro_estado_id_b": reintegro_estado_id,
        "caso_usuarioAsignado_id_b": caso_usuarioAsignado_id,
        "opcion": 'grilla_listar_contar'
    };
    
    $.ajax({
        dataType: "html",
        method: "POST",
        url: "agenda_reintegros_cb.php",
        data: parametros,

        success:function(data){
            $("#grilla_info").html(data);
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });

    var parametros = {
        "caso_numero_desde_b": caso_numero_desde,
        "caso_numero_hasta_b": caso_numero_hasta,
        "reintegro_estado_id_b": reintegro_estado_id,
        "caso_usuarioAsignado_id_b": caso_usuarioAsignado_id,
        "opcion": 'grilla_listar'
    };
    $.ajax({
        dataType: "html",
        method: "POST",
        url: "agenda_reintegros_cb.php",
        data: parametros,
        success:function(data){
            $("#grilla_agenda").html(data);
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });
};


// Resetear el formulario ASIGNACION cuando se pulsa en cancelar
$("#btn_cancelar_asignacion").click(function(){
    $("#formulario_asignacion").clearValidation();
    $('#formulario_asignacion')[0].reset();
    $('#panel_formulario_asignacion').slideUp();
    $('#panel_grilla').slideDown();
});


// Resetear el formulario RE-ASIGNACION cuando se pulsa en cancelar
$("#btn_cancelar_reasignacion").click(function(){
    $("#formulario_reasignacion").clearValidation();
    $('#formulario_reasignacion')[0].reset();
    $('#panel_formulario_reasignacion').slideUp();
    $('#panel_grilla').slideDown();
});


// Función para resetear validaciones que quedan arraigadas al formulario al menos que se las saque explícitamente.
$.fn.clearValidation = function(){var v = $(this).validate();$('[name]',this).each(function(){v.successList.push(this);v.showErrors();});v.resetForm();v.reset();};