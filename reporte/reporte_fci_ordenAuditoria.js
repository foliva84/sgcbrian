//Verifica que se halla cargado la página para ejecutar las funciones.		
$().ready(function(){
    
    listar_clientes();
    // Funciones que se van a ejecutar en la primer carga de la página.
    //grilla_listar();
});

// Funciones para completar los SELECT
//
// Autocomplete de prestador en buscador de facturas con orden auditoria
$('#factura_prestador_nombre_buscar').autocomplete({ 
    source: function( request, response ) {
        $.ajax({
            method: "post",
            url : '../prestador/prestador_cb.php',
            dataType: "json",
                data: {
                    prestador: request.term,
                    opcion: 'buscar_prestador_nombre'
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
            $('#factura_prestador_id_buscar').val(names[1]);
    }		      	
});
// Select Clientes
function listar_clientes(){

    var parametros = {
        "opcion": 'listar_clientes'
    };

    var miselect = $("#factura_cliente_id");

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: '../reporte/reporte_facturacion_cb.php',
        data: parametros,
        success:function(data){

            miselect.append('<option value="">Seleccione un Pagador</option>');

            for (var i=0; i<data.length; i++) {
                miselect.append('<option value="' + data[i].cliente_id + '">' + data[i].cliente_nombre + '</option>');
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });  
};


//Va a buscar los datos de la grilla
var grilla_listar = function(){
    
    let cliente_id = $("#factura_cliente_id option:selected").val();
    let prestador_id = $("#factura_prestador_id_buscar").val();
    
    if (prestador_id == "") {
        alert("Debe seleccionar un prestador");
        return true;
    }
    if (cliente_id == "") {
        alert("Debe seleccionar un pagador");
        return true;
    }
    // inicializa el array de items seleccionados en la grilla    
    $("#fci_seleccionados").val("");
    
    var parametros = {
        "cliente_id": cliente_id,
        "prestador_id": prestador_id,
        "opcion": 'grilla_listar_contar'
    };
    $.ajax({
            dataType: "html",
            method: "POST",
            url: "reporte_fci_ordenAuditoria_cb.php",
            data: parametros,
           
            success:function(data){
                $("#grilla_info").html(data);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
            }
    });
   
    var parametros = {
        "cliente_id": cliente_id,
        "prestador_id": prestador_id,
        "opcion": 'grilla_listar'
    };
    $.ajax({
            dataType: "html",
            method: "POST",
            url: "reporte_fci_ordenAuditoria_cb.php",
            data: parametros,
           
            success:function(data){
                $("#grilla_itemFactura_ordenAuditoria").html(data);
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
// Funcion para la seleccion de ITEMS de Facturas para generar orden de pago
function seleccion_items() {

    let seleccionados = "";

    $('input[name="seleccionados[]"]:checked').each(function() {
        seleccionados += $(this).val() + ","; 
    });
    // Se elimina la última coma
    seleccionados = seleccionados.substring(0, seleccionados.length-1);
    $('#fci_seleccionados').val(seleccionados);

    //console.log('resultado: ' + seleccionados);
    var parametros = {
        "fci_seleccionados": seleccionados,
        "opcion": 'importeAprobadoUSD_total'
    };
        
    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'reporte_fci_ordenAuditoria_cb.php',
        data: parametros,
        success:function(data){

            var importeAprobadoUSD_total = data.importeAprobadoUSD_total;
            //console.log('importeAprobadoUSD_total: ' + importeAprobadoUSD_total);
            $('#importeAprobadoUSD_total').val(importeAprobadoUSD_total);
            
        }
    });
};


$("#formulario_reporte").validate({
        ignore: [], 
        rules:  {
            factura_cliente_id: {
                required: true    
            },
            factura_prestador_nombre_buscar: {
                required: true
            },
            fci_seleccionados: {
                required: true
            }
        },     

        messages: {
            factura_cliente_id: {
                required: "Por favor seleccione un cliente"
            },
            factura_prestador_nombre_buscar: {
                required: "Por favor seleccione un prestador"
            },
            fci_seleccionados: {
                required: "Debe seleccionar items de facturas"
            }
        }
        
 });