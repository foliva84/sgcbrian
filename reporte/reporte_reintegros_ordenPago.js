//Verifica que se halla cargado la página para ejecutar las funciones.		
$().ready(function(){
    
    $("#btn_generar_orden_pago").attr("disabled", true);

});


//Va a buscar los datos de la grilla
var grilla_listar = function(){
    
    // Inicializa el array de items seleccionados en la grilla    
    $("#rim_seleccionados").val("");
    

    var parametros = {
        "opcion": 'grilla_listar_contar'
    };
    $.ajax({
            dataType: "html",
            method: "POST",
            url: "reporte_reintegros_ordenPago_cb.php",
            data: parametros,
           
            success:function(data){
                $("#grilla_info").html(data);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
            }
    });
   

    var parametros = {
        "opcion": 'grilla_listar'
    };

    $.ajax({
            dataType: "html",
            method: "POST",
            url: "reporte_reintegros_ordenPago_cb.php",
            data: parametros,
           
            success:function(data){
                $("#reporte_reintegros_ordenPago").html(data);
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


// Exportación a Excel - función para generar el excel a exportar - Utiliza la clase y librería PHPExcel
// Funcion para la seleccion de Reintegros para generar la orden de pago
function seleccion_items() {

    let seleccionados = "";
    var contador = 0;

    $('input[name="seleccionados[]"]:checked').each(function() {
        seleccionados += $(this).val() + ",";
        contador++;
    });
    // Se elimina la última coma
    seleccionados = seleccionados.substring(0, seleccionados.length-1);
    $('#rim_seleccionados').val(seleccionados);
    $('#rim_contador').val(contador);
    
    if (contador >= 1) {
        $("#btn_generar_orden_pago").attr("disabled", false);
    } else {
        $("#btn_generar_orden_pago").attr("disabled", true);
    };
    
    var parametros = {
        "rim_seleccionados": seleccionados,
        "opcion": 'importeAprobadoARS_total'
    };
        
    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'reporte_reintegros_ordenPago_cb.php',
        data: parametros,
        success:function(data){

            var importeAprobadoARS_total = data.importeAprobadoARS_total;
            $('#importeAprobadoARS_total').val(importeAprobadoARS_total);
            
        }
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


jQuery.validator.addMethod("selectMax", function(value, element) {

        var check = false;
        console.log("contador: " + value);
        if (value <= 15) {
                check = true;
            } else {
                check = false;
            }
        return this.optional(element) || check;
    },
    "Debe seleccionar un maximo de 15 reintegros"
);

$("#formulario_reporte").validate({
        ignore: [], 
        rules:  {
            rim_seleccionados: {
                required: true
            },
            rim_contador: {
                selectMax: true
            }
        },     
        messages: {
            rim_seleccionados: {
                required: "Debe seleccionar reintegros"
            }
        }
        
 });