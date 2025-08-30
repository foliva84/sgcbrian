//Verifica que se halla cargado la página para ejecutar las funciones.		
$().ready(function(){
      
    // Funciones que se van a ejecutar en la primer carga de la página.
        
    // Funciones de DateTimePicker
    // Date Picker
    $('#caso_fechaSiniestro_desde').datepicker();
    $('#caso_fechaSiniestro_hasta').datepicker();
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
    
    let caso_numero_desde = $("#caso_numero_desde").val();
    let caso_numero_hasta = $("#caso_numero_hasta").val();
    let caso_fechaSiniestro_desde = $("#caso_fechaSiniestro_desde").val();
    let caso_fechaSiniestro_hasta = $("#caso_fechaSiniestro_hasta").val();
      
               
    var parametros = {
        "caso_numero_desde": caso_numero_desde,
        "caso_numero_hasta": caso_numero_hasta,
        "caso_fechaSiniestro_desde": caso_fechaSiniestro_desde,
        "caso_fechaSiniestro_hasta": caso_fechaSiniestro_hasta,
        "opcion": 'grilla_listar_contar'
    };
    $.ajax({
            dataType: "html",
            method: "POST",
            url: "reporte_datosContacto_cb.php",
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
        "opcion": 'grilla_listar'
    };
    $.ajax({
            dataType: "html",
            method: "POST",
            url: "reporte_datosContacto_cb.php",
            data: parametros,
           
            success:function(data){
                $("#grilla_datosContacto").html(data);
                
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
            }
    });
 };
 
 
//Hace arrancar la lista
var listar = function(){

    var table = $("#dt_datosContacto").DataTable({
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
            url: "reporte_datosContacto_cb.php",
            dataType: "json",
            data: $(form).serialize(),
            complete:
            function () {
                window.location = "reporte_datosContacto_cb.php";
            }

    });

 };

