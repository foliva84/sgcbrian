//Verifica que se halla cargado la página para ejecutar las funciones.		
$().ready(function(){
      
    // Funciones que se van a ejecutar en la primer carga de la página.
    // Funcion para llenar con informacion las estadisticas
    estadisticas_sistema();

    grilla_listar_casos();

});


// Funcion para llenar con informacion las estadisticas en el dashboard
function estadisticas_sistema() {
    
    var parametros = {
        "opcion": 'estadisticas_sistema'
    };
    
    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'dashboard_cb.php',
        data: parametros,
        success:function(data){

            /*data.forEach(function(element) {
                console.log(element);
            });*/
            
            // CASOS
            $('#c_casos_totales').text(data[0]);
            $('#c_casos_abiertos').text(data[1]);
            $('#c_casos_simples_abiertos').text(data[2]);
            $('#c_casos_complejos_abiertos').text(data[3]);
            $('#c_casos_cerrados').text(data[4]);

            //$ingresado['cantidad'], $ingresado['total'], $pendiente_pago['cantidad'], $pendiente_pago['total'], $pagado['cantidad'], $pagado['total']

            // FACTURAS
            $('#c_facturas_total_ingresado').text(data[5]);
            $('#t_facturas_total_ingresado').text(data[6]);//.number( true, 2, '.', ',' );
            $('#c_facturas_pendiente_auditar').text(data[7]);
            $('#c_facturas_pendiente_pagar').text(data[8]);
            $('#t_facturas_pendiente_pagar').text(data[9]);//.number( true, 2, '.', ',' );
            $('#c_facturas_importe_pagado').text(data[10]);
            $('#t_facturas_importe_pagado').text(data[11]);//.number( true, 2, '.', ',' );
            
        }
    });
}


// Va a buscar los datos de la grilla
var grilla_listar_casos = function() {

    var parametros = {
        "opcion": 'listar_ultimos_n'
    };

    $.ajax({
        dataType: "html",
        method: "POST",
        url: "../caso/caso_cb.php",
        data: parametros,

        success: function(data) {
            $("#grilla_casos").html(data);
        },

        error: function(xhr, ajaxOptions, thrownError) {
            $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= ' + xhr.responseText + '</div>');
        }
    });

};