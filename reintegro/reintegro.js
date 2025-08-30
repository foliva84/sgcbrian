// Funciones que se van a ejecutar en la primer carga de la página.
$().ready(function(){
    
    // Carga los datos en panel_formulario_vistaDatosCaso
    formulario_vistaDatosCaso('','');
    
    grilla_listar('','');
    $('#panel_grilla').slideDown();
    
    // Formatea los campos numericos
    $('#reintegro_CBUcuenta_m').mask("9999999999999999999999");
    $('#reintegro_beneficiarioDocumento_m').mask("99999999999");
    $('#reintegro_codigoArea_m').mask("9999");
    $('#reintegro_telefono_m').mask("9999999999");
    // Formatea los campos monetarios
    $('#reintegro_importe_m').number( true, 2, ',', '.' );
    
});

// Formularios
// 
// Formulario de ALTA de un REINTEGRO
$("#formulario_alta").validate({  
    ignore: [],
    rules: {
        reintegro_fechaPresentacion_n: {
            required: true
        }     
    },
    messages: {
        reintegro_fechaPresentacion_n: {
            required: "Por favor ingrese la fecha de presentacion del reintegro"
        }
    },
    
    submitHandler: function (form) {
        
        $.ajax({
            type: "POST",
            url: "reintegro_cb.php",
            data: $(form).serialize(),
            success: function (data) {
                
                $.Notification.autoHideNotify('success', 'top center', 'El reintegro se guardó exitosamente...', 'Los cambios han sido guardados.');
                
                // Limpia formulario alta
                $("#formulario_alta").clearValidation();
                $('#formulario_alta')[0].reset();
                
                // Acomoda paneles
                $('#panel_formulario_alta').slideUp();
                reintegro_id = data.replace(/"/g,"");
                formulario_lectura(reintegro_id); 
                
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $.Notification.autoHideNotify('error', 'top center', 'Error...', 'Reporte del error. ' + xhr.responseText);
            }
        });
        
        // Previene que el formulario haga POST después de la validación, ya que ejecuto el guardado por Ajax.
        return false;
    }
});

// Formulario de LECTURA de un REINTEGRO
function formulario_lectura(reintegro_id) {
    
    // scrollea la pagina hasta arriba al cargar la funcion
    window.scrollTo(0,0);
    
    // Le saca la propiedad hidden a la clase del menu tabs
    $('#menu_tabs').removeClass('hidden');
    
    // Carga los formularios con info de los distintos tabs para reintegros
    $('#pantalla_comunicacionesR').attr('data','../comunicacionReintegro/comunicacionR.php?reintegro_id=' + reintegro_id);
    $('#pantalla_archivos').attr('data','../archivos_reintegros/archivo.php?reintegro_id_archivo=' + reintegro_id);
    
      
    let parametros = {
        "reintegro_id": reintegro_id,
        "opcion": 'formulario_lectura'
    };
    
    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'reintegro_cb.php',
        data: parametros,
        success:function(data){
            
            // Datos Generales
            $('#reintegro_id_v').val(data.reintegro_id);
            $('#reintegroItem_reintegro_id_n').val(data.reintegro_id);
            $('#reintegro_estado_nombre_v').val(data.reintegroEstado_nombre);
            $('#reintegro_fechaPresentacion_v').val(data.reintegro_fechaPresentacion);
            $('#reintegro_fechaAuditado_v').val(data.reintegro_fechaAuditado);
            $('#reintegro_fechaPago_v').val(data.reintegro_fechaPago);
            $('#reintegro_formaPago_v').val(data.formaPago_nombre);
            $('#reintegro_valorTotal_usd_v').val(data.total_usd);
            $('#reintegro_valorTotal_ars_v').val(data.total_ars);
            $('#reintegro_importe_usd_v').val(data.total_usd);
            $('#reintegro_observaciones_v').val(data.reintegro_observaciones);
            
            /* Llama a la funcion que prepara:
            *   1- El formulario vista de reintegro
            *   2- Formulario alta de item
            *   3- Grilla de items / Archivos
            */
            preparar_formulario_lectura(reintegro_id, data.reintegro_reintegroEstado_id, data.total);
            
            // Importante para que luego de la carga dinámica vuelvan a funcionar los tooltips
            $('[data-toggle="tooltip"]').tooltip();
        }
    });
};

// Prepara los formularios de Vista de reintegros y Alta de Items
let preparar_formulario_lectura = function(reintegro_id, estado_reintegro, total_reintegro) {

    // scrollea la pagina hasta arriba la cargar la funcion
    window.scrollTo(0,0);

    // Deshabilita el boton de nuevo reintegro
    $('#btn_nuevo_reintegro').prop("disabled", true);
    
    // Muestra el menu tabs Reintegro e Items
    $('#menu_tabs').show();
    
    // Valida el estado de los Items del Reintegro
    let parametros = {
        "reintegro_id": reintegro_id,   
        "opcion": 'valida_estado_items'
    };
    
    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'reintegro_cb.php',
        data: parametros,
        success: function (data) {

            /* Validaciones para habilitar los botones */

            // Estado 'Pend. Documentación' (Id. 1)
            if (estado_reintegro == 1) {
                $('#btn_modificar_reintegro').removeClass('hidden');
                $('#grupo_btn_auditoria').removeClass('hidden');
                $('#btn_reintegro_aprobado').addClass('hidden');
                $('#btn_reintegro_rechazado').removeClass('hidden');
                $('#btn_reintegro_retener').addClass('hidden'); 
                $('#btn_reintegro_liberar').addClass('hidden'); 
                $('#btn_reintegro_formaPago').addClass('hidden');
                $('#btn_modificar_datosbancarios').removeClass('hidden');
                $('#btn_reintegro_rollbackPendDoc').addClass('hidden');
                $('#btn_reintegro_rollbackEnProceso').removeClass('hidden');
                $('#btn_reintegro_rollbackAuditado').addClass('hidden');
                $('#panel_formulario_alta_ri').removeClass('hidden');
            // Estado 'En Proceso' (Id. 2)
            } else if (estado_reintegro == 2) {
                $('#btn_modificar_reintegro').addClass('hidden');
                if ((data[0] != false) && (data[1] > 0)) {
                    $('#grupo_btn_auditoria').removeClass('hidden');
                    $('#btn_reintegro_aprobado').removeClass('hidden');
                    $('#btn_reintegro_rechazado').removeClass('hidden');
                    $('#btn_reintegro_retener').removeClass('hidden');
                } else {
                    $('#grupo_btn_auditoria').addClass('hidden');
                    $('#btn_reintegro_aprobado').addClass('hidden');
                    $('#btn_reintegro_rechazado').addClass('hidden');
                    $('#btn_reintegro_retener').addClass('hidden');
                }
                $('#btn_reintegro_liberar').addClass('hidden'); 
                $('#btn_reintegro_formaPago').addClass('hidden');
                $('#btn_modificar_datosbancarios').removeClass('hidden');
                $('#btn_reintegro_rollbackPendDoc').removeClass('hidden');
                $('#btn_reintegro_rollbackEnProceso').addClass('hidden');
                $('#btn_reintegro_rollbackAuditado').addClass('hidden');
                $('#panel_formulario_alta_ri').removeClass('hidden');
            // Estado 'Auditado' (Id. 3)
            } else if (estado_reintegro == 3) {
                $('#btn_modificar_reintegro').addClass('hidden');
                $('#grupo_btn_auditoria').addClass('hidden');
                $('#btn_reintegro_aprobado').addClass('hidden');
                $('#btn_reintegro_rechazado').addClass('hidden');
                $('#btn_reintegro_retener').addClass('hidden');
                $('#btn_reintegro_liberar').addClass('hidden'); 
                $('#btn_reintegro_formaPago').removeClass('hidden');
                $('#btn_modificar_datosbancarios').removeClass('hidden');
                $('#btn_reintegro_rollbackPendDoc').addClass('hidden');
                $('#btn_reintegro_rollbackEnProceso').addClass('hidden');
                $('#btn_reintegro_rollbackAuditado').addClass('hidden');
                $('#panel_formulario_alta_ri').addClass('hidden');
            // Estado 'Pend. Orden de Pago' (Id. 4)
            } else if (estado_reintegro == 4) {
                $('#btn_modificar_reintegro').addClass('hidden');
                $('#grupo_btn_auditoria').addClass('hidden');
                $('#btn_reintegro_aprobado').addClass('hidden');
                $('#btn_reintegro_rechazado').addClass('hidden');
                $('#btn_reintegro_retener').addClass('hidden');
                $('#btn_reintegro_liberar').addClass('hidden'); 
                $('#btn_reintegro_formaPago').addClass('hidden');
                $('#btn_modificar_datosbancarios').removeClass('hidden');
                $('#btn_reintegro_rollbackPendDoc').addClass('hidden');
                $('#btn_reintegro_rollbackEnProceso').addClass('hidden');
                $('#btn_reintegro_rollbackAuditado').addClass('hidden');
                $('#panel_formulario_alta_ri').addClass('hidden'); 
            // Estado 'Pendiente Pago' (Id. 5)
            } else if (estado_reintegro == 5) {
                $('#btn_modificar_reintegro').addClass('hidden');
                $('#grupo_btn_auditoria').addClass('hidden');
                $('#btn_reintegro_aprobado').addClass('hidden');
                $('#btn_reintegro_rechazado').addClass('hidden');
                $('#btn_reintegro_retener').addClass('hidden');
                $('#btn_reintegro_liberar').addClass('hidden'); 
                $('#btn_reintegro_formaPago').addClass('hidden');
                $('#btn_modificar_datosbancarios').addClass('hidden');
                $('#btn_reintegro_rollbackPendDoc').addClass('hidden');
                $('#btn_reintegro_rollbackEnProceso').addClass('hidden');
                $('#btn_reintegro_rollbackAuditado').removeClass('hidden');
                $('#panel_formulario_alta_ri').addClass('hidden');
            // Estado 'Auditado' (Id. 3)
            } else if (estado_reintegro == 7) {
                $('#btn_modificar_reintegro').addClass('hidden');
                $('#grupo_btn_auditoria').addClass('hidden');
                $('#btn_reintegro_aprobado').addClass('hidden');
                $('#btn_reintegro_rechazado').addClass('hidden');
                $('#btn_reintegro_retener').addClass('hidden');
                $('#btn_reintegro_liberar').removeClass('hidden'); 
                $('#btn_reintegro_formaPago').addClass('hidden');
                $('#btn_modificar_datosbancarios').addClass('hidden');
                $('#btn_reintegro_rollbackPendDoc').addClass('hidden');
                $('#btn_reintegro_rollbackEnProceso').addClass('hidden');
                $('#btn_reintegro_rollbackAuditado').addClass('hidden');
                $('#panel_formulario_alta_ri').addClass('hidden');
            // Sino...     
            } else {
                $('#btn_modificar_reintegro').addClass('hidden');
                $('#grupo_btn_auditoria').addClass('hidden');
                $('#btn_reintegro_aprobado').addClass('hidden');
                $('#btn_reintegro_rechazado').addClass('hidden');
                $('#btn_reintegro_retener').addClass('hidden');
                $('#btn_reintegro_liberar').addClass('hidden'); 
                $('#btn_reintegro_formaPago').addClass('hidden');
                $('#btn_modificar_datosbancarios').addClass('hidden');
                $('#btn_reintegro_rollbackPendDoc').addClass('hidden');
                $('#btn_reintegro_rollbackEnProceso').addClass('hidden');
                $('#btn_reintegro_rollbackAuditado').addClass('hidden');
                $('#panel_formulario_alta_ri').addClass('hidden');
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $.Notification.autoHideNotify('error', 'top center', 'Error...', 'Reporte del error. ' + xhr.responseText);
        }
    });

    // IMPLEMENTAR: Deshabilita el boton modificar si la reintegro avanzo en el proceso de aprobacion
    /*if (estadoFactura != 1 && estadoFactura != 2) {
        $('#btn_modificar_reintegro').prop("disabled", true);
    } else {
        $('#btn_modificar_reintegro').prop("disabled", false);
    }*/
    
    // Formatea los campos monetarios de ITEM
    $('#reintegroItem_importeOrigen_n').number( true, 2, ',', '.' );
    $('#reintegroItem_monedaTC_n').number( true, 2, ',', '.' );
    $('#reintegroItem_importeUSD_n').number( true, 2, ',', '.' );
    $('#reintegroItem_importeOrigen').number( true, 2, ',', '.' );
    $('#reintegroItem_monedaTC').number( true, 2, ',', '.' );
    $('#reintegroItem_importeUSD').number( true, 2, ',', '.' );
    
    // Agrega la informacion a los distintos Select en el formulario de ALTA ITEM
    listar_riConceptos_alta();
    listar_riMonedas_alta();
    // Deshabilita los campos readonly en ALTA ITEM
    $('#reintegroItem_monedaTC_n').prop("disabled", true);
    $('#reintegroItem_importeUSD_n').prop("disabled", true);
    
    // Acomoda los paneles
    $('#panel_formulario_alta').slideUp();
    $('#panel_formulario_vista').removeClass('hidden');
    $('#panel_formulario_vista').slideDown();
    $('#panel_grilla').slideUp();
    
    $('#panel_grilla_items').slideDown();
    grilla_listar_items('','');

};

