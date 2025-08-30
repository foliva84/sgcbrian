$().ready(function() {

    // Funciones que se van a ejecutar en la primer carga de la página.

    // Carga los datos en la grilla
    grilla_listar('', '');
    // Select Estados de Casos
    casoEstadoslistar_buscadorCasos();
    // select Tipos de asistencia
    tipoAsistenciaListar_buscadorCasos();

    // Date Picker
    $('#caso_fechaSiniestro_n').datepicker({
        maxDate: new Date(),
        yearRange: '-1:+0',
        numberOfMonths: 2
    });
    $('#caso_fechaSiniestro').datepicker({
        maxDate: new Date(),
        yearRange: '-1:+0',
        numberOfMonths: 2
    });
    $('#caso_beneficiarioNacimiento_n').datepicker({
        maxDate: new Date(),
        yearRange: '-100:+0'
    });
    $('#caso_beneficiarioNacimiento').datepicker({
        maxDate: new Date(),
        yearRange: '-100:+0'
    });

    $('#caso_fechaSiniestro_desde_b').datepicker({
        maxDate: new Date(),
        yearRange: '-2:+0',
        numberOfMonths: 1
    });
    $('#caso_fechaSiniestro_hasta_b').datepicker({
        maxDate: new Date(),
        yearRange: '-2:+0',
        numberOfMonths: 1
    });
    $('#caso_fechaSalida_n').datepicker({
        maxDate: new Date(),
        yearRange: '-1:+0'
    });
    $('#caso_fechaSalida').datepicker({
        maxDate: new Date(),
        yearRange: '-1:+0'
    });

    //Emision y vigencia de voucher en alta de caso
    $(
        function() {
            var dateFormat = "dd-mm-yy";
            emision_n = $('#caso_fechaEmisionVoucher_n').datepicker()
                .on("change", function() { from_n.datepicker("option", "minDate", getDate(this)); }),
                $('#caso_vigenciaVoucherDesde_n').datepicker({ numberOfMonths: 2 })
                .on("change", function() { emision_n.datepicker("option", "maxDate", getDate(this)); }),

                from_n = $('#caso_vigenciaVoucherDesde_n').datepicker({ numberOfMonths: 2 })
                .on("change", function() { to_n.datepicker("option", "minDate", getDate(this)); }),
                to_n = $('#caso_vigenciaVoucherHasta_n').datepicker({ numberOfMonths: 2 })
                .on("change", function() { from_n.datepicker("option", "maxDate", getDate(this)); });

            function getDate(element) {
                var date;
                try {
                    date = $.datepicker.parseDate(dateFormat, element.value);
                } catch (error) {
                    date = null;
                }

                return date;
            }
        });

    $('#caso_fechaInicioSintomas_n').datepicker({
        maxDate: new Date(),
        yearRange: '-1:+0',
        numberOfMonths: 2
    });
    $('#caso_fechaInicioSintomas').datepicker({
        maxDate: new Date(),
        yearRange: '-1:+0',
        numberOfMonths: 2
    });

    //Perdida y recuperacion de equipaje en alta de caso
    $(
        function() {
            var dateFormat = "dd-mm-yy";
            from_equip_n = $('#caso_fechaPerdidaEquipaje_n').datepicker({
                maxDate: new Date(),
                yearRange: '-1:+0',
                numberOfMonths: 2
            })
            .on("change", function() { to_equip_n.datepicker("option", "minDate", getDate(this)); }),
            to_equip_n = $('#caso_fechaRecuperacionEquipaje_n').datepicker({
                    maxDate: new Date(),
                    yearRange: '-1:+0',
                    numberOfMonths: 2
                })
                .on("change", function() { from_equip_n.datepicker("option", "maxDate", getDate(this)); });

            function getDate(element) {
                var date;
                try {
                    date = $.datepicker.parseDate(dateFormat, element.value);
                } catch (error) {
                    date = null;
                }
                return date;
            }
        });

    // Funcion para formatear y borrar caracteres invalidos en los campos de telefono
    $('#telefono_numero_n').mask('+00 000000000000000000');
    $('#telefono_numero_n_2').mask('+00 000000000000000000');
    $('#telefono_numero').mask('+00 000000000000000000');
});

// *******************************************************************************************************************************************************************************************    
// FORMULARIO ALTA y PROCESAMIENTO
// *******************************************************************************************************************************************************************************************
// Validaciones y Form

jQuery.validator.addMethod("dateARG", function(value, element) {

    var check = false;
    var re = /^\d{1,2}\-\d{1,2}\-\d{4}$/;
    if (re.test(value)) {
        var adata = value.split('-');
        var dd = parseInt(adata[0], 10);
        var mm = parseInt(adata[1], 10);
        var yyyy = parseInt(adata[2], 10);
        var xdata = new Date(yyyy, mm - 1, dd);
        if ((xdata.getFullYear() === yyyy) && (xdata.getMonth() === mm - 1) && (xdata.getDate() === dd)) {
            check = true;
        } else {
            check = false;
        }
    } else {
        check = false;
    }
    return this.optional(element) || check;
},
    "Por favor ingrese una fecha valida"
);


