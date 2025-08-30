//Verifica que se halla cargado la página para ejecutar las funciones.		
$().ready(function() {

    // Funciones que se van a ejecutar en la primer carga de la página.
    // Agrega la informacion a los distintos Select en el formulario de ALTA de PRESTADORES
    // Select Tipo de cliente
    formulario_alta_tipoCliente();
    // Select Paises
    formulario_alta_paises();
    // Select de tipos de telefono
    listarTipoTelefonos_cliente();
    // Select de tipos de email
    listarTipoEmail_cliente();

    // Acomoda los paneles para la primer vista.
    $('#panel_formulario_alta').slideUp();
    $('#panel_formulario_modificacion').slideUp();
    $('#panel_formulario_ver').slideUp();

    // Funcion para formatear y borrar caracteres invalidos en los campos de telefono
    $('#telefono_numero_n').mask('+00 000000000000000000');
    $('#telefono_numero').mask('+00 000000000000000000');

});

// Validación de formulario de modificacion y procesamiento
jQuery.validator.addMethod("numguionespacio", function(value, element) {
        var check = false;
        var regex = new RegExp("^[Z0-9\-\ \]+$");
        var key = value;

        if (!regex.test(key)) {
            check = false;
        } else {
            check = true;
        }
        return this.optional(element) || check;
    },
    "Ingrese solo numeros, guiones y espacios");


$("#formulario_modificacion").validate({

    ignore: [],
    rules: {
        cliente_nombre: {
            required: true,
            minlength: 4,
            remote: {
                url: "cliente_cb.php",
                type: "post",
                data: {
                    cliente_id: function() {
                        return $("#cliente_id").val();
                    },
                    opcion: 'cliente_existe_modificacion'
                }
            }
        },
        cliente_razonSocial: {
            required: true,
            minlength: 4
        },
        cliente_pais_id: {
            required: true
        },
        cliente_ciudad_id_2: {
            required: true
        },
        cliente_abreviatura: {
            required: true,
            minlength: 4
        },
        cliente_tipoCliente_id: {
            required: true
        }
    },
    messages: {
        cliente_nombre: {
            required: "Por favor ingrese un nombre",
            minlength: "Por favor ingrese por lo menos 4 caracteres"
        },
        cliente_razonSocial: {
            required: "Por favor ingrese una razon social",
            minlength: "Por favor ingrese por lo menos 4 caracteres"
        },
        cliente_pais_id: {
            required: "Por favor seleccione un pais"
        },
        cliente_ciudad_id_2: {
            required: "Por favor seleccione una ciudad"
        },
        cliente_abreviatura: {
            required: "Por favor ingrese una abreviatura",
            minlength: "Por favor ingrese por lo menos 4 caracteres"
        },
        cliente_tipoCliente_id: {
            required: "Por favor seleccione un tipo de cliente"
        }
    },
    submitHandler: function(form) {
        $.ajax({
            type: "POST",
            url: "cliente_cb.php",
            data: $(form).serialize(),
            success: function() {
                $.Notification.autoHideNotify('success', 'top right', 'Grabado exitosamente...', 'Los cambios han sido guardados.');
                grilla_listar('', '');
                $('#panel_grilla').slideDown();
                $('#panel_formulario_modificacion').slideUp();
            },
            error: function(xhr, ajaxOptions, thrownError) {
                $.Notification.autoHideNotify('error', 'top right', 'Error...', 'Reporte del error. ' + xhr.responseText);
            }
        });
        return false; // Previene que el formulario haga POST después de la validación, ya que ejecuto el guardado por Ajax.
    }
});