// Prepara la vista de reintegros e Items pagos (Estado Cerrado)
let preparar_vistaReintegroCerrado = function() {
    
    // scrollea la pagina hasta arriba la cargar la funcion
    window.scrollTo(0,0);
    
    // Deshabilita el boton de nuevo reintegro
    $('#btn_nuevo_reintegro').prop("disabled", true);
    
    // Muestra el menu tabs Reintegro e Items
    $('#menu_tabs').show();
       
    // Acomoda los paneles
    $('#panel_formulario_alta').slideUp();
    $('#panel_formulario_vista').removeClass('hidden');
    $('#panel_formulario_vista').slideDown();
    $('#panel_grilla').slideUp();
    
    $('#panel_formulario_alta_ri').slideUp();
    $('#panel_grilla_items').slideDown();
    grilla_listar_items('','');
};


// Formulario de MODIFICACION de un REINTEGRO
$("#formulario_modificacion").validate({ 

    ignore: [],
    rules: {
        reintegro_fechaPresentacion_m: {
            required: true
        }
    },
    messages: {
        reintegro_fechaPresentacion_m: {
            required: "Por favor ingrese la fecha de presentacion del reintegro"
        }
    },
    
    submitHandler: function (form) {
                
        $.ajax({
            type: "POST",
            url: "reintegro_cb.php",
            data: $(form).serialize(),
            success: function () {
                
                $.Notification.autoHideNotify('success', 'top center', 'El reintegro se modifico exitosamente...', 'Los cambios han sido guardados.');
                
                // Limpia formulario modificacion
                $("#formulario_modificacion").clearValidation();
                $('#formulario_modificacion')[0].reset();
                
                // Acomoda los paneles
                $('#panel_formulario_modificacion').slideUp();
                $('#menu_tabs').hide();
                
                // Llamar a formulario_lectura para mostrar el reintegro recien modificado
                let reintegro_id = $('#reintegro_id_v').val();
                formulario_lectura(reintegro_id);

            },
            error: function (xhr, ajaxOptions, thrownError) {

                $.Notification.autoHideNotify('error', 'top center', 'Error...', 'Reporte del error. ' + xhr.responseText);

            }
        });
        
        // Previene que el formulario haga POST después de la validación, ya que ejecuto el guardado por Ajax.
        return false;
    }

});


// Formulario para establecer la Forma de Pago del Reintegro
$("#formulario_reintegro_formaPago").validate({  
    ignore: [],
    rules: {
        reintegro_formaPago_id: {
            required: true
        },
        reintegro_importe_ars: {
            required: true,
            min: 0.01
        }
    },
    messages: {
        reintegro_formaPago_id: {
            required: "Por favor seleccione la Forma de Pago"
        },
        reintegro_importe_ars: {
            required: "Por favor ingrese el Importe en ARS",
            min: "Por favor ingrese el Importe en ARS"
        }
    },
    
    submitHandler: function (form) {

        // Bloquea los btn hasta que se ejecute el ajax
        $("#btn_cancelarReintegro_formPago").attr("disabled", true);
        $("#btn_generar_pago").attr("disabled", true);
                
        $.ajax({
            type: "POST",
            url: "reintegro_cb.php",
            data: $(form).serialize(),
            beforeSend: function () {
                $.Notification.autoHideNotify('warning', 'top center', 'El Reintegro está siendo modificado...', 'Aguarde un instante por favor.');
            },
            success: function () {
                
                // Mensaje de Confirmación
                $.Notification.autoHideNotify('success', 'top center', 'El Reintegro se modifico exitosamente...', 'Los cambios han sido guardados.');

                // Desbloquea los btn hasta que se ejecute el ajax
                $("#btn_cancelarReintegro_formPago").attr("disabled", false);
                $("#btn_generar_pago").attr("disabled", false);
                
                // Limpia formulario modificacion
                $("#formulario_modificacion").clearValidation();
                $('#formulario_modificacion')[0].reset();
                
                // Acomoda los paneles
                $('#panel_formulario_modificacion').slideUp();
                $('#menu_tabs').hide();

                // Oculta modal de formas de pago de reintegros
                $('#modal_pago_reintegro').modal('hide');

                // Llamar a formulario_lectura para mostrar el reintegro recien modificado
                let reintegro_id = $('#reintegro_id_v').val();
                formulario_lectura(reintegro_id);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $.Notification.autoHideNotify('error', 'top center', 'Error...', 'Reporte del error. ' + xhr.responseText);
            }
        });
        
        // Previene que el formulario haga POST después de la validación, ya que ejecuto el guardado por Ajax.
        return false;
    }
});


// Formulario de DATOS BANCARIOS
//
// funcion de validacion SOLO LETRAS MAYUSCULAS
jQuery.validator.addMethod("LETRAS", function( value, element ) {
        var check = false;
        var regex = new RegExp("^[A-Z\ \]+$");
        var key = value;

        if (!regex.test(key)) {
            check = false;
        } else {
            check = true;
        }
        return this.optional(element) || check;
    }, 
    "Ingrese solo letras en mayusculas y espacio"
);


