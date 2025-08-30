//Verifica que se halla cargado la página para ejecutar las funciones.		
$().ready(function(){
      
    // Funciones que se van a ejecutar en la primer carga de la página.
    $('#panel_formulario_voucher').slideUp();
    $('#panel_grilla_producto').slideUp();
    $('#panel_grilla_voucher').slideUp();
     
    // Para poblar el select de sistemas de emisión en la búsqueda de vouchers
    select_sistema_emision();
     
});
   
    
// Select de los sistemas de emisión para los webservices
function select_sistema_emision(){

    var parametros = {
        "opcion": 'select_sistema_emision'
    };

    var miselect = $("#sistema_emision");

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'ws_cb.php',
        data: parametros,
        success:function(data){
          
            for (var i=0; i<data.length; i++) {
                miselect.append('<option value="' + data[i].se_id + '">' + data[i].se_nombre + '</option>');
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });  
};
    




// Ver para botón para cerrar luego de ver el producto
$("#btn_cancelar").click(function(){
        
    $('#panel_grilla_voucher').slideDown();
});



// Función para buscar un voucher
function buscar_voucher(opcion_caso){
    
    let voucher_number = $("#voucher_number").val();
    let passenger_first_name = $("#passenger_first_name").val(); 
    let passenger_last_name = $("#passenger_last_name").val(); 
    let passenger_document_number = $("#passenger_document_number").val();  
    let sistema_emision =  $("#sistema_emision option:selected").val();
    
    
    var parametros = {
        "voucher_number": voucher_number,
        "passenger_first_name": passenger_first_name, 
        "passenger_last_name": passenger_last_name, 
        "passenger_document_number": passenger_document_number, 
        "sistema_emision": sistema_emision,
        "opcion_caso": opcion_caso,
        "opcion": 'buscar_voucher'
    };
    
      $.ajax({
        dataType: "html",
        method: "POST",
        url: "ws_cb.php",
        data: parametros,
        beforeSend: function () {
           $('#panel_grilla_voucher').slideUp();
           $('#panel_grilla_producto').slideUp();
           $('#panel_formulario_voucher').slideDown();
           $("#formulario_voucher").html('<div style="text-align:center; font-size:16px; margin-top:50px;">Solicitando información <i class="fa fa-spin fa-refresh"></i></div>');
        },
        success:  function (data) {
            $("#formulario_voucher").html(data);
            // Ver acá que otras acciones
           
            // Importante para que luego de la carga dinámica vuelvan a funcionar los tooltips
            $('[data-toggle="tooltip"]').tooltip();
        },
        error: function(jqXHR, exception) {
            let tipo_error;
            if (jqXHR.status === 0) {
                tipo_error = 'No hay conección de red';
            } else if (jqXHR.status == 404) {
                tipo_error = 'No se encontró la información solicitada';
            } else if (jqXHR.status == 500) {
                tipo_error = 'Internal Server Error [500].';
            } else if (exception === 'parsererror') {
                tipo_error = 'Requested JSON parse failed.';
            } else if (exception === 'timeout') {
                tipo_error ='Time out error.';
            } else if (exception === 'abort') {
                tipo_error ='Ajax request aborted.';
            } else {
                tipo_error ='Uncaught Error.\n' + jqXHR.responseText;
            }
            
            $("#grilla_producto").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= ' + tipo_error + '</div>');
            
        }
        
        
    });
};


// Grilla para mostrar el producto seleccionado
function aagrilla_producto(producto_id,sistema_emision){
    
     var parametros = {
        "producto_id": producto_id,
        "sistema_emision": sistema_emision,
        "opcion": 'grilla_producto'
    };
    
      $.ajax({
        dataType: "html",
        method: "POST",
        url: "ws_cb.php",
        data: parametros,
        beforeSend: function () {
           $('#panel_grilla_voucher').slideUp();
           $('#panel_grilla_producto').slideDown();
           $("#grilla_producto").html('<div style="text-align:center; font-size:16px; margin-top:50px;">Solicitando información <i class="fa fa-spin fa-refresh"></i></div>');
        },
        success:  function (data) {
            $("#grilla_producto").html(data);
            // Ver acá que otras acciones
            // listar();  aquí no haría falta listar????   
           
            // Importante para que luego de la carga dinámica vuelvan a funcionar los tooltips
            $('[data-toggle="tooltip"]').tooltip();
        },
         error: function(jqXHR, exception) {
            let tipo_error;
            if (jqXHR.status === 0) {
                tipo_error = 'No hay conección de red';
            } else if (jqXHR.status == 404) {
                tipo_error = 'No se encontró la información solicitada';
            } else if (jqXHR.status == 500) {
                tipo_error = 'Internal Server Error [500].';
            } else if (exception === 'parsererror') {
                tipo_error = 'Requested JSON parse failed.';
            } else if (exception === 'timeout') {
                tipo_error ='Time out error.';
            } else if (exception === 'abort') {
                tipo_error ='Ajax request aborted.';
            } else {
                tipo_error ='Uncaught Error.\n' + jqXHR.responseText;
            }
            
            $("#grilla_producto").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= ' + tipo_error + '</div>');
            
        }
    });
};


// Muestra el voucher seleccionado
function mostrar_voucher(numero_voucher_b, sistema_emision) {
    
    var parametros = {
       "voucher_number": numero_voucher_b,
       "sistema_emision": sistema_emision,
       "opcion": 'mostrar_voucher'
    };
    
    $.ajax({
        dataType: "html",
        method: "POST",
        url: "ws_cb.php",
        data: parametros,
        beforeSend: function () {
            
           $('#panel_formulario_voucher').slideUp();
           $('#panel_grilla_producto').slideUp();
           $('#panel_grilla_voucher').slideDown();  
           $("#grilla_voucher").html('<div style="text-align:center; font-size:16px; margin-top:50px;">Solicitando información <i class="fa fa-spin fa-refresh"></i></div>');
        },
        success:  function (data) {
            $("#grilla_voucher").html(data);
            // Ver acá que otras acciones
            listar();   
            
            // Importante para que luego de la carga dinámica vuelvan a funcionar los tooltips
            $('[data-toggle="tooltip"]').tooltip();
            
            $("#btn-WS-alta-caso").attr("disabled", false);
            
        },
        error: function(jqXHR, exception) {
            let tipo_error;
            if (jqXHR.status === 0) {
                tipo_error = 'No hay conección de red';
            } else if (jqXHR.status == 404) {
                tipo_error = 'No se encontró la información solicitada';
            } else if (jqXHR.status == 500) {
                tipo_error = 'Internal Server Error [500].';
            } else if (exception === 'parsererror') {
                tipo_error = 'Requested JSON parse failed.';
            } else if (exception === 'timeout') {
                tipo_error ='Time out error.';
            } else if (exception === 'abort') {
                tipo_error ='Ajax request aborted.';
            } else {
                tipo_error ='Uncaught Error.\n' + jqXHR.responseText;
            }
            
            $("#grilla_producto").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= ' + tipo_error + '</div>');   
        }   
    });
 };


//Hace arrancar la lista
var listar = function(){
    // Importante para que luego de la carga dinámica vuelvan a funcionar los tooltips
            $('[data-toggle="tooltip"]').tooltip();
    var table = $("#dt_voucher").DataTable({
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