// Validación de formulario de alta y procesamiento
$("#formulario_alta").validate({
    ignore: [],

    rules: {
        cliente_nombre_n: {
            required: true,
            minlength: 4,
            remote: {
                url: "cliente_cb.php",
                type: "post",
                data: {
                    opcion: 'cliente_existe'
                }
            }
        },
        cliente_razonSocial_n: {
            required: true,
            minlength: 4
        },
        cliente_pais_id_n: {
            required: true
        },
        cliente_ciudad_id_n_2: {
            required: true
        },
        cliente_abreviatura_n: {
            required: true,
            minlength: 4
        },
        cliente_tipoCliente_id_n: {
            required: true
        }
    },
    messages: {
        cliente_nombre_n: {
            required: "Por favor ingrese un nombre",
            minlength: "Por lo menos 4 caracteres"
        },
        cliente_razonSocial_n: {
            required: "Por favor ingrese una razon social",
            minlength: "Por favor ingrese por lo menos 4 caracteres"
        },
        cliente_pais_id_n: {
            required: "Por favor seleccione un pais"
        },
        cliente_ciudad_id_n_2: {
            required: "Por favor seleccione una ciudad"
        },
        cliente_abreviatura_n: {
            required: "Por favor ingrese una abreviatura",
            minlength: "Por favor ingrese por lo menos 4 caracteres"
        },
        cliente_tipoCliente_id_n: {
            required: "Por favor seleccione un tipo de cliente"
        }
    },

    submitHandler: function(form) {
        //deshabilita boton Guardar al hacer el submit
        $("#btn_guardar_nuevo").attr("disabled", true);

        // Checkbox de telefono principal
        if ($('#telefono_principal_n').prop("checked")) {
            $('#telefono_principal_n').val('1');
        } else {
            $('#telefono_principal_n').val('0');
        }
        // Checkbox de email principal
        if ($('#email_principal_n').prop("checked")) {
            $('#email_principal_n').val('1');
        } else {
            $('#email_principal_n').val('0');
        }

        $.ajax({
            type: "POST",
            url: "cliente_cb.php",
            data: $(form).serialize(),
            success: function() {
                $.Notification.autoHideNotify('success', 'top right', 'Grabado exitosamente...', 'Los cambios han sido guardados.');
                grilla_listar('', '');
                $('#panel_grilla').slideDown();
                $("#formulario_alta").clearValidation();
                $('#formulario_alta')[0].reset();
                $('#panel_formulario_alta').slideUp();
                //habilita boton Guardar despues de realizado el insert
                $("#btn_guardar_nuevo").attr("disabled", false);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                $.Notification.autoHideNotify('error', 'top right', 'Error...', 'Reporte del error. ' + xhr.responseText);
            }
        });

        return false; // Previene que el formulario haga POST después de la validación, ya que ejecuto el guardado por Ajax.
    }
});



// Funciones para completar los SELECT
//
// Tipos de clientes para el Formulario de Alta de Clientes
function formulario_alta_tipoCliente() {

    var parametros = {
        "opcion": 'formulario_alta_tipoCliente'
    };

    var miselect = $("#cliente_tipoCliente_id_n");
    var miselect_b = $("#cliente_tipoCliente_id_b");

    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: 'cliente_cb.php',
        data: parametros,
        success: function(data) {

            miselect.append('<option value="">Seleccione un Tipo de Cliente</option>');
            miselect_b.append('<option value="">Seleccione un Tipo de Cliente</option>');

            for (var i = 0; i < data.length; i++) {
                miselect.append('<option value="' + data[i].tipoCliente_id + '">' + data[i].tipoCliente_nombre + '</option>');
                miselect_b.append('<option value="' + data[i].tipoCliente_id + '">' + data[i].tipoCliente_nombre + '</option>');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= ' + xhr.responseText + '</div>');
        }
    });
}

// Tipos de clientes para el Formulario de Modificacion de Cliente
function formulario_modificacion_tipoCliente(cliente_id) {

    var parametros = {
        "opcion": 'formulario_modificacion_tipoCliente',
        "cliente_id": cliente_id
    };

    var miselect = $("#cliente_tipoCliente_id");
    miselect.empty();

    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: 'cliente_cb.php',
        data: parametros,
        success: function(data) {

            for (var i = 0; i < data.length; i++) {
                miselect.append('<option value="' + data[i].tipoCliente_id + '">' + data[i].tipoCliente_nombre + '</option>');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= ' + xhr.responseText + '</div>');
        }
    });

}


// Paises para el Formulario de Alta de Cliente
function formulario_alta_paises() {

    var parametros = {
        "opcion": 'formulario_alta_paises'
    };

    var miselect = $("#cliente_pais_id_n");
    var miselect_b = $("#cliente_pais_id_b");

    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: 'cliente_cb.php',
        data: parametros,
        success: function(data) {

            miselect.append('<option value="">Seleccione un Pais</option>');
            miselect_b.append('<option value="">Seleccione un Pais</option>');

            for (var i = 0; i < data.length; i++) {
                miselect.append('<option value="' + data[i].pais_id + '">' + data[i].pais_nombreEspanol + '</option>');
                miselect_b.append('<option value="' + data[i].pais_id + '">' + data[i].pais_nombreEspanol + '</option>');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= ' + xhr.responseText + '</div>');
        }
    });
}

// Select Dependiente de Paises > Ciudades para el Formulario de Alta de Cliente
// Esto sirve para que cuando se modifica el select de país se borre lo que está en el input de ciudad
$("#cliente_pais_id_n").change(function() {
    $("#cliente_ciudad_id_n").val("");
    $("#cliente_ciudad_id_n_2").val("");
});