$("#formulario_datosBancarios").validate({
    ignore: [],
    rules: {
        reintegro_CBUcuenta_m: {
            required: true,
            minlength: 22
        },
        reintegro_denominacion_m: {
            required: function(e){
                return $("#reintegro_CBUcuenta_m").val().substr(0,3) !== "007";
            },
            LETRAS: true
        },
        reintegro_beneficiarioDocumento_m: {
            required: function(e){
                return $("#reintegro_CBUcuenta_m").val().substr(0,3) !== "007";
            }
        },
        reintegro_referencia_m: {
            LETRAS: true
        }
    },
    messages: {
        reintegro_CBUcuenta_m: {
            required: "Por favor ingrese el CBU de la cuenta",
            minlength: "Por favor ingrese un mínimo de 22 caracteres"
        },
        reintegro_denominacion_m: {
            required: "Por favor ingrese la denominacion"
        },
        reintegro_beneficiarioDocumento_m: {
            required: "Por favor ingrese el numero de documento"
        }
    },
    
    submitHandler: function (form) {
                
        $.ajax({
            type: "POST",
            url: "reintegro_cb.php",
            data: $(form).serialize(),
            success: function () {
                
                $.Notification.autoHideNotify('success', 'top center', 'Loa datos bancarios se guardaron exitosamente...', 'Los cambios han sido guardados.');
                
                // Acomoda los paneles
                $('#panel_formulario_datosBancarios').slideUp();
                
                // Llamar a formulario_lectura para mostrar el reintegro recien modificado
                let reintegro_id = $('#reintegro_id_v').val();
                formulario_lectura(reintegro_id);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $.Notification.autoHideNotify('error', 'top center', 'Error...', 'Reporte del error. ' + xhr.responseText);
            }
        });
        
        // Previene que el formulario haga POST después de la validación, ya que ejecuto el guardado por Ajax.
        return false;
    }
});

$("#formulario_datosBancarios_cl").validate({
    ignore: [],
    rules: {
        reintegro_CBUcuenta_m: {
            required: true
        },
        reintegro_denominacion_m: {
            
            LETRAS: true
        },
        reintegro_beneficiarioDocumento_m: {
            
        },
        reintegro_referencia_m: {
            LETRAS: true
        }
    },
    messages: {
        reintegro_CBUcuenta_m: {
            required: "Por favor ingrese el CBU de la cuenta",
        },
        reintegro_denominacion_m: {
            required: "Por favor ingrese la denominacion"
        },
        reintegro_beneficiarioDocumento_m: {
            required: "Por favor ingrese el numero de documento"
        }
    },
    
    submitHandler: function (form) {
                
        $.ajax({
            type: "POST",
            url: "reintegro_cb.php",
            data: $(form).serialize(),
            success: function () {
                
                $.Notification.autoHideNotify('success', 'top center', 'Loa datos bancarios se guardaron exitosamente...', 'Los cambios han sido guardados.');
                
                // Acomoda los paneles
                $('#panel_formulario_datosBancarios_cl').slideUp();
                
                // Llamar a formulario_lectura para mostrar el reintegro recien modificado
                let reintegro_id = $('#reintegro_id_v').val();
                formulario_lectura(reintegro_id);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $.Notification.autoHideNotify('error', 'top center', 'Error...', 'Reporte del error. ' + xhr.responseText);
            }
        });
        
        // Previene que el formulario haga POST después de la validación, ya que ejecuto el guardado por Ajax.
        return false;
    }
});

$("#formulario_datosBancarios_co").validate({
    ignore: [],
    rules: {
        reintegro_CBUcuenta_m: {
            required: true
        },
        reintegro_denominacion_m: {
            
            LETRAS: true
        },
        reintegro_beneficiarioDocumento_m: {
            
        },
        reintegro_referencia_m: {
            LETRAS: true
        }
    },
    messages: {
        reintegro_CBUcuenta_m: {
            required: "Por favor ingrese el CBU de la cuenta",
        },
        reintegro_denominacion_m: {
            required: "Por favor ingrese la denominacion"
        },
        reintegro_beneficiarioDocumento_m: {
            required: "Por favor ingrese el numero de documento"
        }
    },
    
    submitHandler: function (form) {
                
        $.ajax({
            type: "POST",
            url: "reintegro_cb.php",
            data: $(form).serialize(),
            success: function () {
                
                $.Notification.autoHideNotify('success', 'top center', 'Loa datos bancarios se guardaron exitosamente...', 'Los cambios han sido guardados.');
                
                // Acomoda los paneles
                $('#panel_formulario_datosBancarios_co').slideUp();
                
                // Llamar a formulario_lectura para mostrar el reintegro recien modificado
                let reintegro_id = $('#reintegro_id_v').val();
                formulario_lectura(reintegro_id);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $.Notification.autoHideNotify('error', 'top center', 'Error...', 'Reporte del error. ' + xhr.responseText);
            }
        });
        
        // Previene que el formulario haga POST después de la validación, ya que ejecuto el guardado por Ajax.
        return false;
    }
});

$("#formulario_datosBancarios_uy").validate({
    ignore: [],
    rules: {
        reintegro_CBUcuenta_m: {
            required: true
        },
        reintegro_denominacion_m: {
            LETRAS: true
        },
        reintegro_beneficiarioDocumento_m: {
            
        },
        reintegro_referencia_m: {
            LETRAS: true
        }
    },
    messages: {
        reintegro_CBUcuenta_m: {
            required: "Por favor ingrese el CBU de la cuenta",
        },
        reintegro_denominacion_m: {
            required: "Por favor ingrese la denominacion"
        },
        reintegro_beneficiarioDocumento_m: {
            required: "Por favor ingrese el numero de documento"
        }
    },
    
    submitHandler: function (form) {
                
        $.ajax({
            type: "POST",
            url: "reintegro_cb.php",
            data: $(form).serialize(),
            success: function () {
                
                $.Notification.autoHideNotify('success', 'top center', 'Loa datos bancarios se guardaron exitosamente...', 'Los cambios han sido guardados.');
                
                // Acomoda los paneles
                $('#panel_formulario_datosBancarios_uy').slideUp();
                
                // Llamar a formulario_lectura para mostrar el reintegro recien modificado
                let reintegro_id = $('#reintegro_id_v').val();
                formulario_lectura(reintegro_id);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $.Notification.autoHideNotify('error', 'top center', 'Error...', 'Reporte del error. ' + xhr.responseText);
            }
        });
        
        // Previene que el formulario haga POST después de la validación, ya que ejecuto el guardado por Ajax.
        return false;
    }
});


// Formulario de ALTA de un ITEM de Reintegro
$("#formulario_alta_ri").validate({  
    ignore: [],
    rules: {
        reintegroItem_concepto_id_n: {
            required: true
        },
        reintegroItem_importeOrigen_n: {
            required: true,
            min: 0.01
        },
        reintegroItem_moneda_id_n: {
            required: true
        },
        reintegroItem_monedaTC_n: {
            required: true,
            min: 0.01
        },
        reintegroItem_importeUSD_n: {
            required: true
        }
    },
    messages: {
        reintegroItem_concepto_id_n: {
            required: "Por favor seleccione el concepto"
        },
        reintegroItem_importeOrigen_n: {
            required: "Por favor ingrese el importe de origen",
            min: "El importe no puede ser nulo"
        },
        reintegroItem_moneda_id_n: {
            required: "Por favor seleccione la moneda de origen"
        },
        reintegroItem_monedaTC_n: {
            required: "Debe ingresar importe y moneda de origen",
            min: "El tipo de cambio no puede ser nulo"
        },
        reintegroItem_importeUSD_n: {
            required: "Algo falló, el Importe USD no fue calculado"
        }
    },
    
    submitHandler: function (form) {
        
        // Habilita los campos readonly en ALTA ITEM para el insert
        $('#reintegroItem_monedaTC_n').prop("disabled", false);
        $('#reintegroItem_importeUSD_n').prop("disabled", false);
        
        $.ajax({
            type: "POST",
            url: "reintegro_cb.php",
            data: $(form).serialize(),
            success: function () {
                
                $.Notification.autoHideNotify('success', 'top center', 'El item del reintegro se grabó exitosamente...', 'Los cambios han sido guardados.');
                
                // Limpia formulario alta
                $("#formulario_alta_ri").clearValidation();
                $('#formulario_alta_ri')[0].reset();
                
                $('#panel_grilla_items').slideDown();
                grilla_listar_items();

                // Llamar a formulario_lectura para actualizar el reintegro
                let reintegro_id = $('#reintegro_id_v').val();
                formulario_lectura(reintegro_id);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $.Notification.autoHideNotify('error', 'top center', 'Error...', 'Reporte del error. ' + xhr.responseText);
            }
        });
        
        // Previene que el formulario haga POST después de la validación, ya que ejecuto el guardado por Ajax.
        return false;
    }
});


