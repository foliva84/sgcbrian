//Verifica que se halla cargado la página para ejecutar las funciones.		
$().ready(function(){
      
    practicas_prestador_llenar();
    practicas_llenar();
    
});


    $("#practicas").change(function () {    
           $("#practica_id").val("");
    });


   

const practicas_llenar = () => {
    
   let prestador_id =  $("#prestador_id").val();
  
  
    const parametros = {
        "opcion": 'practicas_llenar',
        "prestador_id": prestador_id
    };

    let miselect = $("#practicas");

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'prestador_practica_cb.php',
        data: parametros,
        success:function(data){
            for (var i=0; i<data.length; i++) {
                miselect.append('<option value="' + data[i].practica_id + '">' + data[i].practica_nombre + '</option>');
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $.Notification.autoHideNotify('error', 'top center', 'Error...', 'Reporte del error. ' + xhr.responseText + ' ' + ajaxOptions + ' ' + thrownError);
        }
    });
    
};


const practicas_prestador_llenar = () => {
    
   let prestador_id =  $("#prestador_id").val();
  
  
    const parametros = {
        "opcion": 'practicas_prestador_llenar',
        "prestador_id": prestador_id
    };

    let miselect = $("#practicas_prestador");

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'prestador_practica_cb.php',
        data: parametros,
        success:function(data){
            for (var i=0; i<data.length; i++) {
                miselect.append('<option value="' + data[i].prestadorPractica_id + '">' + data[i].practica_nombre + '  :  ' + data[i].presuntoOrigen + '</option>');
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $.Notification.autoHideNotify('error', 'top center', 'Error...', 'Reporte del error. ' + xhr.responseText + ' ' + ajaxOptions + ' ' + thrownError);
        }
    });
    
};

const ShowModalPractices = () => {
    $("#presunto_origen_practica_prestador").val("");
    let practica = $("#practicas option:selected").text();
    
    $("#practicesModalLabel").text('Presunto origen para '+practica);
    $('#practicesModal').modal('show');
}

const setPresuntoOrigen = () => {
    let presunto = $("#presunto_origen_practica_prestador").val();
    $('#practicesModal').modal('hide');
    practicas_prestador_insertar(presunto);
}

const practicas_prestador_insertar = (presuntoOrigen) => {
    let prestador_id = $("#prestador_id").val();
    let practica_id = $("#practicas option:selected").val();
    
    if(!practica_id){
        $.Notification.autoHideNotify('error', 'top center', 'Error...', 'Debe seleccionar una práctica a su izquierda');
        return;
    }
  
    const parametros = {
        "opcion": 'practicas_prestador_insertar',
        "prestador_id": prestador_id,
        "practica_id": practica_id,
        "presuntoOrigen":presuntoOrigen
    };

    $.ajax({
        type: 'POST',
        dataType:'html',
        url: 'prestador_practica_cb.php',
        data: parametros,
        success:function(data){
            
            let resultado = data[0];
            
            console.log(resultado);
            
            if (resultado === "t") {
                 $.Notification.autoHideNotify('error', 'top center', 'Error...', 'Ya existe');
            } else {
                
                $.Notification.autoHideNotify('success', 'top center', 'Práctica agregada','Se agregó la práctica al prestador.');  
                $('#practicas_prestador').empty();
                practicas_prestador_llenar();
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $.Notification.autoHideNotify('error', 'top center', 'Error...', 'Reporte del error. ' + xhr.responseText + ' ' + ajaxOptions + ' ' + thrownError);
        }
    });
    
};



const practicas_prestador_eliminar = () => {
    
    let prestadorPractica_id = $("#practicas_prestador option:selected").val();
    
    if (!prestadorPractica_id){
        $.Notification.autoHideNotify('error', 'top center', 'Seleccione una práctica', 'para eliminar.');
        return;
    }  
    
  
  
    const parametros = {
        "opcion": 'practicas_prestador_eliminar',
        "prestadorPractica_id": prestadorPractica_id
    };

    $.ajax({
        type: 'POST',
        dataType:'html',
        url: 'prestador_practica_cb.php',
        data: parametros,
        success:function(){
            $.Notification.autoHideNotify('success', 'top center', 'Práctica eliminada','Se eliminó la práctica del prestador.');  
            $('#practicas_prestador').empty();
            practicas_prestador_llenar();
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $.Notification.autoHideNotify('error', 'top right', 'Error...', 'Reporte del error. ' + xhr.responseText + ' ' + ajaxOptions + ' ' + thrownError);
        }
    });
    
};