$("#formulario_alta").validate({
    ignore: [],
    rules: {
        caso_numeroVoucher_n: {
            required: true
        },
        caso_beneficiarioNombre_n: {
            required: true,
            minlength: 5
        },
        caso_fechaSiniestro_n: {
            required: true,
            dateARG: true
        },
        caso_cliente_id_n: {
            required: true
        },
        caso_tipoAsistencia_id_n: {
            required: true
        },
        caso_fee_id_n: {
            required: true
        },
        caso_pais_id_n: {
            required: true
        },
        caso_ciudad_id_n: {
            required: true
        },
        // Para cuando se selecciona pais y ciudad, pero el id de ciudad no se carga en 'caso_ciudad_id_n_2'
        caso_ciudad_id_n_2: {
            required: function(e) {
                return $("#caso_ciudad_id_n").val() !== "";
            }
        },
        caso_beneficiarioNacimiento_n: {
            dateARG: true
        },
        caso_fechaSalida_n: {
            required: function(e) {
                return $("#caso_producto_id_n option:selected").text().includes('Multiviaje');
            },
            dateARG: true
        },
        caso_fechaEmisionVoucher_n: {
            dateARG: true
        },
        caso_vigenciaVoucherDesde_n: {
            dateARG: true
        },
        caso_vigenciaVoucherHasta_n: {
            dateARG: true
        },
        caso_fechaInicioSintomas_n: {
            required: function(e) {
                return $("#caso_tipoAsistencia_clasificacion_id_n").val() === "1";
            },
            dateARG: true
        },
        caso_sintomas_n: {
            required: function(e) {
                return $("#caso_tipoAsistencia_clasificacion_id_n").val() === "1";
            }
        },
        caso_deducible_n: {
            required: true
        },
        caso_producto_id_n: {
            required: true
        },
        caso_fechaPerdidaEquipaje_n: {
            dateARG: true
        },
        caso_fechaRecuperacionEquipaje_n: {
            dateARG: true
        },
        caso_observaciones_n: {
            required: function(e) {
                return $("#caso_info_ws_n").val() === "0";
            }
        }
    },
    messages: {
        caso_numeroVoucher_n: {
            required: "Por favor ingrese el número de voucher"
        },
        caso_beneficiarioNombre_n: {
            required: "Por favor ingrese el nombre del beneficiario",
            minlength: "Debe ingresar al menos 5 caracteres"
        },
        caso_fechaSiniestro_n: {
            required: "Por favor ingrese la fecha de siniestro"
        },
        caso_cliente_id_n: {
            required: "Por favor seleccione un cliente"
        },
        caso_tipoAsistencia_id_n: {
            required: "Por favor seleccione un tipo de asistencia"
        },
        caso_fee_id_n: {
            required: "Por favor seleccione un fee"
        },
        caso_pais_id_n: {
            required: "Por favor seleccione un pais"
        },
        caso_ciudad_id_n: {
            required: "Por favor seleccione una ciudad"
        },
        caso_ciudad_id_n_2: {
            required: "La ciudad no se cargo correctamente. Vuelva a seleccionarla."
        },
        caso_deducible_n: {
            required: "Por favor ingrese un deducible"
        },
        caso_producto_id_n: {
            required: "Por favor seleccione un producto"
        },
        caso_fechaSalida_n: {
            required: "Por favor ingrese la fecha de salida"
        },
        caso_fechaInicioSintomas_n: {
            required: "Por favor ingrese la fecha de inicio de sintomas"
        },
        caso_sintomas_n: {
            required: "Por favor ingrese un sintoma"
        },
        caso_observaciones_n: {
            required: "Ingrese el motivo del alta de un Caso Manual"
        }
    },

    submitHandler: function(form) {
        $("#btn_crear_nuevo").attr("disabled", true);
        // Habilita nuevamente los campos con .prop("disabled") para que se guarden correctamente en la tabla
        $('#caso_cliente_id_n').prop("disabled", false);
        $('#caso_beneficiarioNacimiento_n').prop("disabled", false);
        $('#caso_producto_id_n').prop("disabled", false);
        $('#caso_fechaEmisionVoucher_n').prop("disabled", false);
        $('#caso_vigenciaVoucherDesde_n').prop("disabled", false);
        $('#caso_vigenciaVoucherHasta_n').prop("disabled", false);

        // Checkbox

        // no medical cost_n
        if ($('#no_medical_cost_n').prop("checked")) {
            $('#no_medical_cost_n').val('1');
        } else {
            $('#no_medical_cost_n').val('0');
        }
        // Pax VIP
        if ($('#caso_paxVIP_n').prop("checked")) {
            $('#caso_paxVIP_n').val('1');
        } else {
            $('#caso_paxVIP_n').val('0');
        }
        // Caso Legal
        if ($('#caso_legal_n').prop("checked")) {
            $('#caso_legal_n').val('1');
        } else {
            $('#caso_legal_n').val('0');
        }

        // Llama a la funcion para validar las fechas ingresadas en el formulario_alta de Casos
        let resultado = valida_fechas('form_alta');
        if (resultado == 0) {
            return false;
        }

        // Con esto se puede ver como se serializó la información de un formulario.
        //console.log('FORMULARIO SERIALIZADO' + $(form).serialize());

        $.ajax({
            type: "POST",
            url: "caso_cb.php",
            data: $(form).serialize(),
            success: function(data) {

                // Limpia formulario alta
                $("#formulario_alta").clearValidation();
                $('#formulario_alta')[0].reset();
                $("#btn_crear_nuevo").attr("disabled", false);

                // Llamar a formulario_lectura para mostrar el caso recien cargado
                caso_id = data.replace(/"/g, "");
                formulario_lectura(caso_id);

                // Desbloquea el boton nuevo caso
                $('#btn_nuevo_caso').prop("disabled", false);
                $('#btn_caso_manual').prop("disabled", false);

            },
            error: function(xhr, ajaxOptions, thrownError) {
                $.Notification.autoHideNotify('error', 'top center', 'Error...', 'Reporte del error. ' + xhr.responseText);
            }
        });
        // Previene que el formulario haga POST después de la validación, ya que ejecuto el guardado por Ajax.
        return false;
    }
});


// Funcion para validar si se cargo correctamente el id de la ciudad en 'caso_ciudad_id_n_2'
// Actualmente existen casos en los que se carga mal ese valor y deja seguir el formulario
function valida_ciudad_id() {

}

// Funcion para validar fechas
function valida_fechas(form_desde) {

    $("#btn_crear_nuevo").attr("disabled", false);

    // Oculta las alertas que pudieran haber aparecido y deban cerrarse
    $('.notifyjs-wrapper').trigger('notify-hide');

    // Declaracion de variables
    let caso_fechaSiniestro, fecha_siniestro, caso_fechaSalida, fecha_salida, caso_emisionVoucher, fecha_emision, caso_vigenciaDesde, vigencia_desde,
        caso_vigenciaHasta, vigencia_hasta, caso_fechaNacimiento, fecha_nacimiento, caso_fechaInicioSintomas, fecha_inicioSintomas,
        caso_fechaPerdidaEquipaje, fecha_perdidaEquipaje, caso_fechaRecuperacionEquipaje, fecha_recuperacionEquipaje;

    // Condicional para validar desde que formulario se llama a la funcion
    // Para luego tomar y formatear variables fechas con moment.js del formulario correspondiente
    if (form_desde === 'form_alta') {
        // Fecha Siniestro
        caso_fechaSiniestro = $('#caso_fechaSiniestro_n').val();
        fecha_siniestro = moment(caso_fechaSiniestro, 'DD-MM-YYYY');
        // Fecha de Salida
        caso_fechaSalida = $('#caso_fechaSalida_n').val();
        fecha_salida = moment(caso_fechaSalida, 'DD-MM-YYYY');
        // Fecha Emision Voucher
        caso_emisionVoucher = $('#caso_fechaEmisionVoucher_n').val();
        fecha_emision = moment(caso_emisionVoucher, 'DD-MM-YYYY');
        // Fecha Vigencia Desde
        caso_vigenciaDesde = $('#caso_vigenciaVoucherDesde_n').val();
        vigencia_desde = moment(caso_vigenciaDesde, 'DD-MM-YYYY');
        // Fecha Vigencia Hasta
        caso_vigenciaHasta = $('#caso_vigenciaVoucherHasta_n').val();
        vigencia_hasta = moment(caso_vigenciaHasta, 'DD-MM-YYYY');
        // Fecha Nacimiento
        caso_fechaNacimiento = $('#caso_beneficiarioNacimiento_n').val();
        fecha_nacimiento = moment(caso_fechaNacimiento, 'DD-MM-YYYY');
        // Fecha Inicio Sintoma
        caso_fechaInicioSintomas = $('#caso_fechaInicioSintomas_n').val();
        fecha_inicioSintomas = moment(caso_fechaInicioSintomas, 'DD-MM-YYYY');
        // Fecha Perdida Equipaje
        caso_fechaPerdidaEquipaje = $('#caso_fechaPerdidaEquipaje_n').val();
        fecha_perdidaEquipaje = moment(caso_fechaPerdidaEquipaje, 'DD-MM-YYYY');
        // Fecha Recuperacion Equipaje
        caso_fechaRecuperacionEquipaje = $('#caso_fechaRecuperacionEquipaje_n').val();
        fecha_recuperacionEquipaje = moment(caso_fechaRecuperacionEquipaje, 'DD-MM-YYYY');
    } else if (form_desde === 'form_modificacion') {
        // Fecha Siniestro
        caso_fechaSiniestro = $('#caso_fechaSiniestro').val();
        fecha_siniestro = moment(caso_fechaSiniestro, 'DD-MM-YYYY');
        // Fecha de Salida
        caso_fechaSalida = $('#caso_fechaSalida').val();
        fecha_salida = moment(caso_fechaSalida, 'DD-MM-YYYY');
        // Fecha Emision Voucher
        caso_emisionVoucher = $('#caso_fechaEmisionVoucher').val();
        fecha_emision = moment(caso_emisionVoucher, 'DD-MM-YYYY');
        // Fecha Vigencia Desde
        caso_vigenciaDesde = $('#caso_vigenciaVoucherDesde').val();
        vigencia_desde = moment(caso_vigenciaDesde, 'DD-MM-YYYY');
        // Fecha Vigencia Hasta
        caso_vigenciaHasta = $('#caso_vigenciaVoucherHasta').val();
        vigencia_hasta = moment(caso_vigenciaHasta, 'DD-MM-YYYY');
        // Fecha Nacimiento
        caso_fechaNacimiento = $('#caso_beneficiarioNacimiento').val();
        fecha_nacimiento = moment(caso_fechaNacimiento, 'DD-MM-YYYY');
        // Fecha Inicio Sintoma
        caso_fechaInicioSintomas = $('#caso_fechaInicioSintomas').val();
        fecha_inicioSintomas = moment(caso_fechaInicioSintomas, 'DD-MM-YYYY');
        // Fecha Perdida Equipaje
        caso_fechaPerdidaEquipaje = $('#caso_fechaPerdidaEquipaje').val();
        fecha_perdidaEquipaje = moment(caso_fechaPerdidaEquipaje, 'DD-MM-YYYY');
        // Fecha Recuperacion Equipaje
        caso_fechaRecuperacionEquipaje = $('#caso_fechaRecuperacionEquipaje').val();
        fecha_recuperacionEquipaje = moment(caso_fechaRecuperacionEquipaje, 'DD-MM-YYYY');
    }


    /* Validaciones: FECHA SINIESTRO */
    // Fecha de Siniestro < Fecha de Salida
    if (fecha_siniestro < fecha_salida) {
        $.Notification.notify('error', 'top right', 'Error con fecha', 'La <b>Fecha del Siniestro</b> no puede ser <b>MENOR</b> a la <b>Fecha de Salida</b>');
        //return 0;
    }
    // Fecha del Siniestro < Fecha emisión del Voucher
    if (fecha_siniestro < fecha_emision) {
        $.Notification.notify('error', 'top right', 'Error con fecha', 'La <b>Fecha del Siniestro</b> no puede ser <b>MENOR</b> a la <b>Fecha de Emisión del Voucher</b>');
        //return 0;
    }
    // Fecha del Siniestro < Fecha Voucher Desde
    if (fecha_siniestro < vigencia_desde) {
        $.Notification.notify('error', 'top right', 'Error con fecha', 'La <b>Fecha del Siniestro</b> no puede ser <b>MENOR</b> a la <b>Vigencia Desde del Voucher</b>');
        //return 0;
    }
    // Fecha del Siniestro > Fecha Voucher Hasta
    if (fecha_siniestro > vigencia_hasta) {
        $.Notification.notify('error', 'top right', 'Error con fecha', 'La <b>Fecha del Siniestro</b> no debe ser <b>MAYOR</b> a la <b>Vigencia Hasta del Voucher</b>');
        //return 0;
    }
    // Fecha del Siniestro < Inicio de Síntomas
    if (fecha_siniestro < fecha_inicioSintomas) {
        $.Notification.notify('error', 'top right', 'Error con fecha', 'La <b>Fecha del Siniestro</b> no debe ser <b>MENOR</b> a la <b>Fecha de Inicio Sintomas</b>');
        //return 0;
    }
    // Fecha del Siniestro < Fecha de la pérdida del equipaje
    if (fecha_siniestro < fecha_perdidaEquipaje) {
        $.Notification.notify('error', 'top right', 'Error con fecha', 'La <b>Fecha del Siniestro</b> no puede ser <b>MENOR</b> a la <b>Fecha Pérdida del Equipaje</b>');
        return 0;
    }
    // Fecha del Siniestro > Fecha de recuperación del equipaje
    if (fecha_siniestro > fecha_recuperacionEquipaje) {
        $.Notification.notify('error', 'top right', 'Error con fecha', 'La <b>Fecha del Siniestro</b> no puede ser <b>MAYOR</b> a la <b>Fecha Recuperación del Equipaje</b>');
        return 0;
    }
    // Fecha del Siniestro < Fecha de Nacimiento
    if (fecha_siniestro < fecha_nacimiento) {
        $.Notification.notify('error', 'top right', 'Error con fecha', 'La <b>Fecha del Siniestro</b> no puede ser <b>MENOR</b> a la <b>Fecha de Nacimiento</b>');
        return 0;
    }

    /* Validaciones: EMISION DEL VOUCHER */
    // Fecha emisión del Voucher > Fecha de Salida
    if (fecha_emision > fecha_salida) {
        $.Notification.notify('error', 'top right', 'Error con fecha', 'La <b>Fecha Emisión del Voucher</b> no puede ser <b>MAYOR</b> a la <b>Fecha de Salida</b>');
        //return 0;
    }
    // Fecha emisión del Voucher > Fecha Voucher Desde
    if (fecha_emision > vigencia_desde) {
        $.Notification.notify('error', 'top right', 'Error con fecha', 'La <b>Fecha Emisión del Voucher</b> no puede ser <b>MAYOR</b> a la <b>Vigencia Desde del Voucher</b>');
        return 0;
    }
    // Fecha emisión del Voucher > Fecha Voucher Hasta
    if (fecha_emision > vigencia_hasta) {
        $.Notification.notify('error', 'top right', 'Error con fecha', 'La <b>Fecha Emisión del Voucher</b> no puede ser <b>MAYOR</b> a la <b>Vigencia Hasta del Voucher</b>');
        return 0;
    }
    // Fecha emisión del Voucher > Inicio de Síntomas
    if (fecha_emision > fecha_inicioSintomas) {
        $.Notification.notify('error', 'top right', 'Error con fecha', 'La <b>Fecha Emisión del Voucher</b> no puede ser <b>MAYOR</b> a la <b>Fecha de Inicio Sintomas</b>');
        return 0;
    }
    // Fecha emisión del Voucher > Fecha de la pérdida del equipaje
    if (fecha_emision > fecha_perdidaEquipaje) {
        $.Notification.notify('error', 'top right', 'Error con fecha', 'La <b>Fecha Emisión del Voucher</b> no puede ser <b>MAYOR</b> a la <b>Fecha Pérdida del Equipaje</b>');
        return 0;
    }
    // Fecha emisión del Voucher > Fecha de recuperación del equipaje
    if (fecha_emision > fecha_recuperacionEquipaje) {
        $.Notification.notify('error', 'top right', 'Error con fecha', 'La <b>Fecha Emisión del Voucher</b> no puede ser <b>MAYOR</b> a la <b>Fecha Recuperación del Equipaje</b>');
        return 0;
    }

    /* Validaciones: FECHA VOUCHER DESDE */
    // Fecha Voucher Desde > Fecha de Salida
    if (vigencia_desde > fecha_salida) {
        $.Notification.notify('error', 'top right', 'Error con fecha', 'La <b>Vigencia Desde del Voucher</b> no puede ser <b>MAYOR</b> a la <b>Fecha de Salida</b>');
        //return 0;
    }
    // Fecha Voucher Desde > Fecha Voucher Hasta
    if (vigencia_desde > vigencia_hasta) {
        $.Notification.notify('error', 'top right', 'Error con fecha', 'La <b>Vigencia Desde del Voucher</b> no puede ser <b>MAYOR</b> a la <b>Vigencia Hasta del Voucher</b>');
        return 0;
    }
    // Fecha Voucher Desde > Inicio de Síntomas
    if (vigencia_desde > fecha_inicioSintomas) {
        $.Notification.notify('error', 'top right', 'Error con fecha', 'La <b>Vigencia Desde del Voucher</b> no puede ser <b>MAYOR</b> a la <b>Fecha de Inicio Sintomas</b>');
        //return 0;
    }
    // Fecha Voucher Desde > Fecha de la pérdida del equipaje
    if (vigencia_desde > fecha_perdidaEquipaje) {
        $.Notification.notify('error', 'top right', 'Error con fecha', 'La <b>Vigencia Desde del Voucher</b> no puede ser <b>MAYOR</b> a la <b>Fecha Pérdida del Equipaje</b>');
        //return 0;
    }
    // Fecha Voucher Desde > Fecha de recuperación del equipaje
    if (vigencia_desde > fecha_recuperacionEquipaje) {
        $.Notification.notify('error', 'top right', 'Error con fecha', 'La <b>Vigencia Desde del Voucher</b> no puede ser <b>MAYOR</b> a la <b>Fecha Recuperación del Equipaje</b>');
        //return 0;
    }

    /* Validaciones: FECHA VOUCHER HASTA */
    // Fecha Voucher Hasta < Fecha de Salida
    if (vigencia_hasta < fecha_salida) {
        $.Notification.notify('error', 'top right', 'Error con fecha', 'La <b>Vigencia Hasta del Voucher</b> no puede ser <b>MENOR</b> a la <b>Fecha de Salida</b>');
        //return 0;
    }
    // Fecha Voucher Hasta < Inicio de Síntomas
    if (vigencia_hasta < fecha_inicioSintomas) {
        $.Notification.notify('error', 'top right', 'Error con fecha', 'La <b>Vigencia Hasta del Voucher</b> no debe ser <b>MENOR</b> a la <b>Fecha de Inicio Sintomas</b>');
        //return 0;
    }
    // Fecha Voucher Hasta < Fecha de la pérdida del equipaje
    if (vigencia_hasta < fecha_perdidaEquipaje) {
        $.Notification.notify('error', 'top right', 'Error con fecha', 'La <b>Vigencia Hasta del Voucher</b> no puede ser <b>MENOR</b> a la <b>Fecha Pérdida del Equipaje</b>');
        return 0;
    }

    /* Validaciones: FECHA PERDIDA DEL EQUIPAJE */
    // Fecha de la pérdida del equipaje < Fecha de Salida
    if (fecha_perdidaEquipaje < fecha_salida) {
        $.Notification.notify('error', 'top right', 'Error con fecha', 'La <b>Fecha Pérdida del Equipaje</b> no puede ser <b>MENOR</b> a la <b>Fecha de Salida</b>');
        //return 0;
    }
    // Fecha de la pérdida del equipaje > Fecha de recuperación del equipaje
    if (fecha_perdidaEquipaje > fecha_recuperacionEquipaje) {
        $.Notification.notify('error', 'top right', 'Error con fecha', 'La <b>Fecha Pérdida del Equipaje</b> no puede ser <b>MAYOR</b> a la <b>Fecha Recuperación del Equipaje</b>');
        return 0;
    }
}


// Funcion para saber desde que formulario se consulta al WS. 
// En base a esto habilita el boton para el form ALTA o MODIFICACION
// Aquí se debe borrar toda consulta anterior
function form_consulta_ws(form_caso) {

    var voucher = $('#caso_numeroVoucher_n').val();

    $('#voucher_number').val(voucher);
    $('#passenger_first_name').val("");
    $('#passenger_last_name').val("");
    $('#passenger_document_number').val("");

    $("#formulario_voucher").html("");
    $('#panel_grilla_voucher').slideUp();
    $('#panel_grilla_producto').slideUp();

    if (form_caso == 1) {
        $('#btn_WS_alta_caso').removeClass('hidden');
        $('#btn_WS_modificar_caso').addClass('hidden');

    } else if (form_caso == 2) {
        $('#btn_WS_alta_caso').addClass('hidden');
        $('#btn_WS_modificar_caso').removeClass('hidden');
    }
}


// Funcion para comprobar a que cliente corresponde el Voucher seleccionado
function valida_cliente_WS(voucherPrimerasDosLetras) {

    // Objeto con la informacion del Cliente
    var infoCliente = { "cliente_id": '', "cliente_nombre": '' };

    switch (voucherPrimerasDosLetras) {
        case 'AR': // Cliente CORIS Argentina (id. 1)
            infoCliente.cliente_id = 1;
            infoCliente.cliente_nombre = 'CORIS Argentina';
            break;
        case 'UY': // Cliente CORIS Uruguay (id. 2)
            infoCliente.cliente_id = 2;
            infoCliente.cliente_nombre = 'CORIS Uruguay';
            break;
        case 'CL': // Cliente CORIS Chile (id. 3)
            infoCliente.cliente_id = 3;
            infoCliente.cliente_nombre = 'CORIS Chile';
            break;
        case 'PY': // Cliente CORIS Paraguay (id. 4)
            infoCliente.cliente_id = 4;
            infoCliente.cliente_nombre = 'CORIS Paraguay';
        case 'PA': // Cliente CORIS Paraguay (id. 4)
            infoCliente.cliente_id = 4;
            infoCliente.cliente_nombre = 'CORIS Paraguay';
            break;
        case 'BV': // Clientes CORIS Bolivia (id. 13)
            infoCliente.cliente_id = 13;
            infoCliente.cliente_nombre = 'CORIS Bolivia';
            break;
        case 'CO': // Clientes CORIS Colombia (id. 14)
            infoCliente.cliente_id = 14;
            infoCliente.cliente_nombre = 'CORIS Colombia';
            break;
        case 'PE': // Clientes CORIS Perú (id. 15)
            infoCliente.cliente_id = 15;
            infoCliente.cliente_nombre = 'CORIS Perú';
            break;
        case 'EC': // Clientes CORIS Ecuador (id. 16)
            infoCliente.cliente_id = 16;
            infoCliente.cliente_nombre = 'CORIS Ecuador';
            break;
        default:
            infoCliente.cliente_id = 0;
    }

    return infoCliente;
}


// FORMULARIO ALTA - Función para completar los datos obtenidos por consulta a WebService
function datos_ws_alta_caso(nvoucher, sistema_emision) {

    // Cierra el modal
    $('#modal-ws').modal('hide');

    // Para borrar los select
    $("#caso_cliente_id_n").empty();
    $("#caso_producto_id_n").empty();

    // Carga de los datos 
    var parametros = {
        "voucher_numero": nvoucher,
        "sistema_emision": sistema_emision,
        "opcion": 'caso_completar_voucher'
    };

    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: 'ws_cb.php',
        data: parametros,
        beforeSend: function() {
            $.Notification.autoHideNotify('warning', 'top center', 'Cargando los datos del voucher...', 'Aguarde un instante por favor.');
        },
        success: function(data) {

            // Define el CLIENTE en base a las primeras dos letras del voucher_number
            voucherIniciales = data.vouchers[1].voucher_number.substr(0, 2);

            if (voucherIniciales == 'A1') {
                voucherIniciales = data.vouchers[1].voucher_number.substr(2, 2);
            }

            //
            let infoCliente = valida_cliente_WS(voucherIniciales);

            // Si el voucher seleccionado no es valido (cliente_id = 0) arroja error y no carga la info al caso
            if (infoCliente.cliente_id == 0) {

                $.Notification.autoHideNotify('error', 'top center', 'El Voucher: ' + data.vouchers[1].voucher_number + ' es inválido.');

            } else {

                $.Notification.autoHideNotify('success', 'top center', 'Voucher cargado exitosamente', data.vouchers[1].voucher_number);

                /* 
                |   Completa los campos del formulario alta caso
                */

                // Numero de Voucher
                $('#caso_numeroVoucher_n').val(data.vouchers[1].voucher_number);
                // Cliente
                let selectCliente = $("#caso_cliente_id_n");
                selectCliente.append('<option selected value="' + infoCliente.cliente_id + '">' + infoCliente.cliente_nombre + '</option>');
                // Beneficiario Nombre
                $('#caso_beneficiarioNombre_n').val(data.vouchers[1].passenger_last_name + ', ' + data.vouchers[1].passenger_first_name + ' ' + data.vouchers[1].passenger_second_name);
                // Fecha de Nacimiento
                passenger_birth_date = data.vouchers[1].passenger_birth_date;
                passenger_birth_date = passenger_birth_date.replace("/", "-");
                passenger_birth_date = passenger_birth_date.replace("/", "-");
                /* toFix1-API - Comprueba si la consulta viene de Assist1 y asi formatear la fecha */
                    passenger_birth_date = (passenger_birth_date.indexOf("-") == 4) ? passenger_birth_date = moment(passenger_birth_date).format('DD-MM-YYYY'):passenger_birth_date;
                $('#caso_beneficiarioNacimiento_n').val(passenger_birth_date);
                // Cálculo de Edad
                $('#caso_beneficiarioEdad_n').val(calcularEdad(passenger_birth_date));
                // Documento
                $('#caso_beneficiarioDocumento_n').val(data.vouchers[1].passenger_document_number);
                // Producto
                let selectProducto = $("#caso_producto_id_n");
                selectProducto.append('<option selected value="' + data[1].replace(/'/g, "") + '">' + data.vouchers[1].product_name.replace(/'/g, "") + '</option>'); //Acá va data[1] porque contiene el id_interno de product
                // Genero
                if (data.vouchers[1].passenger_gender == 'f' || data.vouchers[1].passenger_gender == 2) {
                    passenger_gender = 'Femenino';
                } else if (data.vouchers[1].passenger_gender == 'm' || data.vouchers[1].passenger_gender == 1) {
                    passenger_gender = 'Masculino';
                } else {
                    passenger_gender = null;
                }
                let selectbeneficiarioGenero = $("#caso_beneficiarioGenero_id_n");
                selectbeneficiarioGenero.append('<option selected value="' + data.vouchers[1].passenger_gender + '">' + passenger_gender + '</option>');
                // Deducible
                $('#caso_deducible_n').val(data[0]);
                // Fecha Emisión
                voucher_date = data.vouchers[1].voucher_date;
                voucher_date = voucher_date.replace("/", "-");
                voucher_date = voucher_date.replace("/", "-");
                /* toFix1-API - Comprueba si la consulta viene de Assist1 y asi formatear la fecha */
                voucher_date = (voucher_date.indexOf("-") == 4) ? voucher_date = moment(voucher_date).format('DD-MM-YYYY'):voucher_date;
                $('#caso_fechaEmisionVoucher_n').val(voucher_date);
                // Vigencia Desde
                vouchers_date_from = data.vouchers[1].voucher_date_from;
                vouchers_date_from = vouchers_date_from.replace("/", "-");
                vouchers_date_from = vouchers_date_from.replace("/", "-");
                /* toFix1-API - Comprueba si la consulta viene de Assist1 y asi formatear la fecha */
                vouchers_date_from = (vouchers_date_from.indexOf("-") == 4) ? vouchers_date_from = moment(vouchers_date_from).format('DD-MM-YYYY'):vouchers_date_from;
                $('#caso_vigenciaVoucherDesde_n').val(vouchers_date_from);
                // Vigencia Hasta
                voucher_date_to = data.vouchers[1].voucher_date_to;
                voucher_date_to = voucher_date_to.replace("/", "-");
                voucher_date_to = voucher_date_to.replace("/", "-");
                /* toFix1-API - Comprueba si la consulta viene de Assist1 y asi formatear la fecha */
                voucher_date_to = (voucher_date_to.indexOf("-") == 4) ? voucher_date_to = moment(voucher_date_to).format('DD-MM-YYYY'):voucher_date_to;
                $('#caso_vigenciaVoucherHasta_n').val(voucher_date_to);
                // Agencia
                $('#caso_agencia_n').val(data.vouchers[1].agency_name);
                // Quien Emitió el Voucher
                $('#caso_quienEmitioVoucher_n').val(data.vouchers[1].issuing_user_name);

                // Identifica que la info del caso que se esta por cargar viene de un WS
                $('#caso_info_ws_n').val(1);

                // Inhabilita los campos que viene del WS
                $('#caso_numeroVoucher_n').prop("readonly", true);
                $('#caso_cliente_id_n').prop("disabled", true);
                $('#caso_beneficiarioNombre_n').prop("readonly", true);
                $('#caso_beneficiarioNacimiento_n').prop("disabled", true);
                $('#caso_beneficiarioDocumento_n').prop("readonly", true);
                $('#caso_producto_id_n').prop("disabled", true);
                $('#caso_deducible_n').prop("readonly", true);
                $('#caso_agencia_n').prop("readonly", true);
                $('#caso_quienEmitioVoucher_n').prop("readonly", true);
                $('#caso_fechaEmisionVoucher_n').prop("disabled", true);
                $('#caso_vigenciaVoucherDesde_n').prop("disabled", true);
                $('#caso_vigenciaVoucherHasta_n').prop("disabled", true);
            }

            // Llama a la funcion para buscar vouchers repetidos cuando se trae informacion del WS
            buscar_vouchers_repetidos(nvoucher, 'alta');
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $.Notification.autoHideNotify('error', 'top center', 'Error..', xhr.responseText);
        }
    });
}

// FORMULARIO MODIFICACION - Función para completar los datos obtenidos por consulta a WebService





function datos_ws_modificar_caso(nvoucher, sistema_emision) {

    // Cierra el modal
    $('#modal-ws').modal('hide');

    // Para borrar los select
    $("#caso_cliente_id").empty();
    $("#caso_producto_id").empty();

    // Carga de los datos 
    var parametros = {
        "voucher_numero": nvoucher,
        "sistema_emision": sistema_emision,
        "opcion": 'caso_completar_voucher'
    };

    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: 'ws_cb.php',
        data: parametros,
        beforeSend: function() {
            $.Notification.autoHideNotify('warning', 'top center', 'Cargando los datos del voucher...', 'Aguarde un instante por favor.');
        },
        success: function(data) {

            // Define el CLIENTE en base a las primeras dos letras del voucher_number
            voucherIniciales = data.vouchers[1].voucher_number.substr(0, 2);

            if (voucherIniciales == 'A1') {
                voucherIniciales = data.vouchers[1].voucher_number.substr(2, 2);
            }

            let infoCliente = valida_cliente_WS(voucherIniciales);

            // Si el voucher seleccionado no es valido (cliente_id = 0) arroja error y no carga la info al caso
            if (infoCliente.cliente_id == 0) {
                $.Notification.autoHideNotify('error', 'top center', 'El Voucher: ' + data.vouchers[1].voucher_number + ' es inválido.');
            } else {
                $.Notification.autoHideNotify('success', 'top center', 'Voucher cargado exitosamente', data.vouchers[1].voucher_number);

                // Completa los campos del formulario alta caso
                $('#caso_numeroVoucher').val(data.vouchers[1].voucher_number);
                let selectCliente = $("#caso_cliente_id");
                selectCliente.append('<option selected value="' + infoCliente.cliente_id + '">' + infoCliente.cliente_nombre + '</option>');
                $('#caso_beneficiarioNombre').val(data.vouchers[1].passenger_last_name + ', ' + data.vouchers[1].passenger_first_name + ' ' + data.vouchers[1].passenger_second_name);
                passenger_birth_date = data.vouchers[1].passenger_birth_date;
                passenger_birth_date = passenger_birth_date.replace("/", "-");
                passenger_birth_date = passenger_birth_date.replace("/", "-");
                $('#caso_beneficiarioNacimiento').val(passenger_birth_date);
                $('#caso_beneficiarioDocumento').val(data.vouchers[1].passenger_document_number);
                let selectProducto = $("#caso_producto_id");
                selectProducto.append('<option selected value="' + data[1].replace(/'/g, "") + '">' + data.vouchers[1].product_name.replace(/'/g, "") + '</option>');
                $('#caso_deducible').val(data[0]);
                voucher_date = data.vouchers[1].voucher_date;
                voucher_date = voucher_date.replace("/", "-");
                voucher_date = voucher_date.replace("/", "-");
                $('#caso_fechaEmisionVoucher').val(voucher_date);
                voucher_date_from = data.vouchers[1].voucher_date_from;
                voucher_date_from = voucher_date_from.replace("/", "-");
                voucher_date_from = voucher_date_from.replace("/", "-");
                $('#caso_vigenciaVoucherDesde').val(voucher_date_from);
                voucher_date_to = data.vouchers[1].voucher_date_to;
                voucher_date_to = voucher_date_to.replace("/", "-");
                voucher_date_to = voucher_date_to.replace("/", "-");
                $('#caso_vigenciaVoucherHasta').val(voucher_date_to);

                $('#caso_agencia').val(data.vouchers[1].agency_name);

                $('#caso_quienEmitioVoucher').val(data.vouchers[1].issuing_user_name);

                // Identifica que la info del caso que se esta por cargar viene de un WS
                $('#caso_info_ws').val(1);

                // Llama a la funcion calcularEdad para completar la edad en caso_beneficiarioEdad_n
                $('#caso_beneficiarioEdad').val(calcularEdad(data.vouchers[1].passenger_birth_date.replace(/'/g, "")));

                // Inhabilita los campos que vienen del WS
                $('#caso_numeroVoucher').prop("readonly", true);
                $('#caso_cliente_id').prop("disabled", true);
                $('#caso_beneficiarioNombre').prop("readonly", true);
                $('#caso_beneficiarioNacimiento').prop("disabled", true);
                $('#caso_beneficiarioDocumento').prop("readonly", true);
                $('#caso_producto_id').prop("disabled", true);
                $('#caso_deducible').prop("readonly", true);
                $('#caso_agencia').prop("readonly", true);
                $('#caso_quienEmitioVoucher').prop("readonly", true);
                $('#caso_fechaEmisionVoucher').prop("disabled", true);
                $('#caso_vigenciaVoucherDesde').prop("disabled", true);
                $('#caso_vigenciaVoucherHasta').prop("disabled", true);
            }

            // Llama a la funcion para buscar vouchers repetidos cuando se trae informacion del WS
            buscar_vouchers_repetidos(nvoucher, 'modificacion');
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $.Notification.autoHideNotify('error', 'top center', 'Error...', xhr.responseText);
        }
    });
}


// Funciones para completar los SELECT (En formularios ALTA)

// Clientes para el Formulario de Alta de Casos
function formulario_alta_clientes() {

    var parametros = {
        "opcion": 'formulario_alta_clientes'
    };

    var miselect = $("#caso_cliente_id_n");

    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: 'caso_cb.php',
        data: parametros,
        success: function(data) {

            miselect.append('<option value="">Seleccione un Cliente</option>');

            for (var i = 0; i < data.length; i++) {
                miselect.append('<option value="' + data[i].cliente_id + '">' + data[i].cliente_nombre + '</option>');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= ' + xhr.responseText + '</div>');
        }
    });
}

// Tipos de Asistencia para el Formulario de Alta de Casos
function formulario_alta_tiposAsistencias() {

    var parametros = {
        "opcion": 'formulario_alta_tiposAsistencias'
    };

    var miselect = $("#caso_tipoAsistencia_id_n");

    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: 'caso_cb.php',
        data: parametros,
        success: function(data) {

            miselect.append('<option value="">Seleccione un Tipo de Asistencia</option>');

            for (var i = 0; i < data.length; i++) {
                miselect.append('<option value="' + data[i].tipoAsistencia_id + '">' + data[i].tipoAsistencia_nombre + '</option>');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= ' + xhr.responseText + '</div>');
        }
    });
}

// Fees para el Formulario de Alta de Casos
function formulario_alta_fees() {

    var parametros = {
        "opcion": 'formulario_alta_fees'
    };

    var miselect = $("#caso_fee_id_n");

    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: 'caso_cb.php',
        data: parametros,
        success: function(data) {

            miselect.append('<option value="">Seleccione un FEE</option>');

            for (var i = 0; i < data.length; i++) {
                miselect.append('<option value="' + data[i].fee_id + '">' + data[i].fee_nombre + '</option>');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= ' + xhr.responseText + '</div>');
        }
    });
}

// Paises para el Formulario de Alta de Casos
function formulario_alta_paises() {

    var parametros = {
        "opcion": 'formulario_alta_paises'
    };

    var miselect = $("#caso_pais_id_n");

    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: 'caso_cb.php',
        data: parametros,
        success: function(data) {

            miselect.append('<option value="">Seleccione un Pais</option>');

            for (var i = 0; i < data.length; i++) {
                miselect.append('<option value="' + data[i].pais_id + '">' + data[i].pais_nombreEspanol + '</option>');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= ' + xhr.responseText + '</div>');
        }
    });
};