// Formulario de LECTURA de un ITEM
function formulario_lectura_ri(reintegroItem_id) {
    
    // scrollea la pagina hasta arriba al cargar la funcion
    window.scrollTo(0,0);
    
    let parametros = {
        "reintegroItem_id": reintegroItem_id,
        "opcion": 'formulario_lectura_ri'
    };
    
    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'reintegro_cb.php',
        data: parametros,
        success:function(data){
            
            $('#reintegroItem_id').val(data.reintegroItem_id);
            listar_riConceptos_modificacion(data.reintegroItem_id);
            $('#reintegroItem_importeOrigen').val(data.reintegroItem_importeOrigen);
            listar_riMonedas_modificacion(data.reintegroItem_id);
            $('#reintegroItem_importeUSD').val(data.reintegroItem_importeUSD);
            $('#reintegroItem_monedaTC').val(data.reintegroItem_monedaTC);
            $('#reintegroItem_importeAprobadoUSD').val(data.reintegroItem_importeAprobadoUSD);
            $('#reintegroItem_observaciones').val(data.reintegroItem_observaciones);
            //$('#reintegroItem_estado_id').val(data.reintegroItem_estado_id);
            
            // Importante para que luego de la carga dinámica vuelvan a funcionar los tooltips
            $('[data-toggle="tooltip"]').tooltip();
            
            // Deshabilita los campos readonly en ALTA ITEM
            $('#reintegroItem_monedaTC').prop("disabled", true);
            $('#reintegroItem_importeUSD').prop("disabled", true);
            
            // Acomoda los paneles
            $('#panel_formulario_alta_ri').slideUp();
            $('#panel_formulario_modificacion_ri').removeClass('hidden');
            $('#panel_formulario_modificacion_ri').slideDown();
            
            $('#panel_grilla_items').slideUp();
            //grilla_listar_items();
        }
    });
};


// Formulario de MODIFICACION de un ITEM de Reintegro
$("#formulario_modificacion_ri").validate({  
    ignore: [],
    rules: {
        reintegroItem_concepto_id: {
            required: true
        },
        reintegroItem_importeOrigen: {
            required: true,
            min: 0.01
        },
        reintegroItem_moneda_id: {
            required: true
        },
        reintegroItem_monedaTC: {
            required: true,
            min: 0.01
        },
        reintegroItem_importeUSD: {
            required: true
        }
    },
    messages: {
        reintegroItem_concepto_id: {
            required: "Por favor seleccione el concepto"
        },
        reintegroItem_importeOrigen: {
            required: "Por favor ingrese el importe de origen",
            min: "El importe no puede ser nulo"
        },
        reintegroItem_moneda_id: {
            required: "Por favor seleccione la moneda de origen"
        },
        reintegroItem_monedaTC: {
            required: "Debe ingresar importe y moneda de origen",
            min: "El tipo de cambio no puede ser nulo"
        },
        reintegroItem_importeUSD: {
            required: "Algo falló, el Importe USD no fue calculado"
        }
    },
    
    submitHandler: function (form) {
        
        // Habilita los campos readonly en MODIFICAR ITEM para el update
        $('#reintegroItem_monedaTC').prop("disabled", false);
        $('#reintegroItem_importeUSD').prop("disabled", false);
        
        $.ajax({
            type: "POST",
            url: "reintegro_cb.php",
            data: $(form).serialize(),
            success: function () {
                
                $.Notification.autoHideNotify('success', 'top center', 'El item del reintegro se grabó exitosamente...', 'Los cambios han sido guardados.');
                
                // Limpia formulario modificacion
                $("#formulario_modificacion_ri").clearValidation();
                $('#formulario_modificacion_ri')[0].reset();
                
                $('#panel_formulario_modificacion_ri').slideUp();
                $('#panel_formulario_alta_ri').slideDown();
                $('#panel_grilla_items').slideDown();
                grilla_listar_items();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $.Notification.autoHideNotify('error', 'top center', 'Error...', 'Reporte del error. ' + xhr.responseText);
            }
        });
        
        // Previene que el formulario haga POST después de la validación, ya que ejecuto el guardado por Ajax.
        return false;
    }
});

// Formulario de AUTORIZACION de un ITEM de REINTEGRO
$("#formulario_autorizacion_ri").validate({  
    ignore: [],
    rules: {
        ri_mov_auditoria_auto_id: {
           required: true
        },
        ri_importeAprobadoUSD_auto: {
            required: function(e){
                return $("#ri_estado_au_id").val() === "1";
            },
            maxStrict: function(e){
                return $("#ri_importeRechazadoUSD_auto").val();
            },
            min: 0
        },
        ri_observaciones_auto: {
            required: function(e){
                return $("#ri_mov_auditoria_auto_id option:selected").val() === "3" || $("#ri_mov_auditoria_auto_id option:selected").val() === "4";
            }
        },
        ri_fechaPago_auto: {
            required: function(e){
                return $("#ri_mov_auditoria_auto_id option:selected").val() === "7";
            }
        },
        ri_formaPago_auto_id: {
            required: function(e){
                return $("#ri_mov_auditoria_auto_id option:selected").val() === "7";
            }
        }
    },
    messages: {
        ri_mov_auditoria_auto_id: {
            required: "Por favor seleccione un tipo de autorización"
        },
        ri_importeAprobadoUSD_auto: {
            required: "Por favor ingrese el importe aprobado en USD",
            maxStrict: "El importe aprobado no puede ser superior al importe del ITEM del Reintegro",
            min: "El importe aprobado no puede ser negativo"
        },
        ri_observaciones_auto: {
            required: "Por favor ingrese una observación"
        },
        ri_fechaPago_auto: {
            required: "Por favor ingrese una fecha de pago"
        },
        ri_formaPago_auto_id: {
            required: "Por favor seleccione una forma de pago"
        }
    },
    
    submitHandler: function (form) {
        
        $('#ri_importeAprobadoUSD_au').prop("disabled", false);
        $('#ri_mov_auditoria_auto_id').prop("disabled", false);
        
        $.ajax({
            type: "POST",
            url: "reintegro_cb.php",
            data: $(form).serialize(),
            success: function () {
                // Mensaje de confirmacion
                $.Notification.autoHideNotify('success', 'top center', 'El ITEM del reintegro se autorizó exitosamente...', 'Los cambios han sido guardados.');
                
                // Limpia formulario autorizacion
                $("#formulario_autorizacion_ri").clearValidation();
                $('#formulario_autorizacion_ri')[0].reset();
                $('#ri_importeAprobadoUSD_au').prop("disabled", true);
                
                // Oculta modal de autorizacion de reintegros
                $('#modal_auto_reintegros').modal('hide');

                // Arma la grilla con los ITEMS de Reintegro
                $('#panel_grilla_items').slideDown();
                grilla_listar_items();

                // Llamar a formulario_lectura para actualizar el reintegro
                let reintegro_id = $('#reintegro_id_v').val();
                formulario_lectura(reintegro_id);
            },
            error: function (xhr) {
                // Mensaje de error
                $.Notification.autoHideNotify('error', 'top center', 'Error...', 'Reporte del error. ' + xhr.responseText);
            }
        });
        
        // Previene que el formulario haga POST después de la validación, ya que ejecuto el guardado por Ajax.
        return false;
    }
});

// Completa los datos a mostrar del ITEM que se va a autorizar 
// y llama a la funcion que prepara el formulario de autorizacion
let autorizar_ri = function(reintegroItem_id) {
    
    // Limpia los select
    $("#ri_mov_auditoria_auto_id").empty();
    
    let parametros = {
        "reintegroItem_id": reintegroItem_id,
        "opcion": 'ri_pendiente_autorizar'
    };

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'reintegro_cb.php',
        data: parametros,
        success:function(data){
                        
            $('#ri_importeUSD_au').number( true, 2, ',', '.' );
            $('#ri_importeUSD_auto').number( true, 2, ',', '.' );
            $('#ri_importeAprobadoUSD_au').number( true, 2, ',', '.' );
            
            // Datos para ingresar en formulario
            $('#ri_id_au').val(data.reintegroItem_id);
            $('#ri_estado_au_id').val(data.riMov_riEstado_id);
            $('#ri_estado_au').val(data.riEstado_nombre);
            $('#ri_importeUSD_au').val(data.reintegroItem_importeUSD);
            $('#ri_importeAprobadoUSD_au').val(data.riMov_importeAprobadoUSD);
            $('#ri_importeUSD_auto').val(data.reintegroItem_importeUSD);
            
            listar_movEstados_alta(data.riMov_riEstado_id);
            
            preparar_formulario_autorizacion_ri(data.riMov_riEstado_id);
        },
        error: function (xhr) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });
}


// Convierte a mayúscula todo lo que se escriba en el campo reintegro_denominacion_m
$('#reintegro_denominacion_m').keyup(function() {
    this.value = this.value.toUpperCase();
});


