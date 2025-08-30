//Verifica que se halla cargado la página para ejecutar las funciones.		
$().ready(function(){
      
    // Funciones que se van a ejecutar en la primer carga de la página.
    // Acomoda los paneles para la primer vista.
    $('#panel_buscador').slideDown();
    $('#panel_grilla').slideDown();
    $('#panel_formulario_gop').slideUp();
    
});


//Va a buscar los datos de la grilla
var grilla_listar = function(){
    
    let caso_numero = $("#caso_numero").val();
                   
    var parametros = {
        "caso_numero": caso_numero,
        "opcion": 'grilla_listar_contar'
    };
    $.ajax({
            dataType: "html",
            method: "POST",
            url: "reporte_gopEnviadas_cb.php",
            data: parametros,
           
            success:function(data){
                $("#grilla_info").html(data);
                $('#panel_grilla').slideDown();
                $('#panel_formulario_gop').slideUp();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
            }
    });
 
    
    var parametros = {
        "caso_numero": caso_numero,
        "opcion": 'grilla_listar'
    };
    $.ajax({
            dataType: "html",
            method: "POST",
            url: "reporte_gopEnviadas_cb.php",
            data: parametros,
           
            success:function(data){
                $("#grilla_gop_enviadas").html(data);
                listar();
                
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $("#avisos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error.</div><div style="text-align:center; font-size:14px; margin-top:50px;">Mensaje para desarrollo= '+xhr.responseText+'</div>');
            }
    });
 };
 
 
//Hace arrancar la lista
var listar = function(){

    var table = $("#dt_gopEnviadas").DataTable({
        "destroy":true,
        "stateSave": true,
        "bFilter": false,
        "order": [[ 0, 'desc' ]],
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


// Funcion para buscar la informacion del caso y mostrarla en pantalla para el formulario de envio GOP
function ver_gop(gop_id) {
    
    let parametros = {
        "gop_id": gop_id,
        "opcion": 'ver_gop'
    };
    
    
    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'reporte_gopEnviadas_cb.php',
        data: parametros,
        success:function(data){
            $('#numero_caso_g').val(data.gop_casoNumero);
            $('#paciente_g').val(data.gop_nombreBeneficiario);
            $('#nVoucher_g').val(data.gop_voucher);
            $('#fecha_nacimiento_g').val(data.gop_nacimientoBeneficiario);
            $('#edad_g').val(data.gop_edad);
            $('#sintomas_g').val(data.gop_sintomas);
            $('#telefono_g').val(data.gop_telefono);
            $('#pais_g').val(data.pais_nombreEspanol);
            $('#pais_id_g').val(data.gop_pais_id);
            $('#ciudad_g').val(data.ciudad_nombre);
            $('#ciudad_id_g').val(data.gop_ciudad_id);
            $('#direccion_g').val(data.gop_direccion);
            $('#cp_g').val(data.gop_cp);
            $('#hotel_g').val(data.gop_hotel);
            $('#habitacion_g').val(data.gop_habitacion);
            $('#prestador_g').val(data.prestador_nombre);
            $('#prestador_id_g').val(data.gop_prestador_id);
            $('#email_g').val(data.gop_prestadorEmail);
            $('#observaciones_g').val(data.gop_observaciones);
            
            $('#usuario_nombre_g').val(data.usuario_usuario);
            $('#fecha_g').val(data.gop_fecha);       
            
            // Acomoda los paneles
            $('#panel_buscador').slideUp();
            $('#panel_grilla').slideUp();
            $('#panel_formulario_gop').removeClass('hidden');
            $('#panel_formulario_gop').slideDown();
        }
    });
};

// boton para volver desde una vista GOP al buscador y la grilla
$("#btn_volver").click(function(){
       
    // Acomoda los paneles
    $('#panel_buscador').slideDown();
    $('#panel_grilla').slideDown();
    $('#panel_formulario_gop').slideUp();
});




// Exportación a Excel - función para generar el excel a exportar - Utiliza la clase y librería PHPExcel

var exportar_excel = function(form){
   
       
    $.ajax({
            type: "POST",
            contentType: "application/json; charset=utf-8",
            url: "reporte_gopEnviadas_cb.php",
            dataType: "json",
            data: $(form).serialize(),
            complete:
            function () {
                window.location = "reporte_gopEnviadas_cb.php";
            }

    });

 };