// Select Dependiente de Paises > Ciudades para el Formulario de Alta de Casos

// Esto sirve para que cuando se modifica el select de país se borre lo que está en el input de ciudad
$("#caso_pais_id_n").change(function() {
    $("#caso_ciudad_id_n").val("");
});

// Autocomplete de ciudades en alta de casos
$('#caso_ciudad_id_n').autocomplete({
    source: function(request, response) {
        $.ajax({
            method: "post",
            url: 'caso_cb.php',
            dataType: "json",
            data: {
                ciudad: request.term,
                opcion: 'select_ciudades',
                pais_id: $("#caso_pais_id_n option:selected").val()
            },
            success: function(data) {
                response($.map(data, function(item) {
                    var code = item.split("|");
                    return {
                        label: code[0],
                        value: code[0],
                        data: item
                    }
                }));
            }
        });
    },
    autoFocus: true,
    minLength: 3,
    select: function(event, ui) {
        var names = ui.item.data.split("|");
        $('#caso_ciudad_id_n_2').val(names[1]);

    }
});

// Autocomplete de diagnosticos en alta de casos
$('#caso_diagnostico_id_n').autocomplete({
    source: function(request, response) {
        $.ajax({
            method: "post",
            url: 'caso_cb.php',
            dataType: "json",
            data: {
                diagnostico: request.term,
                opcion: 'select_diagnosticos'
            },
            success: function(data) {
                response($.map(data, function(item) {
                    var code = item.split("|");
                    return {
                        label: code[0],
                        value: code[0],
                        data: item
                    }
                }));
            }
        });
    },
    autoFocus: true,
    minLength: 3,
    select: function(event, ui) {
        var names = ui.item.data.split("|");
        $('#caso_diagnostico_id_n_2').val(names[1]);
    }
});


// Funcion para completar el select de producto dependiendo del cliente elegido
$("#caso_cliente_id_n").change(function() {

    cliente_id = $("#caso_cliente_id_n option:selected").val();

    var parametros = {
        "cliente_id": cliente_id,
        "opcion": 'productosListar_altaCasos'
    };

    var miselect = $("#caso_producto_id_n");

    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: 'caso_cb.php',
        data: parametros,
        success: function(data) {

            // ARGENTODO - cambiar el remove de id
            // Borra la seleccion anterior
            $('#caso_producto_id_n').find('option').remove().end().append(data);

            // Arma el select
            miselect.append('<option value="">Seleccione un Producto</option>');

            for (var i = 0; i < data.length; i++) {
                miselect.append('<option value="' + data[i].product_id_interno + '">' + data[i].product_name + '</option>');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= ' + xhr.responseText + '</div>');
        }
    });
});


// Lista de Telefonos para el Formulario de Alta de Casos
function listarTipoTelefonos_caso() {

    var parametros = {
        "opcion": 'listarTipoTelefonos_caso'
    };


    var miselect = $("#caso_telefonoTipo_id_n");
    var miselect2 = $("#caso_telefonoTipo_id");
    var miselect3 = $("#caso_telefonoTipo_id_n_2");

    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: 'caso_cb.php',
        data: parametros,
        success: function(data) {

            miselect.append('<option value="">Seleccione</option>');
            miselect2.append('<option value="">Seleccione</option>');
            miselect3.append('<option value="">Seleccione</option>');

            for (var i = 0; i < data.length; i++) {
                miselect.append('<option value="' + data[i].tipoTelefono_id + '">' + data[i].tipoTelefono_nombre + '</option>');
                miselect2.append('<option value="' + data[i].tipoTelefono_id + '">' + data[i].tipoTelefono_nombre + '</option>');
                miselect3.append('<option value="' + data[i].tipoTelefono_id + '">' + data[i].tipoTelefono_nombre + '</option>');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= ' + xhr.responseText + '</div>');
        }
    });
}


// *****************************************************************************************************************************************************************************************
// FORMULARIO MODIFICAR y PROCESAMIENTO
// *****************************************************************************************************************************************************************************************
// Validaciones y Form
$("#formulario_modificacion").validate({
    ignore: [],
    rules: {
        caso_numeroVoucher: {
            required: true
        },
        caso_beneficiarioNombre: {
            required: true,
            minlength: 5
        },
        caso_fechaSiniestro: {
            required: true,
            dateARG: true
        },
        caso_cliente_id: {
            required: true
        },
        caso_tipoAsistencia_id: {
            required: true
        },
        caso_pais_id: {
            required: true
        },
        caso_ciudad_id: {
            required: true
        },
        // Para cuando se selecciona pais y ciudad, pero el id de ciudad no se carga en 'caso_ciudad_id_2'
        caso_ciudad_id_2: {
            required: function(e) {
                return $("#caso_ciudad_id").val() !== "";
            }
        },
        caso_beneficiarioNacimiento: {
            dateARG: true
        },
        caso_fechaSalida: {
            required: function(e) {
                return $("#caso_producto_id option:selected").text().includes('Multiviaje');
            },
            dateARG: true
        },
        caso_fechaEmisionVoucher: {
            dateARG: true
        },
        caso_vigenciaVoucherDesde: {
            dateARG: true
        },
        caso_vigenciaVoucherHasta: {
            dateARG: true
        },
        caso_fechaInicioSintomas: {
            required: function(e) {
                return $("#caso_tipoAsistencia_clasificacion_id").val() === "1";
            },
            dateARG: true
        },
        caso_sintomas: {
            required: function(e) {
                return $("#caso_tipoAsistencia_clasificacion_id").val() === "1";
            }
        },
        caso_deducible: {
            required: true
        },
        caso_producto_id: {
            required: true
        },
        caso_fechaPerdidaEquipaje: {
            dateARG: true
        },
        caso_fechaRecuperacionEquipaje: {
            dateARG: true
        }
    },
    messages: {
        caso_numeroVoucher: {
            required: "Por favor ingrese el número de voucher"
        },
        caso_beneficiarioNombre: {
            required: "Por favor ingrese el nombre del beneficiario",
            minlength: "Debe ingresar al menos 5 caracteres"
        },
        caso_fechaSiniestro: {
            required: "Por favor ingrese la fecha de siniestro"
        },
        caso_cliente_id: {
            required: "Por favor seleccione un cliente"
        },
        caso_tipoAsistencia_id: {
            required: "Por favor seleccione un tipo de asistencia"
        },
        caso_pais_id: {
            required: "Por favor seleccione un pais"
        },
        caso_ciudad_id: {
            required: "Por favor seleccione una ciudad"
        },
        caso_ciudad_id_2: {
            required: "La ciudad no se selecciono correctamente"
        },
        caso_deducible: {
            required: "Por favor ingrese un deducible"
        },
        caso_producto_id: {
            required: "Por favor seleccione un producto"
        },
        caso_fechaSalida: {
            required: "Por favor ingrese la fecha de salida"
        },
        caso_fechaInicioSintomas: {
            required: "Por favor ingrese la fecha de inicio de sintomas"
        },
        caso_sintomas: {
            required: "Por favor ingrese un sintoma"
        }
    },
    submitHandler: function(form) {

        // no_medical_cost
        if ($('#no_medical_cost').prop("checked")) {
            $('#no_medical_cost').val('1');
        } else {
            $('#no_medical_cost').val('0');
        }

        var parametros = {
            "caso_id": $('#caso_id').val(),
            "asistencia_sin_costo": $('#no_medical_cost').val(),
            "opcion": 'valida_servicios_costo_asistencia'
        };

        $.ajax({
            type: 'POST',
            dataType: 'JSON',
            url: 'caso_cb.php',
            data: parametros,
            success: function(data) {
                
                if(data && $('#no_medical_cost').val() == 1){
                    // if($('#no_medical_cost').val() == 1){
                    $.Notification.autoHideNotify('error', 'top center', 'Error...', '"Asistencia sin costo" no puede marcarse porque existen servicios con presunto origen mayor a 0');
                    // }else{
                    //     $.Notification.autoHideNotify('error', 'top center', 'Error...', '"Asistencia sin costo" no puede desmarcarse porque existen servicios con presunto origen 0');
                    // }
                    return false;
                }else{
                     // Habilita nuevamente los campos con .prop("disabled") para que se guarden correctamente en la tabla
                    $('#caso_cliente_id').prop("disabled", false);
                    $('#caso_beneficiarioNacimiento').prop("disabled", false);
                    $('#caso_producto_id').prop("disabled", false);
                    $('#caso_fechaEmisionVoucher').prop("disabled", false);
                    $('#caso_vigenciaVoucherDesde').prop("disabled", false);
                    $('#caso_vigenciaVoucherHasta').prop("disabled", false);

                    // Checkbox
                    // Pax VIP
                    if ($('#caso_paxVIP').prop("checked")) {
                        $('#caso_paxVIP').val('1');
                    } else {
                        $('#caso_paxVIP').val('0');
                    }
                    // Caso Legal
                    if ($('#caso_legal').prop("checked")) {
                        $('#caso_legal').val('1');
                    } else {
                        $('#caso_legal').val('0');
                    }

                    // Llama a la funcion para validar las fechas ingresadas en el formulario_modificacion de Casos
                    let resultado = valida_fechas('form_modificacion');
                    if (resultado == 0) {
                        return false;
                    }

                    $.ajax({
                        type: "POST",
                        url: "caso_cb.php",
                        data: $(form).serialize(),
                        success: function(data) {

                            $.Notification.autoHideNotify('success', 'top center', 'El Caso se modificó exitosamente...', 'Los cambios han sido guardados.');

                            // Desbloquea el boton nuevo caso
                            $('#btn_nuevo_caso').prop("disabled", false);
                            $('#btn_caso_manual').prop("disabled", false);

                            // Muestra la grilla
                            grilla_listar('', '');

                            // Limpia el formulario modificacion
                            $("#formulario_modificacion").clearValidation();
                            $('#formulario_modificacion')[0].reset();

                            // Acomoda paneles
                            $('#panel_formulario_modificacion').slideUp();

                            // Llamar a formulario_lectura para mostrar el caso recien modificado
                            caso_id = $('#caso_id').val();
                            formulario_lectura(caso_id);
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            $.Notification.autoHideNotify('error', 'top center', 'Error...', 'Reporte del error. ' + xhr.responseText);
                        }
                    });

                    // Previene que el formulario haga POST después de la validación, ya que ejecuto el guardado por Ajax.
                    return false;
                }
            }
        });
    }
});