$('#cliente_ciudad_id_n').autocomplete({
    source: function(request, response) {
        $.ajax({
            method: "post",
            url: 'cliente_cb.php',
            dataType: "json",
            data: {
                ciudad: request.term,
                opcion: 'select_ciudades',
                pais_id: $("#cliente_pais_id_n option:selected").val()
            },
            success: function(data) {
                response($.map(data, function(item) {
                    var code = item.split("|");
                    return {
                        label: code[0],
                        value: code[0],
                        data: item
                    };
                }));
            }
        });
    },
    autoFocus: true,
    minLength: 3,
    select: function(event, ui) {
        var names = ui.item.data.split("|");
        $('#cliente_ciudad_id_n_2').val(names[1]);

    }
});

// Paises para el Formulario de Modificacion de Cliente
function formulario_modificacion_paises(cliente_id) {

    var parametros = {
        "opcion": 'formulario_modificacion_paises',
        "cliente_id": cliente_id
    };

    var miselect = $("#cliente_pais_id");
    miselect.empty();

    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: 'cliente_cb.php',
        data: parametros,
        success: function(data) {
            for (var i = 0; i < data.length; i++) {
                miselect.append('<option value="' + data[i].pais_id + '">' + data[i].pais_nombreEspanol + '</option>');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= ' + xhr.responseText + '</div>');
        }
    });
}

// Select Dependiente de Paises > Ciudades para el Formulario de Modificacion de Cliente
// Esto sirve para que cuando se modifica el select de país se borre lo que está en el input de ciudad
$("#cliente_pais_id").change(function() {
    $("#cliente_ciudad_id").val("");
    $("#cliente_ciudad_id_2").val("");
});

$('#cliente_ciudad_id').autocomplete({
    source: function(request, response) {
        $.ajax({
            method: "post",
            url: 'cliente_cb.php',
            dataType: "json",
            data: {
                ciudad: request.term,
                opcion: 'select_ciudades',
                pais_id: $("#cliente_pais_id option:selected").val()
            },
            success: function(data) {
                response($.map(data, function(item) {
                    var code = item.split("|");
                    return {
                        label: code[0],
                        value: code[0],
                        data: item
                    };
                }));
            }
        });
    },
    autoFocus: true,
    minLength: 3,
    select: function(event, ui) {
        var names = ui.item.data.split("|");
        $('#cliente_ciudad_id_2').val(names[1]);

    }
});



// Función para resetear validaciones que quedan arraigadas al formulario al menos que se las saque explícitamente.
$.fn.clearValidation = function() {
    var v = $(this).validate();
    $('[name]', this).each(function() {
        v.successList.push(this);
        v.showErrors();
    });
    v.resetForm();
    v.reset();
};



// Resetear el formulario alta cuando se pulsa en cancelar
$("#btn_cancelar_nuevo").click(function() {
    $("#formulario_alta").clearValidation();
    $('#formulario_alta')[0].reset();
    $('#panel_formulario_alta').slideUp();
    $('#panel_formulario_modificacion').slideUp();
    $('#panel_grilla').slideDown();
});


// Resetear el formulario modificacion cuando se pulsa en cancelar
$("#btn_cancelar").click(function() {
    $("#formulario_modificacion").clearValidation();
    $('#formulario_modificacion')[0].reset();
    $('#panel_formulario_modificacion').slideUp();
    $('#panel_grilla').slideDown();
});

// Cerrar vista de cliente
$("#btn_cancelar_vista").click(function(){
    $('#panel_formulario_ver').slideUp();
    $('#panel_grilla').slideDown();
});



// Agrega los datos al formulario
var agrega_cliente_formulario = function() {
    $('#panel_formulario_alta').removeClass('hidden');
    $('#panel_formulario_alta').slideDown();
    $('#panel_formulario_modificacion').slideUp();
    $('#panel_grilla').slideUp();
};

var modal_baja = function($cliente_id) {
    $('#ventana_modal_borrado').modal('show');
    $('#cliente_id_b').val($cliente_id);

};
var modal_alta = function($cliente_id) {
    $('#ventana_modal_habilita').modal('show');
    $('#cliente_id_a').val($cliente_id);

};



//Desactiva un cliente - borrado lógico
$("#formulario_baja").on("submit", function(e) {

    e.preventDefault();

    var cliente_id_b = $('#cliente_id_b').val();

    var parametros = {
        "cliente_id_b": cliente_id_b,
        "opcion": 'formulario_baja'
    };

    $.ajax({
        type: "POST",
        url: "cliente_cb.php",
        dataType: 'JSON',
        data: parametros,
        success: function(resultado) {
            if (resultado == 'ok') {
                $.Notification.autoHideNotify('success', 'top center', 'Deshabilitado exitosamente...', 'Se ha deshabilitado la cliente.');
                grilla_listar('','');

            } else {
                $.Notification.autoHideNotify('error', 'top center', 'Error...', 'Descripción:' + resultado);
            }


        },
        error: function(xhr, ajaxOptions, thrownError) {
            $.Notification.autoHideNotify('error', 'top center', 'Error...', 'Reporte del error. ' + xhr.responseText);
        }
    });
    $('#ventana_modal_borrado').modal('hide');
});


