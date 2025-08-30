//Verifica que se halla cargado la página para ejecutar las funciones.		
$().ready(function(){
      
    // Funciones que se van a ejecutar en la primer carga de la página.
    // Select Productos
    listar_productos();
    // Select Paises
    listar_paises();
    // Select estados de reintegro
    listar_reintegro_estados();
    
    //Fecha desde y Hasta enlazados
    $(
        function() {
                var dateFormat = "dd-mm-yy";
                from_fecha_n = $('#caso_fechaSiniestro_desde').datepicker({
                    maxDate: new Date(),
                    yearRange: '-1:+0'
                })
                .on("change", function() { to_fecha_n.datepicker("option", "minDate", getDate(this)); }),
                to_fecha_n = $('#caso_fechaSiniestro_hasta').datepicker({
                    maxDate: new Date(),
                    yearRange: '-1:+0'
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
        
    $('#reintegro_fechaIngresoSistema_desde').datepicker();
    $('#reintegro_fechaIngresoSistema_hasta').datepicker();
    
});

// Select Productos
function listar_productos(){

    var parametros = {
        "opcion": 'listar_productos'
    };

    var miselect = $("#caso_producto_id");

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'reporte_reintegros_general_cb.php',
        data: parametros,
        success:function(data){

            miselect.append('<option value="">Seleccione un Producto</option>');

            for (var i=0; i<data.length; i++) {
                miselect.append('<option value="' + data[i].product_id + '">' + data[i].product_name + '</option>');
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });  
};

// Paises para el select del buscador
function listar_paises(){

    var parametros = {
        "opcion": 'listar_paises'
    };

    var miselect = $("#caso_pais_id");
    
    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'reporte_reintegros_general_cb.php',
        data: parametros,
        success:function(data){
            
            miselect.append('<option value="">Seleccione un Pais</option>');
                        
            for (var i=0; i<data.length; i++) {
                miselect.append('<option value="' + data[i].pais_id + '">' + data[i].pais_nombreEspanol + '</option>');
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });
};

// Select Estados de reintegros
function listar_reintegro_estados(){

    var parametros = {
        "opcion": 'listar_reintegro_estados'
    };

    var miselect = $("#reintegroEstado_id");

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'reporte_reintegros_general_cb.php',
        data: parametros,
        success:function(data){

            miselect.append('<option value="">Seleccione un estado</option>');

            for (var i=0; i<data.length; i++) {
                miselect.append('<option value="' + data[i].reintegroEstado_id + '">' + data[i].reintegroEstado_nombre + '</option>');
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });  
};


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
    
    let caso_numero_desde = $("#caso_numero_desde").val();
    let caso_numero_hasta = $("#caso_numero_hasta").val();
    let caso_fechaSiniestro_desde = $("#caso_fechaSiniestro_desde").val();
    let caso_fechaSiniestro_hasta = $("#caso_fechaSiniestro_hasta").val();
    let caso_agencia = $("#caso_agencia").val();
    let caso_pais_id = $("#caso_pais_id option:selected").val();
    let caso_producto_id = $("#caso_producto_id option:selected").val();
    let reintegroEstado_id = $("#reintegroEstado_id").val();
    let reintegro_fechaIngresoSistema_desde = $("#reintegro_fechaIngresoSistema_desde").val();
    let reintegro_fechaIngresoSistema_hasta = $("#reintegro_fechaIngresoSistema_hasta").val();
      
               
    var parametros = {
        "caso_numero_desde": caso_numero_desde,
        "caso_numero_hasta": caso_numero_hasta,
        "caso_fechaSiniestro_desde": caso_fechaSiniestro_desde,
        "caso_fechaSiniestro_hasta": caso_fechaSiniestro_hasta,
        "caso_agencia": caso_agencia,
        "caso_pais_id": caso_pais_id,
        "caso_producto_id": caso_producto_id,
        "reintegroEstado_id": reintegroEstado_id,
        "reintegro_fechaIngresoSistema_desde": reintegro_fechaIngresoSistema_desde,
        "reintegro_fechaIngresoSistema_hasta": reintegro_fechaIngresoSistema_hasta,
        "opcion": 'grilla_listar_contar'
    };
    $.ajax({
            dataType: "html",
            method: "POST",
            url: "reporte_reintegros_general_cb.php",
            data: parametros,
           
            success:function(data){
                $("#grilla_info").html(data);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
            }
    });
 
    
    var parametros = {
        "caso_numero_desde": caso_numero_desde,
        "caso_numero_hasta": caso_numero_hasta,
        "caso_fechaSiniestro_desde": caso_fechaSiniestro_desde,
        "caso_fechaSiniestro_hasta": caso_fechaSiniestro_hasta,
        "caso_agencia": caso_agencia,
        "caso_pais_id": caso_pais_id,
        "caso_producto_id": caso_producto_id,
        "reintegroEstado_id": reintegroEstado_id,
        "reintegro_fechaIngresoSistema_desde": reintegro_fechaIngresoSistema_desde,
        "reintegro_fechaIngresoSistema_hasta": reintegro_fechaIngresoSistema_hasta,
        "opcion": 'grilla_listar'
    };
    $.ajax({
            dataType: "html",
            method: "POST",
            url: "reporte_reintegros_general_cb.php",
            data: parametros,
           
            success:function(data){
                $("#grilla_reintegros").html(data);
                
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
            }
    });
 };
 
 
//Hace arrancar la lista
var listar = function(){

    var table = $("#dt_reintegros").DataTable({
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
            url: "reporte_reintegros_general_cb.php",
            dataType: "json",
            data: $(form).serialize(),
            complete:
            function () {
                window.location = "reporte_reintegros_general_cb.php";
            }

    });

 };