// Funcion para limitar los datepicker del voucher con las fechas ingresadas hasta el momento
function modificarVoucher_modificacionCasos(emision_voucher, vigencia_desde, vigencia_hasta) {


    if (emision_voucher !== null) {
        var values_emision = emision_voucher.split("-");
        var dia_emision = values_emision[0];
        var mes_emision = values_emision[1] - 1;
        var ano_emision = values_emision[2];
    }

    if (vigencia_desde !== null) {
        var values_desde = vigencia_desde.split("-");
        var dia_desde = values_desde[0];
        var mes_desde = values_desde[1] - 1;
        var ano_desde = values_desde[2];
    }

    if (vigencia_hasta !== null) {
        var values_hasta = vigencia_hasta.split("-");
        var dia_hasta = values_hasta[0];
        var mes_hasta = values_hasta[1] - 1;
        var ano_hasta = values_hasta[2];
    }

    var dateFormat = "dd-mm-yy";

    emision = $('#caso_fechaEmisionVoucher').datepicker({
        minDate: "",
        maxDate: new Date(ano_desde, mes_desde, dia_desde),
        numberOfMonths: 3,
        dateFormat: 'dd-mm-yy'
    })
    .on("change", function() {
        from.datepicker("option", "minDate", getDate(this));
        }),
        $('#caso_vigenciaVoucherDesde').datepicker({
            minDate: new Date(ano_emision, mes_emision, dia_emision),
            maxDate: new Date(ano_hasta, mes_hasta, dia_hasta),
            numberOfMonths: 3,
            dateFormat: 'dd-mm-yy'
        })
        .on("change", function() {
            emision.datepicker("option", "maxDate", getDate(this));
        }),

        from = $('#caso_vigenciaVoucherDesde').datepicker({
            minDate: new Date(ano_emision, mes_emision, dia_emision),
            maxDate: new Date(ano_hasta, mes_hasta, dia_hasta),
            numberOfMonths: 3,
            dateFormat: 'dd-mm-yy'
        })
        .on("change", function() {
            to.datepicker("option", "minDate", getDate(this));
        }),

        to = $('#caso_vigenciaVoucherHasta').datepicker({
            minDate: new Date(ano_desde, mes_desde, dia_desde),
            maxDate: "",
            numberOfMonths: 3,
            dateFormat: 'dd-mm-yy'
        })
        .on("change", function() {
            from.datepicker("option", "maxDate", getDate(this));
        });


    function getDate(element) {
        var date;
        try {
            date = $.datepicker.parseDate(dateFormat, element.value);
        } catch (error) {
            date = null;
        }

        return date;
    }
}


// Funcion para limitar los datepicker de equipaje con las fechas ingresadas hasta el momento
function modificarEquipaje_modificacionCasos(perdidaEquipaje, recuperacionEquipaje) {

    if (perdidaEquipaje !== null) {
        var values_perdida = perdidaEquipaje.split("-");
        var dia_perdida = values_perdida[0];
        var mes_perdida = values_perdida[1] - 1;
        var ano_perdida = values_perdida[2];
    } else {
        var fechaHoy = new Date();
        var dia_perdida = "";
        var mes_perdida = fechaHoy.getMonth();
        var ano_perdida = fechaHoy.getFullYear();
    }

    if (recuperacionEquipaje !== null) {
        var values_recuperacion = recuperacionEquipaje.split("-");
        var dia_recuperacion = values_recuperacion[0];
        var mes_recuperacion = values_recuperacion[1] - 1;
        var ano_recuperacion = values_recuperacion[2];
    }

    var dateFormat = "dd-mm-yy";
    from_equip = $('#caso_fechaPerdidaEquipaje').datepicker({
        minDate: "",
        maxDate: new Date(ano_recuperacion, mes_recuperacion, dia_recuperacion),
        numberOfMonths: 2,
        yearRange: '-1:+0',
        dateFormat: 'dd-mm-yy'
    })
    .on("change", function() {
        to_equip.datepicker("option", "minDate", getDate(this));
        }),

        to_equip = $('#caso_fechaRecuperacionEquipaje').datepicker({
            minDate: new Date(ano_perdida, mes_perdida, dia_perdida),
            maxDate: new Date(),
            numberOfMonths: 2,
            dateFormat: 'dd-mm-yy'
        })
        .on("change", function() {
            from_equip.datepicker("option", "maxDate", getDate(this));
        });


    function getDate(element) {
        var date;
        try {
            date = $.datepicker.parseDate(dateFormat, element.value);
        } catch (error) {
            date = null;
        }

        return date;
    }
}


// Funciones auxiliares para Modificar Caso
// Funciones para completar los INPUT que traen ID (En formularios MODIFICACION)


// Funciones para completar los SELECT (En formularios MODIFICACION)

// Lista Clientes para el Formulario de Modificacion
function clientesListar_modificacionCasos(caso_id) {

    var parametros = {
        "opcion": 'clientesListar_modificacionCasos',
        "caso_id": caso_id
    };

    var miselect = $("#caso_cliente_id");
    miselect.empty();

    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: 'caso_cb.php',
        data: parametros,
        success: function(data) {
            for (var i = 0; i < data.length; i++) {
                miselect.append('<option value="' + data[i].cliente_id + '">' + data[i].cliente_nombre + '</option>');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= ' + xhr.responseText + '</div>');
        }
    });
}

// Lista los Tipos de Asistencia para el Formulario de Modificacion
function tiposAsistenciaListar_modificacionCasos(caso_id) {

    var parametros = {
        "opcion": 'tiposAsistenciaListar_modificacionCasos',
        "caso_id": caso_id
    };

    var miselect = $("#caso_tipoAsistencia_id");
    miselect.empty();

    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: 'caso_cb.php',
        data: parametros,
        success: function(data) {
            for (var i = 0; i < data.length; i++) {
                miselect.append('<option value="' + data[i].tipoAsistencia_id + '">' + data[i].tipoAsistencia_nombre + '</option>');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= ' + xhr.responseText + '</div>');
        }
    });
}

// Lista los Fees para el Formulario de Modificacion
function feesListar_modificacionCasos(caso_id) {

    var parametros = {
        "opcion": 'feesListar_modificacionCasos',
        "caso_id": caso_id
    };

    var miselect = $("#caso_fee_id");
    miselect.empty();

    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: 'caso_cb.php',
        data: parametros,
        success: function(data) {

            for (var i = 0; i < data.length; i++) {
                miselect.append('<option value="' + data[i].fee_id + '">' + data[i].fee_nombre + '</option>');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= ' + xhr.responseText + '</div>');
        }
    });
}

// Paises para el Formulario de Modificacion de un Caso
function paisesListar_modificacionCasos(caso_id) {

    var parametros = {
        "opcion": 'paisesListar_modificacionCasos',
        "caso_id": caso_id
    };

    var miselect = $("#caso_pais_id");
    miselect.empty();

    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: 'caso_cb.php',
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

// Paises para el Formulario de Clonar un Caso
function paisesListar_clonarCasos(caso_id) {

    var parametros = {
        "opcion": 'paisesListar_modificacionCasos',
        "caso_id": caso_id
    };

    var miselect = $("#caso_pais_id_n");
    miselect.empty();

    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: 'caso_cb.php',
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

// Select Dependiente de Paises > Ciudades para el Formulario de Modificacion de Casos

// Esto sirve para que cuando se modifica el select de país se borre lo que está en el input de ciudad
$("#caso_pais_id").change(function() {
    $("#caso_ciudad_id").val("");
});

$('#caso_ciudad_id').autocomplete({
    source: function(request, response) {
        $.ajax({
            method: "post",
            url: 'caso_cb.php',
            dataType: "json",
            data: {
                ciudad: request.term,
                opcion: 'select_ciudades',
                pais_id: $("#caso_pais_id option:selected").val()
            },
            success: function(data) {
                response($.map(data, function(item) {
                    var code = item.split("|");
                    return {
                        label: code[0],
                        value: code[0],
                        data: item
                    }
                }));
            }
        });
    },
    autoFocus: true,
    minLength: 3,
    select: function(event, ui) {
        var names = ui.item.data.split("|");
        $('#caso_ciudad_id_2').val(names[1]);
    }
});

// Autocomplete de diagnosticos en modificacion del casos
$('#caso_diagnostico_id').autocomplete({
    source: function(request, response) {
        $.ajax({
            method: "post",
            url: 'caso_cb.php',
            dataType: "json",
            data: {
                diagnostico: request.term,
                opcion: 'select_diagnosticos'
            },
            success: function(data) {
                response($.map(data, function(item) {
                    var code = item.split("|");
                    return {
                        label: code[0],
                        value: code[0],
                        data: item
                    }
                }));
            }
        });
    },
    autoFocus: true,
    minLength: 3,
    select: function(event, ui) {
        var names = ui.item.data.split("|");
        $('#caso_diagnostico_id_2').val(names[1]);
    }
});


// Al modificarse el cliente del caso en modificar, actualiza el select de productos
$("#caso_cliente_id").change(function() {

    let cliente_id = $("#caso_cliente_id option:selected").val();

    var parametros = {
        "cliente_id": cliente_id,
        "opcion": 'productosListar_altaCasos'
    };

    var miselect = $("#caso_producto_id");

    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: 'caso_cb.php',
        data: parametros,
        success: function(data) {

            // ARGENTODO - cambiar el remove de id
            // Borra la seleccion anterior
            $('#caso_producto_id').find('option').remove().end().append(data);

            // Arma el select
            miselect.append('<option value="">Seleccione un Producto</option>');

            for (var i = 0; i < data.length; i++) {
                miselect.append('<option value="' + data[i].product_id_interno + '">' + data[i].product_name + '</option>');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= ' + xhr.responseText + '</div>');
        }
    });
});


// Lista Productos para el Formulario de Modificacion. Trae el producto elegido, depende del cliente asignado al caso
function productosListar_modificacionCasos(caso_id) {

    let cliente_id = $("#caso_cliente_id_input").val();

    let parametros = {
        "opcion": 'productosListar_modificacionCasos',
        "caso_id": caso_id,
        "cliente_id": cliente_id
    };

    let miselect = $("#caso_producto_id");
    miselect.empty();

    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: 'caso_cb.php',
        data: parametros,
        success: function(data) {

            //miselect.append('<option value="">Seleccione un Producto</option>');

            for (let i = 0; i < data.length; i++) {
                miselect.append('<option value="' + data[i].product_id_interno + '">' + data[i].product_name + '</option>');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= ' + xhr.responseText + '</div>');
        }
    });
};

// Lista de Telefonos para el Formulario de Modificacion de Casos
function listarTipoTelefonos_modificacion(telefono_id_e) {

    var parametros = {
        "opcion": 'listarTipoTelefonos_modificacion',
        "telefono_id_e": telefono_id_e
    };

    var miselect = $("#caso_telefonoTipo_id");
    miselect.empty();

    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: 'caso_cb.php',
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


// Función para parsear fechas
function parseDate(str) {
    var mdy = str.split('/');
    return new Date(mdy[0] - 1, mdy[2], mdy[1]);
}

// Función para validar si el caso tiene Servicios o Reintegros. 
// Si tiene, bloquea el botón para la búsqueda de vouchers (btn_ws)
function valida_servicios_reintegros(caso_id) {

    var parametros = {
        "caso_id": caso_id,
        "opcion": 'valida_servicios_reintegros'
    };

    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: 'caso_cb.php',
        data: parametros,
        success: function(data) {

            if (data) {
                $('#btn_ws').prop("disabled", true);
            } else {
                $('#btn_ws').prop("disabled", false);
            }

            // Importante para que luego de la carga dinámica vuelvan a funcionar los tooltips
            $('[data-toggle="tooltip"]').tooltip();
        }
    });
}