//Activa un cliente
$("#formulario_habilita").on("submit", function(e) {

    e.preventDefault();

    var cliente_id_a = $('#cliente_id_a').val();

    var parametros = {
        "cliente_id_a": cliente_id_a,
        "opcion": 'formulario_habilita'
    };

    $.ajax({
        type: "POST",
        url: "cliente_cb.php",
        dataType: 'JSON',
        data: parametros,
        success: function(resultado) {
            if (resultado == 'ok') {
                $.Notification.autoHideNotify('success', 'top center', 'Habilitado exitosamente...', 'Se ha rehabilitado el cliente.');
                grilla_listar('','');

            } else {
                $.Notification.autoHideNotify('error', 'top center', 'Error...', 'Descripción:' + resultado);
            }

        },
        error: function(xhr, ajaxOptions, thrownError) {
            $.Notification.autoHideNotify('error', 'top center', 'Error...', 'Reporte del error. ' + xhr.responseText);
        }
    });
    $('#ventana_modal_habilita').modal('hide');
});


// Carga los datos para el formulario de la vista
function formulario_ver(cliente_id) {

    // scrollea la pagina hasta arriba al cargar la funcion
    window.scrollTo(0,0);
    
    // carga el cliente_id en los elementos object
    $('#pantalla_producto_v').attr('data', 'cliente_producto.php?cliente_id=' + cliente_id);

    var parametros = {
        "cliente_id": cliente_id,
        "opcion": 'formulario_ver'
    };

    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: 'cliente_cb.php',
        data: parametros,
        success: function(data) {
            $('#cliente_id_v').val(data.cliente_id);
            $('#cliente_nombre_v').val(data.cliente_nombre);
            $('#cliente_razonSocial_v').val(data.cliente_razonSocial);
            $('#cliente_abreviatura_v').val(data.cliente_abreviatura);
            $('#cliente_paginaWeb_v').val(data.cliente_paginaWeb);
            $('#cliente_direccion_v').val(data.cliente_direccion);
            $('#cliente_codigoPostal_v').val(data.cliente_codigoPostal);
            $('#cliente_observaciones_v').val(data.cliente_observaciones);
            $('#cliente_tipoCliente_nombre_v').val(data.tipoCliente_nombre);
            $('#cliente_pais_nombre_v').val(data.pais_nombreEspanol);
            $('#cliente_ciudad_nombre_v').val(data.ciudad_nombre);            
            
            grilla_telefonos_v();
            grilla_emails_v();

            let cliente_modifica = (data.cliente_modifica);
            // Bloquea el boton de modificar cliente
            if (cliente_modifica == 1) {
                $("#btn_modificar_cliente").prop("disabled", false);
            }else{
                $("#btn_modificar_cliente").prop("disabled", true);
            }

            //$('#panel_formulario_alta').slideUp();
            //$('#panel_formulario_modificacion').slideUp();
            $('#panel_formulario_ver').removeClass('hidden');
            $('#panel_formulario_ver').slideDown();
            $('#panel_grilla').slideUp();
            
        }
    });
};




// carga los datos a editar en el formulario
//function formulario_modifica(cliente_id) {
    
$("#btn_modificar_cliente").on('click', function() {
    
    // scrollea la pagina hasta arriba al cargar la funcion
    window.scrollTo(0,0);

    let cliente_id = $("#cliente_id_v").val();
    
    // carga el cliente_id en los elementos object
    $('#pantalla_producto').attr('data', 'cliente_producto.php?cliente_id=' + cliente_id);

    var parametros = {
        "cliente_id": cliente_id,
        "opcion": 'formulario_modifica'
    };

    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: 'cliente_cb.php',
        data: parametros,
        success: function(data) {
            $('#cliente_id').val(data.cliente_id);
            $('#cliente_nombre').val(data.cliente_nombre);
            $('#cliente_razonSocial').val(data.cliente_razonSocial);
            $('#cliente_abreviatura').val(data.cliente_abreviatura);
            $('#cliente_paginaWeb').val(data.cliente_paginaWeb);
            $('#cliente_direccion').val(data.cliente_direccion);
            $('#cliente_codigoPostal').val(data.cliente_codigoPostal);
            $('#cliente_observaciones').val(data.cliente_observaciones);

            formulario_modificacion_tipoCliente(cliente_id);
            formulario_modificacion_paises(cliente_id);

            grilla_telefonos();
            grilla_emails();

            $('#cliente_ciudad_id').val(data.ciudad_nombre);
            $('#cliente_ciudad_id_2').val(data.cliente_ciudad_id);

            $('#panel_formulario_alta').slideUp();
            $('#panel_formulario_ver').slideUp();
            $('#panel_formulario_modificacion').removeClass('hidden');
            $('#panel_formulario_modificacion').slideDown();
            $('#panel_grilla').slideUp();

        }
    });
});