// Prepara el formulario de AUTORIZACION de ITEMS de Facturas
let preparar_formulario_autorizacion_ri = function(estado_ri) {
    
    // Muestra el modal de autorizacion de ITEMS de Reintegros
    $('#modal_auto_reintegros').modal('show');
        
    // Date picker
    $('#ri_fechaPago_auto').datepicker({
        maxDate: new Date(),
        yearRange: '-1:+0'
    });
    
    // Limpia los select
    //$("#ri_formaPago_auto_id").empty();
    
    // Formatea los campos de monedas
    $('#ri_importeAprobadoUSD_auto').number( true, 2, ',', '.' );
    $('#ri_importeRechazadoUSD_auto').number( true, 2, ',', '.' );
    
    
    // Dependiendo del estado de ITEMS de Reintegros son los campos que muestra
    if (estado_ri == 1) {  //Estado: Ingresado
        $('#ri_mov_auditoria_auto_id').prop("disabled", true);
        $('.importeAprobadoRi').show();
        $('.importeUSDri').show();
        $('.importeRechazadoUSDri').show();
        $('.estadoAutorizacionRi').show();
        $('.fechaPago').hide();
        $('.formaPago').hide();
    } else if (estado_ri == 5) { // Estado: Aprobado Auditoria > Pend. Pago
        $('#ri_mov_auditoria_auto_id').prop("disabled", false);
        $('.importeAprobadoRi').hide();
        $('.importeUSDri').hide();
        $('.importeRechazadoUSDri').hide();
        $('.estadoAutorizacionRi').hide();
        $('.fechaPago').show();
        $('.formaPago').show();
    } else { // Todo el resto de los estados
        $('#ri_mov_auditoria_auto_id').prop("disabled", false);
        $('.importeAprobadoRi').hide();
        $('.importeUSDri').hide();
        $('.importeRechazadoUSDri').hide();
        $('.estadoAutorizacionRi').hide();
        $('.fechaPago').hide();
        $('.formaPago').hide();
    }
};

// Funcion para 
$("#ri_importeAprobadoUSD_auto").on('keyup', function() {
    
    let importe_real_USD    = $('#ri_importeUSD_auto').val();
    let importe_aprobado    = $('#ri_importeAprobadoUSD_auto').val();
    let importe_rechazado   = (importe_real_USD - importe_aprobado);
    
    $('#ri_importeRechazadoUSD_auto').val(importe_rechazado);
    
    if (importe_rechazado == 0)  {
        $('#ri_mov_auditoria_auto_id').val(2); // 2 - Item Aprobado Autorizacion
    } else if (importe_real_USD == importe_rechazado) {
        $('#ri_mov_auditoria_auto_id').val(4); // 4 - Item Rechazado Autorizacion
    } else {
        $('#ri_mov_auditoria_auto_id').val(3); // 3 - Item Aprobado Parcial Autorizacion
    }
    
    reintegro_log_estado = $("#ri_mov_auditoria_auto_id option:selected").val();
});

// Valida si el importe rechazado es mayor a 0 para el formulario AUTORIZACION
$.validator.addMethod('maxStrict', function (value, el, param) {
    return 0 <= param;
});

// Valida si el importe rechazado es mayor a 0 para el formulario AUTORIZACION
$.validator.addMethod('minZero', function (value, el, param) {
    return 0 < param;
});

// 
$("#ri_mov_auditoria_auto_id").on('change', function() {
    reintegro_log_estado = $("#ri_mov_auditoria_auto_id option:selected").val();
});


// Funciones auxiliares formulario
// 
// Función para resetear validaciones que quedan arraigadas al formulario al menos que se las saque explícitamente.
$.fn.clearValidation = function(){let v = $(this).validate();$('[name]',this).each(function(){v.successList.push(this);v.showErrors();});v.resetForm();v.reset();};

// Funcion para cargar el formulario MODIFICACION con la info del reintegro seleccionado
$("#btn_modificar_reintegro").on('click', function() {
    
    let reintegro_id = $('#reintegro_id_v').val();
    
    var parametros = {
        "reintegro_id": reintegro_id,
        "opcion": 'formulario_lectura'
    };
    
    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'reintegro_cb.php',
        data: parametros,
        success:function(data){
                        
            $('#reintegro_id_m').val(data.reintegro_id);
            $('#reintegroItem_reintegro_id_n').val(data.reintegro_id);
            $('#reintegro_estado_nombre_v2').val(data.reintegroEstado_nombre);
            $('#reintegro_fechaPresentacion_m').val(data.reintegro_fechaPresentacion);
            $('#reintegro_observaciones_m').val(data.reintegro_observaciones);
                       
            preparar_formulario_modificacion(data.reintegro_id);
        }
    });
});

// Funcion para cargar el formulario MODIFICACION con la info de los datos bancarios
$("#btn_modificar_datosbancarios").on('click', function() {
    
    let reintegro_id = $('#reintegro_id_v').val();
    
    var parametros = {
        "reintegro_id": reintegro_id,
        "opcion": 'formulario_lectura'
    };
    
    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'reintegro_cb.php',
        data: parametros,
        success:function(data){
            
            $('.reintegro_pais').val(data.pais);
            $('.reintegro_id_db').val(data.reintegro_id);
            $('#reintegroItem_reintegro_id_n').val(data.reintegro_id);
            
            
    
            // Datos Bancarios
            $('.reintegro_CBUcuenta_m').val(data.reintegro_CBUcuenta);
            $('.reintegro_CBUalias_m').val(data.reintegro_CBUalias);
            $('.reintegro_importe_m').val(data.total_ars);
            $('.reintegro_denominacion_m').val(data.reintegro_denominacion);
            listar_documentoTipos_modificacion(data.reintegro_id);
            $('.reintegro_beneficiarioDocumento_m').val(data.reintegro_beneficiarioDocumento);
            listar_referenciaTipos_modificacion(data.reintegro_id);
            $('.reintegro_referencia_m').val(data.reintegro_referencia);
            listar_avisoTransTipos_modificacion(data.reintegro_id);
            $('.reintegro_emailDestinatario_m').val(data.reintegro_emailDestinatario);
            $('.reintegro_emailTexto_m').val(data.reintegro_emailTexto);
            $('.reintegro_compania_m').val(data.reintegro_compania);
            $('.reintegro_codigoArea_m').val(data.reintegro_codigoArea);
            $('.reintegro_telefono_m').val(data.reintegro_telefono); 
            
            $('.reintegro_banco_m').val(data.reintegro_banco); 
            $('.reintegro_digito_verificacion_titular_m').val(data.reintegro_digito_verificacion_titular); 
            $('.reintegro_mail_titular_m').val(data.reintegro_mail_titular);
            if(data.reintegro_tipo_cuenta !=""){
                $(".reintegro_tipo_cuenta_m > option[value="+ data.reintegro_tipo_cuenta +"]").attr("selected",true);
            } 
            $('.reintegro_direccion_titular_m').val(data.reintegro_direccion_titular); 
            $('.reintegro_ciudad_m').val(data.reintegro_ciudad); 
            
            // Muestra el menu de los tabs
            $('#menu_tabs').show();

            // Acomoda los paneles segun pais 
            // console.log(data.pais);
            if(data.pais == 41){//Chile
                $('#panel_formulario_datosBancarios_cl').removeClass('hidden');
                $('#panel_formulario_datosBancarios_cl').slideDown();
            }else if(data.pais == 45){//Colombia
                $('#panel_formulario_datosBancarios_co').removeClass('hidden');
                $('#panel_formulario_datosBancarios_co').slideDown();
            }else if(data.pais == 237){//Uruguay
                $('#panel_formulario_datosBancarios_uy').removeClass('hidden');
                $('#panel_formulario_datosBancarios_uy').slideDown();
            }else{//el resto 
                $('#panel_formulario_datosBancarios').removeClass('hidden');
                $('#panel_formulario_datosBancarios').slideDown();
            }
            
        }
    });
});



// Prepara el formulario de ALTA de reintegros
let preparar_formulario_alta = function() {
    
    // scrollea la pagina hasta arriba la cargar la funcion
    window.scrollTo(0,0);
    
    // Date picker
    $('#reintegro_fechaPresentacion_n').datepicker({
        maxDate: new Date(),
        yearRange: '-1:+0'
    });
    
    // Deshabilita el boton de nuevo reintegro
    $('#btn_nuevo_reintegro').prop("disabled", true);
       
    // Acomoda los paneles
    $('#panel_formulario_alta').removeClass('hidden');
    $('#panel_formulario_alta').slideDown();
    $('#panel_grilla').slideUp();
};


// Prepara el formulario de MODIFICACION de reintegros
let preparar_formulario_modificacion = function(reintegro_id) {
    
    // scrollea la pagina hasta arriba la cargar la funcion
    //window.scrollTo(0,0);
    
    // Muestra el menu de los tabs
    $('#menu_tabs').show();
    
    // Date picker
    $('#reintegro_fechaPresentacion_m').datepicker({
        maxDate: new Date(),
        yearRange: '-1:+0'
    });
    
    
    // Chequea si existen items pendientes de pago
    var parametros = {
        "reintegro_id": reintegro_id,
        "opcion": 'chequeoItemsPendientes'
    };
    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'reintegro_cb.php',
        data: parametros,
        success:function(data){
            
            let cantItemsPendientes = (data.cantItemsPendientes);
        }
    });    
    
    // Acomoda los paneles
    $('#panel_formulario_modificacion').removeClass('hidden');
    $('#panel_formulario_modificacion').slideDown();
    $('#panel_formulario_vista').slideUp();
    
    $('#panel_grilla_items').slideDown();
    grilla_listar_items('','');    
};


// Funciones para completar los SELECT
// 
// Select de estados de reintegros para el Formulario de Alta
let listar_estadosReintegro_alta = function() {

    let parametros = {
        "opcion": 'listar_estadosReintegro_alta'
    };

    let miselect = $("#reintegro_estado_id_n");
    miselect.empty();

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'reintegro_cb.php',
        data: parametros,
        success:function(data){

            miselect.append('<option value="">Seleccione</option>');

            for (let i=0; i<data.length; i++) {
                miselect.append('<option value="' + data[i].reintegroEstado_id + '">' + data[i].reintegroEstado_nombre + '</option>');
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });  
};