// Carga los datos para mostrar el FORMULARIO MODIFICAR caso
$("#btn_modificar_caso, #btn_modificar_caso_footer").on('click', function() {

    let caso_id = $('#caso_id_v').val();

    var parametros = {
        "caso_id": caso_id,
        "opcion": 'formulario_lectura'
    };

    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: 'caso_cb.php',
        data: parametros,
        success: function(data) {

            // Bloquea el boton nuevo caso
            $('#btn_nuevo_caso').prop("disabled", true);
            $('#btn_caso_manual').prop("disabled", true);

            // Llama a la función valida_servicios_reintegros
            valida_servicios_reintegros(data.caso_id);

            // DATOS GENERALES
            $('#caso_id').val(data.caso_id);
            $('#caso_info_ws').val(data.caso_info_ws);

            // DATOS OPERATIVOS
            $('#caso_numero').val(data.caso_numero);
            $('#caso_casoEstado_nombre').val(data.casoEstado_nombre);
            $('#caso_fechaAperturaCaso').val(data.caso_fechaAperturaCaso);
            $('#caso_ultimaModificacion').val(data.caso_ultimaModificacion);
            var abiertoPor = data.abiertoPor_nombre + ' ' + data.abiertoPor_apellido; // Arma el string con nombre + apellido
            $('#caso_abiertoPor_nombre').val(abiertoPor);
            if (data.asignadoA_nombre != null && data.asignadoA_apellido != null) {
                var asignadoA = data.asignadoA_nombre + ' ' + data.asignadoA_apellido; // Arma el string con nombre + apellido    
                $('#caso_asignadoA_nombre').val(asignadoA);
            } else {
                $('#caso_asignadoA_nombre').val('Caso sin asignar');
            }

            // DATOS PRINCIPALES PARA CREAR EL CASO
            $('#caso_numeroVoucher').val(data.caso_numeroVoucher);
            $('#caso_cliente_id_input').val(data.caso_cliente_id);
            clientesListar_modificacionCasos(caso_id);
            $('#caso_beneficiarioNombre').val(data.caso_beneficiarioNombre);
            // Checkbox picker PaxVIP
            var caso_paxVIP = data.caso_paxVIP;
            if (caso_paxVIP == '1') {
                $('#caso_paxVIP').prop('checked', true);
            } else if (caso_paxVIP == '0') {
                $('#caso_paxVIP').prop('checked', false);
            }
            // Checkbox picker Caso Legal
            var caso_legal = data.caso_legal;
            if (caso_legal == '1') {
                $('#caso_legal').prop('checked', true);
            } else if (caso_legal == '0') {
                $('#caso_legal').prop('checked', false);
            }

            // Checkbox picker name="no_medical_cost_n"
            var no_medical_cost = data.no_medical_cost;
            if (no_medical_cost == '1') {
                $('#no_medical_cost').prop('checked', true);
            } else if (no_medical_cost == '0') {
                $('#no_medical_cost').prop('checked', false);
            }


            $('#caso_fechaSiniestro').val(data.caso_fechaSiniestro);
            tiposAsistenciaListar_modificacionCasos(caso_id);
            feesListar_modificacionCasos(caso_id);
            // DATOS TERRITORIALES
            paisesListar_modificacionCasos(caso_id);
            $('#caso_ciudad_id').val(data.ciudad_nombre);
            $('#caso_ciudad_id_2').val(data.caso_ciudad_id);
            $('#caso_direccion').val(data.caso_direccion);
            $('#caso_codigoPostal').val(data.caso_codigoPostal);
            $('#caso_hotel').val(data.caso_hotel);
            $('#caso_habitacion').val(data.caso_habitacion);
            // DATOS DE CONTACTO
            grilla_telefonos();
            listarTipoTelefonos_caso();
            $('#email_id').val(data.email_id);
            $('#email_email').val(data.email_email);
            // DATOS DEL BENEFICIARIO
            $('#caso_beneficiarioNacimiento').val(data.caso_beneficiarioNacimiento);
            $('#caso_beneficiarioEdad').val(data.caso_beneficiarioEdad);
            $('#caso_beneficiarioGenero_id').val(data.caso_beneficiarioGenero_id);
            if (data.caso_beneficiarioGenero_id == 1) {
                $('#caso_beneficiarioGenero_nombre').val("Masculino");
            } else {
                $('#caso_beneficiarioGenero_nombre').val("Femenino");
            }
            $('#caso_beneficiarioDocumento').val(data.caso_beneficiarioDocumento);
            // DATOS DEL PRODUCTO
            productosListar_modificacionCasos(caso_id);
            $('#caso_deducible').val(data.caso_deducible);
            $('#caso_agencia').val(data.caso_agencia);
            $('#caso_quienEmitioVoucher').val(data.caso_quienEmitioVoucher);
            $('#caso_fechaSalida').val(data.caso_fechaSalida);
            $('#caso_fechaEmisionVoucher').val(data.caso_fechaEmisionVoucher);
            $('#caso_vigenciaVoucherDesde').val(data.caso_vigenciaVoucherDesde);
            $('#caso_vigenciaVoucherHasta').val(data.caso_vigenciaVoucherHasta);
            // Limita los datepicker del voucher a las fechas ingresadas hasta el momento
            var emision_voucher = data.caso_fechaEmisionVoucher;
            var vigencia_desde = data.caso_vigenciaVoucherDesde;
            var vigencia_hasta = data.caso_vigenciaVoucherHasta;
            modificarVoucher_modificacionCasos(emision_voucher, vigencia_desde, vigencia_hasta);
            // DATOS POR ENFERMEDAD
            $('#caso_fechaInicioSintomas').val(data.caso_fechaInicioSintomas);
            $('#caso_sintomas').val(data.caso_sintomas);
            $('#caso_antecedentes').val(data.caso_antecedentes);
            $('#caso_diagnostico_id').val(data.diagnostico_nombre);
            $('#caso_diagnostico_id_2').val(data.caso_diagnostico_id);
            // DATOS POR DEMORA DE VUELO
            $('#caso_motivoVueloDemorado').val(data.caso_motivoVueloDemorado);
            // DATOS POR PERDIDA DE EQUIPAJE
            $('#caso_companiaAerea').val(data.caso_companiaAerea);
            $('#caso_numeroVuelo').val(data.caso_numeroVuelo);
            $('#caso_numeroPIR').val(data.caso_numeroPIR);
            $('#caso_titularPIR').val(data.caso_titularPIR);
            $('#caso_numeroEquipaje').val(data.caso_numeroEquipaje);
            // Limita los datepicker de perdida y recuperacion de equipaje a las fechas ingresadas hasta el momento
            $('#caso_fechaPerdidaEquipaje').val(data.caso_fechaPerdidaEquipaje);
            $('#caso_fechaRecuperacionEquipaje').val(data.caso_fechaRecuperacionEquipaje);
            var perdidaEquipaje = data.caso_fechaPerdidaEquipaje;
            var recuperacionEquipaje = data.caso_fechaRecuperacionEquipaje;
            modificarEquipaje_modificacionCasos(perdidaEquipaje, recuperacionEquipaje);
            // OTROS DATOS
            $('#caso_observaciones').val(data.caso_observaciones);
            $('#caso_campoSupervisor').val(data.caso_campoSupervisor);

            // Busca la clasificacion correspondiente al Tipo de Asistencia asignado
            $('#caso_tipoAsistencia_clasificacion_id').val(data.tipoAsistencia_clasificacion_id);
            var tipoAsistencia_clasificacion = data.tipoAsistencia_clasificacion_id;

            if (tipoAsistencia_clasificacion == 1) {
                $('#porEnfermedad').show();
                $('#porDemoraVuelo').hide();
                $('#porPerdidaEquipaje').hide();
            } else if (tipoAsistencia_clasificacion == 2) {
                $('#porPerdidaEquipaje').show();
                $('#porEnfermedad').hide();
                $('#porDemoraVuelo').hide();
            } else if (tipoAsistencia_clasificacion == 3) {
                $('#porDemoraVuelo').show();
                $('#porPerdidaEquipaje').hide();
                $('#porEnfermedad').hide();
            } else {
                $('#porEnfermedad').hide();
                $('#porDemoraVuelo').hide();
                $('#porPerdidaEquipaje').hide();
            }

            // Inhabilita los campos del voucher para forzar la búsqueda por WS
            $('#caso_numeroVoucher').prop("readonly", true);
            $('#caso_cliente_id').prop("disabled", true);
            $('#caso_beneficiarioNombre').prop("readonly", true);
            $('#caso_beneficiarioNacimiento').prop("disabled", true);
            $('#caso_beneficiarioDocumento').prop("readonly", true);
            $('#caso_producto_id').prop("disabled", true);
            $('#caso_deducible').prop("readonly", true);
            $('#caso_agencia').prop("readonly", true);
            $('#caso_quienEmitioVoucher').prop("readonly", true);
            $('#caso_fechaEmisionVoucher').prop("disabled", true);
            $('#caso_vigenciaVoucherDesde').prop("disabled", true);
            $('#caso_vigenciaVoucherHasta').prop("disabled", true);

            // Oculta las alertas que pudieran haber aparecido y deban cerrarse
            $('.notifyjs-wrapper').trigger('notify-hide');

            // Alertas
            alertas_caso(caso_id);

            // Muestra u oculta paneles
            $('#panel_formulario_alta').slideUp();
            $('#panel_formulario_vista').slideUp();
            $('#panel_formulario_modificacion').removeClass('hidden');
            $('#panel_formulario_modificacion').slideDown();
            $('#panel_grilla').slideUp();
            $('#grilla_info').slideUp();
            $('#grilla_caso').slideUp();
        }
    });
});


// Funcion para buscar un caso puntual
function buscarCaso() {

    let caso_numero = $('#caso_numero_v').val();

    let parametros = {
        "caso_numero": caso_numero,
        "opcion": 'buscar_caso_id'
    };

    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: 'caso_cb.php',
        data: parametros,
        success: function(data) {
            let caso_id = data.caso_id;
            formulario_lectura(caso_id);
        }
    });

    // Oculta las alertas que pudieran haber aparecido y deban cerrarse
    $('.notifyjs-wrapper').trigger('notify-hide');
}


// Funcion para desplazarse entre los casos (anterior y siguiente)
function navegaCasos(valor) {

    let caso_id = parseInt($('#caso_id_v').val());
    let resultado = parseInt(caso_id + valor);

    // Oculta las alertas que pudieran haber aparecido y deban cerrarse
    $('.notifyjs-wrapper').trigger('notify-hide');
    // Llama al formulario lectura
    formulario_lectura(resultado);
}


// Funcion para anular casos
// Modal para confirmar la anulacion del caso
$("#btn_anular_caso").on("click", function() {

    let caso_id_b = $('#caso_id_v').val();

    $('#ventana_modal_anular').modal('show');
    $('#caso_id_b').val(caso_id_b);
});


// Funcion que anula el caso
$("#formulario_anular").on("submit", function(e) {

    e.preventDefault();

    var caso_id = $('#caso_id_b').val();
    var caso_numero = $('#caso_numero_v').val();

    var parametros = {
        "caso_id": caso_id,
        "opcion": 'anular_casos'
    };

    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: 'caso_cb.php',
        data: parametros,
        success: function(data) {
            $('#ventana_modal_anular').modal('hide');
            $.Notification.autoHideNotify('success', 'top center', 'Caso anulado exitosamente...', 'Se ha anulado el Caso: ' + caso_id);
            formulario_lectura(caso_id);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $('#ventana_modal_anular').modal('hide');
            $.Notification.autoHideNotify('warning', 'top center', 'El  caso no se pudo anular...', 'Error: ' + xhr.responseText);
            formulario_lectura(caso_id);
        }
    });

});

// Funcion para rehabilitar casos
// Modal para confirmar la anulacion del caso
$("#btn_rehabilitar_caso").on("click", function() {

    let caso_id_a = $('#caso_id_v').val();

    $('#ventana_modal_rehabilita').modal('show');
    $('#caso_id_a').val(caso_id_a);
});

// Funcion que anula el caso
$("#formulario_rehabilita").on("submit", function(e) {

    e.preventDefault();

    var caso_id = $('#caso_id_a').val();
    var caso_numero = $('#caso_numero_v').val();

    var parametros = {
        "caso_id": caso_id,
        "opcion": 'rehabilitar_casos'
    };

    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: 'caso_cb.php',
        data: parametros,
        success: function() {
            // Esto deberia estar en success
            $.Notification.autoHideNotify('success', 'top center', 'Caso reahbilitado exitosamente...', 'Se ha reahbilitado el Caso: ' + caso_numero);
            $('#ventana_modal_rehabilita').modal('hide');
            formulario_lectura(caso_id);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $('#ventana_modal_rehabilita').modal('hide');
            $.Notification.autoHideNotify('warning', 'top center', 'El  caso no se pudo rehabilitar...', 'Error: ' + xhr.responseText);
            formulario_lectura(caso_id);
        }
    });




});

$('#tabServicios').on('shown.bs.tab', function(event){

    var caso_id = $("#tabServicios").attr("data-caso-id");
    $('#pantalla_servicios').attr('data', '../servicio/servicio.php?caso_id=' + caso_id);

});

// Carga los datos para mostrar el formulario de vista del caso
function formulario_lectura(caso_id) {

    // scrollea la pagina hasta arriba la cargar la funcion
    window.scrollTo(0, 0);

    // Carga los distintos tabs en ver caso
    $('#pantalla_comunicaciones').attr('data', '../comunicacion/comunicacion.php?caso_id=' + caso_id);
    $('#pantalla_servicios').attr('data', '../servicio/servicio.php?caso_id=' + caso_id);
    $('#pantalla_reintegros').attr('data', '../reintegro/reintegro.php?caso_id=' + caso_id);
    $('#pantalla_archivos').attr('data', '../archivos/archivo.php?caso_id=' + caso_id);

    var parametros = {
        "caso_id": caso_id,
        "opcion": 'formulario_lectura'
    };

    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: 'caso_cb.php',
        data: parametros,
        success: function(data) {

            // El permiso viene del return de la Clase Caso
            if (data != false) {

                $('#caso_id_v').val(data.caso_id);

                // DATOS OPERATIVOS
                $('#tabServicios').attr("data-caso-id", data.caso_id);
                $('#caso_numero_v').val(data.caso_numero);
                $('#caso_casoEstado_nombre_v').val(data.casoEstado_nombre);
                $('#caso_fechaAperturaCaso_v').val(data.caso_fechaAperturaCaso);
                $('#caso_ultimaModificacion_v').val(data.caso_ultimaModificacion);
                var abiertoPor = data.abiertoPor_nombre + ' ' + data.abiertoPor_apellido; // Arma el string con nombre + apellido
                $('#caso_abiertoPor_nombre_v').val(abiertoPor);
                if (data.asignadoA_nombre != null && data.asignadoA_apellido != null) {
                    var asignadoA = data.asignadoA_nombre + ' ' + data.asignadoA_apellido; // Arma el string con nombre + apellido    
                    $('#caso_asignadoA_nombre_v').val(asignadoA);
                } else {
                    $('#caso_asignadoA_nombre_v').val('Caso sin asignar');
                }
                // DATOS PRINCIPALES PARA CREAR EL CASO
                $('#caso_numeroVoucher_v').val(data.caso_numeroVoucher);
                $('#caso_cliente_nombre_v').val(data.cliente_nombre);
                $('#caso_beneficiarioNombre_v').val(data.caso_beneficiarioNombre);

                // Checkbox pickerno medical cost_n
                var no_medical_cost = data.no_medical_cost;
                if (no_medical_cost == '1') {
                    $('#no_medical_cost_v').prop('checked', true);
                } else if (no_medical_cost == '0') {
                    $('#no_medical_cost_v').prop('checked', false);
                }
                
                // Checkbox picker PaxVIP
                var caso_paxVIP = data.caso_paxVIP;
                if (caso_paxVIP == '1') {
                    $('#caso_paxVIP_v').prop('checked', true);
                } else if (caso_paxVIP == '0') {
                    $('#caso_paxVIP_v').prop('checked', false);
                }
                // Checkbox picker PaxVIP
                var caso_legal = data.caso_legal;
                if (caso_legal == '1') {
                    $('#caso_legal_v').prop('checked', true);
                } else if (caso_legal == '0') {
                    $('#caso_legal_v').prop('checked', false);
                }
                $('#caso_fechaSiniestro_v').val(data.caso_fechaSiniestro);
                $('#caso_tipoAsistencia_nombre_v').val(data.tipoAsistencia_nombre);                
                $('#caso_fee_nombre_v').val(data.fee_nombre);
                // DATOS TERRITORIALES
                $('#caso_pais_nombre_v').val(data.pais_nombreEspanol);
                $('#caso_ciudad_id_v').val(data.ciudad_nombre);
                $('#caso_ciudad_id_2_v').val(data.caso_ciudad_id);
                $('#caso_direccion_v').val(data.caso_direccion);
                $('#caso_codigoPostal_v').val(data.caso_codigoPostal);
                $('#caso_hotel_v').val(data.caso_hotel);
                $('#caso_habitacion_v').val(data.caso_habitacion);
                // DATOS DE CONTACTO
                grilla_telefonos_v();
                $('#email_id_v').val(data.email_id);
                $('#email_email_v').val(data.email_email);
                // DATOS DEL BENEFICIARIO
                $('#caso_beneficiarioNacimiento_v').val(data.caso_beneficiarioNacimiento);
                $('#caso_beneficiarioEdad_v').val(data.caso_beneficiarioEdad);
                $('#caso_beneficiarioGenero_id_v').val(data.caso_beneficiarioGenero_id);
                if (data.caso_beneficiarioGenero_id == 1) {
                    $('#caso_beneficiarioGenero_nombre_v').val("Masculino");
                } else if(data.caso_beneficiarioGenero_id == 2) {
                    $('#caso_beneficiarioGenero_nombre_v').val("Femenino");
                } else {
                    $('#caso_beneficiarioGenero_nombre_v').val("N/D");
                }
                $('#caso_beneficiarioDocumento_v').val(data.caso_beneficiarioDocumento);
                // DATOS DEL PRODUCTO
                $('#caso_producto_nombre_v').val(data.product_name);
                $('#caso_deducible_v').val(data.caso_deducible);
                $('#caso_agencia_v').val(data.caso_agencia);
                $('#caso_quienEmitioVoucher_v').val(data.caso_quienEmitioVoucher);
                $('#caso_fechaSalida_v').val(data.caso_fechaSalida);
                $('#caso_fechaEmisionVoucher_v').val(data.caso_fechaEmisionVoucher);
                $('#caso_vigenciaVoucherDesde_v').val(data.caso_vigenciaVoucherDesde);
                $('#caso_vigenciaVoucherHasta_v').val(data.caso_vigenciaVoucherHasta);
                // Limita los datepicker del voucher a las fechas ingresadas hasta el momento
                var emision_voucher = data.caso_fechaEmisionVoucher;
                var vigencia_desde = data.caso_vigenciaVoucherDesde;
                var vigencia_hasta = data.caso_vigenciaVoucherHasta;
                modificarVoucher_modificacionCasos(emision_voucher, vigencia_desde, vigencia_hasta);
                // DATOS POR ENFERMEDAD
                $('#caso_fechaInicioSintomas_v').val(data.caso_fechaInicioSintomas);
                $('#caso_sintomas_v').val(data.caso_sintomas);
                $('#caso_antecedentes_v').val(data.caso_antecedentes);
                $('#caso_diagnostico_id_v').val(data.diagnostico_nombre);
                $('#caso_diagnostico_id_2_v').val(data.caso_diagnostico_id);
                // DATOS POR DEMORA DE VUELO
                $('#caso_motivoVueloDemorado_v').val(data.caso_motivoVueloDemorado);
                // DATOS POR PERDIDA DE EQUIPAJE
                $('#caso_companiaAerea_v').val(data.caso_companiaAerea);
                $('#caso_numeroVuelo_v').val(data.caso_numeroVuelo);
                $('#caso_numeroPIR_v').val(data.caso_numeroPIR);
                $('#caso_titularPIR_v').val(data.caso_titularPIR);
                $('#caso_numeroEquipaje_v').val(data.caso_numeroEquipaje);
                // Limita los datepicker de perdida y recuperacion de equipaje a las fechas ingresadas hasta el momento
                $('#caso_fechaPerdidaEquipaje_v').val(data.caso_fechaPerdidaEquipaje);
                $('#caso_fechaRecuperacionEquipaje_v').val(data.caso_fechaRecuperacionEquipaje);
                var perdidaEquipaje = data.caso_fechaPerdidaEquipaje;
                var recuperacionEquipaje = data.caso_fechaRecuperacionEquipaje;
                modificarEquipaje_modificacionCasos(perdidaEquipaje, recuperacionEquipaje);
                // OTROS DATOS
                $('#caso_observaciones_v').val(data.caso_observaciones);
                $('#caso_campoSupervisor_v').val(data.caso_campoSupervisor);

                // Oculta o no los botones dependiendo si el caso fue ANULADO
                if (data.caso_anulado == 1) {
                    // Botones
                    $('#btn_clonar_caso').hide();
                    $('#btn_modificar_caso').hide();
                    $('#btn_anular_caso').hide();
                    $('#btn_rehabilitar_caso').show();
                    $('#btn_modificar_caso_footer').hide();
                    // Tabs
                    $('#tabComunicaciones').hide();
                    $('#tabServicios').hide();
                    $('#tabReintegros').hide();
                    $('#tabArchivos').hide();
                } else {
                    // Botones
                    $('#btn_clonar_caso').show();
                    $('#btn_modificar_caso').show();
                    $('#btn_anular_caso').show();
                    $('#btn_rehabilitar_caso').hide();
                    $('#btn_modificar_caso_footer').show();
                    // Tabs
                    $('#tabComunicaciones').show();
                    $('#tabServicios').show();
                    $('#tabReintegros').show();
                    $('#tabArchivos').show();
                }

                // Busca la clasificacion correspondiente al Tipo de Asistencia asignado
                // Para mostrar o no determinados DIV
                $('#caso_tipoAsistencia_clasificacion_id_v').val(data.tipoAsistencia_clasificacion_id);

                var tipoAsistencia_clasificacion_v = data.tipoAsistencia_clasificacion_id;

                if (tipoAsistencia_clasificacion_v == 1) {
                    $('#porEnfermedad_v').show();
                    $('#porDemoraVuelo_v').hide();
                    $('#porPerdidaEquipaje_v').hide();
                } else if (tipoAsistencia_clasificacion_v == 2) {
                    $('#porPerdidaEquipaje_v').show();
                    $('#porEnfermedad_v').hide();
                    $('#porDemoraVuelo_v').hide();
                } else if (tipoAsistencia_clasificacion_v == 3) {
                    $('#porDemoraVuelo_v').show();
                    $('#porPerdidaEquipaje_v').hide();
                    $('#porEnfermedad_v').hide();
                } else {
                    $('#porEnfermedad_v').hide();
                    $('#porDemoraVuelo_v').hide();
                    $('#porPerdidaEquipaje_v').hide();
                }

                // Alertas
                alertas_caso(caso_id);

                // Acomoda los paneles
                $('#panel_formulario_alta').slideUp();
                $('#panel_formulario_vista').removeClass('hidden');
                $('#panel_formulario_vista').slideDown();
                $('#panel_formulario_modificacion').slideUp();
                $('#panel_grilla').slideUp();
                $('#grilla_info').slideUp();
                $('#grilla_caso').slideUp();
            } else {
                // Agrega o quita propiedades a la Clase, para mostrar solo la info de Reintegros
                $("#caso").removeClass("active");
                $("#reintegros").addClass("active");
                $("#tabCasos").removeClass("active");
                $("#tabReintegros").addClass("active");
                $("#tabCasos").attr("aria-expanded","false");
                $("#tabReintegros").attr("aria-expanded","true");

                // Solo activa el Tab de Reintegros
                $('#tabCasos').hide();
                $('#tabComunicaciones').hide();
                $('#tabServicios').hide();
                $('#tabReintegros').show();
                $('#tabArchivos').hide();

                // Acomoda los paneles
                $('#panel_formulario_alta').slideUp();
                $('#panel_formulario_vista').removeClass('hidden');
                $('#panel_formulario_vista').slideDown();
                $('#panel_formulario_modificacion').slideUp();
                $('#panel_grilla').slideUp();
                $('#grilla_info').slideUp();
                $('#grilla_caso').slideUp();
            }
        }
    });
};