//Va a buscar los datos de la grilla
var grilla_listar = function() {

    let cliente_nombre = $("#cliente_nombre_buscar").val();
    let cliente_pais_id = $("#cliente_pais_id_b option:selected").val();
    let cliente_tipoCliente_id = $("#cliente_tipoCliente_id_b option:selected").val();

    var parametros = {
        "cliente_nombre_buscar": cliente_nombre,
        "cliente_pais_id_buscar": cliente_pais_id,
        "cliente_tipoCliente_id_buscar": cliente_tipoCliente_id,
        "opcion": 'grilla_listar_contar'
    };
    $.ajax({
        dataType: "html",
        method: "POST",
        url: "cliente_cb.php",
        data: parametros,

        success: function(data) {
            $("#grilla_info").html(data);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= ' + xhr.responseText + '</div>');
        }
    });


    var parametros = {
        "cliente_nombre_buscar": cliente_nombre,
        "cliente_pais_id_buscar": cliente_pais_id,
        "cliente_tipoCliente_id_buscar": cliente_tipoCliente_id,
        "opcion": 'grilla_listar'
    };
    $.ajax({
        dataType: "html",
        method: "POST",
        url: "cliente_cb.php",
        data: parametros,
        success: function(data) {
            $("#grilla_cliente").html(data);
            listar_clientes();
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= ' + xhr.responseText + '</div>');
        }
    });
};


// Hace arrancar el datatable
var listar_clientes = function() {

    var table = $("#dt_cliente").DataTable({
        "destroy": true,
        "stateSave": true,
        "bFilter": false,
        "language": idioma_espanol
    });
};


// Idioma de la grilla
var idioma_espanol = {
    "sProcessing": "Procesando...",
    "sLengthMenu": "Mostrar _MENU_ registros",
    "sZeroRecords": "No se encontraron resultados",
    "sEmptyTable": "Ningún dato disponible en esta tabla",
    "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
    "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
    "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
    "sInfoPostFix": "",
    "sSearch": "Buscar:",
    "sUrl": "",
    "sInfoThousands": ",",
    "sLoadingRecords": "Cargando...",
    "oPaginate": {
        "sFirst": "Primero",
        "sLast": "Último",
        "sNext": "Siguiente",
        "sPrevious": "Anterior"
    },
    "oAria": {
        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
    }
};



// -------------------------------------------   Area Teléfonos -------------------------------------------------

// Grilla para los teléfonos en vista
var grilla_telefonos_v = function(){
    
    let cliente_id = $("#cliente_id_v").val();
    
    var parametros = {
        "cliente_id": cliente_id,
        "opcion": 'grilla_telefonos_v'
    };
    $.ajax({
        dataType: "html",
        method: "POST",
        url: "cliente_cb.php",
        data: parametros
    }).done( function( data ){					
        $("#grilla_telefonos_v").html(data);
    });
};
// Grilla para los teléfonos
var grilla_telefonos = function() {

    let cliente_id = $("#cliente_id").val();

    var parametros = {
        "cliente_id": cliente_id,
        "opcion": 'grilla_telefonos'
    };
    $.ajax({
        dataType: "html",
        method: "POST",
        url: "cliente_cb.php",
        data: parametros
    }).done(function(data) {
        $("#grilla_telefonos").html(data);
        // Ver acá que otras acciones
        //  listar();   por el momento no voy a usar grilla DataTable
    });
};

// Lista de Telefonos para el Formulario de Alta de Clientes
function listarTipoTelefonos_cliente() {

    var parametros = {
        "opcion": 'listarTipoTelefonos_cliente'
    };

    var miselect = $("#telefonoTipo_id_n");
    var miselect2 = $("#telefonoTipo_id");

    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: 'cliente_cb.php',
        data: parametros,
        success: function(data) {

            miselect.append('<option value="">Seleccione</option>');
            miselect2.append('<option value="">Seleccione</option>');

            for (var i = 0; i < data.length; i++) {
                miselect.append('<option value="' + data[i].tipoTelefono_id + '">' + data[i].tipoTelefono_nombre + '</option>');
                miselect2.append('<option value="' + data[i].tipoTelefono_id + '">' + data[i].tipoTelefono_nombre + '</option>');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= ' + xhr.responseText + '</div>');
        }
    });
};

