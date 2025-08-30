//Verifica que se halla cargado la página para ejecutar las funciones.		
$().ready(function(){
      
    // Funciones que se van a ejecutar en la primer carga de la página.
    grilla_listar();
});


//Va a buscar los datos de la grilla
var grilla_listar = function(){
    
                     
    var parametros = {
        "opcion": 'grilla_listar_contar'
    };
    $.ajax({
            dataType: "html",
            method: "POST",
            url: "reporte_fci_ingresado_cb.php",
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
            url: "reporte_fci_ingresado_cb.php",
            data: parametros,
           
            success:function(data){
                $("#grilla_itemFactura_ingresado").html(data);
                
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
            url: "reporte_fci_ingresado_cb.php",
            dataType: "json",
            data: $(form).serialize(),
            complete:
            function () {
                window.location = "reporte_fci_ingresado_cb.php";
            }

    });

 };

