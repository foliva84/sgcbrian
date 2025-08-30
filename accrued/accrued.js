//Verifica que se halla cargado la página para ejecutar las funciones.		
$().ready(function(){
    // Date Picker
    $('#reint_fechaPago_desde').datepicker();
    $('#reint_fechaPago_hasta').datepicker();

    // Formatea los campos monetarios
    $('#fci_importe_limite_n').number( true, 2, ',', '.' );
    $('#fci_importe_acumulado_v').number( true, 2, ',', '.' );

    // Autocarga las listas de Borradores
    listar_borrador_reintegros();
    listar_borrador_fci();

    // Autocarga Grilla
    listar_accrued_generados();
});



/* 
| GRILLAS
*/

// Función para Listar los Reintegros
function listar_reintegros() {

    $("#formulario_acc_reint").validate();

    // Toma Datos de los Input
    let reint_fechaPago_desde = $("#reint_fechaPago_desde").val();
    let reint_fechaPago_hasta = $("#reint_fechaPago_hasta").val();

    let parametros = {
        "reint_fechaPago_desde": reint_fechaPago_desde,
        "reint_fechaPago_hasta": reint_fechaPago_hasta,
        "opcion": 'listar_reintegros'
    };

    $.ajax({
        dataType: "html",
        method: "POST",
        url: "accrued_cb.php",
        data: parametros,
        success: function(data) {

            $("#grilla_acc_reint").html(data);
            dt_reintegros();

            // Muestra botón
            $('#btn_crear_borrador_reint').removeClass('hidden');

        },
        error: function(xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });

    // Previene que el formulario haga POST después de la validación, ya que ejecuto el guardado por Ajax.
    return false;
}

// Función para Listar los Reintegros en Borrador
function listar_borrador_reintegros() {

    var parametros = {
        "opcion": 'listar_borrador_reintegros'
    };
    $.ajax({
        dataType: "html",
        method: "POST",
        url: "accrued_cb.php",
        data: parametros,
        success: function(data) {
            $("#grilla_acc_reint_preview").html(data);
            dt_reintegros_borrador();
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });

    // Previene que el formulario haga POST después de la validación, ya que ejecuto el guardado por Ajax.
    return false;
}

// Función para Mostrar los Reintegros Procesados
function reint_procesado(lote_numero) {
    var parametros = {
        "lote_numero": lote_numero,
        "opcion": 'reint_procesado'
    };
    $.ajax({
        dataType: "html",
        method: "POST",
        url: "accrued_cb.php",
        data: parametros,
        success: function(data) {
            $("#grilla_reint_procesado").html(data);
            dt_reint_procesado();
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });

    // Previene que el formulario haga POST después de la validación, ya que ejecuto el guardado por Ajax.
    return false;
}

// Función para Listar los FCI
function listar_fci() {

    // Toma Datos de los Input
    let fci_fechaPago_desde = $("#fci_fechaPago_desde").val();
    let fci_fechaPago_hasta = $("#fci_fechaPago_hasta").val();

    var parametros = {
        "fci_fechaPago_desde": fci_fechaPago_desde,
        "fci_fechaPago_hasta": fci_fechaPago_hasta,
        "opcion": 'listar_fci'
    };
    $.ajax({
        dataType: "html",
        method: "POST",
        url: "accrued_cb.php",
        data: parametros,
        success: function(data) {
            $("#grilla_acc_fci").html(data);
            dt_fci();
            // Acciones en Botones
            $('#btn_borrador_fci').removeClass('hidden');
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });

    // Previene que el formulario haga POST después de la validación, ya que ejecuto el guardado por Ajax.
    return false;
}

// Función para Listar los FCI en Borrador
function listar_borrador_fci() {

    var parametros = {
        "opcion": 'listar_borrador_fci'
    };
    $.ajax({
        dataType: "html",
        method: "POST",
        url: "accrued_cb.php",
        data: parametros,
        success: function(data) {
            $("#grilla_acc_fci_preview").html(data);
            dt_fci_borrador();
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });

    // Previene que el formulario haga POST después de la validación, ya que ejecuto el guardado por Ajax.
    return false;
}

// Función para Mostrar los FCI Procesados
function fci_procesado(lote_numero) {
    var parametros = {
        "lote_numero": lote_numero,
        "opcion": 'fci_procesado'
    };
    $.ajax({
        dataType: "html",
        method: "POST",
        url: "accrued_cb.php",
        data: parametros,
        success: function(data) {
            $("#grilla_fci_procesado").html(data);
            dt_fci_procesado();
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });

    // Previene que el formulario haga POST después de la validación, ya que ejecuto el guardado por Ajax.
    return false;
}

// Función para Listar los Accrued Generados
function listar_accrued_generados() {

    var parametros = {
        "opcion": 'listar_accrued_generados'
    };
    $.ajax({
        dataType: "html",
        method: "POST",
        url: "accrued_cb.php",
        data: parametros,
        success: function(data) {
            $("#grilla_accrued_generados").html(data);
            dt_accrued_generados();
            // Importante para que luego de la carga dinámica vuelvan a funcionar los tooltips
            $('[data-toggle="tooltip"]').tooltip();
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });

    // Previene que el formulario haga POST después de la validación, ya que ejecuto el guardado por Ajax.
    return false;
}



/*
| ACCIONES
*/

// Función para mostrar el aprobado USD a medida que se seleccionan los FCI
function seleccion_fci() {

    // Inicializa variables
    let seleccionados = "";
    let contador = 0;

    $('input[name="seleccionados[]"]:checked').each(function() {
        seleccionados += $(this).val() + ",";
        contador++;
    });

    // Se elimina la última coma
    seleccionados = seleccionados.substring(0, seleccionados.length-1);

    // Completa info en el campo 'fci_seleccionados'
    $('#fci_seleccionados').val(seleccionados);
    
    // 1- Habilita o Deshabilita los botones btn_borrador_fci y btn_buscar_fci
    // 2- Valida diferencias entre importe limite e importe acumulado
    if (contador >= 1) {
        $("#btn_borrador_fci").attr("disabled", false);
        $("#btn_buscar_fci").attr("disabled", true);
    } else {
        $("#btn_borrador_fci").attr("disabled", true);
        $("#btn_buscar_fci").attr("disabled", false);
    };

    // Parametros para Ajax
    let parametros = {
        "fci_seleccionados": seleccionados,
        "opcion": 'sum_imp_aprobado_usd'
    };

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: '../facturacion/facturacion_cb.php',
        data: parametros,
        success:function(data) {
            
            let acumulado_usd = data;
            $('#fci_importe_acumulado_v').val(acumulado_usd);  
            
            // Valida que el importe limite no sea inferior al acumulado
            imp_limite = $('#fci_importe_limite_n').val();
            if (Number(imp_limite) > Number(acumulado_usd)) {
                //$("#btn_borrador_fci").attr("disabled", false);
                $("#campo_acumulado").removeClass("has-error");
                $("#campo_acumulado").addClass("has-success");
            } else {
                //$("#btn_borrador_fci").attr("disabled", true);
                $("#campo_acumulado").removeClass("has-success");
                $("#campo_acumulado").addClass("has-error");
            }
        }
    });
};

// Función para guardar el borrador Accrued de Reintegros
function guardar_acc_reint() {
    
    // Toma Datos de los Input
    let reint_fechaPago_desde = $("#reint_fechaPago_desde").val();
    let reint_fechaPago_hasta = $("#reint_fechaPago_hasta").val();
    
    let parametros = {
        "reint_fechaPago_desde": reint_fechaPago_desde,
        "reint_fechaPago_hasta": reint_fechaPago_hasta,
        "opcion": 'guardar_acc_reint'
    };

    $.ajax({
        dataType: "JSON",
        method: "POST",
        url: "accrued_cb.php",
        data: parametros,
        success:function(data) {

            if (data != false) {

                // Agrega o quita propiedades a class, para mostrar el tab de FCI
                $("#tab_acc_reint").removeClass("active");
                $("#tab_acc_fci").addClass("active");
                $("#tab_acc_reint").attr("aria-expanded","false");
                $("#tab_acc_fci").attr("aria-expanded","true");
                $("#acc_reint").removeClass("active");
                $("#acc_fci").addClass("active");

                // Recarga la lista de Reintegros
                listar_reintegros();
                listar_borrador_reintegros();

                // Bloquea botón
                $("#btn_buscar_reint").attr("disabled", true);
                // Oculta botón
                $('#btn_crear_borrador_reint').addClass('hidden');

                // Mensaje Success
                $.Notification.autoHideNotify('success', 'top center', 'Reintegros guardados en borrador', 'Los cambios han sido guardados.');

            } else {

                $('.notifyjs-wrapper').trigger('notify-hide');
                $.Notification.autoHideNotify('error', 'top center', 'Existe un Lote Pendiente Informar');
            }

        },
        error: function (xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });
}

// Función para guardar el borrador Accrued de FCI
$("#formulario_acc_fci").validate({
    
    submitHandler: function (form) {
        
        parametros = $(form).serialize();   
            
        $.ajax({
            dataType: "JSON",
            type: "POST",
            url: "accrued_cb.php",
            data: parametros,
            success: function (data) {

                if (data != false) {

                    // Función para mostrar el tab_preview
                    mostrar_tab_preview();

                    // Mensaje Success
                    $.Notification.autoHideNotify('success', 'top center', 'Items de Factura guardados en borrador', 'Los cambios han sido guardados.');

                    // Reset del Form
                    $('#formulario_acc_fci')[0].reset();

                    // Acciones en Botones
                    $("#btn_buscar_fci").attr("disabled", true);
                    $('#btn_borrador_fci').addClass('hidden');

                    // Recarga la lista de Reintegros
                    listar_fci();
                    listar_borrador_fci();

                } else {

                    $('.notifyjs-wrapper').trigger('notify-hide');
                    $.Notification.autoHideNotify('error', 'top center', 'Existe un Lote Pendiente Informar');
                }

            },
            error: function (xhr, ajaxOptions, thrownError) {
                $.Notification.autoHideNotify('error', 'top center', 'Error...', 'Reporte del error. ' + xhr.responseText);
            }
        });
        
        // Previene que el formulario haga POST después de la validación, ya que ejecuto el guardado por Ajax.
        return false;
    } 
});

// Función para mostrar el tab de Preview
function mostrar_tab_preview() {
    $("#tab_acc_reint").removeClass("active");
    $("#tab_acc_fci").removeClass("active");
    $("#tab_acc_preview").addClass("active");

    $("#tab_acc_reint").attr("aria-expanded","false");
    $("#tab_acc_fci").attr("aria-expanded","false");
    $("#tab_acc_preview").attr("aria-expanded","true");

    $("#acc_reint").removeClass("active");
    $("#acc_fci").removeClass("active");
    $("#acc_preview").addClass("active");
}

// Función para mostrar el Lote Procesado
function mostrar_lote_procesado(lote_numero) {

    // Completa el input con el ID del lote
    $('#exp_acc_id').val(lote_numero);

    // Llama a las funciones que listan los Reintegros y FCI Procesados
    reint_procesado(lote_numero);
    fci_procesado(lote_numero);

    // Acomoda paneles
    $('#panel_formulario_acc_generados').slideUp();
    $('#panel_formulario_acc_procesado').removeClass('hidden');
    $('#panel_formulario_acc_procesado').slideDown();
}

// Función para Procesar el Accrued
$("#btn_procesar_accrued").on('click', function() {
    
    let parametros = {
        "opcion": 'procesar_accrued'
    };

    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: 'accrued_cb.php',
        data: parametros,
        success: function(data) {

            if (data == 0) {
                
                // Mensaje Success
                $.Notification.autoHideNotify('success', 'top center', 'El Accrued se procesó correctamente', 'Ahora puede descargar el Excel.');

                // Actualiza las Grillas
                listar_borrador_reintegros();
                listar_borrador_fci();

            } else if (data == 1) {
                // Mensaje Warning - Falta FCI
                $.Notification.autoHideNotify('warning', 'top center', 'El Accrued no se pudo procesar', 'Es necesario crear el borrador de Items de Factura.');
            } else if (data == 2) {
                // Mensaje Warning - Falta Reintegros
                $.Notification.autoHideNotify('warning', 'top center', 'El Accrued no se pudo procesar', 'Es necesario crear el borrador de Reintegros.');
            } else if (data == 3) {
                // Mensaje Warning - Faltan Ambos
                $.Notification.autoHideNotify('warning', 'top center', 'El Accrued no se pudo procesar', 'Se deben crear los borradores de Reintegros y FCI.');
            }

        }
    });
});

// Función para informar el Accrued
function informar_accrued(lote_numero) {

    console.log('entro OK');

    let parametros = {
        "lote_numero": lote_numero,
        "opcion": 'informar_accrued'
    };

    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: 'accrued_cb.php',
        data: parametros,
        success: function() {

            // Mensaje Success
            $.Notification.autoHideNotify('success', 'top center', 'El Accrued se marcó como Informado Correctamente');

            // Actualiza las Grillas
            listar_accrued_generados();
        }
    });
}

// Función para Descargar el Accrued
let exportar_excel = function(form) {
   
    $.ajax({
            type: "POST",
            contentType: "application/json; charset=utf-8",
            url: "accrued_cb.php",
            dataType: "JSON",
            data: $(form).serialize(),
            complete:function () {
                window.location = "accrued_cb.php";
            }
    });
}



/*
| BOTONES SIMPLES
*/

// Función del botón Volver
$("#btn_volver_lista_acc").on('click', function() {
    // Acomoda paneles
    $('#panel_formulario_acc_procesado').slideUp();
    $('#panel_formulario_acc_generados').slideDown();
});



/*
| DATATABLES
*/

// DataTable para Reintegros
let dt_reintegros = function() {
    $("#dt_listado_reintegros").DataTable({
        "language": idioma_espanol
    });
};

// DataTable para Borrador de Reintegros
let dt_reintegros_borrador = function() {
    $("#dt_listado_reintegros_borrador").DataTable({
        "language": idioma_espanol
    });
};

// DataTable para Reintegros Procesados
let dt_reint_procesado = function() {
    $("#dt_reint_procesado").DataTable({
        "language": idioma_espanol
    });
};

// DataTable para FCI
let dt_fci = function() {
    $("#dt_listado_fci").DataTable({
        "language": idioma_espanol
    });
};

// DataTable para Borrador de FCI
let dt_fci_borrador = function() {
    $("#dt_listado_fci_borrador").DataTable({
        "language": idioma_espanol
    });
};

// DataTable para Reintegros Procesados
let dt_fci_procesado = function() {
    $("#dt_fci_procesado").DataTable({
        "language": idioma_espanol
    });
};

// DataTable para FCI
let dt_accrued_generados = function() {
    $("#dt_listado_acc_generados").DataTable({
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