// Lista de Telefonos para el Formulario de Modificacion de Prestador
function listarTipoTelefonos_modificacion(telefono_id_e) {

    var parametros = {
        "opcion": 'listarTipoTelefonos_modificacion',
        "telefono_id_e": telefono_id_e
    };

    var miselect = $("#telefonoTipo_id");
    miselect.empty();

    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: 'cliente_cb.php',
        data: parametros,
        success: function(data) {
            for (var i = 0; i < data.length; i++) {
                miselect.append('<option value="' + data[i].tipoTelefono_id + '">' + data[i].tipoTelefono_nombre + '</option>');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= ' + xhr.responseText + '</div>');
        }
    });
};


// Limpia el contenido del formulario
var telefono_limpiar = function() {

    $("#telefono_nombre").val('');
    $("#telefono_numero").val('');
    $('#telefono_principal').prop('checked', false);
    $('#telefono_principal').val('');
    $("#telefono_id_m").val('');
    $('#telefono_nombre').css("border", "1px solid #E6E6E6");
    $('#telefono_numero').css("border", "1px solid #E6E6E6");

};


// Modal para borrar teléfono
var modal_telefono_borrar = function(telefono_id_b) {
    $('#ventana_modal_borrado_telefono').modal('show');
    $('#id_telefono_eliminar').val(telefono_id_b);
};


