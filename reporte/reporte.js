//Verifica que se halla cargado la página para ejecutar las funciones.		
$().ready(function(){
      
    // Funciones que se van a ejecutar en la primer carga de la página.
    // Agrega la informacion a los distintos Select en el buscador de casos
    
    // Select Clientes
    listar_clientes();
    // Select Productos
    listar_productos();
    // Select tipos de Asistencia
    listar_tiposAsistencia();
    // Select Usuarios
    listar_usuarios();
    // Select Prestador
    listar_prestadores();
    // Select Paises
    listar_paises();        
                           
    // Funciones de DateTimePicker
    // Date Picker
    $('#caso_fechaSiniestro_desde').datepicker();
    $('#caso_fechaSiniestro_hasta').datepicker();
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
        url: 'reporte_casosAvanzados_cb.php',
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
function listar_productos(){

    var parametros = {
        "opcion": 'listar_productos'
    };

    var miselect = $("#caso_producto_id");

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'reporte_casosAvanzados_cb.php',
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
    
// Tipos de Asistencia para el buscador de casos
function listar_tiposAsistencia(){

    var parametros = {
        "opcion": 'listar_tiposAsistencia'
    };

    var miselect = $("#caso_tipoAsistencia_id");

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'reporte_casosAvanzados_cb.php',
        data: parametros,
        success:function(data){

            miselect.append('<option value="">Seleccione un Tipo de Asistencia</option>');

            for (var i=0; i<data.length; i++) {
                miselect.append('<option value="' + data[i].tipoAsistencia_id + '">' + data[i].tipoAsistencia_nombre + '</option>');
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });
};
    
// Lista de usuarios
function listar_usuarios(){

    var parametros = {
        "opcion": 'listar_usuarios'
    };

    var miselect = $("#caso_abiertoPor_id");

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'reporte_casosAvanzados_cb.php',
        data: parametros,
        success:function(data){
            
            miselect.append('<option value="">Seleccione un usuario</option>');
            
            for (var i=0; i<data.length; i++) {
                miselect.append('<option value="' + data[i].usuario_id + '">' + data[i].usuario_nombre + ' ' + data[i].usuario_apellido + '</option>');
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });
};

// Lista de prestadores
function listar_prestadores(){

    var parametros = {
        "opcion": 'listar_prestadores'
    };

    var miselect = $("#caso_prestador_id");

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'reporte_casosAvanzados_cb.php',
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

// Paises para el select del buscador
function listar_paises(){

    var parametros = {
        "opcion": 'listar_paises'
    };

    var miselect = $("#caso_pais_id");
    
    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'reporte_casosAvanzados_cb.php',
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

// Select Dependiente de Paises > Ciudades para el Formulario de Alta de Prestador
// Esto sirve para que cuando se modifica el select de país se borre lo que está en el input de ciudad
    $("#caso_pais_id").change(function () {    
           $("#caso_ciudad_id").val("");
           $("#caso_ciudad_id_2").val("");
    });

    $('#caso_ciudad_id').autocomplete({  
        source: function( request, response ) {
                $.ajax({
                        method: "post",
                        url : 'reporte_casosAvanzados_cb.php',
                        dataType: "json",
                                data: {
                                   ciudad: request.term,
                                   opcion: 'select_ciudades',
                                   pais_id: $("#caso_pais_id option:selected").val()
                                },
                                 success: function( data ) {
                                         response( $.map( data, function( item ) {
                                                var code = item.split("|");
                                               return {
						label: code[0],
						value: code[0],
						data : item
                                                }
                                        }));
                                }
                });
        },
        autoFocus: true,
        minLength: 3,
        select: function( event, ui ) {
		var names = ui.item.data.split("|");						
		$('#caso_ciudad_id_2').val(names[1]);
		
	}		      	
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
    
    if ($('#caso_anulado').prop("checked")){
        $('#caso_anulado').val('1');
    } else {
        $('#caso_anulado').val('0');
    }
    
    let caso_numero_desde = $("#caso_numero_desde").val();
    let caso_numero_hasta = $("#caso_numero_hasta").val();
    let caso_fechaSiniestro_desde = $("#caso_fechaSiniestro_desde").val();
    let caso_fechaSiniestro_hasta = $("#caso_fechaSiniestro_hasta").val();
    let caso_cliente_id = $("#caso_cliente_id option:selected").val();
    let caso_producto_id = $("#caso_producto_id option:selected").val();
    let caso_pais_id = $("#caso_pais_id option:selected").val();
    let caso_ciudad_id = $("#caso_ciudad_id_2").val();
    let caso_tipoAsistencia_id = $("#caso_tipoAsistencia_id option:selected").val();
    let caso_abiertoPor_id = $("#caso_abiertoPor_id option:selected").val();
    let caso_agencia = $("#caso_agencia").val();
    let caso_prestador_id = $("#caso_prestador_id option:selected").val();
    let caso_anulado = $('#caso_anulado').val();
    
               
    var parametros = {
        "caso_numero_desde": caso_numero_desde,
        "caso_numero_hasta": caso_numero_hasta,
        "caso_fechaSiniestro_desde": caso_fechaSiniestro_desde,
        "caso_fechaSiniestro_hasta": caso_fechaSiniestro_hasta,
        "caso_cliente_id": caso_cliente_id,
        "caso_producto_id": caso_producto_id,
        "caso_pais_id": caso_pais_id,
        "caso_ciudad_id": caso_ciudad_id,
        "caso_tipoAsistencia_id": caso_tipoAsistencia_id,
        "caso_abiertoPor_id": caso_abiertoPor_id,
        "caso_agencia": caso_agencia,
        "caso_prestador_id": caso_prestador_id,  
        "caso_anulado": caso_anulado,
        "opcion": 'grilla_listar_contar'
    };
    $.ajax({
            dataType: "html",
            method: "POST",
            url: "reporte_casosAvanzados_cb.php",
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
        "caso_cliente_id": caso_cliente_id,
        "caso_producto_id": caso_producto_id,
        "caso_pais_id": caso_pais_id,
        "caso_ciudad_id": caso_ciudad_id,
        "caso_tipoAsistencia_id": caso_tipoAsistencia_id,
        "caso_abiertoPor_id": caso_abiertoPor_id,
        "caso_agencia": caso_agencia,
        "caso_prestador_id": caso_prestador_id,
        "caso_anulado": caso_anulado,
        "opcion": 'grilla_listar'
    };
    $.ajax({
            dataType: "html",
            method: "POST",
            url: "reporte_casosAvanzados_cb.php",
            data: parametros,
           
            success:function(data){
                $("#grilla_casos_avanzado").html(data);
                
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
            }
    });
 };
 
 
//Hace arrancar la lista
var listar = function(){

    var table = $("#dt_prestador").DataTable({
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
   
    if ($('#caso_anulado').prop("checked")){
        $('#caso_anulado').val('1');
    } else {
        $('#caso_anulado').val('0');
    }
    
    $.ajax({
            type: "POST",
            contentType: "application/json; charset=utf-8",
            url: "reporte_casosAvanzados_cb.php",
            dataType: "json",
            data: $(form).serialize(),
            complete:
            function () {
                window.location = "reporte_casosAvanzados_cb.php";
            }

    });

 };

