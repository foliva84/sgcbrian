//Verifica que se halla cargado la página para ejecutar las funciones.		
$().ready(function(){
      
    // Funciones que se van a ejecutar en la primer carga de la página.
    // Agrega la informacion a los distintos Select en el buscador de casos
    
    // Select Clientes
    listar_clientes();
    // Select 
    listar_fci_estados();
    // Select Prestador
    listar_prestadores();
                               
    // Funciones de DateTimePicker
    // Date Picker
    $('#factura_fechaIngresoSistema_desde').datepicker();
    $('#factura_fechaIngresoSistema_hasta').datepicker();
});

// Funciones para completar los SELECT
//
// Select Clientes
function listar_clientes(){

    var parametros = {
        "opcion": 'listar_clientes'
    };

    var miselect = $("#caso_cliente_id");

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'reporte_facturacion_cb.php',
        data: parametros,
        success:function(data){

            miselect.append('<option value="">Seleccione un Cliente</option>');

            for (var i=0; i<data.length; i++) {
                miselect.append('<option value="' + data[i].cliente_id + '">' + data[i].cliente_nombre + '</option>');
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });  
};

// Select Productos
function listar_fci_estados(){

    var parametros = {
        "opcion": 'listar_fci_estados'
    };

    var miselect = $("#facturaEstado_id");

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'reporte_facturacion_cb.php',
        data: parametros,
        success:function(data){

            miselect.append('<option value="">Seleccione un estado</option>');

            for (var i=0; i<data.length; i++) {
                miselect.append('<option value="' + data[i].fciMovEstado_id + '">' + data[i].fciMovEstado_nombre + ': ' + data[i].fciMovEstado_sector + '</option>');
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });  
};
    
    
// Lista de prestadores
function listar_prestadores() {

    var parametros = {
        "opcion": 'listar_prestadores_select'
    };

    var miselect = $("#caso_prestador_id");

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: '../prestador/prestador_cb.php',
        data: parametros,
        success:function(data){
            
            miselect.append('<option value="">Seleccione un prestador</option>');
            
            for (var i=0; i<data.length; i++) {
                miselect.append('<option value="' + data[i].prestador_id + '">' + data[i].prestador_nombre + '</option>');
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
    
    let factura_fechaIngresoSistema_desde = $("#factura_fechaIngresoSistema_desde").val();
    let factura_fechaIngresoSistema_hasta = $("#factura_fechaIngresoSistema_hasta").val();
    let caso_numero_desde = $("#caso_numero_desde").val();
    let caso_numero_hasta = $("#caso_numero_hasta").val();
    let caso_cliente_id = $("#caso_cliente_id option:selected").val();
    let caso_prestador_id = $("#caso_prestador_id option:selected").val();
    let facturaEstado_id = $("#facturaEstado_id option:selected").val();
    let factura_numero = $("#factura_numero").val();
                   
    var parametros = {
        "factura_fechaIngresoSistema_desde": factura_fechaIngresoSistema_desde,
        "factura_fechaIngresoSistema_hasta": factura_fechaIngresoSistema_hasta,
        "caso_numero_desde": caso_numero_desde,
        "caso_numero_hasta": caso_numero_hasta,
        "caso_cliente_id": caso_cliente_id,
        "caso_prestador_id": caso_prestador_id,
        "facturaEstado_id": facturaEstado_id,
        "factura_numero": factura_numero,          
        
        "opcion": 'grilla_listar_contar'
    };
    $.ajax({
            dataType: "html",
            method: "POST",
            url: "reporte_facturacion_cb.php",
            data: parametros,
           
            success:function(data){
                $("#grilla_info").html(data);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
            }
    });
 
    
    var parametros = {
        "factura_fechaIngresoSistema_desde": factura_fechaIngresoSistema_desde,
        "factura_fechaIngresoSistema_hasta": factura_fechaIngresoSistema_hasta,
        "caso_numero_desde": caso_numero_desde,
        "caso_numero_hasta": caso_numero_hasta,
        "caso_cliente_id": caso_cliente_id,
        "caso_prestador_id": caso_prestador_id,
        "facturaEstado_id": facturaEstado_id,
        "factura_numero": factura_numero,
        "opcion": 'grilla_listar'
    };
    $.ajax({
            dataType: "html",
            method: "POST",
            url: "reporte_facturacion_cb.php",
            data: parametros,
           
            success:function(data){
                $("#grilla_facturas").html(data);
                
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
            }
    });
 };
 
 
//Hace arrancar la lista
var listar = function(){

    var table = $("#dt_facturas").DataTable({
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
            url: "reporte_facturacion_cb.php",
            dataType: "json",
            data: $(form).serialize(),
            complete:
            function () {
                window.location = "reporte_facturacion_cb.php";
            }

    });

 };