// Select de tipos de documentos para alta de Datos Bancarios
let listar_documentoTipos_alta = function(){
    
    let parametros = {
        "opcion": 'listar_documentoTipos_alta'
    };

    let miselect = $("#reintegro_documentoTipo_id_n");
    miselect.empty();

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'reintegro_cb.php',
        data: parametros,
        success:function(data) {
            
            for (let i=0; i<data.length; i++) {
                miselect.append('<option value="' + data[i].tipoDocumento_id + '">' + data[i].tipoDocumento_nombre + '</option>');
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });  
};
let listar_documentoTipos_modificacion = function(reintegro_id){

    let parametros = {
        "opcion": 'listar_documentoTipos_modificacion',
        "reintegro_id": reintegro_id
    };

    let miselect = $(".reintegro_documentoTipo_id_m");
    miselect.empty();

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'reintegro_cb.php',
        data: parametros,
        success:function(data) {

            for (let i=0; i<data.length; i++) {
                miselect.append('<option value="' + data[i].tipoDocumento_id + '">' + data[i].tipoDocumento_nombre + '</option>');
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });  
};

// Select de tipos de referencia para alta de Datos Bancarios
let listar_referenciaTipos_alta = function(){
    
    let parametros = {
        "opcion": 'listar_referenciaTipos_alta'
    };

    let miselect = $("#reintegro_referenciaTipo_id_n");
    miselect.empty();

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'reintegro_cb.php',
        data: parametros,
        success:function(data) {

            for (let i=0; i<data.length; i++) {
                miselect.append('<option value="' + data[i].re_tipoReferencia_id + '">' + data[i].re_tipoReferencia_nombre + '</option>');
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });  
};
let listar_referenciaTipos_modificacion = function(reintegro_id){

    let parametros = {
        "opcion": 'listar_referenciaTipos_modificacion',
        "reintegro_id": reintegro_id
    };

    let miselect = $(".reintegro_referenciaTipo_id_m");
    miselect.empty();

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'reintegro_cb.php',
        data: parametros,
        success:function(data){

            for (let i=0; i<data.length; i++) {
                miselect.append('<option value="' + data[i].re_tipoReferencia_id + '">' + data[i].re_tipoReferencia_nombre + '</option>');
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });  
};

// Select de tipos de aviso de transferencia para alta de Datos Bancarios
let listar_avisoTransTipos_alta = function(){
    
    let parametros = {
        "opcion": 'listar_avisoTransTipos_alta'
    };

    let miselect = $("#reintegro_avisoTransTipo_id_n");
    miselect.empty();

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'reintegro_cb.php',
        data: parametros,
        success:function(data) {
            
            for (let i=0; i<data.length; i++) {
                miselect.append('<option value="' + data[i].re_tipoAvisoTrans_id + '">' + data[i].re_tipoAvisoTrans_nombre + '</option>');
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });  
};
let listar_avisoTransTipos_modificacion = function(reintegro_id){

    let parametros = {
        "opcion": 'listar_avisoTransTipos_modificacion',
        "reintegro_id": reintegro_id
    };

    let miselect = $(".reintegro_avisoTransTipo_id_m");
    miselect.empty();

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'reintegro_cb.php',
        data: parametros,
        success:function(data){
            for (let i=0; i<data.length; i++) {
                miselect.append('<option value="' + data[i].re_tipoAvisoTrans_id + '">' + data[i].re_tipoAvisoTrans_nombre + '</option>');
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });  
};



// Select de Conceptos de Item de Reintegro en formulario de alta item
let listar_riConceptos_alta = function(){
    
    let parametros = {
        "opcion": 'listar_riConceptos_alta'
    };

    let miselect = $("#reintegroItem_concepto_id_n");
    miselect.empty();

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'reintegro_cb.php',
        data: parametros,
        success:function(data) {
            
            miselect.append('<option value="">Seleccione</option>');
            
            for (let i=0; i<data.length; i++) {
                miselect.append('<option value="' + data[i].riConcepto_id + '">' + data[i].riConcepto_nombre + '</option>');
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });  
};

// Select de Conceptos de Item de Reintegro en formulario modificar item
let listar_riConceptos_modificacion = function(reintegroItem_id){
    
    let parametros = {
        "opcion": 'listar_riConceptos_modificacion',
        "reintegroItem_id": reintegroItem_id
    };

    let miselect = $("#reintegroItem_concepto_id");
    miselect.empty();

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'reintegro_cb.php',
        data: parametros,
        success:function(data) {
            
            for (let i=0; i<data.length; i++) {
                miselect.append('<option value="' + data[i].riConcepto_id + '">' + data[i].riConcepto_nombre + '</option>');
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });  
};

// Monedas para el formulario de alta de ITEM
function listar_riMonedas_alta(){

    var parametros = {
        "opcion": 'listar_riMonedas_alta'
    };

    var miselect = $("#reintegroItem_moneda_id_n");
    miselect.empty();

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'reintegro_cb.php',
        data: parametros,
        success:function(data){
            
            miselect.append('<option value="">Seleccione moneda</option>');
            
            for (var i=0; i<data.length; i++) {
                miselect.append('<option value="' + data[i].moneda_id + '">' + data[i].moneda_nombre + '</option>');
                
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });
};

// Monedas para el formulario de alta de ITEM
function listar_riMonedas_modificacion(reintegroItem_id){

    var parametros = {
        "opcion": 'listar_riMonedas_modificacion',
        "reintegroItem_id": reintegroItem_id
    };

    var miselect = $("#reintegroItem_moneda_id");
    miselect.empty();

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'reintegro_cb.php',
        data: parametros,
        success:function(data){
            
            for (var i=0; i<data.length; i++) {
                miselect.append('<option value="' + data[i].moneda_id + '">' + data[i].moneda_nombre + '</option>');
                
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });
};


let listar_movEstados_alta = function(ri_estado_id_au){
    
    let parametros = {
        "ri_estado_id_au": ri_estado_id_au,
        "opcion": 'listar_movEstados_alta'
    };

    let miselect = $("#ri_mov_auditoria_auto_id");
    miselect.empty();

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'reintegro_cb.php',
        data: parametros,
        success:function(data){
            
            miselect.append('<option value="">Seleccione</option>');
            
            for (let i=0; i<data.length; i++) {
                miselect.append('<option value="' + data[i].riEstado_id + '">' + data[i].riEstado_nombre + '</option>');
            }
        },
        error: function (xhr) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });  
};

// Select de formas de pago del reintegro
let listar_formasPagos_reintegro = function() {

    let parametros = {
        "opcion": 'listar_formasPagos_reintegro'
    };

    let miselect = $("#reintegro_formaPago_id");
    miselect.empty();

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'reintegro_cb.php',
        data: parametros,
        success:function(data){

            miselect.append('<option value="">Seleccione</option>');

            for (let i=0; i<data.length; i++) {
                miselect.append('<option value="' + data[i].formaPago_id + '">' + data[i].formaPago_nombre + '</option>');
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });  
};




// Funciones de grilla
//
// carga los datos a editar en el formulario
function formulario_vistaDatosCaso(){
    
    var caso_id = $("#caso_id_dGeneral").val();
    
    var parametros = {
        "caso_id": caso_id,
        "opcion": 'formulario_vistaDatosCaso'
    };
    
    $.ajax({
       type: 'POST',
       dataType:'JSON',
       url: 'reintegro_cb.php',
       data: parametros,
       success:function(data){
           
           $('#caso_numero_dGeneral').val(data.caso_numero);
           $('#caso_fechaSiniestro_dGeneral').val(data.caso_fechaSiniestro);
           $('#caso_beneficiarioNombre_dGeneral').val(data.caso_beneficiarioNombre);
           $('#caso_voucher_dGeneral').val(data.caso_numeroVoucher);
           $('#caso_producto_dGeneral').val(data.caso_productoNombre);
           $('#caso_agencia_dGeneral').val(data.caso_agencia);
           $('#caso_pais_nombre_dGeneral').val(data.caso_paisSiniestro);
       }
   });
};

//Va a buscar los datos de la grilla
var grilla_listar = function(){
    
    var caso_id = $('#caso_id').val();
    
    var parametros = {
        "opcion": 'grilla_listar',
        "caso_id": caso_id
    };
    
    $.ajax({
        dataType: "HTML",
        method: "POST",
        url: "reintegro_cb.php",
        data: parametros
    }).done( function( data ){
        $("#grilla_reintegros").html(data);
        listar_reintegros();
    });
};

//Va a buscar los datos para la grilla de items
var grilla_listar_items = function(){
    
    var reintegro_id = $('#reintegroItem_reintegro_id_n').val();
    
    var parametros = {
        "opcion": 'grilla_listar_items',
        "reintegro_id": reintegro_id
    };
    
    $.ajax({
        dataType: "HTML",
        method: "POST",
        url: "reintegro_cb.php",
        data: parametros
    }).done( function( data ){
        $("#grilla_reintegroItems").html(data);
        listar_reintegros();
    });
};