// Funcion para mostrar el formulario de clonar caso
$("#btn_clonar_caso").on('click', function() {

    // Funcion para preparar el formulario de CLONAR Casos
    preparar_clonar_caso();

    // Trae el valor del campo hidden caso_id
    let caso_id = $('#caso_id_v').val();

    var parametros = {
        "caso_id": caso_id,
        "opcion": 'formulario_clonar'
    };

    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: 'caso_cb.php',
        data: parametros,
        success: function(data) {
            paisesListar_clonarCasos(caso_id);
            $('#caso_ciudad_id_n').val(data.ciudad_nombre);
            $('#caso_ciudad_id_n_2').val(data.caso_ciudad_id);
            $('#caso_direccion_n').val(data.caso_direccion);
            $('#caso_codigoPostal_n').val(data.caso_codigoPostal);
            $('#caso_hotel_n').val(data.caso_hotel);
            $('#caso_habitacion_n').val(data.caso_habitacion);
            $('#caso_telefonoTipo_id_n').val(data.tipoTelefono_id);
            $('#telefono_numero_n').val(data.telefono_numero);
            $('#email_email_n').val(data.email_email);
        }
    });
});


// Funcion para mostrar alertas en los casos
function alertas_caso(caso_id) {

    let parametros = {
        "caso_id": caso_id,
        "opcion": 'alertas_caso'
    };

    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: 'caso_cb.php',
        data: parametros,
        success: function(data) {

            // Toma datos del metodo Caso::alertas_caso
            let caso_numero                 = data.caso_numero;
            let caso_voucher                = data.caso_numeroVoucher;
            let voucherIniciales            = caso_voucher.substr(0, 2);
            let voucher_assist1;
            if (voucherIniciales == 'A1') {
                voucher_assist1 = true;
            } else {
                voucher_assist1 = false;
            }
            let caso_deducible              = data.caso_deducible;
            let caso_vip                    = data.caso_paxVIP;
            let caso_anulado                = data.caso_anulado;
            let voucherFueraCobertura       = data.caso_voucherFueraCobertura;

            let caso_fechaSiniestro         = data.caso_fechaSiniestro;
            let fecha_siniestro             = moment(caso_fechaSiniestro).format('DD/MM/YYYY');
            let caso_fechaAperturaCaso      = data.caso_fechaAperturaCaso;
            let fecha_apertura              = moment(caso_fechaAperturaCaso).format('DD/MM/YYYY');
            let caso_fechaEmisionVoucher    = data.caso_fechaEmisionVoucher;
            let fecha_emision               = moment(caso_fechaEmisionVoucher).format('DD/MM/YYYY');
            let caso_vigenciaDesde          = data.caso_vigenciaVoucherDesde;
            let vigencia_desde              = moment(caso_vigenciaDesde).format('DD/MM/YYYY');
            let caso_vigenciaHasta          = data.caso_vigenciaVoucherHasta;
            let vigencia_hasta              = moment(caso_vigenciaHasta, 'DD-MM-YYYY');

            let producto                    = data.product_name;
            let productoLowerCase           = producto.toLowerCase(); // Pasa el producto a miniscula para la validacion
            let sistema_antiguo             = data.caso_sistemaAntiguo;
            let caso_ws                     = data.caso_info_ws;


            /* Valida si la fecha de emision esta entre dos fechas */
            // Parametros
            let dateFrom = "15/05/2018";
            let today = moment().toDate();
            let today_formatted = moment(today).format('DD/MM/YYYY');
            let dateTo = today_formatted;
            let dateCheck = moment(caso_fechaEmisionVoucher).format('DD/MM/YYYY');
            // Split
            let d1 = dateFrom.split("/");
            let d2 = dateTo.split("/");
            let c = dateCheck.split("/");
            // Parsea las fechas
            let from = new Date(d1[2], parseInt(d1[1])-1, d1[0]);  // -1 because months are from 0 to 11
            let to = new Date(d2[2], parseInt(d2[1])-1, d2[0]);
            let check = new Date(c[2], parseInt(c[1])-1, c[0]);
            // Resultado
            let entre_fechas = ((check >= from && check < to) || (check > from && check <= to));


            /* VALIDA: Si el Caso esta ANULADO */
            if (caso_anulado == 1) {
                $.Notification.notify('error', 'bottom right', 'Caso ANULADO', 'Caso: ' + caso_numero);
            } else { // Sino esta ANULADO, muestra las otras alertas de existir

                /* VALIDA: Si el Caso es VIP */
                if (caso_vip == 1) {
                    $.Notification.notify('custom', 'bottom right', 'Caso VIP', 'Caso: ' + caso_numero);
                }

                /* VALIDA: Si el Caso tiene DEDUCIBLE  */
                if ((caso_deducible !== '') && (caso_deducible !== 'no') && (caso_deducible !== 'No') && (caso_deducible !== 'NO') && (caso_deducible !== 'N/A')) {
                    $.Notification.notify('warning', 'bottom right', 'Caso con Deducible', 'Deducible: ' + caso_deducible);
                }

                /* VALIDA: Si el Caso esta REPETIDO por numero de voucher. La funcion buscar_vouchers_repetidos realiza la logica */
                buscar_vouchers_repetidos(caso_voucher, 'alertas');

                /* VALIDA: Si el caso se cargo por WS */
                if (caso_ws != 1) {
                    $.Notification.notify('error', 'bottom right', 'Caso Manual', 'Debe regularizarse con la información del Sistema de Emisión');
                }

                /* VALIDA: Si el caso se creo estando fuera de cobertura */
                if (voucherFueraCobertura == 1) {
                    $.Notification.notify('warning', 'bottom right', 'Caso Fuera de Cobertura', 'El Caso fue creado con un Voucher fuera de cobertura, por lo que solo usuarios autorizados pueden cargar servicios');
                }

                /* VALIDA: Si el Voucher esta PROXIMO A VENCER o VENCIDO */
                // Calcula la diferencia en dias
                let hoy = new Date(); // hoy();
                let hoy_moment = moment(hoy, 'YYYY-MM-DD');
                let dias_restantes;
                dias_restantes = vigencia_hasta.diff(hoy_moment, 'days');

                if (hoy_moment > vigencia_hasta) {
                    $.Notification.notify('error', 'bottom right', 'Voucher VENCIDO', 'Vigencia hasta: ' + caso_vigenciaHasta); // ARGENTODO: Hay que formatear bien esta fecha
                } else if (dias_restantes <= 7) {
                    $.Notification.notify('warning', 'bottom right', 'Voucher próximo a vencer', 'Días restantes: ' + dias_restantes);
                }

                /* VALIDA: 
                |   CASO MULTIVIAJES: 
                |       Si el Producto contiene: MULTIVIAJES
                */
                if (productoLowerCase.includes('multiviajes')) {

                    $.Notification.notify('custom', 'bottom right', 'Producto Multiviajes', 'Producto: ' + producto);

                }

                // Ticket  Comprueba que la fecha_emision sea menor a 31/12/2019
                let fe_yyyy     = moment(caso_fechaEmisionVoucher).format('YYYY');
                let fe_mm       = moment(caso_fechaEmisionVoucher).format('MM');
                let fe_dd       = moment(caso_fechaEmisionVoucher).format('DD');
                let f_emision   = new Date(fe_yyyy, fe_mm, fe_dd);
                let f_comprueba = new Date(2019, 12, 31); // 31/12/2019

                if (f_emision < f_comprueba) {

                    /*
                    | COORDINACION CORIS: 
                    |       CONDICION 1
                    |           1- Si el Voucher es de Assist1
                    |       CONDICION 2
                    |           1- Si el Voucher es UY o PY
                    |           2- Si la Fecha de emisión esta entre 15/05/18 y now()
                    |           3- Si el producto es: BRONZE - GOLD – PLATINUM – SILVER - DIAMOND
                    |       CONDICION 3
                    |           1- Si el Voucher es BV
                    |       CONDICION 4
                    |           1- Si el Voucher es AR
                    |           2- Si el producto es: BRONZE GIT
                    |       CONDICION 5
                    |           1- Si el producto es: EUROPABA GOLD PLUS TC
                    */
                    if  (

                            // CONDICION 1
                            (voucher_assist1 == true) ||
                            // CONDICION 2
                            (
                                ((voucherIniciales == 'UY') || (voucherIniciales == 'PY')) &&
                                (entre_fechas == true) &&
                                ((productoLowerCase.includes('bronze')) || (productoLowerCase.includes('silver')) || (productoLowerCase.includes('gold')) ||
                                    (productoLowerCase.includes('platinum')) || (productoLowerCase.includes('diamond')))
                            ) ||
                            // CONDICION 3
                            (voucherIniciales == 'BV') ||
                            // CONDICION 4
                            (voucherIniciales == 'AR' &&  productoLowerCase.includes('bronze git')) ||
                            // CONDICION 5
                            (productoLowerCase.includes('europaba gold plus tc'))

                        )
                    
                    {

                        $.Notification.notify('custom', 'bottom right', 'Coordinación CORIS', 'Producto: ' + producto);

                        /*
                        |   CASO ILS: Ver Condiciones Coordinador ILS.xlsx  
                        */
                    } else if (

                        // CONDICIONES ARGENTINA
                        (
                            (voucherIniciales == 'AR') &&
                            (
                                (productoLowerCase.includes('coris 100')) ||
                                (productoLowerCase.includes('coris 300')) ||
                                (productoLowerCase.includes('sudamerica y mundo')) ||
                                (productoLowerCase.includes('acquaviajes assistance basic')) ||
                                (productoLowerCase.includes('acquaviajes assistance blue')) ||
                                (productoLowerCase.includes('amci diamond')) ||
                                (productoLowerCase.includes('amci gold')) ||
                                (productoLowerCase.includes('amci platinum')) ||
                                (productoLowerCase.includes('bronze')) ||
                                (productoLowerCase.includes('bronze 300 binomio ')) ||
                                (productoLowerCase.includes('bronze astros')) ||
                                (productoLowerCase.includes('bronze binomio 2019')) ||
                                (productoLowerCase.includes('bronze cs')) ||
                                (productoLowerCase.includes('bronze cvl')) ||
                                (productoLowerCase.includes('bronze cvl 76-85 mayores')) ||
                                (productoLowerCase.includes('bronze cvl.')) ||
                                (productoLowerCase.includes('bronze la ribera')) ||
                                (productoLowerCase.includes('bronze la ribera 250 ')) ||
                                (productoLowerCase.includes('bronze la ribera mayores')) ||
                                (productoLowerCase.includes('bronze pretti 2500')) ||
                                (productoLowerCase.includes('bronze pretti mayores')) ||
                                (productoLowerCase.includes('bronze pretti-')) ||
                                (productoLowerCase.includes('bronze pretti.')) ||
                                (productoLowerCase.includes('bronze se tur ')) ||
                                (productoLowerCase.includes('bronze setur')) ||
                                (productoLowerCase.includes('bronze toselli reg')) ||
                                (productoLowerCase.includes('bronze turismo latino')) ||
                                (productoLowerCase.includes('bronze vestigium 700')) ||
                                (productoLowerCase.includes('bronze viafin 1000')) ||
                                (productoLowerCase.includes('bronze viafin 6000')) ||
                                (productoLowerCase.includes('conosur')) ||
                                (productoLowerCase.includes('conosur promo')) ||
                                (productoLowerCase.includes('coris 100 gelardino')) ||
                                (productoLowerCase.includes('coris 100 gelardino mayores')) ||
                                (productoLowerCase.includes('coris 100 maigon')) ||
                                (productoLowerCase.includes('coris 100 setil viajes')) ||
                                (productoLowerCase.includes('coris 100 telecom')) ||
                                (productoLowerCase.includes('coris 100 torremolinos')) ||
                                (productoLowerCase.includes('coris 300 torremolinos')) ||
                                (productoLowerCase.includes('diamond')) ||
                                (productoLowerCase.includes('diamond 100')) ||
                                (productoLowerCase.includes('diamond 100 binomio')) ||
                                (productoLowerCase.includes('diamond 750')) ||
                                (productoLowerCase.includes('diamond akumal')) ||
                                (productoLowerCase.includes('diamond barcelona tour')) ||
                                (productoLowerCase.includes('diamond binomio')) ||
                                (productoLowerCase.includes('diamond coltravel')) ||
                                (productoLowerCase.includes('diamond coltravel 200')) ||
                                (productoLowerCase.includes('diamond coltravel dem')) ||
                                (productoLowerCase.includes('diamond cs')) ||
                                (productoLowerCase.includes('diamond disney 8400')) ||
                                (productoLowerCase.includes('diamond disney binomio')) ||
                                (productoLowerCase.includes('diamond disney el portal turismo ')) ||
                                (productoLowerCase.includes('diamond disney estudio turistico ')) ||
                                (productoLowerCase.includes('diamond disney tdc')) ||
                                (productoLowerCase.includes('diamond duspa viajes')) ||
                                (productoLowerCase.includes('diamond el portal')) ||
                                (productoLowerCase.includes('diamond exit tour')) ||
                                (productoLowerCase.includes('diamond extremo comodoro')) ||
                                (productoLowerCase.includes('diamond luis japaze')) ||
                                (productoLowerCase.includes('diamond luis japaze tours')) ||
                                (productoLowerCase.includes('diamond magic dreams')) ||
                                (productoLowerCase.includes('diamond open')) ||
                                (productoLowerCase.includes('diamond open turismo')) ||
                                (productoLowerCase.includes('diamond passerini')) ||
                                (productoLowerCase.includes('diamond plus fossati')) ||
                                (productoLowerCase.includes('diamond plus fossati')) ||
                                (productoLowerCase.includes('diamond plus judith tours')) ||
                                (productoLowerCase.includes('diamond plus tamanaha')) ||
                                (productoLowerCase.includes('diamond plus toshin')) ||
                                (productoLowerCase.includes('diamond plus valinor')) ||
                                (productoLowerCase.includes('diamond pretti')) ||
                                (productoLowerCase.includes('diamond pretti disney')) ||
                                (productoLowerCase.includes('diamond sudameria')) ||
                                (productoLowerCase.includes('feel free (lgbt)')) ||
                                (productoLowerCase.includes('gold ')) ||
                                (productoLowerCase.includes('gold duspa viajes')) ||
                                (productoLowerCase.includes('gold itinerantur')) ||
                                (productoLowerCase.includes('gold plus')) ||
                                (productoLowerCase.includes('gold plus matreros')) ||
                                (productoLowerCase.includes('gold plus tulutur')) ||
                                (productoLowerCase.includes('gold plus urban travel')) ||
                                (productoLowerCase.includes('gold travel quil')) ||
                                (productoLowerCase.includes('gold urban travel')) ||
                                (productoLowerCase.includes('platinum cs')) ||
                                (productoLowerCase.includes('platinum plus')) ||
                                (productoLowerCase.includes('platinum plus add travel')) ||
                                (productoLowerCase.includes('platinum plus alma viajera')) ||
                                (productoLowerCase.includes('platinum plus anual multiviajes 30')) ||
                                (productoLowerCase.includes('platinum plus arab')) ||
                                (productoLowerCase.includes('platinum plus fts')) ||
                                (productoLowerCase.includes('platinum plus furlong fox')) ||
                                (productoLowerCase.includes('platinum plus honda motors')) ||
                                (productoLowerCase.includes('platinum plus qwerty travel')) ||
                                (productoLowerCase.includes('platinum plus turismo dallas 500 ')) ||
                                (productoLowerCase.includes('silver')) ||
                                (productoLowerCase.includes('silver akumal')) ||
                                (productoLowerCase.includes('silver sudameria')) ||
                                (productoLowerCase.includes('tuv rheinland argentina'))
                            )
                        ) ||
                        // CONDICION URUGUAY
                        (
                            (voucherIniciales == 'UY') &&
                            (
                                (productoLowerCase.includes('coris 100')) ||
                                (productoLowerCase.includes('coris 300')) ||
                                (productoLowerCase.includes('sudamerica y mundo')) ||
                                (productoLowerCase.includes('corporativo d2')) ||
                                (productoLowerCase.includes('corporativo d3')) ||
                                (productoLowerCase.includes('corporativo dp4')) ||
                                (productoLowerCase.includes('corporativo pp1')) ||
                                (productoLowerCase.includes('corporativo pp3'))
                            )
                        ) ||
                        // CONDICION CHILE
                        (
                            (voucherIniciales == 'CL') &&
                            (
                                (productoLowerCase.includes('coris 100')) ||
                                (productoLowerCase.includes('coris 300')) ||
                                (productoLowerCase.includes('sudamerica y mundo')) ||
                                (productoLowerCase.includes('bronce  el avión de la roja')) ||
                                (productoLowerCase.includes('bronze web')) ||
                                (productoLowerCase.includes('diamond')) ||
                                (productoLowerCase.includes('diamond plus')) ||
                                (productoLowerCase.includes('diamond plus compara')) ||
                                (productoLowerCase.includes('europe compara')) ||
                                (productoLowerCase.includes('europe plus compara')) ||
                                (productoLowerCase.includes('feel free (lgbt)')) ||
                                (productoLowerCase.includes('gold')) ||
                                (productoLowerCase.includes('gold plus')) ||
                                (productoLowerCase.includes('gold web')) ||
                                (productoLowerCase.includes('mercosur')) ||
                                (productoLowerCase.includes('mercosur compara')) ||
                                (productoLowerCase.includes('platinum')) ||
                                (productoLowerCase.includes('platinum plus')) ||
                                (productoLowerCase.includes('platinum plus compara')) ||
                                (productoLowerCase.includes('silver')) ||
                                (productoLowerCase.includes('silver compara')) ||
                                (productoLowerCase.includes('silver web')) ||
                                (productoLowerCase.includes('sudamérica & mundo'))
                            )
                        ) ||
                        // CONDICION PARAGUAY
                        (
                            (voucherIniciales == 'PY') &&
                            (
                                (productoLowerCase.includes('coris 100')) ||
                                (productoLowerCase.includes('coris 300')) ||
                                (productoLowerCase.includes('sudamerica y mundo'))
                            )
                        )

                    ) {

                        $.Notification.notify('custom', 'bottom right', 'Coordinación ILS', 'Producto: ' + producto);

                    }

                }

                /* VALIDA: Si el Caso viene de un sistema antiguo  */
                if (sistema_antiguo === '1') { // Caso gestionado en Cosmos
                    $.Notification.notify('default', 'bottom right', 'Sistema Antiguo', 'Caso gestionado en el SGC1');
                }

                /* VALIDA: Si el Caso viene de un sistema antiguo  */
                if (sistema_antiguo === '1') { // Caso gestionado en Cosmos
                    $.Notification.notify('default', 'bottom right', 'Sistema Antiguo', 'Caso gestionado en el SGC1');
                }

                /* VALIDA: FECHA SINIESTRO || FECHA APERTURA DEL CASO == FECHA EMISION || FECHA VOUCHER DESDE */
                // Hace primero validaciones generales
                if ((fecha_siniestro === fecha_emision) && (fecha_siniestro === vigencia_desde) && (fecha_apertura === fecha_emision) && (fecha_apertura === vigencia_desde)) {
                    $.Notification.notify('error', 'buttom right', 'VALIDAR CASO', 'Las <b>Fechas de Siniestro y Apertura del Caso</b> son <b>IGUALES</b> a la <b>Fecha de Emision y Vigencia Desde del Voucher</b>');
                } else if ((fecha_siniestro === fecha_emision) && (fecha_apertura === fecha_emision)) {
                    $.Notification.notify('error', 'buttom right', 'VALIDAR CASO', 'Las <b>Fechas de Siniestro y Apertura del Caso</b> son <b>IGUALES</b> a la <b>Fecha de Emision</b>');
                } else if ((fecha_siniestro === vigencia_desde) && (fecha_apertura === vigencia_desde)) {
                    $.Notification.notify('error', 'buttom right', 'VALIDAR CASO', 'Las <b>Fechas de Siniestro y Apertura del Caso</b> son <b>IGUALES</b> a la <b>Vigencia Desde del Voucher</b>');
                } else if ((fecha_siniestro === fecha_emision) && (fecha_siniestro === vigencia_desde)) {
                    $.Notification.notify('error', 'buttom right', 'VALIDAR CASO', 'La <b>Fecha de Siniestro</b> es <b>IGUAL</b> a la <b>Fecha de Emision y Vigencia Desde del Voucher</b>');
                } else if ((fecha_apertura === fecha_emision) && (fecha_apertura === vigencia_desde)) {
                    $.Notification.notify('error', 'buttom right', 'VALIDAR CASO', 'La <b>Apertura del Caso</b> es <b>IGUAL</b> a la <b>Fecha de Emision y Vigencia Desde del Voucher</b>');
                } else {
                    // Fecha del Siniestro == Fecha de Emision
                    if (fecha_siniestro === fecha_emision) {
                        $.Notification.notify('error', 'buttom right', 'VALIDAR CASO', 'La <b>Fecha del Siniestro</b> es <b>IGUAL</b> a la <b>Fecha de Emision</b>');
                    }
                    // Fecha del Siniestro == Fecha Voucher Desde
                    if (fecha_siniestro === vigencia_desde) {
                        $.Notification.notify('error', 'buttom right', 'VALIDAR CASO', 'La <b>Fecha del Siniestro</b> es <b>IGUAL</b> a la <b>Vigencia Desde del Voucher</b>');
                    }
                    // Fecha Apertura Caso == Fecha de Emision
                    if (fecha_apertura === fecha_emision) {
                        $.Notification.notify('error', 'buttom right', 'VALIDAR CASO', 'La <b>Fecha de Apertura del Caso</b> es <b>IGUAL</b> a la <b>Fecha de Emision</b>');
                    }
                    // Fecha Apertura Caso == Fecha Voucher Desde
                    if (fecha_apertura === vigencia_desde) {
                        $.Notification.notify('error', 'buttom right', 'VALIDAR CASO', 'La <b>Fecha de Apertura del Caso</b> es <b>IGUAL</b> a la <b>Vigencia Desde del Voucher</b>');
                    }
                }
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= ' + xhr.responseText + '</div>');
        }
    });
}


