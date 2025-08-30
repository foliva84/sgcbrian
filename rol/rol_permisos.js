
// Función para modificar el permiso.
var permiso_modifica = function(permiso_id, rol_id){
    
    if (($('#'+permiso_id)).is(':checked')) {
       
        var parametros = {
            "permiso_id": permiso_id,
            "rol_id" : rol_id,
            "opcion": 'permiso_alta'
        }; 
        
        $.ajax({
            type: "POST",
            url: "rol_permisos_cb.php",
            dataType:'JSON',
            data: parametros,
            success: function (resultado) {
                if(resultado){
                    $.Notification.autoHideNotify('success', 'top right', 'Permiso asignado exitosamente...','Se ha asignado el permiso al Rol.');
                }else{
                    $.Notification.autoHideNotify('error', 'top right', 'Error...', 'Descripción:' + resultado);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $.Notification.autoHideNotify('error', 'top right', 'Error...', 'Reporte del error. ' + xhr.responseText);
            }
  });
       
       
   } else {
     
        var parametros = {
            "permiso_id": permiso_id,
            "rol_id" : rol_id,
            "opcion": 'permiso_baja'
        }; 
       
        $.ajax({
            type: "POST",
            url: "rol_permisos_cb.php",
            dataType:'JSON',
            data: parametros,
            success: function (resultado) {
                if(resultado){
                    $.Notification.autoHideNotify('success', 'top right', 'Permiso desasignado exitosamente...','Se ha quitado el permiso para este Rol.');
                }else{
                    $.Notification.autoHideNotify('error', 'top right', 'Error...', 'Descripción:' + resultado);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $.Notification.autoHideNotify('error', 'top right', 'Error...', 'Reporte del error. ' + xhr.responseText);
            }
  });
}
    
};




//Hace arrancar la lista
var listar = function(){

    var table = $("#dt_cliente").DataTable({
       "destroy":true,
       "language": idioma_espanol,
       "paging":   false,
       "info":     false,
       "dom": 't'
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