// Va a buscar los datos para la grilla de movimientos de items
let grilla_movimientos_ri = function(reintegroItem_id) {
    
    let parametros = {
        "reintegroItem_id": reintegroItem_id,
        "opcion": 'grilla_listar_mov_ri'
    };
    $.ajax({        
        method: "POST",
        dataType: "HTML",
        url: "reintegro_cb.php",
        data: parametros
    }).done(function(data){	
        // Muestra el modal con los movimientos del ITEM de Factura
        $('#modal_mov_ri').modal('show'); 
        // Arma la grilla con los movimientos del ITEM de Factura
        $("#grilla_mov_ri").html(data);
    });
};

// Va a buscar los datos para la grilla de observaciones del item
let grilla_observaciones_ri = function(reintegroItem_id) {
    
    let parametros = {
        "reintegroItem_id": reintegroItem_id,
        "opcion": 'grilla_observaciones_ri'
    };
    $.ajax({        
        method: "POST",
        dataType: "HTML",
        url: "reintegro_cb.php",
        data: parametros
    }).done(function(data){	
        // Muestra el modal con los movimientos del ITEM de Factura
        $('#modal_observaciones_ri').modal('show'); 
        // Arma la grilla con los movimientos del ITEM de Factura
        $("#grilla_observaciones_ri").html(data);
    });
};


// Hace arrancar el dataTable de reintegros
let listar_reintegros = function() {
    
    // Importante para que luego de la carga dinámica vuelvan a funcionar los tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    $("#dt_reintegros").DataTable({   
        "destroy":true,
        "stateSave": true,
        "bFilter": false,
        "language": idioma_espanol,
        "order": [ 1, 'desc' ]
    });
};

//Idioma de las grillas
let idioma_espanol = {
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
        "sSortAscending":  ": Actilet para ordenar la columna de manera ascendente",
        "sSortDescending": ": Actilet para ordenar la columna de manera descendente"
    }
};




// Acciones Botones
//
// Proceso de Auditoria del Reintegro
const proceso_auditoria = (auditoria_tipo) => {
    
    let reintegro_id = $("#reintegro_id_v").val();

    let parametros = {
        "reintegro_id": reintegro_id,
        "auditoria_tipo": auditoria_tipo,
        "opcion": 'auditar_reintegro'
    };

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'reintegro_cb.php',
        data: parametros,
        success:function(data) {

            formulario_lectura(reintegro_id);

            if (data == true) {
                $.Notification.autoHideNotify('success', 'top center', 'El reintegro se auditó exitosamente...', 'Los cambios han sido guardados.');
            } else if (data == false) {
                $.Notification.autoHideNotify('error', 'top center', 'Error al auditar el reintegro', 'Si el problema persiste contacte al Soporte');
            }

        },
        error: function (data) {
            $.Notification.autoHideNotify('error', 'top center', data, 'Error al realizar la operación');
        }
    });

};

// Cierra y Limpia el formulario ALTA cuando se pulsa en cancelar
$("#btn_cancelar_nuevo").on('click', function() {
    // scrollea la pagina hasta arriba al cargar la funcion
    window.scrollTo(0,0);
    // Habilita el boton de nuevo reintegro
    $('#btn_nuevo_reintegro').prop("disabled", false);
    // Limpia formulario
    $("#formulario_alta").clearValidation();
    $('#formulario_alta')[0].reset();
    // Acomoda paneles
    $('#panel_formulario_alta').slideUp();
    $('#panel_grilla').slideDown();
});

// Cierra y Limpia el formulario VISTA cuando se pulsa en cancelar
$("#btn_cerrar_vista").on('click', function() {
    // scrollea la pagina hasta arriba al cargar la funcion
    window.scrollTo(0,0);
    // Habilita el boton de nuevo reintegro
    $('#btn_nuevo_reintegro').prop("disabled", false);
    // Oculta el menu reintegros-items 
    $('#menu_tabs').hide();
    // Limpia formulario
    $("#formulario_vista").clearValidation();
    $('#formulario_vista')[0].reset();
    // Acomoda paneles
    $('#panel_formulario_vista').slideUp();
    $('#panel_formulario_datosBancarios_cl').slideUp();
    $('#panel_formulario_datosBancarios_co').slideUp();
    $('#panel_formulario_datosBancarios_uy').slideUp();
    $('#panel_formulario_datosBancarios').slideUp();
    $('#panel_grilla').slideDown();
    grilla_listar('','');
});

// Cierra y Limpia el formulario MODIFICACION cuando se pulsa en cancelar
$("#btn_cancelar_modificacion").on('click', function() {
    // scrollea la pagina hasta arriba al cargar la funcion
    window.scrollTo(0,0);    
    // Limpia formulario
    $("#formulario_modificacion").clearValidation();
    $('#formulario_modificacion')[0].reset();
    // Acomoda paneles
    $('#panel_formulario_modificacion').slideUp();
    $('#menu_tabs').show();
    $('#panel_formulario_vista').slideDown();
});

// Cierra y Limpia el formulario DATOS BANCARIOS cuando se pulsa en cancelar
$("#btn_cancelar_datosBancarios, #btn_cancelar_datosBancarios_cl, #btn_cancelar_datosBancarios_co, #btn_cancelar_datosBancarios_uy").on('click', function() {
    // scrollea la pagina hasta arriba al cargar la funcion
    window.scrollTo(0,0);    
    // Limpia formulario
    $("#formulario_datosBancarios").clearValidation();
    $('#formulario_datosBancarios')[0].reset();

    $("#formulario_datosBancarios_cl").clearValidation();
    $('#formulario_datosBancarios_cl')[0].reset();

    $("#formulario_datosBancarios_co").clearValidation();
    $('#formulario_datosBancarios_co')[0].reset();

    $("#formulario_datosBancarios_uy").clearValidation();
    $('#formulario_datosBancarios_uy')[0].reset();

    // Acomoda paneles
    // console.log('dscsd');
    $('#panel_formulario_datosBancarios_cl').slideUp();
    $('#panel_formulario_datosBancarios_co').slideUp();
    $('#panel_formulario_datosBancarios_uy').slideUp();
    $('#panel_formulario_datosBancarios').slideUp();
});

// 
$("#btn_cancelar_nuevo_ri").on('click', function() {
    // scrollea la pagina hasta arriba al cargar la funcion
    window.scrollTo(0,0);
    // Limpia formulario
    $("#formulario_alta_ri").clearValidation();
    $('#formulario_alta_ri')[0].reset();
    // Acomoda paneles
    $('#panel_grilla_items').slideDown();
});

// 
$("#btn_cancelar_modificacion_ri").on('click', function() {
    // scrollea la pagina hasta arriba al cargar la funcion
    window.scrollTo(0,0);
    // Limpia formulario
    $("#formulario_modificacion_ri").clearValidation();
    $('#formulario_modificacion_ri')[0].reset();
    // Acomoda paneles
    $('#panel_formulario_modificacion_ri').slideUp();
    $('#panel_formulario_alta_ri').slideDown();
    $('#panel_grilla_items').slideDown();
});

// 
$("#btn_cancelar_autorizacion_reintegro").on('click', function() {
    // Oculta el modal
    $('#modal_auto_reintegros').modal('hide');
    // Limpia el formulario
    $("#formulario_autorizacion_ri").clearValidation();
    $('#formulario_autorizacion_ri')[0].reset();
});

// Cambia el estado del reintegro a Retenido (Id. 7)
$("#btn_reintegro_retener").on('click', function() {

    let reintegro_id = $("#reintegro_id_v").val();

    var parametros = {
        "reintegro_id": reintegro_id,
        "opcion": 'retener_reintegro'
    };

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'reintegro_cb.php',
        data: parametros,
        success:function(data){
            formulario_lectura(reintegro_id);
            if (data == true) {
                $.Notification.autoHideNotify('success', 'top center', 'El reintegro se retuvo exitosamente...', 'Los cambios han sido guardados.');
            } else if (data == false) {
                $.Notification.autoHideNotify('error', 'top center', 'Error al retener el reintegro', 'No tiene permisos suficientes para retenerlo');
            }
        },
        error: function (xhr, ajaxOptions, thrownError, data) {
            $.Notification.autoHideNotify('error', 'top center', data, 'Error al realizar la operación');
        }
    });
});


// Libera el Reintegro y cambia el estado a En Proceso (Id. 2)
$("#btn_reintegro_liberar").on('click', function() {

    let reintegro_id = $("#reintegro_id_v").val();

    var parametros = {
        "reintegro_id": reintegro_id,
        "opcion": 'liberar_reintegro'
    };

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'reintegro_cb.php',
        data: parametros,
        success:function(data){
            formulario_lectura(reintegro_id);
            if (data == true) {
                $.Notification.autoHideNotify('success', 'top center', 'El reintegro se liberó exitosamente...', 'Los cambios han sido guardados.');
            } else if (data == false) {
                $.Notification.autoHideNotify('error', 'top center', 'Error al liberar el reintegro', 'No tiene permisos suficientes para liberarlo');
            }
        },
        error: function (xhr, ajaxOptions, thrownError, data) {
            $.Notification.autoHideNotify('error', 'top center', data, 'Error al realizar la operación');
        }
    });
});