// Eliminar teléfono
$("#formulario_eliminar_telefono").on("submit", function(e) {

    e.preventDefault();

    var telefono_id_b = $('#id_telefono_eliminar').val();

    var parametros = {
        "telefono_id_b": telefono_id_b,
        "opcion": 'telefono_borrar'
    };

    $.ajax({
        dataType: "JSON",
        method: "POST",
        url: "cliente_cb.php",
        data: parametros,
        success: function(resultado) {
            if (resultado === 'OK') {
                $.Notification.autoHideNotify('success', 'top right', 'Teléfono borrado...', 'Se ha eliminado el Teléfono.');
            } else {
                $.Notification.autoHideNotify('error', 'top right', 'Error...', 'Descripción:' + resultado);
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $.Notification.autoHideNotify('error', 'top right', 'Error asincrónico...', 'Reporte del error. ' + xhr.responseText + ajaxOptions + thrownError);
        }

    }).done(function() {
        grilla_telefonos();
    });
    $('#ventana_modal_borrado_telefono').modal('hide');
    return false; // Previene que el formulario haga POST después de la validación, ya que ejecuto el guardado por Ajax. 
});


// Guarda el telefono nuevo o la modificación a un teléfono
var telefono_guardar = function() {

    let cliente_id = $("#cliente_id").val();
    let telefonoTipo_id = $("#telefonoTipo_id").val();
    let telefono_numero = $("#telefono_numero").val();


    if (telefonoTipo_id == '' || telefonoTipo_id == '') {
        $.Notification.autoHideNotify('error', 'top cneter', 'Debe completar todos los campos ', 'Algunos de los campos no se encuatran completos.');
        return;
    }


    if ($('#telefono_principal').prop('checked')) {
        telefono_principal = 1;
    } else {
        telefono_principal = 0;
    }
    let telefono_id_m = $("#telefono_id_m").val();

    let opcion;

    if (telefono_id_m) {
        opcion = 'telefono_modificar';
    } else {
        opcion = 'telefono_guardar';
    }


    var parametros = {
        "cliente_id": cliente_id,
        "opcion": opcion,
        "telefonoTipo_id": telefonoTipo_id,
        "telefono_numero": telefono_numero,
        "telefono_principal": telefono_principal,
        "telefono_id_m": telefono_id_m
    };
    $.ajax({
        dataType: "JSON",
        method: "POST",
        url: "cliente_cb.php",
        data: parametros,
        success: function(resultado) {
            if (resultado === 'OK') {
                $.Notification.autoHideNotify('success', 'top center', 'Teléfono guardado...', 'Se ha guardado el nuevo teléfono.');
                telefono_limpiar();
            } else {
                $.Notification.autoHideNotify('error', 'top center', 'Error...', 'Descripción:' + resultado);
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $.Notification.autoHideNotify('error', 'top center', 'Error asincrónico...', 'Reporte del error. ' + xhr.responseText + ajaxOptions + thrownError);
        }

    }).done(function() {
        // Refresca la grilla con los teléfonos
        grilla_telefonos();
    });
    return false; // Previene que el formulario haga POST después de la validación, ya que ejecuto el guardado por Ajax.
};


// Editar el telefono - Trae los valores al formulario para ser editados
var telefono_editar = function(telefono_id_e) {

    // $.Notification.autoHideNotify('success', 'top right', 'Teléfono principal...',telefono_principal);

    var parametros = {
        "telefono_id_e": telefono_id_e,
        "opcion": 'telefono_editar'
    };
    $.ajax({
        dataType: "JSON",
        method: "POST",
        url: "cliente_cb.php",
        data: parametros,
        success: function(data) {
            $('#telefono_id_m').val(data.telefono_id);
            //$('#telefonoTipo_id').val(data.telefono_tipoTelefono_id);
            listarTipoTelefonos_modificacion(data.telefono_id);
            $('#telefono_numero').val(data.telefono_numero);
            let telefono_principal = data.telefono_principal;
            if (telefono_principal === '1') {
                $('#telefono_principal').prop('checked', true);
            } else {
                $('#telefono_principal').prop('checked', false);
            }

            $('#telefono_nombre').css("border", "1px solid #5882FA");
            $('#telefono_numero').css("border", "1px solid #5882FA");


        },
        error: function(xhr, ajaxOptions, thrownError) {
            $.Notification.autoHideNotify('error', 'top center', 'Error asincrónico...', 'Reporte del error. ' + xhr.responseText + ajaxOptions + thrownError);
        }

    }).done(function() {
        // Refresca la grilla con los teléfonos
        grilla_telefonos();
    });
    return false; // Previene que el formulario haga POST después de la validación, ya que ejecuto el guardado por Ajax.
};

// -------------------------------------------  Fin Area Teléfonos -------------------------------------------------


// -------------------------------------------   Area E-mails ------------------------------------------------------

// Grilla para los teléfonos en vista
var grilla_emails_v = function(){
    
    let cliente_id = $("#cliente_id_v").val();
    
    var parametros = {
        "cliente_id": cliente_id,
        "opcion": 'grilla_emails_v'
    };
    $.ajax({
        dataType: "html",
        method: "POST",
        url: "cliente_cb.php",
        data: parametros
    }).done( function( data ){					
        $("#grilla_emails_v").html(data);
    });
};
// Grilla para los emails
var grilla_emails = function() {

    let cliente_id = $("#cliente_id").val();

    var parametros = {
        "cliente_id": cliente_id,
        "opcion": 'grilla_emails'
    };
    $.ajax({
        dataType: "html",
        method: "POST",
        url: "cliente_cb.php",
        data: parametros
    }).done(function(data) {
        $("#grilla_emails").html(data);
        // Ver acá que otras acciones
        //  listar();   por el momento no voy a usar grilla DataTable
    });
};

// Lista de tipos de email para el Formulario de Alta de Cliente
function listarTipoEmail_cliente() {

    var parametros = {
        "opcion": 'listarTipoEmail_cliente'
    };


    var miselect = $("#emailTipo_id_n");
    var miselect2 = $("#emailTipo_id");


    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: 'cliente_cb.php',
        data: parametros,
        success: function(data) {

            miselect.append('<option value="">Seleccione</option>');
            miselect2.append('<option value="">Seleccione</option>');

            for (var i = 0; i < data.length; i++) {
                miselect.append('<option value="' + data[i].tipoEmail_id + '">' + data[i].tipoEmail_nombre + '</option>');
                miselect2.append('<option value="' + data[i].tipoEmail_id + '">' + data[i].tipoEmail_nombre + '</option>');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= ' + xhr.responseText + '</div>');
        }
    });
};

// Lista de Telefonos para el Formulario de Modificacion de Cliente
function listarTipoEmail_modificacion(email_id_e) {

    var parametros = {
        "opcion": 'listarTipoEmail_modificacion',
        "email_id_e": email_id_e
    };

    var miselect = $("#emailTipo_id");
    miselect.empty();

    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: 'cliente_cb.php',
        data: parametros,
        success: function(data) {
            for (var i = 0; i < data.length; i++) {
                miselect.append('<option value="' + data[i].tipoEmail_id + '">' + data[i].tipoEmail_nombre + '</option>');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= ' + xhr.responseText + '</div>');
        }
    });
};

// Limpia el contenido del formulario
var email_limpiar = function() {

    $("#email_nombre").val('');
    $("#email_email").val('');
    $('#email_principal').prop('checked', false);
    $('#email_principal').val('');
    $("#email_id_m").val('');
    $('#email_nombre').css("border", "1px solid #E6E6E6");
    $('#email_email').css("border", "1px solid #E6E6E6");
};


// Modal para borrar email
var modal_email_borrar = function(email_id_b) {
    $('#ventana_modal_borrado_email').modal('show');
    $('#id_email_eliminar').val(email_id_b);
};