// Funcion para CONTAR (en caso que existan) los vouchers repetidos en casos cargados
function buscar_vouchers_repetidos(numero_voucher, form) {

    let parametros = {
        "numero_voucher": numero_voucher,
        "opcion": 'buscar_vouchers_repetidos'
    };

    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: 'caso_cb.php',
        data: parametros,
        success: function(data) {

            // Dado que el aviso de voucher repetido funciona en los formularios de 'alta' y 'modificacion' de caso, 
            // distintos que cuando se llama desde 'alertas'. Se arma de esta forma:
            let repetidos;
            if (form != 'alertas') {
                repetidos = data.cantidad + 1;
            } else {
                repetidos = data.cantidad;
            }

            if (repetidos > 1) {

                $.Notification.confirm('warning', 'bottom right', 'VOUCHER REPETIDO', 'Desea ver los casos en donde se repite?');

                // Escucha el evento click
                // Al clickear NO
                $(document).on('click', '.notifyjs-metro-base .no', function() {
                    // Oculta la notificacion
                    $(this).trigger('notify-hide');
                });
                // Al clickear SI
                $(document).on('click', '.notifyjs-metro-base .yes', function() {
                    // Llama a la funcion mostrar_vouchers_repetidos() para mostrar el modal con los casos repetidos 
                    mostrar_vouchers_repetidos(numero_voucher);
                    // Oculta la notificacion
                    $(this).trigger('notify-hide');
                });

            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= ' + xhr.responseText + '</div>');
        }
    });
}


// Funcion para MOSTRAR (en caso que existan) los vouchers repetidos en casos cargados
function mostrar_vouchers_repetidos(numero_voucher) {

    let parametros = {
        "numero_voucher": numero_voucher,
        "opcion": 'mostrar_vouchers_repetidos'
    };

    $.ajax({
        type: 'POST',
        dataType: 'HTML',
        url: 'caso_cb.php',
        data: parametros,
        success: function(data) {
            // Muestra el modal de autorizacion de facturas
            $('#modal_casosRepetidos').modal('show');

            // Acomoda la grilla dentro del modal
            $("#grilla_casos_repetidos").html(data);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= ' + xhr.responseText + '</div>');
        }
    });
}


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


// Resetear el formulario ALTA cuando se pulsa en cancelar
$("#btn_cancelar_nuevo").click(function() {

    // Desbloquea el boton nuevo caso
    $('#btn_nuevo_caso').prop("disabled", false);
    $('#btn_caso_manual').prop("disabled", false);
    // Limpia el formulario
    $("#formulario_alta").clearValidation();
    $('#formulario_alta')[0].reset();
    // Acomoda los paneles
    $('#panel_formulario_alta').slideUp();
    $('#panel_formulario_vista').slideUp();
    $('#panel_formulario_modificacion').slideUp();
    $('#panel_grilla').slideDown();
    $('#grilla_info').slideDown();
    $('#grilla_caso').slideDown();
});

// Resetear el formulario VISTA cuando se pulsa en Cerrar
$("#btn_cancelar_vista").click(function() {

    // Oculta las alertas que pudieran haber aparecido y deban cerrarse
    $('.notifyjs-wrapper').trigger('notify-hide');

    $('#panel_formulario_alta').slideUp();
    $('#panel_formulario_vista').slideUp();
    $('#panel_formulario_modificacion').slideUp();
    $('#panel_grilla').slideDown();
    $('#grilla_info').slideDown();
    $('#grilla_caso').slideDown();
});

// Resetear el formulario MODIFICACION cuando se pulsa en Cancelar
$("#btn_cancelar_modificacion").click(function() {

    // Oculta las alertas que pudieran haber aparecido y deban cerrarse
    $('.notifyjs-wrapper').trigger('notify-hide');
    // Desbloquea el boton nuevo caso
    $('#btn_nuevo_caso').prop("disabled", false);
    $('#btn_caso_manual').prop("disabled", false);
    // Limpia formulario modificacion
    $("#formulario_modificacion").clearValidation();
    $('#formulario_modificacion')[0].reset();
    // Llamar a formulario_lectura para mostrar el caso desde donde se cancelo
    caso_id = $('#caso_id').val();
    formulario_lectura(caso_id);
});



function habilita_alta_manual_caso() {

    preparar_alta_caso();

    $('#caso_beneficiarioNombre_n').prop("readonly", false);
    $('#caso_beneficiarioNacimiento_n').prop("disabled", false);
    $('#caso_beneficiarioDocumento_n').prop("readonly", false);
    $('#caso_producto_id_n').prop("disabled", false);
    $('#caso_deducible_n').prop("readonly", false);
    $('#caso_agencia_n').prop("readonly", false);
    $('#caso_quienEmitioVoucher_n').prop("readonly", false);
    $('#caso_fechaEmisionVoucher_n').prop("disabled", false);
    $('#caso_vigenciaVoucherDesde_n').prop("disabled", false);
    $('#caso_vigenciaVoucherHasta_n').prop("disabled", false);

    // Indica que el caso no es manual
    $('#caso_info_ws_n').val(0);


}



// Funcion para preparar el formulario de ALTA Casos
function preparar_alta_caso() {

    // Oculta las alertas que pudieran haber aparecido y deban cerrarse
    $('.notifyjs-wrapper').trigger('notify-hide');

    // Bloquea el boton nuevo caso
    $('#btn_nuevo_caso').prop("disabled", true);
    $('#btn_caso_manual').prop("disabled", false);

    // Vacia los select
    $("#caso_cliente_id_n").empty();
    $("#caso_pais_id_n").empty();
    $("#caso_tipoAsistencia_id_n").empty();
    $("#caso_fee_id_n").empty();
    $("#caso_telefonoTipo_id_n").empty();
    $("#caso_producto_id_n").empty();

    // Agrega la informacion a los distintos Select en el formulario de ALTA de CASOS
    // Select Clientes
    formulario_alta_clientes();
    // Select Paises
    formulario_alta_paises();
    // Select Tipos de Asistencias
    formulario_alta_tiposAsistencias();
    // Select Fees
    formulario_alta_fees();
    // Select Tipos Telefonos
    listarTipoTelefonos_caso();

    // Habilita los campos que traen info del WS
    // En caso que se haya cargado un caso anterior con info del WS y no se haya refrescado la pagina

    $('#caso_cliente_id_n').prop("disabled", true);
    $('#caso_beneficiarioNombre_n').prop("readonly", true);
    $('#caso_beneficiarioNacimiento_n').prop("disabled", true);
    $('#caso_beneficiarioDocumento_n').prop("readonly", true);
    $('#caso_producto_id_n').prop("disabled", true);
    $('#caso_deducible_n').prop("readonly", true);
    $('#caso_agencia_n').prop("readonly", true);
    $('#caso_quienEmitioVoucher_n').prop("readonly", true);
    $('#caso_fechaEmisionVoucher_n').prop("disabled", true);
    $('#caso_vigenciaVoucherDesde_n').prop("disabled", true);
    $('#caso_vigenciaVoucherHasta_n').prop("disabled", true);

    $('#caso_numeroVoucher_n').prop("readonly", false);
    $('#caso_cliente_id_n').prop("disabled", false);

    // Acomoda los paneles
    $('#panel_formulario_alta').removeClass('hidden');
    $('#panel_formulario_alta').slideDown();
    $('#panel_formulario_vista').slideUp();
    $('#panel_grilla').slideUp();
    $('#grilla_info').slideUp();
    $('#grilla_caso').slideUp();
};

