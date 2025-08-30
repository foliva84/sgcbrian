//Verifica que se halla cargado la página para ejecutar las funciones.		
$().ready(function(){
      
     // Funciones que se van a ejecutar en la primer carga de la página.
     $('#panel_formulario_voucher').slideUp();
     $('#panel_grilla_producto').slideUp();
     $('#panel_grilla').slideUp();
});


function grilla_producto_cerrar(){
    $('#panel_grilla_producto').slideUp();
}

function formulario_voucher_cerrar() {
    $('#panel_formulario_voucher').slideUp();
}



// Ver para botón para cerrar luego de ver el producto
$("#btn_cancelar").click(function(){
    $('#formulario_modificacion')[0].reset();
    $('#panel_formulario_modificacion').slideUp();
    $('#panel_grilla').slideDown();
});

// Formulario para mostrar el voucher
function formulario_voucher(numero_voucher){
    
    
    var sistema_emision =  $("#sistema_emision option:selected").val();
    
     var parametros = {
        "voucher_numero": numero_voucher,
        "sistema_emision": sistema_emision,
        "opcion": 'formulario_voucher'
    };
    
      $.ajax({
        dataType: "html",
        method: "POST",
        url: "ws_cb.php",
        data: parametros,
        beforeSend: function () {
           $('#panel_grilla').slideUp();
           $('#panel_grilla_producto').slideUp();
           $('#panel_formulario_voucher').slideDown();
           $("#formulario_voucher").html('<div style="text-align:center; font-size:16px; margin-top:50px;">Solicitando información <i class="fa fa-refresh"></i></div>');
        },
        success:  function (data) {
            $("#formulario_voucher").html(data);
            // Ver acá que otras acciones
           
            // Importante para que luego de la carga dinámica vuelvan a funcionar los tooltips
            $('[data-toggle="tooltip"]').tooltip();
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $("#formulario_voucher").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error interno.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });
};


// Grilla para mostrar el producto seleccionado
function grilla_producto(producto_id){
    
     var parametros = {
        "producto_id": producto_id,
        "opcion": 'grilla_producto'
    };
    
      $.ajax({
        dataType: "html",
        method: "POST",
        url: "ws_cb.php",
        data: parametros,
        beforeSend: function () {
           $('#panel_grilla').slideUp();
           $('#panel_grilla_producto').slideDown();
           $("#grilla_producto").html('<div style="text-align:center; font-size:16px; margin-top:50px;">Solicitando información <i class="fa fa-refresh"></i></div>');
        },
        success:  function (data) {
            $("#grilla_producto").html(data);
            // Ver acá que otras acciones
            // listar();  aquí no haría falta listar????   
           
            // Importante para que luego de la carga dinámica vuelvan a funcionar los tooltips
            $('[data-toggle="tooltip"]').tooltip();
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $("#grilla_producto").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error interno.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });
};


function test() {
    $.Notification.autoHideNotify('error', 'top right', 'Se está activando...', 'Reporte del error. ' + xhr.responseText);
    
}



// Grilla para ñostar ñps vouchers buscados
function grilla_listar(){
    var buscar_voucher = $("#buscar_voucher").val();
    var sistema_emision =  $("#sistema_emision option:selected").val();
    
    var parametros = {
       "buscar_voucher": buscar_voucher,
       "sistema_emision": sistema_emision,
       "opcion": 'grilla_listar'
    };
    $.ajax({
        dataType: "html",
        method: "POST",
        url: "ws_cb.php",
        data: parametros,
        beforeSend: function () {
            
           $('#panel_formulario_voucher').slideUp();
           $('#panel_grilla_producto').slideUp();
           $('#panel_grilla').slideDown();
           
           $("#grilla_voucher").html('<div style="text-align:center; font-size:16px; margin-top:50px;">Solicitando información <i class="fa fa-refresh"></i></div>');
        },
        success:  function (data) {
            $("#grilla_voucher").html(data);
            // Ver acá que otras acciones
            listar();   
            
            // Importante para que luego de la carga dinámica vuelvan a funcionar los tooltips
            $('[data-toggle="tooltip"]').tooltip();
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $("#grilla_voucher").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error interno.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
        }
    });
}


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