// Eliminar email
$("#formulario_eliminar_email").on("submit", function(e) {

    e.preventDefault();

    var email_id_b = $('#id_email_eliminar').val();

    var parametros = {
        "email_id_b": email_id_b,
        "opcion": 'email_borrar'
    };

    $.ajax({
        dataType: "JSON",
        method: "POST",
        url: "cliente_cb.php",
        data: parametros,
        success: function(resultado) {
            if (resultado === 'OK') {
                $.Notification.autoHideNotify('success', 'top right', 'E-mail borrado...', 'Se ha eliminado el E-mail.');
            } else {
                $.Notification.autoHideNotify('error', 'top right', 'Error...', 'Descripción:' + resultado);
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $.Notification.autoHideNotify('error', 'top right', 'Error asincrónico...', 'Reporte del error. ' + xhr.responseText + ajaxOptions + thrownError);
        }

    }).done(function() {
        grilla_emails();
    });
    $('#ventana_modal_borrado_email').modal('hide');
    return false; // Previene que el formulario haga POST después de la validación, ya que ejecuto el guardado por Ajax.  
});


// Funcion para la validacion de emails
function isEmail(emailAddress) {
    var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
    return pattern.test(emailAddress);
};


// Guarda el E-mail nuevo o la modificación a un E-mail
var email_guardar = function() {

    let cliente_id = $("#cliente_id").val();
    let emailTipo_id = $("#emailTipo_id").val();
    let email_email = $("#email_email").val();


    if (emailTipo_id == '' || email_email === '') {
        $.Notification.autoHideNotify('error', 'top center', 'Debe completar todos los campos ', 'Algunos de los campos no estan completos.');
        return;
    }

    if (!isEmail(email_email)) {
        $.Notification.autoHideNotify('error', 'top center', 'Debe colocar un mail válido ', 'Este no es un mail válido.');
        return;
    }

    if ($('#email_principal').prop('checked')) {
        email_principal = 1;
    } else {
        email_principal = 0;
    }
    let email_id_m = $("#email_id_m").val();

    let opcion;

    if (email_id_m) {
        opcion = 'email_modificar';
    } else {
        opcion = 'email_guardar';
    }


    var parametros = {
        "cliente_id": cliente_id,
        "opcion": opcion,
        "emailTipo_id": emailTipo_id,
        "email_email": email_email,
        "email_principal": email_principal,
        "email_id_m": email_id_m
    };
    $.ajax({
        dataType: "JSON",
        method: "POST",
        url: "cliente_cb.php",
        data: parametros,
        success: function(resultado) {
            if (resultado === 'OK') {
                $.Notification.autoHideNotify('success', 'top center', 'E-mail guardado...', 'Se ha guardado el nuevo E-mail.');
                email_limpiar();
            } else {
                $.Notification.autoHideNotify('error', 'top center', 'Error...', 'Descripción:' + resultado);
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $.Notification.autoHideNotify('error', 'top center', 'Error asincrónico...', 'Reporte del error. ' + xhr.responseText + ajaxOptions + thrownError);
        }

    }).done(function() {
        // Refresca la grilla con los E-mails
        grilla_emails();
    });
    return false; // Previene que el formulario haga POST después de la validación, ya que ejecuto el guardado por Ajax.
};


// Editar el E-mail - Trae los valores al formulario para ser editados
var email_editar = function(email_id_e) {

    var parametros = {
        "email_id_e": email_id_e,
        "opcion": 'email_editar'
    };
    $.ajax({
        dataType: "JSON",
        method: "POST",
        url: "cliente_cb.php",
        data: parametros,
        success: function(data) {
            $('#email_id_m').val(data.email_id);
            listarTipoEmail_modificacion(data.email_id);
            $('#email_email').val(data.email_email);

            let email_principal = data.email_principal;
            if (email_principal === '1') {
                $('#email_principal').prop('checked', true);
            } else {
                $('#email_principal').prop('checked', false);
            }

            $('#emailTipo_id').css("border", "1px solid #5882FA");
            $('#email_email').css("border", "1px solid #5882FA");


        },
        error: function(xhr, ajaxOptions, thrownError) {
            $.Notification.autoHideNotify('error', 'top center', 'Error asincrónico...', 'Reporte del error. ' + xhr.responseText + ajaxOptions + thrownError);
        }

    }).done(function() {
        // Refresca la grilla con los e-mails
        grilla_emails();
    });
    return false; // Previene que el formulario haga POST después de la validación, ya que ejecuto el guardado por Ajax.
};

// -------------------------------------------  Fin Area E-mails ------------------------------------------------------