// Funcion para preparar el formulario de CLONAR Casos
function preparar_clonar_caso() {

    // Oculta las alertas que pudieran haber aparecido y deban cerrarse
    $('.notifyjs-wrapper').trigger('notify-hide');

    // Bloquea el boton nuevo caso
    $('#btn_nuevo_caso').prop("disabled", true);
    $('#btn_caso_manual').prop("disabled", true);

    // Vacia los select
    $("#caso_cliente_id_n").empty();
    //$("#caso_pais_id_n").empty();
    $("#caso_tipoAsistencia_id_n").empty();
    $("#caso_fee_id_n").empty();
    $("#caso_telefonoTipo_id_n").empty();
    $("#caso_producto_id_n").empty();

    // Agrega la informacion a los distintos Select en el formulario de ALTA de CASOS
    // Select Clientes
    formulario_alta_clientes();
    // Select Paises
    //formulario_alta_paises();
    // Select Tipos de Asistencias
    formulario_alta_tiposAsistencias();
    // Select Fees
    formulario_alta_fees();
    // Select Tipos Telefonos
    listarTipoTelefonos_caso();

    // Habilita los campos que traen info del WS
    // En caso que se haya cargado un caso anterior con info del WS y no se haya refrescado la pagina
    $('#caso_numeroVoucher_n').prop("readonly", false);
    $('#caso_cliente_id_n').prop("disabled", false);
    $('#caso_beneficiarioNombre_n').prop("readonly", false);
    $('#caso_beneficiarioNacimiento_n').prop("disabled", false);
    $('#caso_beneficiarioDocumento_n').prop("readonly", false);
    $('#caso_producto_id_n').prop("disabled", false);
    $('#caso_deducible_n').prop("readonly", false);
    $('#caso_agencia_n').prop("readonly", false);
    $('#caso_quienEmitioVoucher_n').prop("readonly", false);
    $('#caso_fechaEmisionVoucher_n').prop("disabled", false);
    $('#caso_vigenciaVoucherDesde_n').prop("disabled", false);
    $('#caso_vigenciaVoucherHasta_n').prop("disabled", false);

    // Acomoda los paneles
    $('#panel_formulario_alta').removeClass('hidden');
    $('#panel_formulario_alta').slideDown();
    $('#panel_formulario_vista').slideUp();
    $('#panel_grilla').slideUp();
    $('#grilla_info').slideUp();
    $('#grilla_caso').slideUp();
};


// -------------------------------------------   INICIO Calcula Edad -----------------------------------------------
// Funcion para calcular la edad en base a la fecha de nacimiento
function calcularEdad(fecha) {
    var nacimiento = moment(fecha, "DD-MM-YYYY");
    var hoy = moment();
    var anios = hoy.diff(nacimiento,"years");
    return anios;
}


// Formulario Alta
$('#caso_beneficiarioNacimiento_n').change(function() {

    let fechaNacimiento = $("#caso_beneficiarioNacimiento_n").val();

    let edad = calcularEdad(fechaNacimiento);
    $("#caso_beneficiarioEdad_n").val(edad)
});

// Formulario Modificacion
$('#caso_beneficiarioNacimiento').change(function() {

    let fechaNacimiento = $("#caso_beneficiarioNacimiento").val();
    let edad = calcularEdad(fechaNacimiento);
    $("#caso_beneficiarioEdad").val(edad)
});
// -------------------------------------------   FIN Calcula Edad -----------------------------------------------


// -------------------------------------------   INICIO Ocultar o Mostrar DIV -----------------------------------

// Formulario Alta
// Primera carga aparecen los DIV ocultos
$('#porEnfermedad_n').hide();
$('#porDemoraVuelo_n').hide();
$('#porPerdidaEquipaje_n').hide();
// $('#no_medical_cost_container').hide();


// Llamado a la funcion para traer el ID de clasificacion
$('#caso_tipoAsistencia_id_n').change(function() {

    let tipoAsistencia_id = $("#caso_tipoAsistencia_id_n option:selected").val();

    let parametros = {
        "tipoAsistencia": tipoAsistencia_id,
        "opcion": 'tipoAsistencia_clasificacion'
    };
    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: 'caso_cb.php',
        data: parametros,
        success: function(data) {
            let tipoAsistencia_clasificacion_id = data.tipoAsistencia_clasificacion_id;
            $("#caso_tipoAsistencia_clasificacion_id_n").val(tipoAsistencia_clasificacion_id)
                .trigger('change'); // 30/04/17- Ver con Esteban: https://goo.gl/ylgdTI
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $.Notification.autoHideNotify('error', 'top center', 'Error...', 'Reporte del error. ' + xhr.responseText + ' ' + ajaxOptions + ' ' + thrownError);
        }
    });
});

// Funcion change para mostrar u ocultar los DIV dependiendo de la seleccion del Tipo de Asistencia -> Clasificacion Tipo Asistencia
$('#caso_tipoAsistencia_clasificacion_id_n').change(function() {
    $('#no_medical_cost_container #no_medical_cost_n').prop('checked', false);
    $('#no_medical_cost_container').show();
    if ($('#caso_tipoAsistencia_clasificacion_id_n').val() == 1) {
        $('#porEnfermedad_n').show();
        $('#porDemoraVuelo_n').hide();
        $('#porPerdidaEquipaje_n').hide();
    } else if ($('#caso_tipoAsistencia_clasificacion_id_n').val() == 2) {
        $('#porPerdidaEquipaje_n').show();
        $('#porEnfermedad_n').hide();
        $('#porDemoraVuelo_n').hide();
        // $('#no_medical_cost_container').hide();
    } else if ($('#caso_tipoAsistencia_clasificacion_id_n').val() == 3) {
        $('#porDemoraVuelo_n').show();
        $('#porEnfermedad_n').hide();
        $('#porPerdidaEquipaje_n').hide();
        // $('#no_medical_cost_container').hide();
    } else {
        $('#porEnfermedad_n').hide();
        $('#porDemoraVuelo_n').hide();
        $('#porPerdidaEquipaje_n').hide();
        // $('#no_medical_cost_container').hide();
    }
});


// Formulario Modificacion
// Llamado a la funcion para traer el ID de clasificacion
$('#caso_tipoAsistencia_id').change(function() {

    let tipoAsistencia_id = $("#caso_tipoAsistencia_id option:selected").val();

    let parametros = {
        "tipoAsistencia": tipoAsistencia_id,
        "opcion": 'tipoAsistencia_clasificacion'
    };
    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: 'caso_cb.php',
        data: parametros,
        success: function(data) {
            let tipoAsistencia_clasificacion_id = data.tipoAsistencia_clasificacion_id;
            $("#caso_tipoAsistencia_clasificacion_id").val(tipoAsistencia_clasificacion_id)
                .trigger('change'); // 30/04/17- Ver con Esteban: https://goo.gl/ylgdTI
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $.Notification.autoHideNotify('error', 'top center', 'Error...', 'Reporte del error. ' + xhr.responseText + ' ' + ajaxOptions + ' ' + thrownError);
        }
    });
});

// Funcion change para mostrar u ocultar los DIV dependiendo la seleccion del Tipo de Asistencia -> Clasificacion Tipo Asistencia
$('#caso_tipoAsistencia_clasificacion_id').change(function() {
    if ($('#caso_tipoAsistencia_clasificacion_id').val() == 1) {
        $('#porEnfermedad').show();
        $('#porDemoraVuelo').hide();
        $('#porPerdidaEquipaje').hide();
    } else if ($('#caso_tipoAsistencia_clasificacion_id').val() == 2) {
        $('#porPerdidaEquipaje').show();
        $('#porEnfermedad').hide();
        $('#porDemoraVuelo_n').hide();
    } else if ($('#caso_tipoAsistencia_clasificacion_id').val() == 3) {
        $('#porDemoraVuelo').show();
        $('#porEnfermedad').hide();
        $('#porPerdidaEquipaje').hide();
    } else {
        $('#porEnfermedad').hide();
        $('#porDemoraVuelo').hide();
        $('#porPerdidaEquipaje').hide();
    }
});

// -------------------------------------------   FIN Ocultar o Mostrar DIV --------------------------------------


// -------------------------------------------   Area Teléfonos -------------------------------------------------
// Vista Caso
// Grilla para los teléfonos
var grilla_telefonos_v = function() {

    let caso_id = $("#caso_id_v").val();

    var parametros = {
        "caso_id": caso_id,
        "opcion": 'grilla_telefonos_v'
    };
    $.ajax({
        dataType: "html",
        method: "POST",
        url: "caso_cb.php",
        data: parametros
    }).done(function(data) {
        $("#grilla_telefonos_v").html(data);

    });
};


// Modificar Caso
// Grilla para los teléfonos
var grilla_telefonos = function() {

    let caso_id = $("#caso_id").val();

    var parametros = {
        "caso_id": caso_id,
        "opcion": 'grilla_telefonos'
    };
    $.ajax({
        dataType: "html",
        method: "POST",
        url: "caso_cb.php",
        data: parametros
    }).done(function(data) {
        $("#grilla_telefonos").html(data);

    });
};


// Limpia el contenido del formulario
var telefono_limpiar = function() {

    $("#caso_telefonoTipo_id").val('');
    $("#telefono_numero").val('');
    $('#telefono_principal').prop('checked', false);
    $('#telefono_principal').val('');
    $("#telefono_id_m").val('');
    $('#caso_telefonoTipo_id').css("border", "1px solid #E6E6E6");
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
        url: "caso_cb.php",
        data: parametros,
        success: function(resultado) {
            if (resultado === 'OK') {
                $.Notification.autoHideNotify('success', 'top center', 'Teléfono borrado...', 'Se ha eliminado el Teléfono.');
            } else {
                $.Notification.autoHideNotify('error', 'top center', 'Error...', 'Descripción:' + resultado);
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $.Notification.autoHideNotify('error', 'top center', 'Error asincrónico...', 'Reporte del error. ' + xhr.responseText + ajaxOptions + thrownError);
        }

    }).done(function() {
        grilla_telefonos();
    });
    $('#ventana_modal_borrado_telefono').modal('hide');
    return false; // Previene que el formulario haga POST después de la validación, ya que ejecuto el guardado por Ajax. 
});


// Guarda el telefono nuevo o la modificación a un teléfono
var telefono_guardar = function() {

    let caso_id = $("#caso_id").val();
    let caso_telefonoTipo_id = $("#caso_telefonoTipo_id").val();
    let telefono_numero = $("#telefono_numero").val();


    if (caso_telefonoTipo_id == '' || telefono_numero == '' || telefono_numero == '+') {
        $.Notification.autoHideNotify('error', 'top center', 'Debe completar todos los campos ', 'Algunos de los campos no estan completos.');
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
        "caso_id": caso_id,
        "opcion": opcion,
        "caso_telefonoTipo_id": caso_telefonoTipo_id,
        "telefono_numero": telefono_numero,
        "telefono_principal": telefono_principal,
        "telefono_id_m": telefono_id_m
    };
    $.ajax({
        dataType: "JSON",
        method: "POST",
        url: "caso_cb.php",
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

    var parametros = {
        "telefono_id_e": telefono_id_e,
        "opcion": 'telefono_editar'
    };
    $.ajax({
        dataType: "JSON",
        method: "POST",
        url: "caso_cb.php",
        data: parametros,
        success: function(data) {

            $('#telefono_id_m').val(data.telefono_id);
            listarTipoTelefonos_modificacion(data.telefono_id);
            $('#telefono_numero').val(data.telefono_numero);

            let telefono_principal = data.telefono_principal;
            if (telefono_principal === '1') {
                $('#telefono_principal').prop('checked', true);
            } else {
                $('#telefono_principal').prop('checked', false);
            }

            $('#caso_telefonoTipo_id').css("border", "1px solid #5882FA");
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

// -------------------------------------- BUSCADOR Y GRILLA ----------------------------------------------------
// funciones para completar select del buscador
function casoEstadoslistar_buscadorCasos() {

    var parametros = {
        "opcion": 'casoEstadoslistar_buscadorCasos'
    };

    var miselect = $("#caso_estado_id_b");

    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: 'caso_cb.php',
        data: parametros,
        success: function(data) {

            miselect.append('<option value="">Seleccione un Estado</option>');

            for (var i = 0; i < data.length; i++) {
                miselect.append('<option value="' + data[i].casoEstado_id + '">' + data[i].casoEstado_nombre + '</option>');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= ' + xhr.responseText + '</div>');
        }
    });
};

function tipoAsistenciaListar_buscadorCasos() {

    var parametros = {
        "opcion": 'tipoAsistenciaListar_buscadorCasos'
    };

    var miselect = $("#caso_tipoAsistencia_id_b");

    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: 'caso_cb.php',
        data: parametros,
        success: function(data) {

            miselect.append('<option value="">Seleccione un Tipo de Asistencia</option>');

            for (var i = 0; i < data.length; i++) {
                miselect.append('<option value="' + data[i].tipoAsistencia_id + '">' + data[i].tipoAsistencia_nombre + '</option>');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= ' + xhr.responseText + '</div>');
        }
    });
};


//Habilita la tecla ENTER para el buscador
$(document).keypress(function(e) {
    if (e.keyCode === 13) {
        // Carga los datos en la grilla
        grilla_listar('', '');
    }
});


// Busca el prefijo del pais
$("#caso_pais_id_n").change(function() {

    const pais_id = $("#caso_pais_id_n option:selected").val();

    const parametros = {
        "opcion": 'buscar_prefijo_pais',
        "pais_id": pais_id
    };

    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: '../pais/pais_cb.php',
        data: parametros,
        success: function(data) {
            $("#btn_prefijo_pais_n").text(data);
            $("#btn_prefijo_pais_n_2").text(data);
        },
        error: function(xhr) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= ' + xhr.responseText + '</div>');
        }
    });
});


// Inserta el prefijo de pais en el campo telefono_numero_n 
$("#btn_prefijo_pais_n").click(function() {
    const prefijo_pais = $("#btn_prefijo_pais_n").text();
    $("#telefono_numero_n").val(prefijo_pais).focus();
});


// Inserta el prefijo de pais en el campo telefono_numero_n_2 
$("#btn_prefijo_pais_n_2").click(function() {
    const prefijo_pais = $("#btn_prefijo_pais_n_2").text();
    $("#telefono_numero_n_2").val(prefijo_pais).focus();
});


// Va a buscar los datos de la grilla
var grilla_listar = function() {

    let caso_numero_desde = $("#caso_numero_desde_b").val();
    let caso_numero_hasta = $("#caso_numero_hasta_b").val();
    let caso_estado_id = $("#caso_estado_id_b option:selected").val();
    let caso_tipoAsistencia_id = $("#caso_tipoAsistencia_id_b option:selected").val();
    let caso_fechaSiniestro_desde = $("#caso_fechaSiniestro_desde_b").val();
    let caso_fechaSiniestro_hasta = $("#caso_fechaSiniestro_hasta_b").val();
    let caso_beneficiario = $("#caso_beneficiario_b").val();
    let caso_voucher = $("#caso_voucher_b").val();
    let caso_agencia = $("#caso_agencia_b").val();


    var parametros = {
        "caso_numero_desde_b": caso_numero_desde,
        "caso_numero_hasta_b": caso_numero_hasta,
        "caso_estado_id_b": caso_estado_id,
        "caso_tipoAsistencia_id_b": caso_tipoAsistencia_id,
        "caso_fechaSiniestro_desde_b": caso_fechaSiniestro_desde,
        "caso_fechaSiniestro_hasta_b": caso_fechaSiniestro_hasta,
        "caso_beneficiario_b": caso_beneficiario,
        "caso_voucher_b": caso_voucher,
        "caso_agencia_b": caso_agencia,
        "opcion": 'grilla_listar_contar'
    };

    $.ajax({
        dataType: "html",
        method: "POST",
        url: "caso_cb.php",
        data: parametros,

        success: function(data) {
            $("#grilla_info").html(data);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= ' + xhr.responseText + '</div>');
        }
    });

    var parametros = {
        "caso_numero_desde_b": caso_numero_desde,
        "caso_numero_hasta_b": caso_numero_hasta,
        "caso_estado_id_b": caso_estado_id,
        "caso_tipoAsistencia_id_b": caso_tipoAsistencia_id,
        "caso_fechaSiniestro_desde_b": caso_fechaSiniestro_desde,
        "caso_fechaSiniestro_hasta_b": caso_fechaSiniestro_hasta,
        "caso_beneficiario_b": caso_beneficiario,
        "caso_voucher_b": caso_voucher,
        "caso_agencia_b": caso_agencia,
        "opcion": 'grilla_listar'
    };
    $.ajax({
        dataType: "html",
        method: "POST",
        url: "caso_cb.php",
        data: parametros
    }).done(function(data) {
        $("#grilla_caso").html(data);
        listar_casos();
    });
};

//Hace arrancar la lista
var listar_casos = function() {

    var table = $("#dt_caso").DataTable({
        "destroy": true,
        "stateSave": true,
        "bFilter": false,
        "order": [
            [0, 'desc']
        ],
        "language": idioma_espanol
    });
};

//Idioma de la grilla
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