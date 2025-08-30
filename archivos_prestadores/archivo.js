// Función para esperar a que la página se encuentre totalmente cargada.
$(function(){
      
    grilla_listar();
     
    $(':file').on('fileselect', function(event, numFiles, label) {

        var input = $(this).parents('.input-group').find(':text'),
            log = numFiles > 1 ? numFiles + ' files selected' : label;

        if( input.length ) {
            input.val(log);
        } else {
            if( log ) alert(log);
        }


    });
    
    $(document).on('change', ':file', function() {
        var input = $(this),
            numFiles = input.get(0).files ? input.get(0).files.length : 1,
            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [numFiles, label]);
    });

    
});  
  
 

// Función anónima para identificar los elementos del DOM
function _(identificador){
	return document.getElementById(identificador);
}

// Función para subir el archivo por ajax
function uploadFile(){
	var file = _("upfile").files[0];
	
        var prestador_id_archivo = $("#prestador_id_archivo").val();
        
	var formdata = new FormData();
	formdata.append("upfile", file);
        
        formdata.append("prestador_id_archivo", prestador_id_archivo);
        
	var ajax = new XMLHttpRequest();
	ajax.upload.addEventListener("progress", progressHandler, false);
	ajax.addEventListener("load", completeHandler, false);
	ajax.addEventListener("error", errorHandler, false);
	ajax.addEventListener("abort", abortHandler, false);
	ajax.open("POST", "guarda_archivo.php");
	ajax.send(formdata);
}
function progressHandler(event){
	_("loaded_n_total").innerHTML = "Subidos "+event.loaded+" bytes de "+event.total;
	var percent = (event.loaded / event.total) * 100;
	_("progressBar").value = Math.round(percent);
	_("status").innerHTML = Math.round(percent)+"% subido... espere por favor";
}
function completeHandler(event){
	_("status").innerHTML = event.target.responseText;
	_("progressBar").value = 0;
        grilla_listar();
}
function errorHandler(event){
	_("status").innerHTML = "Falló la subida";
}
function abortHandler(event){
	_("status").innerHTML = "Subida abortada";
}




var vista_archivo = function(archivo){
    window.open("../a_edc6c6cba6ee5c14f146448b9fe908435d9b665f_repo_prestadores/" + archivo , "_blank");
};




//Desactiva un cliente - borrado lógico
$( "#formulario_baja_archivo" ).on( "submit", function(e) {
    
  e.preventDefault();
  
    var archivo_id_modal_baja = $('#archivo_id_modal_baja').val();
  
    var parametros = {
        "id_archivo_borrar": archivo_id_modal_baja,
        "opcion": 'archivo_eliminar'
    };
 
  $.ajax({
            type: "POST",
            url: "elimina_archivo.php",
            dataType:'html',
            data: parametros,
            success: function (data) {
                $("#grilla_archivos").html(data);  
                grilla_listar();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $("#grilla_archivos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error interno en el servidor.</div>');
            }
  });
    $('#ventana_modal_borrado_archivo').modal('hide');
});







// Modal para borrar archivos
var modal_borra_archivo = function(archivo_id){
    $('#ventana_modal_borrado_archivo').modal('show');
    $('#archivo_id_modal_baja').val(archivo_id);
 };




//Va a buscar los datos de la grilla
var grilla_listar = function(){
    var parametros = {
        "opcion": 'grilla_listar',
        "prestador_id_archivo": $("#prestador_id_archivo").val()
    };
    $.ajax({
            dataType: "html",
            method: "POST",
            url: "archivo_cb.php",
            data: parametros,
            beforeSend: function () {
                    $("#grilla_archivos").html('<div style="text-align:center; font-size:16px; margin-top:50px;"><img src="../assets/images/ajax_loader.gif"/></div>');
            },
            success:  function (data) {
                    $("#grilla_archivos").html(data);  
                    listar();
            },
            error: function () {
                    $("#grilla_archivos").html('<div style="text-align:center; font-size:14px; margin-top:50px;">Existió un error interno en el servidor.</div>');
            }
    });
};

//Hace arrancar la lista
var listar = function(){

    var table = $("#dt_archivos").DataTable({
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