// Abre el modal para establecer la forma de pago del reintegro
$("#btn_reintegro_formaPago").on('click', function() {

    // Limpia los campos
    $('#reintegro_tc_ars').val('');
    $('#reintegro_importe_ars').val('');
        
    // Formatea los campos importes
    $('#reintegro_tc_ars').number(true, 2, ',', '.');
    $('#reintegro_importe_ars').number(true, 2, ',', '.');
    
    // Completa el ID del Reintegro
    let reintegro_id = $("#reintegro_id_v").val();
    $("#reintegro_id_fp").val(reintegro_id);

    // Muestra el modal de pago del reintegro
    $('#modal_pago_reintegro').modal('show');

    // Lista las formas de pago
    listar_formasPagos_reintegro();
});


// Calculo en Front-End para pasar de USD a ARS
$("#reintegro_tc_ars").on('change', function() {

    // Toma variables
    let importe_usd = $("#reintegro_importe_usd_v").val();
    let tc = $("#reintegro_tc_ars").val();

    // Cálculo
    let importe_ars = importe_usd * tc;

    // Output
    $("#reintegro_importe_ars").val(importe_ars);
    
});


// ROLLBACK > Pasa el Reintegro al Estado 'Auditado (Id. 3)'
$("#btn_reintegro_rollbackAuditado").on('click', function() {

    let reintegro_id = $("#reintegro_id_v").val();

    var parametros = {
        "reintegro_id": reintegro_id,
        "opcion": 'rollback_reintegro'
    };

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'reintegro_cb.php',
        data: parametros,
        success:function(data){

            formulario_lectura(reintegro_id);

            if (data == true) {
                $.Notification.autoHideNotify('success', 'top center', 'Rollback realizado con éxito...', 'Los cambios han sido guardados.');
            } else if (data == false) {
                $.Notification.autoHideNotify('error', 'top center', 'Error al realizar el Rollback', 'No fue posible realizar esta acción');
            }

        },
        error: function (xhr, ajaxOptions, thrownError, data) {

            $.Notification.autoHideNotify('error', 'top center', data, 'Error al realizar el Rollback');

        }

    });

});


// ROLLBACK > Pasa el Reintegro al Estado 'Pend. Documentacion (Id. 1)'
$("#btn_reintegro_rollbackPendDoc").on('click', function() {

    let reintegro_id = $("#reintegro_id_v").val();

    var parametros = {
        "reintegro_id": reintegro_id,
        "opcion": 'rollback_reintegro_pendDoc'
    };

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'reintegro_cb.php',
        data: parametros,
        success:function(data){

            formulario_lectura(reintegro_id);

            if (data == true) {
                $.Notification.autoHideNotify('success', 'top center', 'Rollback realizado con éxito...', 'Los cambios han sido guardados.');
            } else if (data == false) {
                $.Notification.autoHideNotify('error', 'top center', 'Error al realizar el Rollback', 'No fue posible realizar esta acción');
            }

        },
        error: function (xhr, ajaxOptions, thrownError, data) {

            $.Notification.autoHideNotify('error', 'top center', data, 'Error al realizar el Rollback');

        }

    });

});


// ROLLBACK > Pasa el Reintegro al Estado 'En Proceso'
$("#btn_reintegro_rollbackEnProceso").on('click', function() {

    let reintegro_id = $("#reintegro_id_v").val();

    var parametros = {
        "reintegro_id": reintegro_id,
        "opcion": 'rollback_reintegro_enProceso'
    };

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'reintegro_cb.php',
        data: parametros,
        success:function(data){

            formulario_lectura(reintegro_id);

            if (data == true) {
                $.Notification.autoHideNotify('success', 'top center', 'Rollback realizado con éxito...', 'Los cambios han sido guardados.');
            } else if (data == false) {
                $.Notification.autoHideNotify('error', 'top center', 'Error al realizar el Rollback', 'No fue posible realizar esta acción');
            }

        },
        error: function (xhr, ajaxOptions, thrownError, data) {

            $.Notification.autoHideNotify('error', 'top center', data, 'Error al realizar el Rollback');

        }

    });

});


// Calculo automatico de importeUSD 
//
// Formulario ALTA
// Al cambiar el select de moneda, llama a la funcion 'consultar_tipo_cambio'
$("#reintegroItem_moneda_id_n").on('change', function() {    
    // Selecciona elementos del formulario ALTA
    let currency    = $("#reintegroItem_moneda_id_n option:selected").text();
    let amount      = $("#reintegroItem_importeOrigen_n").val();
    
    consultar_tipo_cambio('alta',currency,amount);
});


// Formulario MODIFICACION
// Al cambiar el select de moneda, llama a la funcion 'consultar_tipo_cambio'
$("#reintegroItem_moneda_id").on('change', function() {    
    
    // Selecciona elementos del formulario ALTA
    let currency    = $("#reintegroItem_moneda_id option:selected").text();
    let amount      = $("#reintegroItem_importeOrigen").val();
    
    consultar_tipo_cambio('modificacion',currency,amount);
});

// Formulario ALTA
// Al modificarse el campo de presunto origen, llama a la funcion 'calcular_importe_usd'
$("#reintegroItem_importeOrigen_n").add('#reintegroItem_monedaTC_n').on('keyup', function() {    
    
    let importe_origen  = $('#reintegroItem_importeOrigen_n').val();
    let tipo_cambio     = $('#reintegroItem_monedaTC_n').val();
    let resultado       = calcular_importe_usd('alta',importe_origen,tipo_cambio);
    
    $('#reintegroItem_importeUSD_n').val(resultado);
});

// Formulario MODIFICACION
// Al modificarse el campo de presunto origen, llama a la funcion 'calcular_importe_usd'
$("#reintegroItem_importeOrigen").add('#reintegroItem_monedaTC').on('keyup', function() {   
    
    let importe_origen  = $('#reintegroItem_importeOrigen').val();
    let tipo_cambio     = $('#reintegroItem_monedaTC').val();
    let resultado       = calcular_importe_usd('modificacion',importe_origen,tipo_cambio);
    
    $('#reintegroItem_importeUSD').val(resultado);
});

// Funcion para consultar el tipo de cambio - Consulta API
function consultar_tipo_cambio(form, moneda, importe_origen) {

    // Setea la conexion con la API
    endpoint = 'live';
    access_key = '2NgMGSgT3Ea2UNJZZEHVL7vEYgniu1oq';
    
    $.ajax({
        url: 'https://api.apilayer.com/currency_data/' + endpoint + '?source=&currencies=' + moneda,
        dataType: 'JSON',
        headers: {
            'apikey': access_key
        },
        success: function(data) {
            
            let tipoCambio;
            let tipo_cambio;
            let importeUSD;
            
            if(form == 'alta') {
                tipoCambio = $('#reintegroItem_monedaTC_n');
                importeUSD = $('#reintegroItem_importeUSD_n');
            } else if (form == 'modificacion') {
                tipoCambio = $('#reintegroItem_monedaTC');
                importeUSD = $('#reintegroItem_importeUSD');
            }

            // Valida si encuentra la moneda en el conversor
            if (data.success === true) {
                // Deshabilita el boton fci_tipoCambio_n
                tipoCambio.prop("disabled", true);
                // Busca en la primer posicion del objeto data.quotes y lo guarda en 'tipo_cambio'
                if (moneda === 'USD') {
                    tipo_cambio = '1';
                } else {
                    tipo_cambio = data.quotes[Object.keys(data.quotes)[0]];
                }
                // Llama a la funcion 'calcular_importe_usd'
                calcular_importe_usd(form,importe_origen,tipo_cambio);
            } else {
                $.Notification.autoHideNotify('error', 'top center', `La moneda ${moneda} no fue encontrada`, 'Ingrese el Tipo de Cambio en forma manual.');
                // Acciones sobre los campos fci_tipoCambio y fci_importeUSD
                    // Limpia el campo
                    tipoCambio.val('');
                    importeUSD.val('');
                    // Lo habilita
                    tipoCambio.prop("disabled", false);
                    // Hace foco en el
                    tipoCambio.focus();
            }
        },
        error: function (){    
            $.Notification.autoHideNotify('error', 'top center', `Hubo un error en la conversión de moneda`, 'Genere un ticket a soporte técnico copiando el codigo de error. Código: #CC239');
            // Acciones sobre el boton tipoCambio
                // Limpia el campo
                tipoCambio.val('');
                // Lo habilita
                tipoCambio.prop("disabled", false);
                // Hace foco en el
                tipoCambio.focus();
        }
    });
}

// Funcion para calcular la conversion a USD
function calcular_importe_usd(form,importe_origen,tipo_cambio) {
    
    let parametros = {
        "importe_origen": importe_origen,
        "tipo_cambio": tipo_cambio,
        "opcion": 'calcular_importe_usd'
    };
    
    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'reintegro_cb.php',
        data: parametros,
        success:function(data) {
            // Pone los resultados en el formulario de ALTA o MODIFICACION
            // Dependiendo de lo que llegue en la variable 'form'
            if (form === 'alta') {
                $("#reintegroItem_monedaTC_n").val(tipo_cambio);
                $('#reintegroItem_importeUSD_n').val(data);
            } else if (form === 'modificacion') {
                $("#reintegroItem_monedaTC").val(tipo_cambio);
                $('#reintegroItem_importeUSD').val(data);
            } else {
                $.Notification.autoHideNotify('error', 'top right', 'Error al convertir la moneda');
            }
        }
    });
}