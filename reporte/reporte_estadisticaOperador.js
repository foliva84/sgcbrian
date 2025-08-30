//Verifica que se halla cargado la página para ejecutar las funciones.		
$().ready(function(){

    $('#incluir_usuariosDeshab').val('1');
      
    // Funciones que se van a ejecutar en la primer carga de la página.
        
    // Funciones de DateTimePicker
    // Date Picker
    // $('#caso_fechaAperturaCaso_desde').datepicker();
    // $('#caso_fechaAperturaCaso_hasta').datepicker();
    
    //Fecha desde y Hasta enlazados
    $(
        function() {

            var dateFormat = "dd-mm-yy";
            from_fecha_n = $('#caso_fechaAperturaCaso_desde').datepicker({
                    maxDate: new Date(),
                    yearRange: '-2:+0',
                    numberOfMonths: 2
                })
                .on("change", function() { to_fecha_n.datepicker("option", "minDate", getDate(this)); }),
                to_fecha_n = $('#caso_fechaAperturaCaso_hasta').datepicker({
                    maxDate: new Date(),
                    yearRange: '-2:+0',
                    numberOfMonths: 2
                })
                .on("change", function() { from_fecha_n.datepicker("option", "maxDate", getDate(this)); });

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
});

// Función para resetear validaciones que quedan arraigadas al formulario al menos que se las saque explícitamente.
$.fn.clearValidation = function(){var v = $(this).validate();$('[name]',this).each(function(){v.successList.push(this);v.showErrors();});v.resetForm();v.reset();};

// Resetear el formulario del buscador cuando se pulsa en cancelar
$("#btn_cancelar_nuevo").click(function(){
    $("#formulario_alta").clearValidation();
    $('#formulario_alta')[0].reset();
    $('#panel_formulario_alta').slideUp();
    $('#panel_formulario_modificacion').slideUp();
    $('#panel_grilla').slideDown();
});

//Va a buscar los datos de la grilla
var grilla_listar = function(){
    
    let caso_fechaAperturaCaso_desde = $("#caso_fechaAperturaCaso_desde").val();
    let caso_fechaAperturaCaso_hasta = $("#caso_fechaAperturaCaso_hasta").val();
    let incluir_usuariosDeshabilitados;
    
    if ($('#incluir_usuariosDeshab').prop("checked")) {
        incluir_usuariosDeshabilitados = 1;
    } else {
        incluir_usuariosDeshabilitados = 0;
    }
        
    var parametros = {
        "caso_fechaAperturaCaso_desde": caso_fechaAperturaCaso_desde,
        "caso_fechaAperturaCaso_hasta": caso_fechaAperturaCaso_hasta,
        "incluir_usuariosDeshabilitados": incluir_usuariosDeshabilitados,
        "opcion": 'grilla_listar'
    };
    $.ajax({
            dataType: "html",
            method: "POST",
            url: "reporte_estadisticaOperador_cb.php",
            data: parametros,
           
            success:function(data){
                $("#grilla_estadisticaOperador").html(data);
                
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
            }
    });
 };
 
 
//Hace arrancar la lista
var listar = function(){

    var table = $("#dt_estadisticaOperador").DataTable({
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



// Exportación a Excel - función para generar el excel a exportar - Utiliza la clase y librería PHPExcel
var exportar_excel = function(form){
      
    $.ajax({
            type: "POST",
            contentType: "application/json; charset=utf-8",
            url: "reporte_estadisticaOperador_cb.php",
            dataType: "json",
            data: $(form).serialize(),
            complete: function () {
                window.location = "reporte_estadisticaOperador_cb.php";
            }

    });
 };

